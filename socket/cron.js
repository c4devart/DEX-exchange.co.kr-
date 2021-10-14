var io = require('socket.io-client');
var request = require('request');
var cron = require('node-cron');
var mysql = require('mysql');
var dateFormat = require('dateformat');
var config = require('./config');

//------------------------------Server------------------------------//
// var socket = io.connect(config.base_url + ':' + config.port, {
// 	secure: true,
// 	reconnect: true,
// 	rejectUnauthorized: false
// });
//------------------------------ ***** ------------------------------//


//------------------------------Local------------------------------//
var socket = io.connect(config.base_url + ':' + config.port);
//------------------------------ ***** ------------------------------//


socket.on('connect', function(data) {
    socket.emit('joinbot', 'bot');
});

// var targets = config.targets;
var targets = ['BTC', 'ETH', 'SKY', 'BDR'];

var dbconnection = mysql.createConnection({
    host     : config.mysql.host,
    user     : config.mysql.username,
    password : config.mysql.password,
    database : config.mysql.database
});
dbconnection.connect();

function getUpbitOrders(){
    var options = { 
        method: 'GET',
        url: 'https://api.upbit.com/v1/orderbook',
        qs: {
            markets: 'KRW-BTC,KRW-ETH,KRW-ADX,KRW-POLY'
        } 
    };
    var order_data = [];
    request(options, function (error, response, body) {
        if (error){
            throw new Error(error);
        }else{
            var upbit_orderbook_data = JSON.parse(body);
            if(upbit_orderbook_data.error){}else{
                upbit_orderbook_data.forEach(function(value){
                    var market = value.market;
                    var temp = market.split('-');
                    var target = temp[1];
                    if(target == 'ADX'){
						target = 'SKY';
					}else if(target == 'POLY'){
						target = 'BDR';
					}
                    var date = new Date();
                    var regdate = date.getTime();
                    regdate = Math.round(regdate / 1000);
                    var orders = value.orderbook_units;
                    var count = 0;
                    orders.forEach(function(vvalue){
                        var order_buy_rate = parseFloat(vvalue.bid_price);
                        var order_buy_amount = parseFloat(vvalue.bid_size);
                        order_buy_amount = parseFloat(order_buy_amount) / 200;
                        if(order_buy_amount < 0.001) order_buy_amount = 0.001;
                        var order_buy_price = order_buy_rate * order_buy_amount;
                        order_buy_price = order_buy_price.toFixed(0);
                        var buyOrderData = {
                            'token' : 'WybFmfmfyLik4iZJlUORpGRjcCKwQW9b',
                            'order_type' : 'buy',
                            'base' : 'KRW',
                            'target' : target,
                            'order_price' : order_buy_price,
                            'order_rate' : order_buy_rate,
                            'order_amount' : order_buy_amount,
                            'exchange_fee' : 0.15
                        };
                        var jsonBuyOrderData = JSON.stringify(buyOrderData);
                        socket.emit('orderBot', jsonBuyOrderData);
                        var order_sell_rate = parseFloat(vvalue.ask_price);
                        var order_sell_amount = parseFloat(vvalue.ask_size);
                        order_sell_amount = parseFloat(order_sell_amount) / 200;
                        if(order_sell_amount < 0.001) order_sell_amount = 0.001;
                        var order_sell_price = order_sell_rate * order_sell_amount;
                        order_sell_price = order_sell_price.toFixed(0);
                        var sellOrderData = {
                            'token' : 'WybFmfmfyLik4iZJlUORpGRjcCKwQW9b',
                            'order_type' : 'sell',
                            'base' : 'KRW',
                            'target' : target,
                            'order_price' : order_sell_price,
                            'order_rate' : order_sell_rate,
                            'order_amount' : order_sell_amount,
                            'exchange_fee' : 0.15
                        };
                        var jsonSellOrderData = JSON.stringify(sellOrderData);
                        socket.emit('orderBot', jsonSellOrderData);
                    });
                });
            }
        }
    });
}

