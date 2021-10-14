var express = require('express');
var bodyParser = require('body-parser');
var Web3 = require('web3');
var Tx = require('ethereumjs-tx');
var config = require('./config');
var mysql = require('mysql');
var cron = require('node-cron');
var request = require('request');

var app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({
	extended: true
}));

var socketWeb3;
var web3 = new Web3(new Web3.providers.HttpProvider('https://mainnet.infura.io/v3/' + config.INFURA_KEY));

var depositAddressList = [];
var dbconnection = mysql.createConnection({
	host: config.mysql.host,
	user: config.mysql.username,
	password: config.mysql.password,
	database: config.mysql.database
});
dbconnection.connect();

var depositServiceSubscription = null;

function startSocketWeb3() {
	socketWeb3 = new Web3('wss://mainnet.infura.io/ws/v3/' + config.INFURA_KEY);
	runDepositService();
	socketWeb3._provider.on('end', function () {
		if (depositServiceSubscription != null){
			depositServiceSubscription.unsubscribe(function (error, success) {});
			depositServiceSubscription = null;
		}
		startSocketWeb3();
	});
	socketWeb3._provider.on('close', function () {
		if (depositServiceSubscription != null) {
			depositServiceSubscription.unsubscribe(function (error, success) {});
			depositServiceSubscription = null;
		}
		startSocketWeb3();
	});
	socketWeb3._provider.on('error', function () {
		if (depositServiceSubscription != null) {
			depositServiceSubscription.unsubscribe(function (error, success) {});
			depositServiceSubscription = null;
		}
		startSocketWeb3();
	});
	setTimeout(function () {
		socketWeb3 = null;
		if (depositServiceSubscription != null) {
			depositServiceSubscription.unsubscribe(function (error, success) {});
			depositServiceSubscription = null;
		}
		startSocketWeb3();
	}, 900000);
}

setInterval(function () {
	var pingSubscription = socketWeb3.eth.subscribe('newBlockHeaders', function (error, result) {})
		.on("data", function (blockHeader) {pingSubscription.unsubscribe(function (error, success) {});})
		.on("error", function (err){pingSubscription.unsubscribe(function (error, success) {});});
}, 30000);

function processDeposit(unit, amount, ethAddress, transactionFrom, txHash) {
	var userToken, balanceData, query;
	var getUserToken = new Promise(function (resolve, reject) {
		query = "SELECT f_token AS userToken FROM tb_user_deposit_address_eth WHERE f_address='" + ethAddress + "'";
		dbconnection.query(query, function (err, rows, fields) {
			if (err) {
				throw err;
			} else {
				userToken = rows[0].userToken;
				resolve(userToken);
			}
		});
	});
	getUserToken.then(function (userToken) {
		var getBalanceData = new Promise(function (resolve, reject) {
			query = "SELECT * FROM tb_user_wallet WHERE f_token='" + userToken + "'  && f_unit='" + unit + "'";
			dbconnection.query(query, function (err, rows, fields) {
				if (err) {
					throw err;
				} else {
					if (rows.length > 0) {
						balanceData = {
							f_token: rows[0].f_token,
							f_unit: rows[0].f_unit,
							f_total: rows[0].f_total,
							f_available: rows[0].f_available,
							f_buy_volume: rows[0].f_buy_volume,
							f_buy_base_volume: rows[0].f_buy_base_volume,
							f_buy_sell_base_volume: rows[0].f_buy_sell_base_volume
						};
						resolve(balanceData);
					}
				}
			});
		});
		getBalanceData.then(function (balanceData) {
			balanceData.f_total += amount;
			balanceData.f_available += amount;
			balanceData.f_buy_volume += amount;
			query = "SELECT f_close AS currentRate FROM tb_market WHERE f_target='" + unit + "'  && f_base='KRW'";
			dbconnection.query(query, function (err, rows, fields) {
				if (err) {
					throw err;
				} else {
					if (rows.length > 0) {
						var currentRate = rows[0].currentRate;
						balanceData.f_buy_base_volume += amount * currentRate;
						query = "UPDATE tb_user_wallet SET f_total='" + balanceData.f_total + "', f_available='" + balanceData.f_available + "', f_buy_volume='" + balanceData.f_buy_volume + "', f_buy_base_volume='" + balanceData.f_buy_base_volume + "', f_buy_sell_base_volume='" + balanceData.f_buy_sell_base_volume + "' WHERE f_token='" + balanceData.f_token + "' && f_unit='" + balanceData.f_unit + "'";
						dbconnection.query(query, function (err, rows, fields) {
							if (err) {
								throw err;
							} else {
								var date = new Date();
								var regDate = date.getTime();
								regDate = Math.round(regDate / 1000);
								query = "INSERT INTO tb_log_user_deposit_withdraw (`f_token`, `f_unit`, `f_type`, `f_amount`, `f_fees`, `f_detail`, `f_txhash`, `f_status`, `f_regdate`) VALUES ('" + userToken + "', '" + unit + "', 'deposit', '" + amount + "', 0, '" + transactionFrom + "', '" + txHash + "', 1, '" + regDate + "')";
								dbconnection.query(query, function (err, rows, fields) {});
								query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" + balanceData.f_token + "', 'in', '" + balanceData.f_unit + "', '" + amount + "', 'deposit coin', '" + regDate + "')";
								dbconnection.query(query, function (err, rows, fields) {});
							}
						});
					}
				}
			});
		});
	});
}

