var alphaValidation = /[A-Za-z]/;
var numericValidation = /[0-9]/;
var specValidation = /[~`!@#$%^&*()_+=':";/<>,.?]/;
var passwordValidated = false;

var currentPassword, newPassword, confirmPassword, phoneNumber, phoneConfirm, phoneNumber_change, phoneNumber_close;
var passwrodValidation = {
    same : false,
    alpha : false,
    numeric : false,
    spec : false,
    length : false,
    confirm : false
};

var phoneNumberValidated = false, phoneConfirmed = false, phoneConfirmed_change = false, phoneConfirmed_close = false;
var timerCount = 180;
var phoneConfirmId = false;
var phoneConfirmInterval;

var closeAgreedStatus = false;

function passwordValidator(currentPassword, newPassword, confirmPassword){
    passwordValidated = true;
    if (currentPassword != newPassword){
        $("#conditionSame").removeClass('passive');
        $("#conditionSame").addClass('active');
        passwrodValidation.same = true;
    }else{
        $("#conditionSame").removeClass('active');
        $("#conditionSame").addClass('passive');
        passwordValidated = false;
    }
    if (alphaValidation.test(newPassword)){
        $("#conditionAlpha").removeClass('passive');
        $("#conditionAlpha").addClass('active');
        passwrodValidation.alpha = true;
    }else{
        $("#conditionAlpha").removeClass('active');
        $("#conditionAlpha").addClass('passive');
        passwordValidated = false;
    }
    if (numericValidation.test(newPassword)){
        $("#conditionNumeric").removeClass('passive');
        $("#conditionNumeric").addClass('active');
        passwrodValidation.numeric = true;
    }else{
        $("#conditionNumeric").removeClass('active');
        $("#conditionNumeric").addClass('passive');
        passwordValidated = false;
    }
    if (specValidation.test(newPassword)){
        $("#conditionSpec").removeClass('passive');
        $("#conditionSpec").addClass('active');
        passwrodValidation.spec = true;
    }else{
        $("#conditionSpec").removeClass('active');
        $("#conditionSpec").addClass('passive');
        passwordValidated = false;
    }
    if (newPassword.length >= 8){
        $("#conditionLength").removeClass('passive');
        $("#conditionLength").addClass('active');
        passwrodValidation.length = true;
    }else{
        $("#conditionLength").removeClass('active');
        $("#conditionLength").addClass('passive');
        passwordValidated = false;
    }
    if (newPassword == confirmPassword){
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

$(function() {
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
    });
});
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-36251023-1']);
_gaq.push(['_setDomainName', 'jqueryscript.net']);
_gaq.push(['_trackPageview']);
(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

$(document).ready(function() {

    $("#newPassword").keyup(function(){
        currentPassword = $("#currentPassword").val();
        newPassword = $("#newPassword").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(currentPassword, newPassword, confirmPassword);
    });
    $("#newPassword").keydown(function(){
        currentPassword = $("#currentPassword").val();
        newPassword = $("#newPassword").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(currentPassword, newPassword, confirmPassword);
    })
    $("#confirmPassword").keyup(function(){
        currentPassword = $("#currentPassword").val();
        newPassword = $("#newPassword").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(currentPassword, newPassword, confirmPassword);
    })
    $("#confirmPassword").keydown(function(){
        currentPassword = $("#currentPassword").val();
        newPassword = $("#newPassword").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(currentPassword, newPassword, confirmPassword);
    })
    $("#currentPassword").keyup(function(){
        currentPassword = $("#currentPassword").val();
        newPassword = $("#newPassword").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(currentPassword, newPassword, confirmPassword);
    })
    $("#currentPassword").keydown(function(){
        currentPassword = $("#currentPassword").val();
        newPassword = $("#newPassword").val();
        confirmPassword = $("#confirmPassword").val();
        passwordValidator(currentPassword, newPassword, confirmPassword);
    })

    $("#submitPhoneNumber").click(function(){
        if(phoneConfirmed == false){
            phoneNumber = $("#phoneNumber").val();
            phoneNumberValidated = phoneNumberValidation(phoneNumber);
            if(phoneNumberValidated == true){
                $.ajax({
                    url: base_url + 'api/phoneNumberValidation',
                    type: 'POST',
                    data: {
                        phoneNumber : phoneNumber
                    },
                    dataType : 'json',
                    success: function(resultData) {
                        if(resultData.res == true){
                            phoneConfirmId = resultData.confirmId;
                            $.sweetModal({
                                content: lang_msg_8[lang],
                                icon: $.sweetModal.ICON_SUCCESS
                            });
                            clearInterval(phoneConfirmInterval);
                            timerCount = 180;
                            phoneConfirmInterval = setInterval(function(){
                                timerCount--;
                                if(timerCount < 0){
                                    clearInterval(phoneConfirmInterval);
                                    timerCount = 180;
                                    removePhoneNumberConfirmId(phoneConfirmId);
                                }else{
                                    var formattedTime = getTimeFormat(timerCount);
                                    $("#phoneNumberConfirmCount").html(formattedTime);
                                }
                            }, 1000);
    					}else{
    						$.sweetModal({
    							content: resultData.msg,
    							icon: $.sweetModal.ICON_SUCCESS
    						});
    					}
                    }
                });
            }else{
                $.sweetModal({
                    content: lang_msg_18[lang],
                    icon: $.sweetModal.ICON_WARNING
                });            
            }
        }
    });

    $("#phoneNumberConfirmSubmit").click(function(){
        if(phoneConfirmed == false){
            phoneConfirm = $("#phoneConfirm").val();
            var temp = numberValidation(phoneConfirm);
            if(temp == true){
                if(phoneConfirmId == false){
                    $.sweetModal({
                        content: lang_msg_10[lang],
                        icon: $.sweetModal.ICON_WARNING
                    });  
                }else{
                    $.ajax({
                        url: base_url + 'api/confirmPhoneNumber',
                        type: 'POST',
                        data: {
                            confirmId : phoneConfirmId,
                            code : phoneConfirm
                        },
                        dataType : 'json',
                        success: function(resultData) {
                            if(resultData.res == true){
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
                            }else{
                                $.sweetModal({
                                    content: lang_msg_12[lang],
                                    icon: $.sweetModal.ICON_WARNING
                                });  
                            }
                        }
                    });
                }
            }else{
                $.sweetModal({
                    content: lang_msg_13[lang],
                    icon: $.sweetModal.ICON_WARNING
                });  
            }
        }
    })


    $("#submit_change").click(function(){
        var token = $("#token").val();
        var currentPassword = $("#currentPassword").val();
        var newPassword = $("#newPassword").val();
        var confirmPassword = $("#confirmPassword").val();
        passwordValidator(currentPassword, newPassword, confirmPassword);
        if(passwordValidated == false){
            $.sweetModal({
                content: lang_msg_7[lang],
                icon: $.sweetModal.ICON_WARNING
            });  
        }else if(phoneConfirmed_change == false){
            $.sweetModal({
                content: lang_msg_6[lang],
                icon: $.sweetModal.ICON_WARNING
            });  
        }else{
            $.ajax({
                url: base_url + 'api/changePassword',
                type: 'POST',
                data: {
                    token : token,
                    password : currentPassword,
                    new_password : newPassword
                },
                dataType : 'json',
                success: function(return_data) {
                    if(return_data.res == true){
                        $.sweetModal({
                            content: return_data.msg,
                            icon: $.sweetModal.ICON_SUCCESS
                        });    
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }else{
                        $.sweetModal({
                            content: return_data.msg,
                            icon: $.sweetModal.ICON_WARNING
                        });
                    }  
                }
            });
        }
    })

    $("#google2fa_status").change(function(){
        var token = $("#token").val();
        var secret_code = $("#secret_code").val();
        var secret_key = $("#secret_key").val();
        var status = $(this).prop('checked');
        if(status == true){
            status = 1;
        }else{
            status = 0;
        }
        $.ajax({
            url: base_url + 'acnt/changeGoogle2faStatus',
            type: 'POST',
            data: {
                token : token,
                secret_key : secret_key,
                secret_code : secret_code,
                status : status
            },
            dataType : 'json',
            success: function(result_data) {
                if(result_data.res == true){
                    $.sweetModal({
                        content: result_data.msg,
                        icon: $.sweetModal.ICON_SUCCESS
                    });    
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                }else{
                    $.sweetModal({
                        content: result_data.msg,
                        icon: $.sweetModal.ICON_WARNING
                    });
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                }  
            }
        });
    })

    $("#submit_close").click(function(){
        var token = $("#token").val();
        var close_password = $("#close_password").val();
        var balanceStatus = $("#balanceStatus").val();
        var onOrderStatus = $("#onOrderStatus").val();
        var onWithdrawStatus = $("#onWithdrawStatus").val();
        var blockStatus = $("#blockStatus").val();
        if(balanceStatus == true && onOrderStatus == true && onWithdrawStatus == true && balanceStatus == true){
            if(closeAgreedStatus == true){
                if(phoneConfirmed_close == true){
                    $.ajax({
                        url: base_url + 'acnt/closeUser',
                        type: 'POST',
                        data: {
                            token : token,
                            close_password : close_password
                        },
                        dataType : 'json',
                        success: function(result_data) {
                            if(result_data.res == true){
                                $.sweetModal({
                                    content: result_data.msg,
                                    icon: $.sweetModal.ICON_SUCCESS
                                });    
                                setTimeout(function(){
                                    window.location.href = base_url+'acnt/siin';
                                }, 2000);
                            }else{
                                $.sweetModal({
                                    content: result_data.msg,
                                    icon: $.sweetModal.ICON_WARNING
                                });
                            }  
                        }
                    });
                }else{
                    $.sweetModal({
                        content: lang_msg_6[lang],
                        icon: $.sweetModal.ICON_WARNING
                    });
                }
            }else{
                $.sweetModal({
                    content: lang_msg_19[lang],
                    icon: $.sweetModal.ICON_WARNING
                });
            }
        }else{
            $.sweetModal({
                content: lang_msg_20[lang],
                icon: $.sweetModal.ICON_WARNING
            });
        }
    })

    
    $("#submitPhoneNumber_change").click(function(){
        if(phoneConfirmed_change == false){
            phoneNumber_change = $("#phoneNumber_change").val();
            phoneNumberValidated = phoneNumberValidation(phoneNumber_change);
            if(phoneNumberValidated == true){
                $.ajax({
                    url: base_url + 'api/phoneNumberValidation',
                    type: 'POST',
                    data: {
                        phoneNumber : phoneNumber_change
                    },
                    dataType : 'json',
                    success: function(resultData) {
                        if(resultData.res == true){
                            phoneConfirmId = resultData.confirmId;
                            $.sweetModal({
                                content: lang_msg_8[lang],
                                icon: $.sweetModal.ICON_SUCCESS
                            });
                            clearInterval(phoneConfirmInterval);
                            timerCount = 180;
                            phoneConfirmInterval = setInterval(function(){
                                timerCount--;
                                if(timerCount < 0){
                                    clearInterval(phoneConfirmInterval);
                                    timerCount = 180;
                                    removePhoneNumberConfirmId(phoneConfirmId);
                                }else{
                                    var formattedTime = getTimeFormat(timerCount);
                                    $("#phoneNumberConfirmCount_change").html(formattedTime);
                                }
                            }, 1000);
                        }else{
                            $.sweetModal({
                                content: resultData.msg,
                                icon: $.sweetModal.ICON_WARNING
                            });
                        }
                    }
                });
            }else{
                $.sweetModal({
                    content: lang_msg_21[lang],
                    icon: $.sweetModal.ICON_WARNING
                });            
            }
        }
    });

    $("#phoneNumberConfirmSubmit_change").click(function(){
        if(phoneConfirmed_change == false){
            var phoneConfirm_change = $("#phoneConfirm_change").val();
            var temp = numberValidation(phoneConfirm_change);
            if(temp == true){
                if(phoneConfirmId == false){
                    $.sweetModal({
                        content: lang_msg_10[lang],
                        icon: $.sweetModal.ICON_WARNING
                    });  
                }else{
                    $.ajax({
                        url: base_url + 'api/confirmPhoneNumber',
                        type: 'POST',
                        data: {
                            confirmId : phoneConfirmId,
                            code: phoneConfirm_change
                        },
                        dataType : 'json',
                        success: function(resultData) {
                            if(resultData.res == true){
                                $("#submitPhoneNumber_change").css('background-color', '#f4f7f9');
                                $("#submitPhoneNumber_change").css('border', 'solid 1px #c7c7c7');
                                $("#submitPhoneNumber_change").css('color', '#7d7d7d');
                                $("#phoneNumberConfirmSubmit_change").css('background-color', '#f4f7f9');
                                $("#phoneNumberConfirmSubmit_change").css('border', 'solid 1px #c7c7c7');
                                $("#phoneNumberConfirmSubmit_change").css('color', '#7d7d7d');
                                clearInterval(phoneConfirmInterval);
                                timerCount = 180;
                                removePhoneNumberConfirmId(phoneConfirmId);
                                phoneConfirmed_change = true;
                                $.sweetModal({
                                    content: lang_msg_11[lang],
                                    icon: $.sweetModal.ICON_SUCCESS
                                });
                            }else{
                                $.sweetModal({
                                    content: lang_msg_12[lang],
                                    icon: $.sweetModal.ICON_WARNING
                                });  
                            }
                        }
                    });
                }
            }else{
                $.sweetModal({
                    content: lang_msg_13[lang],
                    icon: $.sweetModal.ICON_WARNING
                });  
            }
        }
    })

    $("#changePhoneNumberSubmit").click(function(){
        phoneNumber_change = $("#phoneNumber_change").val();
        if(phoneConfirmed_change == false){
            $.sweetModal({
                content: lang_msg_6[lang],
                icon: $.sweetModal.ICON_WARNING
            });  
        }else{
            $.ajax({
                url: base_url + 'acnt/changePhoneNumber',
                type: 'POST',
                data: {
                    phone : phoneNumber_change
                },
                dataType : 'json',
                success: function(resultData) {
                    if(resultData == 1){
                        $.sweetModal({
                            content: lang_msg_22[lang],
                            icon: $.sweetModal.ICON_SUCCESS
                        });
                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    }else{
                        $.sweetModal({
                            content: lang_msg_23[lang],
                            icon: $.sweetModal.ICON_WARNING
                        });
                    }  
                }
            });
        }
    })

    $("#submitPhoneNumber_close").click(function(){
        if(phoneConfirmed_close == false){
            phoneNumber_close = $("#phoneNumber_close").val();
            phoneNumberValidated = phoneNumberValidation(phoneNumber_close);
            if(phoneNumberValidated == true){
                $.ajax({
                    url: base_url + 'api/phoneNumberValidation',
                    type: 'POST',
                    data: {
                        phoneNumber : phoneNumber_close
                    },
                    dataType : 'json',
                    success: function(resultData) {
                        if(resultData.res == true){
                            phoneConfirmId = resultData.confirmId;
                            $.sweetModal({
                                content: lang_msg_8[lang],
                                icon: $.sweetModal.ICON_SUCCESS
                            });
                            clearInterval(phoneConfirmInterval);
                            timerCount = 180;
                            phoneConfirmInterval = setInterval(function(){
                                timerCount--;
                                if(timerCount < 0){
                                    clearInterval(phoneConfirmInterval);
                                    timerCount = 180;
                                    removePhoneNumberConfirmId(phoneConfirmId);
                                }else{
                                    var formattedTime = getTimeFormat(timerCount);
                                    $("#phoneNumberConfirmCount_close").html(formattedTime);
                                }
                            }, 1000);
                        }else{
                            $.sweetModal({
                                content: resultData.msg,
                                icon: $.sweetModal.ICON_WARNING
                            });
                        }
                    }
                });
            }else{
                $.sweetModal({
                    content: lang_msg_21[lang],
                    icon: $.sweetModal.ICON_WARNING
                });            
            }
        }
    });

    $("#phoneNumberConfirmSubmit_close").click(function(){
        if(phoneConfirmed_close == false){
            var phoneConfirm_close = $("#phoneConfirm_close").val();
            var temp = numberValidation(phoneConfirm_close);
            if(temp == true){
                if(phoneConfirmId == false){
                    $.sweetModal({
                        content: lang_msg_10[lang],
                        icon: $.sweetModal.ICON_WARNING
                    });  
                }else{
                    $.ajax({
                        url: base_url + 'api/confirmPhoneNumber',
                        type: 'POST',
                        data: {
                            confirmId : phoneConfirmId,
                            code: phoneConfirm_close
                        },
                        dataType : 'json',
                        success: function(resultData) {
                            if(resultData.res == true){
                                $("#submitPhoneNumber_close").css('background-color', '#f4f7f9');
                                $("#submitPhoneNumber_close").css('border', 'solid 1px #c7c7c7');
                                $("#submitPhoneNumber_close").css('color', '#7d7d7d');
                                $("#phoneNumberConfirmSubmit_close").css('background-color', '#f4f7f9');
                                $("#phoneNumberConfirmSubmit_close").css('border', 'solid 1px #c7c7c7');
                                $("#phoneNumberConfirmSubmit_close").css('color', '#7d7d7d');
                                clearInterval(phoneConfirmInterval);
                                timerCount = 180;
                                removePhoneNumberConfirmId(phoneConfirmId);
                                phoneConfirmed_close = true;
                                $.sweetModal({
                                    content: lang_msg_11[lang],
                                    icon: $.sweetModal.ICON_SUCCESS
                                });
                            }else{
                                $.sweetModal({
                                    content: lang_msg_12[lang],
                                    icon: $.sweetModal.ICON_WARNING
                                });  
                            }
                        }
                    });
                }
            }else{
                $.sweetModal({
                    content: lang_msg_13[lang],
                    icon: $.sweetModal.ICON_WARNING
                });  
            }
        }
    })

    $("#agreePrivacy").click(function(){
        if(closeAgreedStatus == true){
            closeAgreedStatus = false;
            $("#agreePrivacy").removeClass("img-check-on");
            $("#agreePrivacy").addClass("img-check-off");
        }else{
            closeAgreedStatus = true;
            $("#agreePrivacy").removeClass("img-check-off");
            $("#agreePrivacy").addClass("img-check-on");
        }
    })
});


