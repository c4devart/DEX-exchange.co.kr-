function form_validation(username, password, new_password, confirm_password){
    var return_data = [];
    if(username != "" && password != "" && new_password != "" && confirm_password != ""){
        if ( username.length < 3 ) {
            return_data['res'] = false;
            return_data['msg'] = langData[6];
        } else {
            if ( new_password.length < 6 ) {
                return_data['res'] = false;
                return_data['msg'] = langData[7];
            } else {
                if ( new_password != confirm_password ) {
                    return_data['res'] = false;
                    return_data['msg'] = langData[8];
                } else {
                    return_data['res'] = true;
                }
            }
        }    
    }else{
        return_data['res'] = false;
        return_data['msg'] = langData[9];
    }    
    return return_data;
}
$(document).ready(function() {
    $("#submit_change").click(function(){
        $("#submit_error_alert").html('');
        $("#submit_error_alert_content").css('display','none');
        var username = $("#username").val();
        var password = $("#password").val();
        var new_password = $("#new_password").val();
        var confirm_password = $("#confirm_password").val();
        var validate_result = form_validation(username, password, new_password, confirm_password);
        if(validate_result['res'] == true){
            $.ajax({
                url: base_url + 'admin/ajax_change_admin_settings',
                type: 'POST',
                data: {
                    username : username,
                    password : password,
                    new_password : new_password
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
        }else{
            $("#submit_error_alert").html(validate_result['msg']);
            $("#submit_error_alert_content").css('display','block');
        }
    });
});