function getUpbitMarketHistory(target = 'BTC'){
	var apiTarget;
    if(target == 'SKY'){
        apiTarget = 'ADX';
    } else if(target == 'BDR'){
        apiTarget = 'POLY';
    } else {
    	apiTarget = target;
    }
    var options = { 
        method: 'GET',
        url: 'https://api.upbit.com/v1/trades/ticks',
        qs: { 
            market: 'KRW-'+apiTarget,
            count : 5
        } 
    };
    request(options, function (error, response, body) {
        if (error){
            throw new Error(error);
        }else{
            var upbit_BTC_markethistory_data = JSON.parse(body);
            if(upbit_BTC_markethistory_data.error){}else{
                upbit_BTC_markethistory_data.forEach(function(value){
                    var type = value.ask_bid;
                    if(type == "ASK"){
                        type = "sell";
                    }else{
                        type = "buy";
                    }
                    var rate = value.trade_price;
                    var volume = value.trade_volume;
                    volume = parseFloat(volume) / 200;
                    if(volume < 0.001) volume = 0.001;
                    var order_buy_price = rate * volume;
                    order_buy_price = order_buy_price.toFixed(0);
                    var orderData = {
                        'token' : 'WybFmfmfyLik4iZJlUORpGRjcCKwQW9b',
                        'order_type' : 'buy',
                        'base' : 'KRW',
                        'target' : target,
                        'order_rate' : rate,
                        'order_amount' : volume,
                        'order_price' : order_buy_price,
                        'exchange_fee' : 0.15
					};
					var jsonOrderData = JSON.stringify(orderData);
                    socket.emit('orderBot', jsonOrderData);
                });
            }
        }
    });
}

function deleteOldData(target = 'BTC', base = 'KRW'){
    var query = "SELECT COUNT(*) AS totalBuyCount FROM tb_market_order WHERE f_token='WybFmfmfyLik4iZJlUORpGRjcCKwQW9b' &&f_type='buy' && f_target='" + target + "' && f_base='" + base + "'";
    dbconnection.query(query, function (err, rows, fields) {
        if (err) throw err;
        if(rows.length > 0){
            var totalBuyCount = rows[0].totalBuyCount;
            if(totalBuyCount > 50){
                var deleteBuyCount = totalBuyCount - 50;
                var sub_query = "DELETE FROM tb_market_order WHERE f_id IN (SELECT f_id FROM (SELECT f_id FROM tb_market_order WHERE f_token='WybFmfmfyLik4iZJlUORpGRjcCKwQW9b' && f_type='buy' && f_target='" + target + "' && f_base='" + base + "' ORDER BY f_regdate ASC LIMIT 0,"+deleteBuyCount+") a )";
                dbconnection.query(sub_query, function (err, sub_rows, fields) {
                    if (err) throw err;
                });
            }
        }
    });
    query = "SELECT COUNT(*) AS totalSellCount FROM tb_market_order WHERE f_token='WybFmfmfyLik4iZJlUORpGRjcCKwQW9b' &&f_type='sell' && f_target='" + target + "' && f_base='" + base + "'";
    dbconnection.query(query, function (err, rows, fields) {
        if (err) throw err;
        if(rows.length > 0){
            var totalSellCount = rows[0].totalSellCount;
            if(totalSellCount > 50){
                var deleteSellCount = totalSellCount - 50;
                var sub_query = "DELETE FROM tb_market_order WHERE f_id IN (SELECT f_id FROM (SELECT f_id FROM tb_market_order WHERE f_token='WybFmfmfyLik4iZJlUORpGRjcCKwQW9b' && f_type='sell' && f_target='" + target + "' && f_base='" + base + "' ORDER BY f_regdate ASC LIMIT 0,"+deleteSellCount+") a )";
                dbconnection.query(sub_query, function (err, sub_rows, fields) {
                    if (err) throw err;
                });
            }
        }
    });    
}

function deleteOldMarketHistory(target = 'BTC', base = 'KRW'){
    var date = new Date();
    var current_time = date.getTime();
    current_time = Math.round(current_time / 1000);
    deleteTime = current_time - 300;
    var sub_query = "DELETE FROM tb_market_history WHERE f_id IN (SELECT f_id FROM (SELECT f_id FROM tb_market_history WHERE f_token='WybFmfmfyLik4iZJlUORpGRjcCKwQW9b' && f_target='" + target + "' && f_base='" + base + "' && f_regdate<" + deleteTime + ") a )";
    dbconnection.query(sub_query, function (err, sub_rows, fields) {
        if (err) throw err;
    });
}

