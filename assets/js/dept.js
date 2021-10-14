var alphaValidation = /[A-Za-z]/;
var numericValidation = /[0-9]/;
var specValidation = /[~`!@#$%^&*()_+=':";/<>,.?]/;

function makeNumber(str) {
	var res = str.split(",");
	var ret_value = res.join('');
	return ret_value;
}

var phoneNumberValidated = false, phoneConfirmed = false, phoneConfirmed_Deposit = false;
var timerCount = 180;
var phoneConfirmId = false;
var phoneConfirmInterval;

var phoneNumber, phoneConfirm;

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



function form_validation(to_address, amount, password){
    var return_data = [];
    if (to_address != "" && amount !="" && password!=""){
        if (parseFloat(amount) != amount || parseFloat(amount)<=0) {
            return_data['res'] = false;
            return_data['msg'] = lang_msg_2[lang];
        } else {
            return_data['res'] = true;
        }
    }else{
        return_data['res'] = false;
        return_data['msg'] = lang_msg_3[lang];
    }
    return return_data;
}

function KRW_form_validation(username, amount, bankName, bankId, password){
    var return_data = [];
    if (username != "" && amount !="" && bankName!="" && bankId!="" && password!=""){
        if (parseFloat(amount) != amount || parseFloat(amount)<=0) {
            return_data['res'] = false;
            return_data['msg'] = lang_msg_2[lang];
        } else {
            return_data['res'] = true;
        }
    }else{
        return_data['res'] = false;
        return_data['msg'] = lang_msg_3[lang];
    }
    return return_data;
}


$(document).ready(function(){
    var token = $("#token").val();
    var coin = $("#coin").val();
	var is_clicked = 0;

	var balanceData = [];
	var balanceDataFlag = false;

	$.ajax({
		url: base_url + 'api/getEachCoinBalance',
		type: 'POST',
		data: {
			token : token
		},
		dataType : 'json',
		success: function(result_data) {
			balanceData = result_data;
			balanceDataFlag = true;
		}
	});
    
    $("#copydepositAddresskey").click(function() {
        var depositAddressKey = $("#depositAddressKey").html();
        Clipboard.copy(depositAddressKey);
        $("#copydepositAddresskey").html(lang_msg_4[lang]);
        var count = 0;
        setTimeout(function(){
            $("#copydepositAddresskey").html(lang_msg_5[lang]);
        }, 2000);
    });
	
	$("#checkboxViewBalancedOnly").change(function(){
		var value = $("#checkboxViewBalancedOnly").prop('checked');
		if(value == true){
			if(balanceDataFlag == true){
				$.each(balanceData, function(index, value){
					if(value > 0){
						$("#sidebarContent"+index).css('display','block');
					}else{
						$("#sidebarContent"+index).css('display','none');
					}
				});
			}else{
				$.ajax({
					url: base_url + 'api/getEachCoinBalance',
					type: 'POST',
					data: {
						token : token
					},
					dataType : 'json',
					success: function(result_data) {
						balanceData = result_data;
						balanceDataFlag = true;
						$.each(balanceData, function(index, value){
							if(value > 0){
								$("#sidebarContent"+index).css('display','block');
							}else{
								$("#sidebarContent"+index).css('display','none');
							}
						});
					}
				});
			}
		}else{
			$(".coinsky-sidebar-content").each(function(index, value){
				$(value).css('display', 'block');
			});
		}
	});

    $("#generate_coin_address").click(function(){
		if (coin == "BTC" || coin == "ETH" || coin == "SKY" || coin == "BDR") {
            if(is_clicked == 0){
                is_clicked = 1;
                $.ajax({
                    url: base_url + 'api/getDepositAddress',
                    type: 'POST',
                    data:{
                        token : token,
                        coin : coin
                    },
                    dataType : 'json',
                    success: function(return_data) {
                        is_clicked = 0;
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
        }             
    });

    $("#confirm_deposit").click(function(){
        if(coin!="KRW"){
            $.ajax({
                url: base_url + 'api/confirmDeposit',
                type: 'POST',
                data:{
                    token : token,
                    coin : coin
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
	});

	$("#request_amount_Withdraw").keyup(function () {
		var request_amount_Withdraw = $("#request_amount_Withdraw").val();
		var amount_Withdraw = request_amount_Withdraw - 1000;
		$("#amount_Withdraw").val(amount_Withdraw);
	});
	$("#request_amount_Withdraw").change(function () {
		var request_amount_Withdraw = $("#request_amount_Withdraw").val();
		var amount_Withdraw = request_amount_Withdraw - 1000;
		$("#amount_Withdraw").val(amount_Withdraw);
	});

    $("#submit_withdraw").click(function(){
        if(coin!="KRW"){
            var to_address = $("#to_address").val();
            var amount = $("#amount").val();
            var password = $("#password").val();
            var validate_result = form_validation(to_address, amount, password);
            if(validate_result['res'] == true){
                if(phoneConfirmed == false){
                    $.sweetModal({
                        content: lang_msg_6[lang],
                        icon: $.sweetModal.ICON_WARNING
                    });  
                }else{
                    $.ajax({
                        url: base_url + 'api/withdrawCoin',
                        type: 'POST',
                        data: {
                            token : token,
                            coin : coin,
                            to_address : to_address,
                            amount : amount,
                            password : password
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
            }else{
                $.sweetModal({
                    content: validate_result.msg,
                    icon: $.sweetModal.ICON_WARNING
                });
            }
        } else if (coin == 'KRW') {
        	var bankName = $("#bankName_Withdraw").val();
        	var bankId = $("#bankId_Withdraw").val();
        	var accountName = $("#accountName_Withdraw").val();
			var amount = $("#amount_Withdraw").val();
        	var password = $("#password_Withdraw").val();
        	var validate_result = KRW_form_validation(bankName, amount, bankId, accountName, password);
        	if (validate_result['res'] == true) {
        		if (phoneConfirmed == false) {
        			$.sweetModal({
        				content: lang_msg_6[lang],
        				icon: $.sweetModal.ICON_WARNING
        			});
        		} else {
        			$.ajax({
        				url: base_url + 'api/requestUserDepWithKRW',
        				type: 'POST',
        				data: {
							type: 'withdraw',
							token: token,
							username: accountName,
							password: password,
							amount: amount,
							bankName: bankName,
							bankId: bankId
        				},
        				dataType: 'json',
        				success: function (return_data) {
        					if (return_data.res == true) {
        						$.sweetModal({
        							content: return_data.msg,
        							icon: $.sweetModal.ICON_SUCCESS
        						});
        						setTimeout(function () {
        							window.location.reload();
        						}, 2000);
        					} else {
        						$.sweetModal({
        							content: return_data.msg,
        							icon: $.sweetModal.ICON_WARNING
        						});
        					}
        				}
        			});
        		}
        	} else {
        		$.sweetModal({
        			content: validate_result.msg,
        			icon: $.sweetModal.ICON_WARNING
        		});
        	}
		}
	});
	
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
                                icon: $.sweetModal.ICON_WARNING
                            });
                        }
                    }
                });
            }else{
                $.sweetModal({
                    content: lang_msg_9[lang],
                    icon: $.sweetModal.ICON_WARNING
                });            
            }
        }
    });

    $("#submitPhoneNumber_Deposit").click(function(){
        if(phoneConfirmed_Deposit == false){
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
                                    $("#phoneNumberConfirmCount_Deposit").html(formattedTime);
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
                    content: lang_msg_9[lang],
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
	
	$("#phoneNumberConfirmSubmit_Deposit").click(function () {
		if (phoneConfirmed == false) {
			phoneConfirm = $("#phoneConfirm_Deposit").val();
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
								$("#submitPhoneNumber_Deposit").css('background-color', '#f4f7f9');
								$("#submitPhoneNumber_Deposit").css('border', 'solid 1px #c7c7c7');
								$("#submitPhoneNumber_Deposit").css('color', '#7d7d7d');
								$("#phoneNumberConfirmSubmit_Deposit").css('background-color', '#f4f7f9');
								$("#phoneNumberConfirmSubmit_Deposit").css('border', 'solid 1px #c7c7c7');
								$("#phoneNumberConfirmSubmit_Deposit").css('color', '#7d7d7d');
								clearInterval(phoneConfirmInterval);
								timerCount = 180;
								removePhoneNumberConfirmId(phoneConfirmId);
								phoneConfirmed_Deposit = true;
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
	})

    
    $("#submit_Deposit").click(function(){
		var username = $("#username_Deposit").val();
		var amount = $("#amount_Deposit").val();
		var bankName = $("#bankName_Deposit").val();
		var bankId = $("#bankId_Deposit").val();
		var password = $("#password_Deposit").val();
		var validate_result = KRW_form_validation(username, amount, bankName, bankId, password);
		if(validate_result['res'] == true){
			if(phoneConfirmed_Deposit == false){
				$.sweetModal({
					content: lang_msg_6[lang],
					icon: $.sweetModal.ICON_WARNING
				});  
			}else{
				$.ajax({
					url: base_url + 'api/requestUserDepWithKRW',
					type: 'POST',
					data: {
						type: 'deposit',
						token : token,
						username: username,
						password: password,
						amount: amount,
						bankName: bankName,
						bankId: bankId
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
		}else{
			$.sweetModal({
				content: validate_result.msg,
				icon: $.sweetModal.ICON_WARNING
			});
		}
	});
  
});