function processDepositSubUpdateWallet(unit, amount, ethAddress) {
	var userToken, balanceData, query;
	var getUserToken = new Promise(function (resolve, reject) {
		query = "SELECT f_token AS userToken FROM tb_user_deposit_address_eth WHERE f_address='" + ethAddress + "'";
		dbconnection.query(query, function (err, rows, fields) {
			if (err) {
				throw err;
			} else {
				userToken = rows[0].userToken;
				resolve(userToken);
			}
		});
	});
	getUserToken.then(function (userToken) {
		var getBalanceData = new Promise(function (resolve, reject) {
			query = "SELECT * FROM tb_user_wallet WHERE f_token='" + userToken + "'  && f_unit='" + unit + "'";
			dbconnection.query(query, function (err, rows, fields) {
				if (err) {
					throw err;
				} else {
					if (rows.length > 0) {
						balanceData = {
							f_token: rows[0].f_token,
							f_unit: rows[0].f_unit,
							f_total: rows[0].f_total,
							f_available: rows[0].f_available,
							f_buy_volume: rows[0].f_buy_volume,
							f_buy_base_volume: rows[0].f_buy_base_volume,
							f_buy_sell_base_volume: rows[0].f_buy_sell_base_volume
						};
						resolve(balanceData);
					}
				}
			});
		});
		getBalanceData.then(function (balanceData) {
			balanceData.f_total += amount;
			balanceData.f_available += amount;
			balanceData.f_buy_volume += amount;
			query = "SELECT f_close AS currentRate FROM tb_market WHERE f_target='" + unit + "'  && f_base='KRW'";
			dbconnection.query(query, function (err, rows, fields) {
				if (err) {
					throw err;
				} else {
					if (rows.length > 0) {
						var currentRate = rows[0].currentRate;
						balanceData.f_buy_base_volume += amount * currentRate;
						query = "UPDATE tb_user_wallet SET f_total='" + balanceData.f_total + "', f_available='" + balanceData.f_available + "', f_buy_volume='" + balanceData.f_buy_volume + "', f_buy_base_volume='" + balanceData.f_buy_base_volume + "', f_buy_sell_base_volume='" + balanceData.f_buy_sell_base_volume + "' WHERE f_token='" + balanceData.f_token + "' && f_unit='" + balanceData.f_unit + "'";
						dbconnection.query(query, function (err, rows, fields) {
							if (err) {
								throw err;
							} else {
								return;
							}
						});
					}
				}
			});
		});
	});
}

