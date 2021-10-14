$(document).ready(function(){
    var key = 'total';
    var datatableEthDropHistory = $('#datatableEthDropHistory').DataTable({
        "ajax": base_url+"admin/ajaxGetEthDropHistory?fromDate=&toDate=",
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
			"targets": 0,
			"className": "text-center",
		},{
			"targets": 1,
			"className": "text-center",
		},{
			"targets": 2, // your case first column
			"className": "text-center"
		},{
			"targets": 3,
			"className": "text-right",
		},{
			"targets": 4,
			"className": "text-right",
		},{
			"targets": 5,
			"className": "text-right",
		},{
			"targets": 6,
			"className": "text-right",
		},{
			"targets": 7,
			"className": "text-right",
		},{
			"targets": 8,
			"className": "text-right",
		},{
			"targets": 9,
			"className": "text-right",
		},{
			"targets": 10,
			"className": "text-right",
		}]
	});	
	datatableEthDropHistory
	    .order( [ 0, 'desc' ] )
			.draw();
			
	$("#filterSearch").click(function(){
		var searchByFromDate = $("#searchByFromDate").val();
		var searchByToDate = $("#searchByToDate").val();
		datatableEthDropHistory.ajax.url(base_url+'admin/ajaxGetEthDropHistory?fromDate='+searchByFromDate+'&toDate='+searchByToDate).load();
	});
});
$(function() {
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
    });
});

