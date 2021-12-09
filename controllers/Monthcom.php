<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * [ Controller File name : Monthcom.php ]
 */
class Monthcom extends CRUD_Controller
{

	private $per_page;
	private $another_js;
	private $another_css;

	public function __construct()
	{
		parent::__construct();
		$this->per_page = 150;
		$this->num_links = 6;
		$this->uri_segment = 4;
		$this->load->model('reportuser/monthcom_model', 'Monthcom');
		$this->data['page_url'] = site_url('reportuser/monthcom');
		
		$this->data['page_title'] = 'THITARAM GROUP';

		$js_url = 'assets/js_modules/reportuser/monthcom.js?ft='. filemtime('assets/js_modules/reportuser/monthcom.js');
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
		$this->data['breadcrumb_list'] = $this->parser->parse('template/sb-admin-bs4/breadcrumb_home', $this->breadcrumb_data, TRUE);
		$this->data['page_content'] = $this->parser->parse_repeat($path, $this->data, TRUE);
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
		$this->session->unset_userdata($this->Monthcom->session_name . '_search_field');
		$this->session->unset_userdata($this->Monthcom->session_name . '_value');
		$this->session->unset_userdata($this->Monthcom->session_name . '_paynum');
		$this->session->unset_userdata($this->Monthcom->session_name . '_payyear');
		$this->search();
	}

	// ------------------------------------------------------------------------

	/**
	 * Search data
	 */
	public function search()
	{
		$this->breadcrumb_data['breadcrumb'] = array(
						
						array('title' => 'รายงานพิเศษ', 'class' => 'active', 'url' => '#'),
		);
		
		$options1 = array();
		$this->data['tb_comppany_rf_company_id_option_list'] = $this->Monthcom->returnOptionList("tb_company", "company_id", "company_name",$options1 );

		$this->data['tb_paymonth_monthpay_option_list'] = $this->Monthcom->returnOptionList("tb_paymonth", "paymonth_id", "CONCAT_WS(' - ', paymonth_id,paymonth)" );

		$options3 = array("where" => " report_type = 5 AND report_void = 0");
		$this->data['tb_report_report_id_option_list'] = $this->Monthcom->returnOptionList("tb_report", "report_id", "CONCAT_WS(' - ',report_file,report_name)", $options3);

		if (isset($_POST['submit'])) {
			$search_field =  $this->input->post('search_field', TRUE);
			$value = $this->input->post('txtSearch', TRUE);
			$paynum = $this->input->post('txtpnum', TRUE);
			$payyear = $this->input->post('txtYear', TRUE);
			$arr = array(
				$this->Monthcom->session_name . '_search_field' => $search_field, 
				$this->Monthcom->session_name . '_value' => $value,  
				$this->Monthcom->session_name . '_paynum' => $paynum,
				$this->Monthcom->session_name . '_payyear' => $payyear);

			$this->session->set_userdata($arr);
		} else {
			$search_field = $this->session->userdata($this->Monthcom->session_name . '_search_field');
			$value = $this->session->userdata($this->Monthcom->session_name . '_value');
			$paynum = $this->session->userdata($this->Monthcom->session_name . '_paynum');
			$payyear = $this->session->userdata($this->Monthcom->session_name . '_payyear');
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
			$this->Monthcom->order_field = $field;
			$this->Monthcom->order_sort = $sort;
		}
		$results = $this->Monthcom->read($start_row, $per_page);
		$total_row = $results['total_row'];
		$search_row = $results['search_row'];
		$list_data = $this->setDataListFormat($results['list_data'], $start_row);


		$page_url = site_url('reportuser/monthcom');
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
		$this->data['txt_year']	= $payyear;
		$this->data['current_path_uri'] = uri_string();
		$this->data['current_page_offset'] = $start_row;
		$this->data['start_row']	= $start_row + 1;
		$this->data['end_row']	= $end_row;
		$this->data['order_by']	= $order_by;
		$this->data['total_row']	= $total_row;
		$this->data['search_row']	= $search_row;
		$this->data['page_url']	= $page_url;
		$this->data['pagination_link']	= $pagination;
		$this->data['csrf_protection_field']	= insert_csrf_field(true);

		$this->render_view('reportuser/monthcom/list_view');
	}

	// ------------------------------------------------------------------------

	/**
	 * Preview Data
	 * @param String encrypt id
	 */
	public function preview($encrypt_id = "")
	{
		$this->breadcrumb_data['breadcrumb'] = array(
						array('title' => 'Monthcom', 'url' => site_url('reportuser/monthcom')),
						array('title' => 'แสดงข้อมูลรายละเอียด', 'url' => '#', 'class' => 'active')
		);
		$encrypt_id = urldecode($encrypt_id);
		$id = ci_decrypt($encrypt_id);
		if ($id == "") {
			$this->data['message'] = "กรุณาระบุรหัสอ้างอิงที่ต้องการแสดงข้อมูล";
			$this->render_view('ci_message/warning');
		} else {
			$results = $this->Monthcom->load($id);
			if (empty($results)) {
				$this->data['message'] = "ไม่พบข้อมูลตามรหัสอ้างอิง <b>$id</b>";
				$this->render_view('ci_message/danger');
			} else {
				$this->setPreviewFormat($results);
				$this->render_view('reportuser/monthcom/preview_view');
			}
		}
	}


	// ------------------------------------------------------------------------

