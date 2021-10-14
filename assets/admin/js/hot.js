$(document).ready(function(){			

	var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
	
	$("#submitWithdrawal").click(function () {
		var coin = $("#coin").val();
		var toAddress = $("#toAddress").val();
		var amount = $("#amount").val();
		if(coin != '' && toAddress != '' && amount != ''){
			if(numberRegex.test(amount)) {
				$.ajax({
					url: base_url + 'admin/ajaxHotWithdraw',
					type: 'POST',
					data: {
						coin: coin,
						toAddress: toAddress,
						amount : amount
					},
					dataType: 'json',
					success: function (return_data) {
						if (return_data.res == true) {
							alertify.success(return_data.msg);
						} else {
							alertify.error(return_data.msg);
						}
					}
				});
			}else{
				alertify.error('처리 결과 : 실패, 숫자입력...');
			}
		}else{
			alertify.error('처리 결과 : 실패, 입력하세요.');
		}
	});
});
function setUnit(unit){
	$("#coin").val(unit);
}