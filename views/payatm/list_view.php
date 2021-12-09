<!-- [ View File name : list_view.php ] -->
<div class="row">
	<div class="col-md-12">
        <div class="card">
        <div class="card-header w3-theme-d5">
        <h3 class="card-title text-white"><i class="fa fa-list-alt"></i><b> ข้อมูลการเข้าATM : ประจำบริษัท</b></h3>
        </div>
    	<div class="card-body">
		<form class="forms-sample" name="formSearch" method="post" action="{page_url}/search">
		{csrf_protection_field}        	

		<div class="row">
			<div class="col-sm-6">	
				<div class="form-group">
				<input type="hidden" class="form-control" value="rf_company" name="search_field"/>
				</div>
				<div class="form-group">
					<label class='control-label' for='rf_company'> <h6>ระบุบริษัทที่ต้องการค้นหา : </h6></label> <br>
					<select id="rf_company" name="txtSearch" value="{txt_search}">
					<option value="">- เลือก บริษัท -</option>
						{tb_comppany_rf_company_id_option_list}
					</select>
				</div>
			
				<div class="form-group">
					<label class='control-label' for='rf_bank_id'><h6> ระบุธนาคาร : </h6></label> <br>         
						<select id='rf_bank_id' name="txtBank" value="{txt_bank}" >
							<option value="">- เลือก ธนาคาร - </option>
							{tb_bank_rf_bank_id_option_list}
						</select>
				</div>
           		<input type="hidden" value="{order_by}" name="order_by"/>
		
				<button type="submit" name="submit" class="btn btn-info">
						<span class="glyphicon glyphicon-search"><i class="fas fa-search"></i>Search</span> 
				</button>	
				<a href="{page_url}" title="รีเฟสหน้าจอ"><button type="button" class="btn btn-primary"><i class="fas fa-sync-alt"> </i>Clear</button></a>

				<input type="hidden" id="search_datarow" value="{search_datarow}" name="search_datarow" class="form-control" />
			</div>

			<div class="col-sm-6">	
				<br>
				<div class="form-group">
 					<label class='control-label' for='rfPayIdPayAppdate'> <h6>ระบุวันที่จ่าย : </h6></label>  <br>	 
					 <input type="text" class="form-control col-sm-8  datepicker" id="pay_appdate" placeholder=" เลือกวันที่จ่าย " name="txtAppdate" value="{txt_appdate}">
				</div>

				
 				<div class="form-group">
 					<label class='control-label' for='rf_report_id'> <h6>ระบุชื่อรายงาน : </h6></label>  <br>	 
					<select id="rf_report_id" name="rf_report_id" value="{txt_report}">
					<option value="">- เลือก รายงาน -</option>
						{tb_report_report_id_option_list}
					</select>
				</div>
		
				<div class="form-group">
				
					<a class="btn btn-success btn-lg" id="btnReport" href="" title="ส่งออกข้อมูล">
						<i class="fas fa-download"></i></span> Eport Report
					</a>
				</div>
			</div>
			
       	</div>
		
		   			<!-- <div class="row">
				<div class="col-sm-8">	
					<div class="form-group">
					<input type="hidden" class="form-control" value="rf_company" name="search_field"/>
			
 					</div>
 					<div class="form-group">
					<label class='col-sm-4 control-label' for='rf_company'> <h6>ระบุบริษัทที่ต้องการค้นหา : </h6></label>  
					<select id="rf_company" name="txtSearch" value="{txt_search}">
					<option value="">- เลือก บริษัท -</option>
						{tb_comppany_rf_company_id_option_list}
					</select>
					</div>
				</div>
				<div class="col-sm-4">
					<br>
					<button type="submit" name="submit" class="btn btn-info">
						<span class="glyphicon glyphicon-search"><i class="fas fa-search"></i>Search</span> 
					</button>	
					<a href="{page_url}" title="รีเฟสหน้าจอ"><button type="button" class="btn btn-primary"><i class="fas fa-sync-alt"> </i>Clear</button></a>
		
				</div>
			</div>
			<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
					<label class='col-sm-4 control-label' for='rf_bank_id'><h6> ระบุธนาคาร : </h6></label>                
						<select id='rf_bank_id' name="txtBank" value="{txt_bank}" >
							<option value="">- เลือก ธนาคาร - </option>
							{tb_bank_rf_bank_id_option_list}
						</select>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="dropdown">
					<button class="btn btn-danger btn-lg dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-file-pdf"> </i>PDF Report</button>
			
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="{page_url}/print_pdf_atm" target="_blank" >รายงาน ATM</a>			
					</div>

				</div>
			</div>
			</div>
			<div class="row">
			<div class="col-sm-8">
				<div class="input-group input-group-button">
					<div class="form-group col-sm-4">
					<label class='control-label' for='rf_bank_id'><h6> ระบุวันที่จ่าย : </h6></label>                
					</div>
					<div class="form-group">
					<input type="text" class="form-control col-sm-12  datepicker" id="txtAppdate" placeholder=" เลือกวันที่จ่าย " name="txtAppdate" value="{txt_appdate}">
					</div>
				</div>
              
					<input type="hidden" value="{order_by}" name="order_by"/>
			</div>	
			<div class="col-sm-4">
				<div class="text-left">
					<a href="{page_url}/export_excel" class="btn btn-success btn-lg" data-toggle="tooltip" title="ส่งออกข้อมูล">
					<i class="fas fa-file-excel"></i></span> Excel
					</a>
				</div>		
			</div>	
			</div> -->

      </form>
    
