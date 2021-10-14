<?php
	include ('../lib/ct_cli_lib.php');

	function f_get_parm_str($val){
		if ($val == null) $val = "";
		if ($val == "") $val = "";
		return $val;
	}
	
	function f_get_parm_int($val){
		$ret_val = "";
		if ($val == null) $val = "00";
		if ($val == "") $val = "00";
		$ret_val = strlen($val) == 1 ? ("0" . $val) : $val;
		return $ret_val;
	}

	$home_dir = "/var/www/html/exchange.coinsky.co.kr/kcpcert"; // ct_cll 절대경로 (bin 전까지)
	$req_tx = "";
	$site_cd = "";
	$ordr_idxx = "";
	$year = "";
	$month = "";
	$day = "";
	$user_name = "";
	$sex_code = "";
	$local_code = "";
	$cert_able_yn = "";
	$web_siteid = "";
	$web_siteid_hashYN = "";
	$up_hash = "";
	$param_opt_1 = "";

	$ct_cert = new C_CT_CLI;
	$ct_cert->mf_clear();
	$sbParam = '';
	foreach ($_POST as $nmParam => $valParam) {
		if ($nmParam == "site_cd") {
			$site_cd = f_get_parm_str($valParam);
		}
		if ($nmParam == "req_tx") {
			$req_tx = f_get_parm_str($valParam);
		}
		if ($nmParam == "ordr_idxx") {
			$ordr_idxx = f_get_parm_str($valParam);
		}
		if ($nmParam == "user_name") {
			$user_name = f_get_parm_str($valParam);
		}
		if ($nmParam == "year") {
			$year = f_get_parm_int($valParam);
		}
		if ($nmParam == "month") {
			$month = f_get_parm_int($valParam);
		}
		if ($nmParam == "day") {
			$day = f_get_parm_int($valParam);
		}
		if ($nmParam == "sex_code") {
			$sex_code = f_get_parm_str($valParam);
		}
		if ($nmParam == "local_code") {
			$local_code = f_get_parm_str($valParam);
		}
		if ($nmParam == "cert_able_yn") {
			$cert_able_yn = f_get_parm_str($valParam);
		}
		if ($nmParam == "web_siteid_hashYN") {
			$web_siteid_hashYN = f_get_parm_str($valParam);
		}
		if ($nmParam == "web_siteid") {
			$web_siteid = f_get_parm_str($valParam);
		}
		if ($nmParam == "param_opt_1") {
			$param_opt_1 = f_get_parm_str($valParam);
		}
		$sbParam .= '<input type="hidden" name="'.$nmParam.'" value="'.f_get_parm_str($valParam).'"/>';
	}

	if ($req_tx == "CERT") {
		if ($web_siteid_hashYN != "Y") {
			$web_siteid = "";
		}
		if ($cert_able_yn == "Y") {
			$hash_data = $site_cd .
				$ordr_idxx .
				$web_siteid .
				"" .
				"00" .
				"00" .
				"00" .
				"" .
				"";
		} else {
			$hash_data = $site_cd .
				$ordr_idxx .
				$web_siteid .
				$user_name .
				f_get_parm_int($year) .
				f_get_parm_int($month) .
				f_get_parm_int($day) .
				$sex_code .
				$local_code;
		}
		$up_hash = $ct_cert->make_hash_data($home_dir, $hash_data);
		$sbParam .= '<input type="hidden" name="up_hash" value="'.$up_hash.'"/>';
	}
	$ct_cert->mf_clear();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
        <title>*** KCP Online Payment System [PHP Version] ***</title>
    </head>
    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
        <form name="form_auth" method="post">
            <?php echo $sbParam;?>
        </form>
    </body>
	<script type="text/javascript">
		window.onload=function(){
			var frm = document.form_auth;
			if (frm.req_tx.value == "CERT"){
				opener.document.form_auth.veri_up_hash.value = frm.up_hash.value; // up_hash 데이터 검증을 위한 필드
				frm.action="https://cert.kcp.co.kr/kcp_cert/cert_view.jsp";
				frm.submit();
			}else if ((frm.req_tx.value == "auth" || frm.req_tx.value == "otp_auth")){
				frm.action="./res.php";
				frm.submit();
				window.close();
			}else{
				alert ("req_tx 값을 확인해 주세요");
			}
		}
	</script>
</html>
