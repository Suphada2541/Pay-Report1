<!-- [ View File name : list_view.php ] -->
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header w3-theme-d5">
				<h3 class="card-title text-white"><i class="fa fa-list-alt"></i><b> รายประจำเดือน : แยกตามสาขา</b></h3>
			</div>
			<div class="card-body">
				<form class="forms-sample" name="formSearch" method="post" action="{page_url}/search">
					{csrf_protection_field}
					<div class="row">
						<div class="col-sm-6">

							<div class="form-group">
								<input type="hidden" class="form-control" value="rf_branch_id" name="search_field" />

							</div>
							<div class="form-group">
								<label class='control-label' for='rf_branch_id'>
									<h6>ระบุสาขาที่ต้องการค้นหา : </h6>
								</label> <br>
								<select id="rf_branch_id" name="txtSearch" value="{txt_search}">
									<option value="">- เลือก สาขา -</option>
									{tb_branch_rf_branch_id_option_list}
								</select>
							</div>

							<div class="row">
								<div class="form-group">
									<label class='control-label' for='monthpay'>
										<h6> &nbsp; &nbsp; ระบุเดือน-ปี : </h6>
									</label> <br>
									&nbsp; &nbsp; &nbsp;<select id='monthpay' name="txtpnum" value="{txt_pnum}">
										<option value="">- เลือก เดือน -</option>
										{tb_paymonth_monthpay_option_list}
									</select>
								</div>

								<div class="form-group">
									<label class='control-label' for='txtYear'>
										<h6> &nbsp; </h6>
									</label> <br>
									<input type="text" class="form-control col-sm-12" id="txtYear" name="txtYear" value="{txt_year}" placeholder="ระบุ ปี พ.ศ.">
								</div>
							</div>
							<input type="hidden" value="{order_by}" name="order_by" />

							<button type="submit" name="submit" class="btn btn-info">
								<span class="glyphicon glyphicon-search"><i class="fas fa-search"></i>Search</span>
							</button>
							<a href="{page_url}" title="รีเฟสหน้าจอ"><button type="button" class="btn btn-primary"><i class="fas fa-sync-alt"> </i>Clear</button></a>

							<input type="hidden" id="search_datarow" value="{search_datarow}" name="search_datarow" class="form-control" />
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<input type="hidden" class="form-control" value="rf_branch_id" name="search_field" />
							</div>

							<br><br><br><br>
							<div class="form-group">
								<div class="col-sm-12">
									<input type="hidden" class="form-control" id="report3id" name="reportid" value="">
								</div>
							</div>
							<div class="form-group">
								<label class='control-label' for='rf_report_id'>
									<h6>ระบุชื่อรายงาน : </h6>
								</label> <br>
								<select id="rf_report3_id" name="rf_report3_id">
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
								<th width="20px;">#</th>
								<th>ปี</th>
								<th>เดือน</th>
								<th>ชื่อพนักงาน</th>
								<th>ยอดเงินคิดสปส.</th>
								<th>เงินสปส.ส่วนพนง</th>
								<th>เงินสปส.ส่วนนายจ้าง</th>
							</tr>
						</thead>
						<tbody>
							<tr parser-repeat="[data_list]" id="row_{record_number}">
								<td style="text-align:center;">[{record_number}]</td>
								<td>{yearpay}</td>
								<td>{monthpayPaymonthId} - {monthpayPaymonth}</td>
								<td>{rfNameIdEmpName} {rfNameIdEmpSurname}</td>
								<td>{month_mony_sso}</td>
								<td>{month_de_ssop}</td>
								<td>{month_de_ssoc}</td>
							</tr>
						</tbody>
					</table>

				</div>
			</div>


		</div>
	</div>
</div>


<script>
	var param_search_field = '{search_field}';
	var param_current_page = '{current_page_offset}';
	var param_current_path = '{current_path_uri}';
</script>