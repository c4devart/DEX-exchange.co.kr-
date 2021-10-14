$(document).ready(function(){
	$("#addNewAdmin").click(function () {
		var username = $("#username").val();
		var password = $("#password").val();
		var confirmPassword = $("#confirmPassword").val();
		var state = $("#state").val();
		var status = $("#status").val();
		if (username != '' && password != '' && confirmPassword != '' && state != '' && status != '') {
			if (password == confirmPassword) {
				$.ajax({
					url: base_url + 'admin/addNewAccount',
					type: 'POST',
					data: {
						username: username,
						password: password,
						state: state,
						status: status
					},
					dataType: 'json',
					success: function (return_data) {
						if (return_data.res == true) {
							alertify.success(return_data.msg);
							window.location.reload();
						} else {
							alertify.error(return_data.msg);
						}
					}
				});
			} else {
				alertify.error('비밀번호 미확인...');
			}
		} else {
			alertify.error('입력하세요.');
		}
	});
});

function resetPassword(id, input) {
	$.sweetModal.prompt('비밀번호 재설정', '1111111', '1111111', function (val) {
		$.ajax({
			url: base_url + 'admin/ajaxResetPassword',
			type: 'POST',
			data: {
				id: id,
				password : val
			},
			dataType: 'json',
			success: function (return_data) {
				if (return_data.res == true) {
					alertify.success(return_data.msg);
				} else {
					alertify.error(return_data.msg);
				}
			}
		});
	});
}

function changeStatus(id, field, input) {
	var value = $(input).children("option:selected").val();
	$.ajax({
        url: base_url + 'admin/ajaxChangeAdminStatus',
        type: 'POST',
        data: {
        	id : id,
			field: field,
			value: value
        },
        dataType : 'json',
        success: function (return_data) {
			if (return_data.res == true) {
				window.location.reload();
			} else {
				alertify.error(return_data.msg);
			}
		}
    });
}

function deleteAccount(id) {
	if(confirm(langData[5])){
		$.ajax({
	        url: base_url + 'admin/ajaxDeleteAccount',
	        type: 'POST',
	        data: {
	        	id : id
	        },
	        dataType : 'json',
	        success: function (return_data) {
				if (return_data.res == true) {
					window.location.reload();
				} else {
					alertify.error(return_data.msg);
				}
	        }
	    });
	}
}
