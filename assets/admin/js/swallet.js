$(document).ready(function(){
    var key = 'total';
    var siteProfitHistory = $('#siteProfitHistory').DataTable({
        "ajax": base_url+"admin/ajaxGetSiteProfitHistory?fromDate=&toDate=&unit=&orderType=",
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
			"targets": 3, // your case first column
			"className": "text-right"
		},{
			"targets": 0,
			"className": "text-center",
		},{
			"targets": 1,
			"className": "text-center",
		},{
			"targets": 2,
			"className": "text-center",
		},{
			"targets": 4,
			"className": "text-center",
		}]
	});	
	siteProfitHistory
	    .order( [ 0, 'desc' ] )
			.draw();
			
	$("#filterSearch").click(function(){
		var searchByFromDate = $("#searchByFromDate").val();
		var searchByToDate = $("#searchByToDate").val();
		var searchByUnit = $("#searchByUnit").val();
		var searchByType = $("#searchByType").val();
		siteProfitHistory.ajax.url(base_url+'admin/ajaxGetSiteProfitHistory?fromDate='+searchByFromDate+'&toDate='+searchByToDate+'&unit='+searchByUnit+'&type='+searchByType).load();
	});
});
$(function() {
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
    });
});

