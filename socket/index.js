var express = require('express');  
var app = express();  
var server = require('http').createServer(app);  
var io = require('socket.io')(server);

var mysql = require('mysql');
var dateFormat = require('dateformat');
var config = require('./config');
var bodyParser = require('body-parser');

server.listen(config.port);
var socketIdList = [];
var dbconnection = mysql.createConnection({
    host     : config.mysql.host,
    user     : config.mysql.username,
    password : config.mysql.password,
    database : config.mysql.database
});
dbconnection.connect();

app.use(bodyParser.json());

var socket_receiver_app = express();

var socket_receiver_app = express();
socket_receiver_app.use(bodyParser.json({
	limit: '50mb',
	extended: true
}));
socket_receiver_app.use(bodyParser.urlencoded({
	limit: '50mb',
	extended: true ,
	parameterLimit: 1000000
}));

var socket_receiver_server = socket_receiver_app.listen(8082, function () {
	console.log('8082 server started...');
});

function emit_order_book_data(target, base, order_book){

	var orderBookData = {
		sell: order_book.sell_order,
		buy: order_book.buy_order
	};
	io.in(target + '_' + base).emit('updateOrderBook', orderBookData);
	
	var token_volume = order_book.token_volume;
	var token_volume_status = order_book.token_volume_status;

	var emit_socket_user_list = [];
	socketIdList.forEach(function(value){
		emit_socket_user_list.push(value);
	});
	if (token_volume_status == true) {
		Object.keys(token_volume).map(function (this_token) {
			for (var i = 0; i < emit_socket_user_list.length; i++) {
				if (emit_socket_user_list[i].token === this_token) {
					io.to(emit_socket_user_list[i].socketId).emit('update_my_target_volume', token_volume[this_token]);
					emit_socket_user_list.splice(i, 1);
				}
			}
		});		
	}
	var empty_token_volume = [];
	for (var i = 1; i <= 13; i++) {
		var temp_data = {
			type: 'buy',
			target: target,
			base: base,
			no: i,
			myTargetVolume: ''
		};
		empty_token_volume.push(temp_data);
		temp_data = {
			type: 'sell',
			target: target,
			base: base,
			no: i,
			myTargetVolume: ''
		};
		empty_token_volume.push(temp_data);
	}
	emit_socket_user_list.forEach(function (value) {
		io.to(value.socketId).emit('update_my_target_volume', empty_token_volume);
	});
}

socket_receiver_app.post('/create_order', function (request, response) {

	var res = request.body.res;
	var msg = request.body.msg;
	var token = request.body.token;
	var target = request.body.target;
	var base = request.body.base;

	var my_open_orders = request.body.my_open_orders;
	var order_book = request.body.order_book;
	var my_balance = request.body.my_balance;
	var market_history = request.body.market_history;
	var order_history = request.body.order_history;
	var chart_data = request.body.chart_data;
	var daily_market_history = request.body.daily_market_history;
	var market_summaries = request.body.market_summaries;

	var return_data = {
		res: res,
		msg: msg
	};

	if (res == true) {

		Object.keys(my_open_orders).map(function (this_token) {
			socketIdList.forEach(function (value) {
				if (value.token == this_token) {
					io.to(value.socketId).emit('updateMyOpenOrderData', my_open_orders[this_token]);
				}
			});
		});
		emit_order_book_data(target, base, order_book);

		Object.keys(my_balance).map(function (this_token) {
			socketIdList.forEach(function (value) {
				if (value.token == this_token) {
					io.to(value.socketId).emit('receiveMyBalanceData', my_balance[this_token]);
				}
			});
		});

		if (market_history != '') {
			io.in(target + '_' + base).emit('updateMarketHistory', market_history);
		}

		if (order_history != '') {
			socketIdList.forEach(function (socketTokenId) {
				if (socketTokenId.token == order_history.token) {
					io.to(socketTokenId.socketId).emit('updateMyOrderHistoryData', order_history.token_data);
				} else if (socketTokenId.token == order_history.other_token) {
					io.to(socketTokenId.socketId).emit('updateMyOrderHistoryData', order_history.other_token_data);
				}
			});
		}

		if (chart_data != '') {
			io.in(target + '_' + base).emit('updateChart', chart_data);
		}

		if (daily_market_history != '') {
			io.in(target + '_' + base).emit('updateDayHistoryData', daily_market_history);
		}

		if (market_summaries != '') {
			io.emit('receiveMarketData', market_summaries);
		}

		socketIdList.forEach(function (value) {
			if (value.token == token) {
				io.to(value.socketId).emit('orderSucceed', return_data);
			}
		});
	} else {
		socketIdList.forEach(function (value) {
			if (value.token == token) {
				io.to(value.socketId).emit('orderFailed', return_data);
			}
		});
	}

	response.json({
		status: true
	});
});

socket_receiver_app.post('/edit_order', function (request, response) {

	var res = request.body.res;
	var token = request.body.token;
	var target = request.body.target;
	var base = request.body.base;
	var order_book = request.body.order_book;
	var my_balance = request.body.my_balance;
	var msg = request.body.msg;
	var return_data = {
		res: res,
		msg: msg
	};
	if (res == true) {

		socketIdList.forEach(function (value) {
			if (value.token == token) {
				io.to(value.socketId).emit('changeOrderSucceed', return_data);
				io.to(value.socketId).emit('receiveMyBalanceData', my_balance);
			}
		});
		emit_order_book_data(target, base, order_book);
		
	} else {
		socketIdList.forEach(function (value) {
			if (value.token == token) {
				io.to(value.socketId).emit('changeOrderFailed', return_data);
			}
		});
	}
	response.json({
		status: true
	});
});

socket_receiver_app.post('/cancel_order', function (request, response) {
	
	console.log('cancel--order');
	console.log(request.body.res);
	if(request.body.res == '3' || request.body.res == '4')
		console.log(request.body.blocked);
	
	var res = request.body.res;
	var token = request.body.token;
	var target = request.body.target;
	var base = request.body.base;

	var my_open_orders = request.body.my_open_orders;
	var my_balance = request.body.my_balance;
	var order_book = request.body.order_book;

	if (res == true) {
		socketIdList.forEach(function (value) {
			if (value.token == token) {
				io.to(value.socketId).emit('orderCancelled', res);
				io.to(value.socketId).emit('updateMyOpenOrderData', my_open_orders);
				io.to(value.socketId).emit('receiveMyBalanceData', my_balance);
			}
		});
		emit_order_book_data(target, base, order_book);
	}else{
		socketIdList.forEach(function (value) {
			if (value.token == token) {
				io.to(value.socketId).emit('orderCancelFailed', res);
			}
		});
	}
	response.json({
		status: true
	});
});

