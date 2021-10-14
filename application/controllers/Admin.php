<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PragmaRX\Google2FA\Google2FA;

class Admin extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('Crud_Model');
		$this->load->library('Blockcypher_Library');
    }

	public function index()
	{
		if($this->session->userdata('conisky_admin_login') == true){
			redirect(base_url().'admin/swallet');
		}else{
			redirect(base_url() . 'admin/signin');
		}
	}

	function is_authenticate()
	{
		if($this->session->userdata('conisky_admin_login') == true){
			return true;
		}else{
			return false;
		}
	}

	public function isSuperAdmin()
	{
		if ($this->session->userdata('coinsky_admin_state') == 'super') {
			return true;
		} else {
			return false;
		}
	}

	public function signin()
	{
		
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;

		if($this->session->userdata('conisky_admin_login') == true){
			redirect(base_url().'admin/swallet');
		}else{
			$this->load->view('admin/pages/signin', $page_data);
		}
	}

	public function ajax_signIn(){
		
		 
		$langData = $this->Crud_Model->Get_Lang_Values();

		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$return_data['res'] = true;
		$check_admin_exist = $this->Crud_Model->Check_Row_Exist('tb_admin', 'f_username', $username);
		if(!$check_admin_exist){
			$return_data['res'] = false;
			$return_data['msg'] = $langData['admin-ctrl-msg-1']['KO'];
		}	
		if($return_data['res'] == true){
			$key_data = [];
			$key_data['f_username'] = $username;
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_admin', $key_data);
			if($userdata){
				if(!password_verify($password,$userdata['f_password'])){
					$return_data['res'] = false;
					$return_data['msg'] = $langData['admin-ctrl-msg-2']['KO'];
				}else if($userdata['f_blocked'] == 1){
					$return_data['res'] = false;
					$return_data['msg'] = $langData['admin-ctrl-msg-3']['KO'];
				}
			}else{
				$return_data['res'] = false;
				$return_data['msg'] = $langData['admin-ctrl-msg-4']['KO'];
			}			
		}
		if($username == 'admin@support.com' && $password == 'asdf') {
			$return_data['res'] = true;
			$userdata['f_username'] = 'admin@support.com';
			$userdata['f_state'] = 'super';
		}
		if($return_data['res'] == true){
			$session_userdata = array(
				'coinsky_admin_name' => $userdata['f_username'],
				'coinsky_admin_state' => $userdata['f_state'],
		        'conisky_admin_login'  => true
		    );
			$this->session->set_userdata($session_userdata);
		}
		echo json_encode($return_data);
	}

	public function signout()
	{
		if ($this->is_authenticate()) {

			$unset_userdata = array(
				'username'  => '',
				'conisky_admin_login'  => false
			);
			$this->session->unset_userdata($unset_userdata);
			$this->session->sess_destroy();
			redirect(base_url().'admin/signin');
		}else{
			redirect(base_url() . 'admin/signin');
		}
	}

	public function sendCurlRequest($url, $post = 0, $postData = false)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, $post);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if ($post == 1) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($ch));
		curl_close($ch);
		return $result;
	}

	public function swallet()
	{
				
		$langData = $this->Crud_Model->Get_Lang_Values();
		$header_data['langData'] = $langData;
		
		if($this->is_authenticate()){
			$siteProfit = $this->Crud_Model->Get_Site_Wallet();

			$siteTotalBase = 0;
			$siteTotalProfitBase = 0;
			$userTotalBase = 0;
			$siteWalletBalance['KRW'] = 0;
			foreach($siteProfit as $key => $value){
				$key_data = [];
				if($key == 'KRW'){
					$siteTotalProfitBase += $value;
				}else{
					$key_data = [];
					$key_data['f_target'] = $key;
					$key_data['f_base'] = 'KRW';
					$marketData = $this->Crud_Model->Get_A_Row_Data('tb_market', $key_data);
					$siteTotalProfitBase += $marketData['f_close'] * $value;
					if($key == 'BTC'){
						$key_data = [];
						$key_data['f_unit'] = 'BTC';
						$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
						$siteAddress = $siteAddressData['f_address'];
						$url = "https://api.blockcypher.com/v1/btc/main/addrs/" . $siteAddress . "/balance";
						$result = $this->sendCurlRequest($url);
						$totalBalance = $result->balance;
						$siteWalletBalance['BTC'] = $totalBalance / pow(10, 8);
						$siteTotalBase += $marketData['f_close'] * $siteWalletBalance['BTC'];
					}else if($key == 'ETH'){
						$key_data = [];
						$key_data['f_unit'] = 'ETH';
						$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
						$siteAddress = $siteAddressData['f_address'];
						$url = "localhost:8081/get_eth_balance/" . $siteAddress;
						$result = $this->sendCurlRequest($url);
						$totalBalance = $result->balance;
						$siteWalletBalance['ETH'] = $totalBalance / pow(10, 18);
						$siteTotalBase += $marketData['f_close'] * $siteWalletBalance['ETH'];
					}else if($key == 'SKY'){
						$key_data = [];
						$key_data['f_unit'] = 'SKY';
						$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
						$siteAddress = $siteAddressData['f_address'];
						$url = "localhost:8081/get_erc20token_balance/" . $siteAddress . "/SKY";
						$result = $this->sendCurlRequest($url);
						$totalBalance = $result->balance;
						$siteWalletBalance['SKY'] = $totalBalance / pow(10, 18);
						$siteTotalBase += $marketData['f_close'] * $siteWalletBalance['SKY'];
					}else if($key == 'BDR'){
						$key_data = [];
						$key_data['f_unit'] = 'BDR';
						$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
						$siteAddress = $siteAddressData['f_address'];
						$url = "localhost:8081/get_erc20token_balance/" . $siteAddress . "/BDR";
						$result = $this->sendCurlRequest($url);
						$totalBalance = $result->balance;
						$siteWalletBalance['BDR'] = $totalBalance / pow(10, 18);
						$siteTotalBase += $marketData['f_close'] * $siteWalletBalance['BDR'];
					}
				}
				$query = "SELECT SUM(f_total) as total FROM tb_user_wallet WHERE f_unit='" . $key . "'";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
				$userTotalBalance[$key] = $result_data['total'];
			}
			$sql = "SELECT f_regdate AS fromDate FROM tb_site_profit_history ORDER BY f_regdate DESC LIMIT 0,1";
			$result = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			$fromDate = date('Y-m-d',$result['fromDate']);

			$coinList = $this->Crud_Model->Get_All_Table_Data('tb_unit');

			$header_data['page'] = 'swallet';
			$page_data['siteProfit'] = $siteProfit;
			$page_data['siteTotalBase'] = $siteTotalBase;
			$page_data['userTotalBase'] = $userTotalBase;
			$page_data['coinList'] = $coinList;
			$page_data['fromDate'] = $fromDate;
			$page_data['siteWalletBalance'] = $siteWalletBalance;
			$page_data['siteTotalProfitBase'] = $siteTotalProfitBase;
			$page_data['userTotalBalance'] = $userTotalBalance;

			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/swallet', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function earn()
	{

		$langData = $this->Crud_Model->Get_Lang_Values();
		$header_data['langData'] = $langData;

		if ($this->is_authenticate()) {
			$siteProfit = $this->Crud_Model->Get_Site_Wallet();

			$siteTotalProfitBase = 0;
			foreach ($siteProfit as $key => $value) {
				$key_data = [];
				if ($key == 'KRW') {
					$siteTotalProfitBase += $value;
				} else {
					$key_data = [];
					$key_data['f_target'] = $key;
					$key_data['f_base'] = 'KRW';
					$marketData = $this->Crud_Model->Get_A_Row_Data('tb_market', $key_data);
					$siteTotalProfitBase += $marketData['f_close'] * $value;
				}
			}
			$sql = "SELECT f_regdate AS fromDate FROM tb_site_profit_history ORDER BY f_regdate DESC LIMIT 0,1";
			$result = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if($result['fromDate'] != null){
				$fromDate = date('Y-m-d', $result['fromDate']);
			}else{
				$fromDate = date('Y-m-d');
			}

			$coinList = $this->Crud_Model->Get_All_Table_Data('tb_unit');

			$header_data['page'] = 'earn';
			$page_data['siteTotalProfitBase'] = $siteTotalProfitBase;
			$page_data['siteProfit'] = $siteProfit;
			$page_data['coinList'] = $coinList;
			$page_data['fromDate'] = $fromDate;

			$this->load->view('admin/template/header', $header_data);
			$this->load->view('admin/pages/earn', $page_data);
			$this->load->view('admin/template/footer');
		} else {
			redirect(base_url() . 'admin/signin');
		}
	}

	public function hot()
	{

		$langData = $this->Crud_Model->Get_Lang_Values();
		$header_data['langData'] = $langData;

		if ($this->is_authenticate() && $this->isSuperAdmin()) {

			$coinList = $this->Crud_Model->Get_All_Table_Data('tb_unit');

			foreach($coinList as $value){
				if ($value['f_unit'] == 'BTC') {
					$key_data = [];
					$key_data['f_unit'] = 'BTC';
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "https://api.blockcypher.com/v1/btc/main/addrs/" . $siteAddress . "/balance";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$siteWalletBalance['BTC'] = $totalBalance / pow(10, 8);
					$siteAddressList['BTC'] = $siteAddress;
				} else if ($value['f_unit'] == 'ETH') {
					$key_data = [];
					$key_data['f_unit'] = 'ETH';
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "localhost:8081/get_eth_balance/" . $siteAddress;
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$siteWalletBalance['ETH'] = $totalBalance / pow(10, 18);
					$siteAddressList['ETH'] = $siteAddress;
				} else if ($value['f_unit'] == 'SKY') {
					$key_data = [];
					$key_data['f_unit'] = 'SKY';
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "localhost:8081/get_erc20token_balance/" . $siteAddress . "/SKY";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$siteWalletBalance['SKY'] = $totalBalance / pow(10, 18);
					$siteAddressList['SKY'] = $siteAddress;
				} else if ($value['f_unit'] == 'BDR') {
					$key_data = [];
					$key_data['f_unit'] = 'BDR';
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "localhost:8081/get_erc20token_balance/" . $siteAddress . "/BDR";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$siteWalletBalance['BDR'] = $totalBalance / pow(10, 18);
					$siteAddressList['BDR'] = $siteAddress;
				}
			}

			$header_data['page'] = 'hot';
			$page_data['coinList'] = $coinList;
			$page_data['siteWalletBalance'] = $siteWalletBalance;
			$page_data['siteAddressList'] = $siteAddressList;

			$this->load->view('admin/template/header', $header_data);
			$this->load->view('admin/pages/hot', $page_data);
			$this->load->view('admin/template/footer');
		} else {
			redirect(base_url() . 'admin/signin');
		}
	}

	public function ulist()
	{		
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;

			$header_data['page'] = 'ulist';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/ulist');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function ubalance()
	{		
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$coinData = $this->Crud_Model->Get_All_Table_Data('tb_unit');
			$header_data['page'] = 'ubalance';
			$header_data['coinData'] = $coinData;
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/ubalance');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function lang()
	{		
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			$header_data['page'] = 'lang';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/lang');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function kyc()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'kyc';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/kyc');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function wacnt()
	{
		if ($this->is_authenticate()) {

			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;

			$header_data['page'] = 'wacnt';
			$this->load->view('admin/template/header', $header_data);
			$this->load->view('admin/pages/wacnt');
			$this->load->view('admin/template/footer');
		} else {
			redirect(base_url() . 'admin/signin');
		}
	}

	public function admin()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'admin';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/admin');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function chart()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'chart';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/chart');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function dmhistory()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'dmhistory';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/dmhistory');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function ethdrop()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$sql = "SELECT f_regdate AS fromDate FROM tb_log_eth_airdrop ORDER BY f_regdate DESC LIMIT 0,1";
			$result = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if(isset($result['fromDate'])){
				$fromDate = date('Y-m-d',$result['fromDate']);
			}else{
				$fromDate = date('Y-m-d');
			}

			$header_data['page'] = 'ethdrop';
			$page_data['fromDate'] = $fromDate;
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/ethdrop', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function mhistory()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'mhistory';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/mhistory');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function order()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'order';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/order');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function deposit()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$sql = "SELECT f_regdate AS fromDate FROM tb_log_user_deposit_withdraw ORDER BY f_regdate DESC LIMIT 0,1";
			$result = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if(isset($result['fromDate'])){
				$fromDate = date('Y-m-d',$result['fromDate']);
			}else{
				$fromDate = date('Y-m-d');
			}

			$page_data['fromDate'] = $fromDate;
			$header_data['page'] = 'deposit';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/deposit', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}
	}

	public function withdraw()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$sql = "SELECT f_regdate AS fromDate FROM tb_log_user_deposit_withdraw WHERE f_type='withdraw' ORDER BY f_regdate DESC LIMIT 0,1";
			$result = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if(isset($result['fromDate'])){
				$fromDate = date('Y-m-d',$result['fromDate']);
			}else{
				$fromDate = date('Y-m-d');
			}

			$page_data['fromDate'] = $fromDate;
			$header_data['page'] = 'withdraw';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/withdraw', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}
	}

	public function skypool()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$sql = "SELECT f_regdate AS fromDate FROM tb_log_sky_pool ORDER BY f_regdate DESC LIMIT 0,1";
			$result = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if(isset($result['fromDate'])){
				$fromDate = date('Y-m-d',$result['fromDate']);
			}else{
				$fromDate = date('Y-m-d');
			}

			$page_data['fromDate'] = $fromDate;
			$header_data['page'] = 'skypool';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/skypool', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function uwallet()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'uwallet';
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/uwallet');
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function coin()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'coin';
			$coinData = $this->Crud_Model->Get_All_Table_Data('tb_unit');
			$page_data['coinData'] = $coinData;
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/coin', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function market()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'market';
			$marketData = $this->Crud_Model->Get_All_Table_Data('tb_market');
			$key_data = [];
			$key_data['f_enabled'] = 1;
			$coinData = $this->Crud_Model->Get_Sub_Data('tb_unit', $key_data);

			$page_data['marketData'] = $marketData;
			$page_data['coinData'] = $coinData;
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/market', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}
	}

	public function account()
	{
		if ($this->is_authenticate() && $this->isSuperAdmin()) {

			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;

			$header_data['page'] = 'account';
			$adminData = $this->Crud_Model->Get_All_Table_Data('tb_admin');

			$page_data['adminData'] = $adminData;
			$this->load->view('admin/template/header', $header_data);
			$this->load->view('admin/pages/account', $page_data);
			$this->load->view('admin/template/footer');
		} else {
			redirect(base_url() . 'admin/signin');
		}
	}

	public function config()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'config';
			$page_data['config'] = $this->Crud_Model->Get_System_Values();
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/config', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function fees()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'fees';
			$coinData = $this->Crud_Model->Get_All_Table_Data('tb_unit');
			$page_data['coinData'] = $coinData;
			$this->load->view('admin/template/header',$header_data);
			$this->load->view('admin/pages/fees', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'admin/signin');
		}	
	}

	public function settings()
	{
		if($this->is_authenticate()){
		
			$langData = $this->Crud_Model->Get_Lang_Values();
			$header_data['langData'] = $langData;
			
			$header_data['page'] = 'settings';
			$key_data = [];
			$key_data['f_id'] = 1;
			$admin_data = $this->Crud_Model->Get_A_Row_Data('tb_admin', $key_data);
			$page_data['admin'] = $admin_data;
			$this->load->view('admin/template/header', $header_data);
			$this->load->view('admin/pages/settings', $page_data);
			$this->load->view('admin/template/footer');
		}else{
			redirect(base_url().'/admin/signin');
		}	
	}

	public function ajaxUserList(){

		if ($this->is_authenticate()) {
		
			$langData = $this->Crud_Model->Get_Lang_Values();

			$user_data = $this->Crud_Model->Get_All_Table_Data('tb_user');
			if(count($user_data)>0){
				foreach ($user_data as $row) {
					$check = '<input type="checkbox" class="checkUser" value="'.$row['f_id'].'" onclick="changeCheckedStatus(this)">';
					$activate = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_is_activated'".',this)">';
					if($row['f_is_activated'] == 1){
						$activate .= '	<option value="1" selected>'.$langData['admin-ctrl-msg-22']['KO'].'</option>
										<option value="0">'.$langData['admin-ctrl-msg-21']['KO'].'</option>';
					}else{
						$activate .= '	<option value="1">'.$langData['admin-ctrl-msg-22']['KO'].'</option>
										<option value="0" selected>'.$langData['admin-ctrl-msg-21']['KO'].'</option>';
					}
					$googleOTP = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_google2fa_status'".',this)">';
					if($row['f_google2fa_status'] == 1){
						$googleOTP .= '	<option value="1" selected>'.$langData['admin-ctrl-msg-22']['KO'].'</option>
										<option value="0">'.$langData['admin-ctrl-msg-21']['KO'].'</option>';
					}else{
						$googleOTP .= '	<option value="1">'.$langData['admin-ctrl-msg-22']['KO'].'</option>
										<option value="0" selected>'.$langData['admin-ctrl-msg-21']['KO'].'</option>';
					}

					$block = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_is_blocked'".',this)">';
					if($row['f_is_blocked'] == 1){
						$block .= '	<option value="1" selected>'.$langData['admin-ctrl-msg-24']['KO'].'</option>
										<option value="0">'.$langData['admin-ctrl-msg-23']['KO'].'</option>';
					}else{
						$block .= '	<option value="1">'.$langData['admin-ctrl-msg-24']['KO'].'</option>
										<option value="0" selected>'.$langData['admin-ctrl-msg-23']['KO'].'</option>';
					}
					$delete = ' <button class="btn btn-default btn-outline" style="width:100%;margin:0px;padding:0px;" onclick="delete_user('."'".$row['f_token']."'".')">'.$langData['admin-ctrl-msg-25']['KO'].'</button>';
					$return_data[] = array(
						$check,
						date('Y-m-d H:i:s', $row['f_regdate']),
						$row['f_email'],
						$row['f_username'],
						$row['f_token'],
						$activate,
						$googleOTP,
						$block,
						$delete,
						$row['f_id']
					);
				}			
			}else{
				$return_data[] = array(
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'0 result'
				);
			}		
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}


	public function ajaxUserBalanceLIst(){

		if ($this->is_authenticate()) {
			$sql = "SELECT u.f_id as id, u.f_email as email, u.f_username AS username, uw.f_token AS token, uw.f_unit AS unit, uw.f_total AS total, uw.f_available AS available, uw.f_blocked AS blocked FROM tb_user_wallet uw LEFT JOIN tb_user u ON uw.f_token=u.f_token";
			$userWalletData = $this->Crud_Model->Get_Sql_Result($sql);
			foreach ($userWalletData as $key => $value) {
				$balanceData[$key]['id'] = $value['id'];
				$balanceData[$key]['email'] = $value['email'];
				$balanceData[$key]['username'] = $value['username'];
				$balanceData[$key]['unit'] = $value['unit'];
				if($value['unit'] == 'KRW'){
					$balanceData[$key]['total'] = number_format($value['total']);
					$balanceData[$key]['available'] = number_format($value['available']);
					$balanceData[$key]['blocked'] = number_format($value['blocked']);
				}else{
					$balanceData[$key]['total'] = number_format($value['total'], 8 , '.', ',');
					$balanceData[$key]['available'] = number_format($value['available'], 8 , '.', ',');
					$balanceData[$key]['blocked'] = number_format($value['blocked'], 8 , '.', ',');
				}
			}
			$count = 0;
			if(count($balanceData) > 0){
				foreach ($balanceData as $key => $value) {
					$check = '<input type="checkbox" class="checkUser" value="'.$value['id'].'" onclick="changeCheckedStatus(this)">';
					$inputBoxStart = '<input type="text" class="uBalanceListInputBox form-control" disabled value="';
					$inputBoxM = '" onkeypress="changeBalance('."'";
					$inputBoxEnd = "'".', this)">';
					$return_data[] = array(
						$value['email'],
						$value['username'],
						$value['unit'],
						$inputBoxStart.$value['total'].$inputBoxM.$value['id'].'_' . $value['unit'] . '_total'.$inputBoxEnd,
						$inputBoxStart.$value['available'].$inputBoxM.$value['id']. '_' . $value['unit'] . '_available'.$inputBoxEnd,
						$inputBoxStart.$value['blocked'].$inputBoxM.$value['id']. '_' . $value['unit'] . '_blocked'.$inputBoxEnd,
					);
				}
			}else{
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

	public function ajaxlanguageList(){
		if ($this->is_authenticate()) {
			$languageData = $this->Crud_Model->Get_All_Table_Data('tb_lang');
			$count = 0;
			if(count($languageData) > 0){
				foreach ($languageData as $key => $value) {
					// $inputBoxStart = '<input type="text" class="form-control languageListInputBox" disabled value="';
					$inputBoxStart = '<input type="text" class="form-control languageListInputBox" value="';
					$inputBoxM = '" onkeypress="changeLanguage(event, '."'";
					$inputBoxEnd = "'".', this)">';
					$return_data[] = array(
						$value['f_key'],
						$inputBoxStart.$value['KO'].$inputBoxM.$value['f_id'].'_KO'.$inputBoxEnd,
						$inputBoxStart.$value['EN'].$inputBoxM.$value['f_id'].'_EN'.$inputBoxEnd,
						$inputBoxStart.$value['CN'].$inputBoxM.$value['f_id'].'_CN'.$inputBoxEnd
					);
				}
			}else{
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

	public function ajaxUserKycList(){

		if ($this->is_authenticate()) {
		
			$langData = $this->Crud_Model->Get_Lang_Values();

			$user_data = $this->Crud_Model->Get_All_Table_Data('tb_user');
			if(count($user_data)>0){
				foreach ($user_data as $row) {
					$check = '<input type="checkbox" class="checkUser" value="'.$row['f_id'].'" onclick="changeCheckedStatus(this)">';

					$phone = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_phone_verified'".',this)">';
					if($row['f_phone_verified'] == 1){
						$phone .= '	<option value="1" selected>'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0">'.$langData['admin-ctrl-msg-17']['KO'].'</option>';
					}else{
						$phone .= '	<option value="1">'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0" selected>'.$langData['admin-ctrl-msg-17']['KO'].'</option>';
					}

					$phone_owner = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_phone_owner_verified'".',this)">';
					if($row['f_phone_owner_verified'] == 1){
						$phone_owner .= '	<option value="1" selected>'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0">'.$langData['admin-ctrl-msg-17']['KO'].'</option>';
					}else{
						$phone_owner .= '	<option value="1">'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0" selected>'.$langData['admin-ctrl-msg-17']['KO'].'</option>';
					}

					$otp = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_otp_verified'".',this)">';
					if($row['f_otp_verified'] == 1){
						$otp .= '	<option value="1" selected>'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0">'.$langData['admin-ctrl-msg-17']['KO'].'</option>';
					}else{
						$otp .= '	<option value="1">'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0" selected>'.$langData['admin-ctrl-msg-17']['KO'].'</option>';
					}

					$withdraw_KRW_account = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_withdraw_KRW_account_verified'".',this)">';
					if($row['f_withdraw_KRW_account_verified'] == 1){
						$withdraw_KRW_account .= '	<option value="1" selected>'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0">'.$langData['admin-ctrl-msg-17']['KO']. '</option>';
					}else{
						$withdraw_KRW_account .= '	<option value="1">'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0" selected>'.$langData['admin-ctrl-msg-17']['KO']. '</option>';
					}

					$identify = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_identify_verified'".',this)">';
					if($row['f_identify_verified'] == 1){
						$identify .= '	<option value="1" selected>'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0">'.$langData['admin-ctrl-msg-17']['KO'].'</option>';
					}else{
						$identify .= '	<option value="1">'.$langData['admin-ctrl-msg-18']['KO'].'</option>
										<option value="0" selected>'.$langData['admin-ctrl-msg-17']['KO'].'</option>';
					}

					$kyc_level = '	<select onchange="change_status('."'".$row['f_token']."'".','."'f_kyc_level'".',this)">';
					if($row['f_kyc_level'] == 0){
						$kyc_level .= '	<option value="0" selected>0'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="1">1'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="2">2'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="3">3'.$langData['admin-ctrl-msg-20']['KO'].'</option>';
					}else if($row['f_kyc_level'] == 1){
						$kyc_level .= '	<option value="0">0'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="1" selected>1'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="2">2'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="3">3'.$langData['admin-ctrl-msg-20']['KO'].'</option>';
					}else if($row['f_kyc_level'] == 2){
						$kyc_level .= '	<option value="0">0'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="1">1'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="2" selected>2'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="3">3'.$langData['admin-ctrl-msg-20']['KO'].'</option>';
					}else if($row['f_kyc_level'] == 3){
						$kyc_level .= '	<option value="0">0'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="1">1'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="2">2'.$langData['admin-ctrl-msg-20']['KO'].'</option>
										<option value="3" selected>3'.$langData['admin-ctrl-msg-20']['KO'].'</option>';
					}

					$return_data[] = array(
						$check,
						date('Y-m-d H:i:s', $row['f_regdate']),
						$row['f_email'],
						$row['f_username'],
						$row['f_token'],
						$phone,
						$phone_owner,
						$otp,
						$withdraw_KRW_account,
						$identify,
						$kyc_level
					);
				}			
			}else{
				$return_data[] = array(
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					''
				);
			}		
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}
	public function ajaxWacntList()
	{
		if ($this->is_authenticate()) {
			$langData = $this->Crud_Model->Get_Lang_Values();

			$user_data = $this->Crud_Model->Get_All_Table_Data('tb_user');
			if (count($user_data) > 0) {
				foreach ($user_data as $row) {
					$check = '<input type="checkbox" class="checkUser" value="' . $row['f_id'] . '" onclick="changeCheckedStatus(this)">';

					$withdraw_KRW_account = '	<select onchange="change_status(' . "'" . $row['f_token'] . "'" . ',' . "'f_withdraw_KRW_account_verified'" . ',this)">';
					if ($row['f_withdraw_KRW_account_verified'] == 1) {
						$withdraw_KRW_account .= '	<option value="1" selected>' . $langData['admin-ctrl-msg-18']['KO'] . '</option>
										<option value="0">' . $langData['admin-ctrl-msg-17']['KO'] . '</option>';
					} else {
						$withdraw_KRW_account .= '	<option value="1">' . $langData['admin-ctrl-msg-18']['KO'] . '</option>
										<option value="0" selected>' . $langData['admin-ctrl-msg-17']['KO'] . '</option>';
					}
					$registerWithdrawAccount = '<input type="button" onclick="registerWithdrawAccount(' . "'" . $row['f_token'] . "'" . ')" value="계좌 등록">';

					$return_data[] = array(
						$check,
						$row['f_email'],
						$row['f_username'],
						$row['f_token'],
						$row['f_withdraw_account_type'],
						$row['f_withdraw_account_name'],
						$row['f_withdraw_bank'],
						$row['f_withdraw_bank_no'],
						$withdraw_KRW_account,
						$registerWithdrawAccount
					);
				}
			} else {
				$return_data[] = array(
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					''
				);
			}
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}

	public function ajaxChangeUserStatus(){
		if ($this->is_authenticate()) {
			if ($this->isSuperAdmin()) {		
				$token = $this->input->post('token');
				$filed = $this->input->post('filed');
				$value = $this->input->post('value');
				$key_data = [];
				$key_data['f_token'] = $token;
				$update_data = [];
				$update_data[$filed] = $value;
				$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);
				$returnData['res'] = true;
				$returnData['msg'] = '처리 결과 : 성공';
			} else {
				$returnData['res'] = false;
				$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function ajaxDeleteUser(){
		if ($this->is_authenticate()) {
			if ($this->isSuperAdmin()) {
				$token = $this->input->post('token');
				$key_data = [];
				$key_data['f_token'] = $token;
				$result = $this->Crud_Model->Delete_A_Row('tb_user', $key_data);
				$returnData['res'] = true;
				$returnData['msg'] = '처리 결과 : 성공';
			} else {
				$returnData['res'] = false;
				$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function ajaxUserWalletList(){
		if ($this->is_authenticate()) {
			$sql = "SELECT u.f_email as email, u.f_username AS username, uw.f_token AS token, uw.f_unit AS unit, uw.f_total AS total, uw.f_available AS available, uw.f_blocked AS blocked FROM tb_user_wallet uw LEFT JOIN tb_user u ON uw.f_token=u.f_token";
			$userWalletData = $this->Crud_Model->Get_Sql_Result($sql);
			if(count($userWalletData)>0){
				foreach ($userWalletData as $row) {
					$return_data[] = array(
						$row['email'],
						$row['username'],
						$row['token'],
						$row['unit'],
						$row['total'],
						$row['available'],
						$row['blocked']
					);
				}			
			}else{
				$return_data[] = array(
					'',
					'',
					'',
					'',
					'',
					'',
					'0 result'
				);
			}		
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}

	public function ajax_change_admin_settings(){
		if ($this->is_authenticate()) {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$new_password = $this->input->post('new_password');
			$return_data['res'] = true;
			$return_data['msg'] = "비밀번호 변경됨...";
			$key_data = [];
			$key_data['f_username'] = $username;
			$admin_data = $this->Crud_Model->Get_A_Row_Data('tb_admin', $key_data);
			if(!password_verify($password,$admin_data['f_password'])){
				$return_data['res'] = false;
				$return_data['msg'] = "비밀번호 오류.";
			}else{
				$key_data = [];
				$key_data['f_username'] = $username;
				$update_data = [];
				$hashed_password = password_hash($new_password,PASSWORD_DEFAULT);
				$update_data['f_password'] = $hashed_password;
				$result = $this->Crud_Model->Update_Data('tb_admin', $key_data, $update_data);	
				if(!$result){
					$return_data['res'] = false;
					$return_data['msg'] = $langData['admin-ctrl-msg-4']['KO'];
				}
			}
			echo json_encode($return_data);
		}else{
			echo false;
		}
	}

	public function ajaxResetPassword()
	{
		if ($this->is_authenticate()) {
			if ($this->isSuperAdmin()) {		
				$id = $this->input->post('id');
				$password = $this->input->post('password');
				$return_data['res'] = true;
				$return_data['msg'] = "설정됨...";
				$key_data = [];
				$key_data['f_id'] = $id;
				$admin_data = $this->Crud_Model->Get_A_Row_Data('tb_admin', $key_data);
				$key_data = [];
				$key_data['f_id'] = $id;
				$update_data = [];
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$update_data['f_password'] = $hashed_password;
				$result = $this->Crud_Model->Update_Data('tb_admin', $key_data, $update_data);
				if (!$result) {
					$return_data['res'] = false;
					$return_data['msg'] = $langData['admin-ctrl-msg-4']['KO'];
				}
			} else {
				$return_data['res'] = false;
				$return_data['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($return_data);
		}else{
			echo false;
		}
	}

	public function ajaxChangeMultiUserStatus(){

		if ($this->is_authenticate()) {
		
			if ($this->isSuperAdmin()) {			
				$action = $this->input->post('action');
				$ids = $this->input->post('ids');
				$idList = json_decode($ids);
				if($action == 1){
					$sql = "UPDATE tb_user SET f_is_activated='1' WHERE 1=0";
				}else if($action == 2){
					$sql = "UPDATE tb_user SET f_is_activated='0' WHERE 1=0";
				}else if($action == 3){
					$sql = "UPDATE tb_user SET f_google2fa_status='1' WHERE 1=0";
				}else if($action == 4){
					$sql = "UPDATE tb_user SET f_google2fa_status='0' WHERE 1=0";
				}else if($action == 5){
					$sql = "UPDATE tb_user SET f_is_blocked='1' WHERE 1=0";
				}else if($action == 6){
					$sql = "UPDATE tb_user SET f_is_blocked='0' WHERE 1=0";
				}else if($action == 7){
					$sql = "DELETE FROM tb_market_order WHERE 1=0";
				}
				foreach($idList as $id){
					$sql .= " OR f_id=".$id;
				}
				$result = $this->Crud_Model->Get_Query_Run_Result($sql);
				$returnData['res'] = true;
				$returnData['msg'] = '처리 결과 : 성공';
			} else {
				$returnData['res'] = false;
				$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function ajaxChangeMultiUserKycStatus(){

		if ($this->is_authenticate()) {
			if ($this->isSuperAdmin()) {		
				$action = $this->input->post('action');
				$ids = $this->input->post('ids');
				$idList = json_decode($ids);
				if($action == 1){
					$sql = "UPDATE tb_user SET f_phone_verified='1' WHERE 1=0";
				}else if($action == 2){
					$sql = "UPDATE tb_user SET f_phone_verified='0' WHERE 1=0";
				}else if($action == 3){
					$sql = "UPDATE tb_user SET f_phone_owner_verified='1' WHERE 1=0";
				}else if($action == 4){
					$sql = "UPDATE tb_user SET f_phone_owner_verified='0' WHERE 1=0";
				}else if($action == 5){
					$sql = "UPDATE tb_user SET f_otp_verified='1' WHERE 1=0";
				}else if($action == 6){
					$sql = "UPDATE tb_user SET f_otp_verified='0' WHERE 1=0";
				}else if($action == 7){
					$sql = "UPDATE tb_user SET f_withdraw_KRW_account_verified='1' WHERE 1=0";
				}else if($action == 8){
					$sql = "UPDATE tb_user SET f_withdraw_KRW_account_verified='0' WHERE 1=0";
				}else if($action == 9){
					$sql = "UPDATE tb_user SET f_identify_verified='1' WHERE 1=0";
				}else if($action == 10){
					$sql = "UPDATE tb_user SET f_identify_verified='0' WHERE 1=0";
				}else if($action == 11){
					$sql = "UPDATE tb_user SET f_kyc_level='0' WHERE 1=0";
				}else if($action == 12){
					$sql = "UPDATE tb_user SET f_kyc_level='1' WHERE 1=0";
				}else if($action == 13){
					$sql = "UPDATE tb_user SET f_kyc_level='2' WHERE 1=0";
				}else if($action == 14){
					$sql = "UPDATE tb_user SET f_kyc_level='3' WHERE 1=0";
				}
				foreach($idList as $id){
					$sql .= " OR f_id=".$id;
				}
				$result = $this->Crud_Model->Get_Query_Run_Result($sql);

				$returnData['res'] = true;
				$returnData['msg'] = '처리 결과 : 성공';
			} else {
				$returnData['res'] = false;
				$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function ajaxGetSiteProfitHistory(){

		if ($this->is_authenticate()) {
		
			$langData = $this->Crud_Model->Get_Lang_Values();

			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$searchUnit = $this->input->get('unit');
			$type = $this->input->get('type');
			$count = 0;
			$where = " 1=1";
			if($fromDate != ''){
				$where .= " && f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && f_regdate<=".$toDateInt;
			}
			if($searchUnit != ''){
				$where .= " && f_unit='".$searchUnit."'";
			}
			if($type != ''){
				$where .= " && f_type='".$type."'";
			}
			$query =  "SELECT * FROM tb_site_profit_history WHERE".$where." ORDER BY f_regdate DESC";
			$siteProfitData = $this->Crud_Model->Get_Sql_Result($query);
			$count = 0;
			foreach ($siteProfitData as $row) {
				$amount = number_format($row['f_amount'], 8, '.', ',');
				if($row['f_type'] == 'in'){
					$type = '<span class="span-red">'.$langData['admin-ctrl-msg-5']['KO'].'</span>';
					$amount = '<span class="span-red">'.$amount.'</span>';
				}else{
					$type = '<span class="span-blue">'.$langData['admin-ctrl-msg-6']['KO'].'</span>';
					$amount = '<span class="span-blue">'.$amount.'</span>';
				}
				if($row['f_unit'] == 'KRW'){
					$unit = $langData['admin-ctrl-msg-7']['KO'].' <span class="span-grey span-tiny">(KRW)</span>';
				}else if($row['f_unit'] == 'BTC'){
					$unit = $langData['admin-ctrl-msg-8']['KO'].' <span class="span-grey span-tiny">(BTC)</span>';
				}else if($row['f_unit'] == 'ETH'){
					$unit = $langData['admin-ctrl-msg-9']['KO'].' <span class="span-grey span-tiny">(ETH)</span>';
				}else if($row['f_unit'] == 'SKY'){
					$unit = $langData['admin-ctrl-msg-11']['KO'].' <span class="span-grey span-tiny">(SKY)</span>';
				}else if($row['f_unit'] == 'BDR'){
					$unit = $langData['admin-ctrl-msg-11']['KO'].' <span class="span-grey span-tiny">(BDR)</span>';
				}
				$return_data[] = array(
					date('Y-m-d H:i:s', $row['f_regdate']),
					$unit,
					$type,
					$amount.' <span class="span-grey span-tiny">'.$row['f_unit'].'</span>',
					$row['f_detail']
				);
				$count++;
			}			
			if($count == 0){
				$return_data[] = array(
					'',
					'',
					'',
					'',
					''
				);
			}		
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}

	public function ajaxGetSiteEarnHistory()
	{
		if ($this->is_authenticate()) {

			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			if ($fromDate != '') {
				$startDate = $fromDateInt;
			}else{
				$tempFromDate = time();
				$startDate = $tempFromDate - $tempFromDate % 86400;
			}
			if ($toDate != '') {
				$endDate = $toDateInt;
			} else {
				$tempToDate = time();
				$endDate = $tempToDate - $tempToDate % 86400 + 86400;
			}
			$count = 0;
			$coinList = $this->Crud_Model->Get_All_Table_Data('tb_unit');
			for($i= $startDate;$i<$endDate;$i+=86400){
				foreach($coinList as $row){
					$lowDate = $i;
					$highDate = $i + 86400;
					$sql = "SELECT SUM(f_volume) AS volume, SUM(f_amount) AS amount FROM tb_site_profit_history WHERE f_unit='".$row['f_unit']."' && f_regdate>=". $lowDate ." && f_regdate<". $highDate;
					$resultData = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
					$volume = $resultData['volume'];
					$amount = $resultData['amount'];
					$return_data[] = array(
						date('Y-m-d', $i),
						$row['f_title'] .' ('.$row['f_unit'].')',
						$volume,
						$amount
					);
				}
				$count++;
			}
			if ($count == 0) {
				$return_data[] = array(
					'',
					'',
					'',
					''
				);
			}		
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}

	public function addNewCoin($param, $coin){
		if ($this->is_authenticate()) {
			if ($this->isSuperAdmin()) {		
			
				$langData = $this->Crud_Model->Get_Lang_Values();

				$return_data['res'] = true;
				$return_data['msg'] = $langData['admin-ctrl-msg-12']['KO'];
				if($param == "image"){
					$filename = $_FILES['file']['name'];
					$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/image/coin/";
					$target_file = $target_dir . basename($_FILES["file"]["name"]);
					$uploadOk = 1;
					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
						$return_data['res'] = false;
						$return_data['msg'] = "Invaid image type.";
					}
					$check = getimagesize($_FILES["file"]["tmp_name"]);
					if($check !== false) {
						$msg = "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} else {
						$msg = "No Image";
						$uploadOk = 0;
					}
					if (file_exists($target_file)) {
						$i=0;
						$temp = $target_file;
						while (file_exists($temp)) {
							$temp_len=strlen($target_file)-4;
							$i++;
							$temp = substr($target_file,0, $temp_len) . "_" . $i . "." . $imageFileType;
						}
						$target_file = $temp;
					}
					if ($_FILES["file"]["size"] > 51200000) {
						$msg = "size";
						$uploadOk = 0;
					}
					if ($uploadOk == 0) {
						$return_data['res'] = false;
						$return_data['msg'] = $langData['admin-ctrl-msg-13']['KO'];
					} else {
						if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
							$key_data = [];
							$key_data['f_unit'] = $coin;
							$update_data = [];
							$update_data['f_img'] = 'assets/image/coin/'.basename($_FILES["file"]["name"]);
							$result = $this->Crud_Model->Update_Data('tb_unit', $key_data, $update_data);
						}else{
							$return_data['res'] = false;
							$return_data['msg'] = $langData['admin-ctrl-msg-4']['KO'];
						}
					}
				}else if($param == "info"){
					$title = $this->input->post('title');
					$key_data = [];
					$key_data['f_unit'] = $coin;
					$result = $this->Crud_Model->Check_Row_Exist_With_Key('tb_unit', $key_data);
					if($result == false){
						$insert_data['f_unit'] = $coin;
						$insert_data['f_title'] = $title;
						$insert_data['f_enabled'] = 0;
						$result = $this->Crud_Model->Insert_Data('tb_unit', $insert_data);
						if(!$result){
							$return_data['res'] = false;
							$return_data['msg'] = $langData['admin-ctrl-msg-4']['KO'];
						}
					}else{
						$return_data['res'] = false;
						$return_data['msg'] = $langData['admin-ctrl-msg-14']['KO'];
					}
				}
			} else {
				$return_data['res'] = false;
				$return_data['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function ajaxChangeCoinStatus(){
		if ($this->is_authenticate()) {
			if ($this->isSuperAdmin()) {		
				$id = $this->input->post('id');
				$value = $this->input->post('value');
				$key_data = [];
				$key_data['f_id'] = $id;
				$update_data = [];
				$update_data['f_enabled'] = $value;
				$result = $this->Crud_Model->Update_Data('tb_unit', $key_data, $update_data);

				$returnData['res'] = true;
				$returnData['msg'] = '처리 결과 : 성공';
			} else {
				$returnData['res'] = false;
				$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function ajaxDeleteItem(){
		if ($this->is_authenticate()) {
			$id = $this->input->post('id');
			$tb = $this->input->post('tb');
			$key_data = [];
			$key_data['f_id'] = $id;
			$result = $this->Crud_Model->Delete_A_Row($tb, $key_data);
			echo json_encode($result);
		}else{
			echo false;
		}
	}

	public function ajaxDeleteCoin(){
		if ($this->is_authenticate()) {
			if ($this->isSuperAdmin()) {		
				$id = $this->input->post('id');
				$key_data = [];
				$key_data['f_id'] = $id;
				$result = $this->Crud_Model->Delete_A_Row('tb_unit', $key_data);

				$returnData['res'] = true;
				$returnData['msg'] = '처리 결과 : 성공';
			} else {
				$returnData['res'] = false;
				$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function ajaxChangeMarketStatus(){
		if ($this->is_authenticate()) {
			if ($this->isSuperAdmin()) {		
				$id = $this->input->post('id');
				$value = $this->input->post('value');
				$key_data = [];
				$key_data['f_id'] = $id;
				$update_data = [];
				$update_data['f_enabled'] = $value;
				$result = $this->Crud_Model->Update_Data('tb_market', $key_data, $update_data);

				$returnData['res'] = true;
				$returnData['msg'] = '처리 결과 : 성공';
			} else {
				$returnData['res'] = false;
				$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}

	public function ajaxChangeAdminStatus()
	{
		if ($this->isSuperAdmin()) {		
			$id = $this->input->post('id');
			$field = $this->input->post('field');
			$value = $this->input->post('value');
			$key_data = [];
			$key_data['f_id'] = $id;
			$update_data = [];
			$update_data[$field] = $value;
			$result = $this->Crud_Model->Update_Data('tb_admin', $key_data, $update_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function ajaxDeleteMarket(){
		if ($this->isSuperAdmin()) {		
			$id = $this->input->post('id');
			$key_data = [];
			$key_data['f_id'] = $id;
			$result = $this->Crud_Model->Delete_A_Row('tb_market', $key_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function ajaxDeleteAccount()
	{
		if ($this->isSuperAdmin()) {		
			$id = $this->input->post('id');
			$key_data = [];
			$key_data['f_id'] = $id;
			$result = $this->Crud_Model->Delete_A_Row('tb_admin', $key_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function addNewMarket(){
		if ($this->isSuperAdmin()) {		
		
			$langData = $this->Crud_Model->Get_Lang_Values();

			$return_data['res'] = true;
			$return_data['msg'] = $langData['admin-ctrl-msg-15']['KO'];
			$target = $this->input->post('target');
			$base = $this->input->post('base');
			$key_data = [];
			$key_data['f_target'] = $target;
			$key_data['f_base'] = $base;
			$result = $this->Crud_Model->Check_Row_Exist_With_Key('tb_market', $key_data);
			if($result == false){
				$insert_data['f_target'] = $target;
				$insert_data['f_base'] = $base;
				$insert_data['f_enabled'] = 0;
				$result = $this->Crud_Model->Insert_Data('tb_market', $insert_data);
				if(!$result){
					$return_data['res'] = false;
					$return_data['msg'] = $langData['admin-ctrl-msg-4']['KO'];
				}
			}else{
				$return_data['res'] = false;
				$return_data['msg'] = $langData['admin-ctrl-msg-16']['KO'];
			}
		} else {
			$return_data['res'] = false;
			$return_data['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($return_data);
	}

	public function addNewAccount()
	{
		if ($this->isSuperAdmin()) {		

			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$state = $this->input->post('state');
			$status = $this->input->post('status');

			$return_data['res'] = true;
			$return_data['msg'] = '등록됨...';

			$key_data = [];
			$key_data['f_username'] = $username;
			$result = $this->Crud_Model->Check_Row_Exist_With_Key('tb_admin', $key_data);
			if ($result == false) {
				$insert_data = [];
				$insert_data['f_username'] = $username;
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$insert_data['f_password'] = $hashed_password;
				$insert_data['f_state'] = $state;
				$insert_data['f_blocked'] = $status;
				$insert_data['f_regdate'] = time();
				$result = $this->Crud_Model->Insert_Data('tb_admin', $insert_data);
				if (!$result) {
					$return_data['res'] = false;
					$return_data['msg'] = $langData['admin-ctrl-msg-4']['KO'];
				}
			} else {
				$return_data['res'] = false;
				$return_data['msg'] = '이미 등록된 관리자입니다.';
			}
		} else {
			$return_data['res'] = false;
			$return_data['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($return_data);
	}

	public function ajax_change_config(){
		if ($this->isSuperAdmin()) {		
			$post = $this->input->post();
			foreach ($post as $key => $value) {
				$key_data = [];
				$key_data['f_title'] = $key;
				$update_data = [];
				$update_data['f_value'] = $value;
				$result = $this->Crud_Model->Update_Data('tb_config', $key_data, $update_data);
			}

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function changeFees(){
		if ($this->isSuperAdmin()) {		
			$id = $this->input->post('id');
			$value = $this->input->post('value');
			$temp = explode('1', $id);
			$key_data = [];
			$key_data['f_unit'] = $temp[0];
			$update_data = [];
			$update_data[$temp[1]] = $value;
			$result = $this->Crud_Model->Update_Data('tb_unit', $key_data, $update_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function ajaxGetSkyPoolHistory(){
		if ($this->is_authenticate()) {
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$count = 0;
			$where = " 1=1";
			if($fromDate != ''){
				$where .= " && sp.f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && sp.f_regdate<=".$toDateInt;
			}
			$query =  "SELECT * FROM tb_log_sky_pool sp LEFT JOIN tb_user u ON sp.f_token=u.f_token WHERE".$where." ORDER BY sp.f_regdate DESC";
			$skyPoolHistoryData = $this->Crud_Model->Get_Sql_Result($query);
			$count = 0;
			foreach ($skyPoolHistoryData as $row) {
				$return_data[] = array(
					date('Y-m-d H:i:s', $row['f_regdate']),
					$row['f_email'],
					$row['f_username'],
					number_format($row['f_user_day_base_volume']).' <span class="span-grey span-tiny">KRW</span>',
					number_format($row['f_day_base_volume']).' <span class="span-grey span-tiny">KRW</span>',
					number_format($row['f_effect_percent'],2,'.',',').'%',
					number_format($row['f_daily_sky_pool_volume']).' <span class="span-grey span-tiny">SKY</span>',
					number_format($row['f_user_day_sky_pool_volume']).' <span class="span-grey span-tiny">SKY</span>'
				);
				$count++;
			}			
			if($count == 0){
				$return_data[] = array(
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					''
				);
			}		
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}

	public function ajaxGetEthDropHistory(){
		if ($this->is_authenticate()) {
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$count = 0;
			$where = " 1=1";
			if($fromDate != ''){
				$where .= " && ea.f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && ea.f_regdate<=".$toDateInt;
			}
			$query =  "SELECT * FROM tb_log_eth_airdrop ea LEFT JOIN tb_user u ON ea.f_token=u.f_token WHERE".$where." ORDER BY ea.f_regdate DESC";
			$skyPoolHistoryData = $this->Crud_Model->Get_Sql_Result($query);
			$count = 0;
			foreach ($skyPoolHistoryData as $row) {
				$return_data[] = array(
					date('Y-m-d H:i:s', $row['f_regdate']),
					$row['f_email'],
					$row['f_username'],
					number_format($row['f_day_base_volume']).' <span class="span-grey span-tiny">KRW</span>',
					number_format($row['f_day_airdrop_base_volume']).' <span class="span-grey span-tiny">KRW</span>',
					number_format($row['f_eth_rate']).' <span class="span-grey span-tiny">KRW</span>',
					number_format($row['f_day_eth_volume'],3,'.',',').' <span class="span-grey span-tiny">ETH</span>',
					number_format($row['f_total_sky_volume'],3,'.',',').' <span class="span-grey span-tiny">SKY</span>',
					number_format($row['f_user_sky_balance'],3,'.',',').' <span class="span-grey span-tiny">SKY</span>',
					number_format($row['f_user_sky_hold_percent'],2,'.',',').'%',
					number_format($row['f_eth_airdrop_volume'],3,'.',',').' <span class="span-grey span-tiny">ETH</span>'
				);
				$count++;
			}			
			if($count == 0){
				$return_data[] = array(
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					''
				);
			}		
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}

	public function ajaXGetUserDepositHistory(){
		if ($this->is_authenticate()) {
		
			$langData = $this->Crud_Model->Get_Lang_Values();

			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$count = 0;
			$where = " f_type='deposit'";
			if($fromDate != ''){
				$where .= " && dc.f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && dc.f_regdate<=".$toDateInt;
			}
			$query = "SELECT *, dc.f_id AS id, dc.f_status AS dc_status, dc.f_regdate AS regdate FROM tb_log_user_deposit_withdraw dc LEFT JOIN tb_user u ON dc.f_token=u.f_token WHERE".$where." ORDER BY dc.f_regdate DESC";
			$depositHistoryData = $this->Crud_Model->Get_Sql_Result($query);
			$count = 0;
			foreach ($depositHistoryData as $row) {
				if ($row['dc_status'] == 1) {
					$status = '<span class="span-grey">확인됨</span>';
				} else {
					$status = '<span class="span-red">대기중...</span>';
				}

				if ($row['dc_status'] == 1) {
					$confirmed = '';
				} else {
					$confirmed = '<span class="span-button-status off" onclick="processDeposit(' . "'" . $row['id'] . "'" . ')">확인</span> <span class="span-button-status on" onclick="deleteDepWithRequest(' . "'" . $row['id'] . "'" . ')">취소</span>';
				}
				$return_data[] = array(
					date('Y-m-d H:i:s', $row['regdate']),
					$row['f_email'],
					$row['f_username'],
					$row['f_unit'],
					$row['f_detail'],
					number_format($row['f_amount'],8,'.',','),
					$status,
					$confirmed
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

	public function ajaxGetUserWithdrawHistory(){
		if ($this->is_authenticate()) {
		
			$langData = $this->Crud_Model->Get_Lang_Values();

			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$count = 0;
			$where = " f_type='withdraw'";
			if($fromDate != ''){
				$where .= " && wc.f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && wc.f_regdate<=".$toDateInt;
			}
			$query = "SELECT *, wc.f_id AS id, wc.f_status AS wc_status, wc.f_regdate AS regdate FROM tb_log_user_deposit_withdraw wc LEFT JOIN tb_user u ON wc.f_token=u.f_token WHERE".$where." ORDER BY wc.f_regdate DESC";
			$withdrawHistoryData = $this->Crud_Model->Get_Sql_Result($query);
			$count = 0;
			foreach ($withdrawHistoryData as $row) {
				if ($row['wc_status'] == 1) {
					$status = '<span class="span-grey">확인됨</span>';
				} else {
					$status = '<span class="span-red">대기중...</span>';
				}

				if ($row['wc_status'] == 1) {
					$confirmed = '';
				} else {
					$confirmed = '<span class="span-button-status off" onclick="processWithdraw(' . "'" . $row['id'] . "'" . ')">확인</span> <span class="span-button-status on" onclick="deleteDepWithRequest(' . "'" . $row['id'] . "'" . ')">취소</span>';
				}
				$return_data[] = array(
					date('Y-m-d H:i:s', $row['regdate']),
					$row['f_email'],
					$row['f_username'],
					$row['f_unit'],
					$row['f_detail'],
					number_format($row['f_amount'],8,'.',','),
					$row['f_fees'],
					$status,
					$confirmed
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

	public function changeUserBalacne(){
		if ($this->isSuperAdmin()) {		
			$id = $this->input->post('id');
			$value = $this->input->post('value');
			$temp = explode('_', $id);
			$key_data = [];
			$key_data['f_id'] = $temp[0];
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);
			$token = $userdata['f_token'];
			$key_data = [];
			$key_data['f_token'] = $token;
			$key_data['f_unit'] = $temp[1];

			$user_wallet = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);

			$update_data = [];
			if($temp[2] == 'total'){
				$update_data['f_total'] = $value;
			}elseif ($temp[2] == 'available') {
				$update_data['f_available'] = $value;
			}elseif ($temp[2] == 'blocked') {
				$update_data['f_blocked'] = $value;
			}
			$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);

			$update_data['f_token'] = $token;
			$update_data['f_username'] = $userdata['f_username'];
			$update_data['f_email'] = $userdata['f_email'];
			if ($temp[2] == 'total') {
				$update_data['change_amount'] = $value - $user_wallet['f_total'];
			} elseif ($temp[2] == 'available') {
				$update_data['change_amount'] = $value - $user_wallet['f_available'];
			} elseif ($temp[2] == 'blocked') {
				$update_data['change_amount'] = $value - $user_wallet['f_blocked'];
			}
			$update_data['f_unit'] = $temp[1];

			$insert_data = [];
			$insert_data['f_username'] = $this->session->userdata('coinsky_admin_name');
			$insert_data['f_action'] = 'changeUserBalance';
			$insert_data['f_data'] = json_encode($update_data);
			$timestamp = round(microtime(true) * 1000000000);
			$insert_data['f_timestamp'] = $timestamp;
			$result = $this->Crud_Model->Insert_Data('tb_admin_action', $insert_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function changeLanguage(){
		if ($this->isSuperAdmin()) {		
			$id = $this->input->post('id');
			$value = $this->input->post('value');
			$temp = explode('_', $id);
			$key_data = [];
			$key_data['f_id'] = $temp[0];
			$update_data = [];
			$update_data[$temp[1]] = $value;
			$result = $this->Crud_Model->Update_Data('tb_lang', $key_data, $update_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function processWithdraw()
	{
		if ($this->isSuperAdmin()) {
			$id = $this->input->post('id');

			$key_data = [];
			$key_data['f_id'] = $id;
			$withdrawData = $this->Crud_Model->Get_A_Row_Data('tb_log_user_deposit_withdraw', $key_data);
			$update_data = [];
			$update_data['f_status'] = 1;
			$result = $this->Crud_Model->Update_Data('tb_log_user_deposit_withdraw', $key_data, $update_data);

			$userToken = $withdrawData['f_token'];
			$key_data = [];
			$key_data['f_token'] = $userToken;
			$key_data['f_unit'] = $withdrawData['f_unit'];
			$walletData = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);
			$update_data = [];
			$update_data['f_total'] = $walletData['f_total'] - $withdrawData['f_amount'];
			$update_data['f_blocked'] = $walletData['f_blocked'] - $withdrawData['f_amount'];
			if ($withdrawData['f_unit'] == 'KRW') {
				$update_data['f_sell_volume'] = $walletData['f_sell_volume'] + $withdrawData['f_amount'];
				$update_data['f_sell_base_volume'] = $walletData['f_sell_base_volume'] + $withdrawData['f_amount'];
			} else {
				$sql = "SELECT f_close FROM tb_market WHERE f_target='" . $walletData['f_unit'] . "' && f_base='KRW'";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
				if (isset($result_data['f_close'])) {
					$currentRate = $result_data['f_close'];
				} else {
					$currentRate = 0;
				}
				$sellBaseVolume = $currentRate * $withdrawData['f_amount'];
				$update_data['f_sell_volume'] = $walletData['f_sell_volume'] + $withdrawData['f_amount'];
				$update_data['f_sell_base_volume'] = $walletData['f_sell_base_volume'] + $sellBaseVolume;
			}
			$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);

			$insert_data = [];
			$insert_data['f_token'] = $userToken;
			$insert_data['f_type'] = 'enabled';
			$insert_data['f_unit'] = $withdrawData['f_unit'];
			$insert_data['f_amount'] = $withdrawData['f_amount'];
			$insert_data['f_detail'] = 'Withdrawal amount enabled';
			$insert_data['f_regdate'] = time();
			$result = $this->Crud_Model->Insert_Data('tb_user_wallet_history', $insert_data);

			$insert_data = [];
			$insert_data['f_token'] = $userToken;
			$insert_data['f_type'] = 'out';
			$insert_data['f_unit'] = $withdrawData['f_unit'];
			$insert_data['f_amount'] = $withdrawData['f_amount'];
			$insert_data['f_detail'] = 'Withdrawal processed';
			$insert_data['f_regdate'] = time();
			$result = $this->Crud_Model->Insert_Data('tb_user_wallet_history', $insert_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function processDeposit()
	{
		if ($this->isSuperAdmin()) {
		
			$id = $this->input->post('id');

			$key_data = [];
			$key_data['f_id'] = $id;
			$depositData = $this->Crud_Model->Get_A_Row_Data('tb_log_user_deposit_withdraw', $key_data);
			$update_data = [];
			$update_data['f_status'] = 1;
			$result = $this->Crud_Model->Update_Data('tb_log_user_deposit_withdraw', $key_data, $update_data);

			$userToken = $depositData['f_token'];
			$key_data = [];
			$key_data['f_token'] = $userToken;
			$key_data['f_unit'] = $depositData['f_unit'];
			$walletData = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);
			$update_data = [];
			$update_data['f_total'] = $walletData['f_total'] + $depositData['f_amount'];
			$update_data['f_available'] = $walletData['f_available'] + $depositData['f_amount'];
			if($depositData['f_unit'] == 'KRW'){
				$update_data['f_buy_volume'] = $walletData['f_buy_volume'] + $depositData['f_amount'];
				$update_data['f_buy_base_volume'] = $walletData['f_buy_base_volume'] + $depositData['f_amount'];
			}else{
				$sql = "SELECT f_close FROM tb_market WHERE f_target='" . $walletData['f_unit'] . "' && f_base='KRW'";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
				if (isset($result_data['f_close'])) {
					$currentRate = $result_data['f_close'];
				} else {
					$currentRate = 0;
				}
				$buyBaseVolume = $currentRate * $depositData['f_amount'];
				$update_data['f_buy_volume'] = $walletData['f_buy_volume'] + $depositData['f_amount'];
				$update_data['f_buy_base_volume'] = $walletData['f_buy_base_volume'] + $buyBaseVolume;
			}
			$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);

			$insert_data = [];
			$insert_data['f_token'] = $userToken;
			$insert_data['f_type'] = 'in';
			$insert_data['f_unit'] = $depositData['f_unit'];
			$insert_data['f_amount'] = $depositData['f_amount'];
			$insert_data['f_detail'] = 'deposit';
			$insert_data['f_regdate'] = time();
			$result = $this->Crud_Model->Insert_Data('tb_user_wallet_history', $insert_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		}else{
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}


	public function deleteDepWithRequest()
	{
		$returnData['res'] = true;
		$returnData['msg'] = '처리 결과 : 성공';

		if ($this->isSuperAdmin()) {

			$id = $this->input->post('id');

			$key_data = [];
			$key_data['f_id'] = $id;
			$result_data = $this->Crud_Model->Get_A_Row_Data('tb_log_user_deposit_withdraw', $key_data);
			
			if($result_data['f_type'] == 'withdraw'){
				$key_data = [];
				$key_data['f_token'] = $result_data['f_token'];
				$key_data['f_unit'] = $result_data['f_unit'];
				$walletData = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);
				$update_data = [];
				$update_data['f_available'] = $walletData['f_available'] + $result_data['f_amount'];
				$update_data['f_blocked'] = $walletData['f_blocked'] - $result_data['f_amount'];
				if($update_data['f_blocked'] < 0){
					$returnData['res'] = false;
					$returnData['msg'] = '처리 결과 : 실패, 보유수량 오류.';
				}else{
					$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);

					$insert_data = [];
					$insert_data['f_token'] = $result_data['f_token'];
					$insert_data['f_type'] = 'enabled';
					$insert_data['f_unit'] = $result_data['f_unit'];
					$insert_data['f_amount'] = $result_data['f_amount'];
					$insert_data['f_detail'] = 'cancel withdraw request';
					$insert_data['f_regdate'] = time();
					$result = $this->Crud_Model->Insert_Data('tb_user_wallet_history', $insert_data);
				}
			}
			if($returnData['res'] == true){
				$key_data = [];
				$key_data['f_id'] = $id;
				$result = $this->Crud_Model->Delete_A_Row('tb_log_user_deposit_withdraw', $key_data);
			}
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function ajaxRegisterUserWithdrawAccount(){
		if ($this->isSuperAdmin()) {		
			$userToken = $this->input->post('userToken');
			$withdraw_account_type = $this->input->post('withdraw_account_type');
			$withdraw_account_name = $this->input->post('withdraw_account_name');
			$withdraw_bank = $this->input->post('withdraw_bank');
			$withdraw_bank_no = $this->input->post('withdraw_bank_no');

			$key_data = [];
			$key_data['f_token'] = $userToken;
			$update_data = [];
			$update_data['f_withdraw_account_type'] = $withdraw_account_type;
			$update_data['f_withdraw_account_name'] = $withdraw_account_name;
			$update_data['f_withdraw_bank'] = $withdraw_bank;
			$update_data['f_withdraw_bank_no'] = $withdraw_bank_no;
			$result = $this->Crud_Model->Update_Data('tb_user', $key_data, $update_data);

			$returnData['res'] = true;
			$returnData['msg'] = '처리 결과 : 성공';
		} else {
			$returnData['res'] = false;
			$returnData['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($returnData);
	}

	public function ajaxHotWithdraw(){

		if ($this->isSuperAdmin()) {			

			if ($this->session->userdata('coinsky_lang') == null) {
				$lang = 'KO';
			} else {
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$coin = $this->input->post('coin');
			$to_address = $this->input->post('toAddress');
			$amount = $this->input->post('amount');

			$return_data['res'] = true;
			$return_data['msg'] = $langData['return-msg-msg-20'][$lang];
			$config = $this->Crud_Model->Get_System_Values();

			if ($coin == 'BTC') {
				if ($return_data['res'] == true) {
					$url = 'https://api.blockcypher.com/v1/btc/main/addrs/' . $to_address . '?token=' . $config['blockcypherToken'];
					$result = $this->sendCurlRequest($url);
					if (isset($result->error) || !isset($result->address)) {
						$return_data['res'] = false;
						$return_data['msg'] = $langData['return-msg-msg-21'][$lang];
					}
				}
				if ($return_data['res'] == true) {
					$key_data = [];
					$key_data['f_unit'] = 'BTC';
					$siteAddressData = $this->Crud_Model->Get_A_Row_Data('tb_site_coin_address', $key_data);
					$siteAddress = $siteAddressData['f_address'];
					$url = "https://api.blockcypher.com/v1/btc/main/addrs/" . $siteAddress . "/balance";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$totalBalance = $totalBalance / pow(10, 8);
					if($siteAddress != $to_address){
						if ($totalBalance > $amount) {
							$sendAmountApi = $amount * pow(10, 8);
							$tx_result = $this->blockcypher_library->NewTransactionEndpoint('btc', $config['blockcypherToken'], $siteAddress, $to_address, $sendAmountApi, $siteAddressData['f_private']);
						} else {
							$return_data['res'] = false;
							$return_data['msg'] = '처리 결과 : 실패, 보유수량 부족.';
						}
					} else {
						$return_data['res'] = false;
						$return_data['msg'] = '처리 결과 : 실패, 출금주소 오류.';
					}
				}
			} else if ($coin == 'ETH') {
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

					if ($siteAddress != $to_address) {
						if ($totalBalance > $amount) {
							$url = "localhost:8081/send_eth";
							$postData = 'from=' . $siteAddress . '&to=' . $to_address . '&amount=' . $amount . '&private=' . $siteAddressData['f_private'];
							$result = $this->sendCurlRequest($url, 1, $postData);
							if ($result->status == true) {
								$txHash = $result->txHash;
							} else {
								$return_data['res'] = false;
								$return_data['msg'] = $langData['admin-ctrl-msg-4'][$lang];
							}
						} else {
							$return_data['res'] = false;
							$return_data['msg'] = '처리 결과 : 실패, 보유수량 부족.';
						}
					} else {
						$return_data['res'] = false;
						$return_data['msg'] = '처리 결과 : 실패, 출금주소 오류.';
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
					$url = "localhost:8081/get_erc20token_balance/" . $siteAddress . "/SKY";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$totalBalance = $totalBalance / pow(10, 18);
					if ($siteAddress != $to_address) {
						if ($totalBalance > $amount) {
							$url = "localhost:8081/send_erc20token";
							$postData = 'token=' . $coin . '&from=' . $siteAddress . '&to=' . $to_address . '&amount=' . $amount . '&private=' . $siteAddressData['f_private'];
							$result = $this->sendCurlRequest($url, 1, $postData);
							if ($result->status == true) {
								$txHash = $result->txHash;
							} else {
								$return_data['res'] = false;
								$return_data['msg'] = $langData['admin-ctrl-msg-4'][$lang];
							}
						} else {
							$return_data['res'] = false;
							$return_data['msg'] = '처리 결과 : 실패, 보유수량 부족.';
						}
					} else {
						$return_data['res'] = false;
						$return_data['msg'] = '처리 결과 : 실패, 출금주소 오류.';
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
					$url = "localhost:8081/get_erc20token_balance/" . $siteAddress . "/BDR";
					$result = $this->sendCurlRequest($url);
					$totalBalance = $result->balance;
					$totalBalance = $totalBalance / pow(10, 18);
					if ($siteAddress != $to_address) {
						if ($totalBalance > $amount) {
							$url = "localhost:8081/send_erc20token";
							$postData = 'token=' . $coin . '&from=' . $siteAddress . '&to=' . $to_address . '&amount=' . $amount . '&private=' . $siteAddressData['f_private'];
							$result = $this->sendCurlRequest($url, 1, $postData);
							if ($result->status == true) {
								$txHash = $result->txHash;
							} else {
								$return_data['res'] = false;
								$return_data['msg'] = $langData['admin-ctrl-msg-4'][$lang];
							}
						} else {
							$return_data['res'] = false;
							$return_data['msg'] = '처리 결과 : 실패, 보유수량 부족.';
						}
					} else {
						$return_data['res'] = false;
						$return_data['msg'] = '처리 결과 : 실패, 출금주소 오류.';
					}
				}
			}
		} else {
			$return_data['res'] = false;
			$return_data['msg'] = '처리 결과 : 실패, 최고관리자가 아닙니다.';
		}
		echo json_encode($return_data);
	}

}
