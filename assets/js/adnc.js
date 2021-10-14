var token = $("#token").val();
var is_login = $("#is_login").val();
var exchange_fee = $("#exchange_fee").val();
var currentRate = 0,
	lastDayRate, is_clicked = 0,
	targetAvailableBalance = 0,
	targetBlockedBalance = 0,
	targetBalanceDetail = 0,
	targetTotalBalance = 0,
	targetBuyBaseVolume = 0,
	baseAvailableBalance = 0,
	baseBlockedBalance = 0,
	baseBalanceDetail = 0,
	baseTotalBalance = 0;
var coinList = ['BTC', 'ETH', 'BCH', 'XRP', 'SKY', 'BDR', 'MITH', 'COSM', 'DENT', 'IOST', 'IPDEAL', 'ISR', 'KST', 'LYM', 'HYC'];

var unitDecimal = $("#unitDecimal").val();
currentRate = $("#currentRate").val();
unitDecimal = parseInt(unitDecimal);

var hoga_unit = $("#hoga_unit").val();
hoga_unit = parseFloat(hoga_unit);
hoga_unit = hoga_unit.toFixed(unitDecimal);
hoga_unit = parseFloat(hoga_unit);

var tempBaseUrl = base_url.substr(0, base_url.length - 1);


tempBaseUrl = 'http://45.76.180.140';

//----------------------------------Server-------------------------------//
var socket = io.connect(tempBaseUrl + ':2096', {
	secure: true,
	reconnect: true,
	rejectUnauthorized: false
});
//----------------------------------******-------------------------------//

//----------------------------------Local-------------------------------//
// var socket = io.connect(tempBaseUrl + ':2096');
//----------------------------------******-------------------------------//

var market = target + '_' + base;
var pros = 0;

$.sweetModal.defaultSettings.confirm.yes.label = lang_yes[lang];
$.sweetModal.defaultSettings.confirm.cancel.label = lang_cancel[lang];

if(is_login == true){
	walletObject[target] = 0;
	walletObject[base] = 0;
}

socket.on('connect', function (data) {
	var socketData = {
		market: market,
		token: token
	}
	socket.emit('joinMarket', socketData);
});

