function exportTableToCSV(filename, tableId) {
	$("#"+tableId).table2csv();
}

$(function() {
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
    });
});

function cancel_my_open_order(f_id){
	var token = $("#token").val();
	$(".button.greenB").html(lang_yes[lang]);
	$(".button.redB.bordered.flat").html(lang_no[lang]);
	$.sweetModal.confirm(lang_msg_cancel_order_confirm[lang], function() {             
		$.ajax({
			url: base_url + 'api/order_process/cancel_order',
			type: 'POST',
			data: {
            	f_id : f_id
			},
			dataType: 'json',
			success: function (resultData) {

			}
		});
	});
}

function edit_my_open_order(f_id, f_target_volume){
	var token = $("#token").val();
	$.sweetModal.prompt(lang_msg_edit_target_volume[lang], lang_msg_ask_target_volume[lang], f_target_volume, function(val) {
		$.ajax({
			url: base_url + 'api/order_process/edit_order',
			type: 'POST',
			data: {
				f_id : f_id,
				targetVolume : val
			},
			dataType: 'json',
			success: function (resultData) {

			}
		});
	});
}

function changeTabToMyOrderHistory(){
	$(".tab-basic").each(function(index, value){
		$(value).attr('class', 'tab-basic');
	});
	$("#specTabMyOrderHistory").attr('class', 'tab-basic active');
}




var token = $("#token").val();

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

socket.on('connect', function(data) {
    var socketData = {
        page : 'balance',
        token : token
    };
    socket.emit('joinOther', socketData);
});



$.sweetModal.defaultSettings.confirm.yes.label = lang_yes[lang];
$.sweetModal.defaultSettings.confirm.ok.label = lang_yes[lang];
$.sweetModal.defaultSettings.confirm.cancel.label = lang_cancel[lang];

