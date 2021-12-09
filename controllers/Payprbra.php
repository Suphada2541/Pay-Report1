<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * [ Controller File name : Payprbra.php ]
 */
class Payprbra extends CRUD_Controller
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
		$this->load->model('reportuser/Payprbra_model', 'Payprbra');
		$this->data['page_url'] = site_url('reportuser/payprbra');
		
		$this->data['page_title'] = 'THITARAM GROUP';

		$js_url = 'assets/js_modules/reportuser/payprbra.js?ft='. filemtime('assets/js_modules/reportuser/payprbra.js');
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
		
		$this->session->unset_userdata($this->Payprbra->session_name . '_search_field');
		$this->session->unset_userdata($this->Payprbra->session_name . '_value');
		$this->session->unset_userdata($this->Payprbra->session_name . '_paynum');
		$this->session->unset_userdata($this->Payprbra->session_name . '_report');
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
		return $this->Payprbra->returnOptionList("$table", "$field_value", "$field_text", $options);
	}


	// ------------------------------------------------------------------------


	public function createRfBranchIdPaymentOptionList()
	{
		$search_value = $this->input->post('search_value', TRUE);
		$data = array();
		$data['table_name'] = 'tb_payment';
		$data['field_value'] = 'pay_id';
		$data['field_text'] = ' pay_num, pay_fromdate, pay_todate';
		$data['field_condition'] = 'rf_branch_id';
		$data['search_value'] = $search_value;
		echo $this->create_option_list($data);
	}

	/**
	 * Search data
	 */
	public function search()
	{
		$this->breadcrumb_data['breadcrumb'] = array(
						array('title' => 'Mainpaypr', 'url' => site_url('reportuser/mainpaypr')),
						array('title' => 'Payprbra', 'class' => 'active', 'url' => '#'),
		);

		$options1 = array("where" => " branch_void = 0");
		$this->data['tb_branch_rf_branch_id_option_list'] = $this->Payprbra->returnOptionList("tb_branch", "branch_id", "branch_nick",$options1 );

		$this->data['tb_payment_rf_pay_id_option_list'] = $this->Payprbra->returnOptionList("tb_payment", "pay_id", "CONCAT_WS(' - ', pay_num,pay_fromdate,pay_todate)");

		$options3 = array("where" => " report_type = 1 AND report_void = 0");
		$this->data['tb_report_report_id_option_list'] = $this->Payprbra->returnOptionList("tb_report", "report_id", "CONCAT_WS(' - ',report_file,report_name)",$options3);


		if (isset($_POST['submit'])) {
			$search_field =  $this->input->post('search_field', TRUE);
			$value = $this->input->post('txtSearch', TRUE);
			$paynum = $this->input->post('rf_pay_id', TRUE);
			$report = $this->input->post('rf_report_id', TRUE);

			$arr = array(
				$this->Payprbra->session_name . '_search_field' => $search_field, 
				$this->Payprbra->session_name . '_value' => $value ,
				$this->Payprbra->session_name . '_paynum' => $paynum,
				$this->Payprbra->session_name . '_report' => $report
			);

			$this->session->set_userdata($arr);	 
			
		} else {
			$search_field = $this->session->userdata($this->Payprbra->session_name . '_search_field');
			$value = $this->session->userdata($this->Payprbra->session_name . '_value');
			$paynum = $this->session->userdata($this->Payprbra->session_name . '_paynum');
			$report = $this->session->userdata($this->Payprbra->session_name . '_report');
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
			$this->Payprbra->order_field = $field;
			$this->Payprbra->order_sort = $sort;
		}
		$results = $this->Payprbra->read($start_row, $per_page);
		$total_row = $results['total_row'];
		$search_row = $results['search_row'];
		$list_data = $this->setDataListFormat($results['list_data'], $start_row);


		$page_url = site_url('reportuser/payprbra');
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
		$this->data['txt_pnum']	= $paynum;
		$this->data['txt_report'] = $report;
		$this->data['current_path_uri'] = uri_string();
		$this->data['current_page_offset'] = $start_row;
		$this->data['start_row']	= $start_row + 1;
		$this->data['end_row']	= $end_row;
		$this->data['order_by']	= $order_by;
		$this->data['total_row']	= $total_row;
		$this->data['search_datarow'] = $search_row;
		$this->data['search_row'] = $search_row;
		$this->data['page_url']	= $page_url;
		$this->data['pagination_link']	= $pagination;
		$this->data['csrf_protection_field'] = insert_csrf_field(true);

		$this->render_view('reportuser/payprbra/list_view');
	}

	// ------------------------------------------------------------------------
	
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

	 public function setdatazeroformat($value)
	 {
	  $subject = '';
	   if ($value == 0) {
	       $subject = '';
	   } else {
	       $subject = number_format($value,2) ;
	   }

	   return $subject;
	 }

	 public function setdatazeroPDF($value,$object)
	 {
	  $subject = '';
	   if ($value == 0 AND $object == 0) {
	       	$subject = '';
	   } else if($value != 0 AND $object == 0){
	       	$subject = "<hr>"." ค่าชดเชย &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . number_format($value,2) ;
	   } else if($value == 0 AND $object != 0){
			$subject = "<hr>"." ค่าบอกกล่าว &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . number_format($object,2) ;
	   }else{
		   
			$subject = "<hr>"." ค่าชดเชย &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .number_format($value,2)
			."<br>". " ค่าบอกกล่าว &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . number_format($object,2) ;
	   }

	   return $subject;
	 }

	// ------------------------------------------------------------------------
	/**
	 * SET choice subject
	 */
	private function setdatapayviewSubject($value)
	{
		$subject = '';
		switch($value){
			case 0:
				$subject = 'YES';
				break;
			case 1:
				$subject = 'NO';
				break;
		}
		return $subject;
	}

	function textFormat( $text = '', $pattern = '', $ex = '' ) {
		$cid = ( $text == '' ) ? '0000000000000' : $text;
		$pattern = ( $pattern == '' ) ? '_-____-_____-__-_' : $pattern;
		$p = explode( '-', $pattern );
		$ex = ( $ex == '' ) ? '-' : $ex;
		$first = 0;
		$last = 0;
		for ( $i = 0; $i <= count( $p ) - 1; $i++ ) {
		   $first = $first + $last;
		   $last = strlen( $p[$i]);
		   $returnText[$i] = substr( $cid, $first, $last );
		}
	  
		return implode( $ex, $returnText );
	 }

	public function informdate($dateday){
		
		$date=date_create("$dateday");
		date_modify($date,"-1 days");
		$DayInform = date_format($date,"Y-m-d");
		return $DayInform;
	}

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
		}
		return $data;
	}

	private function setDataListFormatSlip($lists_data, $start_row=0)
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
			$data[$i]['paypr_works1_num'] = $this->setdatazeroformat($data[$i]['paypr_works1_num']);
			$data[$i]['paypr_works1_pay'] = $this->setdatazeroformat($data[$i]['paypr_works1_pay']);
			$data[$i]['paypr_works2_pay'] = $this->setdatazeroformat($data[$i]['paypr_works2_pay']);
			$data[$i]['paypr_works2_num'] = $this->setdatazeroformat($data[$i]['paypr_works2_num']);
			$data[$i]['paypr_works3_pay'] = $this->setdatazeroformat($data[$i]['paypr_works3_pay']);
			$data[$i]['paypr_works3_num'] = $this->setdatazeroformat($data[$i]['paypr_works3_num']);
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
			$data[$i]['paypr_income1'] = $this->setdatazeroformat($data[$i]['paypr_income1']);
			$data[$i]['paypr_income2'] = $this->setdatazeroformat($data[$i]['paypr_income2']);
			$data[$i]['paypr_income3'] = $this->setdatazeroformat($data[$i]['paypr_income3']);
			$data[$i]['paypr_income4'] = $this->setdatazeroformat($data[$i]['paypr_income4']);
			$data[$i]['paypr_income5'] = $this->setdatazeroformat($data[$i]['paypr_income5']);
			$data[$i]['paypr_income6'] = $this->setdatazeroformat($data[$i]['paypr_income6']);
			$data[$i]['paypr_income7'] = $this->setdatazeroformat($data[$i]['paypr_income7']);
			$data[$i]['paypr_income8'] = $this->setdatazeroformat($data[$i]['paypr_income8']);
			$data[$i]['paypr_income9'] = $this->setdatazeroformat($data[$i]['paypr_income9']);
			$data[$i]['paypr_income10'] = $this->setdatazeroformat($data[$i]['paypr_income10']);
			$data[$i]['paypr_income11'] = $this->setdatazeroformat($data[$i]['paypr_income11']);
			$data[$i]['paypr_income12'] = $this->setdatazeroformat($data[$i]['paypr_income12']);
			$data[$i]['paypr_income13'] = $this->setdatazeroformat($data[$i]['paypr_income13']);
			$data[$i]['paypr_income14'] = $this->setdatazeroformat($data[$i]['paypr_income14']);
			$data[$i]['paypr_income15'] = $this->setdatazeroformat($data[$i]['paypr_income15']);
			$data[$i]['paypr_income16'] = $this->setdatazeroformat($data[$i]['paypr_income16']);
			$data[$i]['paypr_income17'] = $this->setdatazeroformat($data[$i]['paypr_income17']);
			$data[$i]['paypr_income18'] = $this->setdatazeroformat($data[$i]['paypr_income18']);
			$data[$i]['paypr_income19'] = $this->setdatazeroformat($data[$i]['paypr_income19']);
			$data[$i]['paypr_income20'] = $this->setdatazeroformat($data[$i]['paypr_income20']);
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
			$data[$i]['paypr_ot101_num'] = $this->setdatazeroformat($data[$i]['paypr_ot101_num']);
			$data[$i]['paypr_ot101_pay'] = $this->setdatazeroformat($data[$i]['paypr_ot101_pay']);
			$data[$i]['paypr_ot105_num'] = $this->setdatazeroformat($data[$i]['paypr_ot105_num']);
			$data[$i]['paypr_ot105_pay'] = $this->setdatazeroformat($data[$i]['paypr_ot105_pay']);
			$data[$i]['paypr_ot102_num'] = $this->setdatazeroformat($data[$i]['paypr_ot102_num']);
			$data[$i]['paypr_ot102_pay'] = $this->setdatazeroformat($data[$i]['paypr_ot102_pay']);
			$data[$i]['paypr_ot103_num'] = $this->setdatazeroformat($data[$i]['paypr_ot103_num']);
			$data[$i]['paypr_ot103_pay'] = $this->setdatazeroformat($data[$i]['paypr_ot103_pay']);
			$data[$i]['paypr_ot104_num'] = $this->setdatazeroformat($data[$i]['paypr_ot104_num']);
			$data[$i]['paypr_ot104_pay'] = $this->setdatazeroformat($data[$i]['paypr_ot104_pay']);
			$data[$i]['paypr_otsum1_pay'] = $this->setdatazeroformat($data[$i]['paypr_otsum1_pay'],2);
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
			$data[$i]['paypr_de_visa'] = $this->setdatazeroformat($data[$i]['paypr_de_visa'],2);
			$data[$i]['paypr_de_work_p'] = $this->setdatazeroformat($data[$i]['paypr_de_work_p'],2);
			$data[$i]['paypr_de_outother'] = number_format($data[$i]['paypr_de_outother'],2);
			$data[$i]['paypr_de_out1'] = $this->setdatazeroformat($data[$i]['paypr_de_out1']);
			$data[$i]['paypr_de_out2'] = $this->setdatazeroformat($data[$i]['paypr_de_out2']);
			$data[$i]['paypr_de_out3'] = $this->setdatazeroformat($data[$i]['paypr_de_out3']);
			$data[$i]['paypr_de_out4'] = $this->setdatazeroformat($data[$i]['paypr_de_out4']);
			$data[$i]['paypr_de_out5'] = $this->setdatazeroformat($data[$i]['paypr_de_out5']);
			$data[$i]['paypr_de_absent'] = $this->setdatazeroformat($data[$i]['paypr_de_absent']);
			$data[$i]['paypr_de_late'] = $this->setdatazeroformat($data[$i]['paypr_de_late']);
			$data[$i]['paypr_de_mulct'] = $this->setdatazeroformat($data[$i]['paypr_de_mulct']);
			$data[$i]['paypr_de_works1p'] = $this->setdatazeroformat($data[$i]['paypr_de_works1p']);
			$data[$i]['paypr_de_works2p'] = $this->setdatazeroformat($data[$i]['paypr_de_works2p']);
			$data[$i]['paypr_de_works3p'] = $this->setdatazeroformat($data[$i]['paypr_de_works3p']);
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
			$data[$i]['rfPayIdPayFromdate'] = $this->setDateView($data[$i]['rfPayIdPayFromdate']);
			$data[$i]['rfPayIdPayTodate'] = $this->setDateView($data[$i]['rfPayIdPayTodate']);
			$data[$i]['rfPayIdPayAppdate'] = $this->setDateView($data[$i]['rfPayIdPayAppdate']);
			$data[$i]['rfPayIdPayInformdate'] = $this->setDateView($this->informdate($data[$i]['rfPayIdPayInformdate']));
			$data[$i]['rfNameIdStartDate'] = $this->setDateView($this->informdate($data[$i]['rfNameIdStartDate']));
			$data[$i]['rfPayaheadIdAheadPay'] = number_format($data[$i]['rfPayaheadIdAheadPay'],2);
			$data[$i]['sumPaysalary'] = number_format($data[$i]['sumPaysalary'],2);
			$data[$i]['paypr_income_1'] = number_format($data[$i]['paypr_income_1'],2);
			$data[$i]['paypr_income_2'] = number_format($data[$i]['paypr_income_2'],2);
			$data[$i]['paypr_de_income_1'] = number_format($data[$i]['paypr_de_income_1'],2);
			$data[$i]['paypr_de_income_2'] = number_format($data[$i]['paypr_de_income_2'],2);
			$data[$i]['rfNameIdNumCard'] = $this->textFormat($data[$i]['rfNameIdNumCard']);
			$data[$i]['wageToMonth'] = number_format($data[$i]['paypr_wage']*30,2);
			$data[$i]['payprSevrancePayprDeclare'] = $this->setdatazeroPDF($data[$i]['paypr_sevrance'],$data[$i]['paypr_declare']);
			}
		return $data;
	}

	/**
	 * SET array data list
	 */
	private function setDataListFormatExcel($lists_data, $start_row=0)
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
			$data[$i]['rfNameIdStartDate'] = $this->setDateView($data[$i]['rfNameIdStartDate']);
		}
		return $data;
	}

	public function export_payprbra_report()
	{
		$report = $this->session->userdata('_report');

		if ($report == 1) //Payroll จ่ายพนักงาน 1  มารตาฐาน
		{
			$this->genreport_101Pdf();
		}
		elseif ($report == 2)  //Payroll จ่ายพนักงาน 2  กิจ / Shut Down
		{
			$this->genreport_102Pdf();
		}
		elseif ($report == 3) //Payroll จ่ายพนักงาน 3  ชมโอทีพิเศษ ไม่มีรายหัก
		{
			$this->genreport_103Pdf();
		}
		elseif ($report == 4) //Payroll จ่ายพนักงาน 4  ชมโอที พิเศษ
		{
			$this->genreport_104Pdf();
		}
		elseif ($report == 5) //Payroll จ่ายพนักงาน 5  ไม่โชว์รายการหัก
		{
			$this->genreport_105Pdf();
		}
		elseif ($report == 6) //Silp 1  เงินเดือนพนักงาน(ATM)คาร์บอน
		{
			$this->genreport_106Pdf();
		}
		elseif ($report == 7) //Silp 1  เงินเดือนพนักงาน(เงินสด)คาร์บอน
		{
			$this->genreport_107Pdf();
		}
		elseif ($report == 8) //กองทุน-รายการหักกองทุนประจำงวด
		{
			$this->genreport_120Excel();
		}
		elseif ($report == 9) //Silp Data Excel Format Makub
		{
			$this->genreport_150Excel();
		}
		elseif ($report == 16) //Payroll ทะเบียนจ่ายค่าจ้าง Data Excel
		{
			$this->genreport_101Excel();
		}
		else
		{
			$this->list_all();
		}
	}

	public function genreport_101Pdf() 
	{
		// load PDF library
		$this->load->library('reportuser/Payprbra_preview_pdf');
		
		$results = $this->Payprbra->check_pdf();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);

		foreach ($data_lists as $row)
		{
			$rfBranchIdCompanyNick = $row['rfBranchIdCompanyNick'];
			$rfBranchIdBranchName = $row['rfBranchIdBranchName'];
			$rfBranchIdBranchCode = $row['rfBranchIdBranchCode'];
			$rfPayIdPayFromdate =  $this->setDateView($row['rfPayIdPayFromdate']);
			$rfPayIdPayTodate =  $this->setDateView($row['rfPayIdPayTodate']);
			$rfPayIdPayAppdate =  $this->setDateView($row['rfPayIdPayAppdate']);
		}

		$pdf = new FPDI('L' , 'mm' , array( 279.4,377.952 ), true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;

		 // กำหนดรายละเอียดของ pdf
		 $pdf->SetCreator("");
		 $pdf->SetAuthor("");
		 $pdf->SetTitle("Genpayprbra_101Pdf");
		 $pdf->SetSubject("Genpayprbra_101Pdf");
		  
		 // กำหนดข้อมูลที่จะแสดงในส่วนของ header และ footer
		 $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE."รายละเอียดPayrollตรวจค่าจ้างพนง.บริษัท $rfBranchIdCompanyNick ที่ทำงานอยู่บริษัท $rfBranchIdBranchName"
		 , PDF_HEADER_STRING."วันที่ทำงานวันที่ $rfPayIdPayFromdate                                                                                                   "
		 ."ถึงวันที่ $rfPayIdPayTodate                                                                                                                             "
		 ."วันที่จ่าย $rfPayIdPayAppdate", array(0,0,0), array(0,0,0));
		 $pdf->setFooterData(PDF_AUTHOR,array(0,0,0), array(0,0,0));

		// set header and footer fonts
		$pdf->setHeaderFont(Array($font,'B',14));
		$pdf->setFooterFont(Array($font,'B',14));

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->SetMargins(5, 10, 8);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(20);
		$pdf->SetFooterMargin(20);

		$pdf->SetFont($font,'B',14);

		// Add a page
		$pdf->AddPage();

		$pdf->SetFillColor(255, 255, 255);
		// Iterate through each record
		$rowCount = 0;
		$paypr_aleave_num = 0;
		$paypr_sleave_num = 0;
		$paypr_hleave_num = 0;
		$paypr_works1_num = 0;
		$paypr_work_num = 0;
		$payprWorkPay = 0;
		$paypr_shift = 0;
		$paypr_meal = 0;
		$paypr_car = 0;
		$paypr_diligent = 0;
		$paypr_income1 = 0;
		$paypr_income2 = 0;
		$paypr_income3 = 0;
		$paypr_income4 = 0;
		$paypr_other = 0;
		$paypr_ot100_num = 0;
		$paypr_ot150_num = 0;
		$paypr_ot200_num = 0;
		$paypr_ot300_num = 0;
		$paypr_de_out1 = 0;
		$paypr_de_out2 = 0;
		$paypr_de_out3 = 0;
		$paypr_de_out4 = 0;
		$paypr_de_outother = 0;

		foreach ($data_lists as $row)
		{
			if($rowCount%47 == 0){
				$pdf->SetFont($font,'B',11);
				$pdf->Cell(8, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(20, 0, "รหัส", 'LTR', 0, "C", true);
				$pdf->Cell(35, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "ค่าจ้าง", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "พัก", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "รวม", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "ประ", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, "วัน", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, "เบี้ย", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, $row['rfFormatCodeTextIncome2'], 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, "อื่นๆ", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(44, 0, "ค่าล่วงเวลา", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, $row['rfFormatCodeTextdeOut1'], 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, $row['rfFormatCodeTextdeOut4'], 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(13, 0, " ", 'LTR', 0, "C", true);
				$pdf->Ln();

				$pdf->Cell(8, 0, "No.", 'LR', 0, "C", true);
				$pdf->Cell(20, 0, "พนง.", 'LR', 0, "C", true);
				$pdf->Cell(35, 0, "ชื่อ - ชื่อสกุล", 'LR', 0, "C", true);
				$pdf->Cell(15, 0, "เริ่มงาน", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "/วัน", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "ร้อน", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "ป่วย", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "เพณี", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, "ทำงาน", 'LR', 0, "C", true);
				$pdf->Cell(15, 0, "ค่าจ้าง", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, "ค่ากะ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, "อาหาร", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, "ค่ารถ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, "ขยัน", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, $row['rfFormatCodeTextIncome3'], 'LR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, "1", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, "1.5", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, "2", 'LTR', 0, "C", true);
				$pdf->Cell(11, 0, "3", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, $row['rfFormatCodeTextdeOut2'], 'LR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(11, 0, "หักอื่น", 'LR', 0, "C", true);
				$pdf->Cell(13, 0, "หมายเหตุ", 'LR', 0, "C", true);
				$pdf->Ln();

				$pdf->Cell(8, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(20, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(35, 0, "", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, $row['rfFormatCodeTextWorks1p'], 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, $row['rfFormatCodeTextIncome1'], 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, $row['rfFormatCodeTextIncome4'], 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, $row['rfFormatCodeTextdeOut3'], 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(13, 0, " ", 'LBR', 0, "C", true);
				$pdf->Ln();
			}

			
			$paypr_aleave_num+=$row['paypr_aleave_num'];
			$paypr_sleave_num+=$row['paypr_sleave_num'];
			$paypr_hleave_num+=$row['paypr_hleave_num'];
			$paypr_works1_num+=$row['paypr_works1_num'];
			$paypr_work_num+=$row['paypr_work_num'];
			$payprWorkPay+=$row['payprWorkPay'];
			$paypr_shift+=$row['paypr_shift'];
			$paypr_meal+=$row['paypr_meal'];
			$paypr_car+=$row['paypr_car'];
			$paypr_diligent+=$row['paypr_diligent'];
			$paypr_income1+=$row['paypr_income1'];
			$paypr_income2+=$row['paypr_income2'];
			$paypr_income3+=$row['paypr_income3'];
			$paypr_income4+=$row['paypr_income4'];
			$paypr_other+=$row['paypr_other'];
			$paypr_ot100_num+=$row['paypr_ot100_num'];
			$paypr_ot150_num+=$row['paypr_ot150_num'];
			$paypr_ot200_num+=$row['paypr_ot200_num'];
			$paypr_ot300_num+=$row['paypr_ot300_num'];
			$paypr_de_out1+=$row['paypr_de_out1'];
			$paypr_de_out2+=$row['paypr_de_out2'];
			$paypr_de_out3+=$row['paypr_de_out3'];
			$paypr_de_out4+=$row['paypr_de_out4'];
			$paypr_de_outother+=$row['paypr_de_outother'];

			$pdf->SetFont($font,'',10);
			$pdf->Cell(8, 0, $rowCount +1, 'LTBR', 0, "C", true);
			$pdf->Cell(20, 0, $row['rf_name_id'], 'LTBR', 0, "C", true);
			$pdf->Cell(35, 0,  ' ' . $row['rfPernamePreId'] . $row['rfNameIdEmpName'] .'  '. $row['rfNameIdEmpSurname'], 'LTBR', 0, "L", true);
			$pdf->Cell(15, 0,  $this->setDateView($row['rfNameIdStartDate']), 'LTBR', 0, "C", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_wage']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_aleave_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_sleave_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_hleave_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_works1_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_work_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(15, 0, $this->setdatazero($row['paypr_work_pay']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_shift']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_meal']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_car']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_diligent']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_income1']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_income2']), 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_income3']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_income4']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_other']), 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_ot100_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_ot150_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_ot200_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_ot300_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_de_out1']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_de_out2']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_de_out3']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_de_out4']), 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, $this->setdatazero($row['paypr_de_outother']), 'LTBR', 0, "R", true);
			$pdf->Cell(13, 0, $row['paypr_memo'], 'LTBR', 0, "L", true);
			$rowCount++;

			$pdf->Ln();
		}

		$paypr_aleave_num = $this->setdatazeroformat($paypr_aleave_num);
		$paypr_sleave_num = $this->setdatazeroformat($paypr_sleave_num);
		$paypr_hleave_num = $this->setdatazeroformat($paypr_hleave_num);
		$paypr_works1_num = $this->setdatazeroformat($paypr_works1_num);
		$paypr_work_num = $this->setdatazeroformat($paypr_work_num);
		$payprWorkPay = $this->setdatazeroformat($payprWorkPay);
		$paypr_shift = $this->setdatazeroformat($paypr_shift);
		$paypr_meal = $this->setdatazeroformat($paypr_meal);
		$paypr_car = $this->setdatazeroformat($paypr_car);
		$paypr_diligent = $this->setdatazeroformat($paypr_diligent);
		$paypr_income1 = $this->setdatazeroformat($paypr_income1);
		$paypr_income2 = $this->setdatazeroformat($paypr_income2);
		$paypr_income3 = $this->setdatazeroformat($paypr_income3);
		$paypr_income4 = $this->setdatazeroformat($paypr_income4);
		$paypr_other = $this->setdatazeroformat($paypr_other);
		$paypr_ot100_num = $this->setdatazeroformat($paypr_ot100_num);
		$paypr_ot150_num = $this->setdatazeroformat($paypr_ot150_num);
		$paypr_ot200_num = $this->setdatazeroformat($paypr_ot200_num);
		$paypr_ot300_num = $this->setdatazeroformat($paypr_ot300_num);
		$paypr_de_out1 = $this->setdatazeroformat($paypr_de_out1);
		$paypr_de_out2 = $this->setdatazeroformat($paypr_de_out2);
		$paypr_de_out3 = $this->setdatazeroformat($paypr_de_out3);
		$paypr_de_out4 = $this->setdatazeroformat($paypr_de_out4);
		$paypr_de_outother = $this->setdatazeroformat($paypr_de_outother);

		if($rowCount%47 == 0){
			$pdf->SetFont($font,'B',11);
			$pdf->Cell(8, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(20, 0, "รหัส", 'LTR', 0, "C", true);
			$pdf->Cell(35, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(10, 0, "ค่าจ้าง", 'LTR', 0, "C", true);
			$pdf->Cell(9, 0, "พัก", 'LTR', 0, "C", true);
			$pdf->Cell(9, 0, "รวม", 'LTR', 0, "C", true);
			$pdf->Cell(9, 0, "ประ", 'LTR', 0, "C", true);
			$pdf->Cell(9, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, "วัน", 'LTR', 0, "C", true);
			$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, "เบี้ย", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, $row['rfFormatCodeTextIncome2'], 'LTR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, "อื่นๆ", 'LTR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(44, 0, "ค่าล่วงเวลา", 'LTR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, $row['rfFormatCodeTextdeOut1'], 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, $row['rfFormatCodeTextdeOut4'], 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(13, 0, " ", 'LTR', 0, "C", true);
			$pdf->Ln();

			$pdf->Cell(8, 0, "No.", 'LR', 0, "C", true);
			$pdf->Cell(20, 0, "พนง.", 'LR', 0, "C", true);
			$pdf->Cell(35, 0, "ชื่อ - ชื่อสกุล", 'LR', 0, "C", true);
			$pdf->Cell(15, 0, "เริ่มงาน", 'LR', 0, "C", true);
			$pdf->Cell(10, 0, "/วัน", 'LR', 0, "C", true);
			$pdf->Cell(9, 0, "ร้อน", 'LR', 0, "C", true);
			$pdf->Cell(9, 0, "ป่วย", 'LR', 0, "C", true);
			$pdf->Cell(9, 0, "เพณี", 'LR', 0, "C", true);
			$pdf->Cell(9, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, "ทำงาน", 'LR', 0, "C", true);
			$pdf->Cell(15, 0, "ค่าจ้าง", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, "ค่ากะ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, "อาหาร", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, "ค่ารถ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, "ขยัน", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, $row['rfFormatCodeTextIncome3'], 'LR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, "1", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, "1.5", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, "2", 'LTR', 0, "C", true);
			$pdf->Cell(11, 0, "3", 'LTR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, $row['rfFormatCodeTextdeOut2'], 'LR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LR', 0, "C", true);
			$pdf->Cell(11, 0, "หักอื่น", 'LR', 0, "C", true);
			$pdf->Cell(13, 0, "หมายเหตุ", 'LR', 0, "C", true);
			$pdf->Ln();

			$pdf->Cell(8, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(20, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(35, 0, "", 'LBR', 0, "C", true);
			$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(9, 0, $row['rfFormatCodeTextWorks1p'], 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, $row['rfFormatCodeTextIncome1'], 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, $row['rfFormatCodeTextIncome4'], 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, $row['rfFormatCodeTextdeOut3'], 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(11, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(13, 0, " ", 'LBR', 0, "C", true);
			$pdf->Ln();

			$pdf->SetFont($font,'B',9);
			$pdf->Cell(8, 0, " ", 'LTBR', 0, "C", true);
			$pdf->Cell(20, 0, " ", 'LTBR', 0, "C", true);
			$pdf->Cell(35, 0, "รวม" , 'LTBR', 0, "C", true);
			$pdf->Cell(15, 0, " ", 'LTBR', 0, "C", true);
			$pdf->Cell(10, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, "$paypr_aleave_num", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, "$paypr_sleave_num", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, "$paypr_hleave_num", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, "$paypr_works1_num", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_work_num", 'LTBR', 0, "R", true);
			$pdf->Cell(15, 0, "$payprWorkPay", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_shift", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_meal", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_car", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_diligent", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_income1", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_income2", 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_income3", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_income4", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_other", 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_ot100_num", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_ot150_num", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_ot200_num", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_ot300_num", 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_out1", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_out2", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_out3", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_out4", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_outother", 'LTBR', 0, "R", true);
			$pdf->Cell(13, 0, " ", 'LTBR', 0, "L", true);	

		}else{
			$pdf->SetFont($font,'B',9);
			$pdf->Cell(8, 0, " ", 'LTBR', 0, "C", true);
			$pdf->Cell(20, 0, " ", 'LTBR', 0, "C", true);
			$pdf->Cell(35, 0, "รวม" , 'LTBR', 0, "C", true);
			$pdf->Cell(15, 0, " ", 'LTBR', 0, "C", true);
			$pdf->Cell(10, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, "$paypr_aleave_num", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, "$paypr_sleave_num", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, "$paypr_hleave_num", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, "$paypr_works1_num", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_work_num", 'LTBR', 0, "R", true);
			$pdf->Cell(15, 0, "$payprWorkPay", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_shift", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_meal", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_car", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_diligent", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_income1", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_income2", 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_income3", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_income4", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_other", 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_ot100_num", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_ot150_num", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_ot200_num", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_ot300_num", 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_out1", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_out2", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_out3", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_out4", 'LTBR', 0, "R", true);
			$pdf->Cell(11, 0, "$paypr_de_outother", 'LTBR', 0, "R", true);
			$pdf->Cell(13, 0, " ", 'LTBR', 0, "L", true);	
		}


		$pdf->Output("Genpayprbra_101Pdf.pdf", 'I');
		
	}

	public function genreport_104Pdf() 
	{
		// load PDF library
		$this->load->library('reportuser/Payprbra_preview_pdf');

		$results = $this->Payprbra->check_pdf();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);

		foreach ($data_lists as $row)
		{
			$rfBranchIdCompanyNick = $row['rfBranchIdCompanyNick'];
			$rfBranchIdBranchName = $row['rfBranchIdBranchName'];
			$rfBranchIdBranchCode = $row['rfBranchIdBranchCode'];
			$rfPayIdPayFromdate =  $this->setDateView($row['rfPayIdPayFromdate']);
			$rfPayIdPayTodate =  $this->setDateView($row['rfPayIdPayTodate']);
			$rfPayIdPayAppdate =  $this->setDateView($row['rfPayIdPayAppdate']);
		}

		$pdf = new FPDI('L' , 'mm' , array( 279.4,377.952 ), true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;

		 // กำหนดรายละเอียดของ pdf
		 $pdf->SetCreator("");
		 $pdf->SetAuthor("");
		 $pdf->SetTitle("Genpayprbra_104Pdf");
		 $pdf->SetSubject("Genpayprbra_104Pdf");
		  
		 // กำหนดข้อมูลที่จะแสดงในส่วนของ header และ footer
		 $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE."รายละเอียดPayrollตรวจค่าจ้างพนง.บริษัท $rfBranchIdCompanyNick ที่ทำงานอยู่บริษัท $rfBranchIdBranchName"
		 , PDF_HEADER_STRING."วันที่ทำงานวันที่ $rfPayIdPayFromdate                                                                                                   "
		 ."ถึงวันที่ $rfPayIdPayTodate                                                                                                                             "
		 ."วันที่จ่าย $rfPayIdPayAppdate", array(0,0,0), array(0,0,0));
		 $pdf->setFooterData(PDF_AUTHOR,array(0,0,0), array(0,0,0));

		// set header and footer fonts
		$pdf->setHeaderFont(Array($font,'B',14));
		$pdf->setFooterFont(Array($font,'B',14));
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetMargins(5, 10, 8);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(20);
		$pdf->SetFooterMargin(20);

		$pdf->SetFont($font,'B',14);

		// Add a page
		$pdf->AddPage();

		$pdf->SetFillColor(255, 255, 255);

		// Iterate through each record
		$rowCount = 0;
		$paypr_aleave_num = 0;
		$paypr_sleave_num = 0;
		$paypr_hleave_num = 0;
		$paypr_works1_num = 0;
		$paypr_work_num = 0;
		$payprWorkPay = 0;
		$paypr_shift = 0;
		$paypr_meal = 0;
		$paypr_diligent = 0;
		$paypr_other = 0;
		$paypr_ot100_num = 0;
		$paypr_ot150_num = 0;
		$paypr_ot200_num = 0;
		$paypr_ot300_num = 0;
		$paypr_otsum0_pay = 0;
		$paypr_ot101_num = 0;
		$paypr_ot105_num = 0;
		$paypr_ot102_num = 0;
		$paypr_ot103_num = 0;
		$PayprOtsum1Pay = 0;
		$PayprSalaryTotal = 0;
		$paypr_de_out1 = 0;
		$paypr_de_out2 = 0;
		$paypr_de_out3 = 0;
		$paypr_de_out4 = 0;
		$paypr_de_outother = 0;
		
		foreach ($data_lists as $row)
		{
			if($rowCount%47 == 0){
				$pdf->SetFont($font,'B',11);
				$pdf->Cell(8, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(20, 0, "รหัส", 'LTR', 0, "C", true);
				$pdf->Cell(35, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "ค่าจ้าง", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "พัก", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "รวม", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "ประ", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "วัน", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "เบี้ย", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "รายได้", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(36, 0, "ล่วงเวลา (ช.ม.)", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(13, 0, "ค่า", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(36, 0, "ล่วงเวลา (นาที)", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(13, 0, "ค่า", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, "รวม", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, $row['rfFormatCodeTextdeOut3'], 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "หัก", 'LTR', 0, "C", true);
				$pdf->Cell(13, 0, " ", 'LTR', 0, "C", true);
				$pdf->Ln();

				$pdf->Cell(8, 0, "No.", 'LR', 0, "C", true);
				$pdf->Cell(20, 0, "พนง.", 'LR', 0, "C", true);
				$pdf->Cell(35, 0, "ชื่อ - ชื่อสกุล", 'LR', 0, "C", true);
				$pdf->Cell(15, 0, "เริ่มงาน", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "/วัน", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "ร้อน", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "ป่วย", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "เพณี", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "ทำงาน", 'LR', 0, "C", true);
				$pdf->Cell(15, 0, "ค่าจ้าง", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "ค่ากะ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "อาหาร", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "ขยัน", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "อื่นๆ", 'LR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "1", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "1.5", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "2", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "3", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(13, 0, "ล่วงเวลา", 'LR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "1", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "1.5", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "2", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "3", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(13, 0, "ล่วงเวลา", 'LR', 0, "C", true);
				$pdf->Cell(15, 0, "รับ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, $row['rfFormatCodeTextdeOut2'], 'LR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "อื่น", 'LR', 0, "C", true);
				$pdf->Cell(13, 0, "หมายเหตุ", 'LR', 0, "C", true);
				$pdf->Ln();

				$pdf->Cell(8, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(20, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(35, 0, "", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, $row['rfFormatCodeTextWorks1p'], 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(13, 0, "(ช.ม.)", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(13, 0, "(นาที)", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, $row['rfFormatCodeTextdeOut1'], 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, $row['rfFormatCodeTextdeOut4'], 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(13, 0, " ", 'LBR', 0, "C", true);
				$pdf->Ln();
			}

			$paypr_aleave_num+=$row['paypr_aleave_num'];
			$paypr_sleave_num+=$row['paypr_sleave_num'];
			$paypr_hleave_num+=$row['paypr_hleave_num'];
			$paypr_works1_num+=$row['paypr_works1_num'];
			$paypr_work_num+=$row['paypr_work_num'];
			$payprWorkPay+=$row['payprWorkPay'];
			$paypr_shift+=$row['paypr_shift'];
			$paypr_meal+=$row['paypr_meal'];
			$paypr_diligent+=$row['paypr_diligent'];
			$paypr_other+=$row['paypr_other'];
			$paypr_ot100_num+=$row['paypr_ot100_num'];
			$paypr_ot150_num+=$row['paypr_ot150_num'];
			$paypr_ot200_num+=$row['paypr_ot200_num'];
			$paypr_ot300_num+=$row['paypr_ot300_num'];
			$paypr_otsum0_pay+=$row['paypr_otsum0_pay'];
			$paypr_ot101_num+=$row['paypr_ot101_num'];
			$paypr_ot105_num+=$row['paypr_ot105_num'];
			$paypr_ot102_num+=$row['paypr_ot102_num'];
			$paypr_ot103_num+=$row['paypr_ot103_num'];
			$PayprOtsum1Pay+=$row['PayprOtsum1Pay'];
			$PayprSalaryTotal+=$row['PayprSalaryTotal'];
			$paypr_de_out1+=$row['paypr_de_out1'];
			$paypr_de_out2+=$row['paypr_de_out2'];
			$paypr_de_out3+=$row['paypr_de_out3'];
			$paypr_de_out4+=$row['paypr_de_out4'];
			$paypr_de_outother+=$row['paypr_de_outother'];
	
			$pdf->SetFont($font,'',10);
			$pdf->Cell(8, 0, $rowCount +1, 'LTBR', 0, "C", true);
			$pdf->Cell(20, 0, $row['rf_name_id'], 'LTBR', 0, "C", true);
			$pdf->Cell(35, 0,  ' ' . $row['rfPernamePreId'] . $row['rfNameIdEmpName'] .'  '. $row['rfNameIdEmpSurname'], 'LTBR', 0, "L", true);
			$pdf->Cell(15, 0,  $this->setDateView($row['rfNameIdStartDate']), 'LTBR', 0, "C", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_wage']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_aleave_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_sleave_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_hleave_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_works1_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_work_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(15, 0, $this->setdatazero($row['paypr_work_pay']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_shift']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_meal']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_diligent']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_other']), 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0,  $this->setdatazero($row['paypr_ot100_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0,  $this->setdatazero($row['paypr_ot150_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0,  $this->setdatazero($row['paypr_ot200_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0,  $this->setdatazero($row['paypr_ot300_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(13, 0, $this->setdatazero($row['paypr_otsum0_pay']), 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_ot101_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_ot105_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_ot102_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(9, 0, $this->setdatazero($row['paypr_ot103_num']), 'LTBR', 0, "R", true);
			$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
			$pdf->Cell(13, 0, $this->setdatazero($row['paypr_otsum1_pay']), 'LTBR', 0, "R", true);
			$pdf->Cell(15, 0, $this->setdatazero($row['paypr_salary_total']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_de_out1']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_de_out2']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_de_out3']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_de_out4']), 'LTBR', 0, "R", true);
			$pdf->Cell(10, 0, $this->setdatazero($row['paypr_de_outother']), 'LTBR', 0, "R", true);
			$pdf->Cell(13, 0, $row['paypr_memo'], 'LTBR', 0, "L", true);
			$rowCount++;

			$pdf->Ln();
		}

		$paypr_aleave_num = $this->setdatazeroformat($paypr_aleave_num);
		$paypr_sleave_num = $this->setdatazeroformat($paypr_sleave_num);
		$paypr_hleave_num = $this->setdatazeroformat($paypr_hleave_num);
		$paypr_works1_num = $this->setdatazeroformat($paypr_works1_num);
		$paypr_work_num = $this->setdatazeroformat($paypr_work_num);
		$payprWorkPay = $this->setdatazeroformat($payprWorkPay);
		$paypr_shift = $this->setdatazeroformat($paypr_shift);
		$paypr_meal = $this->setdatazeroformat($paypr_meal);
		$paypr_diligent = $this->setdatazeroformat($paypr_diligent);
		$paypr_other = $this->setdatazeroformat($paypr_other);
		$paypr_ot100_num = $this->setdatazeroformat($paypr_ot100_num);
		$paypr_ot150_num = $this->setdatazeroformat($paypr_ot150_num);
		$paypr_ot200_num = $this->setdatazeroformat($paypr_ot200_num);
		$paypr_ot300_num = $this->setdatazeroformat($paypr_ot300_num);
		$paypr_otsum0_pay = $this->setdatazeroformat($paypr_otsum0_pay);
		$paypr_ot101_num = $this->setdatazeroformat($paypr_ot101_num);
		$paypr_ot105_num = $this->setdatazeroformat($paypr_ot105_num);
		$paypr_ot102_num = $this->setdatazeroformat($paypr_ot102_num);
		$paypr_ot103_num = $this->setdatazeroformat($paypr_ot103_num);
		$PayprOtsum1Pay = $this->setdatazeroformat($PayprOtsum1Pay);
		$PayprSalaryTotal = $this->setdatazeroformat($PayprSalaryTotal);
		$paypr_de_out1 = $this->setdatazeroformat($paypr_de_out1);
		$paypr_de_out2 = $this->setdatazeroformat($paypr_de_out2);
		$paypr_de_out3 = $this->setdatazeroformat($paypr_de_out3);
		$paypr_de_out4 = $this->setdatazeroformat($paypr_de_out4);
		$paypr_de_outother = $this->setdatazeroformat($paypr_de_outother);

		if($rowCount%47 == 0){
				$pdf->SetFont($font,'B',11);
				$pdf->Cell(8, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(20, 0, "รหัส", 'LTR', 0, "C", true);
				$pdf->Cell(35, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "ค่าจ้าง", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "พัก", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "รวม", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "ประ", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "วัน", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "เบี้ย", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "รายได้", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(36, 0, "ล่วงเวลา (ช.ม.)", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(13, 0, "ค่า", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(36, 0, "ล่วงเวลา (นาที)", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(13, 0, "ค่า", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, "รวม", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, $row['rfFormatCodeTextdeOut3'], 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "หัก", 'LTR', 0, "C", true);
				$pdf->Cell(13, 0, " ", 'LTR', 0, "C", true);
				$pdf->Ln();

				$pdf->Cell(8, 0, "No.", 'LR', 0, "C", true);
				$pdf->Cell(20, 0, "พนง.", 'LR', 0, "C", true);
				$pdf->Cell(35, 0, "ชื่อ - ชื่อสกุล", 'LR', 0, "C", true);
				$pdf->Cell(15, 0, "เริ่มงาน", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "/วัน", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "ร้อน", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "ป่วย", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "เพณี", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "ทำงาน", 'LR', 0, "C", true);
				$pdf->Cell(15, 0, "ค่าจ้าง", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "ค่ากะ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "อาหาร", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "ขยัน", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "อื่นๆ", 'LR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "1", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "1.5", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "2", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "3", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(13, 0, "ล่วงเวลา", 'LR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(9, 0, "1", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "1.5", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "2", 'LTR', 0, "C", true);
				$pdf->Cell(9, 0, "3", 'LTR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(13, 0, "ล่วงเวลา", 'LR', 0, "C", true);
				$pdf->Cell(15, 0, "รับ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, $row['rfFormatCodeTextdeOut2'], 'LR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "อื่น", 'LR', 0, "C", true);
				$pdf->Cell(13, 0, "หมายเหตุ", 'LR', 0, "C", true);
				$pdf->Ln();

				$pdf->Cell(8, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(20, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(35, 0, "", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, $row['rfFormatCodeTextWorks1p'], 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(13, 0, "(ช.ม.)", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(9, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(1, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(13, 0, "(นาที)", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, $row['rfFormatCodeTextdeOut1'], 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, $row['rfFormatCodeTextdeOut4'], 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(13, 0, " ", 'LBR', 0, "C", true);
				$pdf->Ln();

				$pdf->SetFont($font,'B',9);
				$pdf->Cell(8, 0, " ", 'LTBR', 0, "C", true);
				$pdf->Cell(20, 0, " ", 'LTBR', 0, "C", true);
				$pdf->Cell(35, 0, "รวม", 'LTBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LTBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTBR', 0, "C", true);
				$pdf->Cell(9, 0, "$paypr_aleave_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_sleave_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_hleave_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_works1_num", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_work_num", 'LTBR', 0, "R", true);
				$pdf->Cell(15, 0, "$payprWorkPay", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_shift", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_meal", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_diligent", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_other", 'LTBR', 0, "R", true);
				$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot100_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot150_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot200_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot300_num", 'LTBR', 0, "R", true);
				$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
				$pdf->Cell(13, 0, "$paypr_otsum0_pay", 'LTBR', 0, "R", true);
				$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot101_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot102_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot105_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot103_num", 'LTBR', 0, "R", true);
				$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
				$pdf->Cell(13, 0, "$PayprOtsum1Pay", 'LTBR', 0, "R", true);
				$pdf->Cell(15, 0, "$PayprSalaryTotal", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_out1", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_out2", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_out3", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_out3", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_outother", 'LTBR', 0, "R", true);
				$pdf->Cell(13, 0, " ", 'LTBR', 0, "R", true);


		}else{
				$pdf->SetFont($font,'B',9);
				$pdf->Cell(8, 0, " ", 'LTBR', 0, "C", true);
				$pdf->Cell(20, 0, " ", 'LTBR', 0, "C", true);
				$pdf->Cell(35, 0, "", 'LTBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LTBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LTBR', 0, "C", true);
				$pdf->Cell(9, 0, "$paypr_aleave_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_sleave_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_hleave_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_works1_num", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_work_num", 'LTBR', 0, "R", true);
				$pdf->Cell(15, 0, "$payprWorkPay", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_shift", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_meal", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_diligent", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_other", 'LTBR', 0, "R", true);
				$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot100_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot150_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot200_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot300_num", 'LTBR', 0, "R", true);
				$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
				$pdf->Cell(13, 0, "$paypr_otsum0_pay", 'LTBR', 0, "R", true);
				$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot101_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot102_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot105_num", 'LTBR', 0, "R", true);
				$pdf->Cell(9, 0, "$paypr_ot103_num", 'LTBR', 0, "R", true);
				$pdf->Cell(1, 0, " ", 'LTBR', 0, "R", true);
				$pdf->Cell(13, 0, "$PayprOtsum1Pay", 'LTBR', 0, "R", true);
				$pdf->Cell(15, 0, "$PayprSalaryTotal", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_out1", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_out2", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_out3", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_out3", 'LTBR', 0, "R", true);
				$pdf->Cell(10, 0, "$paypr_de_outother", 'LTBR', 0, "R", true);
				$pdf->Cell(13, 0, " ", 'LTBR', 0, "R", true);
		}
		// // Add a page

		$pdf->Output("Genreport_104Pdf.pdf", 'I');
		
	}

	public function Genreport_106Pdf() 
	{
		// load PDF library
		$this->load->library('reportuser/Payprbra_preview_pdf');
		
		$results = $this->Payprbra->slip_pdf();
		$data_lists = $this->setDataListFormatSlip($results['list_data'], 0);
		
		$pdf = new FPDI('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;
		
		$pdf->SetCreator("");
		$pdf->SetAuthor("");
		$pdf->SetTitle("Genpayprbra_106Pdf");
		$pdf->SetSubject("Genpayprbra_106Pdf");
			
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		$pdf->SetMargins(3, 4, 3);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(4);
		$pdf->SetFooterMargin(20);
		$pdf->SetAutoPageBreak('on',70);
		$pdf->SetFont($font, '', 16);
		
		// Add a page
		$pdf->AddPage("P");

		$this->data['data_list'] = $data_lists;
		$data = $this->data;
		
		$html = $this->parser->parse_repeat('reportuser/payprbra/print_pdf_slip1', $data, true);
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
		$pdf->lastPage();
		
		$pdf->Output("Genpayprbra_104Pdf.pdf", 'I');
	}

	public function print_107_Pdf() 
	{
	 	// load PDF library
		$this->load->library('reportuser/Payprbra_preview_pdf');
		
		$results = $this->Payprbra->slip_pdf();
		$data_lists = $this->setDataListFormatSlip($results['list_data'], 0);

		$pdf = new FPDI('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;

		$pdf->SetCreator("");
		$pdf->SetAuthor("");
		$pdf->SetTitle("Genpayprbra_107Pdf");
		$pdf->SetSubject("Genpayprbra_107Pdf");
			
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		$pdf->SetMargins(3, 4, 3);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(4);
		$pdf->SetFooterMargin(20);
		$pdf->SetAutoPageBreak('on',140);
		$pdf->SetFont($font, '', 16);
		
		// Add a page
		$pdf->AddPage("P");

		$this->data['data_list'] = $data_lists;
		$data = $this->data;
		
		$html = $this->parser->parse_repeat('reportuser/payprbra/print_pdf_slip2', $data, true);
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
		$pdf->lastPage();
		
		$pdf->Output("Genpayprbra_107Pdf.pdf", 'I');
	}

	public function genreport_101Excel() 
	{	
		$this->load->library('reportuser/Excel');
		
		$results = $this->Payprbra->read();
		$data_lists = $this->setDataListFormatExcel($results['list_data'], 0);
		$this->data['data_list'] = $data_lists;
		$data = $this->data;
	
		$table	=  $this->parser->parse_repeat('reportuser/Payprbra/genreport_101excel', $data, true);

		$filename = "Genpayprbra_120Excel". date("Y-m-d-H-i-s")."";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0'); 
		
		echo $table;

	}

	public function genreport_120Excel() 
	{	
		$this->load->library('reportuser/Excel');
		
		$results = $this->Payprbra->read();
		$data_lists = $this->setDataListFormatExcel($results['list_data'], 0);
		$this->data['data_list'] = $data_lists;
		$results = $this->Payprbra->sumexcel();
		$data_lists1 = $this->setDataListFormatExcel($results['list_data'], 0);
		$this->data['data_list1'] = $data_lists1;
		$data = $this->data;
	
		$table	=  $this->parser->parse_repeat('reportuser/Payprbra/Genpayprbra_120Excel', $data, true);

		$filename = "Genpayprbra_120Excel". date("Y-m-d-H-i-s")."";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0'); 

		foreach ($data_lists as $row)
		{
			$rfBranchIdBranchNick = $row['rfBranchIdBranchNick'];
			$rfPayIdPayNum = $row['rfPayIdPayNum'];
		}

		echo "บันทึกรายการรับเงิน ประจำสาขา $rfBranchIdBranchNick <br>";
		echo "ประจำงวดวันที่ $rfPayIdPayNum";
		
		echo $table;

	}


}
/*---------------------------- END Controller Class --------------------------------*/
