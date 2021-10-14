$.sweetModal.defaultSettings.confirm.yes.label = lang_yes[lang];
$.sweetModal.defaultSettings.confirm.ok.label = lang_yes[lang];
$.sweetModal.defaultSettings.confirm.cancel.label = lang_cancel[lang];

function levlChangeAddress(address){
	$.sweetModal.prompt(lang_msg_15[lang], lang_msg_16[lang], address, function(newAddress) {
		if(newAddress.length > 0){
			$.ajax({
				url: base_url+'acnt/levlChangeAddress',
				type: 'POST',
				data: {
					address : newAddress
				},
				dataType : 'json',
				success: function(resultData) {
					$("#levlAddress").html(newAddress);
					$.sweetModal({
						content: lang_msg_17[lang],
						icon: $.sweetModal.ICON_SUCCESS
					});
				}
			});
		}else{
			$.sweetModal({
				content: lang_msg_16[lang],
				icon: $.sweetModal.ICON_WARNING
			});
		}
	});
}

function openKCPcertWindow(userToken, username, phone_no) {
	window.open(base_url + 'kcpcert/src/cert.php?userToken=' + userToken + '&name=' + username + '&phone_no=' + phone_no, '_blank', 'width=610,height=330,left=400,top=200');
	return true;
}

function openAccountWindow() {
	var ACCOUNT_CHECK = window.open('', 'ACCOUNT_CHECK', 'width=450, height=800, resizable=1, scrollbars=no, status=0, titlebar=0, toolbar=0, left=600, top=100');
	if (ACCOUNT_CHECK == null) {
		alert(" ※ 윈도우 XP SP2 또는 인터넷 익스플로러 7 사용자일 경우에는 \n    화면 상단에 있는 팝업 차단 알림줄을 클릭하여 팝업을 허용해 주시기 바랍니다. \n\n※ MSN,야후,구글 팝업 차단 툴바가 설치된 경우 팝업허용을 해주시기 바랍니다.");
	}
	document.reqAccountForm.action = 'https://dev.goodpaper.co.kr/account/v2/account_check';
	document.reqAccountForm.target = 'ACCOUNT_CHECK';
	document.reqAccountForm.submit();
	return true;
}
