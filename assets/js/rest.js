var alphaValidation = /[A-Za-z]/;
var numericValidation = /[0-9]/;
var specValidation = /[~`!@#$%^&*()_+=':";/<>,.?]/;

var passwordValidated = false;

var password, confirmPassword;
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
$(document).ready(function() {
    $("#submit_reset").click(function(){
        var token = $("#token").val();
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        if(passwordValidated == true){
            $.ajax({
                url: base_url + 'api/reset',
                type: 'POST',
                data: {
                    token : token,
                    password : password
                },
                dataType : 'json',
                success: function(result_data) {
                    if(result_data['res'] == true){
                        $.sweetModal({
                            content: result_data.msg,
                            icon: $.sweetModal.ICON_SUCCESS
                        });  
                        $("#password").val('');
                        $("#confirm_password").val('');
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
                content: lang_msg_7[lang],
                icon: $.sweetModal.ICON_WARNING
            });
        }
    })
});


