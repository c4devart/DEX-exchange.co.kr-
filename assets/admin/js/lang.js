var languageList;
var checkedUserList = [];
var enableListInputBoxClicked = 0;
$(document).ready(function(){
    languageList = $('#languageList').DataTable({
        "ajax": base_url+"admin/ajaxlanguageList",
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
			"className": "text-left"
		},{
			"targets": 1,
			"className": "text-left",
		},{
			"targets": 2,
			"className": "text-left",
		},{
			"targets": 3,
			"className": "text-left",
		}],
		"pageLength": 25
	});

	$("#enableListInputBox").click(function(){
		if(enableListInputBoxClicked == 0){
			$(".languageListInputBox").each(function(index, value){
				$(value).prop('disabled', false);
			})
			$("#enableListInputBox").html(langData[10]);
			enableListInputBoxClicked = 1;
		}else{
			$(".languageListInputBox").each(function(index, value){
				$(value).prop('disabled', true);
			})
			$("#enableListInputBox").html(langData[11]);
			enableListInputBoxClicked = 0;
			languageList.ajax.reload(null, true);
		}
	})
});

function changeLanguage(event, id, input){
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13'){
		var value = $(input).val();
		$.ajax({
			url: base_url + 'admin/changeLanguage',
			type: 'POST',
			data: {
				id : id,
				value : value
			},
			dataType : 'json',
			success: function (result) {
				if (result.res == true) {
					alertify.success(result.msg);
				} else {
					alertify.error(result.msg);
				}
			}
		});
	}
}
