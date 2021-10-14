<?php
class Crud_Model extends CI_Model {
	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}
	function Insert_Data($tb_name, $insert_data){
		$result = $this->db->insert($tb_name, $insert_data);
		return $result;
	}
	function Insert_Data_Get_Id($tb_name, $insert_data){
		$this->db->insert($tb_name, $insert_data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	function Check_Row_Exist($tb_name, $field_name, $item){
		$this->db->where($field_name, $item);
		$query = $this->db->get($tb_name);
		$result = $query->num_rows();
		if($result>0){
			return true;
		}else{
			return false;
		}
	}
	function Check_Row_Exist_With_Key($tb_name, $key_data){
		foreach ($key_data as $key => $value) {
			$this->db->where($key, $value);
		}
		$query = $this->db->get($tb_name);
		$result = $query->num_rows();
		if($result>0){
			return true;
		}else{
			return false;
		}
	}
	function Update_Data($tb_name, $key_data, $update_data){
		foreach ($key_data as $key => $value) {
			$this->db->where($key, $value);
		}
		$result = $this->db->update($tb_name, $update_data);
		return $result;
	}
	function Delete_A_Row($tb_name, $key_data){
		foreach ($key_data as $key => $value) {
			$this->db->where($key, $value);
		}
		$result = $this->db->delete($tb_name);
		return $result;
	}
	function Get_A_Row_Data($tb_name, $key_data){
		foreach ($key_data as $key => $value) {
			$this->db->where($key, $value);
		}
		$query = $this->db->get($tb_name);
		$result = $query->row_array();
		return $result;
	}
	function Get_Sub_Data($tb_name, $key_data){
		foreach ($key_data as $key => $value) {
			$this->db->where($key, $value);
		}
		$query = $this->db->get($tb_name);
		$result = $query->result_array();
		return $result;
	}
	function Get_An_Element($tb_name, $key_data, $field){
		foreach ($key_data as $key => $value) {
			$this->db->where($key, $value);
		}
		$query = $this->db->get($tb_name);
		$result = $query->row_array();
		return $result[$field];
	}
	function Get_System_Values(){
		$query = $this->db->get('tb_config');
		$result = $query->result_array();
		foreach ($result as $row) {
			$return_data[$row['f_title']] = $row['f_value'];
		}
		return $return_data;
	}
	function Get_Lang_Values(){
		$query = $this->db->get('tb_lang');
		$result = $query->result_array();
		foreach ($result as $row) {
			$return_data[$row['f_key']]['KO'] = $row['KO'];
			$return_data[$row['f_key']]['EN'] = $row['EN'];
			$return_data[$row['f_key']]['CN'] = $row['CN'];
		}
		return $return_data;
	}
	function Get_All_Table_Data($tb_name){
		$query = $this->db->get($tb_name);
		$result = $query->result_array();
		return $result;
	}
	function Get_Sum_Of_Field($tb_name, $f_field){
		$this->db->select('sum(`'.$f_field.'`)');
		$result = $this->db->get($tb_name)->row_array();  
		return $result['sum(`'.$f_field.'`)'];
	}
	function Get_Count_From_Table($tb_name, $key_data){
		foreach ($key_data as $key => $value) {
			$this->db->where($key, $value);
		}
		$query = $this->db->get($tb_name);
		$result = $query->num_rows();
		return $result;
	}
	function Get_Sql_Result($sql){
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}

	function Get_A_Row_Sql_Result($sql){
		$query = $this->db->query($sql);
		$result = $query->row_array();
		return $result;
	}
	function Get_Site_Wallet(){
		$query = $this->db->get('tb_site_profit');
		$result = $query->result_array();
		$count = 0;
		foreach ($result as $row) {
			$count++;
			$return_data[$row['f_unit']] = $row['f_amount'];
		}
		if($count == 0) $return_data = [];
		return $return_data;
	}
	function Get_Query_Run_Result($sql){
		$result = $this->db->query($sql);
		return $result;
	}
	
	///// add new /////
	function Is_Query_Count ($target , $base , $token , $action) {
		
		$this->db->select('*');
		$this->db->from('tb_query');
		$this->db->where('f_token' , $token);
		$this->db->where('f_action' , $action);
		$this->db->like('f_data' , '"target":"'.$target.'"');
		$this->db->like('f_data' , '"base":"'.$base.'"');
		
		if($action == 'create_order')
			$this->db->like('f_data' , '"order_place":"order"');
		
		$result = $this->db->get()->result_array();
		
		if(count($result) < 3)
			return TRUE;
		else
			return FALSE;
		
	}
	
	function removePriceOrder($target , $base , $token) {
		
		$this->db->where('f_token' , $token);
		$this->db->like('f_data' , '"target":"'.$target.'"');
		$this->db->like('f_data' , '"base":"'.$base.'"');
		$this->db->like('f_data' , '"order_place":"price"');
		
		return $this->db->delete('tb_query');
		
	}
	
	
}
