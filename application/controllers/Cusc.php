<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cusc extends CI_Controller {

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
		redirect(base_url().'cusc/notc');
	}

	public function notc(){

		$page_data['page'] = 'notc';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/notc', $page_data);
		$this->load->view('temp/footer', $page_data);

	}

	public function term(){

		$page_data['page'] = 'term';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/term', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function fees(){
		
		$page_data['page'] = 'fees';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/fees', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function priv(){
		
		$page_data['page'] = 'priv';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/priv', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function faq(){
		
		$page_data['page'] = 'faq';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/faq', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function quiz(){
		
		$page_data['page'] = 'quiz';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/quiz', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function code(){
		
		$page_data['page'] = 'code';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/code', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function eror(){
		
		$page_data['page'] = 'eror';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/eror', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function finc(){
		
		$page_data['page'] = 'finc';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/finc', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function provision(){
		
		$page_data['page'] = 'provision';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/provision', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}

	public function privacy(){
		
		$page_data['page'] = 'privacy';
			
		if($this->session->userdata('coinsky_lang') == null){
			$lang = 'KO';
		}else{
			$lang = $this->session->userdata('coinsky_lang');
		}
		$langData = $this->Crud_Model->Get_Lang_Values();
		$page_data['langData'] = $langData;
		$page_data['lang'] = $lang;

		$this->load->view('temp/header',$page_data);
		$this->load->view('cusc/privacy', $page_data);
		$this->load->view('temp/footer', $page_data);
		
	}
}
