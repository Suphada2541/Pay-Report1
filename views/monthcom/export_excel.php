
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

			<table border="1">
				<thead class="well">
				<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th colspan="3">ยอดเงินคิดประกันสังคมประจำ</th>
					</tr>
					<tr>
						<th>ลำดับ</th>
						<th>รหัส</th>
						<th>ชื่อ-สกุล</th>
						<th>วันที่เริ่มงาน</th>
						<th>เดือน</th>
						<th>เดือนส่วนพนง</th>
						<th>เดือนส่วนนายจ้าง</th>
					</tr>
				</thead>
				<tbody>
				<div parser-repeat="[data_list]">

					<tr >
						<td class="text-center" >{record_number}</td>
						<td class="text-center" >{rf_name_id}</td>
						<td>{rfPernameIdPreName}{rfNameIdEmpName}  {rfNameIdEmpSurname}</td>
						<td class="text-center" >{rfNameIdStartDate}</td>
						<td>{month_mony_sso}</td>
						<td>{month_de_ssop}</td>
						<td>{month_de_ssoc}</td>
					</tr>
				</div>
				</tbody>
				<thead class="well">
				<div parser-repeat="[data_list1]">
					<tr>
						<th></th>
						<th></th>
						<th>รวม</th>
						<th></th>
						<th>{month_mony_sso}</th>
						<th>{month_de_ssop}</th>
						<th>{month_de_ssoc}</th>
					</tr>
				</div>
				</thead>

			</table>
			
