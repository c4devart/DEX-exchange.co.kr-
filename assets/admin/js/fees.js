$(document).ready(function() {
	$(".feeInputBox").each(function(index, value){
		var thisId = this.id;
		if (thisId.indexOf("exchange") >= 0){
			$(this).number(true,2);
		}else{
			if (thisId.indexOf("KRW") >= 0){
				$(this).number(true);
			}else{
				$(this).number(true,8);
			}
		}
	});
	$(".feeInputBox").keypress(function(){
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13'){
			var id = this.id;
			var value = $(this).val();
			$.ajax({
				url: base_url + 'admin/changeFees',
				type: 'POST',
				data: {
					id : id,
					value : value
				},
				dataType : 'json',
				success: function (result) {
					if (result.res == true) {
						alertify.success(result.msg);
					} else {
						alertify.error(result.msg);
					}
				}
			});
		}
	})
})