function processDepositSubAddDeposit(ethAddress, insertDataList) {
	var userToken, query;
	var getUserToken = new Promise(function (resolve, reject) {
		query = "SELECT f_token AS userToken FROM tb_user_deposit_address_eth WHERE f_address='" + ethAddress + "'";
		dbconnection.query(query, function (err, rows, fields) {
			if (err) {
				throw err;
			} else {
				userToken = rows[0].userToken;
				resolve(userToken);
			}
		});
	});
	getUserToken.then(function (userToken) {
		var date = new Date();
		var regDate = date.getTime();
		regDate = Math.round(regDate / 1000);
		query = "INSERT INTO tb_log_user_deposit_withdraw (`f_token`, `f_unit`, `f_type`, `f_amount`, `f_fees`, `f_detail`, `f_txhash`, `f_status`, `f_regdate`) VALUES";
		var logQuery = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES";
		var count = 0;
		insertDataList.forEach(function(value){
			count++;
			if(count < insertDataList.length){
				query += " ('" + userToken + "', '" + value.unit + "', 'deposit', '" + value.amount + "', 0, '" + value.from + "', '" + value.txnHash + "', 1, '" + regDate + "'), ";
				logQuery += " ('" + userToken + "', 'in', '" + value.unit + "', '" + value.amount + "', 'deposit coin', '" + regDate + "'), ";
			}else{
				query += " ('" + userToken + "', '" + value.unit + "', 'deposit', '" + value.amount + "', 0, '" + value.from + "', '" + value.txnHash + "', 1, '" + regDate + "')";
				logQuery += " ('" + userToken + "', 'in', '" + value.unit + "', '" + value.amount + "', 'deposit coin', '" + regDate + "')";
			}			
		});
		dbconnection.query(query, function (err, rows, fields) {
			dbconnection.query(logQuery, function (err, rows, fields) {});
		});
	});
}

function runDepositService(){
	var getDepositAddressList = new Promise(function (resolve, reject) {
		var query = "SELECT * FROM tb_user_deposit_address_eth";
		dbconnection.query(query, function (err, rows, fields) {
			if (err) {
				throw err;
			} else {
				rows.forEach(function (value) {
					depositAddressList.push(value.f_address);
				});
			}
			resolve(depositAddressList);
		});
	});
	getDepositAddressList.then(function (depositAddressList) {
		depositServiceSubscription = socketWeb3.eth.subscribe('pendingTransactions', function (error, result) {})
			.on("data", function (transactionHash) {
				socketWeb3.eth.getTransaction(transactionHash)
					.then(function (transaction) {
						if (transaction != null && transaction.from != null && transaction.to != null) {
							var transactionFrom = transaction.from;
							transactionFrom = transactionFrom.toLowerCase();
							var transactionTo = transaction.to;
							transactionTo = transactionTo.toLowerCase();
							var amount;
							if (depositAddressList.indexOf(transactionTo) > -1) {
								var value = transaction.value;
								amount = value / Math.pow(10, 18);
								if (amount >= 0.00000001) {
									processDeposit('ETH', amount, transactionTo, transactionFrom, transactionHash);
								}
							}

							var skyContractAddress = config.CONTRACT_ADDRESS.SKY;
							skyContractAddress = skyContractAddress.toLowerCase();
							if (transactionTo == skyContractAddress) {
								var transactionInput = transaction.input;
								var toInfo = transactionInput.substr(10, 64);
								var toNumberStr = web3.utils.hexToNumberString(toInfo);
								var toAddress = web3.utils.numberToHex(toNumberStr);
								var tempToAddress = toAddress.toString();
								var amountInfo = transactionInput.substr(74, 64);
								var amountNumberStr = web3.utils.hexToNumberString(amountInfo);
								amount = parseFloat(amountNumberStr) / Math.pow(10, 18);
								if (tempToAddress.length < 42) {
									var tempCount = 42 - tempToAddress.length;
									var tempStr = '';
									for (i = 1; i <= tempCount; i++) {
										tempStr += '0';
									}
									tempAddress = tempToAddress.slice(0, 2) + tempStr + tempToAddress.slice(2);
									tempToAddress = tempAddress;
								}
								if (depositAddressList.indexOf(tempToAddress) > -1) {
									if (amount >= 0.00000001) {
										processDeposit('SKY', amount, tempToAddress, transactionFrom, transactionHash);
									}
								}
							}

							var bdrContractAddress = config.CONTRACT_ADDRESS.BDR;
							bdrContractAddress = bdrContractAddress.toLowerCase();
							if (transactionTo == bdrContractAddress) {
								var transactionInput = transaction.input;
								var toInfo = transactionInput.substr(10, 64);
								var toNumberStr = web3.utils.hexToNumberString(toInfo);
								var toAddress = web3.utils.numberToHex(toNumberStr);
								var tempToAddress = toAddress.toString();
								var amountInfo = transactionInput.substr(74, 64);
								var amountNumberStr = web3.utils.hexToNumberString(amountInfo);
								amount = parseFloat(amountNumberStr) / Math.pow(10, 18);
								if (tempToAddress.length < 42) {
									var tempCount = 42 - tempToAddress.length;
									var tempStr = '';
									for (i = 1; i <= tempCount; i++) {
										tempStr += '0';
									}
									tempAddress = tempToAddress.slice(0, 2) + tempStr + tempToAddress.slice(2);
									tempToAddress = tempAddress;
								}
								if (depositAddressList.indexOf(tempToAddress) > -1) {
									if (amount >= 0.00000001) {
										processDeposit('BDR', amount, tempToAddress, transactionFrom, transactionHash);
									}
								}
							}
						}
					});
			});
	});
}

