var userKycList;
var checkedUserList = [];
$(document).ready(function(){
    userKycList = $('#userKycList').DataTable({
        "ajax": base_url+"admin/ajaxUserKycList",
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
		},{
			"targets": 5,
			"className": "text-center",
		},{
			"targets": 6,
			"className": "text-center",
		},{
			"targets": 7,
			"className": "text-center",
		},{
			"targets": 8,
			"className": "text-center",
		}],
		"pageLength": 25
	});

	$("#userListSelectAll").change(function(){
		var checked = $(this).val();
		var temp;
		if(checked == 1){
			$(".checkUser").each(function(index, value){
				temp = checkedUserList.indexOf($(value).val());
				if (temp > -1) {
					checkedUserList.splice(temp, 1);
				}
				checkedUserList.push($(value).val());
				$(value).prop('checked',true);
			});
		}else{
			$(".checkUser").each(function(index, value){
				temp = checkedUserList.indexOf($(value).val());
				if (temp > -1) {
					checkedUserList.splice(temp, 1);
				}
				$(value).prop('checked',false);
			});
		}
	});

	$("#changeCheckedUserStatus").change(function(){
		var checked = $(this).val();
		if(checked > 0){
			if(checkedUserList.length > 0){
				$.ajax({
					url: base_url + 'admin/ajaxChangeMultiUserKycStatus',
					type: 'POST',
					data: {
						action : checked,
						ids : JSON.stringify(checkedUserList)
					},
					dataType : 'json',
					success: function (result) {
						if (result.res == true) {
							alertify.success(result.msg);
							userKycList.ajax.reload(null, false);
							checkedUserList = [];
						} else {
							alertify.error(result.msg);
						}
					}
				});
			}else{
				alert(langData[4]);
			}
		}
	});

});

function change_status(token, filed, input){
	var value = $(input).children("option:selected").val();
	$.ajax({
		url: base_url + 'admin/ajaxChangeUserStatus',
		type: 'POST',
		data: {
			token: token,
			filed: filed,
			value: value
		},
		dataType: 'json',
		success: function (return_data) {
			if (return_data.res == true) {
				alertify.success(return_data.msg);
				userKycList.ajax.reload(null, false);
			} else {
				alertify.error(return_data.msg);
			}
		}
	});
}

function changeCheckedStatus(input){
	var temp;
	if(input.checked){
		temp = checkedUserList.indexOf($(input).val());
		if (temp > -1) {
		  checkedUserList.splice(temp, 1);
		}
		checkedUserList.push($(input).val());
	}else{
		temp = checkedUserList.indexOf($(input).val());
		if (temp > -1) {
		  checkedUserList.splice(temp, 1);
		}
	}
	$("#userListSelectAll").val(1);
}