$(document).ready(function(){

	drawBalanceStatusChart();

	jQuery('.datatable-myopenorders-content').scrollbar();

	var balance_quiz_click = 0;
	$("#balance_quiz").click(function(){
		if(balance_quiz_click == 0){
			$("#balance_answer").css('display','block');
			balance_quiz_click = 1;
		}else{
			$("#balance_answer").css('display','none');
			balance_quiz_click = 0;
		}
	})
	var analyze_quiz_click = 0;
	$("#analyze_quiz").click(function(){
		if(analyze_quiz_click == 0){
			$("#analyze_answer").css('display','block');
			analyze_quiz_click = 1;
		}else{
			$("#analyze_answer").css('display','none');
			analyze_quiz_click = 0;
		}
	})
	var intval_analyze_quiz_click = 0;
	$("#intval_analyze_quiz").click(function(){
		if(intval_analyze_quiz_click == 0){
			$("#intval_analyze_answer").css('display','block');
			intval_analyze_quiz_click = 1;
		}else{
			$("#intval_analyze_answer").css('display','none');
			intval_analyze_quiz_click = 0;
		}
	})
	var token = $("#token").val();
	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var datatableIntvalBalanceCalc = $('#datatableIntvalBalanceCalc').DataTable({
        "ajax": base_url+'walt/getIntvalBalanceCalc/'+token
    });
    var datatableOpenOrders;
    $.ajax({
        url: base_url+'walt/getMyOpenOrdersCount/'+token,
        type: 'GET',
        dataType : 'json',
        success: function(result_data) {
        	$("#total_count_my_open_orders").html(result_data);
        	if(result_data > 0){
        		datatableOpenOrders = $('#datatableMyOpenOrders').DataTable({
					"ajax": base_url+'walt/getMyOpenOrders/'+token+'?fromDate=&toDate=&coin=&orderType=',
					"language": {
					  "paginate": {
						"previous": "<",
						"next": ">",
					  }
					},
					iDisplayLength: -1,
					'columnDefs': [
					{
						"targets": 0, // your case first column
						"className": "text-center",
						"width": "214px"
					},
					{
						"targets": 1,
						"className": "text-center",
						"width": "100px"
					},
					{
						"targets": 2,
						"className": "text-center",
						"width": "100px"
					},
					{
						"targets": 3,
						"className": "text-right",
						"width": "200px"
					},
					{
						"targets":4,
						"className": "text-right",
						"width": "200px"
					},
					{
						"targets": 5,
						"className": "text-right",
						"width": "200px"
					},
					{
						"targets": 6,
						"className": "text-right",
						"width": "200px"
					},
					{
						"targets": 7,
						"className": "text-center",
						"width": "80px"
					},
					{
						"targets": 8,
						"className": "text-center",
						"width": "80px"
					}]
				});

				datatableOpenOrders
				    .order( [ 0, 'desc' ] )
				    .draw();
        	}else{
        		$('#datatableMyOpenOrders tbody').html('<tr><td colspan="9"><div class="empty-panel" style="width:1360px;height:410px;"><p class="empty-panel-title" style="padding-top: 19%;">'+lang_msg_1[lang]+'</p></div></td></tr>');
        	}
		}
    });
	
	$("#datatableMyOpenOrdersFilterSearch").click(function(){
		var searchByFromDate = $("#datatableMyOpenOrdersFilterSearchByFromDate").val();
		var searchByToDate = $("#datatableMyOpenOrdersFilterSearchByToDate").val();
		var searchByCoin = $("#datatableMyOpenOrdersFilterSearchByCoin").val();
		var searchByOrderType = $("#datatableMyOpenOrdersFilterSearchByOrderType").val();
		datatableOpenOrders.ajax.url(base_url+'walt/getMyOpenOrders/'+token+'?fromDate='+searchByFromDate+'&toDate='+searchByToDate+'&coin='+searchByCoin+'&orderType='+searchByOrderType).load();
		$.ajax({
			url: base_url+'walt/getMyOpenOrdersCount/'+token+'?fromDate='+searchByFromDate+'&toDate='+searchByToDate+'&coin='+searchByCoin+'&orderType='+searchByOrderType,
			type: 'GET',
			dataType : 'json',
			success: function(result_data) {
				$("#total_count_my_open_orders").html(result_data);
			}
		});
	});

	$.ajax({
        url: base_url+'walt/getMyMarketHistoryCount/'+token,
        type: 'GET',
        dataType : 'json',
        success: function(result_data) {
        	$("#total_count_my_markethistory").html(result_data);
        	if(result_data > 0){
        		datatableMyMarketHistory = $('#datatableMyMarketHistory').DataTable({
					"ajax": base_url+'walt/getMyMarketHistory/'+token+'?fromDate=&toDate=&coin=&orderType=',
					"language": {
					  "paginate": {
						"previous": "<",
						"next": ">",
					  }
					},
					iDisplayLength: -1,
					'columnDefs': [
					{
						"targets": 0, // your case first column
						"className": "text-center",
						"width": "200px"
					},
					{
						"targets": 1,
						"className": "text-center",
						"width": "125px"
					},
					{
						"targets": 2,
						"className": "text-center",
						"width": "145px"
					},
					{
						"targets": 3,
						"className": "text-center",
						"width": "105px"
					},
					{
						"targets":4,
						"className": "text-right",
						"width": "205px"
					},
					{
						"targets": 5,
						"className": "text-right",
						"width": "205px"
					},
					{
						"targets": 6,
						"className": "text-right",
						"width": "205px"
					},
					{
						"targets": 7,
						"className": "text-right",
						"width": "165px"
					}]
				});
				datatableMyMarketHistory
				    .order( [ 0, 'desc' ] )
				    .draw();
        	}else{
        		$('#datatableMyMarketHistory tbody').html('<tr><td colspan="8"><div class="empty-panel" style="width:1360px;height:410px;"><p class="empty-panel-title" style="padding-top: 19%;">'+lang_msg_1[lang]+'</p></div></td></tr>');
        	}
		}
    });
	var datatableMyMarketHistory;

	$("#datatableMyMarketHistoryFilterSearch").click(function(){
		var searchByFromDate = $("#datatableMyMarketHistoryFilterSearchByFromDate").val();
		var searchByToDate = $("#datatableMyMarketHistoryFilterSearchByToDate").val();
		var searchByCoin = $("#datatableMyMarketHistoryFilterSearchByCoin").val();
		var searchByOrderType = $("#datatableMyMarketHistoryFilterSearchByOrderType").val();
		datatableMyMarketHistory.ajax.url(base_url+'walt/getMyMarketHistory/'+token+'?fromDate='+searchByFromDate+'&toDate='+searchByToDate+'&coin='+searchByCoin+'&orderType='+searchByOrderType).load();
		$.ajax({
			url: base_url+'walt/getMyMarketHistoryCount/'+token+'?fromDate='+searchByFromDate+'&toDate='+searchByToDate+'&coin='+searchByCoin+'&orderType='+searchByOrderType,
			type: 'GET',
			dataType : 'json',
			success: function(result_data) {
				$("#total_count_my_markethistory").html(result_data);
			}
		});
	})

	var datatableSKYPoolHistory;
	$.ajax({
        url: base_url+'walt/getSKYPoolHistoryCount/'+token,
        type: 'GET',
        dataType : 'json',
        success: function(result_data) {
        	$("#total_count_SKYPoolHistory").html(result_data);
        	if(result_data > 0){
        		datatableSKYPoolHistory = $('#datatableSKYPoolHistory').DataTable({
					"ajax": base_url+'walt/getSKYPoolHistory/'+token+'?fromDate=&toDate=',
					"language": {
					  "paginate": {
						"previous": "<",
						"next": ">",
					  }
					},
					iDisplayLength: -1
				});
				datatableSKYPoolHistory
				    .order( [ 0, 'desc' ] )
				    .draw();
			}else{
				$('#datatableSKYPoolHistory tbody').html('<tr><td colspan="6"><div class="empty-panel" style="width:1360px;height:410px;"><p class="empty-panel-title" style="padding-top: 19%;">'+lang_msg_1[lang]+'</p></div></td></tr>');
	        }
		}
    });
	$("#datatableSKYPoolHistoryFilterSearch").click(function(){
		var searchByFromDate = $("#datatableSKYPoolHistoryFilterSearchByFromDate").val();
		var searchByToDate = $("#datatableSKYPoolHistoryFilterSearchByToDate").val();
		$.ajax({
			url: base_url+'walt/getSKYPoolHistoryCount/'+token+'?fromDate='+searchByFromDate+'&toDate='+searchByToDate,
			type: 'GET',
			dataType : 'json',
			success: function(result_data) {
				$("#total_count_SKYPoolHistory").html(result_data);
				if (result_data > 0) {
					datatableSKYPoolHistory.ajax.url(base_url + 'walt/getSKYPoolHistory/' + token + '?fromDate=' + searchByFromDate + '&toDate=' + searchByToDate).load();
				}
			}
		});
	})
	var datatableETHairdropHistory;
	$.ajax({
        url: base_url+'walt/getETHairdropHistoryCount/'+token,
        type: 'GET',
        dataType : 'json',
        success: function(result_data) {
        	$("#total_count_ETHairdropHistory").html(result_data);
        	if(result_data > 0){
        		datatableETHairdropHistory = $('#datatableETHairdropHistory').DataTable({
					"ajax": base_url+'walt/getETHairdropHistory/'+token+'?fromDate=&toDate=&coin=&orderType=',
					"language": {
					  "paginate": {
						"previous": "<",
						"next": ">",
					  }
					}
				});
				datatableETHairdropHistory
				    .order( [ 0, 'desc' ] )
				    .draw();
			}else{
				$('#datatableETHairdropHistory tbody').html('<tr><td colspan="10"><div class="empty-panel" style="width:1360px;height:410px;"><p class="empty-panel-title" style="padding-top: 19%;">'+lang_msg_1[lang]+'</p></div></td></tr>');	
			}
		}
    });
	
	    
	$("#datatableETHairdropHistoryFilterSearch").click(function(){
		var searchByFromDate = $("#datatableETHairdropHistoryFilterSearchByFromDate").val();
		var searchByToDate = $("#datatableETHairdropHistoryFilterSearchByToDate").val();
		$.ajax({
			url: base_url+'walt/getETHairdropHistoryCount/'+token+'?fromDate='+searchByFromDate+'&toDate='+searchByToDate,
			type: 'GET',
			dataType : 'json',
			success: function(result_data) {
				$("#total_count_ETHairdropHistory").html(result_data);
				if(result_data > 0){
					datatableETHairdropHistory.ajax.url(base_url + 'walt/getETHairdropHistory/' + token + '?fromDate=' + searchByFromDate + '&toDate=' + searchByToDate).load();
				}
			}
		});
	});
	
	socket.on('orderCancelled', function (result) {
		$body.removeClass("loading");
		if(result == true){
			$.sweetModal({
				content: lang_msg_order_canceled[lang],
				icon: $.sweetModal.ICON_SUCCESS
			});
			datatableOpenOrders.ajax.reload(false);
		}
	});
	
	socket.on('changeOrderSucceed', function (result) {
		$body.removeClass("loading");
		$.sweetModal({
			content: lang_msg_change_order_succeed[lang],
			icon: $.sweetModal.ICON_SUCCESS
		});
		datatableOpenOrders.ajax.reload(false);
	});	
	
	socket.on('changeOrderFailed', function (result) {
		$body.removeClass("loading");
		var lang_msg_change_order_failed = lang_msg_change_order_failed_temp[lang];
		if(result.msg == 'less_order_volume'){
			lang_msg_change_order_failed += ' ' + lang_msg_order_failed_less_order_volume[lang];
		}else if(result.msg == 'less_base_balance'){
			lang_msg_change_order_failed += ' ' + lang_msg_order_failed_less_base_balance[lang];
		}else if(result.msg == 'less_target_balance'){
			lang_msg_change_order_failed += ' ' + lang_msg_order_failed_less_target_balance[lang];
		}else if(result.msg == 'the_same_volume'){
			lang_msg_change_order_failed += ' ' + lang_msg_same_amount[lang];
		}
		$.sweetModal({
			content: lang_msg_change_order_failed,
			icon: $.sweetModal.ICON_WARNING
		});
	});
	
	var datatableDepWith;
	$("#datatableDepWithFilterSearch").click(function(){
		var searchByFromDate = $("#datatableDepWithFilterSearchByFromDate").val();
		var searchByToDate = $("#datatableDepWithFilterSearchByToDate").val();
		var searchByCoin = $("#datatableDepWithFilterSearchByCoin").val();
		var searchByOrderType = $("#datatableDepWithFilterSearchByOrderType").val();
		datatableDepWith.ajax.url(base_url+'walt/getDeptWithHistory/'+token+'?fromDate='+searchByFromDate+'&toDate='+searchByToDate+'&coin='+searchByCoin+'&orderType='+searchByOrderType).load();
		$.ajax({
			url: base_url+'walt/getDeptWithHistoryCount/'+token+'?fromDate='+searchByFromDate+'&toDate='+searchByToDate+'&coin='+searchByCoin+'&orderType='+searchByOrderType,
			type: 'GET',
			dataType : 'json',
			success: function(result_data) {
				$("#total_count_depWith").html(result_data);
			}
		});
	});
	$.ajax({
		url: base_url+'walt/getDeptWithHistoryCount/'+token+'?fromDate=&toDate=&coin=&orderType=',
		type: 'GET',
		dataType : 'json',
		success: function(result_data) {
			$("#total_count_depWith").html(result_data);
			if(result_data > 0){
				datatableDepWith = $('#datatableDepWith').DataTable({
					"ajax": base_url+'walt/getDeptWithHistory/'+token+'?fromDate=&toDate=&coin=&orderType=',
					"language": {
					  "paginate": {
						"previous": "<",
						"next": ">",
					  }
					},
					iDisplayLength: -1,
					'columnDefs': [
					{
						"targets": 0, // your case first column
						"className": "text-center",
						"width": "215px"
					},
					{
						"targets": 1,
						"className": "text-center",
						"width": "250px"
					},
					{
						"targets": 2,
						"className": "text-center",
						"width": "100px"
					},
					{
						"targets": 3,
						"className": "text-right",
						"width": "220px"
					},
					{
						"targets":4,
						"className": "text-right",
						"width": "180px"
					},
					{
						"targets": 5,
						"className": "text-center",
						"width": "313px"
					},
					{
						"targets": 6,
						"className": "text-center",
						"width": "100px"
					}]
				});

				datatableDepWith
					.order([0, 'desc'])
					.draw();
			}else{
        		$('#datatableDepWith tbody').html('<tr><td colspan="7"><div class="empty-panel" style="width:1360px;height:410px;"><p class="empty-panel-title" style="padding-top: 19%;">'+lang_msg_1[lang]+'</p></div></td></tr>');
			}
		}
	});


});

