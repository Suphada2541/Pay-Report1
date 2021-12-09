
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

			<table border="1">
				<thead class="well">
				<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th colspan="2">รับชำระไม่ครบ</th>
						<th></th>
					</tr>
					<tr>
						<th>ลำดับ</th>
						<th>รหัส</th>
						<th>ชื่อ-สกุล</th>
						<th>วันที่เริ่มงาน</th>
						<th>เงินออมสหกรณ์</th>
						<th>ค่าบัตร</th>
						<th>หักค่าตรวจสุขภาพ</th>
						<th>หักค่ามือถือ</th>
						<th>หักเงินสำรองจ่ายอื่นๆ</th>
						<th>หักเงินกู้ยืม</th>
						<th>หักเงินสำรองจ่ายตจว</th>
						<th>หักเงินกู้ กยศ</th>
						<th>หักเงินกู้บ้าน</th>
						<th>หักค่าตรวจประวัติ</th>
						<th>หักเงินกรมบังคับคดี</th>
						<th>ค่าความเสียหาย</th>
						<th>หักค่าชุด</th>
						<th>หักอื่นๆ</th>
						<th>สาขา</th>
						<th>ลูกค้า</th>
						<th>หมายเหตุ</th>
					</tr>
				</thead>
				<tbody>
				<div parser-repeat="[data_list]">

					<tr >
						<td class="text-center" >{record_number}</td>
						<td class="text-center" >{rf_name_id}</td>
						<td>{rfPernameIdPreName}{rfNameIdEmpName}  {rfNameIdEmpSurname}</td>
						<td class="text-center" >{rfNameIdStartDate}</td>
						<td>{paypr_de_cooperative}</td>
						<td>{paypr_de_card}</td>
						<td>{paypr_de_health}</td>
						<td>{paypr_de_mobile}</td>
						<td>{paypr_de_backother}</td>
						<td>{paypr_de_borrow}</td>
						<td>{paypr_de_backtravel}</td>
						<td>{paypr_de_elond}</td>
						<td>{paypr_de_selfemp}</td>
						<td>{paypr_de_lond}</td>
						<td>{paypr_de_debtcase}</td>
						<td>{paypr_de_pernicious}</td>
						<td>{paypr_de_uniform}</td>
						<td>{paypr_de_income_3}</td>
						<td>{paypr_de_branch}</td>
						<td>{paypr_de_payroll}</td>
						<td>{paypr_de_memo}</td>
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
						<th>{paypr_de_cooperative}</th>
						<th>{paypr_de_card}</th>
						<th>{paypr_de_health}</th>
						<th>{paypr_de_mobile}</th>
						<th>{paypr_de_backother}</th>
						<th>{paypr_de_borrow}</th>
						<th>{paypr_de_backtravel}</th>
						<th>{paypr_de_elond}</th>
						<th>{paypr_de_selfemp}</th>
						<th>{paypr_de_lond}</th>
						<th>{paypr_de_debtcase}</th>
						<th>{paypr_de_pernicious}</th>
						<th>{paypr_de_uniform}</th>
						<th>{paypr_de_income_3}</th>
						<th>{paypr_de_branch}</th>
						<th>{paypr_de_payroll}</th>
						<th></th>
					</tr>
				</div>
				</thead>

			</table>
			
