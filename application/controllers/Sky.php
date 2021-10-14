<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sky extends CI_Controller {

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

	public function index()
	{
		redirect(base_url().'sky/publ');
	}

	public function publ(){

		$page_data['page'] = 'publ';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$sql = "SELECT SUM(f_last_day_base_volume) AS lastDayTotalBaseVolume, SUM(f_day_base_volume) AS todayTotalBaseVolume  FROM tb_market";
		$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
		$lastDayTotalBaseVolume = $result_data['lastDayTotalBaseVolume'];
		$todayTotalBaseVolume = $result_data['todayTotalBaseVolume'];
		
		$sql = "SELECT f_close FROM tb_market WHERE f_target='ETH' && f_base='KRW'";
		$result_data = $this->Crud_Model->Get_A_Row_Sql_Result($sql);
		$ETH_rate = $result_data['f_close'];

		if($ETH_rate == 0){
			$lastDayTotalETHVolume = 0;
			$todayTotalETHVolume = 0;
			$lastDayETHSKYRate = 0;
			$todayETHSKYRate = 0;
			$lastDayEarningETH = 0;
			$todayEarningETH = 0;
		}else{
			$lastDayTotalETHVolume = $lastDayTotalBaseVolume/$ETH_rate;
			if($lastDayTotalETHVolume > 0){
				$lastDayETHSKYRate = 6314422 / $lastDayTotalETHVolume;
			}else{
				$lastDayETHSKYRate = 0;
			}
			$todayTotalETHVolume = $todayTotalBaseVolume/$ETH_rate;
			if ($todayTotalETHVolume > 0) {
				$todayETHSKYRate = 6314422 / $todayTotalETHVolume;
			} else {
				$todayETHSKYRate = 0;
			}			
			$lastDayEarningETH = $lastDayTotalBaseVolume * 0.15 * 0.7 / $ETH_rate;
			$todayEarningETH = $todayTotalBaseVolume * 0.15 * 0.7 / $ETH_rate;
		}

		$sql = "SELECT f_regdate  FROM tb_log_sky_pool GROUP BY f_regdate";
		$result_data = $this->Crud_Model->Get_Sql_Result($sql);
		$lastDaySKYPoolVolume = count($result_data) * 6314422;
		$todaySKYPoolVolume = $lastDaySKYPoolVolume + 6314422;

		if($lastDaySKYPoolVolume > 0){
			$lastDaySKYETHRate = $lastDayEarningETH / $lastDaySKYPoolVolume;
		}else{
			$lastDaySKYETHRate = 0;
		}
		if($todaySKYPoolVolume > 0){
			$todaySKYETHRate = $todayEarningETH / $todaySKYPoolVolume;
		}else{
			$todaySKYETHRate = 0;
		}
		
		$currentTime = time();
		$currentTime = $currentTime + 3600*8;
		$tempTime = $currentTime % 86400;
		$remainTime = 86400 - $tempTime;

		$page_data['lastDayTotalETHVolume'] = number_format($lastDayTotalETHVolume, 8 , '.', ',');
		$page_data['todayTotalETHVolume'] = number_format($todayTotalETHVolume, 8 , '.', ',');
		$page_data['lastDayETHSKYRate'] = number_format($lastDayETHSKYRate, 8 , '.', ',');
		$page_data['todayETHSKYRate'] = number_format($todayETHSKYRate, 8 , '.', ',');
		$page_data['lastDaySKYPoolVolume'] = number_format($lastDaySKYPoolVolume, 8 , '.', ',');
		$page_data['todaySKYPoolVolume'] = number_format($todaySKYPoolVolume, 8 , '.', ',');
		$page_data['lastDayEarningETH'] = number_format($lastDayEarningETH, 8 , '.', ',');
		$page_data['todayEarningETH'] = number_format($todayEarningETH, 8 , '.', ',');
		$page_data['lastDaySKYETHRate'] = number_format($lastDaySKYETHRate, 8 , '.', ',');
		$page_data['todaySKYETHRate'] = number_format($todaySKYETHRate, 8 , '.', ',');
		$page_data['remainTime'] = $remainTime;
		$this->load->view('temp/header',$page_data);
		$this->load->view('sky/publ', $page_data);
		$this->load->view('temp/footer', $page_data);

	}

	public function drop(){

		$page_data['page'] = 'drop';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('sky/drop', $page_data);
		$this->load->view('temp/footer', $page_data);

	}

	public function info(){

		$page_data['page'] = 'info';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('sky/info', $page_data);
		$this->load->view('temp/footer', $page_data);

	}
}