function orderGenerator(target = 'BTC', base = 'KRW'){
    var orderBookData = [];
    var getSellOrders = new Promise(function(resolve, reject) {
        var sellOrdersData = [];
        var sellOrders = [];
        var getSellOrdersData = new Promise(function(resolve, reject) {
            var query = "SELECT f_rate, SUM(f_target_volume) AS sub_target_volume FROM tb_market_order WHERE f_type='sell' && f_target='" + target + "' && f_base='" + base + "' GROUP BY f_rate ORDER BY f_rate ASC limit 0, 10";
            dbconnection.query(query, function (err, rows, fields) {
                if (err) throw err;
                sellOrdersData = rows;
                resolve(sellOrdersData);
            });
        });
        getSellOrdersData.then(function(sellOrdersData) {
            if(sellOrdersData.length > 0){
                for(var i=1;i<=sellOrdersData.length;i++){   
                    var targetVolume = sellOrdersData[i-1].sub_target_volume;
                    targetVolume = targetVolume / 2;
                    targetVolume = targetVolume.toFixed(3);
                    var rate = sellOrdersData[i-1].f_rate;
                    rate = rate.toFixed(0);
                    var pushData = {
                        type : 'buy',
                        rate : rate,
                        targetVolume : targetVolume
                    };
                    orderBookData.push(pushData);
                    resolve('success');
                }
            }else{
                sellOrders = [];
            }
            resolve(sellOrders);
        });
    });
    var getBuyOrders = new Promise(function(resolve, reject) {
        var buyOrdersData = [];
        var buyOrders = [];
        var getBuyOrdersData = new Promise(function(resolve, reject) {
            var query = "SELECT f_rate, SUM(f_target_volume) AS sub_target_volume FROM tb_market_order WHERE f_type='buy' && f_target='" + target + "' && f_base='" + base + "' GROUP BY f_rate ORDER BY f_rate DESC limit 0, 10";
            dbconnection.query(query, function (err, rows, fields) {
                if (err) throw err;
                buyOrdersData = rows;
                resolve(buyOrdersData);
            });
        });
        getBuyOrdersData.then(function(buyOrdersData) {
            if(buyOrdersData.length > 0){
                for(var i=1;i<=buyOrdersData.length;i++){
                    rate = buyOrdersData[i-1].f_rate;
                    var targetVolume = buyOrdersData[i-1].sub_target_volume;
                    rate = rate.toFixed(0);
                    targetVolume = targetVolume.toFixed(3);
                    var pushData = {
                        type : 'sell',
                        rate : rate,
                        targetVolume : targetVolume
                    };
                    orderBookData.push(pushData);
                    resolve('success');
                }
            }else{
                buyOrders = [];
            }
            resolve(buyOrders);
        });
    })
    getSellOrders.then(function(sellOrders) {
        getBuyOrders.then(function(buyOrders) {
            if(orderBookData.length > 0){
                var orderNo = Math.random(1)*20;
                orderNo = orderNo.toFixed(0);
                var tempOrderData = orderBookData[orderNo];
                if(tempOrderData != undefined){
                    var type = tempOrderData.type;
                    var rate = tempOrderData.rate;
                    var volume = tempOrderData.targetVolume;
                    volume = parseFloat(volume) / 20;
                    var order_price = rate * volume;
                    order_price = order_price.toFixed(0);
                    var orderData = {
                        'token' : 'WybFmfmfyLik4iZJlUORpGRjcCKwQW9b',
                        'order_type' : type,
                        'base' : base,
                        'target' : target,
                        'order_rate' : rate,
                        'order_amount' : volume,
                        'order_price' : order_price,
                        'exchange_fee' : 0.15
                    }
                    var jsonOrderData = JSON.stringify(orderData);
                    socket.emit('orderBot', jsonOrderData);
                }                           
            }
        })
    })
}

