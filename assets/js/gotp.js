function changeGoogle2faStatus(status){
	var token = $("#token").val();
	var secret_code = $("#otpConfirmKey").val();
	var secret_key;
	if (status == 1) {
		secret_key = $("#otpKey").html();
	}else{
		secret_key = $("#otpKey").val();
	}
	if (secret_code.length > 1) {
		$.ajax({
			url: base_url + 'acnt/changeGoogle2faStatus',
			type: 'POST',
			data: {
				token: token,
				secret_key: secret_key,
				secret_code: secret_code,
				status: status
			},
			dataType: 'json',
			success: function (result_data) {
				if (result_data.res == true) {
					$.sweetModal({
						content: result_data.msg,
						icon: $.sweetModal.ICON_SUCCESS
					});
					setTimeout(function () {
						window.location.reload();
					}, 2000);
				} else {
					$.sweetModal({
						content: result_data.msg,
						icon: $.sweetModal.ICON_WARNING
					});
					setTimeout(function () {
						window.location.reload();
					}, 2000);
				}
			}
		});
	} else {
		$.sweetModal({
			content: lang_msg_3[lang],
			icon: $.sweetModal.ICON_WARNING
		});
	}
}

$(document).ready(function(){
	$("#copyOtpkey").click(function() {
        var otpKey = $("#otpKey").html();
        Clipboard.copy(otpKey);
        $("#copyOtpkey").html(lang_msg_4[lang]);
        var count = 0;
        setTimeout(function(){
            $("#copyOtpkey").html(lang_msg_5[lang]);
        }, 2000);
    });
    $("#turnOnOtp").click(function(){
    	changeGoogle2faStatus(1);
    })
    $("#turnOffOtp").click(function(){
    	changeGoogle2faStatus(0);
    })
})
