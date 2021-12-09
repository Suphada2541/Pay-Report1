<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * [ Controller File name : Payatm.php ]
 */
class Payatm extends CRUD_Controller
{

	private $per_page;
	private $another_js;
	private $another_css;

	public function __construct()
	{
		parent::__construct();
		// $this->per_page = 30;
		$this->num_links = 6;
		$this->uri_segment = 4;
		$this->load->model('reportuser/Payatm_model', 'Payatm');
		$this->data['page_url'] = site_url('reportuser/payatm');
		
		$this->data['page_title'] = 'THITARAM GROUP';

		$js_url = 'assets/js_modules/reportuser/payatm.js?ft='. filemtime('assets/js_modules/reportuser/payatm.js');
		$this->another_js .= '<script src="'. base_url($js_url) .'"></script>';
	}

	// ------------------------------------------------------------------------

	/**
	 * Index of controller
	 */
	public function index()
	{
		$this->list_all();
	}
	// ------------------------------------------------------------------------

	/**
	 * Render this controller page
	 * @param String path of controller
	 * @param Integer total record
	 */
	protected function render_view($path)
	{
		$this->data['top_navbar'] = $this->parser->parse('template/sb-admin-bs4/top_navbar_view', $this->top_navbar_data, TRUE);
		$this->data['left_sidebar'] = $this->parser->parse('template/sb-admin-bs4/left_sidebar_view', $this->left_sidebar_data, TRUE);
		$this->data['breadcrumb_list'] = $this->parser->parse('template/sb-admin-bs4/breadcrumb_view', $this->breadcrumb_data, TRUE);
		if($this->session->userdata('login_validated') == false){
			$this->data['page_content'] = $this->parser->parse_repeat('member_permission.php', $this->data, TRUE);
			$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->session->set_userdata('after_login_redirect', $current_url);
		}else{
			if($this->session->userdata('user_level') >= 5){
				$this->data['page_content'] = $this->parser->parse_repeat($path, $this->data, TRUE);
			}else{
				$this->data['alert_message'] = 'เฉพาะผู้ใช้งานระดับ <b></b>';
				$this->data['page_content'] = $this->parser->parse_repeat('member_authen_permission.php', $this->data, TRUE);
			}
		}
		$this->data['another_css'] = $this->another_css;
		$this->data['another_js'] = $this->another_js;
		$this->data['utilities_file_time'] = filemtime('assets/js/ci_utilities.js');
		$this->parser->parse('template/sb-admin-bs4/homepage_view', $this->data);
	}

	/**
	 * Set up pagination config
	 * @param String path of controller
	 * @param Integer total record
	 */
	public function create_pagination($page_url, $total) {
		$this->load->library('pagination');
		$config['base_url'] = $page_url;
		$config['total_rows'] = $total;
		$config['per_page'] = $this->per_page;
		$config['num_links'] = $this->num_links;
		$config['uri_segment'] = $this->uri_segment;
		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}

	// ------------------------------------------------------------------------

	/**
	 * List all record 
	 */
	public function list_all() {
		
		$this->session->unset_userdata($this->Payatm->session_name . '_search_field');
		$this->session->unset_userdata($this->Payatm->session_name . '_value');
		$this->session->unset_userdata($this->Payatm->session_name . '_bank');
		$this->session->unset_userdata($this->Payatm->session_name . '_appdate');
		$this->session->unset_userdata($this->Payatm->session_name . '_report');
		$this->search();
	}

	// ------------------------------------------------------------------------
	/**
	 * Create option list for Select box
	 */
	public function create_option_list($data)
	{
		$table = $data['table_name'];
		$field_value = $data['field_value'];
		$field_text = $data['field_text'];
		$field = $data['field_condition'];
		$search = $data['search_value'];
		$single_qoute = "'";
		if( $search[0] == "0" ) {
			$single_qoute = "'";
		}else{
			if (is_numeric($search)) {
				$single_qoute = "";
			}
		}
		$options = array();
		$options['where'] = "$field = {$single_qoute}$search{$single_qoute}";
		$options['order_by'] = "$field_text";
		$options['field_text_separator'] = " - ";
		return $this->Payatm->returnOptionList("$table", "$field_value", "$field_text", $options);
	}

