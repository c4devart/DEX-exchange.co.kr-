<?
    /* ============================================================================== */
    /* =   인증창 호출 및 수신 페이지                                               = */
    /* = -------------------------------------------------------------------------- = */
    /* =   해당 페이지는 반드시 가맹점 서버에 업로드 되어야 하며                    = */
    /* =   가급적 수정없이 사용하시기 바랍니다.                                     = */
    /* ============================================================================== */

    /* ============================================================================== */
    /* =   라이브러리 파일 Include                                                  = */
    /* = -------------------------------------------------------------------------- = */

    require "../lib/ct_cli_lib.php";

    /* = -------------------------------------------------------------------------- = */
    /* =   라이브러리 파일 Include END                                               = */
    /* ============================================================================== */
?>
<?
    /* ============================================================================== */
    /* =   null 값을 처리하는 메소드                                                = */
    /* = -------------------------------------------------------------------------- = */
    function f_get_parm_str( $val )
    {
        if ( $val == null ) $val = "";
        if ( $val == ""   ) $val = "";
        return  $val;
    }

    function f_get_parm_int( $val )
    {
        $ret_val = "";
        
        if ( $val == null ) $val = "00";
        if ( $val == ""   ) $val = "00";
      
        $ret_val = strlen($val) == 1? ("0" . $val) : $val;
      
        return  $ret_val;
    }
    /* ============================================================================== */
?>
<?
    $home_dir      = "/home/kcpuser/pgsample/USER/lds/linux-php/kcpcert_enc"; // ct_cll 절대경로 ( bin 전까지 )

    $req_tx        = "";

    $site_cd       = "";
    $ordr_idxx     = "";

    $year          = "";
    $month         = "";
    $day           = "";
    $user_name     = "";
    $sex_code      = "";
    $local_code    = "";
    
    $cert_able_yn  = "";
    $web_siteid    = "";
    $web_siteid_hashYN    = "";

    $up_hash       = "";
	/*------------------------------------------------------------------------*/
    /*  :: 전체 파라미터 남기기                                               */
    /*------------------------------------------------------------------------*/

    $ct_cert = new C_CT_CLI;
    $ct_cert->mf_clear();

    // request 로 넘어온 값 처리
   foreach($_POST as $nmParam => $valParam)
    {
         if ( $nmParam == "site_cd" )
        {
            $site_cd = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "req_tx" )
        {
            $req_tx = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "ordr_idxx" )
        {
            $ordr_idxx = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "user_name" )
        {
            $user_name = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "year" )
        {
            $year = f_get_parm_int ( $valParam );
        }

        if ( $nmParam == "month" )
        {
            $month = f_get_parm_int ( $valParam );
        }

        if ( $nmParam == "day" )
        {
            $day = f_get_parm_int ( $valParam );
        }

        if ( $nmParam == "sex_code" )
        {
            $sex_code = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "local_code" )
        {
            $local_code = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "cert_able_yn" )
        {
            $cert_able_yn = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "web_siteid_hashYN" )
        {
            $web_siteid_hashYN = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "web_siteid" )
        {
            $web_siteid = f_get_parm_str ( $valParam );
        }

        // 인증창으로 넘기는 form 데이터 생성 필드
        $sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . f_get_parm_str( $valParam ) . "'/>";
    }
    
    if ( $req_tx == "cert" )
    {

        if ( $web_siteid_hashYN !="Y")
        {
            // web_siteid 검증을 안할시 해당 값을 ""(null) 로 설정
            $web_siteid = "";
        }

        if ( $cert_able_yn == "Y" )
        {
            // input 박스 활성화시 up_hash 생성 데이터
            $hash_data = $site_cd                  .
                         $ordr_idxx                .
                         $web_siteid               .
                         ""                        .
                         "00"                      .
                         "00"                      .
                         "00"                      .
                         ""                        .
                         ""; 
        }
        else 
        {
            // !!up_hash 데이터 생성시 주의 사항
            // year , month , day 가 비어 있는 경우 "00" , "00" , "00" 으로 설정이 됩니다
            // 그외의 값은 없을 경우 ""(null) 로 세팅하시면 됩니다.
            // up_hash 데이터 생성시 site_cd 와 ordr_idxx 는 필수 값입니다.
            $hash_data = $site_cd                  .
                         $ordr_idxx                .
                         $web_siteid               .
                         $user_name                .
                         f_get_parm_int ( $year  ) .
                         f_get_parm_int ( $month ) .
                         f_get_parm_int ( $day   ) .
                         $sex_code                 .
                         $local_code; 
        }

        $up_hash = $ct_cert->make_hash_data( $home_dir, $hash_data );

        // 인증창으로 넘기는 form 데이터 생성 필드 ( up_hash )
        $sbParam .= "<input type='hidden' name='up_hash' value='" . $up_hash . "'/>";
    }

    $ct_cert->mf_clear();
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=EUC-KR">
        <title>*** KCP Online Payment System [PHP Version] ***</title>
        <script type="text/javascript">
            window.onload=function()
            {
                cert_page();
            }

			// 인증 요청 시 호출 함수
            function cert_page()
            {
                var frm = document.form_auth;

				if ( ( frm.req_tx.value == "auth" || frm.req_tx.value == "otp_auth" ) )
                {
                    frm.action="./smartcert_proc_res.php";
                    
                   // MOBILE
                    if( ( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 ) )
                    {
                        self.name="kcp_cert";
                    }
                    // PC
					else
					{
					    frm.target="kcp_cert";
					}
                    
                    frm.submit();
                    
                    window.close();
                }
				
				else if ( frm.req_tx.value == "cert" )
                {

                    if( ( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 ) ) // 스마트폰인 경우
                    {
                        parent.document.form_auth.veri_up_hash.value = frm.up_hash.value; // up_hash 데이터 검증을 위한 필드
						self.name="auth_popup";
                    }
					else // 스마트폰 아닐때
					{
	                    opener.document.form_auth.veri_up_hash.value = frm.up_hash.value; // up_hash 데이터 검증을 위한 필드
					}
                    frm.action="https://testcert.kcp.co.kr/kcp_cert/cert_view.jsp";
                    frm.submit();
                }
			}
        </script>
    </head>
    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
        <form name="form_auth" method="post">
            <?= $sbParam ?>
        </form>
    </body>
</html>
