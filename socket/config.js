var path = require('path');

var config = {
    debug: true,
    base_url : 'http://45.76.180.140',
    port: 2096,
    mysql: {
		host: 'localhost',
		username: 'root',
		 password: 'mirai2018',
		 database: 'trade_coinsky_c',
        //password : 'Snpassword2018@!',
        //database : 'trade_coinsky_c'
    },
    targets : ['BTC', 'ETH', 'SKY', 'BDR']
}

module.exports = config
