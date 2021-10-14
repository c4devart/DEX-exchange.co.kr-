var userBalanceLIst;
var checkedUserList = [];
var enableListInputBoxClicked = 0;
$(document).ready(function(){
    userBalanceLIst = $('#userBalanceLIst').DataTable({
        "ajax": base_url+"admin/ajaxUserBalanceLIst",
		"language": {
		  "paginate": {
			"previous": "<",
			"next": ">"
		  },
		  "info": "총 _TOTAL_ 중 _START_ 부터 _END_ 까지",
		  "sLengthMenu": "_MENU_ "+langData[3],
		  "sSearch": langData[2]
		},
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

	$("#enableListInputBox").click(function(){
		if(enableListInputBoxClicked == 0){
			$(".uBalanceListInputBox").each(function(index, value){
				$(value).prop('disabled', false);
			})
			$("#enableListInputBox").html(langData[10]);
			enableListInputBoxClicked = 1;
		}else{
			$(".uBalanceListInputBox").each(function(index, value){
				$(value).prop('disabled', true);
			})
			$("#enableListInputBox").html(langData[11]);
			enableListInputBoxClicked = 0;
			userBalanceLIst.ajax.reload(null, true);
		}
	});
});

function changeBalance(id, input){
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13'){
		var value = $(input).val();
		$.ajax({
			url: base_url + 'admin/changeUserBalacne',
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