	public function preview_print_pdf($encrypt_id = "") 
	{
		// load PDF library
		$this->load->library('reportuser/monthcom_preview_pdf');
		
		$id = ci_decrypt(urldecode($encrypt_id));
		$results = $this->Monthcom->load($id);
		$this->setPreviewFormat($results);
		$data_lists = array();
		$this->data['detail_list'] = $data_lists;
		$data = $this->data;
		
		$pdf = new FPDI('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;
		
		$pdf->SetCreator("");
		$pdf->SetAuthor("");
		$pdf->SetTitle("ตารางแสดงรายการ ข้อมูล tb_Monthcom");
		$pdf->SetSubject("ตารางแสดงรายการ ข้อมูล tb_Monthcom");
				
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(15);
		$pdf->SetFooterMargin(0);
		
		$pdf->SetFont($font, '', 16);
		
		// Add a page
		$pdf->AddPage("P");
		
		$html = $this->parser->parse_repeat('reportuser/monthcom/preview_view_pdf', $data, true);
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
		$pdf->lastPage();
		
		$pdf->Output('Monthcom_list.pdf', 'I');
	}

	public function preview_export_excel($encrypt_id = "") 
	{	
		$id = ci_decrypt(urldecode($encrypt_id));
		$results = $this->Monthcom->load($id);
		$this->setPreviewFormat($results);
		$data_lists = array();
		$this->data['detail_list'] = $data_lists;
		$data = $this->data;
	
		$table	=  $this->parser->parse_repeat('reportuser/monthcom/preview_view_excel', $data, true);

		$filename = "Monthcom_preview". date("Y-m-d-H-i-s")."";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0'); 
		
		echo $table;

	}

	// ------------------------------------------------------------------------
	/**
	 * Add form
	 */
	public function add()
	{
		$this->breadcrumb_data['breadcrumb'] = array(
						array('title' => 'Monthcom', 'url' => site_url('reportuser/monthcom')),
						array('title' => 'เพิ่มข้อมูล', 'url' => '#', 'class' => 'active')
		);
		$this->data['tb_paymonth_monthpay_option_list'] = $this->Monthcom->returnOptionList("tb_paymonth", "paymonth_id", "CONCAT_WS(' - ', paymonth_id,paymonth)" );
		$this->data['tb_person_rf_name_id_option_list'] = $this->Monthcom->returnOptionList("tb_person", "name_id", "CONCAT_WS(' - ', emp_name,emp_surname)" );
		$this->render_view('reportuser/monthcom/add_view');
	}

	// ------------------------------------------------------------------------

	/**
	 * Default Validation
	 * see also https://www.codeigniter.com/userguide3/libraries/form_validation.html
	 */
	public function formValidate()
	{
		$this->load->library('form_validation');
		$frm = $this->form_validation;

		$frm->set_rules('yearpay', 'ปี', 'trim|required');
		$frm->set_rules('monthpay', 'เดือน', 'trim|required');
		$frm->set_rules('rf_name_id', 'รหัสพนักงาน', 'trim|required');

		$frm->set_message('required', '- กรุณากรอก %s');
		

		if ($frm->run() == FALSE) {
			$message  = '';
			$message .= form_error('yearpay');
			$message .= form_error('monthpay');
			$message .= form_error('rf_name_id');
			return $message;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Default Validation for Update
	 * see also https://www.codeigniter.com/userguide3/libraries/form_validation.html
	 */
	public function formValidateUpdate()
	{
		$this->load->library('form_validation');
		$frm = $this->form_validation;

		$frm->set_rules('yearpay', 'ปี', 'trim|required');
		$frm->set_rules('monthpay', 'เดือน', 'trim|required');
		$frm->set_rules('rf_name_id', 'รหัสพนักงาน', 'trim|required');

		$frm->set_message('required', '- กรุณากรอก %s');
		

		if ($frm->run() == FALSE) {
			$message  = '';
			$message .= form_error('yearpay');
			$message .= form_error('monthpay');
			$message .= form_error('rf_name_id');
			return $message;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Create new record
	 */
	public function save()
	{

		$message = '';
		$message .= $this->formValidate();
		if ($message != '') {
			$json = json_encode(array(
						'is_successful' => FALSE,
						'message' => $message
			));
			echo $json;
		} else {

			$post = $this->input->post(NULL, TRUE);

			$encrypt_id = '';
			$id = $this->Monthcom->create($post);
			if($id != ''){
				$success = TRUE;
				$encrypt_id = ci_encrypt($id);
				$message = '<strong>บันทึกข้อมูลเรียบร้อย</strong>';
			}else{
				$success = FALSE;
				$message = 'Error : ' . $this->Monthcom->error_message;
			}

			$json = json_encode(array(
						'is_successful' => $success,
						'encrypt_id' =>  $encrypt_id,
						'message' => $message
			));
			echo $json;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Load data to form
	 * @param String encrypt id
	 */
	public function edit($encrypt_id = '')
	{
		$this->breadcrumb_data['breadcrumb'] = array(
						array('title' => 'Monthcom', 'url' => site_url('reportuser/monthcom')),
						array('title' => 'แก้ไขข้อมูล', 'url' => '#', 'class' => 'active')
		);

		$encrypt_id = urldecode($encrypt_id);
		$id = ci_decrypt($encrypt_id);
		if ($id == "") {
			$this->data['message'] = "กรุณาระบุรหัสอ้างอิงที่ต้องการแก้ไขข้อมูล";
			$this->render_view('ci_message/warning');
		} else {
			$results = $this->Monthcom->load($id);
			if (empty($results)) {
			$this->data['message'] = "ไม่พบข้อมูลตามรหัสอ้างอิง <b>$id</b>";
				$this->render_view('ci_message/danger');
			} else {
				$this->data['csrf_field'] = insert_csrf_field(true);


				$this->setPreviewFormat($results);

				$this->data['tb_paymonth_monthpay_option_list'] = $this->Monthcom->returnOptionList("tb_paymonth", "paymonth_id", "CONCAT_WS(' - ', paymonth_id,paymonth)" );
				$this->data['tb_person_rf_name_id_option_list'] = $this->Monthcom->returnOptionList("tb_person", "name_id", "CONCAT_WS(' - ', emp_name,emp_surname)" );
				$this->render_view('reportuser/monthcom/edit_view');
			}
		}
	}

	// ------------------------------------------------------------------------
	public function checkRecordKey($data)
	{
		$error = '';
		$monthpr_id = ci_decrypt($data['encrypt_monthpr_id']);
		if($monthpr_id==''){
			$error .= '- รหัส monthpr_id';
		}
		return $error;
	}

	/**
	 * Update Record
	 */
	public function update()
	{
		$message = '';
		$message .= $this->formValidateUpdate();
		$edit_remark = $this->input->post('edit_remark', TRUE);
		if ($edit_remark == '') {
			$message .= 'ระบุเหตุผล';
		}
		
		$post = $this->input->post(NULL, TRUE);
		$error_pk_id = $this->checkRecordKey($post);
		if ($error_pk_id != '') {
			$message .= "รหัสอ้างอิงที่ใช้สำหรับอัพเดตข้อมูลไม่ถูกต้อง";
		}
		if ($message != '') {
			$json = json_encode(array(
						'is_successful' => FALSE,
						'message' => $message
			));
			 echo $json;
		} else {

			$result = $this->Monthcom->update($post);
			if($result == false){
				$message = $this->Monthcom->error_message;
				$ok = FALSE;
			}else{
				$message = '<strong>บันทึกข้อมูลเรียบร้อย</strong>' . $this->Monthcom->error_message;
				$ok = TRUE;
			}
			$json = json_encode(array(
						'is_successful' => $ok,
						'message' => $message
			));

			echo $json;
		}
	}

	/**
	 * Delete Record
	 */
	public function del()
	{
		$delete_remark = $this->input->post('delete_remark', TRUE);
			$message = '';
		if ($delete_remark == '') {
			$message .= 'ระบุเหตุผล';
		}
		
		$post = $this->input->post(NULL, TRUE);
		$error_pk_id = $this->checkRecordKey($post);
		if ($error_pk_id != '') {
			$message .= "รหัสอ้างอิงที่ใช้สำหรับลบข้อมูลไม่ถูกต้อง";
		}
		if ($message != '') {
			$json = json_encode(array(
						'is_successful' => FALSE,
						'message' => $message    
			));
			echo $json;
		}else{
			$result = $this->Monthcom->delete($post);
			if($result == false){
				$message = $this->Monthcom->error_message;
				$ok = FALSE;
			}else{
				$message = '<strong>ลบข้อมูลเรียบร้อย</strong>';
				$ok = TRUE;
			}
			$json = json_encode(array(
						'is_successful' => $ok,
						'message' => $message
			));
			echo $json;
		}
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
			$pk1 = $data[$i]['monthpr_id'];
			$data[$i]['url_encrypt_id'] = urlencode(encrypt($pk1));

			if($pk1 != ''){
				$pk1 = ci_encrypt($pk1);
			}
			$data[$i]['encrypt_monthpr_id'] = $pk1;
			$data[$i]['month_mony_sso'] = number_format($data[$i]['month_mony_sso'],2);
			$data[$i]['month_de_ssop'] = number_format($data[$i]['month_de_ssop'],2);
			$data[$i]['month_de_ssoc'] = number_format($data[$i]['month_de_ssoc'],2);
			$data[$i]['rfNameIdStartDate'] = $this->setDateView($data[$i]['rfNameIdStartDate']);
		}
		return $data;
	}

	/**
	 * SET array data list
	 */
	private function setPreviewFormat($row_data)
	{
		$data = $row_data;

		$pk1 = $data['monthpr_id'];
		$this->data['recode_url_encrypt_id'] = urlencode(encrypt($pk1));

		if($pk1 != ''){
			$pk1 = ci_encrypt($pk1);
		}
		$this->data['encrypt_monthpr_id'] = $pk1;


		$titleRow = $this->table('tb_paymonth')->get_array('paymonth_id, paymonth')->where("paymonth_id = '$data[monthpay]'");
		if(!empty($titleRow)){
			$monthpayPaymonthId = $titleRow['paymonth_id'];
			$monthpayPaymonth = $titleRow['paymonth'];
		}else{
			$monthpayPaymonthId = '';
			$monthpayPaymonth = '';
		}
		$this->data['monthpayPaymonthId'] = $monthpayPaymonthId;
		$this->data['monthpayPaymonth'] = $monthpayPaymonth;


		$titleRow = $this->table('tb_person')->get_array('emp_name, emp_surname')->where("name_id = '$data[rf_name_id]'");
		if(!empty($titleRow)){
			$rfNameIdEmpName = $titleRow['emp_name'];
			$rfNameIdEmpSurname = $titleRow['emp_surname'];
		}else{
			$rfNameIdEmpName = '';
			$rfNameIdEmpSurname = '';
		}
		$this->data['rfNameIdEmpName'] = $rfNameIdEmpName;
		$this->data['rfNameIdEmpSurname'] = $rfNameIdEmpSurname;

		$this->data['record_monthpr_id'] = $data['monthpr_id'];
		$this->data['record_yearpay'] = $data['yearpay'];
		$this->data['record_monthpay'] = $data['monthpay'];
		$this->data['record_rf_name_id'] = $data['rf_name_id'];
		$this->data['record_month_mony_sso'] = $data['month_mony_sso'];
		$this->data['record_month_de_ssop'] = $data['month_de_ssop'];
		$this->data['record_month_de_ssoc'] = $data['month_de_ssoc'];
		$this->data['record_month_ckrun'] = $data['month_ckrun'];

		$this->data['record_month_mony_sso'] = number_format($data['month_mony_sso'],2);
		$this->data['record_month_de_ssop'] = number_format($data['month_de_ssop'],2);
		$this->data['record_month_de_ssoc'] = number_format($data['month_de_ssoc'],2);

	}

	public function export_excel() 
	{
		// load excel library
		$this->load->library('reportuser/Excel');
		
		$results = $this->Monthcom->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);
		$this->data['data_list'] = $data_lists;
		$results = $this->Monthcom->sumexcel();
		$data_lists1 = $this->setDataListFormat($results['list_data'], 0);
		$this->data['data_list1'] = $data_lists1;
		$data = $this->data;
		
		$table	=  $this->parser->parse_repeat('reportuser/monthcom/export_excel', $data, true);

		$filename = "Export_Monthcom". date("Y-m-d-H-i-s")."";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0'); 
		
		foreach ($data_lists as $row)
		{
			$rfCompanyIdCompanyName = $row['rfCompanyIdCompanyName'];
			$rfCompanyIdCompanyNick = $row['rfCompanyIdCompanyNick'];
			$monthpayPaymonth = $row['monthpayPaymonth'];
			$yearpay = $row['yearpay'];
		}

		echo "บันทึกรายการรับเงิน ประจำบริษัท $rfCompanyIdCompanyName ($rfCompanyIdCompanyNick)<br>";
		echo "ประจำงวดเดือน $monthpayPaymonth";
		echo " ปี $yearpay";
		echo $table;

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

	 public function setdatazero($value)
	 {
	  $subject = '';
	   if ($value == 0) {
	       $subject = '';
	   } else {
	       $subject = number_format($value,2) ;
	   }

	   return $subject;
	 }

	public function print_pdf() 
	{
		// load  library
		$this->load->library('reportuser/Monthcom_list_pdf');
		
		$results = $this->Monthcom->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);
		$data['data_list'] = $data_lists;

		$pdf = new FPDI('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;
		
		$pdf->SetCreator("");
		$pdf->SetAuthor("");
		$pdf->SetTitle("ตารางแสดงรายการ ข้อมูล tb_summonth");
		$pdf->SetSubject("ตารางแสดงรายการ ข้อมูล tb_summonth");
				
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(15);
		$pdf->SetFooterMargin(0);
		
		$pdf->SetFont($font, '', 16);
		
		// Add a page
		$pdf->AddPage("P");
		
		$html = $this->parser->parse_repeat('reportuser/monthcom/list_view_pdf', $data, true);
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
		$pdf->lastPage();
		
		$pdf->Output('monthcom_list.pdf', 'I');
	}

	public function print_pdf_sso() 
	{
		// load PDF library
		$this->load->library('reportuser/Monthcom_list_pdf');

		$results = $this->Monthcom->pdf_sso();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);

		foreach ($data_lists as $row){
			$monthpayPaymonth = $row['monthpayPaymonth'];
			$payYear = $row['yearpay'];
			$rfBranchIdBranchName = $row['rfBranchIdBranchName'];
			$rfBranchIdBranchNick = $row['rfBranchIdBranchNick'];
			$rfBranchIdBranchCode = $row['rfBranchIdBranchCode'];
			$rfBranchIdBranchSocial = $row['rfBranchIdBranchSocial'];
			$rfBranchIdCompanyName = $row['rfBranchIdCompanyName'];
			$rfBranchIdSocialAccount = $row['rfBranchIdSocialAccount'];
		}

		$rfBranchIdSocialAccount = $this->textFormat($rfBranchIdSocialAccount ,'_________-_');

		$pdf = new FPDI('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;

		 // กำหนดรายละเอียดของ pdf
		 $pdf->SetCreator("");
		 $pdf->SetAuthor("");
		 $pdf->SetTitle("SSO_$rfBranchIdBranchCode");
		 $pdf->SetSubject("SSO_$rfBranchIdBranchCode");
		  
		 // กำหนดข้อมูลที่จะแสดงในส่วนของ header และ footer
		 $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE."รายละเอียดการนำส่งเงินสมทบ                                                                                                                                     "
		 ."สปส.1-10 (ส่วนที่2)"
		 , PDF_HEADER_STRING."สำหรับค่าจ้างเดือน         $monthpayPaymonth        พ.ศ. $payYear                                                                                                              "
		 ."เลขที่บัญชี $rfBranchIdSocialAccount "     
		 ." สถานประกอบการ บริษัท $rfBranchIdCompanyName                                                                                                      "
		 ."ลำดับที่สาขา  $rfBranchIdBranchSocial"
		 , array(0,0,0), array(0,0,0));
		 $pdf->setFooterData(Array(0,0,0), Array(0,0,0));

		// set header and footer fonts
		$pdf->setHeaderFont(Array($font,'B',16));
		$pdf->setFooterFont(Array($font,'B',16));
		
		$pdf->SetMargins(5, 0, 27);
		$pdf->SetHeaderMargin(8);
		$pdf->SetTopMargin(40);
		$pdf->SetFooterMargin(20);

		// Add a page
		$pdf->AddPage();

		$pdf->SetFillColor(255, 255, 255);

		// Iterate through each record
		$rowCount = 0;
		$total_mony_sso = 0;
		$total_de_ssop = 0;
		foreach ($data_lists as $row)
		{
			
			if($rowCount%16 == 0){
				$pdf->SetFont($font,'B',14);
				$pdf->Cell(15, 0, " ", 'LTR', 0, "L", true);
				$pdf->Cell(45, 0, " ", 'LTR', 0, "L", true);
				$pdf->Cell(25, 0, " ", 'LTR', 0, "L", true);
				$pdf->Cell(50, 0, " ", 'LTR', 0, "L", true);
				$pdf->Cell(50, 0, " ", 'LTR', 0, "L", true);
				$pdf->Cell(30, 0, " ", 'LTR', 0, "L", true);
				$pdf->Cell(50, 0, "เงินสมบทผู้ประกันตน", 'LTR', 0, "C", true);
				$pdf->Ln();
				
				$pdf->Cell(15, 7, "ลำดับที่", 'LR', 0, "C", true);
				$pdf->Cell(45, 7, "เลขประจำตัวบัตรประชาชน", 'LR', 0, "C", true);
				$pdf->Cell(25, 7, "คำนำหน้านาม", 'LR', 0, "C", true);
				$pdf->Cell(50, 7, "ชื่อ", 'LR', 0, "C", true);
				$pdf->Cell(50, 7, "ชื่อสกุล", 'LR', 0, "C", true);
				$pdf->Cell(30, 7, "ค่าจ้างที่จ่ายจริง", 'LR', 0, "C", true);
				$pdf->SetFont($font,'BI',10);
				$pdf->Cell(50, 7, "(ค่าจ้างที่ใช้ในการคำนวณไม่ต่ำกว่า 1,650 บาท", 'LR', 0, "C", true);
				$pdf->Ln();

				$pdf->Cell(15, 0, " ", 'LBR', 0, "L", true);
				$pdf->Cell(45, 0, " ", 'LBR', 0, "L", true);
				$pdf->Cell(25, 0, " ", 'LBR', 0, "L", true);
				$pdf->Cell(50, 0, " ", 'LBR', 0, "L", true);
				$pdf->Cell(50, 0, " ", 'LBR', 0, "L", true);
				$pdf->Cell(30, 0, " ", 'LBR', 0, "L", true);
				$pdf->Cell(50, 0, "และไม่เกิน 15,000 บาท)", 'LBR', 0, "C", true);
				$pdf->Ln();
			}

			$total_mony_sso+=$row['total_mony_sso'];
			$total_de_ssop+=$row['month_de_ssop'];

			$pdf->SetFont($font,'',16);
			$pdf->Cell(15, 0, $rowCount +1, 'LR' , 0, "C", true);
			$pdf->Cell(45, 0, $this->textFormat($row['rfNameIdNumCard']), 'LR' , 0, "C", true);
			$pdf->Cell(25, 0, '  '. $row['rfPernamePreName'], 'LR');
			$pdf->Cell(50, 0, '  '. $row['rfNameIdEmpName'], 'LR');
			$pdf->Cell(50, 0, '  '. $row['rfNameIdEmpSurname'], 'LR');
			$pdf->Cell(30, 0, $row['month_mony_sso'], 'LR', 0, "C", true);
			$pdf->Cell(50, 0, $row['month_de_ssop'], 'LR', 0, "C", true);
			$rowCount++;

			if($rowCount%16 == 0){
				
				$pdf->Ln();
				$pdf->Ln();
				$pdf->Cell(165, 0, "$rfBranchIdBranchName",0, 0, "L");
				$pdf->Cell(100, 0,  "ลงชื่อ..................................................ผู้นำส่งเงินสมทบ",0, 0, "R");
			}
			
			// Moving cursor to next row
			$pdf->Ln();
		}

		$total_mony_sso = number_format($total_mony_sso,2);
		$total_de_ssop = number_format($total_de_ssop,2);
		if($rowCount%16 == 0){
			$pdf->SetFont($font,'B',14);
			$pdf->Cell(15, 0, " ", 'LTR', 0, "L", true);
			$pdf->Cell(45, 0, " ", 'LTR', 0, "L", true);
			$pdf->Cell(25, 0, " ", 'LTR', 0, "L", true);
			$pdf->Cell(50, 0, " ", 'LTR', 0, "L", true);
			$pdf->Cell(50, 0, " ", 'LTR', 0, "L", true);
			$pdf->Cell(30, 0, " ", 'LTR', 0, "L", true);
			$pdf->Cell(50, 0, "เงินสมบทผู้ประกันตน", 'LTR', 0, "C", true);
			$pdf->Ln();
			
			$pdf->Cell(15, 7, "ลำดับที่", 'LR', 0, "C", true);
			$pdf->Cell(45, 7, "เลขประจำตัวบัตรประชาชน", 'LR', 0, "C", true);
			$pdf->Cell(25, 7, "คำนำหน้านาม", 'LR', 0, "C", true);
			$pdf->Cell(50, 7, "ชื่อ", 'LR', 0, "C", true);
			$pdf->Cell(50, 7, "ชื่อสกุล", 'LR', 0, "C", true);
			$pdf->Cell(30, 7, "ค่าจ้างที่จ่ายจริง", 'LR', 0, "C", true);
			$pdf->SetFont($font,'BI',10);
			$pdf->Cell(50, 7, "(ค่าจ้างที่ใช้ในการคำนวณไม่ต่ำกว่า 1,650 บาท", 'LR', 0, "C", true);
			$pdf->Ln();

			$pdf->Cell(15, 0, " ", 'LBR', 0, "L", true);
			$pdf->Cell(45, 0, " ", 'LBR', 0, "L", true);
			$pdf->Cell(25, 0, " ", 'LBR', 0, "L", true);
			$pdf->Cell(50, 0, " ", 'LBR', 0, "L", true);
			$pdf->Cell(50, 0, " ", 'LBR', 0, "L", true);
			$pdf->Cell(30, 0, " ", 'LBR', 0, "L", true);
			$pdf->Cell(50, 0, "และไม่เกิน 15,000 บาท)", 'LBR', 0, "C", true);
			$pdf->Ln();

			$pdf->SetFont($font,'',16);

			$pdf->Cell(15, 0, " ", 'T', 0, "L", true);
			$pdf->Cell(45, 0, " ", 'T', 0, "L", true);
			$pdf->Cell(25, 0, " ", 'T', 0, "L", true);
			$pdf->Cell(50, 0, " ", 'T', 0, "L", true);
			$pdf->Cell(50, 0, "รวม", 'LBTR', 0, "C", true);
			$pdf->Cell(30, 0, "$total_mony_sso", 'LBTR', 0, "C", true);
			$pdf->Cell(50, 0, "$total_de_ssop", 'LBTR', 0, "C", true);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(165, 0, "$rfBranchIdBranchName",0, 0, "L");
			$pdf->Cell(100, 0,  "ลงชื่อ..................................................ผู้นำส่งเงินสมทบ",0, 0, "R");
		}else{
			$pdf->SetFont($font,'',16);
			$pdf->Cell(15, 0, " ", 'T', 0, "L", true);
			$pdf->Cell(45, 0, " ", 'T', 0, "L", true);
			$pdf->Cell(25, 0, " ", 'T', 0, "L", true);
			$pdf->Cell(50, 0, " ", 'T', 0, "L", true);
			$pdf->Cell(50, 0, "รวม", 'LBTR', 0, "C", true);
			$pdf->Cell(30, 0, "$total_mony_sso", 'LBTR', 0, "C", true);
			$pdf->Cell(50, 0, "$total_de_ssop", 'LBTR', 0, "C", true);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(165, 0, "$rfBranchIdBranchName",0, 0, "L");
			$pdf->Cell(100, 0,  "ลงชื่อ..................................................ผู้นำส่งเงินสมทบ",0, 0, "R");
		}
		$pdf->Output("SSO $rfBranchIdBranchCode.pdf", 'I', 'UTF-8');
		
	}

	public function print_pdf_tax() 
	{
		// load PDF library
		$this->load->library('reportuser/Monthcom_list_pdf');

		$results = $this->Monthcom->pdf_tax();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);

		foreach ($data_lists as $row){
			$monthpayPaymonth = $row['monthpayPaymonth'];
			$rfBranchIdBranchName = $row['rfBranchIdBranchName'];
			$rfBranchIdBranchCode = $row['rfBranchIdBranchCode'];
			$rfBranchIdCompanyName = $row['rfBranchIdCompanyName'];
			$rfBranchIdTaxAccount = $row['rfBranchIdTaxAccount'];
		}

		$pdf = new FPDI('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;

		 $pdf->SetCreator("");
		 $pdf->SetAuthor("");
		 $pdf->SetTitle("Tax $rfBranchIdBranchCode");
		 $pdf->SetSubject("Tax $rfBranchIdBranchCode");

		$pdf->setFooterFont(Array($font,'B',14));
		
		$pdf->SetMargins(13, 10, 20);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(15);
		$pdf->SetFooterMargin(20);

		$pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		// // Add a page
		$pdf->AddPage();

		$pdf->SetFillColor(255, 255, 255);

		// Iterate through each record
		$rowCount = 0;
		$total_tax = 0;
		$total_salary = 0;

		foreach ($data_lists as $row)
		{
			if($rowCount%16 == 0){
				$pdf->SetFont($font,'BI',12);
				$pdf->Cell(15, 7, " ",0, 0, "L");
				$pdf->Cell(180, 7, " ",0, 0, "L");
				$pdf->Cell(70, 7, "ทะเบียนนิติบุคคลเลขที่ (ของผู้จ่ายเงินได้)",'LTBR', 0, "C");
				$pdf->Ln();

				$pdf->SetFont($font,'BI',12);
				$pdf->Cell(15, 7, " ",0, 0, "L");
				$pdf->Cell(180, 7, " ",0, 0, "L");
				$pdf->Cell(70, 7, "$rfBranchIdTaxAccount",'LTBR', 0, "C");
				$pdf->Ln();


				$pdf->SetFont($font,'BI',16);
				$pdf->Cell(5, 12, " ",'B', 0, "L");
				$pdf->Cell(15, 12, "ใบต่อ",'B', 0, "L");
				$pdf->SetFont($font,'BI',24);
				$pdf->Cell(40, 12, "ภ.ง.ด. 1",'B', 0, "L");
				$pdf->SetFont($font,'B',18);
				$pdf->Cell(135, 12,  "$rfBranchIdCompanyName",'B', 0, "C");
				$pdf->SetFont($font,'BI',11);
				$pdf->Cell(70, 12, "ใบต่อฉบับที่.....................................................",'B', 0, "R");
				$pdf->Ln();

				$pdf->SetFont($font,'BI',11);
				$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
				$pdf->Cell(50, 0, "ชื่อผู้มีเงินได้", 'LTR', 0, "C", true);
				$pdf->Cell(30, 0, "เลขบัตรประชาชน", 'LTR', 0, "C", true);
				$pdf->Cell(70, 0, "รายการลดหย่อน", 'LTR', 0, "C", true);
				$pdf->Cell(40, 0, "ประเภทเงินได้พึงประเมินที่จ่าย", 'LTR', 0, "C", true);
				$pdf->Cell(25, 0, "รวมเงินที่จ่ายทุก", 'LTR', 0, "C", true);
				$pdf->Cell(40, 0, "เงินภาษีนำส่งในครั้งนี้", 'LTR', 0, "C", true);
				$pdf->Ln();
				
				$pdf->Cell(10, 0, "ลำดับ", 'LR', 0, "C", true);
				$pdf->Cell(50, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(30, 0, " ", 'LR', 0, "C", true);
				$pdf->SetFont($font,'BI',10);
				$pdf->Cell(15, 0, "มีสามี", 'LTR', 0, "C", true);
				$pdf->Cell(20, 0, "จำนวนบุตร", 'LTBR', 0, "C", true);
				$pdf->Cell(35, 0, "ค่าลดหย่อนอื่นๆ", 'LBTR', 0, "C", true);
				$pdf->Cell(40, 0, "(รวมทั้งประโยชน์เพิ่มอย่างอื่น)", 'LBR', 0, "C", true);
				$pdf->SetFont($font,'BI',11);
				$pdf->Cell(25, 0, "ประเภทเฉพาะคน", 'LR', 0, "C", true);
				$pdf->SetFont($font,'BI',10);
				$pdf->Cell(20, 0, "จำนวนเงิน", 'LTR', 0, "C", true);
				$pdf->Cell(20, 0, "1) หัก ณ ที่จ่าย", 'LTR', 0, "L", true);
				$pdf->Ln();

				$pdf->SetFont($font,'BI',11);
				$pdf->Cell(10, 0, " ", 'LR', 0, "L", true);
				$pdf->Cell(50, 0, "ที่อยู่ของผู้มีเงินได้", 'LTR', 0, "C", true);
				$pdf->Cell(30, 0, "(ของผู้มีเงินได้)", 'LR', 0, "C", true);
				$pdf->SetFont($font,'BI',10);
				$pdf->Cell(15, 0, "ภริยา", 'LR', 0, "C", true);
				$pdf->Cell(10, 0, "ศึกษา", 'LTR', 0, "C", true);
				$pdf->Cell(10, 0, "ไม่", 'LTR', 0, "C", true);
				$pdf->Cell(20, 0, "วันเดือนปี", 'LTR', 0, "C", true);
				$pdf->Cell(15, 0, "จำนวนเงิน", 'LTR', 0, "C", true);
				$pdf->Cell(20, 0, "ประเภทเงินได้", 'LTR', 0, "C", true);
				$pdf->Cell(20, 0, "จำนวนเงินที่จ่าย", 'LBTR', 0, "C", true);
				$pdf->SetFont($font,'BI',11);
				$pdf->Cell(25, 0, "หนึ่งๆ", 'LBR', 0, "C", true);
				$pdf->SetFont($font,'BI',10);
				$pdf->Cell(20, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(20, 0, "2) ออกให้ตลอด", 'LR', 0, "L", true);
				$pdf->Ln();

				$pdf->Cell(10, 0, " ", 'LBR', 0, "L", true);
				$pdf->Cell(50, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(30, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, "หรือไม่", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(10, 0, "ศึกษา", 'LBR', 0, "C", true);
				$pdf->Cell(20, 0, "ที่จ่าย", 'LBR', 0, "C", true);
				$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(20, 0, " ", 'LBR', 0, "C", true);
				$pdf->Cell(20, 0, "บาท", 'LBTR', 0, "C", true);
				$pdf->Cell(25, 0, "บาท", 'LBTR', 0, "C", true);
				$pdf->Cell(20, 0, "บาท", 'LBTR', 0, "C", true);
				$pdf->Cell(20, 0, "3) ออกให้ครั้งเดียว", 'LBR', 0, "L", true);
				$pdf->Ln();

			}

			$total_salary+=$row['rfPayprIdSalaryNet'];
			$total_tax+=$row['rfPayprIdDeTax'];
			
			$pdf->SetFont($font,'BI',14);
			if( $rowCount%16 == 0 ){
			$pdf->Cell(10, 7, $rowCount +1 , 'LTR' , 0, "C", true);
			$pdf->Cell(50, 7, ' ' .$row['rfPernamePreName'] . $row['rfNameIdEmpName'] .'  '. $row['rfNameIdEmpSurname'], 'LTR', 0, "L", true);
			$pdf->SetFont($font,'BI',12);
			$pdf->Cell(30, 7, $this->textFormat($row['rfNameIdNumCard']), 'LTR', 0, "C", true);
			$pdf->Cell(15, 7, $row['rfNameIdTypestatusId'], 'LTR', 0, "C", true);
			$pdf->Cell(10, 7, $this->setdatazero($row['rfNameIdPersonNumStudent']), 'LTR', 0, "C", true);
			$pdf->Cell(10, 7, $this->setdatazero($row['rfNameIdPersonNumChildren']), 'LTR', 0, "C", true);
			$pdf->Cell(20, 7, '', 'LTR', 0, "C", true);
			$pdf->Cell(15, 7, $this->setdatazero($row['rfNameIdPersonTotal']), 'LTR', 0, "C", true);
			$pdf->SetFont($font,'BI',14);
			$pdf->Cell(20, 7, $row['rfNameIdTypeincome'], 'LTR', 0, "C", true);
			$pdf->SetFont($font,'BI',12);
			$pdf->Cell(20, 7, $this->setdatazero($row['rfPayprIdSalaryNet']) , 'LTR', 0, "C", true);
			$pdf->Cell(25, 7, $this->setdatazero($row['rfPayprIdSalaryNet']) , 'LTR', 0, "C", true);
			$pdf->Cell(20, 7, $this->setdatazero($row['rfPayprIdDeTax']), 'LTR', 0, "C", true);
			$pdf->Cell(20, 7, $row['rfNameIdTypetexId'], 'LTR', 0, "C", true);

			$rowCount++;
			}else{
			$pdf->Cell(10, 7, $rowCount +1 , 'LR' , 0, "C", true);
			$pdf->Cell(50, 7, ' ' .$row['rfPernamePreName'] . $row['rfNameIdEmpName'] .'  '. $row['rfNameIdEmpSurname'], 'LR', 0, "L", true);
			$pdf->SetFont($font,'BI',12);
			$pdf->Cell(30, 7, $this->textFormat($row['rfNameIdNumCard']), 'LR', 0, "C", true);
			$pdf->Cell(15, 7, $row['rfNameIdTypestatusId'], 'LR', 0, "C", true);
			$pdf->Cell(10, 7, $this->setdatazero($row['rfNameIdPersonNumStudent']), 'LR', 0, "C", true);
			$pdf->Cell(10, 7, $this->setdatazero($row['rfNameIdPersonNumChildren']), 'LR', 0, "C", true);
			$pdf->Cell(20, 7, '', 'LR', 0, "C", true);
			$pdf->Cell(15, 7, $this->setdatazero($row['rfNameIdPersonTotal']), 'LR', 0, "C", true);
			$pdf->SetFont($font,'BI',14);
			$pdf->Cell(20, 7, $row['rfNameIdTypeincome'], 'LR', 0, "C", true);
			$pdf->SetFont($font,'BI',12);
			$pdf->Cell(20, 7, $this->setdatazero($row['rfPayprIdSalaryNet']) , 'LR', 0, "C", true);
			$pdf->Cell(25, 7, $this->setdatazero($row['rfPayprIdSalaryNet']) , 'LR', 0, "C", true);
			$pdf->Cell(20, 7, $this->setdatazero($row['rfPayprIdDeTax']), 'LR', 0, "C", true);
			$pdf->Cell(20, 7, $row['rfNameIdTypetexId'], 'LR', 0, "C", true);

			$rowCount++;
			}

			if($rowCount%16 == 0){
				$pdf->Ln();
				$pdf->Ln();
				$pdf->Cell(100, 0, " ",0, 0, "L");
				$pdf->Cell(65, 0, "$monthpayPaymonth",0, 0, "C");
				$pdf->Cell(100, 0,  "$rfBranchIdBranchName",0, 0, "R");
			}
			
			// Moving cursor to next row
			$pdf->Ln();
		}

		$total_salary = number_format($total_salary,2);
		$total_tax = number_format($total_tax,2);

		if($rowCount%16 == 0){

			$pdf->Ln();
			$pdf->Ln();
			$pdf->SetFont($font,'BI',12);
			$pdf->Cell(15, 7, " ",0, 0, "L");
			$pdf->Cell(180, 7, " ",0, 0, "L");
			$pdf->Cell(70, 7, "ทะเบียนนิติบุคคลเลขที่ (ของผู้จ่ายเงินได้)",'LTBR', 0, "C");
			$pdf->Ln();

			$pdf->SetFont($font,'BI',12);
			$pdf->Cell(15, 7, " ",0, 0, "L");
			$pdf->Cell(180, 7, " ",0, 0, "L");
			$pdf->Cell(70, 7, "$rfBranchIdTaxAccount",'LTBR', 0, "C");
			$pdf->Ln();

			$pdf->SetFont($font,'BI',16);
			$pdf->Cell(5, 12, " ",'B', 0, "L");
			$pdf->Cell(15, 12, "ใบต่อ",'B', 0, "L");
			$pdf->SetFont($font,'BI',24);
			$pdf->Cell(40, 12, "ภ.ง.ด. 1",'B', 0, "L");
			$pdf->SetFont($font,'B',18);
			$pdf->Cell(135, 12,  "$rfBranchIdCompanyName",'B', 0, "C");
			$pdf->SetFont($font,'BI',11);
			$pdf->Cell(70, 12, "ใบต่อฉบับที่.....................................................",'B', 0, "R");
			$pdf->Ln();

			$pdf->SetFont($font,'BI',11);
			$pdf->Cell(10, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(50, 0, "ชื่อผู้มีเงินได้", 'LTR', 0, "C", true);
			$pdf->Cell(30, 0, "เลขบัตรประชาชน", 'LTR', 0, "C", true);
			$pdf->Cell(70, 0, "รายการลดหย่อน", 'LTR', 0, "C", true);
			$pdf->Cell(40, 0, "ประเภทเงินได้พึงประเมินที่จ่าย", 'LTR', 0, "C", true);
			$pdf->Cell(25, 0, "รวมเงินที่จ่ายทุก", 'LTR', 0, "C", true);
			$pdf->Cell(40, 0, "เงินภาษีนำส่งในครั้งนี้", 'LTR', 0, "C", true);
			$pdf->Ln();
			
			$pdf->Cell(10, 0, "ลำดับ", 'LR', 0, "C", true);
			$pdf->Cell(50, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(30, 0, " ", 'LR', 0, "C", true);
			$pdf->SetFont($font,'BI',10);
			$pdf->Cell(15, 0, " ", 'LTR', 0, "C", true);
			$pdf->Cell(20, 0, "จำนวนบุตร", 'LTBR', 0, "C", true);
			$pdf->Cell(35, 0, "ค่าลดหย่อนอื่นๆ", 'LBTR', 0, "C", true);
			$pdf->Cell(40, 0, "(รวมทั้งประโยชน์เพิ่มอย่างอื่น)", 'LBR', 0, "C", true);
			$pdf->SetFont($font,'BI',11);
			$pdf->Cell(25, 0, "ประเภทเฉพาะคน", 'LR', 0, "C", true);
			$pdf->SetFont($font,'BI',10);
			$pdf->Cell(20, 0, "จำนวนเงิน", 'LTR', 0, "C", true);
			$pdf->Cell(20, 0, "1) หัก ณ ที่จ่าย", 'LTR', 0, "L", true);
			$pdf->Ln();

			$pdf->SetFont($font,'BI',11);
			$pdf->Cell(10, 0, " ", 'LR', 0, "L", true);
			$pdf->Cell(50, 0, "ที่อยู่ของผู้มีเงินได้", 'LTR', 0, "C", true);
			$pdf->Cell(30, 0, "(ของผู้มีเงินได้)", 'LR', 0, "C", true);
			$pdf->SetFont($font,'BI',10);
			$pdf->Cell(15, 0, "สถานะ", 'LR', 0, "C", true);
			$pdf->Cell(10, 0, "ศึกษา", 'LTR', 0, "C", true);
			$pdf->Cell(10, 0, "ไม่", 'LTR', 0, "C", true);
			$pdf->Cell(20, 0, "วันเดือนปี", 'LTR', 0, "C", true);
			$pdf->Cell(15, 0, "จำนวนเงิน", 'LTR', 0, "C", true);
			$pdf->Cell(20, 0, "ประเภทเงินได้", 'LTR', 0, "C", true);
			$pdf->Cell(20, 0, "จำนวนเงินที่จ่าย", 'LBTR', 0, "C", true);
			$pdf->SetFont($font,'BI',11);
			$pdf->Cell(25, 0, "หนึ่งๆ", 'LBR', 0, "C", true);
			$pdf->SetFont($font,'BI',10);
			$pdf->Cell(20, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(20, 0, "2) ออกให้ตลอด", 'LR', 0, "L", true);
			$pdf->Ln();

			$pdf->Cell(10, 0, " ", 'LBR', 0, "L", true);
			$pdf->Cell(50, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(30, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(10, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(10, 0, "ศึกษา", 'LBR', 0, "C", true);
			$pdf->Cell(20, 0, "ที่จ่าย", 'LBR', 0, "C", true);
			$pdf->Cell(15, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(20, 0, " ", 'LBR', 0, "C", true);
			$pdf->Cell(20, 0, "บาท", 'LBTR', 0, "C", true);
			$pdf->Cell(25, 0, "บาท", 'LBTR', 0, "C", true);
			$pdf->Cell(20, 0, "บาท", 'LBTR', 0, "C", true);
			$pdf->Cell(20, 0, "3) ออกให้ครั้งเดียว", 'LBR', 0, "L", true);
			$pdf->Ln();

			$pdf->Cell(145, 7, " ", 'T', 0, "L", true);
			$pdf->Cell(35, 7, "รวมเป็นเงินภาษีที่นำส่ง", 'LBTR', 0, "C", true);
			$pdf->Cell(20, 7, "$total_salary", 'LBTR', 0, "C", true);
			$pdf->Cell(25, 7, "$total_salary", 'LBTR', 0, "C", true);
			$pdf->Cell(20, 7, "$total_tax", 'LBTR', 0, "C", true);
			$pdf->Cell(20, 7, " ", 'LBR', 0, "C", true);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(100, 0, " ",0, 0, "L");
			$pdf->Cell(65, 0, "$monthpayPaymonth",0, 0, "C");
			$pdf->Cell(100, 0,  "$rfBranchIdBranchName",0, 0, "R");
		}else{
			$pdf->Cell(145, 7, " ", 'T', 0, "L", true);
			$pdf->Cell(35, 7, "รวมเป็นเงินภาษีที่นำส่ง", 'LBTR', 0, "C", true);
			$pdf->Cell(20, 7, "$total_salary", 'LBTR', 0, "C", true);
			$pdf->Cell(25, 7, "$total_salary", 'LBTR', 0, "C", true);
			$pdf->Cell(20, 7, "$total_tax", 'LBTR', 0, "C", true);
			$pdf->Cell(20, 7, " ", 'LBR', 0, "C", true);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(100, 0, " ",0, 0, "L");
			$pdf->Cell(65, 0, "$monthpayPaymonth",0, 0, "C");
			$pdf->Cell(100, 0,  "$rfBranchIdBranchName",0, 0, "R");
		}

		$pdf->Output("Tax $rfBranchIdBranchCode.pdf", 'I');
		
	}



}
/*---------------------------- END Controller Class --------------------------------*/
