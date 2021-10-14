function form_validation(username, password){
    var return_data = [];
    if ( username == "" || password =="" ) {
        return_data['res'] = false;
        return_data['msg'] = langData[9];
    } else {
        return_data['res'] = true;
    }
    return return_data;
}
$(document).ready(function() {
    $("#submit_signin").click(function(){
        $("#submit_error_alert").html('');
        $("#submit_error_alert_content").css('display','none');
        var username = $("#username").val();
        var password = $("#password").val();
        var validate_result = form_validation(username, password);
        if(validate_result['res'] == true){
            $.ajax({
                url: base_url + 'admin/ajax_signIn',
                type: 'POST',
                data: {
                    username : username,
                    password : password
                },
                dataType : 'json',
                success: function(result_data) {
                    if(result_data['res'] == true){
                        window.location.href = base_url + 'admin/swallet';
                    }else{
                        $("#submit_error_alert").html(result_data['msg']);
                        $("#submit_error_alert_content").css('display','block');            
                    }
                }
            });
        }else{
            $("#submit_error_alert").html(validate_result['msg']);
            $("#submit_error_alert_content").css('display','block');
        }
    })
});