<br>
		<div class="row">
			<div class="col-sm-8">
			 <h4><i class="fa fa-list-alt"></i><b> List Data Payroll</b></h4>
			</div>
			<div class="col-sm-4 text-left">
			<h4> ทั้งหมด <span class="badge badge-primary"> {search_row}</span> รายการ</b></h4>
				
			</div>
		</div>
		

	<hr/>	
        <style type="text/css">
        input[type=search] {width: 350px !important;}
        </style>

	<div class="dt-responsive">

			<table id="simpletable" class="table table-striped table-bordered nowrap">
				<thead class="info">
					<tr bgcolor="#dddddd">
						<th>#</th>	
						
						<th>รหัสพนักงาน</th>				
						<th>ชื่อพนักงาน</th>
						<th>สาขา</th>
						<th>วันที่จ่าย</th>
						<th>ธนาคาร</th>	
						<th>เลขที่บัญชี</th>				
						<th>รวมรับ</th>				
								
					</tr>
				</thead>
				<tbody>
					<tr parser-repeat="[data_list]" id="row_{record_number}">
						<td style="text-align:center;">[{record_number}]</td>					
						<td>{rf_name_id}</td>
						<td>{rfPernameIdPreName}{rfNameIdEmpName} {rfNameIdEmpSurname}</td>
						<td>{rfBranchIdBranchNick}</td>
						<td>{rfPayIdPayAppdate}</td>
						<td>{rfBankIdBankName}</td>	
						<td>{rfNameIdBankAccount}</td>					
						<td>{paypr_salary_net}</td>
										
					</tr>
				</tbody>
			</table>

		</div>
	</div>

</div>
</div>
</div>	
 

<!-- Modal Delete -->
<div class="modal fade" id="confirmDelModal" tabindex="-1" role="dialog" aria-labelledby="confirmDelModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="confirmDelModalLabel">ยืนยันการลบข้อมูล</h4>
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			</div>
			<div class="modal-body">
				<h4 class="text-center">***  ท่านต้องการลบข้อมูลแถวที่ <span id="xrow"></span> ???  ***</h4>
				<div id="div_del_detail"></div>
				<form id="formDelete">
					<div class="form-group">
						<div class="col-sm-8">
							<label class="col-sm-3 text-right badge badge-warning" for="edit_remark">ระบุเหตุผล :</label>
						</div>
					<div class="col-sm-12">
						<input type="text" class="form-control" name="delete_remark">
					</div>
				</div>
					<input type="hidden" name="encrypt_company_id" />

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger" id="btn_confirm_delete" >Delete</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalPreview" tabindex="-1" role="dialog" aria-labelledby="modalPreviewLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
<!-- 			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="modalPreviewLabel">แสดงข้อมูล</h4>
			</div> -->
			<div class="modal-body">
				<div id="divPreview"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
			</div>
		</div>
	</div>
</div>
<script>
	var param_search_field = '{search_field}';
	var param_current_page = '{current_page_offset}';
	var param_current_path = '{current_path_uri}';
</script>
