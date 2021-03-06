<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * [ Controller File name : Summonth.php ]
 */
class Summonth extends CRUD_Controller
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
		$this->load->model('reportuser/Summonth_model', 'Summonth');
		$this->data['page_url'] = site_url('reportuser/summonth');
		
		$this->data['page_title'] = 'THITARAM GROUP';

		$js_url = 'assets/js_modules/reportuser/summonth.js?ft='. filemtime('assets/js_modules/reportuser/summonth.js');
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
				$this->data['alert_message'] = '????????????????????????????????????????????????????????? <b></b>';
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
		$this->session->unset_userdata($this->Summonth->session_name . '_search_field');
		$this->session->unset_userdata($this->Summonth->session_name . '_value');

		$this->search();
	}

	// ------------------------------------------------------------------------

	/**
	 * Search data
	 */
	public function search()
	{
		$this->breadcrumb_data['breadcrumb'] = array(
						array('title' => 'Summonth', 'class' => 'active', 'url' => '#'),
		);
	
		if (isset($_POST['submit'])) {
			$search_field =  $this->input->post('search_field', TRUE);
			$value = $this->input->post('txtSearch', TRUE);
			$arr = array($this->Summonth->session_name . '_search_field' => $search_field, $this->Summonth->session_name . '_value' => $value );
			$this->session->set_userdata($arr);
		} else {
			$search_field = $this->session->userdata($this->Summonth->session_name . '_search_field');
			$value = $this->session->userdata($this->Summonth->session_name . '_value');
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
			$this->Summonth->order_field = $field;
			$this->Summonth->order_sort = $sort;
		}
		$results = $this->Summonth->read($start_row, $per_page);
		$total_row = $results['total_row'];
		$search_row = $results['search_row'];
		$list_data = $this->setDataListFormat($results['list_data'], $start_row);


		$page_url = site_url('reportuser/summonth');
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

		$this->render_view('reportuser/summonth/list_view');
	}

	// ------------------------------------------------------------------------

	/**
	 * Preview Data
	 * @param String encrypt id
	 */
	public function preview($encrypt_id = "")
	{
		$this->breadcrumb_data['breadcrumb'] = array(
						array('title' => 'Summonth', 'url' => site_url('reportuser/summonth')),
						array('title' => '????????????????????????????????????????????????????????????', 'url' => '#', 'class' => 'active')
		);
		$encrypt_id = urldecode($encrypt_id);
		$id = ci_decrypt($encrypt_id);
		if ($id == "") {
			$this->data['message'] = "????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????";
			$this->render_view('ci_message/warning');
		} else {
			$results = $this->Summonth->load($id);
			if (empty($results)) {
				$this->data['message'] = "??????????????????????????????????????????????????????????????????????????? <b>$id</b>";
				$this->render_view('ci_message/danger');
			} else {
				$this->setPreviewFormat($results);
				$this->render_view('reportuser/summonth/preview_view');
			}
		}
	}


	// ------------------------------------------------------------------------

	public function preview_print_pdf($encrypt_id = "") 
	{
		// load PDF library
		$this->load->library('reportuser/Summonth_preview_pdf');
		
		$id = ci_decrypt(urldecode($encrypt_id));
		$results = $this->Summonth->load($id);
		$this->setPreviewFormat($results);
		$data_lists = array();
		$this->data['detail_list'] = $data_lists;
		$data = $this->data;
		
		$pdf = new FPDI('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;
		
		$pdf->SetCreator("");
		$pdf->SetAuthor("");
		$pdf->SetTitle("????????????????????????????????????????????? ?????????????????? tb_summonth");
		$pdf->SetSubject("????????????????????????????????????????????? ?????????????????? tb_summonth");
				
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		$pdf->SetMargins(10, 10, 10);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(15);
		$pdf->SetFooterMargin(0);
		
		$pdf->SetFont($font, '', 16);
		
		// Add a page
		$pdf->AddPage("P");
		
		$html = $this->parser->parse_repeat('reportuser/summonth/preview_view_pdf', $data, true);
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
		
		$pdf->lastPage();
		
		$pdf->Output('Summonth_list.pdf', 'I');
	}

	public function preview_export_excel($encrypt_id = "") 
	{	
		$id = ci_decrypt(urldecode($encrypt_id));
		$results = $this->Summonth->load($id);
		$this->setPreviewFormat($results);
		$data_lists = array();
		$this->data['detail_list'] = $data_lists;
		$data = $this->data;
	
		$table	=  $this->parser->parse_repeat('reportuser/summonth/preview_view_excel', $data, true);

		$filename = "Summonth_preview". date("Y-m-d-H-i-s")."";
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
						array('title' => 'Summonth', 'url' => site_url('reportuser/summonth')),
						array('title' => '?????????????????????????????????', 'url' => '#', 'class' => 'active')
		);
		$this->data['tb_paymonth_monthpay_option_list'] = $this->Summonth->returnOptionList("tb_paymonth", "paymonth_id", "CONCAT_WS(' - ', paymonth_id,paymonth)" );
		$this->data['tb_person_rf_name_id_option_list'] = $this->Summonth->returnOptionList("tb_person", "name_id", "CONCAT_WS(' - ', emp_name,emp_surname)" );
		$this->render_view('reportuser/summonth/add_view');
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

		$frm->set_rules('yearpay', '??????', 'trim|required');
		$frm->set_rules('monthpay', '???????????????', 'trim|required');
		$frm->set_rules('rf_name_id', '?????????????????????????????????', 'trim|required');

		$frm->set_message('required', '- ??????????????????????????? %s');
		

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

		$frm->set_rules('yearpay', '??????', 'trim|required');
		$frm->set_rules('monthpay', '???????????????', 'trim|required');
		$frm->set_rules('rf_name_id', '?????????????????????????????????', 'trim|required');

		$frm->set_message('required', '- ??????????????????????????? %s');
		

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
			$id = $this->Summonth->create($post);
			if($id != ''){
				$success = TRUE;
				$encrypt_id = ci_encrypt($id);
				$message = '<strong>???????????????????????????????????????????????????????????????</strong>';
			}else{
				$success = FALSE;
				$message = 'Error : ' . $this->Summonth->error_message;
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
						array('title' => 'Summonth', 'url' => site_url('reportuser/summonth')),
						array('title' => '?????????????????????????????????', 'url' => '#', 'class' => 'active')
		);

		$encrypt_id = urldecode($encrypt_id);
		$id = ci_decrypt($encrypt_id);
		if ($id == "") {
			$this->data['message'] = "???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????";
			$this->render_view('ci_message/warning');
		} else {
			$results = $this->Summonth->load($id);
			if (empty($results)) {
			$this->data['message'] = "??????????????????????????????????????????????????????????????????????????? <b>$id</b>";
				$this->render_view('ci_message/danger');
			} else {
				$this->data['csrf_field'] = insert_csrf_field(true);


				$this->setPreviewFormat($results);

				$this->data['tb_paymonth_monthpay_option_list'] = $this->Summonth->returnOptionList("tb_paymonth", "paymonth_id", "CONCAT_WS(' - ', paymonth_id,paymonth)" );
				$this->data['tb_person_rf_name_id_option_list'] = $this->Summonth->returnOptionList("tb_person", "name_id", "CONCAT_WS(' - ', emp_name,emp_surname)" );
				$this->render_view('reportuser/summonth/edit_view');
			}
		}
	}

	// ------------------------------------------------------------------------
	public function checkRecordKey($data)
	{
		$error = '';
		$monthpr_id = ci_decrypt($data['encrypt_monthpr_id']);
		if($monthpr_id==''){
			$error .= '- ???????????? monthpr_id';
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
			$message .= '??????????????????????????????';
		}
		
		$post = $this->input->post(NULL, TRUE);
		$error_pk_id = $this->checkRecordKey($post);
		if ($error_pk_id != '') {
			$message .= "???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????";
		}
		if ($message != '') {
			$json = json_encode(array(
						'is_successful' => FALSE,
						'message' => $message
			));
			 echo $json;
		} else {

			$result = $this->Summonth->update($post);
			if($result == false){
				$message = $this->Summonth->error_message;
				$ok = FALSE;
			}else{
				$message = '<strong>???????????????????????????????????????????????????????????????</strong>' . $this->Summonth->error_message;
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
			$message .= '??????????????????????????????';
		}
		
		$post = $this->input->post(NULL, TRUE);
		$error_pk_id = $this->checkRecordKey($post);
		if ($error_pk_id != '') {
			$message .= "???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????";
		}
		if ($message != '') {
			$json = json_encode(array(
						'is_successful' => FALSE,
						'message' => $message    
			));
			echo $json;
		}else{
			$result = $this->Summonth->delete($post);
			if($result == false){
				$message = $this->Summonth->error_message;
				$ok = FALSE;
			}else{
				$message = '<strong>???????????????????????????????????????????????????</strong>';
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
		
		$results = $this->Summonth->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
       
		// set Header ***** SECTION 1 ***** 
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', '????????????');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', '??????');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', '???????????????');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', '?????????????????????????????????');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', '?????????????????????????????????????????????????????????????????????????????????????????????');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', '??????????????????????????????????????????????????????????????????????????????????????????????????????????????????');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', '??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', '???????????????????????????????????????');

		// END SECTION 1
		
		// set header bold
		$objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFont()->setBold( true );
							
		// set Row
		$rowCount = 2;
		foreach ($data_lists as $row) {
		
			// ***** SECTION 2 *****

			$sheet = $objPHPExcel->getActiveSheet();
			$sheet->SetCellValue('A' . $rowCount, $row['monthpr_id']);
			$sheet->setCellValueExplicit('B' . $rowCount, $row['yearpay'], PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit('C' . $rowCount, $row['monthpayPaymonthId']. ' ' .$row['monthpayPaymonth'], PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit('D' . $rowCount, $row['rfNameIdEmpName']. ' ' .$row['rfNameIdEmpSurname'], PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->SetCellValue('E' . $rowCount, $row['month_mony_sso']);
			$sheet->SetCellValue('F' . $rowCount, $row['month_de_ssop']);
			$sheet->SetCellValue('G' . $rowCount, $row['month_de_ssoc']);
			$sheet->SetCellValue('H' . $rowCount, $row['month_ckrun']);

			
			$rowCount++;
		}
		
		foreach(range('A','I') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		
		$filename = "Summonth_list". date("Y-m-d-H-i-s").".xlsx";
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0'); 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  

		$objWriter->save('php://output'); 

	}
}
/*---------------------------- END Controller Class --------------------------------*/
