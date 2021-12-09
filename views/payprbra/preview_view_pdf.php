<!-- [ View File name : preview_view.php ] -->

<style>
	body {
		font-family: 'TH SarabunPSK';
		font-size : 16pt;
		margin : 0px;
	}
	table{
		width : 100%;
		border-collapse: collapse;
	}
	table { page-break-inside:auto; }
	
	th {
	   background-color:lightgrey;
	   text-align : center;
	}
</style>

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
