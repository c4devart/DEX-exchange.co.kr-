<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Walt extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('Crud_Model');
    }

	function is_authenticate()
	{
		if($this->session->userdata('exchange_user_login') == true){
			return true;
		}else{
			return false;
		}
	}

	public function dept($coin = 'KRW')
	{
		if($this->is_authenticate()){
			if($coin!='KRW' && $coin!='BTC' && $coin!='ETH' && $coin!='SKY' && $coin != 'BDR'){
				$coin ='KRW';
			}
			$token = $this->session->userdata('token');
			$config = $this->Crud_Model->Get_System_Values();
			$key_data = [];
			$key_data['f_enabled'] = 1;
			$coinList = $this->Crud_Model->Get_Sub_Data('tb_unit', $key_data);

			$key_data = [];
			$key_data['f_unit'] = $coin;
			$coinData = $this->Crud_Model->Get_A_Row_Data('tb_unit', $key_data);

			$query =  "SELECT SUM(f_amount) as dayWithdrawalAmount FROM tb_log_user_deposit_withdraw WHERE f_token='".$token."' && f_unit='".$coin."' && f_type='withdraw' && f_type='withdraw'";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$dayWithdrawalAmount = $result_data['dayWithdrawalAmount'];
			$tempDayWithdrawalAmount = $dayWithdrawalAmount;
			if($coin == 'KRW'){
				$dayWithdrawalAmount = number_format($dayWithdrawalAmount);
				$minWithdrawAmount = number_format($coinData['f_min_withdraw_amount']);
			}else{
				$dayWithdrawalAmount = number_format($dayWithdrawalAmount, 8, '.', ',');
				$minWithdrawAmount = number_format($coinData['f_min_withdraw_amount'], 8, '.', ',');
			}

			$key_data = [];
			$key_data['f_token'] = $this->session->userdata('token');
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);

			if($coin == 'BTC'){
				$key_data = [];
				$key_data['f_token'] = $this->session->userdata('token');
				$result = $this->Crud_Model->Get_A_Row_Data('tb_user_deposit_address_btc', $key_data);
				if($result != []){
					$depositAddress = $result['f_address'];
				}else{
					$depositAddress = '';
				}
			}else if($coin == 'ETH' || $coin == 'SKY' || $coin == 'BDR'){
				$key_data = [];
				$key_data['f_token'] = $this->session->userdata('token');
				$result = $this->Crud_Model->Get_A_Row_Data('tb_user_deposit_address_eth', $key_data);
				if($result != []){
					$depositAddress = $result['f_address'];
				}else{
					$depositAddress = '';
				}
			}else{
				$depositAddress = '';
			}
			$key_data = [];
			$key_data['f_token'] = $this->session->userdata('token');
			$key_data['f_unit'] = $coin;
			$balance = $this->Crud_Model->Get_A_Row_Data('tb_user_wallet', $key_data);
			$key_data = [];
			if($coin == 'KRW'){
				$totalByBase = $balance['f_total'];
			}else{
				$key_data['f_target'] = $coin;
				$key_data['f_base'] = 'KRW';
				$result = $this->Crud_Model->Get_A_Row_Data('tb_market', $key_data);
				$totalByBase = $balance['f_total']*$result['f_close'];
			}

			if($userdata['f_kyc_level'] == 1){
				$day_withdraw_limit = '불가';
				$once_withdraw_limit = '불가';
				$possibleWithdrawAmountToday = '불가';
			}else if($userdata['f_kyc_level'] == 2){
				if($coin == 'KRW'){
					$day_withdraw_limit = number_format($coinData['f_daily_available_withdraw_amount_second']);
					$once_withdraw_limit = number_format($coinData['f_once_available_withdraw_amount_second']);
					$possibleWithdrawAmountToday = $coinData['f_daily_available_withdraw_amount_third'] - $tempDayWithdrawalAmount;
					$possibleWithdrawAmountToday = number_format($possibleWithdrawAmountToday);
				}else{
					if ($coinData['f_unit'] == 'SKY' || $coinData['f_unit'] == 'BDR') {
						$day_withdraw_limit = '무제한';
						$once_withdraw_limit = '무제한';
						$possibleWithdrawAmountToday = '무제한';
					} else {
						$day_withdraw_limit = number_format($coinData['f_daily_available_withdraw_amount_second'], 8, '.', ',');
						$once_withdraw_limit = number_format($coinData['f_once_available_withdraw_amount_second'], 8, '.', ',');
						$possibleWithdrawAmountToday = $coinData['f_daily_available_withdraw_amount_second'] - $tempDayWithdrawalAmount;
						$possibleWithdrawAmountToday = number_format($possibleWithdrawAmountToday, 8, '.', ',');
					}
				}
			}else if($userdata['f_kyc_level'] == 3){
				$possibleWithdrawAmountToday = $coinData['f_daily_available_withdraw_amount_third'] - $tempDayWithdrawalAmount;
				if($coin == 'KRW'){
					$day_withdraw_limit = number_format($coinData['f_daily_available_withdraw_amount_third']);
					$once_withdraw_limit = number_format($coinData['f_once_available_withdraw_amount_third']);
					$possibleWithdrawAmountToday = $coinData['f_daily_available_withdraw_amount_third'] - $tempDayWithdrawalAmount;
					$possibleWithdrawAmountToday = number_format($possibleWithdrawAmountToday);
				}else{
					if ($coinData['f_unit'] == 'SKY' || $coinData['f_unit'] == 'BDR') {
						$day_withdraw_limit = '무제한';
						$once_withdraw_limit = '무제한';
						$possibleWithdrawAmountToday = '무제한';
					} else {
						$day_withdraw_limit = number_format($coinData['f_daily_available_withdraw_amount_third'], 8, '.', ',');
						$once_withdraw_limit = number_format($coinData['f_once_available_withdraw_amount_third'], 8, '.', ',');
						$possibleWithdrawAmountToday = $coinData['f_daily_available_withdraw_amount_third'] - $tempDayWithdrawalAmount;
						$possibleWithdrawAmountToday = number_format($possibleWithdrawAmountToday, 8, '.', ',');
					}
				}
			}else{
				$day_withdraw_limit = '불가';
				$once_withdraw_limit = '불가';
				$possibleWithdrawAmountToday = '무제한';
			}

			$query =  "SELECT SUM(f_base_Volume) as onBuyOrderVolume FROM tb_market_order WHERE f_token='".$token."' && f_base='".$coin."'";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$onBuyOrderVolume = $result_data['onBuyOrderVolume'];

			$query =  "SELECT SUM(f_target_Volume) as onSellOrderVolume FROM tb_market_order WHERE f_token='".$token."' && f_target='".$coin."'";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$onSellOrderVolume = $result_data['onSellOrderVolume'];
			$onOrderVolume = $onBuyOrderVolume + $onSellOrderVolume;

			$query =  "SELECT SUM(f_amount) as onWithdrawalAmount FROM tb_log_user_deposit_withdraw WHERE f_token='".$token."' && f_unit='".$coin."' && f_status=0";
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$onWithdrawalAmount = $result_data['onWithdrawalAmount'];
			
			if($coin == 'KRW'){
				$onBuyOrderVolume = number_format($onBuyOrderVolume);
				$onSellOrderVolume = number_format($onSellOrderVolume);
				$onOrderVolume = number_format($onOrderVolume);
				$withdrawFee = number_format($coinData['f_withdraw_fee_amount']);
				$onWithdrawalAmount = number_format($onWithdrawalAmount);
			}else{
				$onBuyOrderVolume = number_format($onBuyOrderVolume, 8, '.', ',');
				$onSellOrderVolume = number_format($onSellOrderVolume, 8, '.', ',');
				$onOrderVolume = number_format($onOrderVolume, 8, '.', ',');
				$withdrawFee = number_format($coinData['f_withdraw_fee_amount'], 8, '.', ',');
				$onWithdrawalAmount = number_format($onWithdrawalAmount, 8, '.', ',');
			}

			$page_data['page'] = 'dept';
			$page_data['coin'] = $coin;
			$page_data['token'] = $token;
			$page_data['config'] = $config;
			$page_data['userdata'] = $userdata;
			$page_data['depositAddress'] = $depositAddress;
			$page_data['coinList'] = $coinList;
			$page_data['coinData'] = $coinData;
			$page_data['balance'] = $balance;
			$page_data['totalByBase'] = $totalByBase;

			$page_data['day_withdraw_limit'] = $day_withdraw_limit;
			$page_data['once_withdraw_limit'] = $once_withdraw_limit;
			$page_data['dayWithdrawalAmount'] = $dayWithdrawalAmount;
			$page_data['minWithdrawAmount'] = $minWithdrawAmount;
			$page_data['possibleWithdrawAmountToday'] = $possibleWithdrawAmountToday;
			$page_data['onBuyOrderVolume'] = $onBuyOrderVolume;
			$page_data['onSellOrderVolume'] = $onSellOrderVolume;
			$page_data['onOrderVolume'] = $onOrderVolume;
			$page_data['withdrawFee'] = $withdrawFee;
			$page_data['onWithdrawalAmount'] = $onWithdrawalAmount;
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;


			$this->load->view('temp/header',$page_data);
			$this->load->view('walt/dept', $page_data);
			$this->load->view('temp/footer', $page_data);
		}else{
			redirect(base_url().'acnt/siin');
		}
	}

	public function getCoinData(){
		$key_data = [];
		$key_data['f_enabled'] = 1;
		$result = $this->Crud_Model->Get_Sub_Data('tb_unit', $key_data);
		foreach ($result as $key => $value) {
			$coinData[$value['f_unit']] = $value;
		}
		return $coinData;
	}

	public function balc()
	{
		if($this->is_authenticate()){
			$coinData = $this->getCoinData();

			$token = $this->session->userdata('token');
			$key_data = [];
			$key_data['f_token'] = $this->session->userdata('token');
			$balanceData = $this->Crud_Model->Get_Sub_Data('tb_user_wallet', $key_data);

			$KRW_balance = 0;
			$totalTargetBalanceByKRW = 0;
			$totalTargetBuyBalance = 0;
			foreach ($balanceData as $key => $value) {
				if($value['f_unit'] == 'KRW'){
					$KRW_balance = $value['f_total'];
				}
				$sql = "SELECT f_close FROM tb_market WHERE f_target='".$value['f_unit']."' && f_base='KRW'";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
				if(isset($result_data['f_close'])){
					$currentRate[$value['f_unit']] = $result_data['f_close'];
				}else{
					$currentRate[$value['f_unit']] = 0;
				}
				$totalTargetBalanceByKRW += $value['f_total']*$currentRate[$value['f_unit']];
				$totalTargetBuyBalance += $value['f_buy_base_volume'];

				$tempBuySellAvgRate = 0;
				$tempTotalTargetVolume = $value['f_buy_volume'] + $value['f_sell_volume'];
				$tempTotalBaseVolume = $value['f_buy_base_volume'] + $value['f_sell_base_volume'];
				if($tempTotalTargetVolume > 0){
					$tempBuySellAvgRate = $tempTotalBaseVolume / $tempTotalTargetVolume;
				}
				$buySellAvgRate[$value['f_unit']] = $tempBuySellAvgRate;

				$sql = "SELECT SUM(f_amount) AS onWithdraw FROM tb_log_user_deposit_withdraw WHERE f_token='".$token."' && f_unit='".$value['f_unit']."' && f_type='withdraw' && f_status=0";
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
				if(isset($result_data['onWithdraw'])){
					$onWithdraw[$value['f_unit']] = $result_data['onWithdraw'];
				}else{
					$onWithdraw[$value['f_unit']] = 0;
				}
			}


			$key_data = [];
			$key_data['f_token'] = $this->session->userdata('token');
			$userdata = $this->Crud_Model->Get_A_Row_Data('tb_user', $key_data);

			$page_data['page'] = 'balc';
			$page_data['token'] = $token;
			$page_data['userdata'] = $userdata;
			$page_data['balanceData'] = $balanceData;
			$page_data['coinData'] = $coinData;
			$page_data['KRW_balance'] = $KRW_balance;
			$page_data['totalTargetBalanceByKRW'] = $totalTargetBalanceByKRW;
			$page_data['totalBalanceByKRW'] = $KRW_balance + $totalTargetBalanceByKRW;
			$page_data['totalTargetBuyBalance'] = $totalTargetBuyBalance;
			$page_data['totalDiff'] = $totalTargetBalanceByKRW - $totalTargetBuyBalance;
			if($totalTargetBuyBalance > 0){
				$page_data['totalDiffPercent'] = $page_data['totalDiff']/$totalTargetBuyBalance*100;
			}else{
				$page_data['totalDiffPercent'] = 0;
			}
			$page_data['currentRate'] = $currentRate;
			$page_data['onWithdraw'] = $onWithdraw;
			$page_data['buySellAvgRate'] = $buySellAvgRate;
			
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();
			$page_data['langData'] = $langData;
			$page_data['lang'] = $lang;


			$this->load->view('temp/header',$page_data);
			$this->load->view('walt/balc', $page_data);
			$this->load->view('temp/footer', $page_data);
		}else{
			redirect(base_url().'acnt/siin');
		}
	}

	public function getIntvalBalanceCalc(){

	}

	public function getMyOpenOrders($token = ''){
		if ($this->is_authenticate()) {
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$searchCoin = $this->input->get('coin');
			$orderType = $this->input->get('orderType');
			if($searchCoin != ''){
				$count = 0;
				$where = " `f_token`='".$token."'";
				if($fromDate != ''){
					$where .= " && f_regdate>=".$fromDateInt;
				}
				if($toDate != ''){
					$where .= " && f_regdate<=".$toDateInt;
				}
				if($searchCoin != ''){
					$where .= " && f_target='".$searchCoin."'";
				}
				if($orderType != ''){
					$where .= " && f_type='".$orderType."'";
				}
				$query =  "SELECT * FROM tb_market_order WHERE".$where." ORDER BY f_regdate DESC";
				$myOpenOrders = $this->Crud_Model->Get_Sql_Result($query);
				if(count($myOpenOrders)>0){
					foreach ($myOpenOrders as $row) {
						if($row['f_type'] == 'buy'){
							$className="span-red";
							$order_type = $langData['buy'][$lang];
						}else{
							$className="span-blue";
							$order_type = $langData['sell'][$lang];
						}
						$edit_action = '<span class="date badge badge-my-btn span-background-green" onclick="edit_my_open_order('."'".$row['f_id']."'".', '."'".$row['f_target_volume']."'".')">'.$langData['edit'][$lang].'</span>';
						$cancel_action = '<span class="date badge badge-my-btn span-background-red" onclick="cancel_my_open_order('."'".$row['f_id']."'".')">'.$langData['cancel'][$lang].'</span>';
						$return_data[] = array(
							date('Y-m-d H:i:s', $row['f_regdate']),
							$searchCoin."/".$row['f_base'],
							'<span class="'.$className.'">'.$order_type.'</span>',
							'<span class="'.$className.'">'.number_format($row['f_target_volume'], 8, '.', '').'</span>',
							'<span class="'.$className.'">'.number_format($row['f_rate']).'</span>',
							'<span class="'.$className.'">'.number_format($row['f_base_volume']).'</span>',
							'<span class="'.$className.'">'.number_format($row['f_target_volume'], 8, '.', '').'</span>',
							$edit_action,
							$cancel_action
						);
						$count++;
					}	
				}
				if($count == 0){
					$return_data = [];
				}		
			}else{
				$key_data = [];
				$key_data['f_enabled'] = 1;
				$coinList = $this->Crud_Model->Get_Sub_Data('tb_unit', $key_data);
				$count = 0;
				foreach ($coinList as $coin) {
					$where = " `f_token`='".$token."' && f_target='".$coin['f_unit']."'";
					if($fromDate != ''){
						$where .= " && f_regdate>=".$fromDateInt;
					}
					if($toDate != ''){
						$where .= " && f_regdate<=".$toDateInt;
					}
					if($orderType != ''){
						$where .= " && f_type='".$orderType."'";
					}
					$query =  "SELECT * FROM tb_market_order WHERE".$where." ORDER BY f_regdate DESC";
					$myOpenOrders = $this->Crud_Model->Get_Sql_Result($query);
					if(count($myOpenOrders)>0){
						foreach ($myOpenOrders as $row) {
							if($row['f_type'] == 'buy'){
								$className="span-red";
								$order_type = $langData['buy'][$lang];
							}else{
								$className="span-blue";
								$order_type = $langData['sell'][$lang];
							}					
							$edit_action = '<span class="date badge badge-my-btn span-background-green" onclick="edit_my_open_order('."'".$row['f_id']."'".', '."'".$row['f_target_volume']."'".')">'.$langData['edit'][$lang].'</span>';
							$cancel_action = '<span class="date badge badge-my-btn span-background-red" onclick="cancel_my_open_order('."'".$row['f_id']."'".')">'.$langData['cancel'][$lang].'</span>';
							$return_data[] = array(
								date('Y-m-d H:i:s', $row['f_regdate']),
								$coin['f_unit']."/".$row['f_base'],
								'<span class="'.$className.'">'.$order_type.'</span>',
								'<span class="'.$className.'">'.number_format($row['f_target_volume'], 8, '.', '').'</span>',
								'<span class="'.$className.'">'.number_format($row['f_rate']).'</span>',
								'<span class="'.$className.'">'.number_format($row['f_base_volume']).'</span>',
								'<span class="'.$className.'">'.number_format($row['f_target_volume'], 8, '.', '').'</span>',
								$edit_action,
								$cancel_action
							);
							$count++;
						}	
					}
				}
				if($count == 0){
					$return_data = [];
				}
			}	
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}

	}	

	public function getMyOpenOrdersCount($token = ''){
		if ($this->is_authenticate()) {
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$searchCoin = $this->input->get('coin');
			$orderType = $this->input->get('orderType');
			if($searchCoin != ''){
				$where = " `f_token`='".$token."'";
				if($fromDate != ''){
					$where .= " && f_regdate>=".$fromDateInt;
				}
				if($toDate != ''){
					$where .= " && f_regdate<=".$toDateInt;
				}
				if($searchCoin != ''){
					$where .= " && f_target='".$searchCoin."'";
				}
				if($orderType != ''){
					$where .= " && f_type='".$orderType."'";
				}
				$query =  "SELECT COUNT(*) AS sub_count FROM tb_market_order WHERE".$where;
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
				$count = (int)$result_data['sub_count'];
			}else{
				$key_data = [];
				$key_data['f_enabled'] = 1;
				$coinList = $this->Crud_Model->Get_Sub_Data('tb_unit', $key_data);
				$count = 0;
				foreach ($coinList as $coin) {
					$where = " `f_token`='".$token."' && f_target='".$coin['f_unit']."'";
					if($fromDate != ''){
						$where .= " && f_regdate>=".$fromDateInt;
					}
					if($toDate != ''){
						$where .= " && f_regdate<=".$toDateInt;
					}
					if($searchCoin != ''){
						$where .= " && f_target='".$searchCoin."'";
					}
					if($orderType != ''){
						$where .= " && f_type='".$orderType."'";
					}
					$query =  "SELECT COUNT(*) AS sub_count FROM tb_market_order WHERE".$where;
					$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
					$count += (int)$result_data['sub_count'];
				}
			}		
			echo json_encode($count);
		}else{
			echo false;
		}
	}

	public function getMyMarketHistory($token = ''){
		if ($this->is_authenticate()) {
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			ini_set('memory_limit', '1024M');
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$searchCoin = $this->input->get('coin');
			$orderType = $this->input->get('orderType');
			if($searchCoin != ''){
				$count = 0;
				$where = " `f_token`='".$token."'";
				if($fromDate != ''){
					$where .= " && f_regdate>=".$fromDateInt;
				}
				if($toDate != ''){
					$where .= " && f_regdate<=".$toDateInt;
				}
				if($searchCoin != ''){
					$where .= " && (f_target='".$searchCoin."' OR f_base='".$searchCoin."')";
				}
				if($orderType != ''){
					$where .= " && f_type='".$orderType."'";
				}
				$query =  "SELECT * FROM tb_market_history WHERE".$where." ORDER BY f_regdate DESC";
				$myOrderHistory = $this->Crud_Model->Get_Sql_Result($query);
				if(count($myOrderHistory)>0){
					foreach ($myOrderHistory as $row) {
						if($row['f_type'] == 'buy'){
							$order_type = $langData['buy'][$lang];
						}else{
							$order_type = $langData['sell'][$lang];
						}
						$market = $row['f_target'].'-'.$row['f_base'];
						$fee = $row['f_base_volume'] * 0.15 / 100;
						$fee = number_format($fee, 2, '.', ',').$row['f_base'];
						$return_data[] = array(
							date('Y-m-d H:i:s', $row['f_regdate']),
							$searchCoin,
							$market,
							$order_type,
							number_format($row['f_target_volume'], 8, '.', '').' <span class="span-grey span-small">'.$row['f_target'].'</span>',
							number_format($row['f_rate']).' <span class="span-grey span-small">KRW</span>',
							number_format($row['f_base_volume']).' <span class="span-grey span-small">KRW</span>',
							$fee,
						);
						$count++;
					}	
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
			}else{
				$key_data = [];
				$key_data['f_enabled'] = 1;
				$coinList = $this->Crud_Model->Get_Sub_Data('tb_unit', $key_data);
				$count = 0;
				foreach ($coinList as $coin) {
					$where = " `f_token`='".$token."' && f_target='".$coin['f_unit']."'";
					if($fromDate != ''){
						$where .= " && f_regdate>=".$fromDateInt;
					}
					if($toDate != ''){
						$where .= " && f_regdate<=".$toDateInt;
					}
					if($orderType != ''){
						$where .= " && f_type='".$orderType."'";
					}
					$query =  "SELECT * FROM tb_market_history WHERE".$where." ORDER BY f_regdate DESC";
					$myOrderHistory = $this->Crud_Model->Get_Sql_Result($query);
					if(count($myOrderHistory)>0){
						foreach ($myOrderHistory as $row) {
							if($row['f_type'] == 'buy'){
								$order_type = $langData['buy'][$lang];
							}else{
								$order_type = $langData['sell'][$lang];
							}					
							$market = $row['f_target'].'-'.$row['f_base'];
							$fee = $row['f_base_volume'] * 0.15 / 100;
							$fee = number_format($fee, 2, '.', ',').$row['f_base'];
							$return_data[] = array(
								date('Y-m-d H:i:s', $row['f_regdate']),
								$coin['f_unit'],
								$market,
								$order_type,
								number_format($row['f_target_volume'], 3, '.', '').' <span class="span-grey span-small">'.$row['f_target'].'</span>',
								number_format($row['f_rate']).' <span class="span-grey span-small">KRW</span>',
								number_format($row['f_base_volume']).' <span class="span-grey span-small">KRW</span>',
								$fee,
								'',
								''
							);
							$count++;
						}	
					}
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
						'0 Result'
					);
				}
			}	
			$output = array(
				"data" => $return_data
			);
			echo json_encode($output);
		}else{
			echo false;
		}
	}	

	public function getMyMarketHistoryCount($token = ''){
		if ($this->is_authenticate()) {
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$searchCoin = $this->input->get('coin');
			$orderType = $this->input->get('orderType');
			if($searchCoin != ''){
				$where = " `f_token`='".$token."'";
				if($fromDate != ''){
					$where .= " && f_regdate>=".$fromDateInt;
				}
				if($toDate != ''){
					$where .= " && f_regdate<=".$toDateInt;
				}
				if($searchCoin != ''){
					$where .= " && f_target='".$searchCoin."'";
				}
				if($orderType != ''){
					$where .= " && f_type='".$orderType."'";
				}
				$query =  "SELECT COUNT(*) AS sub_count FROM tb_market_history WHERE".$where;
				$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
				$count = (int)$result_data['sub_count'];
			}else{
				$key_data = [];
				$key_data['f_enabled'] = 1;
				$coinList = $this->Crud_Model->Get_Sub_Data('tb_unit', $key_data);
				$count = 0;
				foreach ($coinList as $coin) {
					$where = " `f_token`='".$token."' && f_target='".$coin['f_unit']."'";
					if($fromDate != ''){
						$where .= " && f_regdate>=".$fromDateInt;
					}
					if($toDate != ''){
						$where .= " && f_regdate<=".$toDateInt;
					}
					if($searchCoin != ''){
						$where .= " && f_target='".$searchCoin."'";
					}
					if($orderType != ''){
						$where .= " && f_type='".$orderType."'";
					}
					$query =  "SELECT COUNT(*) AS sub_count FROM tb_market_history WHERE".$where;
					$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
					$count += (int)$result_data['sub_count'];
				}
			}		
			echo json_encode($count);
		}else{
			echo false;
		}
	}

	public function getSKYPoolHistory($token = ''){
		if ($this->is_authenticate()) {
			ini_set('memory_limit', '1024M');
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$count = 0;
			$where = " `f_token`='".$token."'";
			if($fromDate != ''){
				$where .= " && f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && f_regdate<=".$toDateInt;
			}
			$query =  "SELECT * FROM tb_log_sky_pool WHERE".$where." ORDER BY f_regdate DESC";
			$mySKYPoolHistory = $this->Crud_Model->Get_Sql_Result($query);
			if(count($mySKYPoolHistory)>0){
				foreach ($mySKYPoolHistory as $row) {
					$percentHtml = number_format($row['f_effect_percent'], 2, '.', '').'%';
					$return_data[] = array(
						date('Y-m-d H:i:s', $row['f_regdate']),
						number_format($row['f_user_day_base_volume']).' <span class="span-grey span-small">KRW</span>',
						number_format($row['f_day_base_volume']).' <span class="span-grey span-small">KRW</span>',
						$percentHtml,
						number_format($row['f_daily_sky_pool_volume']).' <span class="span-grey span-small">SKY</span>',
						number_format($row['f_user_day_sky_pool_volume']).' <span class="span-grey span-small">SKY</span>'
					);
					$count++;
				}	
			}
			if($count == 0){
				$return_data[] = array(
					'',
					'',
					'',
					'',
					'',
					'',
					'0 Result'
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

	public function getSKYPoolHistoryCount($token = ''){
		if ($this->is_authenticate()) {
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$where = " `f_token`='".$token."'";
			if($fromDate != ''){
				$where .= " && f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && f_regdate<=".$toDateInt;
			}
			$query =  "SELECT COUNT(*) AS sub_count FROM tb_log_sky_pool WHERE".$where;
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$count = (int)$result_data['sub_count'];
			echo json_encode($count);
		}else{
			echo false;
		}
	}

	public function getETHairdropHistory($token = ''){
		if ($this->is_authenticate()) {
			ini_set('memory_limit', '1024M');
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$count = 0;
			$where = " `f_token`='".$token."'";
			if($fromDate != ''){
				$where .= " && f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && f_regdate<=".$toDateInt;
			}
			$query =  "SELECT * FROM tb_log_eth_airdrop WHERE".$where." ORDER BY f_regdate DESC";
			$myETHairdropHistory = $this->Crud_Model->Get_Sql_Result($query);
			if(count($myETHairdropHistory)>0){
				foreach ($myETHairdropHistory as $row) {
					$feehtml = number_format($row['f_fee'], 2, '.', '').'%';
					$return_data[] = array(
						date('Y-m-d', $row['f_regdate']),
						// number_format($row['f_day_base_volume']).' <span class="span-grey span-small">KRW</span>',
						// $feehtml,
						// number_format($row['f_day_airdrop_base_volume']).' <span class="span-grey span-small">KRW</span>',
						// number_format($row['f_eth_rate']).' <span class="span-grey span-small">KRW</span>',
						// number_format($row['f_day_eth_volume'], 8, '.', '').' <span class="span-grey span-small">ETH</span>',
						number_format($row['f_user_sky_balance']).' <span class="span-grey span-small">SKY</span>',
						number_format($row['f_eth_airdrop_volume'], 8, '.', '').' <span class="span-grey span-small">ETH</span>'
					);
					$count++;
				}	
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
					'0 Result'
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

	public function getETHairdropHistoryCount($token = ''){
		if ($this->is_authenticate()) {
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$where = " `f_token`='".$token."'";
			if($fromDate != ''){
				$where .= " && f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && f_regdate<=".$toDateInt;
			}
			$query =  "SELECT COUNT(*) AS sub_count FROM tb_log_eth_airdrop WHERE".$where;
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$count = (int)$result_data['sub_count'];
			echo json_encode($count);
		}else{
			echo false;
		}
	}

	public function getDeptWithHistory($token = ''){
		if ($this->is_authenticate()) {
			if($this->session->userdata('coinsky_lang') == null){
				$lang = 'KO';
			}else{
				$lang = $this->session->userdata('coinsky_lang');
			}
			$langData = $this->Crud_Model->Get_Lang_Values();

			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$searchCoin = $this->input->get('coin');
			$orderType = $this->input->get('orderType');
			$where = " `f_token`='".$token."'";
			if($fromDate != ''){
				$where .= " && f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && f_regdate<=".$toDateInt;
			}
			if($searchCoin != ''){
				$where .= " && f_unit='".$searchCoin."'";
			}
			if($orderType != ''){
				$where .= " && f_type='".$orderType."'";
			}
			$count = 0;			
			$query =  "SELECT * FROM tb_log_user_deposit_withdraw WHERE".$where." ORDER BY f_regdate DESC";
			$deptWithHistory = $this->Crud_Model->Get_Sql_Result($query);
			if(count($deptWithHistory)>0){
				foreach ($deptWithHistory as $row) {
					if($row['f_type'] == 'deposit'){
						$className="span-red";
						$order_type = $langData['deposit'][$lang];
					}else{
						$className="span-blue";
						$order_type = $langData['withdraw'][$lang];
					}
					if($row['f_unit'] == 'KRW'){
						$amount = number_format($row['f_amount']);
						$fee = number_format($row['f_fees']);
					}else{
						$amount = number_format($row['f_amount'], 8, '.', '');
						$fee = number_format($row['f_fees'], 8, '.', '');
					}
					if($row['f_status'] == 1){
						$status = $langData['return-msg-msg-29'][$lang];
					}else{
						$status = $langData['return-msg-msg-30'][$lang];
					}
					if($row['f_type'] == 'withdraw' && $row['f_unit'] == 'BTC'){
						$detail = '<span class="span-under-line ' . $className . '" style="cursor:pointer;" onclick="window.open('."'".'https://www.blockchain.com/btc/address/'.$row['f_detail']."'".')">' . $row['f_detail'] . '</span>';
					}else if ($row['f_unit'] == 'ETH') {
						$detail = '<span class="span-under-line ' . $className . '" style="cursor:pointer;" onclick="window.open(' . "'" . 'https://etherscan.io/address/' . $row['f_detail'] . "'" . ')">' . $row['f_detail'] . '</span>';
					}else if ($row['f_unit'] == 'SKY') {
						$detail = '<span class="span-under-line ' . $className . '" style="cursor:pointer;" onclick="window.open(' . "'" . 'https://etherscan.io/address/' . $row['f_detail'] . "'" . ')">' . $row['f_detail'] . '</span>';
					} else if ($row['f_unit'] == 'BDR') {
						$detail = '<span class="span-under-line ' . $className . '" style="cursor:pointer;" onclick="window.open(' . "'" . 'https://etherscan.io/address/' . $row['f_detail'] . "'" . ')">' . $row['f_detail'] . '</span>';
					} else{
						$detail = '<span class="' . $className . '">' . $row['f_detail'] . '</span>';
					}
					$return_data[] = array(
						date('Y-m-d H:i:s', $row['f_regdate']),
						$row['f_unit'],
						'<span class="'.$className.'">'.$order_type.'</span>',
						'<span class="'.$className.'">'.$amount.'</span>',
						'<span class="'.$className.'">'.$fee.'</span>',
						$detail,
						$status
					);
					$count++;
				}	
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

	public function getDeptWithHistoryCount($token = ''){
		if ($this->is_authenticate()) {
			$fromDate = $this->input->get('fromDate');
			$fromDateInt = strtotime($fromDate);
			$toDate = $this->input->get('toDate');
			$toDateInt = strtotime($toDate);
			$toDateInt = $toDateInt + 86400;
			$searchCoin = $this->input->get('coin');
			$orderType = $this->input->get('orderType');
			$where = " `f_token`='".$token."'";
			if($fromDate != ''){
				$where .= " && f_regdate>=".$fromDateInt;
			}
			if($toDate != ''){
				$where .= " && f_regdate<=".$toDateInt;
			}
			if($searchCoin != ''){
				$where .= " && f_unit='".$searchCoin."'";
			}
			if($orderType != ''){
				$where .= " && f_type='".$orderType."'";
			}
			$query =  "SELECT COUNT(*) AS sub_count FROM tb_log_user_deposit_withdraw WHERE".$where;
			$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($query);
			$count = (int)$result_data['sub_count'];	
			echo json_encode($count);
		}else{
			echo false;
		}
	}

	public function getBalance(){
		if ($this->is_authenticate()) {
			$key_data = [];
			$key_data['f_token'] = $this->session->userdata('token');
			$balanceData = $this->Crud_Model->Get_Sub_Data('tb_user_wallet', $key_data);
			$count = 0;
			foreach ($balanceData as $key => $value) {
				$returnData[$count]['unit'] = $value['f_unit'];
				if($value['f_unit'] == 'KRW'){
					$balance = $value['f_total'];
				}else{
					$sql = "SELECT f_close FROM tb_market WHERE f_target='".$value['f_unit']."' && f_base='KRW'";
					$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
					if(isset($result_data['f_close'])){
						$rate = $result_data['f_close'];
					}else{
						$rate = 0;
					}
					$balance = $value['f_total']*$rate;
				}
				$returnData[$count]['balance'] = $balance;
				$count++;
			}
			echo json_encode($returnData);
		}else{
			echo false;
		}
	}
}
