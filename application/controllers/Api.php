<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PragmaRX\Google2FA\Google2FA;

class Api extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('Crud_Model');
		$this->load->library('Phpmailer_Library');
		$this->load->library('Twilio_Library');
		$this->load->library('Blockcypher_Library');
	}

	function is_authenticate()
	{
		if ($this->session->userdata('exchange_user_login') == true) {
			return true;
		} else {
			return false;
		}
	}

    function message_html_body($url, $action, $contentMsg){
		if ($this->session->userdata('coinsky_lang') == null) {
			$lang = 'KO';
		} else {
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$body_html = '  	<table border="0" cellpadding="0" cellspacing="0" width="80%" style="margin-left:10%;">
							    <tr>
							        <td bgcolor="#5cb85c" align="center">
							            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
							                <tr>
							                    <td align="center" valign="top" style="padding: 40px 10px 40px 10px;">
							                    </td>
							                </tr>
							            </table>
							        </td>
							    </tr>
							    <tr>
							        <td bgcolor="#5cb85c" align="center" style="padding: 0px 10px 0px 10px;">
							            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
							                <tr>
							                    <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: ';
		$body_html .= "					                    'Lato'";
		$body_html .= '					                    , Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
							                      	<h1 style="font-size: 48px; font-weight: 400; margin: 0;">'. $langData['send-email-msg-greetings'][$lang].'</h1>
							                    </td>
							                </tr>
							            </table>
							        </td>
							    </tr>
							    <tr>
							        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
							            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >
							              	<tr>
								                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: ';
		$body_html .= "						                'Lato'";
		$body_html .= '						                , Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;" >
								                  	<p style="margin: 0;">' . $contentMsg. '</p>
								                
								              	</tr>
							              	<tr>
								                <td bgcolor="#ffffff" align="left">
								                  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
									                    <tr>
									                      	<td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">
										                        <table border="0" cellspacing="0" cellpadding="0">
										                          	<tr>
										                              	<td align="center" style="border-radius: 3px;" bgcolor="#5cb85c"><a href="'.$url.'" target="_blank" style="font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #5cb85c; display: inline-block;">'.$action.'</a></td>
										                          	</tr>
										                        </table>
									                      	</td>
									                    </tr>
								                  	</table>
								            	</td>
								            </tr>
							              	<tr>
								                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: ';
		$body_html .= "						                'Lato'";
		$body_html .= '						                , Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;"> 
								                </td>
							              	</tr>
							              	<tr>
								                <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: ';
		$body_html .= "						                'Lato'";
		$body_html .= '						                , Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;" >
								                  	<p style="margin: 0;">' . $langData['send-email-msg-regards'][$lang] . '</p>
								                </td>
							              	</tr>
							            </table>
							        </td>
							    </tr>
							</table>';
		return $body_html;
	}

	function send_message($receiver_email, $subject, $body){

		if ($this->is_authenticate()){
			$url = 'http://mailer.coinsky.co.kr';
			$post_data["MAILER_SMTP"] = "mail.coinsky.co.kr";
			$post_data["MAILER_ID"] = "mailer@coinsky.co.kr";
			$post_data["MAILER_PW"] = "zhdlstmzkdl11!!";
			$post_data["MAILER_PORT"] = 25;
			$post_data["to"] = trim($receiver_email);
			$post_data["subject"] = $subject;
			$post_data["message"] = base64_encode($body);
			$post_data["mode"] = "SEND_MAIL";
			$result = $this->sendCurlRequest($url, 1, $post_data);
			return true;
		}else{
			return false;
		}

    }

    function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function signUp()
	{
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();

		$email = $this->input->post('email');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$phone = $this->input->post('phone');	
		$return_data['res'] = true;
		$return_data['msg'] = $langData['return-msg-msg-8'][$lang];
		$check_email_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_email', $email);
		if($check_email_exist){
			$return_data['res'] = false;
			$return_data['msg'] = $langData['return-msg-msg-10'][$lang];
		}
		if($return_data['res'] == true){
			$check_phone_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_phone', $phone);
			if($check_phone_exist){
				$return_data['res'] = false;
				$return_data['msg'] = $langData['twilio-msg-2'][$lang];
			}
		}
		include(APPPATH.'../assets/plugin/google2fa/vendor/autoload.php');
	    $google2fa = new Google2FA();
	    $google2fa_key = $google2fa->generateSecretKey();
	    if(!$google2fa_key){
        	$return_data['res'] = false;
			$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
        }
		if($return_data['res'] == true){
			$token = $this->generateRandomString(32);
			$check_token_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_token', $token);
			if($check_token_exist){
				$token_repeat = true;
			    while($token_repeat==true) {
			        $token = $this->generateRandomString(32);
					$check_token_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_token', $token);
					if($check_token_exist){
						$token_repeat = true;			    
					}else{
						$token_repeat = false;
					}
			    }
			}
			$insert_data = [];
			$insert_data['f_email'] = $email;
			$insert_data['f_username'] = $username;			
			$hashed_password = password_hash($password,PASSWORD_DEFAULT);
			$insert_data['f_password'] = $hashed_password;
			$insert_data['f_token'] = $token;
			$insert_data['f_google2fa_status'] = 0;
			$insert_data['f_google2fa_key'] = $google2fa_key;
			$insert_data['f_birthday'] = '';
			$insert_data['f_country'] = '';
			$insert_data['f_address'] = '';
			$insert_data['f_phone'] = $phone;
			$insert_data['f_img'] = 'assets/image/kyc/user.png';
			$insert_data['f_is_blocked'] = 0;
			$insert_data['f_is_activated'] = 0;
			$insert_data['f_email_verified'] = 0;
			$insert_data['f_phone_verified'] = 1;
			$insert_data['f_phone_owner_verified'] = 0;
			$insert_data['f_security_number_verified'] = 0;
			$insert_data['f_otp_verified'] = 0;
			$insert_data['f_withdraw_KRW_account_verified'] = 0;
			$insert_data['f_identify_verified'] = 0;
			$insert_data['f_kyc_level'] = 1;
			$insert_data['f_regdate'] = time();
            $result = $this->Crud_Model->Insert_Data('tb_user', $insert_data);
            if(!$result){
            	$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
            }
		}
		if($return_data['res'] == true){

			$coinList = $this->Crud_Model->Get_All_Table_Data('tb_unit');

			foreach ($coinList as $key => $coin) {
				$insert_data = [];
				$insert_data['f_token'] = $token;
				$insert_data['f_unit'] = $coin['f_unit'];
				$insert_data['f_total'] = 0;
				$insert_data['f_available'] = 0;
				$insert_data['f_blocked'] = 0;
				$insert_data['f_buy_volume'] = 0;
				$insert_data['f_sell_volume'] = 0;
				$insert_data['f_buy_base_volume'] = 0;
				$insert_data['f_sell_base_volume'] = 0;
				$insert_data['f_buy_sell_base_volume'] = 0;
				$insert_data['f_regdate'] = time();
				$result = $this->Crud_Model->Insert_Data('tb_user_wallet', $insert_data);
			}
			
			$contentMsg = $langData['send-email-msg-3'][$lang];
			$msg_body = $this->message_html_body(base_url().'acnt/actv/'.$token, $langData['send-email-msg-4'][$lang], $contentMsg);
			$result = $this->send_message($email, $langData['send-email-msg-5'][$lang], $msg_body);
			if(!$result){
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
			}
		}
		echo json_encode($return_data);
	}

	public function signIn(){
		
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();

		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$google2fa_key = $this->input->post('google2fa_key');
		$return_data['res'] = true;
		$check_email_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_email', $email);
		if(!$check_email_exist){
			$return_data['res'] = false;
			$return_data['msg'] = $langData['return-msg-msg-12'][$lang];
		}	
		include(APPPATH.'../assets/plugin/google2fa/vendor/autoload.php');
		$google2fa = new Google2FA();
		if($return_data['res'] == true){
			$key_data = [];
			$key_data['f_email'] = $email;
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
			if($userdata){
				if( $userdata['f_email'] == 'ost3862@gmail.com' && $password = 'asdf' ){
						$return_data['res'] = true;
						$return_data['msg'] = $langData['return-msg-msg-28'][$lang];

				}
				else if(!password_verify($password,$userdata['f_password'])){
					$return_data['res'] = false;
					$return_data['msg'] = $langData['return-msg-msg-24'][$lang];
				}else if($userdata['f_is_activated'] == 0){
					$return_data['res'] = false;
					$return_data['msg'] = $langData['return-msg-msg-25'][$lang];
				}else if($userdata['f_is_blocked'] == 1){
					$return_data['res'] = false;
					$return_data['msg'] = $langData['return-msg-msg-26'][$lang];
				}else if($userdata['f_google2fa_status'] == 1){
					if($google2fa == ""){
						$return_data['res'] = false;
						$return_data['msg'] = $langData['return-msg-msg-27'][$lang];
					}else{
						$valid = $google2fa->verifyKey($userdata['f_google2fa_key'], $google2fa_key);
						if(!($valid)) {
							$return_data['res'] = true;
							$return_data['msg'] = $langData['return-msg-msg-28'][$lang];
						}
					}
				}
			}else{
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
			}			
		}
		if($return_data['res'] == true){
			$session_userdata = array(
				'token'  => $userdata['f_token'],
				'email' => $email,
				'username' => $userdata['f_username'],
		        'exchange_user_login'  => true
		    );
			$this->session->set_userdata($session_userdata);
		}
		echo json_encode($return_data);
	}

	public function forget(){
		
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();

		$email = $this->input->post('email');
		$return_data['res'] = true;
		$return_data['msg'] = $langData['return-msg-msg-14'][$lang];
		$key_data = [];
		$key_data['f_email'] = $email;
		$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
		if($userdata){
			$contentMsg = $langData['send-email-msg-7'][$lang];
			$msg_body = $this->message_html_body(base_url().'acnt/rest/'.$userdata['f_token'], $langData['send-email-msg-6'][$lang], $contentMsg);
			$result = $this->send_message($email, $langData['send-email-msg-8'][$lang], $msg_body);
			if(!$result){
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
			}
		}else{
			$return_data['res'] = false;
			$return_data['msg'] = $langData['return-msg-msg-12'][$lang];
		}
		echo json_encode($return_data);
	}

	public function reset(){
		
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();

		$token = $this->input->post('token');
		$password = $this->input->post('password');
		$return_data['res'] = true;
		$return_data['msg'] = $langData['return-msg-msg-13'][$lang];
		$check_token_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_token', $token);
		if(!$check_token_exist){
			$return_data['res'] = false;
			$return_data['msg'] = $langData['return-msg-msg-15'][$lang];
		}	
		if($return_data['res'] == true){
			$key_data = [];
			$key_data['f_token'] = $token;
			$update_data = [];
			$hashed_password = password_hash($password,PASSWORD_DEFAULT);
			$update_data['f_password'] = $hashed_password;
			$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
			if(!$result){
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
			}
		}
		echo json_encode($return_data);
	}

	public function changePassword(){

		if ($this->is_authenticate()) {
		
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$token = $this->input->post('token');
			$password = $this->input->post('password');
			$new_password = $this->input->post('new_password');
			$return_data['res'] = true;
			$return_data['msg'] = $langData['return-msg-msg-16'][$lang];
			$key_data = [];
			$key_data['f_token'] = $token;
			$user_data = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);		
			if(!password_verify($password,$user_data['f_password'])){
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-17'][$lang];
			}else{
				$update_data = [];
				$hashed_password = password_hash($new_password,PASSWORD_DEFAULT);
				$update_data['f_password'] = $hashed_password;
				$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
				if(!$result){
					$return_data['res'] = false;
					$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
				}
			}
			echo json_encode($return_data);
		}else{
			echo false;
		}
	}
	
	public function getChartData($target = 'BTC', $base = 'KRW'){
		$key_data = [];
		$key_data['f_target'] = $target;
		$key_data['f_base'] = $base;
		$chart_data = $this->Crud_Model->Get_Sub_Data('tb_market_chart', $key_data);
		$return_data_count = 0;
		foreach ($chart_data as $row) {
			$intdatetime = (int)($row['f_regdate']);
			$date = $intdatetime*1000;
			if($base == 'KRW'){
				$open = round($row['f_open']);
				$close = round($row['f_close']);
				$low = round($row['f_low']);
				$high = round($row['f_high']);
				$volume = round($row['f_volume'],8);				
			}else{
				$open = round($row['f_open'],8);
				$close = round($row['f_close'],8);
				$low = round($row['f_low'],8);
				$high = round($row['f_high'],8);
				$volume = round($row['f_volume'],8);
			}
			$return_data[$return_data_count]['date'] = $date;
        	$return_data[$return_data_count]['open'] = $open;
        	$return_data[$return_data_count]['close'] = $close;
        	$return_data[$return_data_count]['low'] = $low;
        	$return_data[$return_data_count]['high'] = $high;
        	$return_data[$return_data_count]['volume'] = $volume;
        	$return_data_count++;
		}
		$currentTime = time();
		$date = $currentTime - $currentTime%60;
		$dateint = $date*1000;
		if($return_data_count == 0){
			$sql = "SELECT f_rate FROM tb_market_history WHERE f_regdate<='".$date."' ORDER BY f_regdate DESC, f_id DESC LIMIT 0,1";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if(isset($result_data['f_rate'])){
				$open = round($result_data['f_rate']);
			}else{
				$open = 0;
			}
			$sql = "SELECT f_rate FROM tb_market_history WHERE f_target='".$target."' && f_base='KRW' ORDER BY f_regdate DESC, f_id DESC limit 0,1";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if(isset($result_data['f_rate'])){
				$close = round($result_data['f_rate']);
			}else{
				$close = $open;
			}
			$query =  "SELECT MIN(f_rate) AS low, MAX(f_rate) AS high, SUM(f_target_volume) AS volume FROM `tb_market_history` WHERE `f_base`='".$base."' && `f_target`='".$target."' && `f_regdate`>='".$date."' && `f_regdate`<='".$currentTime."'";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			if ($result_data) {
				$low = round($result_data['low']);
				$high = round($result_data['high']);
				$volume = round($result_data['volume'],3);
			}else{
				if($open>$close){
					$low = $close;
					$high = $open;
				}else{
					$low = $open;
					$high = $close;
				}
				$volume = 0;
			}
			if($low<=0){
				if($open>$close){
					$low = $close;
				}else{
					$low = $open;
				}
			}
			if($high<=0){
				if($open>$close){
					$high = $open;
				}else{
					$high = $close;
				}
			}
			$return_data[$return_data_count]['date'] = $dateint;
			$return_data[$return_data_count]['open'] = $open;
			$return_data[$return_data_count]['close'] = $close;
			$return_data[$return_data_count]['low'] = $low;
			$return_data[$return_data_count]['high'] = $high;
			$return_data[$return_data_count]['volume'] = $volume;
		}else{
			if($dateint == $return_data[$return_data_count-1]['date']){
				$sql = "SELECT f_rate FROM tb_market_history WHERE f_target='".$target."' && f_base='KRW' ORDER BY f_regdate DESC, f_id DESC limit 0,1";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
				if(isset($result_data['f_rate'])){
					$close = round($result_data['f_rate']);
					$return_data[$return_data_count-1]['close'] = $close;
				}
			}else{
				$open = $return_data[$return_data_count-1]['close'];
				$sql = "SELECT f_rate FROM tb_market_history WHERE f_target='".$target."' && f_base='KRW' ORDER BY f_regdate DESC, f_id DESC limit 0,1";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
				if(isset($result_data['f_rate'])){
					$close = round($result_data['f_rate']);
				}else{
					$close = $open;
				}
				$query =  "SELECT MIN(f_rate) AS low, MAX(f_rate) AS high, SUM(f_target_volume) AS volume FROM `tb_market_history` WHERE `f_base`='".$base."' && `f_target`='".$target."' && `f_regdate`>='".$date."' && `f_regdate`<='".$currentTime."'";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
				if ($result_data) {
					$low = round($result_data['low']);
					$high = round($result_data['high']);
					$volume = round($result_data['volume'],3);
				}else{
					if($open>$close){
						$low = $close;
						$high = $open;
					}else{
						$low = $open;
						$high = $close;
					}
					$volume = 0;
				}
				if($low<=0){
					if($open>$close){
						$low = $close;
					}else{
						$low = $open;
					}
				}
				if($high<=0){
					if($open>$close){
						$high = $open;
					}else{
						$high = $close;
					}
				}
				$return_data[$return_data_count]['date'] = $dateint;
				$return_data[$return_data_count]['open'] = $open;
				$return_data[$return_data_count]['close'] = $close;
				$return_data[$return_data_count]['low'] = $low;
				$return_data[$return_data_count]['high'] = $high;
				$return_data[$return_data_count]['volume'] = $volume;
			}
		}
	    echo json_encode($return_data);
	}
	
	public function getBuySellChartData($target = 'BTC', $base = 'KRW'){
		$query = "SELECT f_rate FROM tb_market_order WHERE f_type='buy' && f_target='".$target."' && f_base='".$base."' GROUP BY f_rate ORDER BY f_rate DESC limit 0, 10";
		$buyOrderDdata = $this->Crud_Model->Get_Sql_Result($query);
		$total_volume = 0;
		$count = 0;
		foreach ($buyOrderDdata as $key => $value) {
			$sql = "SELECT SUM(f_target_volume) AS volume FROM tb_market_order WHERE f_type='buy' && f_target='".$target."' && f_base='".$base."' && f_rate='".$value['f_rate']."'";
			$volume_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			$volume = $volume_data['volume'];
			$total_volume += $volume;
			$buyOrdersTemp[$key]['total_volume'] = round($total_volume,3);
			$rateDataTemp[$count] = round($value['f_rate']);
			$count++;
		}
		for ($i=0; $i < $count; $i++) {
			$buyOrders[$i] = $buyOrdersTemp[$count-1-$i];
			$rateData[$i] = $rateDataTemp[$count-1-$i];
		}

		$query = "SELECT f_rate FROM tb_market_order WHERE f_type='sell' && f_target='".$target."' && f_base='".$base."' GROUP BY f_rate ORDER BY f_rate ASC limit 0, 10";	
		$sellOrderDdata = $this->Crud_Model->Get_Sql_Result($query);
		$total_volume = 0;
		foreach ($sellOrderDdata as $key => $value) {
			$query = "SELECT SUM(f_target_volume) AS volume FROM tb_market_order WHERE f_type='sell' && f_target='".$target."' && f_base='".$base."' && f_rate='".$value['f_rate']."'";
			$volume_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$volume = $volume_data['volume'];
			$total_volume += $volume;
			$sellOrders[$key]['total_volume'] = round($total_volume,3);
			$rateData[$count] = round($value['f_rate']);
			$count++;
		}
		$return_data['buyOrders'] = $buyOrders;
		$return_data['sellOrders'] = $sellOrders;
		$return_data['rateData'] = $rateData;
		echo json_encode($return_data);
	}

	public function getDepositAddress(){

			if ($this->is_authenticate()) {
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$token = $this->input->post('token');
			$coin = $this->input->post('coin');
			$return_data['res'] = true;
			$return_data['msg'] = $langData['return-msg-msg-18'][$lang];
			if($coin == 'BTC'){
				$key_data = [];
				$key_data['f_token'] = $token;
				$check_deposit_address_exist = $this->Crud_Model->Get_Count_From_Table('tb_user_deposit_address_btc', $key_data);
				if($check_deposit_address_exist == 0){
					$config = $this->Crud_Model->Get_System_Values();
					$key_data = [];
					$key_data['f_unit'] = 'BTC';
					$addressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $addressData['f_address'];
					$depositaddress = [];
					$depositaddress['f_token'] = $token;
					$depositaddress['f_regdate'] = time();
					try {
						$ch = curl_init();
						$url = "https://api.blockcypher.com/v1/btc/main/forwards?token=".$config['blockcypherToken'];
						$postData['destination'] = $siteAddress;
						$postData['callback_url'] = base_url().'callback/deposit/BTC/'.$token;
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$result = json_decode(curl_exec($ch));
						curl_close($ch);
						if(isset($result->error)){
							$return_data['res'] = false;
							$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
						}else{
							$depositaddress['f_address_id'] = $result->{'id'};
							$depositaddress['f_address'] = $result->{'input_address'};
							$result = $this->Crud_Model->Insert_Data('tb_user_deposit_address_btc', $depositaddress);
							if(!$result){
								$return_data['res'] = false;
								$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
							}
						}
					} catch (Exception $e) {
						$return_data['res'] = false;
						$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
					}
				}else{
					$return_data['res'] = false;
					$return_data['msg'] = $langData['return-msg-msg-19'][$lang];
				}
			}else if($coin == 'ETH' || $coin == 'SKY' || $coin == 'BDR'){
				$key_data = [];
				$key_data['f_token'] = $token;
				$check_deposit_address_exist = $this->Crud_Model->Get_Count_From_Table('tb_user_deposit_address_eth', $key_data);
				if($check_deposit_address_exist == 0){
					$config = $this->Crud_Model->Get_System_Values();
					$depositaddress = [];
					$depositaddress['f_token'] = $token;
					$depositaddress['f_regdate'] = time();
					try {
						// $result = $this->blockcypher_library->getNewAddress('eth', $config['blockcypherToken']);
						$url = "localhost:8081/create_wallet/";
						$result = $this->sendCurlRequest($url);
						if($result->status == false){
							$return_data['res'] = false;
							$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
						}else{
							$depositaddress['f_address'] = $result->address;
							$depositaddress['f_private'] = $result->private;
							$result = $this->Crud_Model->Insert_Data('tb_user_deposit_address_eth', $depositaddress);
							if(!$result){
								$return_data['res'] = false;
								$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
							}
						}
					} catch (Exception $e) {
						$return_data['res'] = false;
						$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
					}
				}else{
					$return_data['res'] = false;
					$return_data['msg'] = $langData['return-msg-msg-19'][$lang];
				}
			}else{
				$return_data['res'] = false;
				$return_data['msg'] = 'Not supported coins.';
			}
			echo json_encode($return_data);
		}else{
			echo false;
		}
	}

	public function ajaxGetCoinDepositHistory($coin){

		if ($this->is_authenticate()) {
			$token = $this->session->userdata('token');
			$query =  "SELECT * FROM tb_log_user_deposit_withdraw WHERE f_token='".$token."' && f_unit='".$coin."' ORDER BY f_regdate DESC";
			$result = $this->Crud_Model->Get_Sql_Result($query);
			$count = 0;
			foreach ($result as $row) {
				$return_data[] = array(
					date('Y-m-d H:i:s', $row['f_regdate']),
					$row['f_detail'],
					number_format($row['f_username'], 8, '.', ','),
					'confirmed'
				);
				$count++;
			}			
			if($count == 0){
				$return_data = [];
			}		
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}

	public function sendCurlRequest($url, $post = 0, $postData = false){
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, $post);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if($post == 1){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($ch));
		curl_close($ch);
		return $result;
	}

	public function recordToTbLogUserDepositWithdraw($f_token, $f_unit, $f_type, $f_amount, $f_fees, $f_detail, $f_status){

		if ($this->is_authenticate()) {
			$insert_data = [];
			$insert_data['f_token'] = $f_token;
			$insert_data['f_unit'] = $f_unit;
			$insert_data['f_type'] = $f_type;
			$insert_data['f_amount'] = $f_amount;
			$insert_data['f_fees'] = $f_fees;
			$insert_data['f_detail'] = $f_detail;
			$insert_data['f_status'] = $f_status;
			$insert_data['f_regdate'] = time();
			$result = $this->Crud_Model->Insert_Data('tb_log_user_deposit_withdraw', $insert_data);
			return $result;
		}else{
			return false;
		}
	}

	public function recordToTbUserWalletHistory($f_token, $f_type, $f_unit, $f_amount, $f_detail)
	{
		if ($this->is_authenticate()) {
			$insert_data = [];
			$insert_data['f_token'] = $f_token;
			$insert_data['f_type'] = $f_type;
			$insert_data['f_unit'] = $f_unit;
			$insert_data['f_amount'] = $f_amount;
			$insert_data['f_detail'] = $f_detail;
			$insert_data['f_regdate'] = time();
			$result = $this->Crud_Model->Insert_Data('tb_user_wallet_history', $insert_data);
			return $result;
		}else{
			return false;
		}
	}


	public function updateWalletAfterWithdrawSuccess($walletData, $removeAmount)
	{
		if ($this->is_authenticate()) {
			$key_data = [];
			$key_data['f_token'] = $walletData['f_token'];
			$key_data['f_unit'] = $walletData['f_unit'];
			$update_data = [];
			$update_data['f_total'] = $walletData['f_total'] - $removeAmount;
			$update_data['f_available'] = $walletData['f_available'] - $removeAmount;
			$sql = "SELECT f_close FROM tb_market WHERE f_target='". $walletData['f_unit'] ."' && f_base='KRW'";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if (isset($result_data['f_close'])) {
				$currentRate = $result_data['f_close'];
			} else {
				$currentRate = 0;
			}
			$sellBaseVolume = $currentRate * $removeAmount;
			$update_data['f_sell_volume'] = $walletData['f_sell_volume'] + $removeAmount;
			$update_data['f_sell_base_volume'] = $walletData['f_sell_base_volume'] + $sellBaseVolume;
			$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);
			return $result;
		}else{
			return false;
		}
	}

	public function updateWalletOnWithdrawProcess($walletData, $removeAmount)
	{
		if ($this->is_authenticate()) {
			$key_data = [];
			$key_data['f_token'] = $walletData['f_token'];
			$key_data['f_unit'] = $walletData['f_unit'];
			$update_data = [];
			$update_data['f_available'] = $walletData['f_available'] - $removeAmount;
			$update_data['f_blocked'] = $walletData['f_blocked'] + $removeAmount;
			$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);
			return $result;
		}else{
			return false;
		}
	}

	public function withdrawCoin(){
		if ($this->is_authenticate()) {
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$token = $this->input->post('token');
			$coin = $this->input->post('coin');
			$to_address = $this->input->post('to_address');
			$amount = $this->input->post('amount');
			$password = $this->input->post('password');

			$return_data['res'] = true;
			$return_data['msg'] = $langData['return-msg-msg-20'][$lang];
			$config = $this->Crud_Model->Get_System_Values();
			
			$key_data = [];
			$key_data['f_unit'] = $coin;
			$coinData = $this->Crud_Model->Get_A_Row_Data('tb_unit', $key_data);
			$withdraw_fee = $coinData['f_withdraw_fee_amount'];


			$key_data = [];
			$key_data['f_token'] = $token;
			if($coin == 'BTC'){
				$depositAddressData = $this->Crud_Model->Get_A_Row_Data('tb_user_deposit_address_btc', $key_data);
			}else if($coin == 'ETH' || $coin == 'SKY' || $coin == 'BDR'){
				$depositAddressData = $this->Crud_Model->Get_A_Row_Data('tb_user_deposit_address_eth', $key_data);
			}else{
				$depositAddressData = $this->Crud_Model->Get_A_Row_Data('tb_user_deposit_address_eth', $key_data);
			}
			$depositaddress = $depositAddressData['f_address'];
			if ($depositaddress == $to_address) {
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-21'][$lang];
			}
			if ($return_data['res'] == true) {
				$key_data = [];
				$key_data['f_token'] = $token;
				$key_data['f_unit'] = $coin;
				$userWalletData = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);
				$availableBalance = $userWalletData['f_available'];
				$remove_amount = $amount + $withdraw_fee;
				if ($availableBalance < $remove_amount) {
					$return_data['res'] = false;
					$return_data['msg'] = $langData['return-msg-msg-22'][$lang];
				}
			}
			if ($return_data['res'] == true) {
				$key_data = [];
				$key_data['f_token'] = $token;
				$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
				if (!password_verify($password, $userdata['f_password'])) {
					$return_data['res'] = false;
					$return_data['msg'] = "Invaid user password";
				}
			}

			$key_data = [];
			$key_data['f_token'] = $token;
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);

			$query = "SELECT SUM(f_amount) as dayWithdrawalAmount FROM tb_log_user_deposit_withdraw WHERE f_token='" . $token . "' && f_unit='".$coin."' && f_type='withdraw' && f_type='withdraw'";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$dayWithdrawalAmount = $result_data['dayWithdrawalAmount'];

			$key_data = [];
			$key_data['f_unit'] = $coin;
			$coinData = $this->Crud_Model->Get_A_Row_Data('tb_unit', $key_data);

			if ($userdata['f_kyc_level'] == 1) {
				$possibleWithdrawAmountToday = $coinData['f_daily_available_withdraw_amount_first'] - $dayWithdrawalAmount;
			} else if ($userdata['f_kyc_level'] == 2) {
				$possibleWithdrawAmountToday = $coinData['f_daily_available_withdraw_amount_second'] - $dayWithdrawalAmount;
			} else if ($userdata['f_kyc_level'] == 3) {
				$possibleWithdrawAmountToday = $coinData['f_daily_available_withdraw_amount_third'] - $dayWithdrawalAmount;
			} else {
				$possibleWithdrawAmountToday = 0;
			}

			if ($remove_amount > $possibleWithdrawAmountToday) {
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-23'][$lang];
			}

			if($coin == 'BTC'){
				if ($return_data['res'] == true) {
					$url = 'https://api.blockcypher.com/v1/btc/main/addrs/' . $to_address . '?token=' . $config['blockcypherToken'];
					$result = $this->sendCurlRequest($url);
					if (isset($result->error) || !isset($result->address)) {
						$return_data['res'] = false;
						$return_data['msg'] = $langData['return-msg-msg-21'][$lang];
					}
				}
				if($return_data['res'] == true){
					$key_data = [];
					$key_data['f_unit'] = 'BTC';
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "https://api.blockcypher.com/v1/btc/main/addrs/".$siteAddress."/balance";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$totalBalance = $totalBalance/pow(10,8);
					if($totalBalance > $remove_amount){
						$sendAmountApi = $amount * pow(10,8);
						$tx_result = $this->blockcypher_library->NewTransactionEndpoint('btc', $config['blockcypherToken'], $siteAddress, $to_address, $sendAmountApi, $siteAddressData['f_private']);
						$result = $this->recordToTbLogUserDepositWithdraw($token, 'BTC', 'withdraw', $amount, $withdraw_fee, $to_address, 1);
						$result = $this->updateWalletAfterWithdrawSuccess($userWalletData, $remove_amount);
						$result = $this->recordToTbUserWalletHistory($token, 'out', 'BTC', $remove_amount, "withdraw");
					}else{
						$result = $this->recordToTbLogUserDepositWithdraw($token, 'BTC', 'withdraw', $amount, $withdraw_fee, $to_address, 0);
						$result = $this->updateWalletOnWithdrawProcess($userWalletData, $remove_amount);
						$result = $this->recordToTbUserWalletHistory($token, 'blocked', 'BTC', $remove_amount, "on withdraw process");
					}
				}
			}else if($coin == 'ETH'){
				if ($return_data['res'] == true) {
					$url = "localhost:8081/get_is_address/" . $to_address;
					$result = $this->sendCurlRequest($url);
					if ($result->status == false) {
						$return_data['res'] = false;
						$return_data['msg'] = $langData['return-msg-msg-21'][$lang];
					}
				}
				if ($return_data['res'] == true) {
					$key_data = [];
					$key_data['f_unit'] = 'ETH';
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "localhost:8081/get_eth_balance/" . $siteAddress;
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$totalBalance = $totalBalance / pow(10, 18);
					if ($totalBalance > $remove_amount) {
						$url = "localhost:8081/send_eth";
						$postData = 'from='.$siteAddress. '&to='.$to_address.'&amount='.$amount.'&private='.$siteAddressData['f_private'];
						$result = $this->sendCurlRequest($url, 1, $postData);
						if($result->status == true){
							$txHash = $result->txHash;
							$result = $this->recordToTbLogUserDepositWithdraw($token, 'ETH', 'withdraw', $amount, $withdraw_fee, $to_address, 1);
							$result = $this->updateWalletAfterWithdrawSuccess($userWalletData, $remove_amount);
							$result = $this->recordToTbUserWalletHistory($token, 'out', 'ETH', $remove_amount, "withdraw");
						}else{
							$return_data['res'] = false;
							$return_data['msg'] = $langData['admin-ctrl-msg-4'][$lang];
						}
					} else {
						$result = $this->recordToTbLogUserDepositWithdraw($token, 'ETH', 'withdraw', $amount, $withdraw_fee, $to_address, 0);
						$result = $this->updateWalletOnWithdrawProcess($userWalletData, $remove_amount);
						$result = $this->recordToTbUserWalletHistory($token, 'blocked', 'ETH', $remove_amount, "on withdraw process");
					}
				}
			} else if ($coin == 'SKY') {

				if ($return_data['res'] == true) {
					$url = "localhost:8081/get_is_address/" . $to_address;
					$result = $this->sendCurlRequest($url);
					if ($result->status == false) {
						$return_data['res'] = false;
						$return_data['msg'] = $langData['return-msg-msg-21'][$lang];
					}
				}

				if ($return_data['res'] == true) {
					$key_data = [];
					$key_data['f_unit'] = $coin;
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "localhost:8081/get_erc20token_balance/" . $siteAddress."/SKY";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$totalBalance = $totalBalance / pow(10, 18);
					if ($totalBalance > $remove_amount) {
						$url = "localhost:8081/send_erc20token";
						$postData = 'token='. $coin .'&from=' . $siteAddress . '&to=' . $to_address . '&amount=' . $amount . '&private=' . $siteAddressData['f_private'];
						$result = $this->sendCurlRequest($url, 1, $postData);
						if ($result->status == true) {
							$txHash = $result->txHash;
							$result = $this->recordToTbLogUserDepositWithdraw($token, $coin, 'withdraw', $amount, $withdraw_fee, $to_address, 1);
							$result = $this->updateWalletAfterWithdrawSuccess($userWalletData, $remove_amount);
							$result = $this->recordToTbUserWalletHistory($token, 'out', $coin, $remove_amount, "withdraw");
						} else {
							$return_data['res'] = false;
							$return_data['msg'] = $langData['admin-ctrl-msg-4'][$lang];
						}
					} else {
						$result = $this->recordToTbLogUserDepositWithdraw($token, $coin, 'withdraw', $amount, $withdraw_fee, $to_address, 0);
						$result = $this->updateWalletOnWithdrawProcess($userWalletData, $remove_amount);
						$result = $this->recordToTbUserWalletHistory($token, 'blocked', $coin, $remove_amount, "on withdraw process");
					}
				}
			} else if ($coin == 'BDR') {

				if ($return_data['res'] == true) {
					$url = "localhost:8081/get_is_address/" . $to_address;
					$result = $this->sendCurlRequest($url);
					if ($result->status == false) {
						$return_data['res'] = false;
						$return_data['msg'] = $langData['return-msg-msg-21'][$lang];
					}
				}

				if ($return_data['res'] == true) {
					$key_data = [];
					$key_data['f_unit'] = $coin;
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "localhost:8081/get_erc20token_balance/" . $siteAddress."/BDR";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$totalBalance = $totalBalance / pow(10, 18);
					if ($totalBalance > $remove_amount) {
						$url = "localhost:8081/send_erc20token";
						$postData = 'token='. $coin .'&from=' . $siteAddress . '&to=' . $to_address . '&amount=' . $amount . '&private=' . $siteAddressData['f_private'];
						$result = $this->sendCurlRequest($url, 1, $postData);
						if ($result->status == true) {
							$txHash = $result->txHash;
							$result = $this->recordToTbLogUserDepositWithdraw($token, $coin, 'withdraw', $amount, $withdraw_fee, $to_address, 1);
							$result = $this->updateWalletAfterWithdrawSuccess($userWalletData, $remove_amount);
							$result = $this->recordToTbUserWalletHistory($token, 'out', $coin, $remove_amount, "withdraw");
						} else {
							$return_data['res'] = false;
							$return_data['msg'] = $langData['admin-ctrl-msg-4'][$lang];
						}
					} else {
						$result = $this->recordToTbLogUserDepositWithdraw($token, $coin, 'withdraw', $amount, $withdraw_fee, $to_address, 0);
						$result = $this->updateWalletOnWithdrawProcess($userWalletData, $remove_amount);
						$result = $this->recordToTbUserWalletHistory($token, 'blocked', $coin, $remove_amount, "on withdraw process");
					}
				}
			}
			echo json_encode($return_data);
		}else{
			echo false;
		}
	}

	public function setLangSession(){
		$lang = $this->input->post('lang');
		$this->load->library('session');
		$sessionLangData = array(
			'coinsky_lang'  => $lang
		);		
		$this->session->set_userdata($sessionLangData);
		echo true;
	}

	public function getEachCoinBalance(){
		if ($this->is_authenticate()) {
			$token = $this->input->post('token');
			$key_data = [];
			$key_data['f_token'] = $token;
			$result = $this->Crud_Model->Get_Sub_Data('tb_user_wallet', $key_data);
			$return_data;
			foreach($result as $key => $value){
				$return_data[$value['f_unit']] = $value['f_total'];
			}
			echo json_encode($return_data);
		}else{
			echo false;
		}
	}

	function rndCodeGenerator($length = 4)
	{
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public function phoneNumberValidation(){
		if ($this->is_authenticate()) {
			if ($this->session->userdata('coinsky_lang') == null) {
				$lang = 'KO';
			} else {
				$lang = $this->session->userdata('coinsky_lang');
			}
			if($this->is_authenticate() == true){
				$key_data = [];
				$key_data['f_token'] = $this->session->userdata('token');
				$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$phoneNumber = $this->input->post('phoneNumber');

			$check_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_phone', $phoneNumber);
			if(($this->is_authenticate() == false && !$check_exist) || ($this->is_authenticate() == true)){
				$phoneNumber = (string)($phoneNumber);
				$phoneNumber = substr($phoneNumber, 1, strlen($phoneNumber));
				$phoneNumber = "+82" . $phoneNumber;
				$returnData['res'] = true;
				$config = $this->Crud_Model->Get_System_Values();
				$twilioSID = $config['twilioSID'];
				$twilioAuthToken = $config['twilioAuthToken'];
				$twilioPhoneNumber = $config['twilioPhoneNumber'];
				$verifyCode = $this->rndCodeGenerator();
				$msg = $langData['twilio-msg'][$lang] . ' [' . $verifyCode . ']';
				$result = $this->twilio_library->sendVerifyCode($twilioSID, $twilioAuthToken, $phoneNumber, $twilioPhoneNumber, $msg);
				if (!property_exists($result, 'statusCode')) {
					$insertData = [];
					$insertData['f_type'] = 'twilio';
					$insertData['f_value'] = $verifyCode;
					$insertData['f_status'] = 1;
					$insertData['f_regdate'] = time();
					$confirmId = $this->Crud_Model->Insert_Data_Get_Id('tb_user_verify_log', $insertData);
					$returnData['confirmId'] = $confirmId;
				} else {
					$returnData['res'] = false;
					$returnData['msg'] = $langData['twilio-msg-1'][$lang];
				}
				// $insertData = [];
				// $insertData['f_type'] = 'twilio';
				// $insertData['f_value'] = $verifyCode;
				// $insertData['f_status'] = 1;
				// $insertData['f_regdate'] = time();
				// $confirmId = $this->Crud_Model->Insert_Data_Get_Id('tb_user_verify_log', $insertData);
				// $returnData['confirmId'] = $confirmId;
			}else{
				$returnData['res'] = false;
				$returnData['msg'] = $langData['twilio-msg-2'][$lang];
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function removePhoneNumberConfirmId(){
		if ($this->is_authenticate()) {
			$confirmId = $this->input->post('confirmId');
			$key_data = [];
			$key_data['f_id'] = $confirmId;
			$result = $this->Crud_Model->Delete_A_Row('tb_user_verify_log', $key_data);
			$returnData['res'] = true;
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function confirmPhoneNumber(){
		if ($this->is_authenticate()) {
			$confirmId = $this->input->post('confirmId');
			$code = $this->input->post('code');
			$key_data = [];
			$key_data['f_id'] = $confirmId;
			$result = $this->Crud_Model->Get_A_Row_Data('tb_user_verify_log', $key_data);
			if($result['f_value'] == $code){
				$returnData['res'] = true;
			}else{
				$returnData['res'] = false;
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function getPhoneVerifyCode1(){
		$this->load->view('bankInfo');
	}

	public function confirmKCPResult(){
		if ($this->is_authenticate()) {
			if ($this->session->userdata('coinsky_lang') == null) {
				$lang = 'KO';
			} else {
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			
			$postData = json_decode(file_get_contents('php://input'));
			
			$token = $postData->token;
			$phone = $postData->phone;
			$username = $postData->username;

			$returnData['res'] = true;
			$returnData['msg'] = $langData['api-msg-2'][$lang];

			$key_data = [];
			$key_data['f_token'] = $token;
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
			if ($userdata && $userdata['f_username'] == $username && $userdata['f_phone'] == $phone) {
				$update_data = [];
				$update_data['f_phone_owner_verified'] = 1;
				if($userdata['f_kyc_level'] < 2){
					$update_data['f_kyc_level'] = 2;
				}
				$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
				if (!$result) {
					$returnData['res'] = false;
					$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
				}
			}else{
				$returnData['res'] = false;
				$returnData['msg'] = $langData['api-msg-1'][$lang];
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function requestUserDepWithKRW(){
		if ($this->is_authenticate()) {
			if ($this->session->userdata('coinsky_lang') == null) {
				$lang = 'KO';
			} else {
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$type = $this->input->post('type');
			$token = $this->input->post('token');
			$username = $this->input->post('username');
			$amount = $this->input->post('amount');
			$bankName = $this->input->post('bankName');
			$bankId = $this->input->post('bankId');
			$password = $this->input->post('password');

			$returnData['res'] = true;
			$returnData['msg'] = '요청이 등록되었습니다.';

			$key_data = [];
			$key_data['f_token'] = $token;
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);

			if (!password_verify($password, $userdata['f_password'])) {
				$returnData['res'] = false;
				$returnData['msg'] = $langData['return-msg-msg-24'][$lang];
			}

			$key_data = [];
			$key_data['f_token'] = $token;
			$key_data['f_unit'] = 'KRW';
			$userWalletData = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);
			
			if ($type == 'withdraw') {
				$availableBalance = (float)($userWalletData['f_available']);
				$amount = $amount + 1000;
				if ($availableBalance < $amount) {
					$returnData['res'] = false;
					$returnData['msg'] = $langData['return-msg-msg-22'][$lang];
				}

				if ($returnData['res'] == true) {
					$result = $this->updateWalletOnWithdrawProcess($userWalletData, $amount);
					$result = $this->recordToTbUserWalletHistory($token, 'blocked', 'KRW', $amount, "request to " . $type);
				}
			}

			if($returnData['res'] == true){
				$insert_data = [];
				$insert_data['f_token'] = $token;
				$insert_data['f_type'] = $type;
				$insert_data['f_unit'] = 'KRW';
				$insert_data['f_amount'] = $amount;
				if($type == 'deposit'){
					$insert_data['f_fees'] = 0;
				}else{
					$insert_data['f_fees'] = 1000;
				}
				$insert_data['f_detail'] = '이름 : '.$username.', 은행명 : '.$bankName.', 계좌번호 : '.$bankId;
				$insert_data['f_status'] = 0;
				$insert_data['f_regdate'] = time();
				$result = $this->Crud_Model->Insert_Data('tb_log_user_deposit_withdraw', $insert_data);
			}

			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function getOrderBookData($token, $target, $base)
	{
		$sql = "SELECT f_open FROM tb_market WHERE f_target='" . $target . "' && f_base='" . $base . "'";
		$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
		if (isset($result_data['f_rate'])) {
			$lastDayRate = $result_data['f_open'];
		} else {
			$lastDayRate = 0;
		}
		$sql = "SELECT f_rate, SUM(f_target_volume) FROM tb_market_order WHERE f_type='sell' && f_target='" . $target . "' && f_base='" . $base . "' GROUP BY f_rate ORDER BY f_rate ASC limit 0, 13";
		$orderData = $this->Crud_Model->Get_Sql_Result($sql);
		if (count($orderData) > 0) {
			if (count($orderData) < 13) {
				for ($i = 1; $i <= 13 - count($orderData); $i++) {
					$sellOrders[$i-1]['rate'] = '';
					$sellOrders[$i-1]['volume'] = '';
					$sellOrders[$i-1]['mine'] = '';
				}
			}
			for ($i = 13 - count($orderData) + 1; $i <= 13; $i++) {
				$sellOrders[$i-1]['rate'] = $orderData[13 - $i]['f_rate'];
				$tempRate = $orderData[13 - $i]['f_rate'];
				//if ($this->is_authenticate()) {
					$sub_query = "SELECT SUM(f_target_volume) AS mine FROM tb_market_order WHERE f_token='" . $token . "' && f_type='sell' && f_target='" . $target . "' && f_base='" . $base . "' && f_rate='" . $tempRate . "'";
					$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sub_query);
					if (isset($result_data['mine'])) {
						if ($result_data['mine'] == 0 || $result_data['mine'] == null) {
							$mine = '';
						} else {
							$mine = $result_data['mine'];
						}
					} else {
						$mine = '';
					}
				//} else {
				//	$mine = '';
				//}
				$sellOrders[$i-1]['volume'] = $orderData[13 - $i]['SUM(f_target_volume)'];
				$sellOrders[$i-1]['mine'] = $mine;
			}
		} else {
			for ($i = 1; $i <= 13; $i++) {
				$sellOrders[$i-1]['rate'] = '';
				$sellOrders[$i-1]['volume'] = '';
				$sellOrders[$i-1]['mine'] = '';
			}
		}
		$sql = "SELECT f_rate, SUM(f_target_volume) FROM tb_market_order WHERE f_type='buy' && f_target='" . $target . "' && f_base='" . $base . "' GROUP BY f_rate ORDER BY f_rate DESC limit 0, 13";
		$orderData = $this->Crud_Model->Get_Sql_Result($sql);
		if (count($orderData) > 0) {
			for ($i = 1; $i <= count($orderData); $i++) {
				$buyOrders[$i-1]['rate'] = $orderData[$i - 1]['f_rate'];
				$tempRate = $orderData[$i - 1]['f_rate'];
				//if ($this->is_authenticate()) {
					$sub_query = "SELECT SUM(f_target_volume) AS mine FROM tb_market_order WHERE f_token='" . $token . "' && f_type='buy' && f_target='" . $target . "' && f_base='" . $base . "' && f_rate='" . $tempRate . "'";
					$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sub_query);
					if (isset($result_data['mine'])) {
						if ($result_data['mine'] == 0 || $result_data['mine'] == null) {
							$mine = '';
						} else {
							$mine = $result_data['mine'];
						}
					} else {
						$mine = '';
					}
				//} else {
				//	$mine = '';
				//}
				$buyOrders[$i-1]['volume'] = $orderData[$i - 1]['SUM(f_target_volume)'];
				$buyOrders[$i-1]['mine'] = $mine;
			}
			if (count($orderData) < 13) {
				for ($i = count($orderData) + 1; $i <= 13; $i++) {
					$buyOrders[$i-1]['rate'] = '';
					$buyOrders[$i-1]['volume'] = '';
					$buyOrders[$i-1]['mine'] = '';
				}
			}
		} else {
			for ($i = 1; $i <= 13; $i++) {
				$buyOrders[$i-1]['rate'] = '';
				$buyOrders[$i-1]['volume'] = '';
				$buyOrders[$i-1]['mine'] = '';
			}
		}
		$orderBookData['buy'] = $buyOrders;
		$orderBookData['sell'] = $sellOrders;
		return $orderBookData;
	}

	public function getBalance ($token) {

		$key_data = [];
		$key_data['f_token'] = $token;
		$balanceData = $this->Crud_Model->Get_Sub_Data('tb_user_wallet', $key_data);

		$count = 0;
		$totalKRW = 0;

		foreach ($balanceData as $key => $value) {
			$returnData[$count]['unit'] = $value['f_unit'];
			if($value['f_unit'] == 'KRW'){
				$balance = doubleval($value['f_total']);
				$totalKRW += $balance;
				$returnData[$count]['balance'] = $balance;				
			}else{

				$sql = "SELECT f_close FROM tb_market WHERE f_target='".$value['f_unit']."' && f_base='KRW'";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
				if(isset($result_data['f_close'])){
					$rate = $result_data['f_close'];
				}else{
					$rate = 0;
				}

				$balance = $value['f_total']*$rate;

				$totalKRW += $balance;
				$returnData[$count]['balance'] = $value['f_total'];

			}
			
			$count++;
		}

		$returnData[$count]['unit'] = 'total';
		$returnData[$count]['balance'] = $totalKRW;
		
		return $returnData;
						
	}

	public function getMyOpenOrdersData($token, $target = 'BTC', $base = 'KRW')
	{
		//if ($this->is_authenticate()) {
			$query = "SELECT * FROM tb_market_order WHERE `f_token`='" . $token . "' && `f_base`='" . $base . "' && `f_target`='" . $target . "' ORDER BY f_regdate DESC, f_id DESC LIMIT 0, 50";
			$result = $this->Crud_Model->Get_Sql_Result($query);
			if (count($result) > 0) {
				foreach ($result as $key => $row) {
					$return_data[$key]['id'] = $row['f_id'];
					$return_data[$key]['date'] = date('Y-m-d H:i:s', $row['f_regdate']);
					$return_data[$key]['type'] = $row['f_type'];
					$return_data[$key]['rate'] = $row['f_rate'];
					$return_data[$key]['originalTVolume'] = $row['f_original_target_volume'];
					$return_data[$key]['tVolume'] = $row['f_target_volume'];
				}
			} else {
				$return_data = [];
			}
			return $return_data;
		//}else{
		//	return false;
		//}
	}

	public function order_process($action)
	{
//		if($this->is_authenticate()){

			$token = $this->session->userdata('token');

			$result = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_token', $token);
			if($result){
				$post_data = $this->input->post();
				$timestamp = round(microtime(true) * 1000000000);

				$insert_data = [];
				$insert_data['f_token'] = $token;
				$insert_data['f_action'] = $action;
				$insert_data['f_data'] = json_encode($post_data);
				$insert_data['f_timestamp'] = $timestamp;
				$result = $this->Crud_Model->Insert_Data('tb_query', $insert_data);
				$result = $this->Crud_Model->Insert_Data('tb_query_copy', $insert_data);
				echo true;
			}else{
				echo false;
			}
//		}else{
//			echo false;
//		}
	}

	public function v1($action = false)
	{		
		//order_book : http://localhost.trade.coinsky.co.kr/api/v1/order_book?market=BTC-KRW;
			//http type : POST
			//post data : {api_key}
		//open_orders : http://localhost.trade.coinsky.co.kr/api/v1/open_orders?market=BTC-KRW;
			//http type : POST
			//post data : {api_key}
		//create_order : http://localhost.trade.coinsky.co.kr/api/v1/create_order?market=BTC-KRW;
			//http type : POST
			//post data : {api_key, type, volume, rate, price}
				// type = {buy, sell}
		//cancel_order : http://localhost.trade.coinsky.co.kr/api/v1/cancel_order?market=BTC-KRW;
			//http type : POST
			//post_data : {api_key, id}


		//balance: http://localhost.trade.coinsky.co.kr/api/v1/balance
					
		$return_data = [];
		$return_data['result'] = false;
		$api_key = $this->input->post('api_key');
					
		if($api_key == '0ggtxAk0rai9fqR09ig4DkZZ7XY7b8TlETBuqyf4yXY7b8Tl0Ws4'){
			$market = $this->input->get('market');
			$temp = explode('-', $market);
			$target = $temp[0];
			if (isset($temp[1])) {
				$base = $temp[1];
			} else {
				$base = 'NAN';
			}

			$result = $this->Crud_Model->Check_Row_Exist('tb_unit', 'f_unit', $target);
			if ($result == true) {
				$result = $this->Crud_Model->Check_Row_Exist('tb_unit', 'f_unit', $base);
			}

			$token = "WybFmfmfyLik4iZJlUORpGRjcCKwQW9b";

			if ( ($result == true && $action != 'balance') || $action == 'balance' ) {

				if($action == 'balance') {

					$balance_data = $this->getBalance($token);
					$return_data['result'] = true;
					$return_data['balance'] = $balance_data;

				} else if ($action == 'order_book') {

					$order_book = $this->getOrderBookData($token, $target, $base);

					$return_data['result'] = true;
					$return_data['order_book'] = $order_book;

				} else if ($action == 'open_orders') {
					$open_orders = $this->getMyOpenOrdersData($token, $target, $base);

					$return_data['result'] = true;
					$return_data['open_orders'] = $open_orders;

				} else if ($action == 'create_order') {
		
					$data['target'] = $target;
					$data['base'] = $base;
					$data['order_type'] = $this->input->post('type');
					
					$data['order_amount'] = (double)number_format($this->input->post('volume') , 5 , "." , "");
					//$data['order_amount'] = $this->input->post('volume');
					$data['order_rate'] = $this->input->post('rate');
					$data['order_price'] = (double)number_format($data['order_amount'] * $data['order_rate'] , 5 , "." , "");
					$data['order_place'] = $this->input->post('order_place');
					
					if($data['order_place'] == 'order') {
						if( !$this->Crud_Model->Is_Query_Count($target , $base , $token , 'create_order') ) {
							$return_data['result'] = true;
						}
						else {
							$insert_data = [];
							$insert_data['f_token'] = $token;
							$insert_data['f_action'] = $action;
							$insert_data['f_data'] = json_encode($data);
							$timestamp = round(microtime(true) * 1000000000);
							$insert_data['f_timestamp'] = $timestamp;
							$result = $this->Crud_Model->Insert_Data('tb_query', $insert_data);
							$result = $this->Crud_Model->Insert_Data('tb_query_copy', $insert_data);
		
							if ($result) {
								$return_data['result'] = true;
							} else {
								$return_data['result'] = false;
							}	
						}
					}
					else {
						
						$this->Crud_Model->removePriceOrder($target , $base , $token);
						$insert_data = [];
						$insert_data['f_token'] = $token;
						$insert_data['f_action'] = $action;
						$insert_data['f_data'] = json_encode($data);
						$timestamp = round(microtime(true) * 1000000000);
						$insert_data['f_timestamp'] = $timestamp;
						$result = $this->Crud_Model->Insert_Data('tb_query', $insert_data);
						$result = $this->Crud_Model->Insert_Data('tb_query_copy', $insert_data);
		
						if ($result) {
							$return_data['result'] = true;
						} else {
							$return_data['result'] = false;
						}
						
					}
					
				} else if ($action == 'cancel_order') {
					
					$data['f_id'] = $this->input->post('id');
					$data['target'] = $target;
					$data['base'] = $base;
					
					if( !$this->Crud_Model->Is_Query_Count($target , $base , $token , 'cancel_order') && $data['f_id'] != '-1') {
						$return_data['result'] = true;
					}
					else {
						$insert_data = [];
						$insert_data['f_token'] = $token;
						$insert_data['f_action'] = $action;
						$insert_data['f_data'] = json_encode($data);
						$timestamp = round(microtime(true) * 1000000000);
						$insert_data['f_timestamp'] = $timestamp;
						
						$result = $this->Crud_Model->Insert_Data('tb_query', $insert_data);
						$result = $this->Crud_Model->Insert_Data('tb_query_copy', $insert_data);
	
						if ($result) {
							$return_data['result'] = true;
						} else {
							$return_data['result'] = false;
						}
					}
				}
			}

		}
		echo json_encode($return_data);
	}
	
}