	// ------------------------------------------------------------------------

	/**
	 * Search data
	 */
	public function search()
	{
		$this->breadcrumb_data['breadcrumb'] = array(
						array('title' => 'Mainpaypr', 'url' => site_url('reportuser/mainpaypr')),
						array('title' => 'Payatm', 'class' => 'active', 'url' => '#'),
		);

		$options1 = array();
		$this->data['tb_comppany_rf_company_id_option_list'] = $this->Payatm->returnOptionList("tb_company", "company_id", "company_name",$options1 );

		$this->data['tb_bank_rf_bank_id_option_list'] = $this->Payatm->returnOptionList("tb_bank", "bank_id", "bank_name");
		
		$options3 = array("where" => " report_type = 2 AND report_void = 0");
		$this->data['tb_report_report_id_option_list'] = $this->Payatm->returnOptionList("tb_report", "report_id", "CONCAT_WS(' - ',report_file,report_name)",$options3);
	
		if (isset($_POST['submit'])) {
			$search_field =  $this->input->post('search_field', TRUE);
			$value = $this->input->post('txtSearch', TRUE);
			$bank = $this->input->post('txtBank', TRUE);
			$appdate = $this->input->post('txtAppdate', TRUE);
			$report = $this->input->post('rf_report_id', TRUE);

			$arr = array(
				$this->Payatm->session_name . '_search_field' => $search_field, 
				$this->Payatm->session_name . '_value' => $value ,
				$this->Payatm->session_name . '_bank' => $bank ,
				$this->Payatm->session_name . '_appdate' => $appdate,
				$this->Payatm->session_name . '_report' => $report
			);

			$this->session->set_userdata($arr);	 
			
		} else {
			$search_field = $this->session->userdata($this->Payatm->session_name . '_search_field');
			$value = $this->session->userdata($this->Payatm->session_name . '_value');
			$bank = $this->session->userdata($this->Payatm->session_name . '_bank');
			$appdate = $this->session->userdata($this->Payatm->session_name . '_appdate');
			$report = $this->session->userdata($this->Payatm->session_name . '_report');
		}

		$start_row = $this->uri->segment($this->uri_segment ,'0');
		if(!is_numeric($start_row)){
			$start_row = 0;
		}
		$per_page = $this->per_page;
		$order_by =  $this->input->post('order_by', TRUE);
		if ($order_by != '') {
			$arr = explode('|', $order_by);
			$field = $arr[0];
			$sort = $arr[1];
			switch($sort){
				case 'asc':$sort = 'ASC';break;
				case 'desc':$sort = 'DESC';break;
				default:$sort = 'DESC';break;
			}
			$this->Payatm->order_field = $field;
			$this->Payatm->order_sort = $sort;
		}
		$results = $this->Payatm->read($start_row, $per_page);
		$total_row = $results['total_row'];
		$search_row = $results['search_row'];
		$list_data = $this->setDataListFormat($results['list_data'], $start_row);


		$page_url = site_url('reportuser/payatm');
		$pagination = $this->create_pagination($page_url.'/search', $search_row);
		$end_row = $start_row + $per_page;
		if($search_row < $per_page){
			$end_row = $search_row;
		}

		if($end_row > $search_row){
			$end_row = $search_row;
		}

		$this->data['data_list']	= $list_data;
		$this->data['search_field']	= $search_field;
		$this->data['txt_search']	= $value;
		$this->data['txt_bank']	= $bank;
		$this->data['txt_appdate']	= $appdate;
		$this->data['txt_report'] = $report;
		$this->data['current_path_uri'] = uri_string();
		$this->data['current_page_offset'] = $start_row;
		$this->data['start_row']	= $start_row + 1;
		$this->data['end_row']	= $end_row;
		$this->data['order_by']	= $order_by;
		$this->data['total_row']	= $total_row;
		$this->data['search_datarow']	= $search_row;
		$this->data['search_row']	= $search_row;
		$this->data['page_url']	= $page_url;
		$this->data['pagination_link']	= $pagination;
		$this->data['csrf_protection_field']	= insert_csrf_field(true);

		$this->render_view('reportuser/payatm/list_view');
	}
	