var server = app.listen(8081, function () {
	startSocketWeb3();
});

app.get('/get_is_address/:address', function (req, res) {
	var address = req.params.address;
	if (!web3.utils.isAddress(address)) {
		res.json({
			status: false
		});
	}else{
		res.json({
			status: true
		});
	}
});

app.get('/get_eth_balance/:address', function (req, res) {
	var address = req.params.address;
	var balance = web3.eth.getBalance(address)
		.then(function (balance) {
			res.json({
				status: true,
				balance: balance
			});
		})
		.catch(function (err) {
			res.json({
				status: false
			});
		});
});

function sendETH(from, to, amount, private) {
	var privateKey = new Buffer.from(private, 'hex');
	var tempAmount = amount - config.txFee;
	tempAmount = tempAmount.toString();
	web3.eth.getTransactionCount(from)
		.then(function (txCount) {
			var txObject = {
				nonce: web3.utils.toHex(txCount),
				to: to,
				value: web3.utils.toHex(web3.utils.toWei(tempAmount, 'ether')),
				gasLimit: web3.utils.toHex(config.gasLimit),
				gasPrice: web3.utils.toHex(web3.utils.toWei(config.gasPrice.value, config.gasPrice.unit))
			};
			var tx = new Tx(txObject);
			tx.sign(privateKey);

			var serializedTx = tx.serialize();
			var raw = '0x' + serializedTx.toString('hex');

			web3.eth.sendSignedTransaction(raw, (err, txHash) => {
				if (!err) {
					return true;
				} else {
					return false;
				}
			});
		})
		.catch(function (err) {
			return false;
		});
}

app.post('/send_eth', function (req, res) {
	var from = req.body.from;
	var to = req.body.to;
	var amount = req.body.amount;
	var private = req.body.private;
	var privateKey = new Buffer.from(private, 'hex');
	web3.eth.getTransactionCount(from)
		.then(function (txCount) {
			var txObject = {
				nonce: web3.utils.toHex(txCount),
				to: to,
				value: web3.utils.toHex(web3.utils.toWei(amount, 'ether')),
				gasLimit: web3.utils.toHex(config.gasLimit),
				gasPrice: web3.utils.toHex(web3.utils.toWei(config.gasPrice.value, config.gasPrice.unit))
			};
			var tx = new Tx(txObject);
			tx.sign(privateKey);

			var serializedTx = tx.serialize();
			var raw = '0x' + serializedTx.toString('hex');

			web3.eth.sendSignedTransaction(raw, (err, txHash) => {
				if (!err) {
					res.json({
						status: true,
						txHash: txHash
					});
				} else {
					res.json({
						status: false
					});
				}
			});
		})
		.catch(function (err) {
			res.json({
				status: false
			});
		});
});

app.get('/get_erc20token_balance/:address/:token', function (req, res) {
	var address = req.params.address;
	var token = req.params.token;

	var abiArray, contractAddress;
	if (token == 'SKY') {
		abiArray = config.abiArray.SKY;
		contractAddress = config.CONTRACT_ADDRESS.SKY;
	}else if(token == 'BDR'){
		abiArray = config.abiArray.BDR;
		contractAddress = config.CONTRACT_ADDRESS.BDR;
	}

	var contract = new web3.eth.Contract(abiArray, contractAddress, {
		from: address
	});

	contract.methods.balanceOf(address).call()
		.then(function (balance) {
			res.json({
				status: true,
				balance: balance
			});
		})
		.catch(function (err) {
			res.json({
				status: false
			});
		});
});

