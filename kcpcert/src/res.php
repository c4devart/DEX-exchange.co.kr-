<?php
	require "../lib/ct_cli_lib.php";
	
    function f_get_parm_str( $val ){
        if ( $val == null ) $val = "";
        if ( $val == ""   ) $val = "";
        return  $val;
	}
	
    $home_dir      = "/var/www/html/exchange.coinsky.co.kr/kcpcert"; // ct_cll 절대경로 ( bin 전까지 )
    $site_cd       = "";
    $ordr_idxx     = "";    
    $cert_no       = "";
    $cert_enc_use  = "";
    $enc_info      = "";
    $enc_data      = "";
    $req_tx        = "";    
    $enc_cert_data = "";
    $cert_info     = "";
    $tran_cd       = "";
    $res_cd        = "";
    $res_msg       = "";
	$dn_hash       = "";
	$param_opt_1   = "";
	
	$sbParam = '';
    foreach($_POST as $nmParam => $valParam){
        if ( $nmParam == "site_cd" ){
            $site_cd = f_get_parm_str ( $valParam );
		}
		if ( $nmParam == "ordr_idxx" ){
            $ordr_idxx = f_get_parm_str ( $valParam );
		}
		if ( $nmParam == "res_cd" ){
            $res_cd = f_get_parm_str ( $valParam );
		}
		if ( $nmParam == "cert_enc_use" ){
            $cert_enc_use = f_get_parm_str ( $valParam );
		}
		if ( $nmParam == "req_tx" ){
            $req_tx = f_get_parm_str ( $valParam );
		}
		if ( $nmParam == "cert_no" ){
            $cert_no = f_get_parm_str ( $valParam );
		}
		if ( $nmParam == "enc_cert_data" ){
            $enc_cert_data = f_get_parm_str ( $valParam );
		}
		if ( $nmParam == "dn_hash" ){
			$dn_hash = f_get_parm_str ( $valParam );
		}
		if ($nmParam == "param_opt_1") {
			$param_opt_1 = f_get_parm_str($valParam);
		}
        $sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . f_get_parm_str( $valParam ) . "'/>";
    }

    $ct_cert = new C_CT_CLI;
    $ct_cert->mf_clear();

	$baseUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http");
	$baseUrl .= "://" . $_SERVER['HTTP_HOST'] . '/';

	$msg = '인증이 실패하였습니다.';
    // 결과 처리
    if( $cert_enc_use == "Y" ){
        if( $res_cd == "0000" ){
            // dn_hash 검증
            // KCP 가 리턴해 드리는 dn_hash 와 사이트 코드, 요청번호 , 인증번호를 검증하여
			// 해당 데이터의 위변조를 방지합니다
			$veri_str = $site_cd.$ordr_idxx.$cert_no; // 사이트 코드 + 요청번호 + 인증거래번호
			if ( $ct_cert->check_valid_hash ( $home_dir , $dn_hash , $veri_str ) != "1" ){
                // 검증 실패시 처리 영역
                // echo "dn_hash 변조 위험있음";
                // 오류 처리 ( dn_hash 변조 위험있음)
            }
			// 가맹점 DB 처리 페이지 영역
			// echo "========================= 리턴 데이터 ======================="       ."<br>";
            // echo "사이트 코드            :" . $site_cd                                 ."<br>";
            // echo "인증 번호              :" . $cert_no                                 ."<br>";
            // echo "암호된 인증정보        :" . $enc_cert_data                           ."<br>";            
            // 인증데이터 복호화 함수
            // 해당 함수는 암호화된 enc_cert_data 를
            // site_cd 와 cert_no 를 가지고 복화화 하는 함수 입니다.
            // 정상적으로 복호화 된경우에만 인증데이터를 가져올수 있습니다.
            $opt = "1" ; // 복호화 인코딩 옵션 ( UTF - 8 사용시 "1" ) 
            $ct_cert->decrypt_enc_cert( $home_dir , $site_cd , $cert_no , $enc_cert_data , $opt );
            // echo "========================= 복호화 데이터 ====================="       ."<br>";
            // echo "복호화 이동통신사 코드 :" . $ct_cert->mf_get_key_value("comm_id"    )."<br>"; // 이동통신사 코드   
            // echo "복호화 전화번호        :" . $ct_cert->mf_get_key_value("phone_no"   )."<br>"; // 전화번호          
            // echo "복호화 이름            :" . $ct_cert->mf_get_key_value("user_name"  )."<br>"; // 이름              
            // echo "복호화 생년월일        :" . $ct_cert->mf_get_key_value("birth_day"  )."<br>"; // 생년월일          
            // echo "복호화 성별코드        :" . $ct_cert->mf_get_key_value("sex_code"   )."<br>"; // 성별코드          
            // echo "복호화 내/외국인 정보  :" . $ct_cert->mf_get_key_value("local_code" )."<br>"; // 내/외국인 정보    
            // echo "복호화 CI              :" . $ct_cert->mf_get_key_value("ci_url"         )."<br>"; // CI                
            // echo "복호화 DI              :" . $ct_cert->mf_get_key_value("di_url"         )."<br>"; // DI 중복가입 확인값
            // echo "복호화 WEB_SITEID      :" . $ct_cert->mf_get_key_value("web_siteid" )."<br>"; // WEB_SITEID
            // echo "복호화 결과코드        :" . $ct_cert->mf_get_key_value("res_cd"     )."<br>"; // 암호화된 결과코드
			// echo "복호화 결과메시지      :" . $ct_cert->mf_get_key_value("res_msg"    )."<br>"; // 암호화된 결과메시지			

			$phone_no = $ct_cert->mf_get_key_value("phone_no");
			$userToken = $param_opt_1;
			$username = $ct_cert->mf_get_key_value("user_name");

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $baseUrl.'api/confirmKCPResult');
			curl_setopt($ch, CURLOPT_POST, 1);
			$postData = array('token' => $userToken, 'phone' => $phone_no, 'username' => $username);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = json_decode(curl_exec($ch));
			curl_close($ch);
			$msg = $result->msg;
        }else/*if( res_cd.equals( "0000" ) != true )*/{
           // 인증실패
        }
    }else/*if( cert_enc_use.equals( "Y" ) != true )*/{
        // 암호화 인증 안함
    }
	$ct_cert->mf_clear();

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
        <title>*** KCP Online Payment System [PHP Version] ***</title>
        <link href="../../assets/css/jquery.sweet-modal.min.css" rel="stylesheet" type="text/css" />
		<script src="../../assets/theme/vendor/jquery/jquery.min.js"></script>
		<script src="../../assets/js/jquery.sweet-modal.min.js"></script>
    </head>
	<style>
		.button.redB.bordered.flat{
			display: none;
		}
	</style>
    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
        <form name="form_auth" method="post">
            <?php echo $sbParam;?>
        </form>
		<input type="hidden" id="returnMsg" value="<?php echo $msg;?>">
    </body>
	<script type="text/javascript">
		$.sweetModal.defaultSettings.confirm.yes.label = '확인';
		window.onload=function(){
			try{
				// opener.auth_data( document.form_auth ); // 부모창으로 값 전달
				opener.auth_data();
				$.sweetModal.confirm($("#returnMsg").val(), function() {
					window.close();// 팝업 닫기
				});
			}catch(e){
				console.log(e); // 정상적인 부모창의 iframe 를 못찾은 경우임
			}
		}
	</script>
</html>