	/**
	 * SET โชว์วันที่ 0000/00/00 เป็นค่าว่าง
	 */
	private function setDateView($value)
	{
		$subject = '';
					
		if($value >= 1){
		$subject = setThaiDate($value);					
		}
		// elseif($value == '0000-00-00' || $value != '00/00/0000'){
		// 	$subject = '';
		// }
		else{
			$subject = '';
		}
		return $subject;
	}

	public function setdatazero($value)
	 {
	  $subject = '';
	   if ($value == 0) {
	       $subject = '';
	   } else {
	       $subject = $value ;
	   }

	   return $subject;
	 }

	 	
	public function informdate($dateday){
		
		$date=date_create("$dateday");
		date_modify($date,"-1 days");
		$DayInform = date_format($date,"Y-m-d");
		return $DayInform;
	}

	// ------------------------------------------------------------------------
	
	/**
	 * SET array data list
	 */
	private function setDataListFormat($lists_data, $start_row=0)
	{
		$data = $lists_data;
		$count = count($lists_data);
		for($i=0;$i<$count;$i++){
			$start_row++;
			$data[$i]['record_number'] = $start_row;
			$pk1 = $data[$i]['paypr_id'];
			$data[$i]['url_encrypt_id'] = urlencode(encrypt($pk1));

			if($pk1 != ''){
				$pk1 = ci_encrypt($pk1);
			}
			
			$data[$i]['encrypt_paypr_id'] = $pk1;
			$data[$i]['paypr_wage'] = number_format($data[$i]['paypr_wage'],2);
			$data[$i]['paypr_absent_num'] = number_format($data[$i]['paypr_absent_num'],2);
			$data[$i]['paypr_absent_pay'] = number_format($data[$i]['paypr_absent_pay'],2);
			$data[$i]['paypr_bnleave_num'] = number_format($data[$i]['paypr_bnleave_num'],2);
			$data[$i]['paypr_bnleave_pay'] = number_format($data[$i]['paypr_bnleave_pay'],2);
			$data[$i]['paypr_bleave_num'] = number_format($data[$i]['paypr_bleave_num'],2);
			$data[$i]['paypr_bleave_pay'] = number_format($data[$i]['paypr_bleave_pay'],2);
			$data[$i]['paypr_aleave_num'] = number_format($data[$i]['paypr_aleave_num'],2);
			$data[$i]['paypr_aleave_pay'] = number_format($data[$i]['paypr_aleave_pay'],2);
			$data[$i]['paypr_sleave_num'] = number_format($data[$i]['paypr_sleave_num'],2);
			$data[$i]['paypr_sleave_pay'] = number_format($data[$i]['paypr_sleave_pay'],2);
			$data[$i]['paypr_adleave_pay'] = number_format($data[$i]['paypr_adleave_pay'],2);
			$data[$i]['paypr_adleave_num'] = number_format($data[$i]['paypr_adleave_num'],2);
			$data[$i]['paypr_ahleave_pay'] = number_format($data[$i]['paypr_ahleave_pay'],2);
			$data[$i]['paypr_ahleave_num'] = number_format($data[$i]['paypr_ahleave_num'],2);
			$data[$i]['paypr_mleave_pay'] = number_format($data[$i]['paypr_mleave_pay'],2);
			$data[$i]['paypr_mleave_num'] = number_format($data[$i]['paypr_mleave_num'],2);
			$data[$i]['paypr_hleave_num'] = number_format($data[$i]['paypr_hleave_num'],2);
			$data[$i]['paypr_hleave_pay'] = number_format($data[$i]['paypr_hleave_pay'],2);
			$data[$i]['paypr_oleave_pay'] = number_format($data[$i]['paypr_oleave_pay'],2);
			$data[$i]['paypr_oleave_num'] = number_format($data[$i]['paypr_oleave_num'],2);
			$data[$i]['paypr_leave_total'] = number_format($data[$i]['paypr_leave_total'],2);
			$data[$i]['paypr_works1_num'] = number_format($data[$i]['paypr_works1_num'],2);
			$data[$i]['paypr_works1_pay'] = number_format($data[$i]['paypr_works1_pay'],2);
			$data[$i]['paypr_works2_pay'] = number_format($data[$i]['paypr_works2_pay'],2);
			$data[$i]['paypr_works2_num'] = number_format($data[$i]['paypr_works2_num'],2);
			$data[$i]['paypr_works3_pay'] = number_format($data[$i]['paypr_works3_pay'],2);
			$data[$i]['paypr_works3_num'] = number_format($data[$i]['paypr_works3_num'],2);
			$data[$i]['paypr_shutdown_num'] = number_format($data[$i]['paypr_shutdown_num'],2);
			$data[$i]['paypr_shutdown_pay'] = number_format($data[$i]['paypr_shutdown_pay'],2);
			$data[$i]['paypr_work_num'] = number_format($data[$i]['paypr_work_num'],2);
			$data[$i]['paypr_work_pay'] = number_format($data[$i]['paypr_work_pay'],2);
			$data[$i]['paypr_wsum_num'] = number_format($data[$i]['paypr_wsum_num'],2);
			$data[$i]['paypr_wsum_pay'] = number_format($data[$i]['paypr_wsum_pay'],2);
			$data[$i]['paypr_declare'] = number_format($data[$i]['paypr_declare'],2);
			$data[$i]['paypr_number1'] = number_format($data[$i]['paypr_number1'],2);
			$data[$i]['paypr_number2'] = number_format($data[$i]['paypr_number2'],2);
			$data[$i]['paypr_number3'] = number_format($data[$i]['paypr_number3'],2);
			$data[$i]['paypr_number4'] = number_format($data[$i]['paypr_number4'],2);
			$data[$i]['paypr_number5'] = number_format($data[$i]['paypr_number5'],2);
			$data[$i]['paypr_sevrance'] = number_format($data[$i]['paypr_sevrance'],2);
			$data[$i]['paypr_assurance_pay'] = number_format($data[$i]['paypr_assurance_pay'],2);
			$data[$i]['paypr_shift'] = number_format($data[$i]['paypr_shift'],2);
			$data[$i]['paypr_meal'] = number_format($data[$i]['paypr_meal'],2);
			$data[$i]['paypr_car'] = number_format($data[$i]['paypr_car'],2);
			$data[$i]['paypr_diligent'] = number_format($data[$i]['paypr_diligent'],2);
			$data[$i]['paypr_etc'] = number_format($data[$i]['paypr_etc'],2);
			$data[$i]['paypr_bonus'] = number_format($data[$i]['paypr_bonus'],2);
			$data[$i]['paypr_cola'] = number_format($data[$i]['paypr_cola'],2);
			$data[$i]['paypr_telephone'] = number_format($data[$i]['paypr_telephone'],2);
			$data[$i]['paypr_skill'] = number_format($data[$i]['paypr_skill'],2);
			$data[$i]['paypr_position'] = number_format($data[$i]['paypr_position'],2);
			$data[$i]['paypr_gas'] = number_format($data[$i]['paypr_gas'],2);
			$data[$i]['paypr_incentive'] = number_format($data[$i]['paypr_incentive'],2);
			$data[$i]['paypr_profession'] = number_format($data[$i]['paypr_profession'],2);
			$data[$i]['paypr_license'] = number_format($data[$i]['paypr_license'],2);
			$data[$i]['paypr_childship'] = number_format($data[$i]['paypr_childship'],2);
			$data[$i]['paypr_medical'] = number_format($data[$i]['paypr_medical'],2);
			$data[$i]['paypr_carde'] = number_format($data[$i]['paypr_carde'],2);
			$data[$i]['paypr_uptravel'] = number_format($data[$i]['paypr_uptravel'],2);
			$data[$i]['paypr_stay'] = number_format($data[$i]['paypr_stay'],2);
			$data[$i]['paypr_subsidy'] = number_format($data[$i]['paypr_subsidy'],2);
			$data[$i]['paypr_other'] = number_format($data[$i]['paypr_other'],2);
			$data[$i]['paypr_income1'] = number_format($data[$i]['paypr_income1'],2);
			$data[$i]['paypr_income2'] = number_format($data[$i]['paypr_income2'],2);
			$data[$i]['paypr_income3'] = number_format($data[$i]['paypr_income3'],2);
			$data[$i]['paypr_income4'] = number_format($data[$i]['paypr_income4'],2);
			$data[$i]['paypr_income5'] = number_format($data[$i]['paypr_income5'],2);
			$data[$i]['paypr_income6'] = number_format($data[$i]['paypr_income6'],2);
			$data[$i]['paypr_income7'] = number_format($data[$i]['paypr_income7'],2);
			$data[$i]['paypr_income8'] = number_format($data[$i]['paypr_income8'],2);
			$data[$i]['paypr_income9'] = number_format($data[$i]['paypr_income9'],2);
			$data[$i]['paypr_income10'] = number_format($data[$i]['paypr_income10'],2);
			$data[$i]['paypr_income11'] = number_format($data[$i]['paypr_income11'],2);
			$data[$i]['paypr_income12'] = number_format($data[$i]['paypr_income12'],2);
			$data[$i]['paypr_income13'] = number_format($data[$i]['paypr_income13'],2);
			$data[$i]['paypr_income14'] = number_format($data[$i]['paypr_income14'],2);
			$data[$i]['paypr_income15'] = number_format($data[$i]['paypr_income15'],2);
			$data[$i]['paypr_income16'] = number_format($data[$i]['paypr_income16'],2);
			$data[$i]['paypr_income17'] = number_format($data[$i]['paypr_income17'],2);
			$data[$i]['paypr_income18'] = number_format($data[$i]['paypr_income18'],2);
			$data[$i]['paypr_income19'] = number_format($data[$i]['paypr_income19'],2);
			$data[$i]['paypr_income20'] = number_format($data[$i]['paypr_income20'],2);
			$data[$i]['paypr_income_sum'] = number_format($data[$i]['paypr_income_sum'],2);
			$data[$i]['paypr_ot100_num'] = number_format($data[$i]['paypr_ot100_num'],2);
			$data[$i]['paypr_ot100_pay'] = number_format($data[$i]['paypr_ot100_pay'],2);
			$data[$i]['paypr_ot150_num'] = number_format($data[$i]['paypr_ot150_num'],2);
			$data[$i]['paypr_ot150_pay'] = number_format($data[$i]['paypr_ot150_pay'],2);
			$data[$i]['paypr_ot200_num'] = number_format($data[$i]['paypr_ot200_num'],2);
			$data[$i]['paypr_ot200_pay'] = number_format($data[$i]['paypr_ot200_pay'],2);
			$data[$i]['paypr_ot300_num'] = number_format($data[$i]['paypr_ot300_num'],2);
			$data[$i]['paypr_ot300_pay'] = number_format($data[$i]['paypr_ot300_pay'],2);
			$data[$i]['paypr_otsum0_pay'] = number_format($data[$i]['paypr_otsum0_pay'],2);
			$data[$i]['paypr_ot101_num'] = number_format($data[$i]['paypr_ot101_num'],2);
			$data[$i]['paypr_ot101_pay'] = number_format($data[$i]['paypr_ot101_pay'],2);
			$data[$i]['paypr_ot105_num'] = number_format($data[$i]['paypr_ot105_num'],2);
			$data[$i]['paypr_ot105_pay'] = number_format($data[$i]['paypr_ot105_pay'],2);
			$data[$i]['paypr_ot102_num'] = number_format($data[$i]['paypr_ot102_num'],2);
			$data[$i]['paypr_ot102_pay'] = number_format($data[$i]['paypr_ot102_pay'],2);
			$data[$i]['paypr_ot103_num'] = number_format($data[$i]['paypr_ot103_num'],2);
			$data[$i]['paypr_ot103_pay'] = number_format($data[$i]['paypr_ot103_pay'],2);
			$data[$i]['paypr_ot104_num'] = number_format($data[$i]['paypr_ot104_num'],2);
			$data[$i]['paypr_ot104_pay'] = number_format($data[$i]['paypr_ot104_pay'],2);
			$data[$i]['paypr_otsum1_pay'] = number_format($data[$i]['paypr_otsum1_pay'],2);
			$data[$i]['paypr_salary_total'] = number_format($data[$i]['paypr_salary_total'],2);
			$data[$i]['paypr_de_assurance'] = number_format($data[$i]['paypr_de_assurance'],2);
			$data[$i]['paypr_de_uniform'] = number_format($data[$i]['paypr_de_uniform'],2);
			$data[$i]['paypr_de_card'] = number_format($data[$i]['paypr_de_card'],2);
			$data[$i]['paypr_de_cooperative'] = number_format($data[$i]['paypr_de_cooperative'],2);
			$data[$i]['paypr_de_lond'] = number_format($data[$i]['paypr_de_lond'],2);
			$data[$i]['paypr_de_borrow'] = number_format($data[$i]['paypr_de_borrow'],2);
			$data[$i]['paypr_de_elond'] = number_format($data[$i]['paypr_de_elond'],2);
			$data[$i]['paypr_de_mobile'] = number_format($data[$i]['paypr_de_mobile'],2);
			$data[$i]['paypr_de_backtravel'] = number_format($data[$i]['paypr_de_backtravel'],2);
			$data[$i]['paypr_de_backother'] = number_format($data[$i]['paypr_de_backother'],2);
			$data[$i]['paypr_de_selfemp'] = number_format($data[$i]['paypr_de_selfemp'],2);
			$data[$i]['paypr_de_health'] = number_format($data[$i]['paypr_de_health'],2);
			$data[$i]['paypr_de_debtcase'] = number_format($data[$i]['paypr_de_debtcase'],2);
			$data[$i]['paypr_de_pernicious'] = number_format($data[$i]['paypr_de_pernicious'],2);
			$data[$i]['paypr_de_visa'] = number_format($data[$i]['paypr_de_visa'],2);
			$data[$i]['paypr_de_work_p'] = number_format($data[$i]['paypr_de_work_p'],2);
			$data[$i]['paypr_de_outother'] = number_format($data[$i]['paypr_de_outother'],2);
			$data[$i]['paypr_de_out1'] = number_format($data[$i]['paypr_de_out1'],2);
			$data[$i]['paypr_de_out2'] = number_format($data[$i]['paypr_de_out2'],2);
			$data[$i]['paypr_de_out3'] = number_format($data[$i]['paypr_de_out3'],2);
			$data[$i]['paypr_de_out4'] = number_format($data[$i]['paypr_de_out4'],2);
			$data[$i]['paypr_de_out5'] = number_format($data[$i]['paypr_de_out5'],2);
			$data[$i]['paypr_de_absent'] = number_format($data[$i]['paypr_de_absent'],2);
			$data[$i]['paypr_de_late'] = number_format($data[$i]['paypr_de_late'],2);
			$data[$i]['paypr_de_mulct'] = number_format($data[$i]['paypr_de_mulct'],2);
			$data[$i]['paypr_de_works1p'] = number_format($data[$i]['paypr_de_works1p'],2);
			$data[$i]['paypr_de_works2p'] = number_format($data[$i]['paypr_de_works2p'],2);
			$data[$i]['paypr_de_works3p'] = number_format($data[$i]['paypr_de_works3p'],2);
			$data[$i]['paypr_de_total'] = number_format($data[$i]['paypr_de_total'],2);
			$data[$i]['paypr_salary_net'] = number_format($data[$i]['paypr_salary_net'],2);
			$data[$i]['paypr_de_atm'] = number_format($data[$i]['paypr_de_atm'],2);
			$data[$i]['paypr_de_ssop'] = number_format($data[$i]['paypr_de_ssop'],2);
			$data[$i]['paypr_de_ssoc'] = number_format($data[$i]['paypr_de_ssoc'],2);
			$data[$i]['paypr_de_funp'] = number_format($data[$i]['paypr_de_funp'],2);
			$data[$i]['paypr_de_func'] = number_format($data[$i]['paypr_de_func'],2);
			$data[$i]['paypr_de_tax'] = number_format($data[$i]['paypr_de_tax'],2);
			$data[$i]['paypr_calcmony_sso'] = number_format($data[$i]['paypr_calcmony_sso'],2);
			$data[$i]['paypr_calcmony_fun'] = number_format($data[$i]['paypr_calcmony_fun'],2);
			$data[$i]['paypr_calcmony_tax2'] = number_format($data[$i]['paypr_calcmony_tax2'],2);
			$data[$i]['paypr_calcmony_tax3'] = number_format($data[$i]['paypr_calcmony_tax3'],2);
			$data[$i]['paypr_calcmony_tax4'] = number_format($data[$i]['paypr_calcmony_tax4'],2);
			$data[$i]['paypr_totalsalary'] = number_format($data[$i]['paypr_totalsalary'],2);
			$data[$i]['paypr_totalsso'] = number_format($data[$i]['paypr_totalsso'],2);
			$data[$i]['paypr_totalfun'] = number_format($data[$i]['paypr_totalfun'],2);
			$data[$i]['paypr_totaltax'] = number_format($data[$i]['paypr_totaltax'],2);
			$data[$i]['paypr_totalmony'] = number_format($data[$i]['paypr_totalmony'],2);
			$data[$i]['paypr_totalno'] = number_format($data[$i]['paypr_totalno'],2);
			$data[$i]['paypr_create_date'] = $this->setDateView($data[$i]['paypr_create_date']);
			$data[$i]['paypr_modify_date'] = $this->setDateView($data[$i]['paypr_modify_date']);
			$data[$i]['rfPayIdPayAppdate'] = $this->setDateView($data[$i]['rfPayIdPayAppdate']);
		}
		return $data;
	}