function sendErc20Token(token, from, to, amount, private) {
	var fixedAmount = amount * Math.pow(10, 18);
	var tempFixedAmount = fixedAmount.toString();
	var gasPrice = web3.utils.toHex(web3.utils.toWei(config.gasPrice.value, config.gasPrice.unit));
	var tempAmount = web3.utils.toHex(tempFixedAmount);
	var privateKey = new Buffer(private, 'hex');

	if (token == 'SKY') {
		abiArray = config.abiArray.SKY;
		contractAddress = config.CONTRACT_ADDRESS.SKY;
	}else if(token == 'BDR'){
		abiArray = config.abiArray.BDR;
		contractAddress = config.CONTRACT_ADDRESS.BDR;
	}

	var abiArray, contractAddress;
	var contract = new web3.eth.Contract(abiArray, contractAddress, {
		from: from
	});

	var rawTrans = contract.methods.transfer(to, tempAmount);
	rawTrans.estimateGas()
		.then(function (gasLimit) {
			web3.eth.getTransactionCount(from, "pending")
				.then(function (txCount) {
					var chainId = 1;
					var rawTransaction = {
						"from": from,
						"gasPrice": gasPrice,
						"gasLimit": gasLimit,
						"to": contractAddress,
						"value": "0x0",
						"data": contract.methods.transfer(to, tempAmount).encodeABI(),
						"nonce": web3.utils.toHex(txCount),
						"chainId": chainId
					};
					var transaction = new Tx(rawTransaction);
					transaction.sign(privateKey);
					var serializedTx = `0x${transaction.serialize().toString('hex')}`;
					web3.eth.sendSignedTransaction(serializedTx, function (err, hash) {
						if (!err) {
							return true;
						} else {
							return false;
						}
					});
				})
				.catch(function (err) {
					return false;
				});
		})
		.catch(function (err) {
			return false;
		});
}

app.post('/send_erc20token', function (req, res) {
	var token = req.body.token;
	var from = req.body.from;
	var to = req.body.to;
	var amount = req.body.amount;
	var private = req.body.private;
	var fixedAmount = amount * Math.pow(10,18);
	var tempFixedAmount = fixedAmount.toString();
	var gasPrice = web3.utils.toHex(web3.utils.toWei(config.gasPrice.value, config.gasPrice.unit));
	var tempAmount = web3.utils.toHex(tempFixedAmount);
	var privateKey = new Buffer(private, 'hex');

	if (token == 'SKY') {
		abiArray = config.abiArray.SKY;
		contractAddress = config.CONTRACT_ADDRESS.SKY;
	}else if(token == 'BDR'){
		abiArray = config.abiArray.BDR;
		contractAddress = config.CONTRACT_ADDRESS.BDR;
	}

	var abiArray, contractAddress;
	var contract = new web3.eth.Contract(abiArray, contractAddress, {
		from: from
	});

	var rawTrans = contract.methods.transfer(to, tempAmount);
	rawTrans.estimateGas()
		.then(function (gasLimit) {
			web3.eth.getTransactionCount(from, "pending")
				.then(function (txCount) {
					var chainId = 1;
					var rawTransaction = {
						"from": from,
						"gasPrice": gasPrice,
						"gasLimit": gasLimit,
						"to": contractAddress,
						"value": "0x0",
						"data": contract.methods.transfer(to, tempAmount).encodeABI(),
						"nonce": web3.utils.toHex(txCount),
						"chainId": chainId
					};
					var transaction = new Tx(rawTransaction);
					transaction.sign(privateKey);
					var serializedTx = `0x${transaction.serialize().toString('hex')}`;
					web3.eth.sendSignedTransaction(serializedTx, function (err, hash) {
						if (!err) {
							res.json({
								status: true,
								txHash: hash
							});
						} else {
							res.json({
								status: false
							});
						}
					});
				})
				.catch(function (err) {
					res.json({
						status: false
					});
				});
		})
		.catch(function (err) {
			res.json({
				status: false
			});
		});
});

