$(document).ready(function(){
    var key = 'total';
    var siteProfitHistory = $('#siteProfitHistory').DataTable({
        "ajax": base_url+"admin/ajaxGetSiteEarnHistory?fromDate=&toDate=",
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
		'rowsGroup': [0],
		'columnDefs': [{
			"targets": 0,
			"className": "text-center"
		}, {
			"targets": 1,
			"className": "text-left",
		}, {
			"targets": 2,
			"className": "text-right",
		}, {
			"targets": 3,
			"className": "text-right",
		}]
	});	
	siteProfitHistory
	    .order( [ 0, 'desc' ] )
			.draw();
			
	$("#filterSearch").click(function(){
		var searchByFromDate = $("#searchByFromDate").val();
		var searchByToDate = $("#searchByToDate").val();
		siteProfitHistory.ajax.url(base_url + 'admin/ajaxGetSiteEarnHistory?fromDate=' + searchByFromDate + '&toDate=' + searchByToDate).load();
	});
});
$(function() {
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
    });
});