function updateMinuteChartData(target = 'BTC', base = 'KRW'){
    var date = new Date();
    var regDate = date.getTime();
    var current_datetime = Math.round(regDate / 1000);
    var low_datetime = current_datetime - current_datetime % 60;
    var high_datetime = low_datetime + 60;
    var date = low_datetime;
    var open, close, high, low, volume;
    var past_datetime = low_datetime - 60;
    var getOpen = new Promise(function(resolve, reject) {
        var query = "SELECT f_close AS open FROM tb_market_chart WHERE f_target='" + target + "' && f_base='" + base + "' ORDER BY f_regdate DESC LIMIT 0,1";
        dbconnection.query(query, function (err, rows, fields) {
            if(rows.length == 1){
                open = rows[0].open;
                resolve(open);
            }else{
                var sub_query = "SELECT f_rate AS open FROM tb_market_history WHERE f_target='" + target + "' && f_base='" + base + "' && `f_regdate`<='" + low_datetime + "' ORDER BY f_regdate DESC, f_rate DESC, f_id DESC LIMIT 0,1";
                dbconnection.query(sub_query, function (err, rows, fields) {
                    if (err) throw err;
                    if(rows.length == 1){
                        open = rows[0].open;
                        resolve(open);
                    }else{
                        open = 0;
                        resolve(open);
                    }
                });
            }
        });
    })
    getOpen.then(function(open) {
        var getClose = new Promise(function(resolve, reject) {
            var sub_query = "SELECT f_rate AS close FROM tb_market_history WHERE f_target='" + target + "' && f_base='" + base + "' && f_regdate<=" + high_datetime + " ORDER BY f_regdate DESC, f_rate DESC, f_id DESC LIMIT 0,1";
            dbconnection.query(sub_query, function (err, sub_rows, fields) {
                if (err) throw err;
                if(sub_rows.length == 1){
                    close = sub_rows[0].close;
                }else{
                    close = open;
                }
                resolve(close);
            });
        })
        getClose.then(function(close){
            var getOtherRates = new Promise(function(resolve, reject) {
                var sub_query = "SELECT MIN(f_rate) AS low, MAX(f_rate) AS high, SUM(f_target_volume) AS volume FROM `tb_market_history` WHERE `f_base`='" + base + "' && `f_target`='" + target + "' && f_regdate>=" + low_datetime + " && f_regdate<=" + high_datetime + " ORDER BY f_regdate DESC, f_rate DESC, f_id DESC";;
                dbconnection.query(sub_query, function (err, sub_rows, fields) {
                    if (err) throw err;
                    if(sub_rows.length == 1){
                        low = sub_rows[0].low;
                        high = sub_rows[0].high;
                        volume = sub_rows[0].volume;
                        if(volume == null){
                            volume = 0;
                        }
                    }else{
                        if(open>close){
                            low = close;
                        }else{
                            low = open;
                        }
                        if(open>close){
                            high = open;
                        }else{
                            high = close;
                        }
                        volume = 0;
                    }
                    if(low<=0){
                        if(open>close){
                            low = close;
                        }else{
                            low = open;
                        }
                    }
                    if(high<=0){
                        if(open>close){
                            high = open;
                        }else{
                            high = close;
                        }
                    }
                    var otherRate = 'success';
                    resolve(otherRate);
                    var pushData = {
                        target : target,
                        base : base,
                        close : close,
                        volume : volume
                    }
                    socket.emit('updateMinuteBar', pushData);
                });
            })
            getOtherRates.then(function(otherRate) {
                var qquery = "SELECT * FROM tb_market_chart WHERE f_target='" + target + "' && f_base='" + base + "' && `f_regdate`=" + date;
                dbconnection.query(qquery, function (err, rrows, fields) {
                    var deleteQuery = "DELETE FROM tb_market_chart WHERE f_id IN (SELECT f_id FROM (SELECT f_id FROM tb_market_chart WHERE f_target='" + target + "' && f_base='" + base + "' && `f_regdate`=" + date + ") a )";
                    dbconnection.query(deleteQuery, function (err, deleteQueryrows, fields) {
                        if (err) throw err;
                        var query = "INSERT INTO tb_market_chart (f_target, f_base, f_open, f_close, f_low, f_high, f_volume, f_regdate) VALUES ('" + target + "', '" + base + "', '" + open + "', '" + close + "', '" + low + "', '" + high + "', '" + volume + "', '" + date + "')";
                        dbconnection.query(query, function (err, rows, fields) {
                            if (err) throw err;
                        });
                    });
                })
            })
        })
    })
}

