<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excn extends CI_Controller {

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
			redirect(base_url().'excn/adnc');
		}else{
			redirect(base_url().'acnt/siin');
		}
	}

	public function getMarketHistoryData($target = 'BTC', $base = 'KRW'){
		$query =  "SELECT * FROM `tb_market_history` WHERE `f_target`='".$target."' && `f_base`='".$base."' ORDER BY f_regdate DESC, f_id DESC LIMIT 0,200";
	    $result = $this->Crud_Model->Get_Sql_Result($query);
		if(count($result)>0){
			foreach ($result as $key => $row) {
				$return_data[$key]['rate'] = $row['f_rate'];
				$return_data[$key]['volume'] = $row['f_target_volume'];
				$return_data[$key]['bVolume'] = $row['f_base_volume'];
				$return_data[$key]['regdate'] = date('Y-m-d H:i:s',$row['f_regdate']);
				$return_data[$key]['type'] = $row['f_type'];
			}	
		}else{
			$return_data = [];
		}	
		return $return_data;
	}

	public function getDailyMarketHistoryData($target = 'BTC', $base = 'KRW'){
	    $query =  "SELECT * FROM `tb_market_daily_history` WHERE `f_target`='".$target."' && `f_base`='".$base."' ORDER BY f_regdate DESC, f_id DESC LIMIT 0,200";
		$result = $this->Crud_Model->Get_Sql_Result($query);
		$count = 0;
		foreach ($result as $key => $row) {
			$count++;
			$return_data[$count]['date'] = date('Y-m-d', $row['f_regdate']);
			$return_data[$count]['close'] = $row['f_close'];
			$return_data[$count]['diff'] = $row['f_diff'];
			$return_data[$count]['diffPercent'] = $row['f_percent'];
			$return_data[$count]['tVolume'] = $row['f_target_volume'];
			$return_data[$count]['bVolume'] = $row['f_base_volume'];
			$return_data[$count]['open'] = $row['f_open'];
			$return_data[$count]['high'] = $row['f_high'];
			$return_data[$count]['low'] = $row['f_low'];
		}

		$key_data = [];
		$key_data['f_enabled'] = 1;
		$key_data['f_target'] = $target;
		$key_data['f_base'] = $base;
		$result = $this->Crud_Model->Get_A_Row_Data('tb_market', $key_data);
		$return_data[0]['date'] = date('Y-m-d');
		$return_data[0]['close'] = $result['f_close'];
		$return_data[0]['diff'] = $result['f_diff'];
		$return_data[0]['diffPercent'] = $result['f_percent'];
		$return_data[0]['tVolume'] = $result['f_day_target_volume'];
		$return_data[0]['bVolume'] = $result['f_day_base_volume'];
		$return_data[0]['open'] = $result['f_open'];
		$return_data[0]['high'] = $result['f_high'];
		$return_data[0]['low'] = $result['f_low'];
		$returnData = [];
		for($i = 0; $i<=$count; $i++){
			$returnData[$i] = $return_data[count($return_data)-1-$i];
		}
		return $returnData;
	}

	public function getMyOrderHistoryData($token, $target = 'BTC', $base = 'KRW'){

		if ($this->is_authenticate()) {
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$query =  "SELECT * FROM tb_market_history WHERE (`f_token`='".$token."' OR `f_otoken`='".$token."') && `f_base`='".$base."' && `f_target`='".$target."' ORDER BY f_regdate DESC, f_id DESC LIMIT 0, 50";
			$result = $this->Crud_Model->Get_Sql_Result($query);
			if(count($result)>0){
				foreach ($result as $key => $row) {
					if($row['f_token'] == $token){
						if($row['f_type']=='buy'){
							$type = $langData['buy'][$lang];
						}else{
							$type = $langData['sell'][$lang];
						}
					}else if($row['f_otoken'] == $token){
						if($row['f_type']=='buy'){
							$type = $langData['buy'][$lang];
						}else{
							$type = $langData['sell'][$lang];
						}
					}
					$return_data[$key]['date'] = '<span class="datatable-datetime-lineheight">'.date('Y-m-d', $row['f_regdate']).' '.date('H:i:s', $row['f_regdate']).'</span>';
					$return_data[$key]['type'] = $type;
					$return_data[$key]['tVolume'] = $row['f_target_volume'];
					$return_data[$key]['rate'] = $row['f_rate'];
					if($row['f_type']=="buy"){
						$fee = $row['f_base_volume']*0.0015;
						$fee = number_format($fee);
					}else{
						$fee = $row['f_target_volume']*0.0015;
						$fee = number_format($fee,8,'.',',');
					}
					$return_data[$key]['fee'] = $fee;
					$return_data[$key]['bVolume'] = $row['f_base_volume'];
				}
			}else{
				$return_data = [];
			}
			return $return_data;
		}else{
			return false;
		}
	}
	
	public function getMyOpenOrdersData($token, $target = 'BTC', $base = 'KRW')
	{
		if ($this->is_authenticate()) {
			$query = "SELECT * FROM tb_market_order WHERE `f_token`='" . $token . "' && `f_base`='" . $base . "' && `f_target`='" . $target . "' ORDER BY f_regdate DESC, f_id DESC LIMIT 0, 50";
			$result = $this->Crud_Model->Get_Sql_Result($query);
			if (count($result) > 0) {
				foreach ($result as $key => $row) {
					$return_data[$key]['id'] = $row['f_id'];
					if ($row['f_type'] == 'buy') {
						$type = '매수';
					} else {
						$type = '매도';
					}
					$return_data[$key]['date'] = '<span class="datatable-datetime-lineheight">' . date('Y-m-d', $row['f_regdate']) . ' ' . date('H:i:s', $row['f_regdate']) . '</span>';
					$return_data[$key]['type'] = $type;
					$return_data[$key]['rate'] = $row['f_rate'];
					$return_data[$key]['originalTVolume'] = $row['f_original_target_volume'];
					$return_data[$key]['tVolume'] = $row['f_target_volume'];
				}
			} else {
				$return_data = [];
			}
			return $return_data;
		}else{
			return false;
		}
	}

	public function getMyBalanceData($token)
	{
		if ($this->is_authenticate()) {
			$query = "SELECT * FROM tb_user_wallet WHERE f_token = '".$token."'";
			$result = $this->Crud_Model->Get_Sql_Result($query);
			if (count($result) > 0) {
				foreach ($result as $key => $row) {
					$return_data[$row['f_unit']] = $row;
				}
			} else {
				$return_data = [];
			}
			return $return_data;
		}else{
			return false;
		}
	}

	public function getMarketData(){
		$key_data = [];
		$key_data['f_enabled'] = 1;
		$result = $this->Crud_Model->Get_Sub_Data('tb_market', $key_data);
		foreach ($result as $key => $value) {
			$marketData[$value['f_target']][$value['f_base']] = $value;
		}
		return $marketData;
	}

	public function getCoinData(){
		$result = $this->Crud_Model->Get_All_Table_Data('tb_unit');
		foreach ($result as $key => $value) {
			$coinData[$value['f_unit']] = $value;
		}
		return $coinData;
	}

	public function getFavMarket($base){
		if ($this->is_authenticate()) {
			$coinData = $this->getCoinData();
			foreach ($coinData as $key => $value) {
				if($this->session->userdata('exchange_user_login') == true){
					$key_data = [];
					$key_data['f_token'] = $this->session->userdata('token');
					$key_data['f_target'] = $key;
					$key_data['f_base'] = $base;
					$key_data['f_is_fav'] = 1;
					$isFav = $this->Crud_Model->Get_A_Row_Data('tb_market_favourite', $key_data);
					if($isFav != '' && $isFav != null){
						$favMarket[$key][$base] = 1;
					}else{
						$favMarket[$key][$base] = 0;
					}
				}else{
					$favMarket[$key][$base] = 0;
				}
			}
			return $favMarket;
		}else{
			return false;
		}
	}

	public function getOrderBookData($target, $base)
	{
		if ($this->is_authenticate()) {
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
						$sellOrders[$i]['rate'] = '';
						$sellOrders[$i]['tVolume'] = '';
						$sellOrders[$i]['myTVolume'] = '';
					}
				}
				for ($i = 13 - count($orderData) + 1; $i <= 13; $i++) {
					$sellOrders[$i]['rate'] = $orderData[13 - $i]['f_rate'];
					$tempRate = $orderData[13 - $i]['f_rate'];
					if($this->is_authenticate()){
						$token = $this->session->userdata('token');
						$sub_query = "SELECT SUM(f_target_volume) AS myTVolume FROM tb_market_order WHERE f_token='" . $token . "' && f_type='sell' && f_target='" . $target . "' && f_base='" . $base . "' && f_rate='" . $tempRate . "'";
						$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sub_query);
						if (isset($result_data['myTVolume'])) {
							if ($result_data['myTVolume'] == 0 || $result_data['myTVolume'] == null) {
								$myTVolume = '';
							} else {
								$myTVolume = $result_data['myTVolume'];
							}
						} else {
							$myTVolume = '';
						}
					}else{
						$myTVolume = '';
					}
					$sellOrders[$i]['tVolume'] = $orderData[13 - $i]['SUM(f_target_volume)'];
					$sellOrders[$i]['myTVolume'] = $myTVolume;
				}
			} else {
				for ($i = 1; $i <= 13; $i++) {
					$sellOrders[$i]['rate'] = '';
					$sellOrders[$i]['tVolume'] = '';
					$sellOrders[$i]['myTVolume'] = '';
				}
			}
			$sql = "SELECT f_rate, SUM(f_target_volume) FROM tb_market_order WHERE f_type='buy' && f_target='" . $target . "' && f_base='" . $base . "' GROUP BY f_rate ORDER BY f_rate DESC limit 0, 13";
			$orderData = $this->Crud_Model->Get_Sql_Result($sql);
			if (count($orderData) > 0) {
				for ($i = 1; $i <= count($orderData); $i++) {
					$buyOrders[$i]['rate'] = $orderData[$i - 1]['f_rate'];
					$tempRate = $orderData[$i - 1]['f_rate'];
					if ($this->is_authenticate()) {
						$token = $this->session->userdata('token');
						$sub_query = "SELECT SUM(f_target_volume) AS myTVolume FROM tb_market_order WHERE f_token='" . $token . "' && f_type='buy' && f_target='" . $target . "' && f_base='" . $base . "' && f_rate='" . $tempRate . "'";
						$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sub_query);
						if (isset($result_data['myTVolume'])) {
							if($result_data['myTVolume'] == 0 || $result_data['myTVolume'] == null){
								$myTVolume = '';
							} else {
								$myTVolume = $result_data['myTVolume'];
							}
						} else {
							$myTVolume = '';
						}
					} else {
						$myTVolume = '';
					}
					$buyOrders[$i]['tVolume'] = $orderData[$i - 1]['SUM(f_target_volume)'];
					$buyOrders[$i]['myTVolume'] = $myTVolume;
				}
				if (count($orderData) < 13) {
					for ($i = count($orderData) + 1; $i <= 13; $i++) {
						$buyOrders[$i]['rate'] = '';
						$buyOrders[$i]['tVolume'] = '';
						$buyOrders[$i]['myTVolume'] = '';
					}
				}
			} else {
				for ($i = 1; $i <= 13; $i++) {
					$buyOrders[$i]['rate'] = '';
					$buyOrders[$i]['tVolume'] = '';
					$buyOrders[$i]['myTVolume'] = '';
				}
			}
			$orderBookData['buyOrders'] = $buyOrders;
			$orderBookData['sellOrders'] = $sellOrders;
			return $orderBookData;
		}else{
			return false;
		}
	}

	public function adnc($target='BTC', $base='KRW'){
		$key_data = [];
		$key_data['f_target'] = $target;
		$key_data['f_base'] = $base;
		$key_data['f_enabled'] = 1;
		$is_existing_market = $this->Crud_Model->Check_Row_Exist_With_Key('tb_market', $key_data);
		if($is_existing_market){
			$config = $this->Crud_Model->Get_System_Values();
			$langData = $this->Crud_Model->Get_Lang_Values();
			$key_data = [];
			$key_data['f_enabled'] = 1;
			$coinData = $this->getCoinData();
			$marketData = $this->getMarketData();
			$orderBookData = $this->getOrderBookData($target, $base);
			$favMarket = $this->getFavMarket($base);
			$marketHistoryData = $this->getMarketHistoryData($target, $base);
			$dailyMarketHistoryData = $this->getDailyMarketHistoryData($target, $base);
			if ($this->is_authenticate()) {
				$token = $this->session->userdata('token');
				$myOpenOrdersData = $this->getMyOpenOrdersData($token, $target, $base);
				$myOrderHistoryData = $this->getMyOrderHistoryData($token, $target, $base);
				$myBalanceData = $this->getMyBalanceData($token);

				$page_data['is_login'] = true;
				$page_data['token'] = $token;
				$page_data['myOpenOrdersData'] = $myOpenOrdersData;
				$page_data['myOrderHistoryData'] = $myOrderHistoryData;
				$page_data['myBalanceData'] = $myBalanceData;
				$page_data['marketHistoryData'] = $marketHistoryData;

			} else {
				$page_data['is_login'] = false;
				$page_data['token'] = '';
			}

			$query = "SELECT MAX(f_rate) AS lastBuyRate FROM tb_market_order WHERE f_target = '" . $target . "' && f_base = '" . $base . "' && f_type='buy'";
			$result = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			if (count($result) > 0) {
				$lastBuyRate = $result['lastBuyRate'];
			} else {
				$lastBuyRate = $marketData[$target][$base]['f_close'];
			}

			$query = "SELECT MIN(f_rate) AS lastSellRate FROM tb_market_order WHERE f_target = '" . $target . "' && f_base = '" . $base . "' && f_type='sell'";
			$result = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			if (count($result) > 0) {
				$lastSellRate = $result['lastSellRate'];
			} else {
				$lastSellRate = $marketData[$target][$base]['f_close'];
			}

			if ($this->session->userdata('coinsky_lang') == null) {
				$lang = 'KO';
			} else {
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;
			

			$page_data['page'] = 'adnc';
			$page_data['target'] = $target;
			$page_data['base'] = $base;
			$page_data['config'] = $config;
			$page_data['unitDecimal'] = $marketData[$target][$base]['f_decimal'];
			$page_data['marketData'] = $marketData;
			$page_data['coinData'] = $coinData;
			$page_data['lastBuyRate'] = $lastBuyRate;
			$page_data['lastSellRate'] = $lastSellRate;
			$page_data['favMarket'] = $favMarket;
			$page_data['orderBookData'] = $orderBookData;
			$page_data['marketHistoryData'] = $marketHistoryData;
			$page_data['dailyMarketHistoryData'] = $dailyMarketHistoryData;
			$this->load->view('temp/header', $page_data);
			$this->load->view('excn/adnc');
			$this->load->view('temp/footer');
		}else{
			redirect(base_url() . 'excn/adnc');
		}
	}
}
