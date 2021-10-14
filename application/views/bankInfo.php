<?php
	header('Content-Type: text/html; charset=euc-kr');

	$id = "coinsky";
	$svc_code = "coninsky01";
	$req_code = md5(rand(1, 1000) . time() . "site_bank_veryfy");


	if (isset($ret_info) && !empty($ret_info)) {
		$ret_info = $ret_info;
	} else {
		$ret_info = "q";
	}
	// echo ("java -jar BankAction.jar " . $id . " " . $svc_code . " " . $req_code . " " . $ret_info);
	$bicipher = exec("java -jar BankAction.jar $id $svc_code $req_code $ret_info");
	print_r($bicipher);die();
	$Exp = explode(',', $bicipher);
	$Res = $Exp[0];
	$Ret = $Exp[2];

	$decData = "";
	$req_info = $Res;

	$deploy_domain = "http://excahnge.coinsky.co.kr";
	$call_back_link = $deploy_domain . "/api/bankVerify_result";

?>


<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script language="javascript">  
var ACCOUNT_CHECK; 

function openAccountWindow(){ 
    var ACCOUNT_CHECK = window.open('', 'ACCOUNT_CHECK', 'width=450, height=800, resizable=1, scrollbars=no, status=0, titlebar=0, toolbar=0, left=300, top=200' );
    if(ACCOUNT_CHECK == null){ 
        alert(" If you are using Windows XP SP2 or Internet Explorer 7, please allow pop-ups by clicking the pop-up blocker at the top of the screen. * Please allow popup when MSN, Yahoo, Google Popup Blocker toolbar is installed.");
    	return false;
    }
    window.name ='Bank Verification';
//   	document.reqAccountForm.action = 'https://goodpaper.co.kr/account/v2/account_check'; // 실서비스
   	document.reqAccountForm.action = 'https://dev.goodpaper.co.kr/account/v2/account_check'; // 테스트
   	
    document.reqAccountForm.target = "ACCOUNT_CHECK";
    document.reqAccountForm.submit();
}	
</script>
</head>
<body>  
    <form name="reqAccountForm" id="reqAccountForm" method="post" target="ACCOUNT_CHECK">
        <input type="hidden" id="req_info" name="req_info" value="<?= $req_info ?>"/>
        <input type="hidden" id="call_back" name="call_back" value="<?= $call_back_link ?>"/>
    </form>
    <form class="" name="Bank_Verify" method="post" accept-charset="utf-8">
        <input type="hidden" name="holder_name" value="">
        <input type="hidden" name="bank_code" value="">
        <input type="hidden" name="bank_name" value="">
        <input type="hidden" name="holder_number" value="">
        <input type="hidden" name="account_type" value="">
    </form>
    <button id="Bank_Verify_btn" type="submit" onclick="openAccountWindow();">start</button>
</body>
</html>  
<script language="javascript">  
	
</script>
