$(document).ready(function(){
	$("#addNewMarket").click(function(){
		var newTarget = $("#addNewMarketTarget").val();
		var newBase = $("#addNewMarketBase").val();
		if(newTarget == newBase){
        	$("#submit_error_alert").html('Choose different coins.');
        	$("#submit_error_alert_content").css('display','block');
		}else{
			$.ajax({
				url: base_url + 'admin/addNewMarket',
				type: 'POST',
				data:{
					target : newTarget,
					base : newBase
				},
				dataType : 'json',
				success: function(return_data) {
					if(return_data.res == true){
						window.location.reload();
					} else {
						alertify.error(return_data.msg);
					}
				}
			});
		}
	});
});

function changeMarketStatus(id, input){
	var value = $(input).children("option:selected").val();
	$.ajax({
        url: base_url + 'admin/ajaxChangeMarketStatus',
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

function deleteMarket(id){
	if(confirm(langData[5])){
		$.ajax({
	        url: base_url + 'admin/ajaxDeleteMarket',
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
