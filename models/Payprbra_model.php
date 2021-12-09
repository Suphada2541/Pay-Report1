<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * Payprbra_model Class
 * @date 2021-07-13
 */
class Payprbra_model extends MY_Model
{

	private $my_table;
	public $session_name;
	public $order_field;
	public $order_sort;
	public $owner_record;

	public function __construct()
	{
		parent::__construct();
		$this->my_table = 'tb_paypayroll';
		$this->set_table_name($this->my_table);
		$this->order_field = '';
		$this->order_sort = '';
	}


	public function exists($data)
	{
		$paypr_id = checkEncryptData($data['paypr_id']);
		$this->set_table_name($this->my_table);
		$this->set_where("$this->my_table.paypr_id = $paypr_id");
		return $this->count_record();
	}


	public function load($id)
	{
		$this->set_table_name($this->my_table);
		$this->set_where("$this->my_table.paypr_id = $id");
		$lists = $this->load_record();
		return $lists;
	}

	public function loadpayPayrollList($master_ref_id)
	{
		$this->set_table_name($this->my_table);
		$this->set_where("$this->my_table.rf_pay_id = $master_ref_id");
		$list = $this->load_record();
		return $list;

	}


	public function create($post)
	{

		$data = array(
				'rf_branch_id' => $post['rf_branch_id']
				,'rf_pay_id' => $post['rf_pay_id']
				,'rf_name_id' => $post['rf_name_id']
				,'paypr_factoy_code' => $post['paypr_factoy_code']
				,'paypr_team' => $post['paypr_team']
				,'paypr_section' => $post['paypr_section']
				,'paypr_group' => $post['paypr_group']
				,'paypr_operation' => $post['paypr_operation']
				,'rf_emptype_id' => $post['rf_emptype_id']
				,'paypr_wage' => str_replace(",", "", $post['paypr_wage'])
				,'paypr_absent_num' => str_replace(",", "", $post['paypr_absent_num'])
				,'paypr_absent_pay' => str_replace(",", "", $post['paypr_absent_pay'])
				,'paypr_bnleave_num' => str_replace(",", "", $post['paypr_bnleave_num'])
				,'paypr_bnleave_pay' => str_replace(",", "", $post['paypr_bnleave_pay'])
				,'paypr_bleave_num' => str_replace(",", "", $post['paypr_bleave_num'])
				,'paypr_bleave_pay' => str_replace(",", "", $post['paypr_bleave_pay'])
				,'paypr_aleave_num' => str_replace(",", "", $post['paypr_aleave_num'])
				,'paypr_aleave_pay' => str_replace(",", "", $post['paypr_aleave_pay'])
				,'paypr_sleave_num' => str_replace(",", "", $post['paypr_sleave_num'])
				,'paypr_sleave_pay' => str_replace(",", "", $post['paypr_sleave_pay'])
				,'paypr_adleave_pay' => str_replace(",", "", $post['paypr_adleave_pay'])
				,'paypr_adleave_num' => str_replace(",", "", $post['paypr_adleave_num'])
				,'paypr_ahleave_pay' => str_replace(",", "", $post['paypr_ahleave_pay'])
				,'paypr_ahleave_num' => str_replace(",", "", $post['paypr_ahleave_num'])
				,'paypr_mleave_pay' => str_replace(",", "", $post['paypr_mleave_pay'])
				,'paypr_mleave_num' => str_replace(",", "", $post['paypr_mleave_num'])
				,'paypr_hleave_num' => str_replace(",", "", $post['paypr_hleave_num'])
				,'paypr_hleave_pay' => str_replace(",", "", $post['paypr_hleave_pay'])
				,'paypr_oleave_pay' => str_replace(",", "", $post['paypr_oleave_pay'])
				,'paypr_oleave_num' => str_replace(",", "", $post['paypr_oleave_num'])
				,'paypr_leave_total' => str_replace(",", "", $post['paypr_leave_total'])
				,'paypr_works1_num' => str_replace(",", "", $post['paypr_works1_num'])
				,'paypr_works1_pay' => str_replace(",", "", $post['paypr_works1_pay'])
				,'paypr_works2_pay' => str_replace(",", "", $post['paypr_works2_pay'])
				,'paypr_works2_num' => str_replace(",", "", $post['paypr_works2_num'])
				,'paypr_works3_pay' => str_replace(",", "", $post['paypr_works3_pay'])
				,'paypr_works3_num' => str_replace(",", "", $post['paypr_works3_num'])
				,'paypr_shutdown_num' => str_replace(",", "", $post['paypr_shutdown_num'])
				,'paypr_shutdown_pay' => str_replace(",", "", $post['paypr_shutdown_pay'])
				,'paypr_work_num' => str_replace(",", "", $post['paypr_work_num'])
				,'paypr_work_pay' => str_replace(",", "", $post['paypr_work_pay'])
				,'paypr_wsum_num' => str_replace(",", "", $post['paypr_wsum_num'])
				,'paypr_wsum_pay' => str_replace(",", "", $post['paypr_wsum_pay'])
				,'paypr_declare' => str_replace(",", "", $post['paypr_declare'])
				,'paypr_sevrance' => str_replace(",", "", $post['paypr_sevrance'])
				,'paypr_assurance_pay' => str_replace(",", "", $post['paypr_assurance_pay'])
				,'paypr_shift' => str_replace(",", "", $post['paypr_shift'])
				,'paypr_meal' => str_replace(",", "", $post['paypr_meal'])
				,'paypr_car' => str_replace(",", "", $post['paypr_car'])
				,'paypr_diligent' => str_replace(",", "", $post['paypr_diligent'])
				,'paypr_etc' => str_replace(",", "", $post['paypr_etc'])
				,'paypr_bonus' => str_replace(",", "", $post['paypr_bonus'])
				,'paypr_cola' => str_replace(",", "", $post['paypr_cola'])
				,'paypr_telephone' => str_replace(",", "", $post['paypr_telephone'])
				,'paypr_skill' => str_replace(",", "", $post['paypr_skill'])
				,'paypr_position' => str_replace(",", "", $post['paypr_position'])
				,'paypr_gas' => str_replace(",", "", $post['paypr_gas'])
				,'paypr_incentive' => str_replace(",", "", $post['paypr_incentive'])
				,'paypr_profession' => str_replace(",", "", $post['paypr_profession'])
				,'paypr_license' => str_replace(",", "", $post['paypr_license'])
				,'paypr_childship' => str_replace(",", "", $post['paypr_childship'])
				,'paypr_medical' => str_replace(",", "", $post['paypr_medical'])
				,'paypr_carde' => str_replace(",", "", $post['paypr_carde'])
				,'paypr_uptravel' => str_replace(",", "", $post['paypr_uptravel'])
				,'paypr_stay' => str_replace(",", "", $post['paypr_stay'])
				,'paypr_subsidy' => str_replace(",", "", $post['paypr_subsidy'])
				,'paypr_other' => str_replace(",", "", $post['paypr_other'])
				,'paypr_income1' => str_replace(",", "", $post['paypr_income1'])
				,'paypr_income2' => str_replace(",", "", $post['paypr_income2'])
				,'paypr_income3' => str_replace(",", "", $post['paypr_income3'])
				,'paypr_income4' => str_replace(",", "", $post['paypr_income4'])
				,'paypr_income5' => str_replace(",", "", $post['paypr_income5'])
				,'paypr_income6' => str_replace(",", "", $post['paypr_income6'])
				,'paypr_income7' => str_replace(",", "", $post['paypr_income7'])
				,'paypr_income8' => str_replace(",", "", $post['paypr_income8'])
				,'paypr_income9' => str_replace(",", "", $post['paypr_income9'])
				,'paypr_income10' => str_replace(",", "", $post['paypr_income10'])
				,'paypr_income11' => str_replace(",", "", $post['paypr_income11'])
				,'paypr_income12' => str_replace(",", "", $post['paypr_income12'])
				,'paypr_income13' => str_replace(",", "", $post['paypr_income13'])
				,'paypr_income14' => str_replace(",", "", $post['paypr_income14'])
				,'paypr_income15' => str_replace(",", "", $post['paypr_income15'])
				,'paypr_income16' => str_replace(",", "", $post['paypr_income16'])
				,'paypr_income17' => str_replace(",", "", $post['paypr_income17'])
				,'paypr_income18' => str_replace(",", "", $post['paypr_income18'])
				,'paypr_income19' => str_replace(",", "", $post['paypr_income19'])
				,'paypr_income20' => str_replace(",", "", $post['paypr_income20'])
				,'paypr_incentive_sum' => str_replace(",", "", $post['paypr_incentive_sum'])
				,'paypr_ot100_num' => str_replace(",", "", $post['paypr_ot100_num'])
				,'paypr_ot100_pay' => str_replace(",", "", $post['paypr_ot100_pay'])
				,'paypr_ot150_num' => str_replace(",", "", $post['paypr_ot150_num'])
				,'paypr_ot150_pay' => str_replace(",", "", $post['paypr_ot150_pay'])
				,'paypr_ot200_num' => str_replace(",", "", $post['paypr_ot200_num'])
				,'paypr_ot200_pay' => str_replace(",", "", $post['paypr_ot200_pay'])
				,'paypr_ot300_num' => str_replace(",", "", $post['paypr_ot300_num'])
				,'paypr_ot300_pay' => str_replace(",", "", $post['paypr_ot300_pay'])
				,'paypr_otsum0_pay' => str_replace(",", "", $post['paypr_otsum0_pay'])
				,'paypr_salary_total' => str_replace(",", "", $post['paypr_salary_total'])
				,'paypr_de_assurance' => str_replace(",", "", $post['paypr_de_assurance'])
				,'paypr_de_uniform' => str_replace(",", "", $post['paypr_de_uniform'])
				,'paypr_de_card' => str_replace(",", "", $post['paypr_de_card'])
				,'paypr_de_cooperative' => str_replace(",", "", $post['paypr_de_cooperative'])
				,'paypr_de_lond' => str_replace(",", "", $post['paypr_de_lond'])
				,'paypr_de_borrow' => str_replace(",", "", $post['paypr_de_borrow'])
				,'paypr_de_elond' => str_replace(",", "", $post['paypr_de_elond'])
				,'paypr_de_mobile' => str_replace(",", "", $post['paypr_de_mobile'])
				,'paypr_de_backtravel' => str_replace(",", "", $post['paypr_de_backtravel'])
				,'paypr_de_backother' => str_replace(",", "", $post['paypr_de_backother'])
				,'paypr_de_selfemp' => str_replace(",", "", $post['paypr_de_selfemp'])
				,'paypr_de_health' => str_replace(",", "", $post['paypr_de_health'])
				,'paypr_de_debtcase' => str_replace(",", "", $post['paypr_de_debtcase'])
				,'paypr_de_pernicious' => str_replace(",", "", $post['paypr_de_pernicious'])
				,'paypr_de_visa' => str_replace(",", "", $post['paypr_de_visa'])
				,'paypr_de_work_p' => str_replace(",", "", $post['paypr_de_work_p'])
				,'paypr_de_outother' => str_replace(",", "", $post['paypr_de_outother'])
				,'paypr_de_out1' => str_replace(",", "", $post['paypr_de_out1'])
				,'paypr_de_out2' => str_replace(",", "", $post['paypr_de_out2'])
				,'paypr_de_out3' => str_replace(",", "", $post['paypr_de_out3'])
				,'paypr_de_out4' => str_replace(",", "", $post['paypr_de_out4'])
				,'paypr_de_out5' => str_replace(",", "", $post['paypr_de_out5'])
				,'paypr_de_absent' => str_replace(",", "", $post['paypr_de_absent'])
				,'paypr_de_late' => str_replace(",", "", $post['paypr_de_late'])
				,'paypr_de_mulct' => str_replace(",", "", $post['paypr_de_mulct'])
				,'paypr_de_works1p' => str_replace(",", "", $post['paypr_de_works1p'])
				,'paypr_de_works2p' => str_replace(",", "", $post['paypr_de_works2p'])
				,'paypr_de_works3p' => str_replace(",", "", $post['paypr_de_works3p'])
				,'paypr_de_total' => str_replace(",", "", $post['paypr_de_total'])
				,'paypr_salary_net' => str_replace(",", "", $post['paypr_salary_net'])
				,'paypr_memo' => $post['paypr_memo']
				,'paypr_create_date' => setDateToStandard($post['paypr_create_date'])
				,'paypr_user_create' => $post['paypr_user_create']
		);
		$this->set_table_name($this->my_table);
		return $this->add_record($data);
	}

