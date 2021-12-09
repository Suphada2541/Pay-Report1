<!-- [ View File name : preview_view.php ] -->

<style>
.table th.fit,
.table td.fit {
	white-space: nowrap;
	width: 2%;
}
</style>
<div class="card">

	<div class="card-header bg-primary">
		<h3 class="card-title"><i class="fa fa-clipboard"></i> รายละเอียด <b>Summonth</b></h3>
	</div>
	
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover">
				<thead class="well">
					<tr>
						<th class="text-right fit">หัวข้อ</th>
						<th>ข้อมูล</th>
					</tr>
				</thead>
				<tbody>

					<tr>
						<td class="text-right fit"><b>รหัส :</b></td>
						<td>{record_monthpr_id}</td>
					</tr>
					<tr>
						<td class="text-right fit"><b>ปี :</b></td>
						<td>{record_yearpay}</td>
					</tr>
				<tr>
					<td class="text-right fit"><b>เดือน :</b></td>
					<td>{monthpayPaymonthId} {monthpayPaymonth}</td>
				</tr>
				<tr>
					<td class="text-right fit"><b>ชื่อพนักงาน :</b></td>
					<td>{rfNameIdEmpName} {rfNameIdEmpSurname}</td>
				</tr>
					<tr>
						<td class="text-right fit"><b>ยอดเงินคิดประกันสังคมประจำเดือน :</b></td>
						<td>{record_month_mony_sso}</td>
					</tr>
					<tr>
						<td class="text-right fit"><b>ยอดเงินหักประกันสังคมประจำเดือนส่วนพนง :</b></td>
						<td>{record_month_de_ssop}</td>
					</tr>
					<tr>
						<td class="text-right fit"><b>ยอดเงินหักประกันสังคมประจำเดือนส่วนนายจ้าง :</b></td>
						<td>{record_month_de_ssoc}</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-12 col-md-12">
		<div class="pull-right text-right">
			<a href="{page_url}/preview_print_pdf/{recode_url_encrypt_id}" target="_blank" class="btn btn-danger btn-lg" data-toggle="tooltip" title="พิมพ์ข้อมูล">
				<i class="fas fa-file-pdf"></i></span> PDF
			</a>
			<a href="{page_url}/preview_export_excel/{recode_url_encrypt_id}" class="btn btn-success btn-lg" data-toggle="tooltip" title="ส่งออกข้อมูล">
				<i class="fas fa-file-excel"></i></span> Excel
			</a>
		</div>
	</div>
<hr/>
</div>