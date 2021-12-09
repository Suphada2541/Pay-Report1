<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');

/**
 * [ Controller File name : Yearbra.php ]
 */
class Yearbra extends CRUD_Controller
{

	private $per_page;
	private $another_js;
	private $another_css;

	public function __construct()
	{
		parent::__construct();
		$this->per_page = 0;
		$this->num_links = 6;
		$this->uri_segment = 4;
		$this->load->model('reportuser/yearbra_model', 'Yearbra');
		$this->data['page_url'] = site_url('reportuser/yearbra');

		$this->data['page_title'] = 'THITARAM GROUP';

		$js_url = 'assets/js_modules/reportuser/yearbra.js?ft=' . filemtime('assets/js_modules/reportuser/yearbra.js');
		$this->another_js .= '<script src="' . base_url($js_url) . '"></script>';
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
		if ($this->session->userdata('login_validated') == false) {
			$this->data['page_content'] = $this->parser->parse_repeat('member_permission.php', $this->data, TRUE);
			$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->session->set_userdata('after_login_redirect', $current_url);
		} else {
			if ($this->session->userdata('user_level') >= 5) {
				$this->data['page_content'] = $this->parser->parse_repeat($path, $this->data, TRUE);
			} else {
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
	public function create_pagination($page_url, $total)
	{
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
	public function list_all()
	{
		$this->session->unset_userdata($this->Yearbra->session_name . '_search_field');
		$this->session->unset_userdata($this->Yearbra->session_name . '_value');
		$this->session->unset_userdata($this->Yearbra->session_name . '_payyear');
		$this->session->unset_userdata($this->Payprbra->session_name . '_report');
		$this->search();
	}

	// ------------------------------------------------------------------------

	/**
	 * Search data
	 */
	public function search()
	{
		$this->breadcrumb_data['breadcrumb'] = array(
			array('title' => 'Mainpaypr', 'url' => site_url('reportuser/mainpaypr')),
			array('title' => 'Yearbra', 'class' => 'active', 'url' => '#'),
		);

		$options1 = array("where" => " branch_void = 0");
		$this->data['tb_branch_rf_branch_id_option_list'] = $this->Yearbra->returnOptionList("tb_branch", "branch_id", "branch_nick", $options1);

		$options3 = array("where" => " report_type = 4 AND report_void = 0");
		$this->data['tb_report_report_id_option_list'] = $this->Yearbra->returnOptionList("tb_report", "report_id", "CONCAT_WS(' - ',report_file,report_name)", $options3);
		if (isset($_POST['submit'])) {
			$search_field =  $this->input->post('search_field', TRUE);
			$value = $this->input->post('txtSearch', TRUE);
			$payyear = $this->input->post('txtYear', TRUE);
			$report = $this->input->post('rf_report_id', TRUE);

			$arr = array(
				$this->Yearbra->session_name . '_search_field' => $search_field,
				$this->Yearbra->session_name . '_value' => $value,
				$this->Yearbra->session_name . '_payyear' => $payyear,
				$this->Payprbra->session_name . '_report' => $report
			);

			$this->session->set_userdata($arr);
		} else {
			$search_field = $this->session->userdata($this->Yearbra->session_name . '_search_field');
			$value = $this->session->userdata($this->Yearbra->session_name . '_value');
			$payyear = $this->session->userdata($this->Yearbra->session_name . '_payyear');
			$report = $this->session->userdata($this->Payprbra->session_name . '_report');
		}

		$start_row = $this->uri->segment($this->uri_segment, '0');
		if (!is_numeric($start_row)) {
			$start_row = 0;
		}
		$per_page = $this->per_page;
		$order_by =  $this->input->post('order_by', TRUE);
		if ($order_by != '') {
			$arr = explode('|', $order_by);
			$field = $arr[0];
			$sort = $arr[1];
			switch ($sort) {
				case 'asc':
					$sort = 'ASC';
					break;
				case 'desc':
					$sort = 'DESC';
					break;
				default:
					$sort = 'DESC';
					break;
			}
			$this->Yearbra->order_field = $field;
			$this->Yearbra->order_sort = $sort;
		}
		$results = $this->Yearbra->read($start_row, $per_page);
		$total_row = $results['total_row'];
		$search_row = $results['search_row'];
		$list_data = $this->setDataListFormat($results['list_data'], $start_row);


		$page_url = site_url('reportuser/yearbra');
		$pagination = $this->create_pagination($page_url . '/search', $search_row);
		$end_row = $start_row + $per_page;
		if ($search_row < $per_page) {
			$end_row = $search_row;
		}

		if ($end_row > $search_row) {
			$end_row = $search_row;
		}

		$this->data['data_list']	= $list_data;
		$this->data['search_field']	= $search_field;
		$this->data['txt_search']	= $value;
		$this->data['txt_year']	= $payyear;
		$this->data['txt_report'] = $report;
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

		$this->render_view('reportuser/yearbra/list_view');
	}

	// ------------------------------------------------------------------------

	/**
	 * SET โชว์วันที่ 0000/00/00 เป็นค่าว่าง
	 */
	private function setDateView($value)
	{
		$subject = '';

		if ($value >= 1) {
			$subject = setThaiDate($value);
		}
		// elseif($value == '0000-00-00' || $value != '00/00/0000'){
		// 	$subject = '';
		// }
		else {
			$subject = '';
		}
		return $subject;
	}

	function textFormat($text = '', $pattern = '', $ex = '')
	{
		$cid = ($text == '') ? '0000000000000' : $text;
		$pattern = ($pattern == '') ? '_-____-_____-__-_' : $pattern;
		$p = explode('-', $pattern);
		$ex = ($ex == '') ? '-' : $ex;
		$first = 0;
		$last = 0;
		for ($i = 0; $i <= count($p) - 1; $i++) {
			$first = $first + $last;
			$last = strlen($p[$i]);
			$returnText[$i] = substr($cid, $first, $last);
		}

		return implode($ex, $returnText);
	}

	public function setdatazero($value)
	{
		$subject = '';
		if ($value == 0) {
			$subject = '';
		} else {
			$subject = number_format($value, 2);
		}

		return $subject;
	}


	/**
	 * SET array data list
	 */
	private function setDataListFormat($lists_data, $start_row = 0)
	{
		$data = $lists_data;
		$count = count($lists_data);
		for ($i = 0; $i < $count; $i++) {
			$start_row++;
			$data[$i]['record_number'] = $start_row;
			$pk1 = $data[$i]['yearpr_id'];
			$data[$i]['url_encrypt_id'] = urlencode(encrypt($pk1));

			if ($pk1 != '') {
				$pk1 = ci_encrypt($pk1);
			}
			$data[$i]['encrypt_yearpr_id'] = $pk1;
			$data[$i]['yearpr_absent_num'] = number_format($data[$i]['yearpr_absent_num'], 2);
			$data[$i]['yearpr_bnleave_num'] = number_format($data[$i]['yearpr_bnleave_num'], 2);
			$data[$i]['yearpr_bleave_num'] = number_format($data[$i]['yearpr_bleave_num'], 2);
			$data[$i]['yearpr_bleave_pay'] = number_format($data[$i]['yearpr_bleave_pay'], 2);
			$data[$i]['yearpr_aleave_num'] = number_format($data[$i]['yearpr_aleave_num'], 2);
			$data[$i]['yearpr_sleave_num'] = number_format($data[$i]['yearpr_sleave_num'], 2);
			$data[$i]['yearpr_sleave_pay'] = number_format($data[$i]['yearpr_sleave_pay'], 2);
			$data[$i]['yearpr_adleave_pay'] = number_format($data[$i]['yearpr_adleave_pay'], 2);
			$data[$i]['yearpr_ahleave_pay'] = number_format($data[$i]['yearpr_ahleave_pay'], 2);
			$data[$i]['yearpr_mleave_pay'] = number_format($data[$i]['yearpr_mleave_pay'], 2);
			$data[$i]['yearpr_oleave_pay'] = number_format($data[$i]['yearpr_oleave_pay'], 2);
			$data[$i]['yearpr_assurance_pay'] = number_format($data[$i]['yearpr_assurance_pay'], 2);
			$data[$i]['yearpr_de_assurance'] = number_format($data[$i]['yearpr_de_assurance'], 2);
			$data[$i]['yearpr_de_uniform'] = number_format($data[$i]['yearpr_de_uniform'], 2);
			$data[$i]['yearpr_de_card'] = number_format($data[$i]['yearpr_de_card'], 2);
			$data[$i]['yearpr_de_cooperative'] = number_format($data[$i]['yearpr_de_cooperative'], 2);
			$data[$i]['yearpr_de_lond'] = number_format($data[$i]['yearpr_de_lond'], 2);
			$data[$i]['yearpr_de_borrow'] = number_format($data[$i]['yearpr_de_borrow'], 2);
			$data[$i]['yearpr_de_elond'] = number_format($data[$i]['yearpr_de_elond'], 2);
			$data[$i]['yearpr_de_mobile'] = number_format($data[$i]['yearpr_de_mobile'], 2);
			$data[$i]['yearpr_de_backtravel'] = number_format($data[$i]['yearpr_de_backtravel'], 2);
			$data[$i]['yearpr_de_backother'] = number_format($data[$i]['yearpr_de_backother'], 2);
			$data[$i]['yearpr_de_selfemp'] = number_format($data[$i]['yearpr_de_selfemp'], 2);
			$data[$i]['yearpr_de_health'] = number_format($data[$i]['yearpr_de_health'], 2);
			$data[$i]['yearpr_de_debtcase'] = number_format($data[$i]['yearpr_de_debtcase'], 2);
			$data[$i]['yearpr_de_pernicious'] = number_format($data[$i]['yearpr_de_pernicious'], 2);
			$data[$i]['yearpr_de_visa'] = number_format($data[$i]['yearpr_de_visa'], 2);
			$data[$i]['yearpr_de_work_p'] = number_format($data[$i]['yearpr_de_work_p'], 2);
			$data[$i]['yearpr_de_outother'] = number_format($data[$i]['yearpr_de_outother'], 2);
			$data[$i]['yearpr_declare_pay'] = number_format($data[$i]['yearpr_declare_pay'], 2);
			$data[$i]['yearpr_sevrance_pay'] = number_format($data[$i]['yearpr_sevrance_pay'], 2);
			$data[$i]['yearpr_calcmony_sso'] = number_format($data[$i]['yearpr_calcmony_sso'], 2);
			$data[$i]['yearpr_calcmony_fun'] = number_format($data[$i]['yearpr_calcmony_fun'], 2);
			$data[$i]['yearpr_calcmony_tax2'] = number_format($data[$i]['yearpr_calcmony_tax2'], 2);
			$data[$i]['yearpr_calcmony_tax3'] = number_format($data[$i]['yearpr_calcmony_tax3'], 2);
			$data[$i]['yearpr_calcmony_tax4'] = number_format($data[$i]['yearpr_calcmony_tax4'], 2);
			$data[$i]['yearpr_totalsalary'] = number_format($data[$i]['yearpr_totalsalary'], 2);
			$data[$i]['yearpr_totalsso'] = number_format($data[$i]['yearpr_totalsso'], 2);
			$data[$i]['yearpr_totalfun'] = number_format($data[$i]['yearpr_totalfun'], 2);
			$data[$i]['yearpr_totaltax'] = number_format($data[$i]['yearpr_totaltax'], 2);
			$data[$i]['yearpr_totalmony'] = number_format($data[$i]['yearpr_totalmony'], 2);
			$data[$i]['yearpr_totalno'] = number_format($data[$i]['yearpr_totalno'], 2);
			$data[$i]['summer_remain'] = number_format($data[$i]['summer_remain'], 2);
			$data[$i]['rfNameIdStartDate'] = $this->setDateView($data[$i]['rfNameIdStartDate']);
		}
		return $data;
	}

	public function export_yearbra_report()
	{
		$report = $this->session->userdata('_report');

		if ($report == 16) //Data ภงด. ประจำปี
		{
			$this->genreport_401Excel();
		} elseif ($report == 17)  //ลาสะสมประจำปี
		{
			$this->genreport_402Excel();
		} elseif ($report == 18) //50 ทวิA4 ประจำปี
		{
			$this->genreport_403Pdf();
		} elseif ($report == 19) //รายชื่อพนักงานยังไม่คืนเงินประกัน
		{
			$this->genreport_404Excel();
		} elseif ($report == 20) //รายชื่อพนักงานหักเงินประกันไม่ครบ
		{
			$this->genreport_405Excel();
		} else {
			return $report;
		}
	}

	public function genreport_405Excel()
	{
		// load excel library
		$this->load->library('reportuser/Excel');

		$results = $this->Yearbra->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);
		$this->data['data_list'] = $data_lists;


		$objectPHPExcel = new PHPExcel();
		$objectPHPExcel->setActiveSheetIndex(0);

		$objectPHPExcel->getActiveSheet()->SetCellValue('A1', 'No.');
		$objectPHPExcel->getActiveSheet()->SetCellValue('B1', 'Name_id');
		$objectPHPExcel->getActiveSheet()->SetCellValue('C1', 'วันที่ลาออก');
		$objectPHPExcel->getActiveSheet()->SetCellValue('D1', 'ชื่อ -  สกุล');
		$objectPHPExcel->getActiveSheet()->SetCellValue('E1', 'วันเริ่มงาน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('F1', 'ต้องหักประกัน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('G1', 'หักประกันใน PR.');
		$objectPHPExcel->getActiveSheet()->SetCellValue('H1', 'ส่วนต่างหักเพิ่ม');
		$objectPHPExcel->getActiveSheet()->SetCellValue('I1', 'คืนเงินประกัน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('J1', 'หักค่าชุด');
		$objectPHPExcel->getActiveSheet()->SetCellValue('K1', 'ชื่อสาขา');

		$objectPHPExcel->getActiveSheet()->getStyle("A1:K1")->getFont()->setBold(true);

		$rowCount = 2;
		$i_No = 0;
		foreach ($data_lists as $row) {
			$i_No++;

			$objectPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $i_No);
			$objectPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['rfNameID']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['rfNameIDEndDate']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['rfPernameIdPreName'] . $row['rfNameIdEmpName'] . " " . $row['rfNameIdEmpSurname']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['rfNameIdStartDate']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['yearpr_de_assurance']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['yearpr_de_assurance']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, '=SUM('. $row['yearpr_de_assurance'].'-'. $row['yearpr_de_assurance'].')');
			$objectPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row['yearpr_assurance_pay']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['yearpr_de_uniform']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['rfBranchIdBranchNick']);


			$rowCount++;
		}

		$sn = $rowCount + 2;

		//Total
		$objectPHPExcel->getActiveSheet()->setCellValue('E' . $sn, 'รวม');
		$objectPHPExcel->getActiveSheet()->setCellValue('F' . $sn, '=SUM(F2:F' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('G' . $sn, '=SUM(G2:G' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('H' . $sn, '=SUM(H2:H' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('I' . $sn, '=SUM(I2:I' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('J' . $sn, '=SUM(J2:J' . $rowCount . ')');



		foreach (range('A', 'K') as $columnID) {
			$objectPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		// Set the document header and center it by '&C'
		//$objectPHPExcel->getActiveSheet(0)->getHeaderFooter()->setOddHeader('&Cรายงานสะสมการลาของพนักงาน');
		//$objectPHPExcel->getActiveSheet(0)->getHeaderFooter()->setOddHeader("&Lบริษัท $rfCompanyIDCompanyName &R".date("Y-m-d-H-i-s"));



		$filename = "Genreport_405Excel" . date("Y-m-d-H-i-s") . "";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$object_writer = PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');


		$object_writer->save('php://output');
	}

	public function genreport_404Excel()
	{
		// load excel library
		$this->load->library('reportuser/Excel');

		$results = $this->Yearbra->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);
		$this->data['data_list'] = $data_lists;


		$objectPHPExcel = new PHPExcel();
		$objectPHPExcel->setActiveSheetIndex(0);
		foreach ($data_lists as $row) {
			$rfCompanyIDCompanyName = $row['rfCompanyIDCompanyName'];
		}

		// Set the document header and center it by '&C'
		//$objectPHPExcel->getActiveSheet(0)->getHeaderFooter()->setOddHeader('&Cรายงานสะสมการลาของพนักงาน');
		//$objectPHPExcel->getActiveSheet(0)->getHeaderFooter()->setOddHeader("&Lบริษัท $rfCompanyIDCompanyName &R".date("Y-m-d-H-i-s"));
		$objectPHPExcel->getActiveSheet()->SetCellValue('A1', 'No.');
		$objectPHPExcel->getActiveSheet()->SetCellValue('B1', 'Name_id');
		$objectPHPExcel->getActiveSheet()->SetCellValue('C1', 'รหัส');
		$objectPHPExcel->getActiveSheet()->SetCellValue('D1', 'ชื่อ -  สกุล');
		$objectPHPExcel->getActiveSheet()->SetCellValue('E1', 'วันเริ่มงาน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('F1', 'วันที่ออก');
		$objectPHPExcel->getActiveSheet()->SetCellValue('G1', 'อายุงาน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('H1', 'หักประกันใน PR.');
		$objectPHPExcel->getActiveSheet()->SetCellValue('I1', 'คืนเงินประกัน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('J1', 'คงเหลือ');
		$objectPHPExcel->getActiveSheet()->SetCellValue('K1', 'ชื่อสาขา');

		$objectPHPExcel->getActiveSheet()->getStyle("A1:K1")->getFont()->setBold(true);

		//set row
		$rowCount = 2;
		$i_No = 0;
		foreach ($data_lists as $row) {
			$i_No++;

			$objectPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $i_No);
			$objectPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['rfNameID']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['rfNameIDEmpBarcode']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['rfPernameIdPreName'] . $row['rfNameIdEmpName'] . " " . $row['rfNameIdEmpSurname']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['rfNameIdStartDate']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['rfNameIDEndDate']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['rfNameIDAgebStart']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row['yearpr_de_assurance']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row['yearpr_assurance_pay']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, '=SUM(H' . $rowCount . '-I' . $rowCount . ')');
			$objectPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['rfBranchIdBranchNick']);

			$rowCount++;
		}


		foreach (range('A', 'K') as $columnID) {
			$objectPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}


		$filename = "Genreport_404Excel" . date("Y-m-d-H-i-s") . "";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$object_writer = PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');


		$object_writer->save('php://output');
	}
	
	public function genreport_401Excel()
	{
		// load excel library
		$this->load->library('reportuser/Excel');

		$results = $this->Yearbra->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);
		$this->data['data_list'] = $data_lists;


		$objectPHPExcel = new PHPExcel();
		$objectPHPExcel->setActiveSheetIndex(0);

		$objectPHPExcel->getActiveSheet()->SetCellValue('A1', 'ชื่อสาขา');
		$objectPHPExcel->getActiveSheet()->SetCellValue('B1', 'เลขประจำตัวผู้เสียภาษี');
		$objectPHPExcel->getActiveSheet()->SetCellValue('C1', 'ชื่อ-นามสกุล');
		$objectPHPExcel->getActiveSheet()->SetCellValue('D1', 'ยอดรายได้');
		$objectPHPExcel->getActiveSheet()->SetCellValue('E1', 'ยอดเงินภาษี');
		$objectPHPExcel->getActiveSheet()->SetCellValue('F1', 'ยอดเงินหักประกันสังคม');
		$objectPHPExcel->getActiveSheet()->SetCellValue('G1', 'ยอดเงินหักกองทุน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('H1', 'ยอดเงินรวมลดหย่อน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('I1', 'Company');

		$objectPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);

		$rowCount = 2;
		$i_No = 0;
		foreach ($data_lists as $row) {
			$i_No++;

			$objectPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['rfBranchIdBranchNick']);
			$objectPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $rowCount, $row['rfPersonIDPersonMumsso'] , PHPExcel_Cell_DataType::TYPE_STRING);
			$objectPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['rfPernameIdPreName'] . $row['rfNameIdEmpName'] . " " . $row['rfNameIdEmpSurname']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['totalMonyPersonYear']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['totalTaxPersonYear']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['totalSsoPersonYear']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['totalFunPersonYear']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row['rfPersonIDPersonTotal']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row['rfCompanyIDCompanyName']);
			


			$rowCount++;
		}

		foreach (range('A', 'I') as $columnID) {
			$objectPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		$filename = "Genreport_401Excel" . date("Y-m-d-H-i-s") . "";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$object_writer = PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');


		$object_writer->save('php://output');

	}

	public function genreport_402Excel()
	{
		// load excel library
		$this->load->library('reportuser/Excel');

		$results = $this->Yearbra->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);
		$this->data['data_list'] = $data_lists;


		$objectPHPExcel = new PHPExcel();
		$objectPHPExcel->setActiveSheetIndex(0);

		// Set the document header and center it by '&C'
		//$objectPHPExcel->getActiveSheet(0)->getHeaderFooter()->setOddHeader('&Cรายงานสะสมการลาของพนักงาน');
		//$objectPHPExcel->getActiveSheet(0)->getHeaderFooter()->setOddHeader("&Lบริษัท $rfCompanyIDCompanyName &R".date("Y-m-d-H-i-s"));

		$objectPHPExcel->getActiveSheet()->SetCellValue('A1', 'No.');
		$objectPHPExcel->getActiveSheet()->SetCellValue('B1', 'Name_id');
		$objectPHPExcel->getActiveSheet()->SetCellValue('C1', 'รหัส');
		$objectPHPExcel->getActiveSheet()->SetCellValue('D1', 'ชื่อ -  สกุล');
		$objectPHPExcel->getActiveSheet()->SetCellValue('E1', 'วันเริ่มงาน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('F1', 'วันที่ออก');
		$objectPHPExcel->getActiveSheet()->SetCellValue('G1', 'พักร้อน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('H1', 'ป่วย');
		$objectPHPExcel->getActiveSheet()->SetCellValue('I1', 'ปง100');
		$objectPHPExcel->getActiveSheet()->SetCellValue('J1', 'ปง30');
		$objectPHPExcel->getActiveSheet()->SetCellValue('K1', 'คลอดได้รับค่าจ้าง');
		$objectPHPExcel->getActiveSheet()->SetCellValue('L1', 'กิจได้รับค่าจ้าง');
		$objectPHPExcel->getActiveSheet()->SetCellValue('M1', 'กิจไม่ได้รับค่าจ้าง');
		$objectPHPExcel->getActiveSheet()->SetCellValue('N1', 'ป่วยเกิน 30 วัน');
		$objectPHPExcel->getActiveSheet()->SetCellValue('O1', 'คลอด');
		$objectPHPExcel->getActiveSheet()->SetCellValue('P1', 'ลาอื่นๆ');
		$objectPHPExcel->getActiveSheet()->SetCellValue('Q1', 'ขาด');


		$objectPHPExcel->getActiveSheet()->getStyle("A1:Q1")->getFont()->setBold(true);

		//set row
		$rowCount = 2;
		$i_No = 0;
		foreach ($data_lists as $row) {
			$i_No++;

			$objectPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $i_No);
			$objectPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['rfNameID']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['rfNameIDEmpBarcode']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['rfPernameIdPreName'] . $row['rfNameIdEmpName'] . " " . $row['rfNameIdEmpSurname']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['rfNameIdStartDate']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['rfNameIDEndDate']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['yearpr_aleave_num']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row['yearpr_sleave_num']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row['yearpr_adleave_pay']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['yearpr_ahleave_pay']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['yearpr_mleave_pay']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row['yearpr_bleave_num']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $row['yearpr_bnleave_num']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $row['summer_remain']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $row['yearpr_mleave_num']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $row['yearpr_oleave_num']);
			$objectPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $row['yearpr_absent_num']);

			$rowCount++;
		}

		$sn = $rowCount + 2;

		//Total
		$objectPHPExcel->getActiveSheet()->setCellValue('D' . $sn, 'รวม');
		$objectPHPExcel->getActiveSheet()->setCellValue('G' . $sn, '=SUM(G2:G' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('H' . $sn, '=SUM(H2:H' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('I' . $sn, '=SUM(I2:I' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('J' . $sn, '=SUM(J2:J' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('K' . $sn, '=SUM(K2:K' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('L' . $sn, '=SUM(L2:L' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('M' . $sn, '=SUM(M2:M' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('N' . $sn, '=SUM(N2:N' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('O' . $sn, '=SUM(O2:O' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('P' . $sn, '=SUM(P2:P' . $rowCount . ')');
		$objectPHPExcel->getActiveSheet()->setCellValue('Q' . $sn, '=SUM(Q2:Q' . $rowCount . ')');


		foreach (range('A', 'Q') as $columnID) {
			$objectPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		// Set the document header and center it by '&C'
		//$objectPHPExcel->getActiveSheet(0)->getHeaderFooter()->setOddHeader('&Cรายงานสะสมการลาของพนักงาน');
		//$objectPHPExcel->getActiveSheet(0)->getHeaderFooter()->setOddHeader("&Lบริษัท $rfCompanyIDCompanyName &R".date("Y-m-d-H-i-s"));



		$filename = "Genreport_402Excel" . date("Y-m-d-H-i-s") . "";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$object_writer = PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');


		$object_writer->save('php://output');
	}



	public function genreport_403Pdf()
	{
		//     load library
		$this->load->library('Pdf');

		$results = $this->Yearbra->read();
		$data_lists = $this->setDataListFormat($results['list_data'], 0);
		$this->data['data_list'] = $data_lists;

		$pdf = new FPDI('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		$font = 'thsarabun';
		$pdf->font = $font;

		$pdf->SetCreator("");
		$pdf->SetAuthor("");
		$pdf->SetTitle("Genreport_403Pdf");
		$pdf->SetSubject("Genreport_403Pdf");

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		$pdf->SetMargins(
			3,
			4,
			3
		);
		$pdf->SetHeaderMargin(0);
		$pdf->SetTopMargin(4);
		$pdf->SetFooterMargin(20);
		$pdf->SetAutoPageBreak('on', 70);
		$pdf->SetFont($font, '', 16);

		// Add a page
		$pdf->AddPage("P");

		$this->data['data_list'] = $data_lists;
		$data = $this->data;

		$html = $this->parser->parse_repeat('reportuser/yearbra/preview_view_pdf', $data, true);
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		$pdf->lastPage();

		$pdf->Output("Genreport_403Pdf.pdf", 'I');
	}
}
/*---------------------------- END Controller Class --------------------------------*/