	public function save_excel_data($array_data)
	{
		$this->db->insert_batch($this->my_table, $array_data);
		return $this->db->affected_rows();
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

			if($search_field == 'rf_branch_id'){
				$search_method_field = "tb_branch_1.branch_id";
				$search_method_value = "LIKE '%$value%'";				
			}
			$where	.= ($where != '' ? ' AND ' : '') . " $search_method_field $search_method_value AND rf_pay_id ='$paynum'"; 

			if($order_by == ''){
				$order_by	= "";
			}
		}

		$this->set_table_name($this->my_table);
		$total_row = $this->count_record();
		$search_row = $total_row;
		if ($where != '') {
			$this->db->join('tb_branch AS tb_branch_1', "$this->my_table.rf_branch_id = tb_branch_1.branch_id", 'left');
		$this->db->join('tb_payment AS tb_payment_2', "$this->my_table.rf_pay_id = tb_payment_2.pay_id", 'left');
		$this->db->join('tb_person AS tb_person_3', "$this->my_table.rf_name_id = tb_person_3.name_id", 'left');
		$this->db->join('tb_typeformat AS tb_typeformat_4', "$this->my_table.rf_formart_code_pr = tb_typeformat_4.formart_textcode", 'left');
		$this->db->join('tb_emptype AS tb_emptype_5', "$this->my_table.rf_emptype_id = tb_emptype_5.emptype_Id", 'left');
		$this->db->join('tb_typeattach AS tb_typeattach_6', "$this->my_table.rf_typeattach_id_pr = tb_typeattach_6.attach_id", 'left');

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
		, tb_branch_1.branch_nick AS rfBranchIdBranchNick
		, tb_payment_2.pay_num AS rfPayIdPayNum
		, tb_payment_2.pay_stop AS rfPaystop
		, tb_payment_2.pay_fromdate AS rfPayIdPayFromdate
		, tb_payment_2.pay_todate AS rfPayIdPayTodate
		, tb_payment_2.pay_appdate AS rfPayIdPayAppdate 
		, tb_payment_2.rf_month_id AS rfPayIdPayMonthId 
		, tb_payment_2.pay_year AS rfPayIdPayYear 
		, tb_payment_2.pay_appdate AS rfPayIdPayInformdate
		, tb_person_3.name_id AS rfPersonNameId
		, tb_person_3.emp_name AS rfNameIdEmpName
		, tb_person_3.emp_surname AS rfNameIdEmpSurname
		, tb_person_3.start_date AS rfNameIdStartDate
		, tb_person_3.end_date AS rfNameIdEndDate
		, tb_person_3.rf_position_id AS rfNameIdPositionId
		, tb_typeformat_4.formart_textname AS rfFormatCodeFormartTextname
		, tb_emptype_5.emptype_name AS rfEmpTypeEmptypeName
		, tb_typeattach_6.attach_type AS rfTypeattachIdAttachType
		, tb_pername_7.pre_name AS rfPernameIdPreName
		");
		$this->db->join('tb_branch AS tb_branch_1', "$this->my_table.rf_branch_id = tb_branch_1.branch_id", 'left');
		$this->db->join('tb_payment AS tb_payment_2', "$this->my_table.rf_pay_id = tb_payment_2.pay_id", 'left');
		$this->db->join('tb_person AS tb_person_3', "$this->my_table.rf_name_id = tb_person_3.name_id", 'left');
		$this->db->join('tb_typeformat AS tb_typeformat_4', "$this->my_table.rf_formart_code_pr = tb_typeformat_4.formart_textcode", 'left');
		$this->db->join('tb_emptype AS tb_emptype_5', "$this->my_table.rf_emptype_id = tb_emptype_5.emptype_Id", 'left');
		$this->db->join('tb_typeattach AS tb_typeattach_6', "$this->my_table.rf_typeattach_id_pr = tb_typeattach_6.attach_id", 'left');
		$this->db->join('tb_pername AS tb_pername_7', "tb_person_3.rf_pre_id = tb_pername_7.pre_id", 'left');

		$list_record = $this->list_record();
		// echo $this->db->last_query();
		$data = array(
				'total_row'	=> $total_row, 
				'search_row' => $search_row,
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

			if($search_field == 'rf_branch_id'){
				$search_method_field = "tb_branch_1.branch_id";
				$search_method_value = "LIKE '%$value%'";				
			}
			$where	.= ($where != '' ? ' AND ' : '') . " $search_method_field $search_method_value AND rf_pay_id ='$paynum'"; 

			if($order_by == ''){
				$order_by	= "";
			}
		}

		$this->set_table_name($this->my_table);
		$total_row = $this->count_record();
		$search_row = $total_row;
		if ($where != '') {
			$this->db->join('tb_branch AS tb_branch_1', "$this->my_table.rf_branch_id = tb_branch_1.branch_id", 'left');
		$this->db->join('tb_payment AS tb_payment_2', "$this->my_table.rf_pay_id = tb_payment_2.pay_id", 'left');
		$this->db->join('tb_person AS tb_person_3', "$this->my_table.rf_name_id = tb_person_3.name_id", 'left');
		$this->db->join('tb_typeformat AS tb_typeformat_4', "$this->my_table.rf_formart_code_pr = tb_typeformat_4.formart_textcode", 'left');
		$this->db->join('tb_emptype AS tb_emptype_5', "$this->my_table.rf_emptype_id = tb_emptype_5.emptype_Id", 'left');
		$this->db->join('tb_typeattach AS tb_typeattach_6', "$this->my_table.rf_typeattach_id_pr = tb_typeattach_6.attach_id", 'left');

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
		, tb_branch_1.branch_nick AS rfBranchIdBranchNick
		, tb_payment_2.pay_num AS rfPayIdPayNum
		, tb_payment_2.pay_stop AS rfPaystop
		, tb_person_3.name_id AS rfPersonNameId
		, tb_person_3.emp_name AS rfNameIdEmpName
		, tb_person_3.emp_surname AS rfNameIdEmpSurname
		, tb_person_3.start_date AS rfNameIdStartDate
		, tb_typeformat_4.formart_textname AS rfFormatCodeFormartTextname
		, tb_emptype_5.emptype_name AS rfEmpTypeEmptypeName
		, tb_typeattach_6.attach_type AS rfTypeattachIdAttachType
		, tb_pername_7.pre_name AS rfPernameIdPreName
		, sum(paypr_de_outother + paypr_de_out1 + paypr_de_out2 + paypr_de_out3
		+ paypr_de_out4 + paypr_de_out5 + paypr_de_works1p + paypr_de_works2p
		+ paypr_de_works3p + paypr_de_absent + paypr_de_late + paypr_de_mulct
		+ paypr_de_visa + paypr_de_work_p) AS paypr_de_income_3
		");
		$this->db->join('tb_branch AS tb_branch_1', "$this->my_table.rf_branch_id = tb_branch_1.branch_id", 'left');
		$this->db->join('tb_payment AS tb_payment_2', "$this->my_table.rf_pay_id = tb_payment_2.pay_id", 'left');
		$this->db->join('tb_person AS tb_person_3', "$this->my_table.rf_name_id = tb_person_3.name_id", 'left');
		$this->db->join('tb_typeformat AS tb_typeformat_4', "$this->my_table.rf_formart_code_pr = tb_typeformat_4.formart_textcode", 'left');
		$this->db->join('tb_emptype AS tb_emptype_5', "$this->my_table.rf_emptype_id = tb_emptype_5.emptype_Id", 'left');
		$this->db->join('tb_typeattach AS tb_typeattach_6', "$this->my_table.rf_typeattach_id_pr = tb_typeattach_6.attach_id", 'left');
		$this->db->join('tb_pername AS tb_pername_7', "tb_person_3.rf_pre_id = tb_pername_7.pre_id", 'left');
		$this->db->select_sum("$this->my_table.paypr_de_cooperative");
		$this->db->select_sum("$this->my_table.paypr_de_card");
		$this->db->select_sum("$this->my_table.paypr_de_health");
		$this->db->select_sum("$this->my_table.paypr_de_mobile");
		$this->db->select_sum("$this->my_table.paypr_de_backother");
		$this->db->select_sum("$this->my_table.paypr_de_borrow");
		$this->db->select_sum("$this->my_table.paypr_de_backtravel");
		$this->db->select_sum("$this->my_table.paypr_de_elond");
		$this->db->select_sum("$this->my_table.paypr_de_selfemp");
		$this->db->select_sum("$this->my_table.paypr_de_lond");
		$this->db->select_sum("$this->my_table.paypr_de_debtcase");
		$this->db->select_sum("$this->my_table.paypr_de_pernicious");
		$this->db->select_sum("$this->my_table.paypr_de_uniform");
		$this->db->select_sum("$this->my_table.paypr_de_outother");
		$this->db->select_sum("$this->my_table.paypr_de_branch");
		$this->db->select_sum("$this->my_table.paypr_de_payroll");

		$list_record = $this->list_record();
		// echo $this->db->last_query();
		$data = array(
				'total_row'	=> $total_row, 
				'search_row' => $search_row,
				'list_data'	=> $list_record
		);
		return $data;
	}

	public function check_pdf($start_row = FALSE, $per_page = FALSE)
	{
		$search_field 	= $this->session->userdata($this->session_name . '_search_field');
		$value 	= $this->session->userdata($this->session_name . '_value');
		$value 	= trim($value);
		$paynum = $this->session->userdata($this->session_name . '_paynum');


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

			if($search_field == 'rf_branch_id'){
				$search_method_field = "tb_branch_1.branch_id";
				$search_method_value = "LIKE '%$value%'";				
			}
			$where	.= ($where != '' ? ' AND ' : '') . " $search_method_field $search_method_value AND rf_pay_id ='$paynum'"; 

			if($order_by == ''){
				$order_by	= "";
			}
		}

		$this->set_table_name($this->my_table);
		$total_row = $this->count_record();
		$search_row = $total_row;
		if ($where != '') {
			$this->db->join('tb_branch AS tb_branch_1', "$this->my_table.rf_branch_id = tb_branch_1.branch_id", 'left');
		$this->db->join('tb_payment AS tb_payment_2', "$this->my_table.rf_pay_id = tb_payment_2.pay_id", 'left');
		$this->db->join('tb_person AS tb_person_3', "$this->my_table.rf_name_id = tb_person_3.name_id", 'left');
		$this->db->join('tb_typeformat AS tb_typeformat_4', "$this->my_table.rf_formart_code_pr = tb_typeformat_4.formart_textcode", 'left');
		$this->db->join('tb_emptype AS tb_emptype_5', "$this->my_table.rf_emptype_id = tb_emptype_5.emptype_Id", 'left');
		$this->db->join('tb_typeattach AS tb_typeattach_6', "$this->my_table.rf_typeattach_id_pr = tb_typeattach_6.attach_id", 'left');

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
		, $this->my_table.paypr_work_pay AS payprWorkPay
		, $this->my_table.paypr_otsum1_pay AS PayprOtsum1Pay
		, $this->my_table.paypr_salary_total AS PayprSalaryTotal
		, tb_branch_1.branch_nick AS rfBranchIdBranchNick
		, tb_branch_1.branch_name AS rfBranchIdBranchName
		, tb_branch_1.branch_code AS rfBranchIdBranchCode
		, tb_payment_2.pay_num AS rfPayIdPayNum
		, tb_payment_2.pay_stop AS rfPaystop
		, tb_payment_2.pay_fromdate AS rfPayIdPayFromdate
		, tb_payment_2.pay_todate AS rfPayIdPayTodate
		, tb_payment_2.pay_appdate AS rfPayIdPayAppdate
		, tb_person_3.name_id AS rfPersonNameId
		, tb_person_3.emp_name AS rfNameIdEmpName
		, tb_person_3.emp_surname AS rfNameIdEmpSurname
		, tb_person_3.start_date AS rfNameIdStartDate
		, tb_typeformat_4.formart_textname AS rfFormatCodeFormartTextname
		, tb_typeformat_4.text_income1 AS rfFormatCodeTextIncome1 
		, tb_typeformat_4.text_works1p AS rfFormatCodeTextWorks1p
		, tb_typeformat_4.text_income2 AS rfFormatCodeTextIncome2
		, tb_typeformat_4.text_income3 AS rfFormatCodeTextIncome3 
		, tb_typeformat_4.text_income4 AS rfFormatCodeTextIncome4
		, tb_typeformat_4.textde_out1 AS rfFormatCodeTextdeOut1 
		, tb_typeformat_4.textde_out2 AS rfFormatCodeTextdeOut2
		, tb_typeformat_4.textde_out3 AS rfFormatCodeTextdeOut3 
		, tb_typeformat_4.textde_out4 AS rfFormatCodeTextdeOut4
		, tb_emptype_5.emptype_name AS rfEmpTypeEmptypeName
		, tb_typeattach_6.attach_type AS rfTypeattachIdAttachType
		, tb_company_7.company_nick AS rfBranchIdCompanyNick
		, tb_pername_8.pre_name AS rfPernamePreId
		");
		$this->db->join('tb_branch AS tb_branch_1', "$this->my_table.rf_branch_id = tb_branch_1.branch_id", 'left');
		$this->db->join('tb_payment AS tb_payment_2', "$this->my_table.rf_pay_id = tb_payment_2.pay_id", 'left');
		$this->db->join('tb_person AS tb_person_3', "$this->my_table.rf_name_id = tb_person_3.name_id", 'left');
		$this->db->join('tb_typeformat AS tb_typeformat_4', "$this->my_table.rf_formart_code_pr = tb_typeformat_4.formart_textcode", 'left');
		$this->db->join('tb_emptype AS tb_emptype_5', "$this->my_table.rf_emptype_id = tb_emptype_5.emptype_Id", 'left');
		$this->db->join('tb_typeattach AS tb_typeattach_6', "$this->my_table.rf_typeattach_id_pr = tb_typeattach_6.attach_id", 'left');
		$this->db->join('tb_company AS tb_company_7', "tb_branch_1.rf_company_Id = tb_company_7.company_id", 'inner');
		$this->db->join('tb_pername AS tb_pername_8', "tb_person_3.rf_pre_id = tb_pername_8.pre_id", 'inner');

		$list_record = $this->list_record();
		// echo $this->db->last_query();
		$data = array(
				'total_row'	=> $total_row, 
				'search_row'	=> $search_row,
				'list_data'	=> $list_record
		);
		return $data;
	}

	public function slip_pdf($start_row = FALSE, $per_page = FALSE)
	{
		$search_field 	= $this->session->userdata($this->session_name . '_search_field');
		$value 	= $this->session->userdata($this->session_name . '_value');
		$value 	= trim($value);
		$paynum = $this->session->userdata($this->session_name . '_paynum');


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

			if($search_field == 'rf_branch_id'){
				$search_method_field = "tb_branch_1.branch_id";
				$search_method_value = "LIKE '%$value%'";				
			}
			$where	.= ($where != '' ? ' AND ' : '') . " $search_method_field $search_method_value AND rf_pay_id ='$paynum'"; 

			if($order_by == ''){
				$order_by	= "";
			}
		}

		$this->set_table_name($this->my_table);
		$total_row = $this->count_record();
		$search_row = $total_row;
		if ($where != '') {
			$this->db->join('tb_branch AS tb_branch_1', "$this->my_table.rf_branch_id = tb_branch_1.branch_id", 'left');
		$this->db->join('tb_payment AS tb_payment_2', "$this->my_table.rf_pay_id = tb_payment_2.pay_id", 'left');
		$this->db->join('tb_person AS tb_person_3', "$this->my_table.rf_name_id = tb_person_3.name_id", 'left');
		$this->db->join('tb_typeformat AS tb_typeformat_4', "$this->my_table.rf_formart_code_pr = tb_typeformat_4.formart_textcode", 'left');
		$this->db->join('tb_emptype AS tb_emptype_5', "$this->my_table.rf_emptype_id = tb_emptype_5.emptype_Id", 'left');
		$this->db->join('tb_typeattach AS tb_typeattach_6', "$this->my_table.rf_typeattach_id_pr = tb_typeattach_6.attach_id", 'left');

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
				, tb_branch_1.branch_nick AS rfBranchIdBranchNick , tb_branch_1.branch_code AS rfBranchIdBranchCode , tb_branch_1.branch_name AS rfBranchIdBranchName
				, tb_payment_2.pay_num AS rfPayIdPayNum , tb_payment_2.pay_fromdate AS rfPayIdPayFromdate, tb_payment_2.pay_todate AS rfPayIdPayTodate
				, tb_payment_2.pay_appdate AS rfPayIdPayAppdate , tb_payment_2.rf_month_id AS rfPayIdPayMonthId , tb_payment_2.pay_year AS rfPayIdPayYear 
				, tb_payment_2.pay_stop AS rfPayIdpaystop , tb_payment_2.pay_appdate AS rfPayIdPayInformdate
				, tb_person_3.name_id AS rfPersonNameId , tb_person_3.rf_pre_id AS rfNameIdPreId , tb_person_3.emp_name AS rfNameIdEmpName 
				, tb_person_3.emp_surname AS rfNameIdEmpSurname , tb_person_3.emp_barcode AS rfNameIdEnpbarcode , tb_person_3.rf_section_id AS rfNameIdSectionId 
				, tb_person_3.num_card AS rfNameIdNumCard , tb_person_3.rf_typestatus_id AS rfNameIdTypestatusId , tb_person_3.person_num_student AS rfNameIdPersonNumStudent
				, tb_person_3.person_num_children AS rfNameIdPersonNumChildren , tb_person_3.rf_typeincom_id AS rfNameIdTypeincomId
				, tb_person_3.person_dis_other AS rfNameIdPersonDisOther , tb_person_3.rf_typetex_id AS rfNameIdTypetexId
				, tb_person_3.start_date AS rfNameIdStartDate
				, tb_typeformat_4.formart_textname AS rfFormatCodeFormartTextname , tb_typeformat_4.text_works1p AS rfFormatCodeTextWorks1p
				, tb_typeformat_4.text_works2p AS rfFormatCodeTextWorks2p , tb_typeformat_4.text_works3p AS rfFormatCodeTextWorks3p
				, tb_typeformat_4.text_ot101p AS rfFormatCodeTextOt101p , tb_typeformat_4.text_ot105p AS rfFormatCodeTextOt105p
				, tb_typeformat_4.text_ot102p AS rfFormatCodeTextOt102p , tb_typeformat_4.text_ot103p AS rfFormatCodeTextOt103p
				, tb_typeformat_4.text_ot104p AS rfFormatCodeTextOt104p , tb_typeformat_4.textde_visa AS rfFormatTextdeVisa
				, tb_typeformat_4.textde_work_p AS rfFormatTextdeWorkP , tb_typeformat_4.textde_late AS rfFormatTextdeLate
				, tb_typeformat_4.textde_absent AS rfFormatTextdeAbsent , tb_typeformat_4.textde_mulct AS rfFormatTextdeMulct
				, tb_typeformat_4.text_income1 AS rfFormatCodeTextIncome1 , tb_typeformat_4.text_income2 AS rfFormatCodeTextIncome2
				, tb_typeformat_4.text_income3 AS rfFormatCodeTextIncome3 , tb_typeformat_4.text_income4 AS rfFormatCodeTextIncome4
				, tb_typeformat_4.textde_out1 AS rfFormatCodeTextdeOut1 , tb_typeformat_4.textde_out2 AS rfFormatCodeTextdeOut2
				, tb_typeformat_4.textde_out3 AS rfFormatCodeTextdeOut3 , tb_typeformat_4.textde_out4 AS rfFormatCodeTextdeOut4
				, tb_emptype_5.emptype_name AS rfEmpTypeEmptypeName
				, tb_typeattach_6.attach_type AS rfTypeattachIdAttachType
				, tb_payahead_7.ahead_pay  AS rfPayaheadIdAheadPay
				, '$this->my_table.paypr_wsum_pay'+ '$this->my_table.paypr_income_sum' +'rfPayaheadIdAheadPay' AS sumPaysalary
				, '$this->my_table.paypr_other'+'$this->my_table.paypr_income1'+'$this->my_table.paypr_income2'+'$this->my_table.paypr_income3'
				+ '$this->my_table.paypr_income4'+'$this->my_table.paypr_income5'+'$this->my_table.paypr_income6'+'$this->my_table.paypr_income7'
				+ '$this->my_table.paypr_income8'+'$this->my_table.paypr_income9'+'$this->my_table.paypr_income10'+'$this->my_table.paypr_income11'
				+ '$this->my_table.paypr_income12'+'$this->my_table.paypr_income13'+'$this->my_table.paypr_income14'+'$this->my_table.paypr_income15'
				+ '$this->my_table.paypr_income16'+'$this->my_table.paypr_income17'+'$this->my_table.paypr_income18'+'$this->my_table.paypr_income19'
				+ '$this->my_table.paypr_income20' AS paypr_income_1
				, '$this->my_table.paypr_de_outother'+'$this->my_table.paypr_de_out1'+'$this->my_table.paypr_de_out2'+'$this->my_table.paypr_de_out3'
				+ '$this->my_table.paypr_de_out4'+'$this->my_table.paypr_de_out5'+'$this->my_table.paypr_de_works1p'+'$this->my_table.paypr_de_works2p'
				+ '$this->my_table.paypr_de_works3p' AS paypr_de_income_1
				, '$this->my_table.paypr_other'+'$this->my_table.paypr_income1'+'$this->my_table.paypr_income2'+'$this->my_table.paypr_income3'
				+ '$this->my_table.paypr_income4'+'$this->my_table.paypr_income5'+'$this->my_table.paypr_income6'+'$this->my_table.paypr_income7'
				+ '$this->my_table.paypr_income8'+'$this->my_table.paypr_income9'+'$this->my_table.paypr_income10'+'$this->my_table.paypr_income11'
				+ '$this->my_table.paypr_income12'+'$this->my_table.paypr_income13'+'$this->my_table.paypr_income14'+'$this->my_table.paypr_income15'
				+ '$this->my_table.paypr_income16'+'$this->my_table.paypr_income17'+'$this->my_table.paypr_income18'+'$this->my_table.paypr_income19'
				+ '$this->my_table.paypr_income20'+'$this->my_table.paypr_profession'+'$this->my_table.paypr_license'+'$this->my_table.paypr_childship'
				+ '$this->my_table.paypr_income4'+'$this->my_table.paypr_medical'+'$this->my_table.paypr_carde'+'$this->my_table.paypr_uptravel'
				+ '$this->my_table.paypr_income8'+'$this->my_table.paypr_stay'+'$this->my_table.paypr_subsidy' AS paypr_income_2
				, '$this->my_table.paypr_de_outother'+'$this->my_table.paypr_de_out1'+'$this->my_table.paypr_de_out2'+'$this->my_table.paypr_de_out3'
				+ '$this->my_table.paypr_de_out4'+'$this->my_table.paypr_de_out5'+'$this->my_table.paypr_de_works1p'+'$this->my_table.paypr_de_works2p'
				+ '$this->my_table.paypr_de_works3p'+'$this->my_table.paypr_de_absent'+'$this->my_table.paypr_de_late'+'$this->my_table.paypr_de_mulct'
				+ '$this->my_table.paypr_de_backtravel'+'$this->my_table.paypr_de_health'+'$this->my_table.paypr_de_backother'+'$this->my_table.paypr_de_visa'
				+ '$this->my_table.paypr_de_work_p' AS paypr_de_income_2
				, tb_pername_8.pre_name  AS rfPernamePreId
				, $this->my_table.paypr_salary_net AS PayprSalaryNet , $this->my_table.paypr_de_ssop AS PayprdeSso 
				, $this->my_table.paypr_de_tax AS PayprdeTax 
				, tb_typestatus_9.status_type  AS rfNameIdTypestatusType
				, tb_typeincome_10.income_type  AS rfNameIdTypeincomType
				, tb_company_11.company_name  AS companyIdCompanyName
				");

		$this->db->join('tb_branch AS tb_branch_1', "$this->my_table.rf_branch_id = tb_branch_1.branch_id", 'left');
		$this->db->join('tb_payment AS tb_payment_2', "$this->my_table.rf_pay_id = tb_payment_2.pay_id", 'left');
		$this->db->join('tb_person AS tb_person_3', "$this->my_table.rf_name_id = tb_person_3.name_id", 'left');
		$this->db->join('tb_typeformat AS tb_typeformat_4', "$this->my_table.rf_formart_code_pr = tb_typeformat_4.formart_textcode", 'left');
		$this->db->join('tb_emptype AS tb_emptype_5', "$this->my_table.rf_emptype_id = tb_emptype_5.emptype_Id", 'left');
		$this->db->join('tb_typeattach AS tb_typeattach_6', "$this->my_table.rf_typeattach_id_pr = tb_typeattach_6.attach_id", 'left');
		$this->db->join('tb_payahead AS tb_payahead_7', "$this->my_table.rf_name_id = tb_payahead_7.rf_name_id AND tb_payment_2.rf_paynum = tb_payahead_7.rf_paynum AND tb_payment_2.rf_month_id = tb_payahead_7.rf_month_id AND tb_payment_2.pay_year = tb_payahead_7.rf_year ", 'left');
		$this->db->join('tb_pername AS tb_pername_8', "tb_person_3.rf_pre_id = tb_pername_8.pre_id ", 'left');
		$this->db->join('tb_typestatus AS tb_typestatus_9', "tb_person_3.rf_typestatus_id = tb_typestatus_9.status_id ", 'left');
		$this->db->join('tb_typeincome AS tb_typeincome_10', "tb_person_3.rf_typeincom_id = tb_typeincome_10.income_id ", 'left');
		$this->db->join('tb_company AS tb_company_11', "tb_branch_1.rf_company_Id = tb_company_11.company_id ", 'left');

		$list_record = $this->list_record();
		// echo $this->db->last_query();
		$data = array(
				'total_row'	=> $total_row, 
				'search_row'	=> $search_row,
				'list_data'	=> $list_record
		);
		return $data;
	}

	public function update($post)
	{
		// $rf_branch_id = $this->session->userdata('_value');
		// $paynum = $this->session->userdata('_paynum');

		$paypr_de_total = $post['paypr_de_ssop']+$post['paypr_de_tax']+$post['paypr_de_funp']+$post['paypr_de_atm']
		// +$post['paypr_de_assurance']
		+$post['paypr_de_uniform']+$post['paypr_de_card']+$post['paypr_de_cooperative']
		+$post['paypr_de_lond']+$post['paypr_de_borrow']+$post['paypr_de_elond']+$post['paypr_de_mobile']+$post['paypr_de_backtravel']
		+$post['paypr_de_backother']+$post['paypr_de_selfemp']+$post['paypr_de_health']+$post['paypr_de_debtcase']
		+$post['paypr_de_pernicious']+$post['paypr_de_visa']+$post['paypr_de_work_p']+$post['paypr_de_outother']
		+$post['paypr_de_absent']+$post['paypr_de_late']+$post['paypr_de_mulct']+$post['paypr_de_out1']
		+$post['paypr_de_out2']+$post['paypr_de_out3']+$post['paypr_de_out4']+$post['paypr_de_out5']
		+$post['paypr_de_works1p']+$post['paypr_de_works2p']+$post['paypr_de_works3p'];
		
		$paypr_salary_total = $post['paypr_absent_pay']+$post['paypr_bnleave_pay']+$post['paypr_bleave_pay']+$post['paypr_aleave_pay']
		+$post['paypr_sleave_pay']+$post['paypr_adleave_pay']+$post['paypr_ahleave_pay']+$post['paypr_mleave_pay']
		+$post['paypr_hleave_pay']+$post['paypr_oleave_pay']+$post['paypr_works1_pay']+$post['paypr_works2_pay']
		+$post['paypr_works3_pay']+$post['paypr_shutdown_pay']+$post['paypr_work_pay']
		+$post['paypr_declare']+$post['paypr_sevrance']+$post['paypr_assurance_pay']+$post['paypr_shift']
		+$post['paypr_meal']+$post['paypr_car']+$post['paypr_diligent']+$post['paypr_etc']
		+$post['paypr_bonus']+$post['paypr_cola']+$post['paypr_telephone']+$post['paypr_skill']
		+$post['paypr_position']+$post['paypr_gas']+$post['paypr_incentive']+$post['paypr_profession']
		+$post['paypr_license']+$post['paypr_childship']+$post['paypr_medical']+$post['paypr_carde']
		+$post['paypr_uptravel']+$post['paypr_stay']+$post['paypr_subsidy']+$post['paypr_other']
		+$post['paypr_income1']+$post['paypr_income2']+$post['paypr_income3']+$post['paypr_income4']
		+$post['paypr_income5']+$post['paypr_income6']+$post['paypr_income7']+$post['paypr_income8']
		+$post['paypr_income9']+$post['paypr_income10']+$post['paypr_income11']+$post['paypr_income12']
		+$post['paypr_income13']+$post['paypr_income14']+$post['paypr_income15']+$post['paypr_income16']
		+$post['paypr_income17']+$post['paypr_income18']+$post['paypr_income19']+$post['paypr_income20']
		+$post['paypr_ot100_pay']+$post['paypr_ot150_pay']+$post['paypr_ot200_pay']+$post['paypr_ot300_pay']
		+$post['paypr_ot101_pay']+$post['paypr_ot105_pay']+$post['paypr_ot102_pay']+$post['paypr_ot103_pay']
		+$post['paypr_ot104_pay'];

		$paypr_salary_net = $paypr_salary_total-$paypr_de_total;

		$data = array(
				'paypr_absent_pay' => str_replace(",", "", $post['paypr_absent_pay'])
				,'paypr_bnleave_pay' => str_replace(",", "", $post['paypr_bnleave_pay'])
				,'paypr_bleave_pay' => str_replace(",", "", $post['paypr_bleave_pay'])
				,'paypr_aleave_pay' => str_replace(",", "", $post['paypr_aleave_pay'])
				,'paypr_sleave_pay' => str_replace(",", "", $post['paypr_sleave_pay'])
				,'paypr_adleave_pay' => str_replace(",", "", $post['paypr_adleave_pay'])
				,'paypr_ahleave_pay' => str_replace(",", "", $post['paypr_ahleave_pay'])
				,'paypr_mleave_pay' => str_replace(",", "", $post['paypr_mleave_pay'])
				,'paypr_hleave_pay' => str_replace(",", "", $post['paypr_hleave_pay'])
				,'paypr_oleave_pay' => str_replace(",", "", $post['paypr_oleave_pay'])
				,'paypr_works1_pay' => str_replace(",", "", $post['paypr_works1_pay'])
				,'paypr_works2_pay' => str_replace(",", "", $post['paypr_works2_pay'])
				,'paypr_works3_pay' => str_replace(",", "", $post['paypr_works3_pay'])
				,'paypr_shutdown_pay' => str_replace(",", "", $post['paypr_shutdown_pay'])
				,'paypr_work_pay' => str_replace(",", "", $post['paypr_work_pay'])
				,'paypr_declare' => str_replace(",", "", $post['paypr_declare'])
				,'paypr_sevrance' => str_replace(",", "", $post['paypr_sevrance'])
				,'paypr_assurance_pay' => str_replace(",", "", $post['paypr_assurance_pay'])
				,'paypr_shift' => str_replace(",", "", $post['paypr_shift'])
				,'paypr_meal' => str_replace(",", "", $post['paypr_meal'])
				,'paypr_car' => str_replace(",", "", $post['paypr_car'])
				,'paypr_diligent' => str_replace(",", "", $post['paypr_diligent'])
				,'paypr_etc' => str_replace(",", "", $post['paypr_etc'])
				,'paypr_bonus' => str_replace(",", "", $post['paypr_bonus'])
				,'paypr_cola' => str_replace(",", "", $post['paypr_cola'])
				,'paypr_telephone' => str_replace(",", "", $post['paypr_telephone'])
				,'paypr_skill' => str_replace(",", "", $post['paypr_skill'])
				,'paypr_position' => str_replace(",", "", $post['paypr_position'])
				,'paypr_gas' => str_replace(",", "", $post['paypr_gas'])
				,'paypr_incentive' => str_replace(",", "", $post['paypr_incentive'])
				,'paypr_profession' => str_replace(",", "", $post['paypr_profession'])
				,'paypr_license' => str_replace(",", "", $post['paypr_license'])
				,'paypr_childship' => str_replace(",", "", $post['paypr_childship'])
				,'paypr_medical' => str_replace(",", "", $post['paypr_medical'])
				,'paypr_carde' => str_replace(",", "", $post['paypr_carde'])
				,'paypr_uptravel' => str_replace(",", "", $post['paypr_uptravel'])
				,'paypr_stay' => str_replace(",", "", $post['paypr_stay'])
				,'paypr_subsidy' => str_replace(",", "", $post['paypr_subsidy'])
				,'paypr_other' => str_replace(",", "", $post['paypr_other'])
				,'paypr_income1' => str_replace(",", "", $post['paypr_income1'])
				,'paypr_income2' => str_replace(",", "", $post['paypr_income2'])
				,'paypr_income3' => str_replace(",", "", $post['paypr_income3'])
				,'paypr_income4' => str_replace(",", "", $post['paypr_income4'])
				,'paypr_income5' => str_replace(",", "", $post['paypr_income5'])
				,'paypr_income6' => str_replace(",", "", $post['paypr_income6'])
				,'paypr_income7' => str_replace(",", "", $post['paypr_income7'])
				,'paypr_income8' => str_replace(",", "", $post['paypr_income8'])
				,'paypr_income9' => str_replace(",", "", $post['paypr_income9'])
				,'paypr_income10' => str_replace(",", "", $post['paypr_income10'])
				,'paypr_income11' => str_replace(",", "", $post['paypr_income11'])
				,'paypr_income12' => str_replace(",", "", $post['paypr_income12'])
				,'paypr_income13' => str_replace(",", "", $post['paypr_income13'])
				,'paypr_income14' => str_replace(",", "", $post['paypr_income14'])
				,'paypr_income15' => str_replace(",", "", $post['paypr_income15'])
				,'paypr_income16' => str_replace(",", "", $post['paypr_income16'])
				,'paypr_income17' => str_replace(",", "", $post['paypr_income17'])
				,'paypr_income18' => str_replace(",", "", $post['paypr_income18'])
				,'paypr_income19' => str_replace(",", "", $post['paypr_income19'])
				,'paypr_income20' => str_replace(",", "", $post['paypr_income20'])
				,'paypr_ot100_pay' => str_replace(",", "", $post['paypr_ot100_pay'])
				,'paypr_ot150_pay' => str_replace(",", "", $post['paypr_ot150_pay'])
				,'paypr_ot200_pay' => str_replace(",", "", $post['paypr_ot200_pay'])
				,'paypr_ot300_pay' => str_replace(",", "", $post['paypr_ot300_pay'])
				,'paypr_ot101_pay' => str_replace(",", "",$post['paypr_ot101_pay'])
				,'paypr_ot105_pay' => str_replace(",", "",$post['paypr_ot105_pay'])
				,'paypr_ot102_pay' => str_replace(",", "",$post['paypr_ot102_pay'])
				,'paypr_ot103_pay' => str_replace(",", "",$post['paypr_ot103_pay'])
				,'paypr_ot104_pay' => str_replace(",", "",$post['paypr_ot104_pay'])
				,'paypr_salary_total' => str_replace(",", "", $paypr_salary_total)
				,'paypr_de_ssop' => str_replace(",", "",$post['paypr_de_ssop'])
				,'paypr_de_tax' => str_replace(",", "",$post['paypr_de_tax'])
				,'paypr_de_funp' => str_replace(",", "",$post['paypr_de_funp'])
				,'paypr_de_atm' => str_replace(",", "",$post['paypr_de_atm'])
				// ,'paypr_de_assurance' => str_replace(",", "",$post['paypr_de_assurance'])
				,'paypr_de_uniform' => str_replace(",", "",$post['paypr_de_uniform'])
				,'paypr_de_card' => str_replace(",", "",$post['paypr_de_card'])
				,'paypr_de_cooperative' => str_replace(",", "",$post['paypr_de_cooperative'])
				,'paypr_de_lond' => str_replace(",", "",$post['paypr_de_lond'])
				,'paypr_de_borrow' => str_replace(",", "",$post['paypr_de_borrow'])
				,'paypr_de_elond' => str_replace(",", "",$post['paypr_de_elond'])
				,'paypr_de_mobile' => str_replace(",", "",$post['paypr_de_mobile'])
				,'paypr_de_backtravel' => str_replace(",", "",$post['paypr_de_backtravel'])
				,'paypr_de_backother' => str_replace(",", "",$post['paypr_de_backother'])
				,'paypr_de_selfemp' => str_replace(",", "",$post['paypr_de_selfemp'])
				,'paypr_de_health' => str_replace(",", "",$post['paypr_de_health'])
				,'paypr_de_debtcase' => str_replace(",", "",$post['paypr_de_debtcase'])
				,'paypr_de_pernicious' => str_replace(",", "",$post['paypr_de_pernicious'])
				,'paypr_de_visa' => str_replace(",", "",$post['paypr_de_visa'])
				,'paypr_de_work_p' => str_replace(",", "",$post['paypr_de_work_p'])
				,'paypr_de_outother' => str_replace(",", "",$post['paypr_de_outother'])
				,'paypr_de_absent' => str_replace(",", "",$post['paypr_de_absent'])
				,'paypr_de_late' => str_replace(",", "",$post['paypr_de_late'])
				,'paypr_de_mulct' => str_replace(",", "",$post['paypr_de_mulct'])
				,'paypr_de_out1' => str_replace(",", "",$post['paypr_de_out1'])
				,'paypr_de_out2' => str_replace(",", "",$post['paypr_de_out2'])
				,'paypr_de_out3' => str_replace(",", "",$post['paypr_de_out3'])
				,'paypr_de_out4' => str_replace(",", "",$post['paypr_de_out4'])
				,'paypr_de_out5' => str_replace(",", "",$post['paypr_de_out5'])
				,'paypr_de_works1p' => str_replace(",", "",$post['paypr_de_works1p'])
				,'paypr_de_works2p' => str_replace(",", "",$post['paypr_de_works2p'])
				,'paypr_de_works3p' => str_replace(",", "",$post['paypr_de_works3p'])
				,'paypr_de_total' => str_replace(",", "", $paypr_de_total)
				,'paypr_salary_net' => str_replace(",", "", $paypr_salary_net)
				,'paypr_de_branch' => str_replace(",", "",$post['paypr_de_branch'])
				,'paypr_de_payroll' => str_replace(",", "",$post['paypr_de_payroll'])
				,'paypr_de_memo' => $post['paypr_de_memo']
				,'paypr_modify_date' => date('Y-m-d H:i:s')
				,'paypr_user_modify' => $this->session->userdata('user_id')
		);

		if(!empty($data)){
			$paypr_id = checkEncryptData($post['encrypt_paypr_id']);
			$this->set_table_name($this->my_table);
			$this->set_where("$this->my_table.paypr_id = $paypr_id");
			return $this->update_record($data);
		}else{
			$this->error_message = 'ไม่พบข้อมูลที่เปลี่ยนแปลง';
		}
	}

	public function delete($post)
	{
		$paypr_id = checkEncryptData($post['encrypt_paypr_id']);
		$this->set_table_name($this->my_table);
		$this->set_where("$this->my_table.paypr_id = $paypr_id");
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