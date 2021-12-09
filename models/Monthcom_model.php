<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * Summonth_model Class
 * @date 2021-10-19
 */
class Monthcom_model extends MY_Model
{

	private $my_table;
	public $session_name;
	public $order_field;
	public $order_sort;
	public $owner_record;

	public function __construct()
	{
		parent::__construct();
		$this->my_table = 'tb_summonth';
		$this->set_table_name($this->my_table);
		$this->order_field = '';
		$this->order_sort = '';
	}


	public function exists($data)
	{
		$monthpr_id = checkEncryptData($data['monthpr_id']);
		$this->set_table_name($this->my_table);
		$this->set_where("$this->my_table.monthpr_id = $monthpr_id");
		return $this->count_record();
	}


	public function load($id)
	{
		$this->set_table_name($this->my_table);
		$this->set_where("$this->my_table.monthpr_id = $id");
		$lists = $this->load_record();
		return $lists;
	}


	public function create($post)
	{

		$data = array(
				'yearpay' => $post['yearpay']
				,'monthpay' => $post['monthpay']
				,'rf_name_id' => $post['rf_name_id']
				,'month_mony_sso' => str_replace(",", "", $post['month_mony_sso'])
				,'month_de_ssop' => str_replace(",", "", $post['month_de_ssop'])
				,'month_de_ssoc' => str_replace(",", "", $post['month_de_ssoc'])
		);
		$this->set_table_name($this->my_table);
		return $this->add_record($data);
	}