function updateDailyMarketHistory(target, base){      
    var query = "SELECT * FROM tb_market WHERE f_target='"+target+"' && f_base='"+base+"'";
    dbconnection.query(query, function (err, rows, fields) {
        if (err){
            throw err;
        }else{
            var date = new Date();
            var regdate = date.getTime();
			var currentDate = Math.round(regdate / 1000);
			var regDateTime = currentDate;
            currentDate = currentDate - currentDate%86400;
            var open = rows[0].f_open;
            var close = rows[0].f_close;
            var high = rows[0].f_high;
            var low = rows[0].f_low;
            var tVolume = rows[0].f_day_target_volume;
            var bVolume = rows[0].f_day_base_volume;
            var diff = rows[0].f_diff;
            var percent = rows[0].f_percent;
            var insertQuery = "INSERT INTO tb_market_daily_history (`f_target`, `f_base`, `f_open`, `f_close`, `f_high`, `f_low`, `f_target_volume`, `f_base_volume`, `f_diff`, `f_percent`, `f_regdate`) VALUES ('"+target+"', '"+base+"', '"+open+"', '"+close+"', '"+high+"', '"+low+"', '"+tVolume+"', '"+bVolume+"', '"+diff+"', '"+percent+"', '"+currentDate+"')";
            dbconnection.query(insertQuery, function (err, resultRow, fields) {
                currentDate = dateFormat(regdate, "yyyy-mm-dd");
                var pushData = {
                    target : target,
                    base : base,
                    open : open,
                    close : close,
                    high : high,
                    low : low,
                    tVolume : tVolume,
                    bVolume : bVolume,
                    diff : diff,
                    percent : percent,
                    regDate : currentDate
                };
				socket.emit('pushDailyMarketHistoryData', pushData);
				var updateQuery = "UPDATE tb_market SET f_close='" + rows[0].f_close + "', f_open='" + rows[0].f_close + "', f_diff=0, f_percent=0, f_high='" + rows[0].f_close + "', f_low='" + rows[0].f_close + "', f_day_target_volume=0, f_day_base_volume=0, f_last_day_target_volume='" + rows[0].f_day_target_volume + "', f_last_day_base_volume='" + rows[0].f_day_base_volume + "', f_regdate='" + regDateTime + "' WHERE f_target='" + target + "' && f_base='" + base + "'";
				dbconnection.query(updateQuery, function (err, rows, fields) {

				});
            });
        }
    });
}