app.get('/create_wallet', function (req, res) {
	try {
		var newWallet = web3.eth.accounts.create();
		var address = newWallet.address;
		address = address.toLowerCase();
		depositAddressList.push(address);
		res.json({
			status: true,
			address: address,
			private: newWallet.privateKey
		});
	} catch (e) {
		res.json({
			status: false
		});
	}
});

function depositToSiteAddress(address){
	// ETH
	var processETH = new Promise(function (resolveETH, reject) {
		var balance = web3.eth.getBalance(address)
			.then(function (balance) {
				var amount = balance / Math.pow(10, 18);
				if (amount >= config.txFee) {
					var from = address;
					var query = "SELECT f_address FROM tb_site_coin_address WHERE f_unit='ETH'";
					dbconnection.query(query, function (err, rows, fields) {
						if (err) {
							resolveETH(false);
						} else {
							var to = rows[0].f_address;
							query = "SELECT f_private FROM tb_user_deposit_address_eth WHERE f_address='" + address + "'";
							dbconnection.query(query, function (err, rows, fields) {
								if (err) {
									resolveETH(false);
								} else {
									var private = rows[0].f_private;
									sendETH(from, to, amount, private);
									resolveETH(true);
								}
							});
						}
					});
				} else {
					resolveETH(true);
				}
			})
			.catch(function (err) {
				resolveETH(false);
			});
	});
	processETH.then(function (result) {
		var processSKY = new Promise(function (resolveSKY, reject) {
			//SKY
			var token = 'SKY';
			var abiArray, contractAddress;
			abiArray = config.abiArray.SKY;
			contractAddress = config.CONTRACT_ADDRESS.SKY;

			var contract = new web3.eth.Contract(abiArray, contractAddress, {
				from: address
			});
			contract.methods.balanceOf(address).call()
				.then(function (tokenBalance) {
					var amount = tokenBalance / Math.pow(10, 18);
					if (amount > 100) {
						var from = address;
						var query = "SELECT f_address FROM tb_site_coin_address WHERE f_unit='" + token + "'";
						dbconnection.query(query, function (err, rows, fields) {
							if (err) {
								resolveSKY(false);
							} else {
								var to = rows[0].f_address;
								query = "SELECT f_private FROM tb_user_deposit_address_eth WHERE f_address='" + address + "'";
								dbconnection.query(query, function (err, rows, fields) {
									if (err) {
										resolveSKY(false);
									} else {
										var private = rows[0].f_private;
										sendErc20Token(token, from, to, amount, private);
										resolveSKY(true);
									}
								});
							}
						});
					} else {
						resolveSKY(true);
					}
				})
				.catch(function (err) {
					resolveSKY(false);
				});
		});
		processSKY.then(function (result) {
			var processBDR = new Promise(function (resolveBDR, reject) {
				//BDR
				var token = 'BDR';
				var abiArray, contractAddress;
				abiArray = config.abiArray.BDR;
				contractAddress = config.CONTRACT_ADDRESS.BDR;

				var contract = new web3.eth.Contract(abiArray, contractAddress, {
					from: address
				});
				contract.methods.balanceOf(address).call()
					.then(function (tokenBalance) {
						var amount = tokenBalance / Math.pow(10, 18);
						if (amount > 100) {
							var from = address;
							var query = "SELECT f_address FROM tb_site_coin_address WHERE f_unit='" + token + "'";
							dbconnection.query(query, function (err, rows, fields) {
								if (err) {
									resolveBDR(false);
								} else {
									var to = rows[0].f_address;
									query = "SELECT f_private FROM tb_user_deposit_address_eth WHERE f_address='" + address + "'";
									dbconnection.query(query, function (err, rows, fields) {
										if (err) {
											resolveBDR(false);
										} else {
											var private = rows[0].f_private;
											sendErc20Token(token, from, to, amount, private);
											resolveBDR(true);
										}
									});
								}
							});
						} else {
							resolveBDR(true);
						}
					})
					.catch(function (err) {
						resolveBDR(false);
					});
			});
			processBDR.then(function (result) {

			});
		});
	});
}

