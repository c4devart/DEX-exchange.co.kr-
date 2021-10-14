var datatableDepositHistory;
$(document).ready(function(){
    datatableDepositHistory = $('#datatableDepositHistory').DataTable({
        "ajax": base_url+"admin/ajaXGetUserDepositHistory?fromDate=&toDate=",
		"pageLength": 25,
		"language": {
		  "paginate": {
			"previous": "<",
			"next": ">"
		  },
		  "info": "총 _TOTAL_ 중 _START_ 부터 _END_ 까지",
		  "sLengthMenu": "_MENU_ 행",
		  "sSearch": langData[2]
		},
		'columnDefs': [{
			"targets": 0, // your case first column
			"className": "text-center"
		}, {
			"targets": 3,
			"className": "text-center",
		}, {
			"targets": 5,
			"className": "text-right",
		}, {
			"targets": 6,
			"className": "text-center",
		}, {
			"targets": 7,
			"className": "text-center",
		}]
	});	
	datatableDepositHistory
	    .order( [ 0, 'desc' ] )
			.draw();
			
	$("#filterSearch").click(function(){
		var searchByFromDate = $("#searchByFromDate").val();
		var searchByToDate = $("#searchByToDate").val();
		datatableDepositHistory.ajax.url(base_url+'admin/ajaXGetUserDepositHistory?fromDate='+searchByFromDate+'&toDate='+searchByToDate).load();
	});
});
$(function() {
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
    });
});

function processDeposit(id) {
	$.ajax({
		url: base_url + 'admin/processDeposit',
		type: 'POST',
		data: {
			id: id
		},
		dataType: 'json',
		success: function (result) {
			if (result.res == true) {
				alertify.success(result.msg);
				datatableDepositHistory.ajax.reload(null, false);
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
				datatableDepositHistory.ajax.reload(null, false);
			} else {
				alertify.error(result.msg);
			}
		}
	});
}
