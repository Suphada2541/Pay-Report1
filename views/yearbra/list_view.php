<!-- [ View File name : list_view.php ] -->

<div class="row">
	<div class="col-md-12">
	<div class="card">
			<div class="card-header w3-theme-d5">
				<h3 class="card-title text-white"><i class="fa fa-list-alt"></i><b> รายงานประจำปี : แยกตามสาขา</b></h3>
			</div>

	<div class="card-body">
	<form class="forms-sample" name="formSearch" method="post" action="{page_url}/search">
			{csrf_protection_field}
		<div class="col-md-12">	
			<div class="row">
				<div class="col-sm-7">
					<div class="row">	
						<div class="form-group">
							<label class='control-label' for='rf_branch_id'>
								<h6>ระบุสาขาที่ต้องการแสดงผล : </h6>
								</label> <br>
								<select id="rf_branch_id" name="txtSearch" value="{txt_search}">
									<option value="">- เลือก สาขา -</option>
									{tb_branch_rf_branch_id_option_list}
								</select>
						</div>
						<div class="form-group">
							<label class='control-label' for='txtYear'><h6> &nbsp; &nbsp;</h6></label> 
							<input type="text" class="form-control col-sm-8" id="txtYear" name="txtYear" value="{txt_year}" placeholder="ระบุ ปี พ.ศ.">
						</div>
					
					</div>

					<div class="row">
							<div class="form-group">
								<input type="hidden" class="form-control" value="rf_branch_id" name="search_field" />
								<button type="submit" name="submit" class="btn btn-info">
								<span class="glyphicon glyphicon-search"><i class="fas fa-search"></i>Search</span>
								</button>
								<a href="{page_url}" title="รีเฟสหน้าจอ"><button type="button" class="btn btn-primary"><i class="fas fa-sync-alt"> </i>Clear</button>
								</a>
							</div>
					</div>
				</div>

				<div class="col-sm-5">
					<div class="row">
						<div class="form-group">
							<label class='control-label' for='rf_report4_id'>
									<h6>ระบุชื่อรายงาน : </h6>
							</label> <br>
							<select id="rf_report4_id" name="rf_report4_id" value="{txt_report}">
									<option value="">- เลือก รายงาน -</option>
									{tb_report_report_id_option_list}
							</select>
						</div>
					</div>
					<input type="hidden" class="form-control" id="report4id" name="reportid" value="">
					<div class="row">
							<div class="form-group">
									<a class="btn btn-success btn-lg" id="btnReport" href="" title="ส่งออกข้อมูล">
									<i class="fas fa-download"></i></span> Export Report
								</a>
							</div>
					</div>
				</div>

			</div>

		</div>		
	</form>

		<br>
				<div class="row">
					<div class="col-sm-9">
						<h4><i class="fa fa-list-alt"></i><b> List Data Payroll</b></h4>
					</div>
					<div class="col-sm-3 text-left">
						<h4> ทั้งหมด <span class="badge badge-primary"> {search_row}</span> รายการ</b></h4>

					</div>
				</div>

		<hr />

		<style type="text/css">
			input[type=search] {
			width: 350px !important;
			}
		</style>

				<div class="dt-responsive">

					<table id="simpletable" class="table table-striped table-bordered nowrap">
						<thead class="info">
							<tr bgcolor="#dddddd">
								<th>ชื่อสาขา</th>
								<th>เลขผู้เสียภาษี</th>
								<th>ชื่อพนักงาน</th>
								<th>ยอดรายได้</th>
								<th>ยอดภาษี</th>
								<th>หักประกันสังคม</th>
								<th>หักกองทุน</th>
								<th>รวมลดหย่อน</th>
								
							</tr>
						</thead>
						<tbody>
							<tr parser-repeat="[data_list]" id="row_{record_number}">
								<td>{rfBranchIdBranchNick}</td>
								<td>{rfPersonIDPersonMumsso}</td>
								<td>{rfNameIdEmpName} {rfNameIdEmpSurname}</td>
								<td>{yearpr_totalmony}</td>
								<td>{yearpr_totaltax}</td>
								<td>{yearpr_totalsso}</td>
								<td>{totalFunPersonYear}</td>
								<td>{rfPersonIDPersonTotal}</td>
								
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
				<h4 class="text-center">*** ท่านต้องการลบข้อมูลแถวที่ <span id="xrow"></span> ??? ***</h4>
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
				<button type="button" class="btn btn-danger" id="btn_confirm_delete">Delete</button>
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