function airdrop(){
	var config = [];
	var getConfig = new Promise(function (resolve, reject) {
		var getConfigQuery = "SELECT * FROM tb_config";
		dbconnection.query(getConfigQuery, function (err, rows, fields) {
			if (err) {
				throw err;
			} else {
				rows.forEach(function(value){
					config[value.f_title] = value.f_value;
				});
				resolve(config);
			}
		});
	});
	getConfig.then(function (config) {
		if (config['ETH_drop'] == 1 || config['SKY_pool'] == 1) {
			var totalBaseVolume, totalProfitBaseVolume, ETHLastRate, totalProfitETHVolume, totalSKYVolume, ETHProfitUnit, userWalletData = [],
				dailySKYPoolVolume = 6314422,
				myTotalBaseVolume, effectPercent;
			var date = new Date();
			var regdate = date.getTime();
			var currentDate = Math.round(regdate / 1000);
			var lastDate = currentDate - 86400;
			var getTotalBaseVolume = new Promise(function (resolve, reject) {
				var query = "SELECT SUM(f_day_base_volume) AS totalBaseVolume FROM tb_market WHERE f_base='KRW'";
				dbconnection.query(query, function (err, rows, fields) {
					totalBaseVolume = rows[0].totalBaseVolume;
					resolve(totalBaseVolume);
				});
			});
			getTotalBaseVolume.then(function (totalBaseVolume) {
				totalProfitBaseVolume = totalBaseVolume * 0.15 / 100 * 0.7;
				var getETHLastRate = new Promise(function (resolve, reject) {
					var query = "SELECT f_close AS ETHLastRate FROM tb_market WHERE f_target='ETH' && f_base='KRW'";
					dbconnection.query(query, function (err, rows, fields) {
						if (rows.length == 0) {
							resolve(false);
						} else {
							ETHLastRate = rows[0].ETHLastRate;
							resolve(true);
						}
					});
				});
				getETHLastRate.then(function (result) {
					if (result == true) {
						totalProfitETHVolume = totalProfitBaseVolume / ETHLastRate;
						totalProfitETHVolume = totalProfitETHVolume.toFixed(8);
						totalProfitETHVolume = parseFloat(totalProfitETHVolume);
						var getTotalSKYVolume = new Promise(function (resolve, reject) {
							var query = "SELECT SUM(f_total) AS totalSKYVolume FROM tb_user_wallet WHERE f_unit='SKY'";
							dbconnection.query(query, function (err, rows, fields) {
								totalSKYVolume = rows[0].totalSKYVolume;
								resolve(totalSKYVolume);
							});
						});
						getTotalSKYVolume.then(function (totalSKYVolume) {
							var getUserWalletData = new Promise(function (resolve, reject) {
								var query = "SELECT * FROM tb_user_wallet WHERE f_unit='ETH' OR f_unit='SKY'";
								dbconnection.query(query, function (err, rows, fields) {
									if (err) {
										throw err;
									} else {
										var tokenList = [];
										for (var i = 0; i < rows.length; i++) {
											if (tokenList.includes(rows[i].f_token) == false) {
												tokenList.push(rows[i].f_token);
											}
										}
										tokenList.forEach(function (thisToken) {
											var ETHValue, SKYValue;
											rows.forEach(function (value) {
												if (value.f_token == thisToken) {
													if (value.f_unit == 'ETH') {
														ETHValue = value;
													} else if (value.f_unit == 'SKY') {
														SKYValue = value;
													}
												}
											});
											pushData = {
												token: thisToken,
												ETH: ETHValue,
												SKY: SKYValue
											};
											userWalletData.push(pushData);
										});
										resolve(userWalletData);
									}
								});
							});
							getUserWalletData.then(function (userWalletData) {
								var dropETHVolume = 0;
								userWalletData.forEach(function (value) {
									var userToken = value.token;
									var userSKYTotalBalance = value.SKY.f_total;
									var userSKYAvailableBalance = value.SKY.f_available;
									var userSkyHoldPercent = (userSKYTotalBalance / totalSKYVolume) * 100;
									var userETHProfit = userSkyHoldPercent * totalProfitETHVolume / 100;
									var userETHTotalBalance = value.ETH.f_total;
									var userUpdatedETHTotalBalance = userETHTotalBalance + userETHProfit;
									var userETHAvailableBalance = value.ETH.f_available;
									var userUpdatedETHAvailableBalance = userETHAvailableBalance + userETHProfit;
									var ETHAirdrop = new Promise(function (resolve, reject) {
										if (config['ETH_pool'] == 1) {
											if (userETHProfit > 0) {
												dropETHVolume += userETHProfit;
												var query = "UPDATE tb_user_wallet SET f_total='" + userUpdatedETHTotalBalance + "', f_available='" + userUpdatedETHAvailableBalance + "' WHERE f_token='" + userToken + "' && f_unit='ETH'";
												dbconnection.query(query, function (err, rows, fields) {
													insertQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + userToken + "', 'in', 'ETH', '" + userETHProfit + "', 'Daily airdrop', " + currentDate + ")";
													dbconnection.query(insertQuery, function (err, rows, fields) {
														insertQuery = "INSERT INTO tb_log_eth_airdrop (`f_token`, `f_day_base_volume`, `f_fee`, `f_percent`, `f_day_airdrop_base_volume`, `f_eth_rate`, `f_day_eth_volume`, `f_total_sky_volume`, `f_user_sky_balance`, `f_user_sky_hold_percent`, `f_eth_airdrop_volume`, `f_regdate`) VALUES ('" + userToken + "', '" + totalBaseVolume + "', 0.15, 70, '" + totalProfitBaseVolume + "', '" + ETHLastRate + "', '" + totalProfitETHVolume + "', '" + totalSKYVolume + "', '" + userSKYTotalBalance + "', '" + userSkyHoldPercent + "', '" + userETHProfit + "', " + currentDate + ")";
														dbconnection.query(insertQuery, function (err, rows, fields) {
															resolve('success');
														});
													});
												});
											} else {
												resolve('noProfit');
											}
										} else {
											resolve('noProfit');
										}
									});
									ETHAirdrop.then(function (result) {
										if (config['SKY_pool'] == 1) {
											var getMyTotalBaseVolume = new Promise(function (resolve, reject) {
												var query = "SELECT SUM(f_buy_sell_base_volume) AS myTotalBaseVolume FROM tb_user_wallet WHERE f_token='" + userToken + "'";
												dbconnection.query(query, function (err, rows, fields) {
													myTotalBaseVolume = rows[0].myTotalBaseVolume;
													if (myTotalBaseVolume == null) myTotalBaseVolume = 0;
													resolve(myTotalBaseVolume);
												});
											});
											getMyTotalBaseVolume.then(function (myTotalBaseVolume) {
												effectPercent = parseFloat(myTotalBaseVolume) / parseFloat(totalBaseVolume) * 100;
												SKYAirdropVolume = 6314422 * effectPercent / 100;
												var userUpdatedSKYTotalBalance = userSKYTotalBalance + SKYAirdropVolume;
												var userUpdatedSKYAvailableBalance = userSKYAvailableBalance + SKYAirdropVolume;
												if (SKYAirdropVolume > 0) {
													var logDetail = "Daily Pool";
													var insertQuery = "INSERT INTO tb_log_sky_pool (`f_token`, `f_user_day_base_volume`, `f_day_base_volume`, `f_effect_percent`, `f_daily_sky_pool_volume`, `f_user_day_sky_pool_volume`, `f_regdate`) VALUES ('" + userToken + "', '" + myTotalBaseVolume + "', '" + totalBaseVolume + "', " + effectPercent + ", 6314422, " + SKYAirdropVolume + ", " + currentDate + ")";
													dbconnection.query(insertQuery, function (err, rows, fields) {});
													var query = "UPDATE tb_user_wallet SET f_total='" + userUpdatedSKYTotalBalance + "', f_available='" + userUpdatedSKYAvailableBalance + "' WHERE f_token='" + userToken + "' && f_unit='SKY'";
													dbconnection.query(query, function (err, rows, fields) {});
													insertQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + userToken + "', 'in', 'SKY', '" + SKYAirdropVolume + "', '" + logDetail + "', " + currentDate + ")";
													dbconnection.query(insertQuery, function (err, rows, fields) {})
												}
											});
										}
									});
								});
								if (dropETHVolume > 0) {
									var query = "SELECT * FROM tb_site_profit WHERE f_unit='ETH'";
									dbconnection.query(query, function (err, rows, fields) {
										if (err) {
											throw err;
										} else {
											var siteProfit = rows[0].f_amount;
											siteProfit -= dropETHVolume;
											var updateQuery = "UPDATE tb_site_profit SET f_amount='" + siteProfit + "' WHERE f_unit='ETH'";
											dbconnection.query(updateQuery, function (err, rows, fields) {
												var insertQuery = "INSERT INTO tb_site_profit_history (`f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('out', 'ETH', '" + dropETHVolume + "', 'ETH airdrop process', " + currentDate + ")";
												dbconnection.query(insertQuery, function (err, rows, fields) {
													return;
												});
											});
										}
									});
								}
							});
						});
					}
				});
			});
		}
	});
}

cron.schedule('0 0 * * *', () => {
// cron.schedule('* * * * * *', () => {
    // airdrop();
    targets.forEach(function(target){
		updateDailyMarketHistory(target, 'KRW');
    });
}, {
    scheduled: true,
    timezone: "Asia/Seoul"
});

var timer = 0;
cron.schedule('* * * * * *', function() {
    timer++;
    if(timer % 3 == 0){
        targets.forEach(function(target){
            orderGenerator(target, 'KRW');
        });
    }
    if(timer % 20 == 0){
        getUpbitOrders();
        targets.forEach(function(target){
            getUpbitMarketHistory(target);
            deleteOldData(target, 'KRW');
        });
    }
    if(timer == 60){
        targets.forEach(function(target){
            updateMinuteChartData(target, 'KRW');
			deleteOldMarketHistory(target, 'KRW');
		});
		timer = 0;
	}
});
