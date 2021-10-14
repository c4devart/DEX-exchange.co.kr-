function form_validation(email){
    var return_data = [];
    var emailValidation = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    if (emailValidation.test(email)){
        return_data['res'] = true;
    }else{
        return_data['res'] = false;
        return_data['msg'] = lang_msg_14[lang];
    }
    return return_data;
}
$(document).ready(function() {
    $("#submit_send").click(function(){
        var email = $("#email").val();
        var validate_result = form_validation(email);
        if(validate_result['res'] == true){
            $.ajax({
                url: base_url + 'api/forget',
                type: 'POST',
                data: {
                    email : email
                },
                dataType : 'json',
                success: function(result_data) {
                    if(result_data['res'] == true){
                        $("#email").val('');
                        $.sweetModal({
                            content: result_data.msg,
                            icon: $.sweetModal.ICON_WARNING
                        }); 
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
                content: validate_result.msg,
                icon: $.sweetModal.ICON_WARNING
            });
        }
    })
});


