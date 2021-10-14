function form_validation(title, coin){
	var return_data = [];
	return_data.res = true;
    if (title == "" || coin == ""){
    	return_data.res = false;
        return_data.msg = langData[0];
    }
    return return_data;
}

$(document).ready(function(){
	$("#addNewCoin").click(function(){
		var title = $("#title").val();
		var coin = $("#coin").val();
		var fd = new FormData();
        var files = $('#fileToUpload')[0].files[0];
        if(typeof(files)!="undefined" && typeof(files)!=null){
        	var validate_result = form_validation(title, coin);
	    	if(validate_result['res'] == true){
				$.ajax({
					url: base_url + 'admin/addNewCoin/info/'+coin,
					type: 'POST',
					data:{
						title : title,
						coin : coin
					},
					dataType : 'json',
					success: function(return_data) {
						if(return_data.res == true){
							fd.append('file',files);
							$.ajax({
								url: base_url + 'admin/addNewCoin/image/'+coin,
								type: 'post',
								data: fd,
								contentType: false,
								processData: false,
								success: function(return_data){
									var result = JSON.parse(return_data);
									if(result.res == true){
										window.location.reload();
									} else {
										alertify.error(return_data.msg);
									}
								}
							});
						} else {
							alertify.error(return_data.msg);
						}     
					}
				});
		    } else {
		    	alertify.error(validate_result['msg']);
	        }
        } else {
        	alertify.error(langData[1]);
        }
	});
});

function changeCoinStatus(id, input){
	var value = $(input).children("option:selected").val();
	$.ajax({
        url: base_url + 'admin/ajaxChangeCoinStatus',
        type: 'POST',
        data: {
        	id : id,
            value : value
        },
        dataType : 'json',
        success: function (return_data) {
			if (return_data.res == true) {
				window.location.reload();
			} else {
				alertify.error(return_data.msg);
			}
        }
    });
}

function deleteCoin(id){
	if(confirm("Do you really want to delete this user?")){
		$.ajax({
	        url: base_url + 'admin/ajaxDeleteCoin',
	        type: 'POST',
	        data: {
	        	id : id
	        },
	        dataType : 'json',
	        success: function (return_data) {
				if (return_data.res == true) {
					window.location.reload();
				} else {
					alertify.error(return_data.msg);
				}
	        }
	    });
	}
}
