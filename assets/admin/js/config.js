$(document).ready(function(){
    $("#submit_change").click(function(){
        var data = {};
        $.each($('.form-control'), function(i, item) {
            data[item.id] = item.value;
        });
        $.ajax({
            url: base_url + 'admin/ajax_change_config',
            type: 'POST',
            data: data,
            dataType : 'json',
            success: function (return_data) {
				if (return_data.res == true) {
					window.location.reload();
				} else {
					alertify.error(return_data.msg);
				}
            }
        });
    })
})