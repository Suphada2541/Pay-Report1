<!-- [ View File name : add_view.php ] -->
	<div class="card">
		<div class="card-header bg-primary">
			<h3 class="card-title"><i class="fa fa-plus-square"></i> เพิ่มข้อมูล <strong>Summonth</strong></h3>
		</div>
		<div class="card-body">
			<form class="form-horizontal" id="formAdd" accept-charset="utf-8">
				{csrf_protection_field}
				<div class="form-group">
					<label class="col-sm-2 control-label" for="yearpay">ปี  :</label>
					<div class="col-sm-10">

						<input type="text" class="form-control " id="yearpay" name="yearpay" value=""  />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="monthpay">เดือน  :</label>
					<div class="col-sm-10">
					<select  id="monthpay" name="monthpay" value="">
						<option value="">- เลือก เดือน -</option>
						{tb_paymonth_monthpay_option_list}
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="rf_name_id">ชื่อพนักงาน  :</label>
					<div class="col-sm-10">
					<select  id="rf_name_id" name="rf_name_id" value="">
						<option value="">- เลือก ชื่อพนักงาน -</option>
						{tb_person_rf_name_id_option_list}
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="month_mony_sso">ยอดเงินคิดประกันสังคมประจำเดือน  :</label>
					<div class="col-sm-10">

						<input type="text" class="form-control " id="month_mony_sso" name="month_mony_sso" value=""  />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="month_de_ssop">ยอดเงินหักประกันสังคมประจำเดือนส่วนพนง  :</label>
					<div class="col-sm-10">

						<input type="text" class="form-control " id="month_de_ssop" name="month_de_ssop" value=""  />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="month_de_ssoc">ยอดเงินหักประกันสังคมประจำเดือนส่วนนายจ้าง  :</label>
					<div class="col-sm-10">

						<input type="text" class="form-control " id="month_de_ssoc" name="month_de_ssoc" value=""  />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="hidden" id="add_encrypt_id" />
						<button type="button" id="btnConfirmSave"
							class="btn btn-primary btn-lg" data-toggle="modal"
							data-target="#addModal" >
							&nbsp;&nbsp;<i class="fa fa-save"></i> บันทึก &nbsp;&nbsp;
						</button>
					</div>
				</div>

			</form>
		</div> <!--panel-body-->
	</div> <!--panel-->
</div> <!--contrainer-->

<!-- Modal Confirm Save -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<h4 class="modal-title" id="addModalLabel">บันทึกข้อมูล</h4>
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<p class="alert alert-warning">ยืนยันการบันทึกข้อมูล ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-window-close"></i> ปิด</button>
				<button type="button" class="btn btn-primary" id="btnSave"><i class="fa fa-save"></i> บันทึก&nbsp;</button>
			</div>
		</div>
	</div>
</div>
