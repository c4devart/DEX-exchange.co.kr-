var userWalletList;
$(document).ready(function(){
	userWalletList = $('#userWalletList').DataTable({
		"ajax": base_url+"admin/ajaxUserWalletList",
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
			"targets": 4,
			"className": "text-right",
		},{
			"targets": 5,
			"className": "text-right",
		},{
			"targets": 6,
			"className": "text-right",
		}],
		"pageLength": 25
	});
})