	// ------------------------------------------------------------------------	

		public function export_payprbra_report()
	{
		$report = $this->session->userdata('_report');

		if ($report == 10) //Data ATM Convert 
		{
			$this->genreport_200Excel();
		}
		else
		{
			return $report;
		}
	}

	public function genreport_200Excel() 
	{	
		$this->load->library('reportuser/Excel');
		
		$results = $this->Payatm->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
       
		// set Header ***** SECTION 1 ***** 
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'เลขที่บัญชี');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'จำนวนเงิน');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Code');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'สาขา');

		// END SECTION 1
		
		// set header bold
		$objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold( true );

		$rowCount = 2;
		foreach ($data_lists as $row)
		{
			// ***** SECTION 2 *****
			$sheet = $objPHPExcel->getActiveSheet();
			$sheet->setCellValueExplicit('A' . $rowCount, $row['rfPernameIdPreName'].$row['rfNameIdEmpName']. ' ' .$row['rfNameIdEmpSurname']);
			$sheet->setCellValueExplicit('B' . $rowCount, $row['rfNameIdBankAccount'], PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->SetCellValue('C' . $rowCount, $row['paypr_salary_net']);
			$sheet->setCellValueExplicit('D' . $rowCount, $row['rfBankIdBankCode'], PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit('E' . $rowCount, $row['rfBranchIdBranchNick'], PHPExcel_Cell_DataType::TYPE_STRING);
			$rowCount++;
		}

		foreach(range('A','E') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		$filename = "Genpayatm_200Excel". date("Y-m-d-H-i-s").".xlsx";
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0'); 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  

		$objWriter->save('php://output'); 
	}
			
}
/*---------------------------- END Controller Class --------------------------------*/
