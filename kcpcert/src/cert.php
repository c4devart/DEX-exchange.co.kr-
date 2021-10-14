<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
        <title>*** KCP Online Certification System [PHP Version] ***</title>
        <link href="../css/sample.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">

            // 인증창 종료후 인증데이터 리턴 함수
			// function auth_data( frm )
			function auth_data()
            {
            //     var auth_form     = document.form_auth;
            //     var nField        = frm.elements.length;
            //     var response_data = "";

            //     // up_hash 검증 
            //     if( frm.up_hash.value != auth_form.veri_up_hash.value ){
            //         alert("up_hash 변조 위험있음");                    
            //     }
                
            //    /* 리턴 값 모두 찍어보기 (테스트 시에만 사용) */
            //     var form_value = "";
            //     for ( i = 0 ; i < frm.length ; i++ ){
            //         form_value += "["+frm.elements[i].name + "] = [" + frm.elements[i].value + "]\n";
            //     }
			// 	alert(form_value);
				opener.window.location.reload();
				window.close();
            }
            
            // 인증창 호출 함수
            function auth_type_check()
            {
                var auth_form = document.form_auth;    
                if( auth_form.ordr_idxx.value == "" ){
                    alert( "요청번호는 필수 입니다." );    
                    return false;
                }else{
                    if( ( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 ) == false ) // 스마트폰이 아닌경우
                    {
	                    var return_gubun;
	                    var width  = 410;
	                    var height = 500;
	
	                    var leftpos = screen.width  / 2 - ( width  / 2 );
	                    var toppos  = screen.height / 2 - ( height / 2 );
	
	                    var winopts  = "width=" + width   + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
	                    var position = ",left=" + leftpos + ", top="    + toppos;
	                    var AUTH_POP = window.open('','auth_popup', winopts + position);
                    }                    
                    auth_form.method = "post";
                    auth_form.target = "auth_popup"; // !!주의 고정값 ( 리턴받을때 사용되는 타겟명입니다.)
					auth_form.action = "./req.php"; // 인증창 호출 및 결과값 리턴 페이지 주소
                    return true;
                }
            }
    
            /* 예제 */
            window.onload=function()
            {
                var today            = new Date();
                var year             = today.getFullYear();
                var month            = today.getMonth() + 1;
                var date             = today.getDate();
                var time             = today.getTime();
                var year_select_box  = "<option value=''>선택 (년)</option>";
                var month_select_box = "<option value=''>선택 (월)</option>";
                var day_select_box   = "<option value=''>선택 (일)</option>";
                
                if(parseInt(month) < 10) {
                    month = "0" + month;
                }
    
                if(parseInt(date) < 10) {
                    date = "0" + date;
                }
    
                year_select_box = "<select name='year' class='frmselect' id='year_select'>";
                year_select_box += "<option value=''>선택 (년)</option>";
       
                for(i=year;i>(year-100);i--)
                {
                    year_select_box += "<option value='" + i + "'>" + i + " 년</option>";
                }
                
                year_select_box  += "</select>";
                month_select_box  = "<select name=\"month\" class=\"frmselect\" id=\"month_select\">";
                month_select_box += "<option value=''>선택 (월)</option>";
                
                for(i=1;i<13;i++)
                {
                    if(i < 10)
                    {
                        month_select_box += "<option value='0" + i + "'>" + i + " 월</option>";
                    }
                    else
                    {
                        month_select_box += "<option value='" + i + "'>" + i + " 월</option>";
                    }
                }
                
                month_select_box += "</select>";
                day_select_box    = "<select name=\"day\"   class=\"frmselect\" id=\"day_select\"  >";
                day_select_box   += "<option value=''>선택 (일)</option>";
                for(i=1;i<32;i++){
                    if(i < 10){
                        day_select_box += "<option value='0" + i + "'>" + i + " 일</option>";
                    }else{
                        day_select_box += "<option value='" + i + "'>" + i + " 일</option>";
                    }
                }                
                day_select_box += "</select>";                
                document.getElementById( "year_month_day"  ).innerHTML = year_select_box + month_select_box + day_select_box;                
                init_orderid(); // 요청번호 샘플 생성
            }

            // 요청번호 생성 예제 ( up_hash 생성시 필요 ) 
            function init_orderid(){
                var today = new Date();
                var year  = today.getFullYear();
                var month = today.getMonth()+ 1;
                var date  = today.getDate();
                var time  = today.getTime();

                if(parseInt(month) < 10){
                    month = "0" + month;
                }
                var vOrderID = year + "" + month + "" + date + "" + time;
                document.form_auth.ordr_idxx.value = vOrderID;
            }

        </script>
    </head>
    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
        <div align="center">
            <form name="form_auth" action="https://cert.kcp.co.kr/kcp_cert/cert_view.jsp" method="POST">
                <table width="589" cellpadding="0" cellspacing="0">
                    <tr style="height:14px"><td style="background-image:url('../img/boxtop589.gif');"></td></tr>
                    <tr>
                        <td style="background-image:url('../img/boxbg589.gif');">
                            <table width="551px" align="center" cellspacing="0" cellpadding="16">
                                <tr style="height:17px">
                                    <td style="background-image:url('../img/ttbg551.gif');background-repeat: no-repeat;border:0px " class="white">
                                        <span class="bold big">[인증요청]</span> 이 페이지는 휴대폰 인증요청 페이지입니다.
                                    </td>
                                </tr>
                            </table>
                            <table width="527" align="center" cellspacing="0" cellpadding="0" class="margin_top_20">
                                <tr style="height:32px;"><td colspan="2"  class="title">인 증 정 보</td></tr>
								<input type="hidden" name="ordr_idxx" class="frminput" value=""/>
                                <!-- <tr style="height:32px;">
                                    <td class="sub_title1">성명</td>
                                    <td class="sub_content1"><input type="text" name="user_name" value="<?php echo base64_decode($_GET['name']);?>" size="20" maxlength="20" class="frminput" style="margin-left:6px !important;"/></td>
                                </tr> -->
                                <tr style="height:32px;">
                                    <td class="sub_title1">생년월일</td>
                                    <td class="sub_content1" id="year_month_day">
                                    </td>
                                </tr>
                                <tr style="height:32px;">
                                    <td class="sub_title1">성별구분</td>
                                    <td class="sub_content1 bold">
                                        <input type="radio" name="sex_code" value="01" />남성
                                        <input type="radio" name="sex_code" value="02" />여성
                                        <select name='local_code' class="frmselect">
                                            <option value=''>선택</option>
                                            <option value='01'>내국인</option>
                                            <option value='02'>외국인</option>
                                        </select>
                                    </td>
                                </tr>
        
                                <tr class="height_1px"><td colspan="2" bgcolor="#0f75ac"></td></tr>
                            </table>
                            <table width="527" align="center" cellspacing="0" cellpadding="0" class="margin_top_20">
                                <tr id="show_pay_btn">
                                    <td colspan="2" align="center">
                                        <input type="image" src="../img/btn_certi.gif" onclick="return auth_type_check();" width="108" height="37" alt="인증을 요청합니다" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td><img src="../img/boxbtm589.gif" alt="Copyright(c) KCP Inc. All rights reserved."/></td></tr>
                </table>

                <input type="hidden" name="site_cd" value="A8DWR"/>
                <input type="hidden" name="req_tx" value="CERT"/>
                <input type="hidden" name="cert_method" value="01"/>
                <input type="hidden" name="cert_enc_use" value="Y"/>
                <input type="hidden" name="cert_otp_use" value="Y"/>
                <input type="hidden" name="cert_able_yn" value=""/>
                <input type="hidden" name="veri_up_hash" value=""/>
                <input type="hidden" name="web_siteid" value="J19012503138"/>
				<input type="hidden" name="action" value="https://cert.kcp.co.kr/kcp_cert/cert_view.jsp"/>
				<?php
					$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http");
					$base_url .= "://" . $_SERVER['HTTP_HOST'] . '/';
				?>
                <input type="hidden" name="Ret_URL" value="<?php echo $base_url;?>kcpcert/src/res.php" />
                <input type="hidden" name="web_siteid_hashYN" value="Y"/>
                <input type="hidden" name="param_opt_1" value="<?php echo base64_decode($_GET['userToken']);?>"/>
            </form>
        </div>
    </body>
</html>