$(document).ready(function () {

	document.title = currentRate + ' ' + target + '/' + base;

	$(window).scroll(function (event) {
		var st = $(this).scrollTop();
		var screenHeight = $(this)[0].screen.height;
		var marketListHeight = screenHeight - 300;
		var margin_top_by_scroll = st - 70;
		if (st < 70) {
			$(".marketlist-content").css('max-height', marketListHeight + 'px');
			$(".fixed-width-left").css('margin-top', '0px');
		} else if (st < 881) {
			var marketListHeight = screenHeight - 230;
			$(".marketlist-content").css('max-height', marketListHeight + 'px');
			$(".fixed-width-left").css('margin-top', margin_top_by_scroll + 'px');
		} else {
			var marketListHeight = screenHeight - 250;
			$(".marketlist-content").css('max-height', marketListHeight + 'px');
			$(".fixed-width-left").css('margin-top', '811px');
		}
	});

	jQuery('.marketlist-content').scrollbar();
	jQuery('.datatableMarketHistoryScroll').scrollbar();
	jQuery('.my-order-history').scrollbar();

	//receive market data
	socket.on('receiveMarketData', function (marketData) {
		$.each(marketData, function (index, value) {
			var temp = value.percent;
			var className = 'span-grey';
			if (temp > 0) {
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true, 2);
				temp = $("#tempNumberFormatter").html();
				temp = '+' + temp + '%';
				className = 'span-red';
			} else if (temp < 0) {
				className = 'span-blue';
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true, 2);
				temp = $("#tempNumberFormatter").html();
				temp = '-' + temp + '%';
			} else {
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true, 2);
				temp = $("#tempNumberFormatter").html();
				temp = temp + '%';
			}
			$("#marketList_percent_" + value.target).html(temp);

			temp = value.currentRate;
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true, value.unitDecimal);
			temp = $("#tempNumberFormatter").html();
			$("#marketList_rate_" + value.target).html(temp);

			temp = value.bVolume;
			if (temp >= 1000000) {
				temp = Math.round(temp / 1000000);
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true);
				temp = $("#tempNumberFormatter").html();
				temp = temp + '<br><span class="span-grey span-11px span-normal">' + lang_million[lang] + '</span>';
			} else {
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true);
				temp = $("#tempNumberFormatter").html();
			}
			$("#marketList_volume_" + value.target).html(temp);
			$("#marketList_rate_" + value.target).attr('class', 'span-13px span-normal-bold ' + className);
			$("#marketList_percent_" + value.target).attr('class', 'span-12px span-normal-bold ' + className);

			if (value.target == target) {

				temp = value.percent;
				className = 'span-grey';
				if (temp > 0) {
					$("#tempNumberFormatter").html(temp);
					$("#tempNumberFormatter").number(true, 2);
					temp = $("#tempNumberFormatter").html();
					temp = '+' + temp + '%';
					className = 'span-red';
				} else if (temp < 0) {
					className = 'span-blue';
					$("#tempNumberFormatter").html(temp);
					$("#tempNumberFormatter").number(true, 2);
					temp = $("#tempNumberFormatter").html();
					temp = '-' + temp + '%';
				} else {
					$("#tempNumberFormatter").html(temp);
					$("#tempNumberFormatter").number(true, 2);
					temp = $("#tempNumberFormatter").html();
					temp = temp + '%';
				}
				$("#marketSummary_percent").html(temp);
				$("#marketSummary_percent").attr('class', className);
				$("#orderBook_percent").html(temp);
				$("#orderBook_percent").attr('class', className);

				temp = value.currentRate;
				currentRate = temp;
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true, unitDecimal);
				temp = $("#tempNumberFormatter").html();
				$("#marketSummary_rate").html(temp);
				$("#marketSummary_rateContent").attr('class', className);
				$("#orderBook_rate").html(temp);
				$("#orderBook_rate").attr('class', className);
				$("#orderBookCenterCurrentRate").html(temp);
				$("#orderBookCenterCurrentRate").attr('class', className);

				for (var no = 1; no <= 13; no++) {
					temp = $("#orderBookSellOrder_" + no + "_rate").html();
					var tempNumber = makeNumber(temp);
					if (tempNumber == currentRate) {
						$("#orderBookSellOrder_" + no + "_rate").parent().attr('class', 'orderBookSellOrders orderbook_currentRate_rate');
					} else {
						$("#orderBookSellOrder_" + no + "_rate").parent().attr('class', 'orderBookSellOrders');
					}
					temp = $("#orderBookBuyOrder_" + no + "_rate").html();
					tempNumber = makeNumber(temp);
					if (tempNumber == currentRate) {
						$("#orderBookBuyOrder_" + no + "_rate").parent().attr('class', 'orderBookBuyOrders orderbook_currentRate_rate');
					} else {
						$("#orderBookBuyOrder_" + no + "_rate").parent().attr('class', 'orderBookBuyOrders');
					}
				}

				temp = value.pastRate;
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true);
				temp = $("#tempNumberFormatter").html();
				$("#orderBook_last").html(temp);

				temp = value.diff;
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true, unitDecimal);
				temp = $("#tempNumberFormatter").html();
				$("#marketSummary_diff").html(temp);
				$("#marketSummary_diff").attr('class', className);
				$("#orderBook_diff").html(temp);
				$("#orderBook_diff").attr('class', className);

				temp = value.highRate;
				if (value.highRate > value.pastRate) {
					className = 'span-red';
				} else if (value.highRate == value.pastRate) {
					className = 'span-grey';
				} else {
					className = 'span-blue';
				}
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true, unitDecimal);
				temp = $("#tempNumberFormatter").html();
				$("#marketSummary_high").html(temp);
				$("#marketSummary_high").attr('class', className);
				$("#orderBook_high").html(temp);
				$("#orderBook_high").attr('class', className);

				temp = value.lowRate;
				if (value.lowRate > value.pastRate) {
					className = 'span-red';
				} else if (value.lowRate == value.pastRate) {
					className = 'span-grey';
				} else {
					className = 'span-blue';
				}
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true, unitDecimal);
				temp = $("#tempNumberFormatter").html();
				$("#marketSummary_low").html(temp);
				$("#marketSummary_low").attr('class', className);
				$("#orderBook_low").html(temp);
				$("#orderBook_low").attr('class', className);

				temp = value.dayTVolume;
				$("#tempNumberFormatter").html(temp);
				$("#tempNumberFormatter").number(true, 3);
				temp = $("#tempNumberFormatter").html();
				$("#marketSummary_tVolume").html(temp);
				$("#orderBook_tVolume").html(temp);

				temp = value.dayBVolume;
				if (temp >= 1000000) {
					temp = Math.round(temp / 1000000);
					$("#tempNumberFormatter").html(temp);
					$("#tempNumberFormatter").number(true);
					temp = $("#tempNumberFormatter").html();
					temp = temp + lang_million[lang];
				} else {
					$("#tempNumberFormatter").html(temp);
					$("#tempNumberFormatter").number(true);
					temp = $("#tempNumberFormatter").html();
				}
				$("#marketSummary_bVolume").html(temp);
				$("#orderBook_bVolume").html(temp);
			}
		})
	});
	//----------------------------
	//receive my balance data
	socket.on('receiveMyBalanceData', function (myBalanceData) {
		var temp = 0;
		$.each(myBalanceData, function (index, value) {
			if (value.unit == target) {
				walletObject[target] = value.available;
				targetAvailableBalance = value.available;
				targetBlockedBalance = value.blocked;
				targetTotalBalance = value.total;
				targetBuyBaseVolume = value.buyBVolume;
			} else if (value.unit == base) {
				walletObject[base] = value.available;
				baseAvailableBalance = value.available;
				baseBlockedBalance = value.blocked;
				baseTotalBalance = value.total;
			}
		})

		temp = targetAvailableBalance;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true, 8);
		temp = $("#tempNumberFormatter").html();
		$("#targetAvailableBalance").html(temp);

		temp = baseAvailableBalance;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true);
		temp = $("#tempNumberFormatter").html();
		$("#baseAvailableBalance").html(temp);

		var tVolumeByBase = targetTotalBalance * currentRate;
		temp = tVolumeByBase;
		if (temp >= 1000000) {
			temp = Math.round(temp / 1000000);
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true);
			temp = $("#tempNumberFormatter").html();
			temp = temp + lang_million[lang];
		} else {
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true);
			temp = $("#tempNumberFormatter").html();
		}
		$("#myBalance_bVolume").html(temp);

		temp = targetBuyBaseVolume;
		if (temp >= 1000000) {
			temp = Math.round(temp / 1000000);
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true);
			temp = $("#tempNumberFormatter").html();
			temp = temp + lang_million[lang];
		} else {
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true);
			temp = $("#tempNumberFormatter").html();
		}
		$("#myBalance_buy").html(temp);

		temp = targetTotalBalance;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true, 8);
		temp = $("#tempNumberFormatter").html();
		$("#myBalance_total").html(temp);

		var diff = tVolumeByBase - targetBuyBaseVolume;
		temp = diff;
		if (temp >= 1000000) {
			temp = Math.round(temp / 1000000);
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true);
			temp = $("#tempNumberFormatter").html();
			temp = temp + lang_million[lang];
		} else {
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true);
			temp = $("#tempNumberFormatter").html();
		}
		$("#myBalance_diff").html(temp);

		var percent = 0;
		var temp = percent;
		var className = 'span-grey';
		if (temp > 0) {
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true, 2);
			temp = $("#tempNumberFormatter").html();
			temp = '+' + temp + '%';
			className = 'span-red';
		} else if (temp < 0) {
			className = 'span-blue';
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true, 2);
			temp = $("#tempNumberFormatter").html();
			temp = temp + '%';
		} else {
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true, 2);
			temp = $("#tempNumberFormatter").html();
			temp = temp + '%';
		}
		$("#myBalance_percent").html(temp);
		$("#myBalance_percent").attr('class', className);
		$("#myBalance_diff").attr('class', className);

		if (targetBuyBaseVolume > 0) {
			percent = diff / targetBuyBaseVolume;
		}

	});
	//------------------------------------------

	(function ($) {
		$.fn.tableSearch = function (options) {
			if (!$(this).is('table')) {
				return;
			}
			var tableObj = $(this),
				searchText = (options.searchText) ? options.searchText : 'Search: ',
				inputObj = $('#marketListSearchBox'),
				caseSensitive = (options.caseSensitive === true) ? true : false,
				searchFieldVal = '',
				pattern = '';
			inputObj.off('keyup').on('keyup', function () {
				searchFieldVal = $(this).val();
				pattern = (caseSensitive) ? RegExp(searchFieldVal) : RegExp(searchFieldVal, 'i');
				tableObj.find('tbody tr').hide().each(function () {
					var currentRow = $(this);
					currentRow.find('td').each(function () {
						if (pattern.test($(this).html())) {
							currentRow.show();
							return false;
						}
					});
				});
			});
			return tableObj;
		}
	}(jQuery));

	function makeNumber(str) {
		var res = str.split(",");
		var ret_value = res.join('');
		return ret_value;
	}

	function changeProc(mod) {
		if (pros == 0)
			return;
		if (mod == 'buy') {
			if ($('#order_buy_rate').val() == '')
				return;
			orderValue = parseFloat(makeNumber($('#order_buy_rate').val()));
			if (isNaN(orderValue) || orderValue == 0)
				return;
			q_amount = parseFloat(walletObject[base] / orderValue * pros / 100).toFixed(3);
			if (pros == 100) {
				if (q_amount * orderValue > walletObject[base])
					q_amount = q_amount - 0.001;
			}
			$('#order_buy_amount').val(q_amount);
			order_buy_rate = $("#order_buy_rate").val();
			order_buy_amount = $("#order_buy_amount").val();
			order_buy_price = parseFloat(order_buy_rate * order_buy_amount).toFixed(0);
			$("#order_buy_price").html(order_buy_price);
			$('#order_buy_price').number(true);
		} else if (mod == 'sell') {
			if (parseFloat(walletObject[target]) == 0)
				return;
			q_amount = parseFloat(walletObject[target] * pros / 100).toFixed(3);
			if (pros == 100) {
				if (q_amount > walletObject[target])
					q_amount = q_amount - 0.001;
			}
			$('#order_sell_amount').val(q_amount);
			order_sell_rate = $("#order_sell_rate").val();
			order_sell_amount = $("#order_sell_amount").val();
			order_sell_price = parseFloat(order_sell_rate * order_sell_amount).toFixed(0);
			$("#order_sell_price").html(order_sell_price);
			$('#order_sell_price').number(true, 0);
		}
	}

	//function - event
	$('.qtBt.buy').on('click', function (e) {
		e.preventDefault();
		$('.qtBt.buy').each(function () {
			$(this).removeClass('on');
		});
		$(this).addClass('on');
		pros = $(this).attr('quantity');
		changeProc('buy');
	});

	$('.qtBt.sell').on('click', function (e) {
		e.preventDefault();
		$('.qtBt.sell').each(function () {
			$(this).removeClass('on');
		});
		$(this).addClass('on');
		pros = $(this).attr('quantity');
		changeProc('sell');
	});

	$('a.quantitychange.buy').on('click', function (e) {
		$("#order_buy_price").html('');
		$("#order_sell_price").html('');
		e.preventDefault();
		var cur_val = parseFloat(makeNumber($('#order_buy_rate').val()));
		var delta = 0.001; // for accura..
		if ($(this).hasClass('plus')) {
			cur_val = parseInt((cur_val + delta) / hoga_unit) * hoga_unit;
			cur_val = cur_val + hoga_unit;			
			if (cur_val < 1.0) cur_val = 1.0;
			if (cur_val > 100000000000)
				return;
		} else {
			cur_val = parseInt((cur_val + delta) / hoga_unit) * hoga_unit;
			cur_val = cur_val - hoga_unit;
			if (cur_val < 1) cur_val = 1;
			if (cur_val < 0)
				return;
		}
		$('#order_buy_rate').val(cur_val);
		changeProc('buy');
	});

	$('a.quantitychange.sell').on('click', function (e) {
		$("#order_buy_price").html('');
		$("#order_sell_price").html('');
		e.preventDefault();
		var cur_val = parseFloat(makeNumber($('#order_sell_rate').val()));
		var delta = 0.001; // for accura..
		if ($(this).hasClass('plus')) {
			cur_val = parseInt((cur_val + delta) / hoga_unit) * hoga_unit;
			cur_val = cur_val + hoga_unit;
			if (cur_val < 1) cur_val = 1;
			if (cur_val > 100000000000)
				return;
		} else {
			cur_val = parseInt((cur_val + delta) / hoga_unit) * hoga_unit;
			cur_val = cur_val - hoga_unit;
			if (cur_val < 1) cur_val = 1;
			if (cur_val < 0)
				return;
		}
		$('#order_sell_rate').val(cur_val);
		changeProc('sell');
	});


	$('#marketList_KRW').tableSearch({
		searchText: 'Search Table',
		searchPlaceHolder: 'Input Value'
	});
	$("#marketList_KRW").tablesort();

	var order_buy_rate, order_buy_amount, order_buy_price, order_sell_rate, order_sell_amount, order_sell_price;
	$('#order_buy_rate').number(true, unitDecimal);
	$('#order_buy_amount').number(true, 8);
	$('#order_buy_price').number(true, unitDecimal);

	$('#order_sell_rate').number(true, unitDecimal);
	$('#order_sell_amount').number(true, 8);
	$('#order_sell_price').number(true, unitDecimal);

	$('.orderBookSellOrders').on('click', function () {
		var thisId = this.children[0].id;
		var thisRate = $("#" + thisId).html();
		thisRate = makeNumber(thisRate);
		$("#order_buy_rate").val(thisRate);
		$("#order_sell_rate").val(thisRate);
		$("#order_buy_amount").val('');
		$("#order_buy_price").html('');
		$("#order_sell_amount").val('');
		$("#order_sell_price").html('');
	});

	$('.orderBookBuyOrders').on('click', function () {
		var thisId = this.children[0].id;
		var thisRate = $("#" + thisId).html();
		thisRate = makeNumber(thisRate);
		$("#order_buy_rate").val(thisRate);
		$("#order_sell_rate").val(thisRate);
		$("#order_buy_amount").val('');
		$("#order_buy_price").html('');
		$("#order_sell_amount").val('');
		$("#order_sell_price").html('');
	});

	$("#order_buy_rate").keyup(function () {

		order_buy_rate = $("#order_buy_rate").val();
		order_buy_amount = $("#order_buy_amount").val();
		order_buy_price = parseFloat(order_buy_rate * order_buy_amount).toFixed(0);
		$("#order_buy_price").html(order_buy_price);
		$('#order_buy_price').number(true);

		if (makeNumber($('#order_buy_rate').val()) > 100000000000) {
			$('#order_buy_rate').val(100000000000);
		}
		changeProc('buy');

	});
	$("#order_buy_rate").change(function () {
		order_buy_rate = $("#order_buy_rate").val();
		order_buy_amount = $("#order_buy_amount").val();
		order_buy_price = parseFloat(order_buy_rate * order_buy_amount).toFixed(0);
		$("#order_buy_price").html(order_buy_price);
		$('#order_buy_price').number(true);
	});
	$("#order_buy_amount").keyup(function () {
		order_buy_rate = $("#order_buy_rate").val();
		order_buy_amount = $("#order_buy_amount").val();
		order_buy_price = parseFloat(order_buy_rate * order_buy_amount).toFixed(0);
		$("#order_buy_price").html(order_buy_price);
		$('#order_buy_price').number(true);
	});
	$("#order_buy_amount").change(function () {
		order_buy_rate = $("#order_buy_rate").val();
		order_buy_amount = $("#order_buy_amount").val();
		order_buy_price = parseFloat(order_buy_rate * order_buy_amount).toFixed(0);
		$("#order_buy_price").html(order_buy_price);
		$('#order_buy_price').number(true, 0);
	});

	$("#order_sell_rate").keyup(function () {
		order_sell_rate = $("#order_sell_rate").val();
		order_sell_amount = $("#order_sell_amount").val();
		order_sell_price = parseFloat(order_sell_rate * order_sell_amount).toFixed(0);

		$("#order_sell_price").html(order_sell_price);
		$('#order_sell_price').number(true, 0);

		if (makeNumber($('#order_sell_rate').val()) > 100000000000) {
			$('#order_sell_rate').val(100000000000);
		}

		changeProc('sell');

	});
	$("#order_sell_amount").keyup(function () {
		order_sell_rate = $("#order_sell_rate").val();
		order_sell_amount = $("#order_sell_amount").val();
		order_sell_price = parseFloat(order_sell_rate * order_sell_amount).toFixed(0);
		$("#order_sell_price").html(order_sell_price);
		$('#order_sell_price').number(true, 0);
	});

	$("#format_sell_order_details").click(function () {
		$("#order_sell_rate").val('');
		$("#order_sell_amount").val('');
		$("#order_sell_price").html('');
	});

	$("#format_buy_order_details").click(function () {
		$("#order_buy_rate").val('');
		$("#order_buy_amount").val('');
		$("#order_buy_price").html('');
	});

	socket.on('orderSucceed', function (return_data) {
		$body.removeClass("loading");
		is_clicked = 0;
		//$("#order_sell_rate").val('');
		$("#order_sell_amount").val('');
		$("#order_sell_price").html('');
		//$("#order_buy_rate").val('');
		$("#order_buy_amount").val('');
		$("#order_buy_price").html('');
		$.sweetModal({
			content: lang_msg_order_succeed[lang],
			icon: $.sweetModal.ICON_SUCCESS
		});
	});
	socket.on('orderFailed', function (return_data) {
		$body.removeClass("loading");
		is_clicked = 0;
		//$("#order_sell_rate").val('');
		$("#order_sell_amount").val('');
		$("#order_sell_price").html('');
		//$("#order_buy_rate").val('');
		$("#order_buy_amount").val('');
		$("#order_buy_price").html('');
		var failedModalMsg = lang_msg_order_failed[lang];
		if (return_data.msg == 'less_base_balance') {
			failedModalMsg += ' ' + lang_msg_order_failed_less_base_balance[lang];
		} else if (return_data.msg == 'less_target_balance') {
			failedModalMsg += ' ' + lang_msg_order_failed_less_target_balance[lang];
		} else if (return_data.msg == 'less_order_volume') {
			failedModalMsg += ' ' + lang_msg_order_failed_less_order_volume[lang];
		}
		$.sweetModal({
			content: failedModalMsg,
			icon: $.sweetModal.ICON_WARNING
		});
	});

	socket.on('orderCancelled', function (result) {
		$body.removeClass("loading");
		if (result == true) {
			$.sweetModal({
				content: lang_msg_order_canceled[lang],
				icon: $.sweetModal.ICON_SUCCESS
			});
		}
	});

	socket.on('orderCancelFailed', function (result) {
		$body.removeClass("loading");
		if (result == false) {
			$.sweetModal({
				content: '오류가 발생하였습니다.',
				icon: $.sweetModal.ICON_WARNING
			});
		}
	});


	//----------------------------My Order History--------------------------------------
	socket.on('updateMyOrderHistoryData', function (myOrderHistoryData) {

		if (myOrderHistoryData.target == target && myOrderHistoryData.base == base) {
			var value = myOrderHistoryData;
			var datatableMyOrderHistoryHtml = '';
			var tempTrStyle = '';
			var fee = 0;
			if (value.type == 'buy') {
				var className = 'span-red';
				var typeHtml = lang_buy[lang];
			} else {
				var className = 'span-blue';
				var typeHtml = lang_sell[lang];
				tempTrStyle = 'background-color:#f4f7f9;';
			}
			var regdateDateTime = value.regdate;
			var regdateTemp = regdateDateTime.split(' ');
			var tempTargetVolume = parseFloat(value.targetVolume);
			if (value.type == 'sell') {
				fee = tempTargetVolume * 0.0015;
				$("#tempNumberFormatter").html(fee);
				$("#tempNumberFormatter").number(true, 8);
				fee = $("#tempNumberFormatter").html();
			}
			tempTargetVolume = tempTargetVolume.toFixed(3);
			$("#tempNumberFormatter").html(value.rate);
			$("#tempNumberFormatter").number(true, unitDecimal);
			var tempNumberFormatter = $("#tempNumberFormatter").html();

			$("#tempNumberFormatter").html(value.baseVolume);
			$("#tempNumberFormatter").number(true, unitDecimal);
			var tempBaseVolume = $("#tempNumberFormatter").html();
			if (value.type == 'buy') {
				fee = tempBaseVolume * 0.0015;
				$("#tempNumberFormatter").html(fee);
				$("#tempNumberFormatter").number(true, unitDecimal);
				fee = $("#tempNumberFormatter").html();
			}
			$("#datatableMyOrderHistoryEmptyDiv").css('display', 'none');
			datatableMyOrderHistoryHtml += '<tr style="' + tempTrStyle + '">';
			datatableMyOrderHistoryHtml += '<td style="width:125px;text-align:center;"><span class="datatable-datetime-lineheight">' + regdateTemp[0] + ' ' + regdateTemp[1] + '</span></td>';
			datatableMyOrderHistoryHtml += '<td style="width:40px;text-align:center;"><span class="' + className + '">' + typeHtml + '</span></td>';
			datatableMyOrderHistoryHtml += '<td style="width:80px;text-align:right;"><span class="' + className + '">' + tempNumberFormatter + '</span></td>';
			datatableMyOrderHistoryHtml += '<td style="width:80px;text-align:right;"><span class="' + className + '">' + tempTargetVolume + '</span></td>';
			datatableMyOrderHistoryHtml += '<td style="width:80px;text-align:right;"><span class="' + className + '">' + fee + '</span></td>';
			datatableMyOrderHistoryHtml += '<td style="width:85px;text-align:right;padding-right:10px;"><span class="' + className + '">' + tempBaseVolume + '</span></td>';
			datatableMyOrderHistoryHtml += '</tr>';
			$('#datatableMyOrderHistory > tbody > tr:first').before(datatableMyOrderHistoryHtml);
		}
	});
	//---------------------------------------------------------------------------------------------------
	//--------------------------My Open Orders-----------------------------------------
	socket.on('updateMyOpenOrderData', function (myOpenOrderData) {
		var status = true;
		if (myOpenOrderData.length == 1){
			$.each(myOpenOrderData, function (index, value) {
				if (value.target == target && value.base == base && (value.id == 'NAN' || value.id == '')) {
					$("#datatableMyOpenOrders tbody").html('<tr id="datatableMyOpenOrdersEmptyDiv"><td colspan="6"><div class="empty-panel" style="width:500px;height:250px;"><p class="empty-panel-title" style="padding-top: 35%;">' + lang_msg_1[lang] + '</p></div></td></tr>');
					status = false;
				}
			});
		}
		if (status == true) {
			if (myOpenOrderData.length > 0) {
				var datatableMyOpenOrdersHtml = '';
				$("#datatableMyOpenOrdersEmptyDiv").css('display', 'none');
				$.each(myOpenOrderData, function (index, value) {
					if (myOpenOrderData.length == 1 && value.id == '') temp == true;
					if (value.target == target && value.base == base) {
						var trTempStyle = "";
						if (value.type == 'buy') {
							var className = 'span-red';
							var typeHtml = lang_buy[lang];
							var tempStyle = "background-color:#cc0000;";
						} else {
							var className = 'span-blue';
							var typeHtml = lang_sell[lang];
							var tempStyle = "background-color:#065ec2;";
							var trTempStyle = "background-color:#f4f7f9;";
						}
						var regdateDateTime = value.regdate;
						var regdateTemp = regdateDateTime.split(' ');
						$("#tempNumberFormatter").html(value.rate);
						$("#tempNumberFormatter").number(true, unitDecimal);
						var tempNumberFormatter = $("#tempNumberFormatter").html();

						datatableMyOpenOrdersHtml += '<tr style="' + trTempStyle + '">';
						datatableMyOpenOrdersHtml += '<td style="width:135px;text-align:center;"><span class="datatable-datetime-lineheight">' + regdateTemp[0] + ' ' + regdateTemp[1] + '</span></td>';
						datatableMyOpenOrdersHtml += '<td style="width:40px;text-align:center;"><span class="' + className + '">' + typeHtml + '</span></td>';
						datatableMyOpenOrdersHtml += '<td style="width:80px;text-align:right;"><span class="' + className + '">' + tempNumberFormatter + '</span></td>';
						datatableMyOpenOrdersHtml += '<td style="width:80px;text-align:right;"><span class="' + className + '">' + value.originalTargetVolume + '</span></td>';
						datatableMyOpenOrdersHtml += '<td style="width:80px;text-align:right;"><span class="' + className + '">' + value.targetVolume + '</span></td>';
						if (lang == 'EN') {
							datatableMyOpenOrdersHtml += '<td style="width:75px;text-align:center;"><span class="date badge badge-my-order-delete" style="' + tempStyle + ' letter-spacing:1px !important;padding-left:0px !important;" onclick="cancel_my_open_order(' + "'" + value.id + "'" + ')">' + lang_cancel[lang] + '</span></td>';
						} else {
							datatableMyOpenOrdersHtml += '<td style="width:75px;text-align:center;"><span class="date badge badge-my-order-delete" style="' + tempStyle + '" onclick="cancel_my_open_order(' + "'" + value.id + "'" + ')">' + lang_cancel[lang] + '</span></td>';
						};
						datatableMyOpenOrdersHtml += '</tr>';
					}
				});
				if (myOpenOrderData[0].target == target && myOpenOrderData[0].base == base) {
					$("#datatableMyOpenOrders tbody").html('');
					$("#datatableMyOpenOrders tbody").html(datatableMyOpenOrdersHtml);
				}
			} else {
				$("#datatableMyOpenOrders tbody").html('<tr id="datatableMyOpenOrdersEmptyDiv"><td colspan="6"><div class="empty-panel" style="width:500px;height:250px;"><p class="empty-panel-title" style="padding-top: 35%;">' + lang_msg_1[lang] + '</p></div></td></tr>');
			}
		}
	});
	//---------------------------------------------------------------------------------------------------

	socket.on('updateOrderBookMyTargetVolume', function (data) {
		var type = data.type;
		var no = data.no;
		if (data.target == target && data.base == base) {
			var myTargetVolume = data.myTargetVolume;
			if (myTargetVolume == '') {
				if (type == 'buy') {
					$("#orderBookBuyOrder_" + no + "_myTVolume").html('');
				} else {
					$("#orderBookSellOrder_" + no + "_myTVolume").html('');
				}
			} else {
				myTargetVolume = parseFloat(myTargetVolume).toFixed(8);
				if (type == 'buy') {
					$("#orderBookBuyOrder_" + no + "_myTVolume").html(myTargetVolume);
				} else {
					$("#orderBookSellOrder_" + no + "_myTVolume").html(myTargetVolume);
				}
			}
		}
	});

	socket.on('update_my_target_volume', function (my_volume_data) {
		$.each(my_volume_data, function (index, data) {
			var type = data.type;
			var no = data.no;
			if (data.target == target && data.base == base) {
				var myTargetVolume = data.myTargetVolume;
				if (myTargetVolume == '') {
					if (type == 'buy') {
						$("#orderBookBuyOrder_" + no + "_myTVolume").html('');
					} else {
						$("#orderBookSellOrder_" + no + "_myTVolume").html('');
					}
				} else {
					myTargetVolume = parseFloat(myTargetVolume).toFixed(8);
					if (type == 'buy') {
						$("#orderBookBuyOrder_" + no + "_myTVolume").html(myTargetVolume);
					} else {
						$("#orderBookSellOrder_" + no + "_myTVolume").html(myTargetVolume);
					}
				}
			}
		});
	});

	//------------------------------orderbook----------------------------------------------------------------

	socket.on('updateOrderBook', function (orderBookData) {

		var sellOrders = orderBookData.sell;
		var maxTargetVolume = 0;
		$.each(sellOrders, function (index, value) {
			if (maxTargetVolume < parseFloat(value.targetVolume)) {
				maxTargetVolume = parseFloat(value.targetVolume);
			}
		});

		var sellOrdersSum = 0,
			buyOrdersSum = 0;

		$.each(sellOrders, function (index, value) {
			var no = index + 1;
			var targetVolume = parseFloat(value.targetVolume);

			var tempTargetVolume = targetVolume.toFixed(3);
			if ((tempTargetVolume).length > 9) {
				tempTargetVolume = targetVolume.toFixed(0);
			}

			$("#orderBookSellOrder_" + no + "_tVolume").html(tempTargetVolume);
			if (maxTargetVolume > 0) {
				var shapeWidth = parseFloat(targetVolume) / maxTargetVolume * 120;
				$("#orderBookSellOrder_" + no + "_tVolume").css('width', shapeWidth + 'px');
			}
			$("#orderBookSellOrder_" + no + "_rate").html(value.rate);
			$("#orderBookSellOrder_" + no + "_rate").val(parseFloat(value.rate));
			if (parseFloat(value.rate) == parseFloat(currentRate)) {
				$("#orderBookSellOrder_" + no + "_rate").parent().attr('class', 'orderBookSellOrders orderbook_currentRate_rate');
			} else {
				$("#orderBookSellOrder_" + no + "_rate").parent().attr('class', 'orderBookSellOrders');
			}
			if (parseFloat(value.rate) != 0 && value.rate != '') {
				$("#orderBookSellOrder_" + no + "_rate").number(true, unitDecimal);
			}
			if (targetVolume > 0) {
				$("#orderBookSellOrder_" + no + "_tVolume").css('display', 'inline');
				$("#orderBookSellOrder_" + no + "_rate").css('display', 'inline');
			} else {
				$("#orderBookSellOrder_" + no + "_tVolume").css('display', 'none');
				$("#orderBookSellOrder_" + no + "_rate").css('display', 'none');
			}
		})
		var buyOrders = orderBookData.buy;
		var maxTargetVolume = 0;
		$.each(buyOrders, function (index, value) {
			if (maxTargetVolume < parseFloat(value.targetVolume)) {
				maxTargetVolume = parseFloat(value.targetVolume);
			}
		})
		$.each(buyOrders, function (index, value) {
			var no = index + 1;
			var targetVolume = parseFloat(value.targetVolume);

			var tempTargetVolume = targetVolume.toFixed(3);
			if ((tempTargetVolume).length > 9) {
				tempTargetVolume = targetVolume.toFixed(0);
			}
			
			$("#orderBookBuyOrder_" + no + "_rate").html(value.rate);
			$("#orderBookBuyOrder_" + no + "_tVolume").html(tempTargetVolume);
			if (maxTargetVolume > 0) {
				var shapeWidth = parseFloat(targetVolume) / maxTargetVolume * 80;
				$("#orderBookBuyOrder_" + no + "_tVolume").css('width', shapeWidth + 'px');
			}
			$("#orderBookBuyOrder_" + no + "_rate").val(parseFloat(value.rate));

			if (parseFloat(value.rate) == parseFloat(currentRate)) {
				$("#orderBookBuyOrder_" + no + "_rate").parent().attr('class', 'orderBookBuyOrders orderbook_currentRate_rate');
			} else {
				$("#orderBookBuyOrder_" + no + "_rate").parent().attr('class', 'orderBookBuyOrders');
			}

			if (parseFloat(value.rate) != 0 && value.rate != '') {
				$("#orderBookBuyOrder_" + no + "_rate").number(true, unitDecimal);
			}
			if (targetVolume > 0) {
				$("#orderBookBuyOrder_" + no + "_tVolume").css('display', 'inline');
				$("#orderBookBuyOrder_" + no + "_rate").css('display', 'inline');
			} else {
				$("#orderBookBuyOrder_" + no + "_tVolume").css('display', 'none');
				$("#orderBookBuyOrder_" + no + "_rate").css('display', 'none');
			}
		});

		temp = sellOrders[sellOrders.length - 1].rate;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true, unitDecimal);
		temp = $("#tempNumberFormatter").html();
		$("#marketSummary_buy").html(temp);

		temp = buyOrders[0].rate;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true, unitDecimal);
		temp = $("#tempNumberFormatter").html();
		$("#marketSummary_sell").html(temp);

		for (var i = 0; i < sellOrders.length; i++) {

			if (sellOrders[i].targetVolume == "")
				continue;

			sellOrdersSum += parseFloat(sellOrders[i].targetVolume);
		}
		for (var i = 0; i < buyOrders.length; i++) {

			if (buyOrders[i].targetVolume == "")
				continue;

			buyOrdersSum += parseFloat(buyOrders[i].targetVolume);
		}

		$('#orderBook_totalSellVolume').html(parseFloat(sellOrdersSum).toFixed(3));
		$('#orderBook_totalbuyVolume').html(parseFloat(buyOrdersSum).toFixed(3));
		//------------------------------orderbook----------------------------------------------------------------
		//--------orderchart----------
		var categories_data = [];
		var data_buy_volume = [];
		var data_sell_volume = [];
		//----------------------------
		for (var i = buyOrders.length - 1; i >= 0; i--) {

			if (buyOrders[i].rate != "") {
				categories_data.push(parseFloat(buyOrders[i].rate));
				var buyTotalVolume = 0;
				for (var j = 0; j <= i; j++) {
					buyTotalVolume += parseFloat(buyOrders[j].targetVolume);
				}
				data_buy_volume.push(parseFloat(buyTotalVolume.toFixed(3)));
				data_sell_volume.push(null);
			}

		}
		for (var i = sellOrders.length - 1; i >= 0; i--) {

			if (sellOrders[i].rate != "") {
				categories_data.push(parseFloat(sellOrders[i].rate));
				var sellTotalVolume = 0;
				for (var j = sellOrders.length - 1; j >= i; j--) {
					sellTotalVolume += parseFloat(sellOrders[j].targetVolume);
				}
				data_sell_volume.push(parseFloat(sellTotalVolume.toFixed(3)));
				data_buy_volume.push(null);
			}

		}
	});

	socket.on('updateMarketListByfavMarkets', function (favMarkets) {
		$("#searchByFavImg").attr('class', "favMarketSearchImgActivated");
		$.each(coinList, function (index, coin) {
			var is_fav = 0;
			$.each(favMarkets, function (key, coinData) {
				if (coinData.target == coin) {
					is_fav = 1;
				}
			});
			$("#marketListTr_" + coin).removeClass('display-block');
			$("#marketListTr_" + coin).removeClass('display-none');
			if (is_fav == 0) {
				$("#marketListTr_" + coin).addClass('display-none');
			}
		})
	})

	socket.on('updateDayHistoryData', function (data) {
		var flag = false;
		$(".dailyMarketHistoryDate").each(function (index) {
			if ($(this).html() == data.regDate) {
				flag = true;
			}
		});
		var temp = data.diff;
		var className = 'span-grey';
		var tempTrStyle = '';
		if (temp > 0) {
			className = 'span-red';
		} else if (temp < 0) {
			className = 'span-blue';
			tempTrStyle = 'background-color:#f4f7f9;';
		}
		var tbodyAddHtml = '';
		tbodyAddHtml += '<tr style="' + tempTrStyle + '">';
		tbodyAddHtml += '<td style="width:164px;text-align:center;"><span class="dailyMarketHistoryDate">' + data.regDate + '</span></td>';

		temp = data.close;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true, unitDecimal);
		temp = $("#tempNumberFormatter").html();
		tbodyAddHtml += '<td style="width:164px;text-align:center;"><span class="' + className + '">' + temp + '</span></td>';

		temp = data.diff;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true, unitDecimal);
		temp = $("#tempNumberFormatter").html();
		tbodyAddHtml += '<td style="width:164px;text-align:right;padding-right:50px;"><span class="' + className + '">' + temp + '</span></td>';

		temp = data.percent;
		if (temp > 0) {
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true, 2);
			temp = $("#tempNumberFormatter").html();
			temp = '+' + temp + '%';
		} else if (temp < 0) {
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true, 2);
			temp = $("#tempNumberFormatter").html();
			temp = '-' + temp + '%';
		} else {
			$("#tempNumberFormatter").html(temp);
			$("#tempNumberFormatter").number(true, 2);
			temp = $("#tempNumberFormatter").html();
			temp = temp + '%';
		}
		tbodyAddHtml += '<td style="width:164px;text-align:right;padding-right:50px;"><span class="' + className + '">' + temp + '</span></td>';

		var temp = data.tVolume;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true, 8);
		temp = $("#tempNumberFormatter").html();
		tbodyAddHtml += '<td style="width:164px;text-align:right;padding-right:50px;"><span>' + temp + '</span></td>';

		var temp = data.bVolume;
		$("#tempNumberFormatter").html(temp);
		$("#tempNumberFormatter").number(true, unitDecimal);
		temp = $("#tempNumberFormatter").html();
		tbodyAddHtml += '<td style="width:164px;text-align:right;padding-right:50px;"><span>' + temp + '</span></td>';

		tbodyAddHtml += '</tr>';
		$("#datatableDailyMarketHistoryEmptyDiv").css('display', 'none');
		if (flag == true) {
			$('#datatableDailyMarketHistory > tbody > tr:first').replaceWith(tbodyAddHtml);
		} else {
			$('#datatableDailyMarketHistory > tbody > tr:first').before(tbodyAddHtml);
		}

	})

	socket.on('updateMarketHistory', function (marketHistoryDataList) {
		$.each(marketHistoryDataList, function (index, marketHistoryData) {
			var rate = marketHistoryData.rate;
			currentRate = parseFloat(rate);
			$("#tempNumberFormatter").html(currentRate);
			$("#tempNumberFormatter").number(true, unitDecimal);
			var currentRateHtml = $("#tempNumberFormatter").html();
			document.title = currentRateHtml + ' ' + target + '/' + base;
			var targetVolume = marketHistoryData.targetVolume;
			var baseVolume = marketHistoryData.baseVolume;
			var regdate = marketHistoryData.regdate;
			var type = marketHistoryData.type;
			var tempTrStyle = '';
			if (type == 'buy') {
				var className = 'span-red';
			} else {
				var className = 'span-blue';
				tempTrStyle = 'background-color:#f4f7f9;';
			}
			var tempTargetVolume = parseFloat(targetVolume).toFixed(8);

			$("#tempNumberFormatter").html(rate);
			$("#tempNumberFormatter").number(true, unitDecimal);
			var tempNumberFormatter = $("#tempNumberFormatter").html();
			var tempRate = tempNumberFormatter;

			$("#tempNumberFormatter").html(baseVolume);
			$("#tempNumberFormatter").number(true, unitDecimal);
			tempNumberFormatter = $("#tempNumberFormatter").html();
			var tempBaseVolume = tempNumberFormatter;

			var tbodyAddHtml = '';
			tbodyAddHtml += '<tr style="' + tempTrStyle + '">';
			tbodyAddHtml += '<td style="width:245px;text-align:center;">' + regdate + '</td>';
			tbodyAddHtml += '<td style="width:245px;text-align:center;"><span class="' + className + '" id="">' + tempRate + '</span></td>';
			tbodyAddHtml += '<td style="width:245px;text-align:center;"><span class="' + className + '">' + tempTargetVolume + '</span></td>';
			tbodyAddHtml += '<td style="width:245px;text-align:right;padding-right:100px;"><span class="' + className + '">' + tempBaseVolume + '</span></td>';
			tbodyAddHtml += '</tr>';
			$('#datatableMarketHistory > tbody > tr:first').before(tbodyAddHtml);
			tempTargetVolume = parseFloat(targetVolume).toFixed(3);

			$("#datatableMarketHistoryEmptyDiv").css('display', 'none');
			tbodyAddHtml = '';
			tbodyAddHtml += '<tr>';
			tbodyAddHtml += '<td><span style="float:left;" class="' + className + '">' + tempRate + '</span></td>';
			tbodyAddHtml += '<td style="text-align:right;"><span class="' + className + '">' + tempTargetVolume + '</span></td>';
			tbodyAddHtml += '</tr>';
			$('#datatableMinMarketHistory > tbody > tr:first').before(tbodyAddHtml);
			$('#datatableMinMarketHistory > tbody > tr:last').remove();
		});
	});
	socket.on('updateDailyMarketHistory', function (dailyMarketHistoryData) {

	});

	$("#create_order_buy").click(function () {
		if (is_login == "true") {
			order_buy_rate = $("#order_buy_rate").val();
			if (makeNumber(order_buy_rate) > 100000000000) {
				$.sweetModal({
					content: lang_msg_buy_amount_overflow[lang],
					icon: $.sweetModal.ICON_WARNING
				});
				return;
			} else if (makeNumber(order_buy_rate) < 1) {
				$.sweetModal({
					content: lang_msg_order_failed_less_order_volume[lang],
					icon: $.sweetModal.ICON_WARNING
				});
				return;
			}
			order_buy_rate = parseFloat(order_buy_rate / hoga_unit) * hoga_unit;
			$("#order_buy_rate").val(order_buy_rate);
			order_buy_amount = $("#order_buy_amount").val();
			order_buy_price = order_buy_rate * order_buy_amount;
			if (order_buy_rate > 0 && order_buy_amount > 0 && order_buy_price > 0) {
				if((target == 'SKY' && base == 'KRW') || (target == 'BDR' && base == 'KRW')){
					if (order_buy_amount < 1) {
						$.sweetModal({
							content: lang_msg_input_rate_and_price[lang],
							icon: $.sweetModal.ICON_WARNING
						});
						return;
					}
				}
				if (is_clicked == 0) {
					$body.addClass("loading");
					$.ajax({
						url: base_url + 'api/order_process/create_order',
						type: 'POST',
						data: {
							order_type: 'buy',
							base: base,
							target: target,
							order_price: order_buy_price,
							order_rate: order_buy_rate,
							order_amount: order_buy_amount,
							exchange_fee: exchange_fee
						},
						dataType: 'json',
						success: function (resultData) {
							is_clicked = 0;
						}
					});
					is_clicked = 1;
				}
			} else {
				$.sweetModal({
					content: lang_msg_input_rate_and_price[lang],
					icon: $.sweetModal.ICON_WARNING
				});
			}
		} else {
			window.location.href = base_url + 'acnt/siin';
		}
	});

	$("#create_order_sell").click(function () {
		if (is_login == "true") {
			order_sell_rate = $("#order_sell_rate").val();
			if (makeNumber(order_sell_rate) > 100000000000) {
				$.sweetModal({
					content: lang_msg_buy_amount_overflow[lang],
					icon: $.sweetModal.ICON_WARNING
				});
				return;
			} else if (makeNumber(order_sell_rate) < 1) {
				$.sweetModal({
					content: lang_msg_order_failed_less_order_volume[lang],
					icon: $.sweetModal.ICON_WARNING
				});
				return;
			}
			order_sell_rate = parseFloat(order_sell_rate / hoga_unit) * hoga_unit;
			$("#order_sell_rate").val(order_sell_rate);
			order_sell_amount = $("#order_sell_amount").val();
			order_sell_price = order_sell_rate * order_sell_amount;
			if (order_sell_rate > 0 && order_sell_amount > 0 && order_sell_price > 0) {
				if ((target == 'SKY' && base == 'KRW') || (target == 'BDR' && base == 'KRW')) {
					if (order_sell_amount < 1) {
						$.sweetModal({
							content: lang_msg_input_rate_and_price[lang],
							icon: $.sweetModal.ICON_WARNING
						});
						return;
					}
				}
				if (is_clicked == 0) {
					$body.addClass("loading");
					$.ajax({
						url: base_url + 'api/order_process/create_order',
						type: 'POST',
						data: {
							order_type: 'sell',
							base: base,
							target: target,
							order_price: order_sell_price,
							order_rate: order_sell_rate,
							order_amount: order_sell_amount,
							exchange_fee: exchange_fee
						},
						dataType: 'json',
						success: function (resultData) {
							is_clicked = 0;
						}
					});
					is_clicked = 1;
				}
			} else {
				$.sweetModal({
					content: lang_msg_input_rate_and_price[lang],
					icon: $.sweetModal.ICON_WARNING
				});
			}
		} else {
			window.location.href = base_url + 'acnt/siin';
		}
	});

	$(".favMarketSearch").mouseover(function () {
		var searchByFavImg = $("#searchByFavImg");
		var className = searchByFavImg[0].className;
		if (className != 'favMarketSearchImgActivated') {
			$("#searchByFavImg").attr('class', 'favMarketSearchImg active');
		}
	});

	$(".favMarketSearch").mouseleave(function () {
		var searchByFavImg = $("#searchByFavImg");
		var className = searchByFavImg[0].className;
		if (className != 'favMarketSearchImgActivated') {
			$("#searchByFavImg").attr('class', 'favMarketSearchImg passive');
		}
	});

	$(".favMarketSearch").click(function () {
		var searchByFavImg = $("#searchByFavImg");
		var className = searchByFavImg[0].className;
		if (is_login == "true") {
			if (className == 'favMarketSearchImgActivated') {
				$("#searchByFavImg").attr('class', "favMarketSearchImg passive");
				$.each(coinList, function (index, coin) {
					$("#marketListTr_" + coin).removeClass('display-none');
					$("#marketListTr_" + coin).removeClass('display-block');
				})
			} else {
				socket.emit('getfavMarkets', token);
			}
		}
	})

});

function cancel_my_open_order(f_id) {
	var token = $("#token").val();
	$(".button.greenB").html(lang_yes[lang]);
	$(".button.redB.bordered.flat").html(lang_no[lang]);
	$.sweetModal.confirm(lang_msg_cancel_order_confirm[lang], function () {
		$body.addClass("loading");
		$.ajax({
			url: base_url + 'api/order_process/cancel_order',
			type: 'POST',
			data: {
            	f_id : f_id
			},
			dataType: 'json',
			success: function (resultData) {
				is_clicked = 0;
			}
		});
	});
}

function changeFavMarket(target, base) {
	var thisDiv = $("#marketList_favMarket_" + target);
	var className = thisDiv[0].className;
	if (className == 'isFav active') {
		var status = 0;
		$("#marketList_favMarket_" + target).removeClass("active");
		$("#marketList_favMarket_" + target).addClass("passive");
	} else {
		var status = 1;
		$("#marketList_favMarket_" + target).removeClass("passive");
		$("#marketList_favMarket_" + target).addClass("active");
	}
	var data = {
		token: token,
		target: target,
		base: base,
		status: status
	}
	socket.emit('changeFavMarket', data);
}
