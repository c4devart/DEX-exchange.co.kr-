function form_validation(email, password){
    var return_data = [];
    var emailValidation = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    if (emailValidation.test(email)){
        if ( password.length < 3 ) {
            return_data['res'] = false;
            return_data['msg'] = lang_msg_25[lang];
        } else {
            return_data['res'] = true;
        }
    }else{
        return_data['res'] = false;
        return_data['msg'] = lang_msg_14[lang];
    }
    return return_data;
}
$(document).ready(function() {
	function sendSignInRequest() {
		var email = $("#email").val();
		var password = $("#password").val();
		var google2fa_key = $("#google2fa_key").val();
		var validate_result = form_validation(email, password);
		if (validate_result['res'] == true) {
			$.ajax({
				url: base_url + 'api/signIn',
				type: 'POST',
				data: {
					email: email,
					password: password,
					google2fa_key: google2fa_key
				},
				dataType: 'json',
				success: function (result_data) {
					if (result_data['res'] == true) {
						window.location.href = base_url + 'excn/adnc';
					} else {
						$.sweetModal({
							content: result_data.msg,
							icon: $.sweetModal.ICON_WARNING
						});
					}
				}
			});
		} else {
			$.sweetModal({
				content: validate_result.msg,
				icon: $.sweetModal.ICON_WARNING
			});
		}
	}
    $("#submit_signin").click(function(){
		sendSignInRequest();
	});
	$(document).on('keypress', function (e) {
		var keycode = (e.keyCode ? e.keyCode : e.which);
		if (keycode == '13') {
			sendSignInRequest();
		}
	});
});