io.on('connection', function(socket) {

    socket.on('disconnect', function(data) {
        for( var i = 0; i < socketIdList.length; i++){ 
            if (socketIdList[i].socketId === socket.id) {
                socketIdList.splice(i, 1);
            }
		}
    });

    socket.on('joinMarket', function(socketData) {
        socket.rooms = socketData.market;
        var socketIdListPushData = {
            token : socketData.token,
            socketId : socket.id
		};
		
		// var query = "SELECT * FROM tb_user WHERE f_token='" + socketData.token+"'";
		// if (socketData.token == '') {
		// 	console.log('-------------------------------------------');
		// 	console.log('socket_ID : ' + socket.id + ' | user_INFO : guest');
		// } else {
		// 	dbconnection.query(query, function (err, rows, fields) {
		// 		if (err) {
		// 			throw err;
		// 		} else {
		// 			if (rows.length > 0) {
		// 				console.log('-------------------------------------------');
		// 				console.log('socket_ID : ' + socket.id + ' | user_INFO : ' + rows[0].f_username + ', ' + rows[0].f_email);
		// 			}
		// 		}
		// 	});
		// }
        socketIdList.push(socketIdListPushData);


	console.log('<<<<<-----------------' + socketIdList.length + ' users joint----------------->>>>>');

        socket.join(socketData.market);
    });

    socket.on('joinbot', function(value) {
        socket.rooms = 'bot';
		socket.join('bot');
	});
	
	socket.on('joinOther', function (socketData) {
		var socketIdListPushData = {
			token: socketData.token,
			socketId: socket.id
		};
        socket.rooms = socketData.page;
		socket.join(socketData.page);
		socketIdList.push(socketIdListPushData);
    });

    function emitMarketData(flag = false){
        var marketData = [];
        var getMarketData = new Promise(function(resolve, reject) {
            var query = "SELECT * FROM tb_market WHERE f_enabled=1";
            dbconnection.query(query, function (err, rows, fields) {
                if (err){
                    throw err;
                }else{
                    if(rows.length > 0){
                        rows.forEach(function(value){
                            var pushData = {
                                target : value.f_target,
                                base : value.f_base,
                                unitDecimal : value.f_decimal,
                                currentRate : value.f_close,
                                pastRate : value.f_open,
                                diff : value.f_diff,
                                percent : value.f_percent,
                                highRate : value.f_high,
                                lowRate : value.f_low,
                                dayTVolume : value.f_day_target_volume,
                                dayBVolume : value.f_day_base_volume,
                                lastTVolume : value.f_last_day_target_volume,
                                lastBVolume : value.f_last_day_base_volume,
                                tVolume : value.f_target_volume,
                                bVolume : value.f_base_volume
                            };
                            marketData.push(pushData);
                        });
                    }else{
                        marketData = [];
                    }
                }
                resolve(marketData);
            });
        });
        getMarketData.then(function(marketData) {
            if(flag == true){
                io.to(socket.id).emit('receiveMarketData', marketData);
            }else{
                io.emit('receiveMarketData', marketData);
            }
        });
    }

    function emitMyBalanceData(token){
        var balanceData = [];
        var getBalanceData = new Promise(function(resolve, reject) {
            var query = "SELECT * FROM tb_user_wallet WHERE f_token='"+token+"'";
            dbconnection.query(query, function (err, rows, fields) {
                if (err){
                    throw err;   
                }else{
                    if(rows.length > 0){
                        rows.forEach(function(value){
                            var pushData = {
                                unit : value.f_unit,
                                total : value.f_total,
                                available : value.f_available,
                                blocked : value.f_blocked,
                                buyBVolume : value.f_buy_base_volume,
                            };
                            balanceData.push(pushData);
                        });
                    }else{
                        balanceData = [];
                    }
                    resolve(balanceData);
                }
            });
        });
        getBalanceData.then(function(balanceData) {
            socketIdList.forEach(function(value){
                if(value.token == token){
                    io.to(value.socketId).emit('receiveMyBalanceData', balanceData);
                }
            });
        });
    }

    socket.on('getfavMarkets', function(token) {
        var query = "SELECT * FROM tb_market_favourite WHERE f_token='" + token + "' && f_is_fav=1";
        dbconnection.query(query, function (err, rows, fields) {
            if(rows.length > 0){
                var favMarkets = [];
                rows.forEach(function(value){
                    var pushData = {
                        target : value.f_target,
                        base : value.f_base
                    };
                    favMarkets.push(pushData);
                });
                io.to(socket.id).emit('updateMarketListByfavMarkets', favMarkets);
            }
        });
    });

    socket.on('changeFavMarket', function(value) {
        var query = "SELECT * FROM tb_market_favourite WHERE f_token='" + value.token + "' && f_target='" + value.target + "' && f_base='" + value.base + "'";
        dbconnection.query(query, function (err, rows, fields) {
            if (err) throw err;
            if(rows.length > 0){
                updateQquery = "UPDATE tb_market_favourite SET f_is_fav='" + value.status + "' WHERE f_token='" + value.token + "' && f_target='" + value.target + "' && f_base='" + value.base + "'";
                dbconnection.query(updateQquery, function (err, rows, fields) {
                    if (err) throw err;
                });
            }else{
                insertQuery = "INSERT INTO tb_market_favourite (`f_token`, `f_target`, `f_base`, `f_is_fav`) VALUES ('" + value.token + "', '" + value.target + "', '" + value.base + "', '" + value.status + "')";
                dbconnection.query(insertQuery, function (err, rows, fields) {if (err) throw err;});
            }
        });
    });

    function getCurrentDateTimeStamp() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1;
        var yyyy = today.getFullYear();
        if(dd<10) {
            dd = '0'+dd;
        }
        if(mm<10) {
            mm = '0'+mm;
        }
        today = mm + '-' + dd + '-' + yyyy + ' 00:00:00';
        var date1 = new Date(today);
        var regDate1 = date1.getTime();
        return regDate1 / 1000;
    }

    function getMyTargetVolume(myTargetVolumeData){
        socketIdList.forEach(function(value){
            var token = value.token;
            if(token != ''){
                if(myTargetVolumeData.rate == ''){
                    var pushData = {
                        type : myTargetVolumeData.type,
                        target : myTargetVolumeData.target,
                        base : myTargetVolumeData.base,
                        no : myTargetVolumeData.no,
                        myTargetVolume : ''
                    };
                    io.to(value.socketId).emit('updateOrderBookMyTargetVolume', pushData);
                }else{
                    var query = "SELECT SUM(f_target_volume) AS myTargetVolume FROM tb_market_order WHERE f_token='"+token+"' && f_type='"+myTargetVolumeData.type+"' && f_target='" + myTargetVolumeData.target + "' && f_base='" + myTargetVolumeData.base + "' && f_rate=" + myTargetVolumeData.rate;
                    dbconnection.query(query, function (err, rows, fields) {
                        if (err) throw err;
                        var myTargetVolume = rows[0].myTargetVolume;
                        if(myTargetVolume == null){
                            myTargetVolume = '';
                        }
                        var pushData = {
                            type : myTargetVolumeData.type,
                            target : myTargetVolumeData.target,
                            base : myTargetVolumeData.base,
                            no : myTargetVolumeData.no,
                            myTargetVolume : myTargetVolume
                        };
                        io.to(value.socketId).emit('updateOrderBookMyTargetVolume', pushData);
                    });
                }                
            }
        });
    }
    
    function emitOrderBookData(target, base, flag){
        var orderBookData = [];
        var lastDayRate;
        var current_date = getCurrentDateTimeStamp();
        var last_date = current_date - 86400;
        var getLastDayRate = new Promise(function(resolve, reject) {
            var query = "SELECT f_rate FROM tb_market_history WHERE f_target='" + target + "' && f_base='" + base + "' && f_regdate>=" + last_date + " && f_regdate<=" + current_date + " ORDER BY f_regdate DESC, f_id DESC LIMIT 0,1";
            dbconnection.query(query, function (err, rows, fields) {
                if (err) throw err;
                if(rows.length>0){
                    lastDayRate = rows[0].f_rate;
                    if(lastDayRate == null){
                        lastDayRate = 0;
                    }
                }else{
                    lastDayRate = 0;
                }
                resolve(lastDayRate);
            });
        });
        getLastDayRate.then(function(lastDayRate) {
            var lowestSellOrderRate = 0;
            var getSellOrders = new Promise(function(resolve, reject) {
                var sellOrdersData = [];
                var sellOrders = [];
                var getSellOrdersData = new Promise(function(resolve, reject) {
                    var query = "SELECT f_rate, SUM(f_target_volume) AS sub_target_volume, GROUP_CONCAT(f_token SEPARATOR ' ') AS token_list FROM tb_market_order WHERE f_type='sell' && f_target='" + target + "' && f_base='" + base + "' GROUP BY f_rate ORDER BY f_rate ASC limit 0, 13";
                    dbconnection.query(query, function (err, rows, fields) {
                        if (err) throw err;
                        sellOrdersData = rows;
                        resolve(sellOrdersData);
                    });
                });
                getSellOrdersData.then(function(sellOrdersData) {
					var i, pushData;
                    if(sellOrdersData.length > 0){
						lowestSellOrderRate = parseFloat(sellOrdersData[0].f_rate);
                        if(sellOrdersData.length < 13){
                            for(i=1;i<=13-sellOrdersData.length;i++){
                                pushData = {
                                    targetVolume : '',
                                    rate : '',
                                    lastDiffPercent : '',
                                    token_list : ''
                                };
                                sellOrders.push(pushData);
                                pushData = {
                                    type : 'sell',
                                    target : target,
                                    base : base,
                                    rate : '',
                                    no : i
                                };
                                getMyTargetVolume(pushData);
                            }
                        }
                        for(i=14-sellOrdersData.length;i<=13;i++){    
                            if(lowestSellOrderRate > parseFloat(sellOrdersData[13-i].f_rate)){
                                lowestSellOrderRate = parseFloat(sellOrdersData[13-i].f_rate);
							}
							var lastDiffPercent;
                            if(lastDayRate>0){
                                lastDiffPercent = (sellOrdersData[13-i].f_rate - lastDayRate) * 100 / lastDayRate;	
                            }else{
                                lastDiffPercent = 0;
                            }
                            var targetVolume = sellOrdersData[13-i].sub_target_volume;
                            targetVolume = parseFloat(targetVolume).toFixed(4);
                            lastDiffPercent = lastDiffPercent.toFixed(2);
                            var rate = sellOrdersData[13-i].f_rate;
							var token_list = sellOrdersData[13-i].token_list;
							if (target == 'SKY' || target == 'BDR') {
								rate = rate.toFixed(1);
							} else {
								rate = rate.toFixed(0);
							}
                            pushData = {
                                targetVolume : targetVolume,
                                rate : rate,
                                lastDiffPercent : lastDiffPercent,
                                token_list : token_list
                            };
                            sellOrders.push(pushData);
                            pushData = {
                                type : 'sell',
                                target : target,
                                base : base,
                                rate : rate,
                                no : i
                            };
                            getMyTargetVolume(pushData);
                        }
                    }else{
                        for(i=1;i<=13;i++){
                            pushData = {
                                targetVolume : '',
                                rate : '',
                                lastDiffPercent : '',
                                token_list : ''
                            };
                            sellOrders.push(pushData);
                            pushData = {
                                type : 'sell',
                                target : target,
                                base : base,
                                rate : '',
                                no : i
                            };
                            getMyTargetVolume(pushData);
                        }
                    }
                    resolve(sellOrders);
                });
            });
            getSellOrders.then(function(sellOrders) {
                if(lowestSellOrderRate > 0){
                    var sub_query = "DELETE FROM tb_market_order WHERE f_id IN (SELECT f_id FROM (SELECT f_id FROM tb_market_order WHERE f_token='WybFmfmfyLik4iZJlUORpGRjcCKwQW9b' && f_type='buy' && f_target='" + target + "' && f_base='" + base + "' && f_rate>="+lowestSellOrderRate+") a )";
                    dbconnection.query(sub_query, function (err, sub_rows, fields) {
                        var getBuyOrders = new Promise(function(resolve, reject) {
                            var buyOrdersData = [];
                            var buyOrders = [];
                            var getBuyOrdersData = new Promise(function(resolve, reject) {
                                var query = "SELECT f_rate, SUM(f_target_volume) AS sub_target_volume, GROUP_CONCAT(f_token SEPARATOR ' ') AS token_list FROM tb_market_order WHERE f_type='buy' && f_target='" + target + "' && f_base='" + base + "' GROUP BY f_rate ORDER BY f_rate DESC limit 0, 13";
                                dbconnection.query(query, function (err, rows, fields) {
                                    if (err) throw err;
                                    buyOrdersData = rows;
                                    resolve(buyOrdersData);
                                });
                            });
                            getBuyOrdersData.then(function(buyOrdersData) {
                                if(buyOrdersData.length > 0){
                                    for(var i=1;i<=buyOrdersData.length;i++){
										var rate = buyOrdersData[i-1].f_rate;
										var lastDiffPercent;
                                        if(lastDayRate>0){
                                            lastDiffPercent = (rate - lastDayRate) * 100 / lastDayRate ;
                                        }else{
                                            lastDiffPercent = 0;
                                        }
                                        var targetVolume = buyOrdersData[i-1].sub_target_volume;
                                        if (target == 'SKY' || target == 'BDR') {
                                        	rate = rate.toFixed(1);
                                        } else {
                                        	rate = rate.toFixed(0);
                                        }
                                        targetVolume = targetVolume.toFixed(4);
                                        lastDiffPercent = lastDiffPercent.toFixed(2);
                                        var token_list = buyOrdersData[i-1].token_list;
                                        var pushData = {
                                            rate : rate,
                                            lastDiffPercent : lastDiffPercent,
                                            targetVolume : targetVolume,
                                            token_list : token_list
                                        };
                                        buyOrders.push(pushData);
                                        pushData = {
                                            type : 'buy',
                                            target : target,
                                            base : base,
                                            rate : rate,
                                            no : i
                                        };
                                        getMyTargetVolume(pushData);
                                    }
                                    if(buyOrdersData.length<13){
                                        for(var i=buyOrdersData.length+1;i<=13;i++){
                                            var pushData = {
                                                rate : '',
                                                lastDiffPercent : '',
                                                targetVolume : '',
                                                token_list : ''
                                            };
                                            buyOrders.push(pushData);
                                            pushData = {
                                                type : 'buy',
                                                target : target,
                                                base : base,
                                                rate : '',
                                                no : i
                                            };
                                            getMyTargetVolume(pushData);
                                        }
                                    }
                                }else{
                                    for(var i=1;i<=13;i++){
                                        var pushData = {
                                            rate : '',
                                            lastDiffPercent : '',
                                            targetVolume : '',
                                            token_list : ''
                                        };
                                        buyOrders.push(pushData);
                                        pushData = {
                                            type : 'buy',
                                            target : target,
                                            base : base,
                                            rate : '',
                                            no : i
                                        };
                                        getMyTargetVolume(pushData);
                                    }
                                }
                                resolve(buyOrders);
                            });
                        });  
                        getBuyOrders.then(function(buyOrders) {
                            var orderBookData = {
                                sell : sellOrders,
                                buy : buyOrders
                            };
							if(flag == true){
								io.to(socket.id).emit('updateOrderBook', orderBookData);
							}else{
								io.in(target+'_'+base).emit('updateOrderBook', orderBookData);
							}
                        });
                    });
                }else{
                    var getBuyOrders = new Promise(function(resolve, reject) {
                        var buyOrdersData = [];
                        var buyOrders = [];
                        var getBuyOrdersData = new Promise(function(resolve, reject) {
                            var query = "SELECT f_rate, SUM(f_target_volume) AS sub_target_volume, GROUP_CONCAT(f_token SEPARATOR ' ') AS token_list FROM tb_market_order WHERE f_type='buy' && f_target='" + target + "' && f_base='" + base + "' GROUP BY f_rate ORDER BY f_rate DESC limit 0, 13";
                            dbconnection.query(query, function (err, rows, fields) {
                                if (err) throw err;
                                buyOrdersData = rows;
                                resolve(buyOrdersData);
                            });
                        });
                        getBuyOrdersData.then(function(buyOrdersData) {
                            if(buyOrdersData.length > 0){
                                for(var i=1;i<=buyOrdersData.length;i++){
                                    var rate = buyOrdersData[i-1].f_rate;
									var lastDiffPercent = 0;
                                    if(lastDayRate>0){
                                        lastDiffPercent = (rate - lastDayRate) * 100 / lastDayRate ;
                                    }
                                    var targetVolume = buyOrdersData[i-1].sub_target_volume;
                                    if (target == 'SKY' || target == 'BDR') {
                                    	rate = rate.toFixed(1);
                                    } else {
                                    	rate = rate.toFixed(0);
                                    }
                                    targetVolume = targetVolume.toFixed(4);
                                    lastDiffPercent = lastDiffPercent.toFixed(2);
                                    var token_list = buyOrdersData[i-1].token_list;
                                    var pushData = {
                                        rate : rate,
                                        lastDiffPercent : lastDiffPercent,
                                        targetVolume : targetVolume,
                                        token_list : token_list
                                    };
                                    buyOrders.push(pushData);
                                    pushData = {
                                        type : 'buy',
                                        target : target,
                                        base : base,
                                        rate : rate,
                                        no : i
                                    };
                                    getMyTargetVolume(pushData);
                                }
                                if(buyOrdersData.length<13){
                                    for(var i=buyOrdersData.length+1;i<=13;i++){
                                        var pushData = {
                                            rate : '',
                                            lastDiffPercent : '',
                                            targetVolume : '',
                                            token_list : ''
                                        };
                                        buyOrders.push(pushData);
                                        buyOrders.push(pushData);
                                        pushData = {
                                            type : 'buy',
                                            target : target,
                                            base : base,
                                            rate : '',
                                            no : i
                                        };
                                        getMyTargetVolume(pushData);
                                    }
                                }
                            }else{
                                for(var i=1;i<=13;i++){
                                    var pushData = {
                                        rate : '',
                                        lastDiffPercent : '',
                                        targetVolume : '',
                                        token_list : ''
                                    };
                                    buyOrders.push(pushData);
                                    pushData = {
                                        type : 'buy',
                                        target : target,
                                        base : base,
                                        rate : '',
                                        no : i
                                    };
                                    getMyTargetVolume(pushData);
                                }
                            }
                            resolve(buyOrders);
                        });
                    });
                    getBuyOrders.then(function(buyOrders) {

                        var orderBookData = {
                            sell : sellOrders,
                            buy : buyOrders
                        };
                        if(flag == true){
                            io.to(socket.id).emit('updateOrderBook', orderBookData);
                        }else{
							io.in(target+'_'+base).emit('updateOrderBook', orderBookData);
                        }
                    });
                }
            });
        });
    }

    function emitMyOpenOrderData(token, target, base){
        var myOpenOrderData = [];
        var emitMyOpenOrderData = new Promise(function(resolve, reject) {
            var query = "SELECT * FROM tb_market_order WHERE `f_token`='" + token + "' && `f_base`='" + base + "' && `f_target`='" + target + "' ORDER BY f_regdate DESC, f_id DESC LIMIT 0,50";
            dbconnection.query(query, function (err, rows, fields) {
                if (err) throw err;
                if(rows.length>0){
                    rows.forEach(function(value){
                        var id = value.f_id;
                        var type = value.f_type;
                        var rate = value.f_rate;
                        var targetVolume = value.f_target_volume;
                        var originalTargetVolume = value.f_original_target_volume;
						var regdate = value.f_regdate;
						if (target == 'SKY') {
							rate = rate.toFixed(1);
						} else if(target == 'BDR') {
                            rate = rate.toFixed(1);
                        } else {
							rate = rate.toFixed(0);
						}
                        targetVolume = targetVolume.toFixed(3);
                        if(originalTargetVolume == null){
                            originalTargetVolume = targetVolume;
                        }else{
                            originalTargetVolume = originalTargetVolume.toFixed(3);
                        }
                        var formattedDate = new Date(regdate*1000);
                        var socketDataRegdate = dateFormat(formattedDate, "yyyy-mm-dd HH:MM:ss");
                        var pushData = {
                            id : id,
                            target : target,
                            base : base,
                            type : type,
                            rate : rate,
                            originalTargetVolume : originalTargetVolume,
                            targetVolume : targetVolume,
                            regdate : socketDataRegdate
                        };
                        myOpenOrderData.push(pushData);
                    });
                }
                resolve(myOpenOrderData);
            });
        });
        emitMyOpenOrderData.then(function(myOpenOrderData) {
            socketIdList.forEach(function(value){
                if(value.token == token){
                    io.to(value.socketId).emit('updateMyOpenOrderData', myOpenOrderData);
                }
            });
        });
    }
    
    function updateChartData(target, base, close, volume){
        var date = new Date();
        var time = date.getTime();
        var pushData = {
            target : target,
            base : base,
            time : time,
            close : close,
            volume : volume
        };
        if(socket.rooms == 'bot'){
            io.in(target+'_KRW').emit('updateChart', pushData);
        }else{
            io.in(socket.rooms).emit('updateChart', pushData);
        }
    }

    socket.on('updateMinuteBar', function(data) {
        var date = new Date();
        var time = date.getTime();
        var target = data.target;
        var pushData = {
            target : data.target,
            base : data.base,
            time : time,
            close : data.close,
            volume : data.volume
        };
        if(socket.rooms == 'bot'){
            io.in(data.target+'_KRW').emit('updateChartMinBar', pushData);
        }else{
            io.in(socket.rooms).emit('updateChartMinBar', pushData);
        }
    });

    socket.on('pushDailyMarketHistoryData', function(data) {
        if(socket.rooms == 'bot'){
            io.in(data.target+'_KRW').emit('updateDayHistoryData', data);
        }else{
            io.in(socket.rooms).emit('updateDayHistoryData', data);
        }
    });

    function updateMarketInformation(target, base, rate, tVolume, bVolume, regDate){
        query =  "SELECT * FROM `tb_market` WHERE `f_target`='"+target+"' && `f_base`='"+base+"'";
        var marketInfo;
        var getMarketInfo = new Promise(function(resolve, reject) {                        
            dbconnection.query(query, function (err, rows, fields) {
                if (err){
                    throw err;  
                }else{
                    marketInfo = rows[0];
                    resolve(marketInfo);
                }
            });
        });
        getMarketInfo.then(function(marketInfo){
            var crate = rate;
            var prate = marketInfo.f_open;
            var currentDate = getCurrentDateTimeStamp();
            if(marketInfo.f_regdate<currentDate){
                prate = marketInfo.f_close;
            }
            var diff = crate - prate;
            var percent = marketInfo.f_percent;
            if(prate > 0){
                percent = diff/prate*100;
            }else{
                percent = 0;
            }
            var hrate = marketInfo.f_high;
            if(marketInfo.f_regdate<currentDate){
                hrate = rate;
            }
            if(rate > hrate){
                hrate = rate;
            }
            var lrate = marketInfo.f_low;
            if(marketInfo.f_regdate<currentDate){
                lrate = rate;
            }
            if(lrate == 0) lrate = rate;
            if(rate < lrate){
                lrate = rate;
            }
            var dtvolume = marketInfo.f_day_target_volume;
            if(marketInfo.f_regdate<currentDate){
                dtvolume = tVolume;
            }else{
                dtvolume += tVolume;
            }
            var dbvolume = marketInfo.f_day_base_volume;
            if(marketInfo.f_regdate<currentDate){
                dbvolume = bVolume;
            }else{
                dbvolume += bVolume;
            }
            var ltvolume = marketInfo.f_last_day_target_volume;
            if(marketInfo.f_regdate<currentDate){
                ltvolume = marketInfo.f_day_target_volume;
            }
            var lbvolume = marketInfo.f_last_day_base_volume;
            if(marketInfo.f_regdate<currentDate){
                lbvolume = marketInfo.f_day_base_volume;
            }
            var totalTargetVolume = marketInfo.f_target_volume + tVolume;
            var totalBaseVolume = marketInfo.f_base_volume + bVolume;
            var updateQquery = "UPDATE tb_market SET f_close='"+crate+"', f_open='"+prate+"', f_diff='"+diff+"', f_percent='"+percent+"', f_high='"+hrate+"', f_low='"+lrate+"', f_day_target_volume='"+dtvolume+"', f_day_base_volume='"+dbvolume+"', f_last_day_target_volume='"+ltvolume+"', f_last_day_base_volume='"+lbvolume+"', f_target_volume='"+totalTargetVolume+"', f_base_volume='"+totalBaseVolume+"', f_regdate='"+regDate+"' WHERE f_target='"+target+"' && f_base='"+base+"'";
            dbconnection.query(updateQquery, function (err, rows, fields) {
                if (err)  {
                    updateMarketInformation(target, base, rate, tVolume, bVolume, regDate);
                    return;
                }else{
                    var date = new Date();
                    var regdate = date.getTime();
                    currentDate = dateFormat(regdate, "yyyy-mm-dd");
                    var pushData = {
                        target : target,
                        base : base,
                        open : prate,
                        close : crate,
                        high : hrate,
                        low : lrate,
                        tVolume : dtvolume,
                        bVolume : dbvolume,
                        diff : diff,
                        percent : percent,
                        regDate : currentDate
                    };
                    if(socket.rooms == 'bot'){
                        io.in(target+'_KRW').emit('updateDayHistoryData', pushData);
                    }else{
                        io.in(socket.rooms).emit('updateDayHistoryData', pushData);
                    }
                    emitMarketData();
                    return;
                }
            });
        });
	}
	
	function updateSiteProfit(unit, volume, regDate){
		var query = "SELECT * FROM tb_site_profit WHERE f_unit='" + unit + "'";
		dbconnection.query(query, function (err, rows, fields) {
			if (err){
				throw err;  
			}else{
				var siteProfit = rows[0].f_amount;
				var newProfit = volume * 0.15/100;
				siteProfit += newProfit;
				var updateQuery = "UPDATE tb_site_profit SET f_amount='"+siteProfit+"' WHERE f_unit='"+unit+"'";
				dbconnection.query(updateQuery, function (err, rows, fields) {
					var insertQuery = "INSERT INTO tb_site_profit_history (`f_type`, `f_unit`, `f_volume`, `f_fee`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('in', '" + unit + "', '" + volume + "', 0.15, '" + newProfit + "', 'order process', '" + regDate + "')";
					dbconnection.query(insertQuery, function (err, rows, fields) {
						return;
					});
				});
			}
		});
		return;
	}

    function recordMarketHistory(token, type, target, base, tVolume, rate, bVolume, regDate, oToken){
        var insertQuery = "INSERT INTO tb_market_history (`f_token`, `f_type`, `f_target`, `f_base`, `f_target_volume`, `f_rate`, `f_base_volume`, `f_regdate`, `f_otoken`) VALUES ('" + token + "', '" + type + "', '" + target + "', '" + base + "', '" + tVolume + "', '" + rate + "', '" + bVolume + "', '" + regDate + "', '" + oToken + "')";
        dbconnection.query(insertQuery, function (err, rows, fields) {
            if (err)  {
                recordMarketHistory(token, type, target, base, tVolume, rate, bVolume, regDate, oToken);
                return;
            }else{
                updateChartData(target, base, rate, tVolume);
                var tempRegDate = dateFormat(regDate*1000, "yyyy-mm-dd HH:MM:ss");
                var pushData = {
                    target : target,
                    base : base,
                    regdate : tempRegDate,
                    type : type,
                    targetVolume : tVolume,
                    rate : rate,
                    baseVolume : bVolume
                };
                socketIdList.forEach(function(socketTokenId){
                    if(socketTokenId.token == token){
                        io.to(socketTokenId.socketId).emit('updateMyOrderHistoryData', pushData);
                    }
				});
				var tempType;
                if(type == 'buy'){
                    tempType = 'sell';
                }else{
                    tempType = 'buy';
                }
                pushData = {
                    target : target,
                    base : base,
                    regdate : tempRegDate,
                    type : tempType,
                    targetVolume : tVolume,
                    rate : rate,
                    baseVolume : bVolume
                };
                socketIdList.forEach(function(socketTokenId){
                    if(socketTokenId.token == oToken){
                        io.to(socketTokenId.socketId).emit('updateMyOrderHistoryData', pushData);
                    }
                });
                updateMarketInformation(target, base, rate, tVolume, bVolume, regDate);
                return;
            }
        });
    }

    function updateWallet(thisToken, target, base, updateData) {
        var date = new Date();
        var regDate = date.getTime();
        regDate = Math.round(regDate / 1000);
        updateData.target.f_total = updateData.target.f_available + updateData.target.f_blocked;
        updateData.base.f_total = updateData.base.f_available + updateData.base.f_blocked;
        var query = "UPDATE tb_user_wallet SET f_total='" + updateData.target.f_total + "', f_available='" + updateData.target.f_available + "', f_blocked='" + updateData.target.f_blocked + "', f_buy_volume='" + updateData.target.f_buy_volume + "', f_buy_base_volume='" + updateData.target.f_buy_base_volume + "', f_sell_volume='" + updateData.target.f_sell_volume + "', f_sell_base_volume='" + updateData.target.f_sell_base_volume + "', f_buy_sell_base_volume='" + updateData.target.f_buy_sell_base_volume + "' WHERE f_token='" + thisToken + "' && f_unit='" + target + "'";
        dbconnection.query(query, function (err, rows, fields) {
            if (err) {
                updateWallet(thisToken, target, base, updateData);
                return;
            }else{
                query = "UPDATE tb_user_wallet SET f_total='" + updateData.base.f_total + "', f_available='" + updateData.base.f_available + "', f_blocked='" + updateData.base.f_blocked + "', f_buy_volume='" + updateData.base.f_buy_volume + "', f_buy_base_volume='" + updateData.base.f_buy_base_volume + "', f_sell_volume='" + updateData.base.f_sell_volume + "', f_sell_base_volume='" + updateData.base.f_sell_base_volume + "', f_buy_sell_base_volume='" + updateData.base.f_buy_sell_base_volume + "' WHERE f_token='" + thisToken + "' && f_unit='" + base + "'";
                dbconnection.query(query, function (err, rows, fields) {
                    if (err) {
                        updateWallet(thisToken, target, base, updateData);
                        return;
                    } else {
                    	var newAvailableTargetVolume, newBlockedTargetVolume, newAvailableBaseVolume, newBlockedBaseVolume, newTargetVolume, newBaseVolume;
						if (updateData.newAvailableTargetVolume > 0) {
							newAvailableTargetVolume = updateData.newAvailableTargetVolume;
							query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + thisToken + "', 'enabled', '" + target + "', '" + newAvailableTargetVolume + "', 'create order', '" + regDate + "')";
							dbconnection.query(query, function (err, rows, fields) {});
						}
						if(updateData.newBlockedTargetVolume > 0){
							newBlockedTargetVolume = updateData.newBlockedTargetVolume;
							query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + thisToken + "', 'blocked', '" + target + "', '" + newBlockedTargetVolume + "', 'create order', '" + regDate + "')";
							dbconnection.query(query, function (err, rows, fields) {});
						}
						if(updateData.newAvailableBaseVolume > 0){
							newAvailableBaseVolume = updateData.newAvailableBaseVolume;
							query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + thisToken + "', 'enabled', '" + base + "', '" + newAvailableBaseVolume + "', 'create order', '" + regDate + "')";
							dbconnection.query(query, function (err, rows, fields) {});
						}
						if(updateData.newBlockedBaseVolume > 0){
							newBlockedBaseVolume = updateData.newBlockedBaseVolume;
							query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + thisToken + "', 'blocked', '" + base + "', '" + newBlockedBaseVolume + "', 'create order', '" + regDate + "')";
							dbconnection.query(query, function (err, rows, fields) {});
						}
						if (updateData.newTargetVolume > 0) {
							newTargetVolume = updateData.newTargetVolume;
							query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + thisToken + "', 'in', '" + target + "', '" + newTargetVolume + "', 'create order', '" + regDate + "')";
							dbconnection.query(query, function (err, rows, fields) {});
						} else if (updateData.newTargetVolume < 0) {
							newTargetVolume = updateData.newTargetVolume * (-1);
							query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + thisToken + "', 'out', '" + target + "', '" + newTargetVolume + "', 'create order', '" + regDate + "')";
							dbconnection.query(query, function (err, rows, fields) {});
						}
						if (updateData.newBaseVolume > 0) {
							newBaseVolume = updateData.newBaseVolume;
							query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + thisToken + "', 'in', '" + base + "', '" + newBaseVolume + "', 'create order', '" + regDate + "')";
							dbconnection.query(query, function (err, rows, fields) {});
						} else if (updateData.newBaseVolume < 0) {
							newBaseVolume = updateData.newBaseVolume * (-1);
							query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + thisToken + "', 'out', '" + base + "', '" + newBaseVolume + "', 'create order', '" + regDate + "')";
							dbconnection.query(query, function (err, rows, fields) {});
						}
                        emitMyBalanceData(thisToken);
                        return;
                    }
                });
            }
        });
    }

    socket.on('createOrder', function(jsonOrderData) {
		var orderData = JSON.parse(jsonOrderData);
        var token = orderData.token;
		var type = orderData.order_type;
		var target = orderData.target;
        var base = orderData.base;
        var targetVolume = parseFloat(orderData.order_amount);
        var originalTargetVolume = targetVolume;
        var rate = parseFloat(orderData.order_rate);
		var baseVolume = parseFloat(orderData.order_price);
        var fee = parseFloat(orderData.exchange_fee);
        var returnData = {
            res : true,
            msg : 'order_succeed'
        };
        var status = true;
        var diff = 0.001;
        var date = new Date();
        var regDate = date.getTime();
        regDate = Math.round(regDate / 1000);
        var balanceData = [], pushData;
        var getBalanceData = new Promise(function(resolve, reject) {
            var query = "SELECT * FROM tb_user_wallet WHERE f_token='" + token + "' AND (f_unit='"+target+"' OR f_unit='"+base+"')";
            dbconnection.query(query, function (err, rows, fields) {
                if (err){
                    throw err;  
                }else{
                    if(rows.length > 0){
                        var targetValue, baseValue;
                        rows.forEach(function(value){
                            if(value.f_unit == target){
                                targetValue = value;
                            }else if(value.f_unit == base){
                                baseValue = value;
                            }
                        });
                        pushData = {
                            target : targetValue,
							base : baseValue,
							newAvailableTargetVolume : 0,
							newBlockedTargetVolume : 0,
							newAvailableBaseVolume : 0,
							newBlockedBaseVolume: 0,
							newTargetVolume: 0,
							newBaseVolume: 0
                        };
                        balanceData[token] = pushData;
                    }
                    resolve(balanceData);
                }
            });
        });
        getBalanceData.then(function(balanceData) {
            if(type == 'buy'){
                if(balanceData[token].base.f_available<baseVolume){
                    returnData = {
                        res : false,
                        msg : 'less_base_balance'
                    };
                    status = false;
                }
            } else{
                if(balanceData[token].target.f_available<targetVolume){
                    returnData = {
                        res : false,
                        msg : 'less_target_balance'
                    };
                    status = false;
                }
            }
            if(targetVolume<diff){
                returnData = {
                    res : false,
                    msg : 'less_order_volume'
                };
                status = false;
            }
            if(status == true){
                var orders = [], socketMarketHistoryData = [];
                var processOrder = new Promise(function(resolveProcessOrder, reject) {
					var query, tempType;
                    if(type == 'buy'){
                        query =  "SELECT * FROM `tb_market_order` WHERE `f_type`='sell' && `f_target`='" + target + "' && `f_base`='" + base + "' && `f_rate`<='" + rate + "' ORDER BY f_rate ASC, f_regdate ASC";
                        tempType = 'sell';
                    }else{
                        query =  "SELECT * FROM `tb_market_order` WHERE `f_type`='buy' && `f_target`='" + target + "' && `f_base`='" + base + "' && `f_rate`>='" + rate + "' ORDER BY f_rate DESC, f_regdate ASC";
                        tempType = 'buy';
                    }
                    var getOrders = new Promise(function(resolve, reject) {                        
                        dbconnection.query(query, function (err, rows, fields) {
                            if (err) throw err;
                            orders = rows;
                            resolve(orders);
                        });
                    });
                    var insertQuery, updateQquery, deleteQuery, select_query;
                    getOrders.then(function(orders) {
                        if(orders.length > 0){
                            select_query = "SELECT * FROM tb_user_wallet WHERE f_token='" + orders[0].f_token + "'";
                            for(var i = 1; i < orders.length; i ++)
                                select_query += " or f_token='" + orders[i].f_token + "'";
                        }else {
                            select_query = "SELECT * FROM tb_user_wallet WHERE f_token='NAN'";        
                        }
                        select_query += " AND (f_unit='"+target+"' OR f_unit='"+base+"')";
                        var tokenList = [];
                        var getOtherBalanceData = new Promise(function(resolve, reject) {
                            dbconnection.query(select_query, function (err, rows, fields) {
                                if (err){
                                    throw err;
                                }else{
                                    for(var i = 0; i < rows.length; i ++) {
                                        if(rows[i].f_token != token){
                                            if(tokenList.includes(rows[i].f_token) == false){
                                                tokenList.push(rows[i].f_token);
                                            }
                                        }
                                    }
                                    tokenList.forEach(function(thisToken){
										var tempTargetValue, tempBaseValue;
                                        rows.forEach(function(value){
											if(value.f_token == thisToken){
												if(value.f_unit == target){
													tempTargetValue = value;
												}else if(value.f_unit == base){
													tempBaseValue = value;
												}
											}
                                        });
                                        pushData = {
                                            target : tempTargetValue,
                                            base : tempBaseValue,
											newAvailableTargetVolume : 0,
											newBlockedTargetVolume : 0,
											newAvailableBaseVolume : 0,
											newBlockedBaseVolume: 0,
											newTargetVolume: 0,
											newBaseVolume: 0
                                        };
                                        balanceData[thisToken] = pushData;
                                    });
                                    resolve(balanceData);   
                                }
                            });        
                        });
                        getOtherBalanceData.then(function(balanceData) {
                            if(orders.length > 0){
                                for (var i=0; i<orders.length; i++) {
                                    var order = orders[i];                                    
                                    // change target currency value
                                    if(targetVolume - order.f_target_volume > diff){
                                        var processVolume = order.f_target_volume;
                                        nextTargetVolume = targetVolume - order.f_target_volume;
                                        var tempBaseVolume = order.f_rate * processVolume;
                                        //delete this order
                                        var deleteQuery = "DELETE FROM tb_market_order WHERE f_id='" + order.f_id + "'";
                                        dbconnection.query(deleteQuery, function (err, rows, fields) {
                                            if (err){
                                                throw err;
                                            }else{
                                                emitOrderBookData(target, base, false);
                                                emitMyOpenOrderData(order.f_token, target, base);
                                            }
                                        });
                                    }else if(order.f_target_volume - targetVolume > diff){
                                        var processVolume = targetVolume;
                                        nextTargetVolume = 0;
                                        var tempBaseVolume = order.f_rate * processVolume;
                                        //update order
                                        var update_data = [];
                                        update_data['f_target_volume'] = order.f_target_volume - targetVolume;
                                        update_data['f_base_volume'] = order.f_base_volume - tempBaseVolume;
                                        updateQquery = "UPDATE tb_market_order SET f_target_volume='" + update_data['f_target_volume'] + "', f_base_volume='" + update_data['f_base_volume'] + "' WHERE f_id='" + order.f_id + "'";
                                        dbconnection.query(updateQquery, function (err, rows, fields) {
                                            if (err){
                                                throw err;
                                            }else{
                                                emitOrderBookData(target, base, false);
                                                emitMyOpenOrderData(order.f_token, target, base);
                                            }
                                        });
                                    }else{
                                        var processVolume = targetVolume;
                                        nextTargetVolume = 0;
                                        var tempBaseVolume = order.f_rate * processVolume;
                                        //delete this order
                                        var deleteQuery = "DELETE FROM tb_market_order WHERE f_id='" + order.f_id + "'";
                                        dbconnection.query(deleteQuery, function (err, rows, fields) {
                                            if (err){
                                                throw err;
                                            }else{
                                                emitOrderBookData(target, base, false);
                                                emitMyOpenOrderData(order.f_token, target, base);
                                            }
                                        });
									}
									
                                    //record to market history
                                    recordMarketHistory (token, type, target, base, processVolume, order.f_rate, tempBaseVolume, regDate, order.f_token);
                                    pushData = {
                                        rate : order.f_rate,
                                        targetVolume : processVolume,
                                        baseVolume : tempBaseVolume,
                                        regdate : regDate,
                                        type : type
                                    };
									socketMarketHistoryData.push(pushData);

                                    //change user balance data
                                    if(type == 'buy'){
                                        var earnTargetVolume = processVolume*(1-fee/100);
                                        var payBaseVolume = tempBaseVolume;

                                        balanceData[token].target.f_available += earnTargetVolume;
                                        balanceData[token].target.f_buy_base_volume += payBaseVolume;
                                        balanceData[token].target.f_buy_volume += earnTargetVolume;
										balanceData[token].base.f_available -= payBaseVolume;
										balanceData[token].base.f_buy_sell_base_volume += payBaseVolume;

										balanceData[token].newAvailableTargetVolume += earnTargetVolume;
										balanceData[token].newAvailableBaseVolume -= payBaseVolume;
										balanceData[token].newTargetVolume += earnTargetVolume;
										balanceData[token].newBaseVolume -= payBaseVolume;

                                        updateSiteProfit(target, processVolume, regDate);

                                    }else{
                                        var payTargetVolume = processVolume;
                                        var earnBaseVolume = tempBaseVolume*(1-fee/100);

                                        balanceData[token].target.f_available -= payTargetVolume;
                                        balanceData[token].target.f_sell_base_volume += tempBaseVolume;
                                        balanceData[token].target.f_sell_volume += payTargetVolume;
										balanceData[token].base.f_available += earnBaseVolume;
										balanceData[token].base.f_buy_sell_base_volume += earnBaseVolume;
										
										balanceData[token].newAvailableTargetVolume -= payTargetVolume;
										balanceData[token].newAvailableBaseVolume += earnBaseVolume;
										balanceData[token].newTargetVolume -= payTargetVolume;
										balanceData[token].newBaseVolume += earnBaseVolume;

                                        updateSiteProfit(base, tempBaseVolume, regDate);
                                        
                                    }


                                    //update otoken wallet
                                    if (type == 'buy') {

                                    	var payTargetVolume = processVolume;
                                        earnBaseVolume = tempBaseVolume*(1-fee/100);

                                        balanceData[order.f_token].target.f_blocked -= processVolume;
                                        balanceData[order.f_token].target.f_sell_base_volume += tempBaseVolume;
                                        balanceData[order.f_token].target.f_sell_volume += processVolume;
										balanceData[order.f_token].base.f_available += earnBaseVolume;
										balanceData[order.f_token].base.f_buy_sell_base_volume += earnBaseVolume;
										
										balanceData[order.f_token].newBlockedTargetVolume -= processVolume;
										balanceData[order.f_token].newAvailableBaseVolume += earnBaseVolume;
										balanceData[order.f_token].newTargetVolume -= payTargetVolume;
										balanceData[order.f_token].newBaseVolume += earnBaseVolume;

                                        updateSiteProfit(base, tempBaseVolume, regDate);

                                    }else{
                                        earnTargetVolume = processVolume*(1-fee/100);

                                        balanceData[order.f_token].target.f_available += earnTargetVolume;
                                        balanceData[order.f_token].target.f_buy_base_volume += earnTargetVolume*order.f_rate;
                                        balanceData[order.f_token].target.f_buy_volume += earnTargetVolume;
										balanceData[order.f_token].base.f_blocked -= processVolume*order.f_rate;
										balanceData[order.f_token].base.f_buy_sell_base_volume += earnTargetVolume*order.f_rate;
										
										balanceData[order.f_token].newAvailableTargetVolume += earnTargetVolume;
										balanceData[order.f_token].newBlockedBaseVolume -= processVolume * order.f_rate;
										balanceData[order.f_token].newTargetVolume += earnTargetVolume;
										balanceData[order.f_token].newBaseVolume -= processVolume * order.f_rate;

                                        updateSiteProfit(target, processVolume, regDate);

                                    }
                                    if(order.f_token != token){
                                        updateWallet(order.f_token, target, base, balanceData[order.f_token]);
                                    }
                                    
                                    targetVolume = nextTargetVolume;
                                    if(targetVolume == 0) break;
                                }
                                //if target volume is still larger than 0.001
                                if(targetVolume>diff){
                                    //create new order
                                    var tempBaseVolume = targetVolume * rate;
                                    insertQuery = "INSERT INTO tb_market_order (`f_token`, `f_type`, `f_target`, `f_base`, `f_target_volume`, `f_original_target_volume`, `f_rate`, `f_base_volume`, `f_regdate`) VALUES ('" + token + "', '" + type + "', '" + target + "', '" + base + "', '" + targetVolume + "', '" + originalTargetVolume + "', '" + rate + "', '" + tempBaseVolume + "', '" + regDate + "')";
                                    dbconnection.query(insertQuery, function (err, rows, fields) {
                                        if (err){
                                            throw err;
                                        }else{
                                            emitOrderBookData(target, base, false);
                                            emitMyOpenOrderData(token, target, base);
                                            //update wallet base volume after create order
                                            if(type == 'buy'){
                                                balanceData[token].base.f_available -= tempBaseVolume;
												balanceData[token].base.f_blocked += tempBaseVolume;
												
												balanceData[token].newAvailableBaseVolume -= tempBaseVolume;
												balanceData[token].newBlockedBaseVolume += tempBaseVolume;
                                            }else{
                                                balanceData[token].target.f_available -= targetVolume;
                                                balanceData[token].target.f_blocked += targetVolume;
												
												balanceData[token].newAvailableTargetVolume -= targetVolume;
												balanceData[token].newBlockedTargetVolume += targetVolume;
                                            }
                                            resolveProcessOrder(returnData);
                                        }
                                    });
                                }else{
                                    resolveProcessOrder(returnData);
                                }
                            }else{
                                //if matching orders don't exist
                                insertQuery = "INSERT INTO tb_market_order (`f_token`, `f_type`, `f_target`, `f_base`, `f_target_volume`, `f_original_target_volume`, `f_rate`, `f_base_volume`, `f_regdate`) VALUES ('" + token + "', '" + type + "', '" + target + "', '" + base + "', '" + targetVolume + "', '" + originalTargetVolume + "', '" + rate + "', '" + baseVolume + "', '" + regDate + "')";
                                dbconnection.query(insertQuery, function (err, rows, fields) {
                                    if (err){
                                        throw err;
                                    }else{
                                        emitOrderBookData(target, base, false);
                                        emitMyOpenOrderData(token, target, base);
                                        //update wallet volume after create order
                                        if(type == 'buy'){
                                            balanceData[token].base.f_available -= baseVolume;
                                            balanceData[token].base.f_blocked += baseVolume;
												
											balanceData[token].newAvailableBaseVolume -= baseVolume;
											balanceData[token].newBlockedBaseVolume += baseVolume;
                                        }else{
                                            balanceData[token].target.f_available -= targetVolume;
                                            balanceData[token].target.f_blocked += targetVolume;
												
											balanceData[token].newAvailableTargetVolume -= targetVolume;
											balanceData[token].newBlockedTargetVolume += targetVolume;
                                        }
                                        resolveProcessOrder(returnData);
                                    }
                                });
                            }
                        });
                    });
                });
                processOrder.then(function(returnData) {
                    if(socketMarketHistoryData.length > 0){
                        if(socket.rooms == 'bot'){
                            io.in(target+'_KRW').emit('updateMarketHistory', socketMarketHistoryData);
                        }else{
                            io.in(socket.rooms).emit('updateMarketHistory', socketMarketHistoryData);
                        }
                    }
                    updateWallet(token, target, base, balanceData[token]);
                    io.to(socket.id).emit('orderSucceed', returnData);
                })
            }else{
                io.to(socket.id).emit('orderFailed', returnData);
            }
        });
    });

    socket.on('orderBot', function(jsonOrderData) {
        var orderData = JSON.parse(jsonOrderData);
        var token = orderData.token;
        var type = orderData.order_type;
        var target = orderData.target;
        var base = orderData.base;
        var targetVolume = parseFloat(orderData.order_amount);
        var originalTargetVolume = targetVolume;
        var rate = parseFloat(orderData.order_rate);
        var baseVolume = parseFloat(orderData.order_price);
        var fee = parseFloat(orderData.exchange_fee);
        var diff = 0.001;
        var date = new Date();
        var regDate = date.getTime();
        regDate = Math.round(regDate / 1000);
        var balanceData = [], pushData, orders = [], socketMarketHistoryData = [];
        var processOrder = new Promise(function(resolveProcessOrder, reject) {
            if(type == 'buy'){
                var query =  "SELECT * FROM `tb_market_order` WHERE `f_type`='sell' && `f_target`='" + target + "' && `f_base`='" + base + "' && `f_rate`<='" + rate + "' ORDER BY f_rate ASC, f_regdate ASC";
                var tempType = 'sell';
            }else{
                var query =  "SELECT * FROM `tb_market_order` WHERE `f_type`='buy' && `f_target`='" + target + "' && `f_base`='" + base + "' && `f_rate`>='" + rate + "' ORDER BY f_rate DESC, f_regdate ASC";
                var tempType = 'buy';
            }
            var getOrders = new Promise(function(resolve, reject) {                        
                dbconnection.query(query, function (err, rows, fields) {
                    if (err) throw err;
                    orders = rows;
                    resolve(orders);
                });
            });
            var insertQuery, updateQquery, deleteQuery;
            getOrders.then(function(orders) {
                if(orders.length > 0){
                    var select_query = "SELECT * FROM tb_user_wallet WHERE f_token='" + orders[0].f_token + "'";
                    for(var i = 1; i < orders.length; i ++)
                        select_query += " or f_token='" + orders[i].f_token + "'";
                }else {
                    var select_query = "SELECT * FROM tb_user_wallet WHERE f_token='NAN'";        
                }
                select_query += " AND (f_unit='"+target+"' OR f_unit='"+base+"')";
                var tokenList = [];
                var getOtherBalanceData = new Promise(function(resolve, reject) {
                    dbconnection.query(select_query, function (err, rows, fields) {
                        if (err){
                            throw err;
                        }else{
                            for(var i = 0; i < rows.length; i ++) {
                                if(rows[i].f_token != token){
                                    if(tokenList.includes(rows[i].f_token) == false){
                                        tokenList.push(rows[i].f_token);
                                    }
                                }
                            }
                            tokenList.forEach(function(thisToken){
                                rows.forEach(function(value){
									if(value.f_token == thisToken){
										if(value.f_unit == target){
											targetValue = value;
										}else if(value.f_unit == base){
											baseValue = value;
										}
									}
                                })
                                pushData = {
                                    target : targetValue,
                                    base : baseValue,
									newAvailableTargetVolume : 0,
									newBlockedTargetVolume : 0,
									newAvailableBaseVolume : 0,
									newBlockedBaseVolume: 0,
									newTargetVolume: 0,
									newBaseVolume: 0
                                }
                                balanceData[thisToken] = pushData;
                            })
                            resolve(balanceData);   
                        }
                    });        
                });
                getOtherBalanceData.then(function(balanceData) {
                    if(orders.length > 0){
                        for (var i=0; i<orders.length; i++) {
                            var order = orders[i];                                    
                            // change target currency value
                            if(targetVolume - order.f_target_volume > diff){
                                var processVolume = order.f_target_volume;
                                nextTargetVolume = targetVolume - order.f_target_volume;
                                var tempBaseVolume = order.f_rate * processVolume;
                                //delete this order
                                var deleteQuery = "DELETE FROM tb_market_order WHERE f_id='" + order.f_id + "'";
                                dbconnection.query(deleteQuery, function (err, rows, fields) {
                                    if (err){
                                        throw err;
                                    }else{
                                        emitOrderBookData(target, base, false);
                                        emitMyOpenOrderData(order.f_token, target, base);
                                    }
                                });
                            }else if(order.f_target_volume - targetVolume > diff){
                                var processVolume = targetVolume;
                                nextTargetVolume = 0;
                                var tempBaseVolume = order.f_rate * processVolume;
                                //update order
                                var update_data = [];
                                update_data['f_target_volume'] = order.f_target_volume - targetVolume;
                                update_data['f_base_volume'] = order.f_base_volume - tempBaseVolume;
                                updateQquery = "UPDATE tb_market_order SET f_target_volume='" + update_data['f_target_volume'] + "', f_base_volume='" + update_data['f_base_volume'] + "' WHERE f_id='" + order.f_id + "'";
                                dbconnection.query(updateQquery, function (err, rows, fields) {
                                    if (err){
                                        throw err;
                                    }else{
                                        emitOrderBookData(target, base, false);
                                        emitMyOpenOrderData(order.f_token, target, base);
                                    }
                                });
                            }else{
                                var processVolume = targetVolume;
                                nextTargetVolume = 0;
                                var tempBaseVolume = order.f_rate * processVolume;
                                //delete this order
                                var deleteQuery = "DELETE FROM tb_market_order WHERE f_id='" + order.f_id + "'";
                                dbconnection.query(deleteQuery, function (err, rows, fields) {
                                    if (err){
                                        throw err;
                                    }else{
                                        emitOrderBookData(target, base, false);
                                        emitMyOpenOrderData(order.f_token, target, base);
                                    }
                                });
                            }                                 
                            //record to market history
                            recordMarketHistory (token, type, target, base, processVolume, order.f_rate, tempBaseVolume, regDate, order.f_token);
                            pushData = {
                                rate : order.f_rate,
                                targetVolume : processVolume,
								baseVolume : tempBaseVolume,
                                regdate : dateFormat(regDate*1000, "yyyy-mm-dd HH:MM:ss"),
                                type : type
                            }
                            socketMarketHistoryData.push(pushData);

                            //update otoken wallet
                            if(order.f_token != token){
                                if(type == 'buy'){
                                    earnBaseVolume = tempBaseVolume*(1-fee/100);

                                    balanceData[order.f_token].target.f_blocked -= processVolume;
                                    balanceData[order.f_token].target.f_sell_base_volume += tempBaseVolume;
                                    balanceData[order.f_token].target.f_sell_volume += processVolume;
                                    balanceData[order.f_token].base.f_available += earnBaseVolume;
									balanceData[order.f_token].base.f_buy_sell_base_volume += earnBaseVolume;
										
									balanceData[order.f_token].newBlockedTargetVolume -= processVolume;
									balanceData[order.f_token].newAvailableBaseVolume += earnBaseVolume;
									balanceData[order.f_token].newTargetVolume -= processVolume;
									balanceData[order.f_token].newBaseVolume += earnBaseVolume;

                                    updateSiteProfit(base, tempBaseVolume, regDate);

                                }else{
                                    earnTargetVolume = processVolume*(1-fee/100);

                                    balanceData[order.f_token].target.f_available += earnTargetVolume;
                                    balanceData[order.f_token].target.f_buy_base_volume += earnTargetVolume*order.f_rate;
                                    balanceData[order.f_token].target.f_buy_volume += earnTargetVolume;
                                    balanceData[order.f_token].base.f_blocked -= processVolume*order.f_rate;
									balanceData[order.f_token].base.f_buy_sell_base_volume += processVolume*order.f_rate;
										
									balanceData[order.f_token].newAvailableTargetVolume += earnTargetVolume;
									balanceData[order.f_token].newBlockedBaseVolume -= processVolume * order.f_rate;
									balanceData[order.f_token].newTargetVolume += earnTargetVolume;
									balanceData[order.f_token].newBaseVolume -= processVolume * order.f_rate;

                                    updateSiteProfit(target, processVolume, regDate);

                                }
                                updateWallet(order.f_token, target, base, balanceData[order.f_token]);
                            }

                            targetVolume = nextTargetVolume;
                            if(targetVolume == 0) break;
                        }
                        //if target volume is still larger than 0.001
                        if(targetVolume>diff){
                            //create new order
                            var tempBaseVolume = targetVolume * rate;
                            insertQuery = "INSERT INTO tb_market_order (`f_token`, `f_type`, `f_target`, `f_base`, `f_target_volume`, `f_original_target_volume`, `f_rate`, `f_base_volume`, `f_regdate`) VALUES ('" + token + "', '" + type + "', '" + target + "', '" + base + "', '" + targetVolume + "', '" + originalTargetVolume + "', '" + rate + "', '" + tempBaseVolume + "', '" + regDate + "')";
                            dbconnection.query(insertQuery, function (err, rows, fields) {
                                if (err){
                                    throw err;
                                }else{
                                    emitOrderBookData(target, base, false);
                                    resolveProcessOrder(true);
                                }
                            });
                        }else{
                            resolveProcessOrder(true);
                        }
                    }else{
                        //if matching orders don't exist
                        insertQuery = "INSERT INTO tb_market_order (`f_token`, `f_type`, `f_target`, `f_base`, `f_target_volume`, `f_original_target_volume`, `f_rate`, `f_base_volume`, `f_regdate`) VALUES ('" + token + "', '" + type + "', '" + target + "', '" + base + "', '" + targetVolume + "', '" + originalTargetVolume + "', '" + rate + "', '" + baseVolume + "', '" + regDate + "')";
                        dbconnection.query(insertQuery, function (err, rows, fields) {
                            if (err){
                                throw err;
                            }else{
                                emitOrderBookData(target, base, false);
                                resolveProcessOrder(true);
                            }
                        });
                    }
                });
            });
        });
        processOrder.then(function(returnData) {
            if(returnData == true){
                if(socketMarketHistoryData.length > 0){
                    if(socket.rooms == 'bot'){
                        io.in(target+'_KRW').emit('updateMarketHistory', socketMarketHistoryData);
                    }else{
                        io.in(socket.rooms).emit('updateMarketHistory', socketMarketHistoryData);
                    }
                }
            }
        })
	});
	
	socket.on('cancelOrder', function(data) {
        var token = data.token;
        var f_id = data.f_id;
        var order, wallet;
        var date = new Date();
        var regDate = date.getTime();
		regDate = Math.round(regDate / 1000);
        var query = "SELECT * FROM tb_market_order WHERE f_id=" + f_id;
        dbconnection.query(query, function (err, rows, fields) {
            if (err) throw err;
			order = rows[0];
			if(order.f_type == 'buy'){
				query = "SELECT * FROM tb_user_wallet WHERE f_token='" + token + "' && f_unit='" + order.f_base + "'";
			}else{
				query = "SELECT * FROM tb_user_wallet WHERE f_token='" + token + "' && f_unit='" + order.f_target + "'";
			}
            dbconnection.query(query, function (err, rows, fields) {
                if (err){
					throw err;
				}else{
					wallet = rows[0];
					var available, blocked, total, insertQuery;
					if(order.f_type == 'buy'){
						available = wallet.f_available + order.f_base_volume;
						blocked = wallet.f_blocked - order.f_base_volume;
						if (blocked >= 0) {
							total = available + blocked;
							updateQquery = "UPDATE tb_user_wallet SET f_total=" + total + ", f_available=" + available + ", f_blocked=" + blocked + " WHERE f_token='" + token + "' && f_unit='" + order.f_base + "'";
							insertQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + token + "', 'enabled', '" + order.f_base + "', '" + order.f_base_volume + "', 'cancel order', '" + regDate + "')";
						} else {
							var result = false;
							io.to(socket.id).emit('orderCancelFailed', result);
						}
					}else{
						available = wallet.f_available + order.f_target_volume;
						blocked = wallet.f_blocked - order.f_target_volume;
						if (blocked >= 0) {
							total = available + blocked;
							updateQquery = "UPDATE tb_user_wallet SET f_total="+total+", f_available="+available+", f_blocked="+blocked+" WHERE f_token='" + token + "' && f_unit='" + order.f_target + "'";
							insertQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + token + "', 'enabled', '" + order.f_target + "', '" + order.f_target_volume + "', 'cancel order', '" + regDate + "')";
						} else {
							var result = false;
							io.to(socket.id).emit('orderCancelFailed', result);
						}
					}
					if (blocked >= 0) {
						dbconnection.query(updateQquery, function (err, rows, fields) {
							if (err){
								throw err;
							}else{
								dbconnection.query(insertQuery, function (err, rows, fields) {});
								emitMyBalanceData(token);
							}
						});
						var deleteQuery = "DELETE FROM tb_market_order WHERE f_id='" + f_id + "'";
						dbconnection.query(deleteQuery, function (err, rows, fields) {
							if (err){
								throw err;
							}else{
								var result = true;
								io.to(socket.id).emit('orderCancelled', result);
								emitOrderBookData(order.f_target, order.f_base, false);
								emitMyOpenOrderData(token, order.f_target, order.f_base);
							}
						});
					}
				}
            });
        });
	})
	socket.on('editOrder', function(data) {
        var token = data.token;
        var f_id = data.f_id;
		var newTargetVolume = data.targetVolume;
		var order, wallet;
        var date = new Date();
        var regDate = date.getTime();
		regDate = Math.round(regDate / 1000);
		var query = "SELECT * FROM tb_market_order WHERE f_id=" + f_id;
		var return_data = {
			res : true,
			msg : 'changeOrderSucceed'
		};
		if(newTargetVolume < 0.001){
			return_data = {
				res : false,
				msg : 'less_order_volume'
			};
			io.to(socket.id).emit('changeOrderFailed', return_data);
		}else{
			dbconnection.query(query, function (err, rows, fields) {
				if (err){
					throw err;
				}else{
					order = rows[0];
					var newBaseVolume = order.f_rate * newTargetVolume;
					if(order.f_type == 'buy'){
						query = "SELECT * FROM tb_user_wallet WHERE f_token='" + token + "' && f_unit='" + order.f_base + "'";
					}else{
						query = "SELECT * FROM tb_user_wallet WHERE f_token='" + token + "' && f_unit='" + order.f_target + "'";
					}
					dbconnection.query(query, function (err, rows, fields) {
						if (err){
							throw err;
						}else{
							wallet = rows[0];
							var available, blocked, total, insertQuery;
							var changedVolume;
							if(order.f_type == 'buy'){
								changedVolume = order.f_base_volume - newBaseVolume;
								available = wallet.f_available + changedVolume;
								blocked = wallet.f_blocked - changedVolume;
								total = available + blocked;
							}else{
								changedVolume = order.f_target_volume - newTargetVolume;
								available = wallet.f_available + changedVolume;
								blocked = wallet.f_blocked - changedVolume;
								total = available + blocked;
							}
							if(available < 0){
								if(order.f_type == 'buy'){
									return_data = {
										res : false,
										msg : 'less_base_balance'
									};
								}else{
									return_data = {
										res : false,
										msg : 'less_target_balance'
									};
								}
								io.to(socket.id).emit('changeOrderFailed', return_data);
							} else if (blocked < 0) {
								if (order.f_type == 'buy') {
									return_data = {
										res: false,
										msg: 'less_base_balance'
									};
								} else {
									return_data = {
										res: false,
										msg: 'less_target_balance'
									};
								}
								io.to(socket.id).emit('changeOrderFailed', return_data);
							} else {
								if(newTargetVolume != order.f_target_volume){
									if(order.f_type == 'buy'){
										updateQquery = "UPDATE tb_user_wallet SET f_total="+total+", f_available="+available+", f_blocked="+blocked+" WHERE f_token='" + token + "' && f_unit='" + order.f_base + "'";
										if(changedVolume>0){
											insertQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + token + "', 'enabled', '" + order.f_base + "', '" + changedVolume + "', 'changed order amount', '" + regDate + "')";
										}else if(changedVolume<0){
											insertQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + token + "', 'blocked', '" + order.f_base + "', '" + changedVolume + "', 'changed order amount', '" + regDate + "')";
										}
									}else{
										updateQquery = "UPDATE tb_user_wallet SET f_total="+total+", f_available="+available+", f_blocked="+blocked+" WHERE f_token='" + token + "' && f_unit='" + order.f_target + "'";
										if(changedVolume>0){
											insertQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + token + "', 'enabled', '" + order.f_target + "', '" + changedVolume + "', 'changed order amount', '" + regDate + "')";
										}else if(changedVolume<0){
											insertQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + token + "', 'blocked', '" + order.f_target + "', '" + changedVolume + "', 'changed order amount', '" + regDate + "')";
										}
									}
									dbconnection.query(updateQquery, function (err, rows, fields) {
										if (err){
											throw err;
										}else{
											dbconnection.query(insertQuery, function (err, rows, fields) {});
											emitMyBalanceData(token);
										}
									});
									updateQquery = "UPDATE tb_market_order SET f_target_volume="+newTargetVolume+", f_base_volume="+newBaseVolume+" WHERE f_id='" + f_id + "'";
									dbconnection.query(updateQquery, function (err, rows, fields) {
										if (err){
											throw err;
										}else{
											emitOrderBookData(order.f_target, order.f_base, false);
											io.to(socket.id).emit('changeOrderSucceed', return_data);
										}
									});
								}else{
									return_data = {
										res : false,
										msg : 'the_same_volume'
									};
									io.to(socket.id).emit('changeOrderFailed', return_data);
								}
							}
						}
					});
				}
			});
		}
	})
	









});