function checkAddress() {
	var query = "SELECT * FROM tb_user_deposit_address_eth";
	dbconnection.query(query, function (err, rows, fields) {
		if (err) {
			throw err;
		} else {
			rows.forEach(function (value) {
				var address = value.f_address;
				depositToSiteAddress(address);
			});
		}
	});
}

function checkAddressForETHDeposit() {
	var txHashList = [];
	var query = "SELECT f_txhash FROM tb_log_user_deposit_withdraw WHERE f_unit='ETH' AND f_type='deposit'";
	dbconnection.query(query, function (err, hashRows, fields) {
		if (err) {
			throw err;
		} else {
			hashRows.forEach(function(value){
				txHashList.push(value.f_txhash);
			});
			query = "SELECT * FROM tb_user_deposit_address_eth";
			dbconnection.query(query, function (err, rows, fields) {
				if (err) {
					throw err;
				} else {
					var timerCount = 0;
					rows.forEach(function (rowValue) {
						timerCount++;
						setTimeout(function () {
							var address = rowValue.f_address;
							var etherGetScanTxnUrl = 'http://api.etherscan.io/api?module=account&action=txlist&address=' + address + '&sort=asc&startblock=' + rowValue.f_last_block + '&apikey=' + config.ETHERSCAN_KSY;
							var options = {
								method: 'GET',
								url: etherGetScanTxnUrl,
							};
							request(options, function (error, response, body) {
								if (error) {
									throw new Error(error);
								} else {
									var bodyParseData = JSON.parse(body);
									if (bodyParseData.status == '1') {
										var txnData = bodyParseData.result;
										var lastBlockNumber = rowValue.f_last_block;
										var newAmount = 0;
										var insertDataList = [];
										txnData.forEach(function (txn) {
											if (txn.to == address) {
												if (txHashList.indexOf(txn.hash) > -1) {} else {
													var transactionFrom = txn.from;
													transactionFrom = transactionFrom.toLowerCase();
													var value = txn.value;
													amount = value / Math.pow(10, 18);
													if (amount >= 0.00000001) {
														lastBlockNumber = txn.blockNumber;
														newAmount += amount;
														var insertData = {
															unit : 'ETH',
															amount: amount,
															from: transactionFrom,
															txnHash: txn.hash
														};
														insertDataList.push(insertData);
													}
												}
											}
										});
										if (newAmount > 0) {
											processDepositSubAddDeposit(address, insertDataList);
											processDepositSubUpdateWallet('ETH', newAmount, address);
										}
										var updateQuery = "UPDATE tb_user_deposit_address_eth SET f_last_block='" + lastBlockNumber + "' WHERE f_token='" + rowValue.f_token + "' && f_address='" + rowValue.f_address + "'";
										dbconnection.query(updateQuery, function (err, rows, fields) {});
									}
								}
							});
						}, timerCount * 500);
					});
				}
			});
		}
	});	
}

