var alphaValidation = /[A-Za-z]/;
var numericValidation = /[0-9]/;
var specValidation = /[~`!@#$%^&*()_+=':";/<>,.?]/;

var agreeAll = false, agreeProvision = false, agreePrivacy = false;

var passwordValidated = false;

var phoneNumberValidated = false, phoneConfirmed = false;
var timerCount = 180;
var phoneConfirmId = false;
var phoneConfirmInterval;

var email, username, password, confirmPassword, phoneNumber, phoneConfirm;
var passwrodValidation = {
    alpha : false,
    numeric : false,
    spec : false,
    length : false,
    confirm : false
};

function passwordValidator(password, confirmPassword){
    passwordValidated = true;
    if (alphaValidation.test(password)){
        $("#conditionAlpha").removeClass('passive');
        $("#conditionAlpha").addClass('active');
        passwrodValidation.alpha = true;
    }else{
        $("#conditionAlpha").removeClass('active');
        $("#conditionAlpha").addClass('passive');
        passwordValidated = false;
    }
    if (numericValidation.test(password)){
        $("#conditionNumeric").removeClass('passive');
        $("#conditionNumeric").addClass('active');
        passwrodValidation.numeric = true;
    }else{
        $("#conditionNumeric").removeClass('active');
        $("#conditionNumeric").addClass('passive');
        passwordValidated = false;
    }
    if (specValidation.test(password)){
        $("#conditionSpec").removeClass('passive');
        $("#conditionSpec").addClass('active');
        passwrodValidation.spec = true;
    }else{
        $("#conditionSpec").removeClass('active');
        $("#conditionSpec").addClass('passive');
        passwordValidated = false;
    }
    if (password.length >= 8){
        $("#conditionLength").removeClass('passive');
        $("#conditionLength").addClass('active');
        passwrodValidation.length = true;
    }else{
        $("#conditionLength").removeClass('active');
        $("#conditionLength").addClass('passive');
        passwordValidated = false;
    }
    if (password == confirmPassword){
        $("#conditionConfirm").removeClass('passive');
        $("#conditionConfirm").addClass('active');
        passwrodValidation.confirm = true;
    }else{
        $("#conditionConfirm").removeClass('active');
        $("#conditionConfirm").addClass('passive');
        passwordValidated = false;
    }
}

function phoneNumberValidation(phoneNumber){
    var validateResult = true;
    if (numericValidation.test(phoneNumber)){
        validateResult = true;
    }else{
        validateResult = false;
    }
    if (alphaValidation.test(phoneNumber)){
        validateResult = false;
    }
    if (specValidation.test(phoneNumber)){
        validateResult = false;
    }
    if (phoneNumber.length == 0){
        validateResult = false;
    }
    return validateResult;
}

function numberValidation(number){
    var validateResult = true;
    if (numericValidation.test(number)){
        validateResult = true;
    }else{
        validateResult = false;
    }
    if (alphaValidation.test(number)){
        validateResult = false;
    }
    if (specValidation.test(number)){
        validateResult = false;
    }
    if (number.length == 0){
        validateResult = false;
    }
    return validateResult;
}

function getTimeFormat(timerCount){
    var sec = timerCount % 60;
    if(sec<10){
        sec = '0' + sec;
    }
    var min = (timerCount - sec) / 60;
    if (min < 10) {
        min = '0' + min;
    }
    return min + ':' + sec;
}

function removePhoneNumberConfirmId(confirmId){
    $.ajax({
        url: base_url + 'api/removePhoneNumberConfirmId',
        type: 'POST',
        data: {
            confirmId : confirmId
        },
        dataType : 'json',
        success: function(resultData) {
            if(resultData.res == true){
                phoneConfirmId = false;
            }else{
                removePhoneNumberConfirmId(confirmId);
            }
        },
        error: function(e){
            removePhoneNumberConfirmId(confirmId);
        }
    });
}

function form_validation(email, username){
    var return_data = [];
    var emailValidation = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    if (emailValidation.test(email)){
        if ( username.length < 2 ) {
            return_data['res'] = false;
            return_data['msg'] = lang_msg_26[lang];
        } else {
            return_data['res'] = true;
        }        
    }else{
        return_data['res'] = false;
        return_data['msg'] = lang_msg_27[lang];
    }
    return return_data;
}

$(document).ready(function() {

    $("#agreeAll").click(function(){
        if(agreeAll == false){
            $("#agreeAll").removeClass('img-check-off');
            $("#agreeAll").addClass('img-check-on');
            agreeAll = true;
            $("#agreeProvision").removeClass('img-check-off');
            $("#agreeProvision").addClass('img-check-on');
            agreeProvision = true;
            $("#agreePrivacy").removeClass('img-check-off');
            $("#agreePrivacy").addClass('img-check-on');
            agreePrivacy = true;
        }else{
            $("#agreeAll").removeClass('img-check-on');
            $("#agreeAll").addClass('img-check-off');
            agreeAll = false;
            $("#agreeProvision").removeClass('img-check-on');
            $("#agreeProvision").addClass('img-check-off');
            agreeProvision = false;
            $("#agreePrivacy").removeClass('img-check-on');
            $("#agreePrivacy").addClass('img-check-off');
            agreePrivacy = false;
        }
    });
    $("#agreeProvision").click(function(){
        if(agreeProvision == false){
            $("#agreeProvision").removeClass('img-check-off');
            $("#agreeProvision").addClass('img-check-on');
            agreeProvision = true;
            if(agreeProvision == true && agreePrivacy == true){
                $("#agreeAll").removeClass('img-check-off');
                $("#agreeAll").addClass('img-check-on');
                agreeAll = true;
            }
        }else{
            $("#agreeProvision").removeClass('img-check-on');
            $("#agreeProvision").addClass('img-check-off');
            agreeProvision = false;
            
            $("#agreeAll").removeClass('img-check-on');
            $("#agreeAll").addClass('img-check-off');
            agreeAll = false;
        }
    });
    $("#agreePrivacy").click(function(){
        if(agreePrivacy == false){
            $("#agreePrivacy").removeClass('img-check-off');
            $("#agreePrivacy").addClass('img-check-on');
            agreePrivacy = true;
            if(agreeProvision == true && agreePrivacy == true){
                $("#agreeAll").removeClass('img-check-off');
                $("#agreeAll").addClass('img-check-on');
                agreeAll = true;
            }
        }else{
            $("#agreePrivacy").removeClass('img-check-on');
            $("#agreePrivacy").addClass('img-check-off');
            agreePrivacy = false;
            
            $("#agreeAll").removeClass('img-check-on');
            $("#agreeAll").addClass('img-check-off');
            agreeAll = false;
        }
    });

    $("#submitPhoneNumber").click(function(){
		if (phoneConfirmed == false) {
			phoneNumber = $("#phoneNumber").val();
			phoneNumberValidated = phoneNumberValidation(phoneNumber);
			if (phoneNumberValidated == true) {
				$.ajax({
					url: base_url + 'api/phoneNumberValidation',
					type: 'POST',
					data: {
						phoneNumber: phoneNumber
					},
					dataType: 'json',
					success: function (resultData) {
						if (resultData.res == true) {
							phoneConfirmId = resultData.confirmId;
							$.sweetModal({
								content: lang_msg_8[lang],
								icon: $.sweetModal.ICON_SUCCESS
							});
							clearInterval(phoneConfirmInterval);
							timerCount = 180;
							phoneConfirmInterval = setInterval(function () {
								timerCount--;
								if (timerCount < 0) {
									clearInterval(phoneConfirmInterval);
									removePhoneNumberConfirmId(phoneConfirmId);
								} else {
									var formattedTime = getTimeFormat(timerCount);
									$("#phoneNumberConfirmCount").html(formattedTime);
								}
							}, 1000);
						} else {
							$.sweetModal({
								content: resultData.msg,
								icon: $.sweetModal.ICON_WARNING
							});
						}
					}
				});
			} else {
				$.sweetModal({
					content: lang_msg_9[lang],
					icon: $.sweetModal.ICON_WARNING
				});
			}
		}
    });

    $("#phoneNumberConfirmSubmit").click(function(){
		if (phoneConfirmed == false) {
			phoneConfirm = $("#phoneConfirm").val();
			var temp = numberValidation(phoneConfirm);
			if (temp == true) {
				if (phoneConfirmId == false) {
					$.sweetModal({
						content: lang_msg_10[lang],
						icon: $.sweetModal.ICON_WARNING
					});
				} else {
					$.ajax({
						url: base_url + 'api/confirmPhoneNumber',
						type: 'POST',
						data: {
							confirmId: phoneConfirmId,
							code: phoneConfirm
						},
						dataType: 'json',
						success: function (resultData) {
							if (resultData.res == true) {
								$("#submitPhoneNumber").css('background-color', '#f4f7f9');
								$("#submitPhoneNumber").css('border', 'solid 1px #c7c7c7');
								$("#submitPhoneNumber").css('color', '#7d7d7d');
								$("#phoneNumberConfirmSubmit").css('background-color', '#f4f7f9');
								$("#phoneNumberConfirmSubmit").css('border', 'solid 1px #c7c7c7');
								$("#phoneNumberConfirmSubmit").css('color', '#7d7d7d');
								clearInterval(phoneConfirmInterval);
								timerCount = 180;
								removePhoneNumberConfirmId(phoneConfirmId);
								phoneConfirmed = true;
								$.sweetModal({
									content: lang_msg_11[lang],
									icon: $.sweetModal.ICON_SUCCESS
								});
							} else {
								$.sweetModal({
									content: lang_msg_12[lang],
									icon: $.sweetModal.ICON_WARNING
								});
							}
						}
					});
				}
			} else {
				$.sweetModal({
					content: lang_msg_13[lang],
					icon: $.sweetModal.ICON_WARNING
				});
			}
		}
    });

    $("#password").keyup(function(){
        password = $("#password").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(password, confirmPassword);
    });
    $("#password").keydown(function(){
        password = $("#password").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(password, confirmPassword);
    })
    $("#confirmPassword").keyup(function(){
        password = $("#password").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(password, confirmPassword);
    })
    $("#confirmPassword").keydown(function(){
        password = $("#password").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(password, confirmPassword);
    })

    $("#submit_signup").click(function(){
        if(agreeAll == false){
            $.sweetModal({
                content: lang_msg_28[lang],
                icon: $.sweetModal.ICON_WARNING
            });  
        }else{
            email = $("#email").val();
            username = $("#username").val();
            validate_result = form_validation(email, username);
            if(validate_result['res'] == true){
                if(passwordValidated == false){
                    $.sweetModal({
                        content: lang_msg_7[lang],
                        icon: $.sweetModal.ICON_WARNING
                    });  
                }else if(phoneConfirmed == false){
                    $.sweetModal({
                        content: lang_msg_6[lang],
                        icon: $.sweetModal.ICON_WARNING
                    });  
                }else{
                    $.ajax({
                        url: base_url + 'api/signUp',
                        type: 'POST',
                        data: {
                            email : email,
                            username : username,
                            password : password,
                            phone : phoneNumber
                        },
                        dataType : 'json',
                        success: function(resultData) {
                            if(resultData.res == true){
                                $("#email").val('');
                                $("#username").val('');
                                $("#password").val('');
                                $("#confirmPassword").val('');
                                $("#phoneNumber").val('');
                                $("#phoneConfirm").val('');
                                $("#phoneNumberConfirmCount").html('');
                                $.sweetModal({
                                    content: resultData.msg,
                                    icon: $.sweetModal.ICON_SUCCESS
                                });
                            }else{
                                $.sweetModal({
                                    content: resultData.msg,
                                    icon: $.sweetModal.ICON_WARNING
                                });
                            }
                        }
                    });
                }
            }else{
                $.sweetModal({
                    content: validate_result.msg,
                    icon: $.sweetModal.ICON_WARNING
                });  
            }
        } 
    })
});


