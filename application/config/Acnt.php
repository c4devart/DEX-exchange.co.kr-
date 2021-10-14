<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PragmaRX\Google2FA\Google2FA;

class Acnt extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('Crud_Model');
        $this->load->library('session');
    }

	function is_authenticate()
	{
		if($this->session->userdata('exchange_user_login') == true){
			return true;
		}else{
			return false;
		}
	}

	public function index()
	{
		if($this->is_authenticate()){
			redirect(base_url().'acnt/prof');
		}else{
			redirect(base_url().'acnt/siin');
		}
	}

	public function prof($tab = 'password'){

		include(APPPATH.'../assets/plugin/google2fa/vendor/autoload.php');
		if($this->is_authenticate()){
			$token = $this->session->userdata('token');
			$page_data['page'] = 'prof';
			$key_data = [];
			$key_data['f_token'] = $this->session->userdata('token');
			$user_data = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
			$google2fa = new Google2FA();
			$google2fa->setAllowInsecureCallToGoogleApis(true);
		    $qrcode_url = $google2fa->getQRCodeGoogleUrl(
	            'exchange',
	            $user_data['f_email'],
	            $user_data['f_google2fa_key']
	        ); 
	        $page_data['user_data'] = $user_data;
	        $page_data['qrcode_url'] = $qrcode_url;
	        $page_data['token'] = $token;
	        $page_data['tab'] = $tab;
        
			$balanceStatus = true;
			$query =  "SELECT * FROM tb_user_wallet WHERE f_token='".$token."'";
			$balanceData = $this->Crud_Model->Get_Sql_Result($query);
			foreach ($balanceData as $key => $value) {
				$query =  "SELECT f_withdraw_fee_amount FROM tb_unit WHERE f_unit='".$value['f_unit']."'";
				$fee_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
				$fee = $fee_data['f_withdraw_fee_amount'];
				if($value['f_total'] >= $fee) $balanceStatus = false;
			}

			$onOrderStatus = true;
			$checkOrderExist = $this->Crud_Model->Check_Row_Exist('tb_market_order', 'f_token', $token);
			if($checkOrderExist){
				$onOrderStatus = false;
			}
			
			$onWithdrawStatus = true;
			$checkWithdrawExist = $this->Crud_Model->Check_Row_Exist('tb_log_user_deposit_withdraw', 'f_token', $token);
			if($checkWithdrawExist){
				$onWithdrawStatus = false;
			}

			$blockStatus = true;
			if($user_data['f_is_blocked'] == 1){
				$blockStatus = false;
			}

			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;


			$page_data['balanceStatus'] = $balanceStatus;
			$page_data['onOrderStatus'] = $onOrderStatus;
			$page_data['onWithdrawStatus'] = $onWithdrawStatus;
			$page_data['blockStatus'] = $blockStatus;

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/prof', $page_data);
			$this->load->view('temp/footer', $page_data);
		}else{
			redirect(base_url().'acnt/siin');
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

	public function levl(){

		if($this->is_authenticate()){
			$token = $this->session->userdata('token');
			$config = $this->Crud_Model->Get_System_Values();

			$page_data['page'] = 'levl';
			$page_data['token'] = $token;
			
			$key_data = [];
			$key_data['f_token'] = $token;
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;


			$key_data = [];
			$key_data['f_enabled'] = 1;
			$coinData = $this->Crud_Model->Get_Sub_Data('tb_unit', $key_data);

			$infoBankID = $config['infoBankID'];
			$infoBankSvcCode = $config['infoBankServiceCode1'];
			$reqCode1 = $this->rndCodeGenerator(3);
			$reqCode2 = $this->rndCodeGenerator(3);
			$reqCode = $reqCode1.'-'.$reqCode2;
			$reqInfo = 'id='.$infoBankID. '&svc_code=' . $infoBankSvcCode . '&req_code=' . $reqCode . '&userToken=' . $token;


			$plaintext = $reqInfo;
			$ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
			$iv = openssl_random_pseudo_bytes($ivlen);
			$key = $token;
			$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
			$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
			$ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);

			$reqInfo = urlencode($ciphertext);

			$page_data['userdata'] = $userdata;
			$page_data['coinData'] = $coinData;
			$page_data['reqInfo'] = $reqInfo;
			$page_data['callback'] = $config['infoBankUri1'];

			// print_r($reqInfo);die();

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/levl', $page_data);
			$this->load->view('temp/footer', $page_data);
		}else{
			redirect(base_url().'acnt/siin');
		}

	}

	public function gotp(){
		
		include(APPPATH.'../assets/plugin/google2fa/vendor/autoload.php');
		if($this->is_authenticate()){
			$token = $this->session->userdata('token');
			
			$page_data['page'] = 'gotp';
	        $page_data['token'] = $token;
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;

			$key_data = [];
			$key_data['f_token'] = $this->session->userdata('token');
			$user_data = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);

			$googleOtpStatus = $user_data['f_google2fa_status'];

			$google2fa = new Google2FA();
			$google2fa->setAllowInsecureCallToGoogleApis(true);
		    $qrcode_url = $google2fa->getQRCodeGoogleUrl(
	            'exchange',
	            $user_data['f_email'],
	            $user_data['f_google2fa_key']
	        ); 
	        $page_data['user_data'] = $user_data;
	        $page_data['qrcode_url'] = $qrcode_url;
			$page_data['googleOtpStatus'] = $googleOtpStatus;

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/gotp', $page_data);
			$this->load->view('temp/footer', $page_data);
		}else{
			redirect(base_url().'acnt/siin');
		}

	}

	public function actn(){
		
		if($this->is_authenticate()){
			$token = $this->session->userdata('token');
			
			$page_data['page'] = 'actn';
	        $page_data['token'] = $token;
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/actn', $page_data);
			$this->load->view('temp/footer', $page_data);
		}else{
			redirect(base_url().'acnt/siin');
		}

	}

	public function setg(){
		
		if($this->is_authenticate()){
			$token = $this->session->userdata('token');
			
			$page_data['page'] = 'setg';
	        $page_data['token'] = $token;
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/setg', $page_data);
			$this->load->view('temp/footer', $page_data);
		}else{
			redirect(base_url().'acnt/siin');
		}

	}

	public function cnfm($tab = 'withdraw'){
		
		$token = $this->session->userdata('token');
		
		$page_data['page'] = 'cnfm';
        $page_data['token'] = $token;
		
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$page_data['tab'] = $tab;

		$this->load->view('temp/header',$page_data);
		$this->load->view('acnt/cnfm', $page_data);
		$this->load->view('temp/footer', $page_data);

	}

	public function coup(){		

		if($this->is_authenticate()){
			$token = $this->session->userdata('token');
			
			$page_data['page'] = 'coup';
	        $page_data['token'] = $token;
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/coup', $page_data);
			$this->load->view('temp/footer', $page_data);
		}else{
			redirect(base_url().'acnt/siin');
		}

	}

	public function actv($token = ''){

		$check_token_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_token', $token);
		if(!$check_token_exist){
			redirect(base_url().'acnt/siin');
		}else{
			$key_data = [];
			$key_data['f_token'] = $token;
			$update_data = [];
			$update_data['f_is_activated'] = 1;
			$update_data['f_email_verified'] = 1;
			$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
			redirect(base_url().'acnt/siin');
		}

	}

	public function tempScript(){

		$coinList = $this->Crud_Model->Get_All_Table_Data('tb_unit');

		$key_data = [];
		$key_data['f_is_activated'] = 1;
		$key_data['f_email_verified'] = 1;
		$key_data['f_phone_verified'] = 1;
		$userList = $this->Crud_Model->Get_Sub_Data('tb_user', $key_data);

		foreach($userList as $key => $value){
			$token = $value['f_token'];
			foreach($coinList as $key => $coin){
				$key_data = [];
				$key_data['f_token'] = $token;
				$key_data['f_unit'] = $coin['f_unit'];
				$result = $this->Crud_Model->Check_Row_Exist_With_Key('tb_user_wallet', $key_data);
				if ($result == 0) {
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
			}
		}
	}


	public function tempScript_Remove()
	{
		$walletList = $this->Crud_Model->Get_All_Table_Data('tb_user_wallet');

		foreach ($walletList as $key => $value) {
			$token = $value['f_token'];
			$key_data = [];
			$key_data['f_token'] = $token;
			$result = $this->Crud_Model->Check_Row_Exist_With_Key('tb_user', $key_data);
			if ($result == 0) {
				$key_data = [];
				$key_data['f_token'] = $token;
				$this->Crud_Model->Delete_A_Row('tb_user_wallet', $key_data);
			}
		}
	}


	public function tempScriptRemove()
	{
		$walletList = $this->Crud_Model->Get_All_Table_Data('tb_user_wallet');

		foreach ($walletList as $key => $value) {
			$token = $value['f_token'];
			$key_data = [];
			$key_data['f_token'] = $token;
			$key_data['f_emai'] = $token;
			$result = $this->Crud_Model->Check_Row_Exist_With_Key('tb_user', $key_data);
			if ($result == 0) {
				$key_data = [];
				$key_data['f_token'] = $token;
				$this->Crud_Model->Delete_A_Row('tb_user_wallet', $key_data);
			}
		}
	}

	public function forg(){

		if($this->is_authenticate()){
			redirect(base_url().'excn/adnc');
		}else{
			$page_data['token'] = '';
			$page_data['page'] = 'forg';
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/forg', $page_data);
			$this->load->view('temp/footer', $page_data);
		}

	}

	public function rest($token = ''){

		if($this->is_authenticate()){
			redirect(base_url().'excn/adnc');
		}else{
			$check_token_exist = $this->Crud_Model->Check_Row_Exist('tb_user', 'f_token', $token);
			if(!$check_token_exist){
				redirect(base_url().'acnt/siin');
			}else{
				$page_data['page'] = 'rest';
				$page_data['token'] = $token;
			
				if($this->session->userdata('coinsky_lang') == null){
					$lang = 'KO';
				}else{
					$lang = $this->session->userdata('coinsky_lang');
				}
				$langData = $this->Crud_Model->Get_Lang_Values();
				$page_data['langData'] = $langData;
				$page_data['lang'] = $lang;
	
				$this->load->view('temp/header',$page_data);
				$this->load->view('acnt/rest', $page_data);
				$this->load->view('temp/footer', $page_data);
			}
		}

	}

	public function changeGoogle2faStatus(){
		
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();

		$token = $this->input->post('token');
		$secret_key = $this->input->post('secret_key');
		$secret_code = $this->input->post('secret_code');
		$status = $this->input->post('status');
		$key_data = [];
		$key_data['f_token'] = $token;
		$user_data = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
		$return_data['res'] = true;
		if($status == 1){
			$return_data['msg'] = $langData['return-msg-msg-1'][$lang];
		}else{
			$return_data['msg'] = $langData['return-msg-msg-2'][$lang];
		}
		include(APPPATH.'../assets/plugin/google2fa/vendor/autoload.php');
	    $google2fa = new Google2FA();
	    $valid = $google2fa->verifyKey($secret_key, $secret_code);
		if($valid) {
			$key_data = [];
			$key_data['f_token'] = $token;
			$update_data = [];
			$update_data['f_google2fa_status'] = $status;
			if($status == 1){
				$update_data['f_otp_verified'] = 1;
				$update_data['f_kyc_level'] = 2;
			}else{
				$update_data['f_otp_verified'] = 0;
				if($user_data['f_phone_owner_verified'] == 0 && $user_data['f_withdraw_KRW_account_verified'] == 0){
					$update_data['f_kyc_level'] = 1;
				}
			}
			$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
			if (!$result){
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
			}else{
				if($status == 0){
					$new_google2fa_key = $google2fa->generateSecretKey();
					$key_data = [];
					$key_data['f_token'] = $token;
					$update_data = [];
					$update_data['f_google2fa_key'] = $new_google2fa_key;
					$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
				}
			}
		}else {
		    $return_data['res'] = false;
			$return_data['msg'] = $langData['return-msg-msg-4'][$lang];
		}
		echo json_encode($return_data);
	}

	public function submitProfile(){
		$token = $this->session->userdata('token');
		$return_data['res'] = true;
		$return_data['msg'] = $langData['return-msg-msg-5'][$lang];
		$username = $this->input->post('username');
		$birthday = $this->input->post('birthday');
		$country = $this->input->post('country');
		$address = $this->input->post('address');
		$phone_number_code = $this->input->post('phone_number_code');
		$phone_number = $this->input->post('phone_number');
		$birthday_temp = explode('/', $birthday);
		$birthday_year = $birthday_temp[2];
		$birthday_month = $birthday_temp[0];
		$birthday_day = $birthday_temp[1];
		$birthday = $birthday_year."-".$birthday_month."-".$birthday_day;
		$key_data = [];
		$key_data['f_token'] = $token;
		$update_data = [];
		$update_data['f_username'] = $username;
		$update_data['f_birthday'] = $birthday;
		$update_data['f_country'] = $country;
		$update_data['f_address'] = $address;
		$update_data['f_phone'] = $phone_number_code.$phone_number;
		$update_data['f_email_verified'] = 1;
		$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
		if(!$result){
			$return_data['res'] = false;
			$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
		}
		echo json_encode($return_data);		
	}

	public function closeUser(){
		$return_data['res'] = true;
		$return_data['msg'] = $langData['return-msg-msg-6'][$lang];
		$token = $this->input->post('token');
		$close_password = $this->input->post('close_password');
		$key_data = [];
		$key_data['f_token'] = $token;
		$user_data = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
		if(password_verify($close_password,$user_data['f_password'])){
			$key_data = [];
			$key_data['f_token'] = $token;
			$update_data = [];
			$update_data['f_is_blocked'] = 1;
			$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
			if($result){
				$unset_userdata = array(
			        'token'  => '',
			        'email'  => '',
			        'username'  => '',
			        'exchange_user_login'  => false
				);
				$this->session->unset_userdata($unset_userdata);
				$this->session->sess_destroy();
			}else{
				$return_data['res'] = false;
				$return_data['msg'] = $langData['return-msg-msg-3'][$lang];
			}	
		}else{
			$return_data['res'] = false;
			$return_data['msg'] = $langData['return-msg-msg-7'][$lang];
		}	
		echo json_encode($return_data);		
	}

	public function siin(){

		if($this->is_authenticate()){
			redirect(base_url().'excn/adnc');
		}else{
			$page_data['token'] = '';
			$page_data['page'] = 'siin';

			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/siin');
			$this->load->view('temp/footer');
		}

	}

	public function siup(){

		if($this->is_authenticate()){
			redirect(base_url().'acnt/excn');
		}else{
			$page_data['token'] = '';
			$page_data['page'] = 'siup';
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;

			$this->load->view('temp/header',$page_data);
			$this->load->view('acnt/siup', $page_data);
			$this->load->view('temp/footer', $page_data);
		}
		
	}

	public function sout(){

		$unset_userdata = array(
	        'token'  => '',
	        'email'  => '',
	        'username'  => '',
	        'exchange_user_login'  => false
		);
		$this->session->unset_userdata($unset_userdata);
		$this->session->sess_destroy();
		redirect(base_url().'acnt/siin');
		
	}

	public function levlChangeAddress(){
		$token = $this->session->userdata('token');
		$address = $this->input->post('address');
		$key_data = [];
		$key_data['f_token'] = $token;
		$update_data = [];
		$update_data['f_address'] = $address;
		$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
		echo $result;
	}

	public function changePhoneNumber(){
		$token = $this->session->userdata('token');
		$phone = $this->input->post('phone');
		$key_data = [];
		$key_data['f_token'] = $token;
		$update_data = [];
		$update_data['f_phone'] = $phone;
		$update_data['f_phone_verified'] = 1;
		$update_data['f_kyc_level'] = 1;
		$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
		echo $result;
	}

	public function tempScriptDeposit(){

		$depositLog = $this->Crud_Model->Get_All_Table_Data('tb_log_user_deposit_withdraw');
		
		foreach($depositLog as $depositValue){
			$key_data = [];
			$key_data['f_token'] = $depositValue['f_token'];
			$key_data['f_unit'] = $depositValue['f_unit'];
			$userWalletData = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);
			$update_data = [];
			$update_data['f_total'] = $userWalletData['f_total'] + $depositValue['f_amount'];
			$update_data['f_available'] = $userWalletData['f_available'] + $depositValue['f_amount'];

			$sql = "SELECT f_close FROM tb_market WHERE f_target='" . $depositValue['f_unit'] . "' && f_base='KRW'";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if (isset($result_data['f_close'])) {
				$currentRate = $result_data['f_close'];
			} else {
				$currentRate = 0;
			}
			$newBaseVolume = $currentRate * $depositValue['f_amount'];

			$update_data['f_buy_volume'] = $userWalletData['f_buy_volume'] + $depositValue['f_amount'];
			$update_data['f_buy_base_volume'] = $userWalletData['f_buy_base_volume'] + $newBaseVolume;
			$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);

			$insert_data = [];
			$insert_data['f_token'] = $depositValue['f_token'];
			$insert_data['f_type'] = 'in';
			$insert_data['f_unit'] = $depositValue['f_unit'];
			$insert_data['f_amount'] = $depositValue['f_amount'];
			$insert_data['f_detail'] = "deposit";
			$insert_data['f_regdate'] = time();
			$result = $this->Crud_Model->Insert_Data('tb_user_wallet_history', $insert_data);
		}
	}

	public function tempScriptCorrectDeposit()
	{
		$key_data = [];
		$key_data['f_unit'] = 'KRW';
		$key_data['f_type'] = 'deposit';
		$key_data['f_status'] = 1;
		$depositLog = $this->Crud_Model->Get_Sub_Data('tb_log_user_deposit_withdraw', $key_data);
		foreach ($depositLog as $depositValue) {

			$key_data = [];
			$key_data['f_token'] = $depositValue['f_token'];
			$key_data['f_unit'] = $depositValue['f_unit'];
			$userWalletData = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);

			$update_data = [];
			$update_data['f_total'] = $userWalletData['f_total'] + $depositValue['f_amount'];
			$update_data['f_available'] = $userWalletData['f_available'] + $depositValue['f_amount'];

			if($depositValue['f_unit'] == 'KRW'){
				$currentRate = 1;
			}else{
				$sql = "SELECT f_close FROM tb_market WHERE f_target='" . $depositValue['f_unit'] . "' && f_base='KRW'";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
				if (isset($result_data['f_close'])) {
					$currentRate = $result_data['f_close'];
				} else {
					$currentRate = 0;
				}
			}
			$newBaseVolume = $currentRate * $depositValue['f_amount'];

			$update_data['f_buy_volume'] = $userWalletData['f_buy_volume'] + $depositValue['f_amount'];
			$update_data['f_buy_base_volume'] = $userWalletData['f_buy_base_volume'] + $newBaseVolume;
			$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);

			$insert_data = [];
			$insert_data['f_token'] = $depositValue['f_token'];
			$insert_data['f_type'] = 'in';
			$insert_data['f_unit'] = $depositValue['f_unit'];
			$insert_data['f_amount'] = $depositValue['f_amount'];
			$insert_data['f_detail'] = "deposit";
			$insert_data['f_regdate'] = time();
			$result = $this->Crud_Model->Insert_Data('tb_user_wallet_history', $insert_data);
		}
	}
}