function checkAddressForSKYDeposit() {
	var txHashList = [];
	var query = "SELECT f_txhash FROM tb_log_user_deposit_withdraw WHERE (f_unit='SKY' OR f_unit='BDR') AND f_type='deposit'";
	dbconnection.query(query, function (err, hashRows, fields) {
		if (err) {
			throw err;
		} else {
			hashRows.forEach(function (value) {
				txHashList.push(value.f_txhash);
			});
			query = "SELECT * FROM tb_user_deposit_address_eth";
			dbconnection.query(query, function (err, rows, fields) {
				if (err) {
					throw err;
				} else {
					var timerCount = 0;
					rows.forEach(function (rowValue) {
						timerCount++;
						setTimeout(function () {
							var address = rowValue.f_address;
							var etherGetScanTxnUrl = 'http://api.etherscan.io/api?module=account&action=tokentx&address=' + address + '&startblock=' + rowValue.f_sky_last_block + '&sort=asc&apikey=' + config.ETHERSCAN_KSY;
							var options = {
								method: 'GET',
								url: etherGetScanTxnUrl,
							};
							request(options, function (error, response, body) {
								if (error) {
									throw error;
								} else {
									var bodyParseData = JSON.parse(body);
									if (bodyParseData.status == '1') {
										var txnData = bodyParseData.result;
										var lastBlockNumber = rowValue.f_sky_last_block;
										var newAmount = {
											SKY : 0,
											BDR : 0
										};
										var insertDataList = [];
										var insertDataListCount = 0;
										txnData.forEach(function (txn) {
											if (txHashList.indexOf(txn.hash) > -1) {} else {
												if (txn.to == address) {
													var transactionFrom, transactionInput, toInfo, toNumberStr, toAddress, tempToAddress, amountInfo, amountNumberStr, tempCount, tempStr, insertData;
												 	if(txn.tokenSymbol == 'SKY') {
														if (lastBlockNumber < txn.blockNumber) {
															lastBlockNumber = txn.blockNumber;
														}
														transactionFrom = txn.from;
														transactionInput = txn.input;
														toInfo = transactionInput.substr(10, 64);
														toNumberStr = web3.utils.hexToNumberString(toInfo);
														toAddress = web3.utils.numberToHex(toNumberStr);
														tempToAddress = toAddress.toString();
														amountInfo = transactionInput.substr(74, 64);
														amountNumberStr = web3.utils.hexToNumberString(amountInfo);
														amount = parseFloat(amountNumberStr) / Math.pow(10, 18);
														if (tempToAddress.length < 42) {
															tempCount = 42 - tempToAddress.length;
															tempStr = '';
															for (i = 1; i <= tempCount; i++) {
																tempStr += '0';
															}
															tempAddress = tempToAddress.slice(0, 2) + tempStr + tempToAddress.slice(2);
															tempToAddress = tempAddress;
														}
														if (tempToAddress == address) {
															if (amount >= 0.00000001) {
																newAmount.SKY += amount;
																insertData = {
																	unit : 'SKY',
																	amount: amount,
																	from: transactionFrom,
																	txnHash: txn.hash
																};
																insertDataList.push(insertData);
																insertDataListCount++;
															}
														}
													} else if (txn.tokenSymbol == 'BDR') {
														if (lastBlockNumber < txn.blockNumber) {
															lastBlockNumber = txn.blockNumber;
														}
														transactionFrom = txn.from;
														transactionInput = txn.input;
														toInfo = transactionInput.substr(10, 64);
														toNumberStr = web3.utils.hexToNumberString(toInfo);
														toAddress = web3.utils.numberToHex(toNumberStr);
														tempToAddress = toAddress.toString();
														amountInfo = transactionInput.substr(74, 64);
														amountNumberStr = web3.utils.hexToNumberString(amountInfo);
														amount = parseFloat(amountNumberStr) / Math.pow(10, 18);
														if (tempToAddress.length < 42) {
															tempCount = 42 - tempToAddress.length;
															tempStr = '';
															for (i = 1; i <= tempCount; i++) {
																tempStr += '0';
															}
															tempAddress = tempToAddress.slice(0, 2) + tempStr + tempToAddress.slice(2);
															tempToAddress = tempAddress;
														}
														if (tempToAddress == address) {
															if (amount >= 0.00000001) {
																newAmount.BDR += amount;
																insertData = {
																	unit: 'BDR',
																	amount: amount,
																	from: transactionFrom,
																	txnHash: txn.hash
																};
																insertDataList.push(insertData);
																insertDataListCount++;
															}
														}
													}
												}
											}
										});
										if (insertDataListCount > 0) {
											processDepositSubAddDeposit(address, insertDataList);
										}
										if (newAmount.SKY > 0) {
											processDepositSubUpdateWallet('SKY', newAmount.SKY, address);
										} else if (newAmount.BDR > 0) {
											processDepositSubUpdateWallet('BDR', newAmount.BDR, address);
										}
										var updateQuery = "UPDATE tb_user_deposit_address_eth SET f_sky_last_block='" + lastBlockNumber + "' WHERE f_token='" + rowValue.f_token + "' && f_address='" + rowValue.f_address + "'";
										dbconnection.query(updateQuery, function (err, rows, fields) {});
									}
								}
							});
						}, timerCount * 500);
					});
				}
			});
		}
	});
}

checkAddress();
checkAddressForETHDeposit();
checkAddressForSKYDeposit();

var timer = 0;
cron.schedule('0 * * * *', function () {
	timer++;
	checkAddressForETHDeposit();
	checkAddressForSKYDeposit();
	if (timer >= 24) {
		checkAddress();
		timer = 0;
	}
});
