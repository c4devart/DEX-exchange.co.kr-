var userKycList;
var checkedUserList = [];
$(document).ready(function(){
    userKycList = $('#userKycList').DataTable({
        "ajax": base_url + "admin/ajaxWacntList",
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
		}, {
			"targets": 9,
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

function registerWithdrawAccount(token) {
	var bankInfo = ['HSBC농협은행', 'IBK기업은행', 'KB국민은행', 'KDB산업은행', 'KEB하나은행', 'NH농협은행', 'SC제일은행', '경남은행', '광주은행', '대구은행', '부산은행', '상호저축은행', '새마을금고중앙회', '수협중앙회', '신한은행', '신협중앙회', '우리은행', '우체국', '전북은행', '제주은행', '지역농축협', '카카오뱅크', '케이뱅크', '한국씨티은행'];
	var modalContentHtml = '<input type="hidden" id="userToken" value="' + token + '">';
	modalContentHtml += '<select id="withdraw_account_type" class="form-control"><option value="개인">개인</option><option value="법인">법인</option></select><br>';
	modalContentHtml += '계좌 실명<br><input type="text" id="withdraw_account_name" class="form-control"><br>';
	modalContentHtml += '계좌<br><select id="withdraw_bank" class="form-control">';
	$.each(bankInfo, function (index, value) {
		modalContentHtml += '<option value="' + value + '">' + value + '</option>';
	});
	modalContentHtml += '</select><br>';
	modalContentHtml += '계좌번호<br><input type="text" id="withdraw_bank_no" class="form-control"><br><input type="button" class="btn btn-primary form-control" value="새로 등록" id="registerWithdrawKRWAccount"><br>';
	$.sweetModal({
		title: '회원 출금계좌 등록',
		content: modalContentHtml
	});

	$("#registerWithdrawKRWAccount").click(function () {
		var userToken = $("#userToken").val();
		var withdraw_account_type = $("#withdraw_account_type").val();
		var withdraw_account_name = $("#withdraw_account_name").val();
		var withdraw_bank = $("#withdraw_bank").val();
		var withdraw_bank_no = $("#withdraw_bank_no").val();
		$.ajax({
			url: base_url + 'admin/ajaxRegisterUserWithdrawAccount',
			type: 'POST',
			data: {
				userToken: userToken,
				withdraw_account_type: withdraw_account_type,
				withdraw_account_name: withdraw_account_name,
				withdraw_bank: withdraw_bank,
				withdraw_bank_no: withdraw_bank_no
			},
			dataType: 'json',
			success: function (result) {
				if (result.res == true) {
					alertify.success(result.msg);
					userKycList.ajax.reload(null, false);
				} else {
					alertify.error(result.msg);
				}
			}
		});
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
