<!--  [ View File name : edit_view.php ] -->
	<div class="card">
		<div class="card-header bg-primary">
			<h3 class="card-title"><i class="fa fa-edit"></i> แก้ไขข้อมูล <strong>tb_summonth</strong></h3>
		</div>
		<div class="card-body">
			<form class='form-horizontal' id='formEdit' accept-charset='utf-8'>
				{csrf_protection_field}
				<input type="hidden" name="submit_case" value="edit" />
				<div class='form-group'>
					<label class='col-sm-2 control-label' for='monthpr_id'>รหัส  :</label>
					<div class='col-sm-10'>

						<input type="text" class="form-control " id="monthpr_id" name="monthpr_id" value="{record_monthpr_id}" readonly="readonly" />
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label' for='yearpay'>ปี  :</label>
					<div class='col-sm-10'>

						<input type="text" class="form-control " id="yearpay" name="yearpay" value="{record_yearpay}"  />
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label' for='monthpay'>เดือน  :</label>
					<div class='col-sm-10'>
					<select id='monthpay'  name='monthpay' value="{record_monthpay}" >
						<option value="">- เลือก เดือน -</option>
						{tb_paymonth_monthpay_option_list}
					</select>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label' for='rf_name_id'>ชื่อพนักงาน  :</label>
					<div class='col-sm-10'>
					<select id='rf_name_id'  name='rf_name_id' value="{record_rf_name_id}" >
						<option value="">- เลือก ชื่อพนักงาน -</option>
						{tb_person_rf_name_id_option_list}
					</select>
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label' for='month_mony_sso'>ยอดเงินคิดประกันสังคมประจำเดือน  :</label>
					<div class='col-sm-10'>

						<input type="text" class="form-control " id="month_mony_sso" name="month_mony_sso" value="{record_month_mony_sso}"  />
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label' for='month_de_ssop'>ยอดเงินหักประกันสังคมประจำเดือนส่วนพนง  :</label>
					<div class='col-sm-10'>

						<input type="text" class="form-control " id="month_de_ssop" name="month_de_ssop" value="{record_month_de_ssop}"  />
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label' for='month_de_ssoc'>ยอดเงินหักประกันสังคมประจำเดือนส่วนนายจ้าง  :</label>
					<div class='col-sm-10'>

						<input type="text" class="form-control " id="month_de_ssoc" name="month_de_ssoc" value="{record_month_de_ssoc}"  />
					</div>
				</div>
				<div class='form-group'>
					<label class='col-sm-2 control-label' for='month_ckrun'>สถานะปิดเดือน  :</label>
					<div class='col-sm-10'>

						<input type="text" class="form-control " id="month_ckrun" name="month_ckrun" value="{record_month_ckrun}"  />
					</div>
				</div>
				<div class='form-group'>
					<div class='col-sm-offset-2 col-sm-10'>
						<button  type="button" class='btn btn-primary btn-lg'  data-toggle='modal' data-target='#editModal' >&nbsp;&nbsp;<i class="fa fa-save"></i> บันทึก &nbsp;&nbsp;</button>

						</div>
				</div>

				<input type="hidden" name="encrypt_monthpr_id" value="{encrypt_monthpr_id}" />


			</form>
		</div> <!--card-body-->
	</div> <!--card-->

<!-- Modal -->
<div class='modal fade' id='editModal' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>
	<div class='modal-dialog' role='document'>
		<div class='modal-content'>
			<div class='modal-header bg-warning'>
				<h4 class='modal-title' id='editModalLabel'>บันทึกข้อมูล</h4>
				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
			</div>
			<div class='modal-body'>
				<h4>ยืนยันการเปลี่ยนแปลงแก้ไขข้อมูล ?</h4>
				<form class="form-horizontal" onsubmit="return false;" >
					<div class="form-group">
						<div class="col-sm-8">
							<label class="col-sm-3 text-right badge badge-warning" for="edit_remark">ระบุเหตุผล :</label>
						</div>
						<div class="col-sm-12">
							<input type="text" class="form-control" id="edit_remark">
						</div>
					</div>
				</form>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-lg btn-default' data-dismiss='modal'><i class="fas fa-window-close"></i> ปิด</button>
				<button type='button' class='btn btn-lg btn-primary' id='btnSaveEdit'>&nbsp;<i class="fa fa-save"></i> บันทึก&nbsp;</button>
			</div>
		</div>
	</div>
</div>
