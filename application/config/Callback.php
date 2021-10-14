<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('Crud_Model');
		$this->load->library('Blockcypher_Library');
    }

	public function deposit($coin, $token)
	{
		$insertData['f_token'] = $token;
		$insertData['f_unit'] = $coin;
		$insertData['f_type'] = 'deposit';
		$insertData['f_fees'] = 0;
		$insertData['f_status'] = 1;
		$insertData['f_regdate'] = time();
		$depositResult = false;
		
		// $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
		// $temp = json_encode($this->input->get());
		// $temp .= json_encode($this->input->post());
		// $temp .= json_decode(file_get_contents('php://input'));
		// fwrite($myfile, $temp);
		// fclose($myfile);
		
		if($coin == 'BTC'){
			$addressForward = json_decode(file_get_contents('php://input'));
			$value = $addressForward->value;
			$input_transaction_hash = $addressForward->input_transaction_hash;
			$newAmount = $value/pow(10,8);
			$insertData['f_detail'] = $input_transaction_hash;
			$insertData['f_txhash'] = $input_transaction_hash;
			$insertData['f_amount'] = $newAmount;
			$depositResult = $this->Crud_Model->Insert_Data('tb_log_user_deposit_withdraw', $insertData);
		}
		if($depositResult){
			$key_data = [];
			$key_data['f_token'] = $token;
			$key_data['f_unit'] = $coin;
			$userWalletData = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);
			$update_data = [];
			$update_data['f_total'] = $userWalletData['f_total'] + $newAmount;
			$update_data['f_available'] = $userWalletData['f_available'] + $newAmount;

			$sql = "SELECT f_close FROM tb_market WHERE f_target='".$coin."' && f_base='KRW'";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
			if(isset($result_data['f_close'])){
				$currentRate = $result_data['f_close'];
			}else{
				$currentRate = 0;
			}
			$newBaseVolume = $currentRate * $newAmount;

			$update_data['f_buy_volume'] = $userWalletData['f_buy_volume'] + $newAmount;
			$update_data['f_buy_base_volume'] = $userWalletData['f_buy_base_volume'] + $newBaseVolume;
			$result = $this->Crud_Model->Update_Data('tb_user_wallet', $key_data, $update_data);

			$insert_data = [];
			$insert_data['f_token'] = $token;
			$insert_data['f_type'] = 'in';
			$insert_data['f_unit'] = $coin;
			$insert_data['f_amount'] = $newAmount;
			$insert_data['f_detail'] = "deposit";
			$insert_data['f_regdate'] = time();
            $result = $this->Crud_Model->Insert_Data('tb_user_wallet_history', $insert_data);
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
	
	public function temp()
	{	
		$btcAddressListData = $this->sendCurlRequest('https://api.blockcypher.com/v1/btc/main/forwards?token=7c34777a04354e7ea5d02ddee36a9a91');
		foreach($btcAddressListData as $row){
			$result = $this->blockcypher_library->deletePaymentForwardAddress($row->id);
		}
	}





}
