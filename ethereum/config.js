var path = require('path');

var config = {
	mysql: {
		host: '127.0.0.1',
		username: 'root',
		//------------------------server---------------------------//
		password : 'Snpassword2018@!',
		database : 'trade_coinsky_c'
		//---------------------------------------------------------//
		//------------------------local----------------------------//
		// password: '',
		// database: 'db_coinsky',
		//---------------------------------------------------------//
	},
	INFURA_KEY: '5f9bbb61dc174a4a8565394c2703b15a',
	ETHERSCAN_KSY: 'NSHM7P3FXPF6Q8KC4EDIBGR35RXT2JTDQ3',
	CONTRACT_ADDRESS: {
		SKY: '0x4C29f2a4a8f83580426E22c39E682b4708425B7B',
		BDR: '0xEE4594134bd456d1173EB7913adF0080CDbFEEBd'
	},
	gasPrice : {
		value : '10',
		unit : 'gwei'
	},
	gasLimit : 30000,
	txFee: 0.0003,
	abiArray: {
		SKY: [{
			"constant": true,
			"inputs": [],
			"name": "name",
			"outputs": [{
				"name": "",
				"type": "string"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_spender",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}],
			"name": "approve",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [],
			"name": "totalSupply",
			"outputs": [{
				"name": "",
				"type": "uint256"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_from",
				"type": "address"
			}, {
				"name": "_to",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}],
			"name": "transferFrom",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [],
			"name": "decimals",
			"outputs": [{
				"name": "",
				"type": "uint8"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_value",
				"type": "uint256"
			}],
			"name": "burn",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [{
				"name": "",
				"type": "address"
			}],
			"name": "balanceOf",
			"outputs": [{
				"name": "",
				"type": "uint256"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_from",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}],
			"name": "burnFrom",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [],
			"name": "symbol",
			"outputs": [{
				"name": "",
				"type": "string"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_to",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}],
			"name": "transfer",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_spender",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}, {
				"name": "_extraData",
				"type": "bytes"
			}],
			"name": "approveAndCall",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [{
				"name": "",
				"type": "address"
			}, {
				"name": "",
				"type": "address"
			}],
			"name": "allowance",
			"outputs": [{
				"name": "",
				"type": "uint256"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"inputs": [{
				"name": "initialSupply",
				"type": "uint256"
			}, {
				"name": "tokenName",
				"type": "string"
			}, {
				"name": "tokenSymbol",
				"type": "string"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "constructor"
		}, {
			"anonymous": false,
			"inputs": [{
				"indexed": true,
				"name": "from",
				"type": "address"
			}, {
				"indexed": true,
				"name": "to",
				"type": "address"
			}, {
				"indexed": false,
				"name": "value",
				"type": "uint256"
			}],
			"name": "Transfer",
			"type": "event"
		}, {
			"anonymous": false,
			"inputs": [{
				"indexed": true,
				"name": "_owner",
				"type": "address"
			}, {
				"indexed": true,
				"name": "_spender",
				"type": "address"
			}, {
				"indexed": false,
				"name": "_value",
				"type": "uint256"
			}],
			"name": "Approval",
			"type": "event"
		}, {
			"anonymous": false,
			"inputs": [{
				"indexed": true,
				"name": "from",
				"type": "address"
			}, {
				"indexed": false,
				"name": "value",
				"type": "uint256"
			}],
			"name": "Burn",
			"type": "event"
		}],
		BDR: [{
			"constant": true,
			"inputs": [],
			"name": "name",
			"outputs": [{
				"name": "",
				"type": "string"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_spender",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}],
			"name": "approve",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [],
			"name": "totalSupply",
			"outputs": [{
				"name": "",
				"type": "uint256"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_from",
				"type": "address"
			}, {
				"name": "_to",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}],
			"name": "transferFrom",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [],
			"name": "decimals",
			"outputs": [{
				"name": "",
				"type": "uint8"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_value",
				"type": "uint256"
			}],
			"name": "burn",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [{
				"name": "",
				"type": "address"
			}],
			"name": "balanceOf",
			"outputs": [{
				"name": "",
				"type": "uint256"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_from",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}],
			"name": "burnFrom",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [],
			"name": "symbol",
			"outputs": [{
				"name": "",
				"type": "string"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_to",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}],
			"name": "transfer",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": false,
			"inputs": [{
				"name": "_spender",
				"type": "address"
			}, {
				"name": "_value",
				"type": "uint256"
			}, {
				"name": "_extraData",
				"type": "bytes"
			}],
			"name": "approveAndCall",
			"outputs": [{
				"name": "success",
				"type": "bool"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "function"
		}, {
			"constant": true,
			"inputs": [{
				"name": "",
				"type": "address"
			}, {
				"name": "",
				"type": "address"
			}],
			"name": "allowance",
			"outputs": [{
				"name": "",
				"type": "uint256"
			}],
			"payable": false,
			"stateMutability": "view",
			"type": "function"
		}, {
			"inputs": [{
				"name": "initialSupply",
				"type": "uint256"
			}, {
				"name": "tokenName",
				"type": "string"
			}, {
				"name": "tokenSymbol",
				"type": "string"
			}],
			"payable": false,
			"stateMutability": "nonpayable",
			"type": "constructor"
		}, {
			"anonymous": false,
			"inputs": [{
				"indexed": true,
				"name": "from",
				"type": "address"
			}, {
				"indexed": true,
				"name": "to",
				"type": "address"
			}, {
				"indexed": false,
				"name": "value",
				"type": "uint256"
			}],
			"name": "Transfer",
			"type": "event"
		}, {
			"anonymous": false,
			"inputs": [{
				"indexed": true,
				"name": "_owner",
				"type": "address"
			}, {
				"indexed": true,
				"name": "_spender",
				"type": "address"
			}, {
				"indexed": false,
				"name": "_value",
				"type": "uint256"
			}],
			"name": "Approval",
			"type": "event"
		}, {
			"anonymous": false,
			"inputs": [{
				"indexed": true,
				"name": "from",
				"type": "address"
			}, {
				"indexed": false,
				"name": "value",
				"type": "uint256"
			}],
			"name": "Burn",
			"type": "event"
		}]
	}
};

module.exports = config;