function drawBalanceStatusChart(){
	$.ajax({
		url: base_url+'walt/getBalance',
		type: 'GET',
		dataType : 'json',
		success: function(resultData) {
			var chartData = [];
			var pushData;
			$.each(resultData, function(index, value){
				pushData = {
					"unit" : value.unit,
					"balance" : value.balance
				};
				chartData.push(pushData);
			});
		    am4core.useTheme(am4themes_animated);
		    var chart = am4core.create("chartdiv", am4charts.PieChart);
		    chart.data = chartData;
		    var pieSeries = chart.series.push(new am4charts.PieSeries());
		    pieSeries.dataFields.value = "balance";
		    pieSeries.dataFields.category = "unit";
		    pieSeries.innerRadius = am4core.percent(50);
		    pieSeries.ticks.template.disabled = true;
		    pieSeries.labels.template.disabled = true;
		    let rgm = new am4core.RadialGradientModifier();
		    rgm.brightnesses.push(-0.8, -0.8, -0.5, 0, - 0.5);
		    pieSeries.slices.template.fillModifier = rgm;
		    pieSeries.slices.template.strokeModifier = rgm;
		    pieSeries.slices.template.strokeOpacity = 0.4;
		    pieSeries.slices.template.strokeWidth = 0;
		    chart.legend = new am4charts.Legend();
		    chart.legend.position = "right";
		}
	});

	$("#downloadMyOpenOrders").click(function(e) {

	});
}
