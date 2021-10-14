var datatableWithdrawHistory;
$(document).ready(function(){
    datatableWithdrawHistory = $('#datatableWithdrawHistory').DataTable({
        "ajax": base_url+"admin/ajaxGetUserWithdrawHistory?fromDate=&toDate=",
		"pageLength": 25,
		"language": {
		  	"paginate": {
				"previous": "<",
				"next": ">"
			},
			"info": "총 _TOTAL_ 중 _START_ 부터 _END_ 까지",
			"sLengthMenu": "_MENU_ "+langData[3],
			"sSearch": langData[2]
		},
		'columnDefs': [{
				"targets": 0, // your case first column
				"className": "text-center"
			}, {
				"targets": 3, // your case first column
				"className": "text-center"
			}, {
				"targets": 5, // your case first column
				"className": "text-right"
			}, {
				"targets": 6, // your case first column
				"className": "text-right"
			}, {
				"targets": 8, // your case first column
				"className": "text-center"
			}, {
			"targets": 7, // your case first column
			"className": "text-center"
		}],
	});	
	datatableWithdrawHistory
	    .order( [ 0, 'desc' ] )
			.draw();
			
	$("#filterSearch").click(function(){
		var searchByFromDate = $("#searchByFromDate").val();
		var searchByToDate = $("#searchByToDate").val();
		datatableWithdrawHistory.ajax.url(base_url+'admin/ajaxGetUserWithdrawHistory?fromDate='+searchByFromDate+'&toDate='+searchByToDate).load();
	});
});
$(function() {
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
    });
});

function processWithdraw(id){
	$.ajax({
		url: base_url + 'admin/processWithdraw',
		type: 'POST',
		data: {
			id: id
		},
		dataType: 'json',
		success: function (result) {
			if (result.res == true) {
				alertify.success(result.msg);
				datatableWithdrawHistory.ajax.reload(null, false);
			} else {
				alertify.error(result.msg);
			}
		}
	});
}

function deleteDepWithRequest(id) {
	$.ajax({
		url: base_url + 'admin/deleteDepWithRequest',
		type: 'POST',
		data: {
			id: id
		},
		dataType: 'json',
		success: function (result) {
			if (result.res == true) {
				alertify.success(result.msg);
				datatableWithdrawHistory.ajax.reload(null, false);
			} else {
				alertify.error(result.msg);
			}
		}
	});
}