	/**
	* List all data
	* @param $start_row	Number offset record start
	* @param $per_page	Number limit record perpage
	*/
	public function read($start_row = FALSE, $per_page = FALSE)
	{
		$search_field 	= $this->session->userdata($this->session_name . '_search_field');
		$value 	= $this->session->userdata($this->session_name . '_value');
		$value 	= trim($value);
		$paynum = $this->session->userdata($this->session_name . '_paynum');
		$payyear = $this->session->userdata($this->session_name . '_payyear');
		
		$where	= '';
		$order_by	= '';
		if($this->order_field != ''){
			$order_field = $this->order_field;
			$order_sort = $this->order_sort;
			$order_by	= " $this->my_table.$order_field $order_sort";
		}
		
		if($search_field != '' && $value != ''){
			$search_method_field = "$this->my_table.$search_field";
			$search_method_value = '';

			if($search_field == 'rf_company'){
				$search_method_field = "tb_branch_4.rf_company_Id";
				$search_method_value = "LIKE '%$value%'";				
			}

			$where	.= ($where != '' ? ' AND ' : '') . " $search_method_field $search_method_value AND monthpay ='$paynum' AND yearpay ='$payyear'"; 

			if($order_by == ''){
				$order_by	= "";
			}
		}
		$this->set_table_name($this->my_table);
		$total_row = $this->count_record();
		$search_row = $total_row;
		if ($where != '') {
			$this->db->join('tb_paymonth AS tb_paymonth_1', "$this->my_table.monthpay = tb_paymonth_1.paymonth_id", 'left');
			$this->db->join('tb_person AS tb_person_2', "$this->my_table.rf_name_id = tb_person_2.name_id", 'left');
			$this->db->join('tb_pername AS tb_pername_3', "tb_person_2.rf_pre_id = tb_pername_3.pre_id", 'left');
			$this->db->join('tb_branch AS tb_branch_4', "tb_person_2.rf_branch_id = tb_branch_4.branch_id", 'left');
			$this->db->join('tb_company AS tb_company_5', "tb_branch_4.rf_company_Id = tb_company_5.company_id ", 'left');

			$this->set_where($where);
			$search_row = $this->count_record();
		}
		$offset = $start_row;
		$limit = $per_page;
		$this->set_order_by($order_by);
		if($offset != FALSE){
			$this->set_offset($offset);
		}
		if($limit != FALSE){
			$this->set_limit($limit);
		}
		$this->db->select("$this->my_table.*
				, tb_paymonth_1.paymonth_id AS monthpayPaymonthId
				, tb_paymonth_1.paymonth AS monthpayPaymonth
				, tb_person_2.emp_name AS rfNameIdEmpName
				, tb_person_2.emp_surname AS rfNameIdEmpSurname
				, tb_person_2.start_date AS rfNameIdStartDate
				, tb_pername_3.pre_name AS rfPernameIdPreName
				, tb_branch_4.branch_nick AS rfBranchIdBranchNick
				, tb_company_5.company_name AS rfCompanyIdCompanyName
				, tb_company_5.company_nick AS rfCompanyIdCompanyNick
				");
		$this->db->join('tb_paymonth AS tb_paymonth_1', "$this->my_table.monthpay = tb_paymonth_1.paymonth_id", 'left');
		$this->db->join('tb_person AS tb_person_2', "$this->my_table.rf_name_id = tb_person_2.name_id", 'left');
		$this->db->join('tb_pername AS tb_pername_3', "tb_person_2.rf_pre_id = tb_pername_3.pre_id", 'left');
		$this->db->join('tb_branch AS tb_branch_4', "tb_person_2.rf_branch_id = tb_branch_4.branch_id", 'left');
		$this->db->join('tb_company AS tb_company_5', "tb_branch_4.rf_company_Id = tb_company_5.company_id ", 'left');

		$list_record = $this->list_record();
		$data = array(
				'total_row'	=> $total_row, 
				'search_row'	=> $search_row,
				'list_data'	=> $list_record
		);
		return $data;
	}

	public function sumexcel($start_row = FALSE, $per_page = FALSE)
	{
		$search_field 	= $this->session->userdata($this->session_name . '_search_field');
		$value 	= $this->session->userdata($this->session_name . '_value');
		$value 	= trim($value);
		$paynum = $this->session->userdata($this->session_name . '_paynum');
		$payyear = $this->session->userdata($this->session_name . '_payyear');
		
		$where	= '';
		$order_by	= '';
		if($this->order_field != ''){
			$order_field = $this->order_field;
			$order_sort = $this->order_sort;
			$order_by	= " $this->my_table.$order_field $order_sort";
		}
		
		if($search_field != '' && $value != ''){
			$search_method_field = "$this->my_table.$search_field";
			$search_method_value = '';

			if($search_field == 'rf_company'){
				$search_method_field = "tb_branch_4.rf_company_Id";
				$search_method_value = "LIKE '%$value%'";				
			}

			$where	.= ($where != '' ? ' AND ' : '') . " $search_method_field $search_method_value AND monthpay ='$paynum' AND yearpay ='$payyear'"; 

			if($order_by == ''){
				$order_by	= "";
			}
		}
		$this->set_table_name($this->my_table);
		$total_row = $this->count_record();
		$search_row = $total_row;
		if ($where != '') {
			$this->db->join('tb_paymonth AS tb_paymonth_1', "$this->my_table.monthpay = tb_paymonth_1.paymonth_id", 'left');
			$this->db->join('tb_person AS tb_person_2', "$this->my_table.rf_name_id = tb_person_2.name_id", 'left');
			$this->db->join('tb_pername AS tb_pername_3', "tb_person_2.rf_pre_id = tb_pername_3.pre_id", 'left');
			$this->db->join('tb_branch AS tb_branch_4', "tb_person_2.rf_branch_id = tb_branch_4.branch_id", 'left');
			
			$this->set_where($where);
			$search_row = $this->count_record();
		}
		$offset = $start_row;
		$limit = $per_page;
		$this->set_order_by($order_by);
		if($offset != FALSE){
			$this->set_offset($offset);
		}
		if($limit != FALSE){
			$this->set_limit($limit);
		}
		$this->db->select("$this->my_table.*
				, tb_paymonth_1.paymonth_id AS monthpayPaymonthId
				, tb_paymonth_1.paymonth AS monthpayPaymonth
				, tb_person_2.emp_name AS rfNameIdEmpName
				, tb_person_2.emp_surname AS rfNameIdEmpSurname
				, tb_person_2.start_date AS rfNameIdStartDate
				, tb_pername_3.pre_name AS rfPernameIdPreName
				, tb_branch_4.branch_nick AS rfBranchIdBranchNick
				");
		$this->db->join('tb_paymonth AS tb_paymonth_1', "$this->my_table.monthpay = tb_paymonth_1.paymonth_id", 'left');
		$this->db->join('tb_person AS tb_person_2', "$this->my_table.rf_name_id = tb_person_2.name_id", 'left');
		$this->db->join('tb_pername AS tb_pername_3', "tb_person_2.rf_pre_id = tb_pername_3.pre_id", 'left');
		$this->db->join('tb_branch AS tb_branch_4', "tb_person_2.rf_branch_id = tb_branch_4.branch_id", 'left');
		$this->db->select_sum("$this->my_table.month_mony_sso");
		$this->db->select_sum("$this->my_table.month_de_ssop");
		$this->db->select_sum("$this->my_table.month_de_ssoc");

		$list_record = $this->list_record();
		$data = array(
				'total_row'	=> $total_row, 
				'search_row'	=> $search_row,
				'list_data'	=> $list_record
		);
		return $data;
	}

	public function pdf_sso($start_row = FALSE, $per_page = FALSE)
	{
		$search_field 	= $this->session->userdata($this->session_name . '_search_field');
		$value 	= $this->session->userdata($this->session_name . '_value');
		$value 	= trim($value);
		$paynum = $this->session->userdata($this->session_name . '_paynum');
		$payyear = $this->session->userdata($this->session_name . '_payyear');
		
		$where	= '';
		$order_by	= '';
		if($this->order_field != ''){
			$order_field = $this->order_field;
			$order_sort = $this->order_sort;
			$order_by	= " $this->my_table.$order_field $order_sort";
		}
		
		if($search_field != '' && $value != ''){
			$search_method_field = "$this->my_table.$search_field";
			$search_method_value = '';

			if($search_field == 'rf_company'){
				$search_method_field = "tb_branch_4.rf_company_Id";
				$search_method_value = "LIKE '%$value%'";				
			}

			$where	.= ($where != '' ? ' AND ' : '') . " $search_method_field $search_method_value AND monthpay ='$paynum' AND yearpay ='$payyear'"; 

			if($order_by == ''){
				$order_by	= "";
			}
		}
		$this->set_table_name($this->my_table);
		$total_row = $this->count_record();
		$search_row = $total_row;
		if ($where != '') {
			$this->db->join('tb_paymonth AS tb_paymonth_1', "$this->my_table.monthpay = tb_paymonth_1.paymonth_id", 'left');
			$this->db->join('tb_person AS tb_person_2', "$this->my_table.rf_name_id = tb_person_2.name_id", 'left');
			$this->db->join('tb_pername AS tb_pername_3', "tb_person_2.rf_pre_id = tb_pername_3.pre_id", 'left');
			$this->db->join('tb_branch AS tb_branch_4', "tb_person_2.rf_branch_id = tb_branch_4.branch_id", 'left');
			$this->db->join('tb_company AS tb_company_5', "tb_branch_4.rf_company_Id = tb_company_5.company_id", 'left');

			$this->set_where($where);
			$search_row = $this->count_record();
		}
		$offset = $start_row;
		$limit = $per_page;
		$this->set_order_by($order_by);
		if($offset != FALSE){
			$this->set_offset($offset);
		}
		if($limit != FALSE){
			$this->set_limit($limit);
		}
		$this->db->select("$this->my_table.* ,$this->my_table.month_mony_sso AS total_mony_sso
				, tb_paymonth_1.paymonth_id AS monthpayPaymonthId
				, tb_paymonth_1.paymonth AS monthpayPaymonth
				, tb_person_2.emp_name AS rfNameIdEmpName
				, tb_person_2.emp_surname AS rfNameIdEmpSurname
				, tb_person_2.num_card AS rfNameIdNumCard
				, tb_pername_3.pre_name AS rfPernamePreName
				, tb_branch_4.branch_name AS rfBranchIdBranchName
				, tb_branch_4.branch_code AS rfBranchIdBranchCode
				, tb_branch_4.branch_social AS rfBranchIdBranchSocial
				, tb_company_5.company_name AS rfBranchIdCompanyName
				, tb_company_5.social_account AS rfBranchIdSocialAccount
				");
		$this->db->join('tb_paymonth AS tb_paymonth_1', "$this->my_table.monthpay = tb_paymonth_1.paymonth_id", 'left');
		$this->db->join('tb_person AS tb_person_2', "$this->my_table.rf_name_id = tb_person_2.name_id", 'left');
		$this->db->join('tb_pername AS tb_pername_3', "tb_person_2.rf_pre_id = tb_pername_3.pre_id", 'left');
		$this->db->join('tb_branch AS tb_branch_4', "tb_person_2.rf_branch_id = tb_branch_4.branch_id", 'left');
		$this->db->join('tb_company AS tb_company_5', "tb_branch_4.rf_company_Id = tb_company_5.company_id", 'left');
		$this->db->group_by("$this->my_table.rf_name_id");

		$list_record = $this->list_record();
		$data = array(
				'total_row'	=> $total_row, 
				'search_row'	=> $search_row,
				'list_data'	=> $list_record
		);
		return $data;
	}

	public function pdf_tax($start_row = FALSE, $per_page = FALSE)
	{
		$search_field 	= $this->session->userdata($this->session_name . '_search_field');
		$value 	= $this->session->userdata($this->session_name . '_value');
		$value 	= trim($value);
		$paynum = $this->session->userdata($this->session_name . '_paynum');
		$payyear = $this->session->userdata($this->session_name . '_payyear');
		
		$where	= '';
		$order_by	= '';
		if($this->order_field != ''){
			$order_field = $this->order_field;
			$order_sort = $this->order_sort;
			$order_by	= " $this->my_table.$order_field $order_sort";
		}
		
		if($search_field != '' && $value != ''){
			$search_method_field = "$this->my_table.$search_field";
			$search_method_value = '';
			if($search_field == 'rf_company'){
				$search_method_field = "tb_branch_4.rf_company_Id";
				$search_method_value = "LIKE '%$value%'";				
			}

			$where	.= ($where != '' ? ' AND ' : '') . " $search_method_field $search_method_value AND monthpay ='$paynum' AND yearpay ='$payyear'"; 

			if($order_by == ''){
				$order_by	= "";
			}
		}
		$this->set_table_name($this->my_table);
		$total_row = $this->count_record();
		$search_row = $total_row;
		if ($where != '') {
			$this->db->join('tb_paymonth AS tb_paymonth_1', "$this->my_table.monthpay = tb_paymonth_1.paymonth_id", 'left');
		$this->db->join('tb_person AS tb_person_2', "$this->my_table.rf_name_id = tb_person_2.name_id", 'left');

			$this->set_where($where);
			$search_row = $this->count_record();
		}
		$offset = $start_row;
		$limit = $per_page;
		$this->set_order_by($order_by);
		if($offset != FALSE){
			$this->set_offset($offset);
		}
		if($limit != FALSE){
			$this->set_limit($limit);
		}
		$this->db->select("$this->my_table.* ,$this->my_table.month_mony_sso AS total_mony_sso
		, tb_paymonth_1.paymonth_id AS monthpayPaymonthId
		, tb_paymonth_1.paymonth AS monthpayPaymonth
		, tb_person_2.emp_name AS rfNameIdEmpName
		, tb_person_2.emp_surname AS rfNameIdEmpSurname
		, tb_person_2.num_card AS rfNameIdNumCard
		, tb_person_2.rf_typestatus_id AS rfNameIdTypestatusId
		, tb_person_2.person_num_student AS rfNameIdPersonNumStudent
		, tb_person_2.person_num_children AS rfNameIdPersonNumChildren
		, tb_person_2.person_total AS rfNameIdPersonTotal
		, tb_person_2.rf_typetex_id AS rfNameIdTypetexId
		, tb_pername_3.pre_name AS rfPernamePreName
		, tb_branch_4.branch_name AS rfBranchIdBranchName
		, tb_branch_4.branch_code AS rfBranchIdBranchCode
		, tb_company_5.company_name AS rfBranchIdCompanyName
		, tb_company_5.tax_account AS rfBranchIdTaxAccount
		, tb_typeincome_6.income_type AS  rfNameIdTypeincome
		, tb_paypayroll_7.paypr_salary_net AS rfPayprIdSalaryNet
		, tb_paypayroll_7.paypr_de_tax AS rfPayprIdDeTax
		");
		$this->db->join('tb_paymonth AS tb_paymonth_1', "$this->my_table.monthpay = tb_paymonth_1.paymonth_id", 'left');
		$this->db->join('tb_person AS tb_person_2', "$this->my_table.rf_name_id = tb_person_2.name_id", 'left');
		$this->db->join('tb_pername AS tb_pername_3', "tb_person_2.rf_pre_id = tb_pername_3.pre_id", 'left');
		$this->db->join('tb_branch AS tb_branch_4', "tb_person_2.rf_branch_id = tb_branch_4.branch_id", 'left');
		$this->db->join('tb_company AS tb_company_5', "tb_branch_4.rf_company_Id = tb_company_5.company_id", 'left');
		$this->db->join('tb_typeincome AS tb_typeincome_6', "tb_person_2.rf_typeincom_id = tb_typeincome_6.income_id", 'left');
		$this->db->join('tb_paypayroll AS tb_paypayroll_7', "tb_person_2.name_id = tb_paypayroll_7.rf_name_id", 'left');
		$this->db->group_by("$this->my_table.rf_name_id");
		
		$list_record = $this->list_record();
		$data = array(
				'total_row'	=> $total_row, 
				'search_row'	=> $search_row,
				'list_data'	=> $list_record
		);
		return $data;
	}


	public function update($post)
	{
		$data = array(
				'yearpay' => $post['yearpay']
				,'monthpay' => $post['monthpay']
				,'rf_name_id' => $post['rf_name_id']
				,'month_mony_sso' => str_replace(",", "",$post['month_mony_sso'])
				,'month_de_ssop' => str_replace(",", "",$post['month_de_ssop'])
				,'month_de_ssoc' => str_replace(",", "",$post['month_de_ssoc'])
				,'month_ckrun' => $post['month_ckrun']
		);

		if(!empty($data)){
			$monthpr_id = checkEncryptData($post['encrypt_monthpr_id']);
			$this->set_table_name($this->my_table);
			$this->set_where("$this->my_table.monthpr_id = $monthpr_id");
			return $this->update_record($data);
		}else{
			$this->error_message = 'ไม่พบข้อมูลที่เปลี่ยนแปลง';
		}
	}


	public function delete($post)
	{
			$monthpr_id = checkEncryptData($post['encrypt_monthpr_id']);
		$this->set_table_name($this->my_table);
		$this->set_where("$this->my_table.monthpr_id = $monthpr_id");
		return $this->delete_record();
	}


	public function search_table($table, $conditions)
	{
		if($conditions['search_value'] == ''){
			return array();
		}
		$this->set_table_name($table);
		$field1 = $conditions['field_value'];
		$field2 = $conditions['field_text'];
		$field_condition = $conditions['field_condition'];

		if(is_array($field1)){
			$all_field1 = implode(',', $field1);
			$field1 = "CONCAT_WS(' ', $all_field1) AS field_value";
		}else{
			$field1 = "$field1 AS field_value";
		}
		
		if(is_array($field2)){
			$all_field2 = implode(',', $field2);
			$field2 = "CONCAT_WS(' ', $all_field2) AS field_title";
		}else{
			$field2 = "$field2 AS field_title";
		}
		
		if(is_array($field_condition)){
			$all_field = implode(',', $field_condition);
			$field_condition =  "CONCAT_WS('', $all_field)";
		}
		$select = "$field1, $field2, $field_condition AS field_search";
		
		$search_value = $conditions['search_value'];
		
		$search_string = "";
		$search_method = "";
		switch($conditions['search_method']){
			case 'equal':
				$single_qoute = "'";
				if( $search_value[0] == "0" ) {
					$single_qoute = "'";
				}else{
					if (is_numeric($search_value)) {
						$single_qoute = "";
					}
				}
			
				$search_method = '=';
				$search_string = "{$single_qoute}{$search_value}{$single_qoute}";
				break;
			case 'contain':
				$search_method = 'LIKE';
				$search_string = "'%{$search_value}%'";
				$search_value = str_replace('.', '', str_replace(' ', '', $search_value));
				break;
			case 'start_with':
				$search_method = 'LIKE';
				$search_string = "'{$search_value}%'";
				break;
			case 'end_with':
				$search_method = 'LIKE';
				$search_string = "'%{$search_value}'";
				break;
		}
		$where = "$field_condition $search_method $search_string";
		$this->set_select_field("$select");
		$this->set_where("$where");
		return $this->list_record();
	}


}
/*---------------------------- END Model Class --------------------------------*/