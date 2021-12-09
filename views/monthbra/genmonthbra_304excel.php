
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
						<th colspan="2">รับชำระไม่ครบ</th>
						<th></th>
					</tr>
					<tr>
						<th>  ลำดับ  </th>
						<th>  รหัส  </th>
						<th>  ชื่อ-นามสกุล  </th>
						<th>  วันที่เริ่มงาน  </th>
						<th>  ตรวจประวัติ  </th>
						<th>  เครื่องแบบ  </th>
						<th>  บัตร  </th>
						<th>  ตรวจสุขภาพ  </th>
						<th>  อื่นๆ  </th>
						<th>  บริษัท  </th>
						<th>  ลูกค้า  </th>
						<th>  หมายเหตุ  </th>
					</tr>
				</thead>
				<tbody>
				<div parser-repeat="[data_list]">

					<tr >
						<td class="text-center">{record_number}  </td>
						<td class="text-center">{rf_name_id}  </td>
						<td>{rfPernameIdPreName}{rfNameIdEmpName}  {rfNameIdEmpSurname}  </td>
						<td>{rfNameIdEmpSurname}  </td>
						<td>{rfNameIdStartDate}  </td>
						<td>{month_de_ssop}  </td>
						<td>{month_de_ssoc}  </td>
					</tr>
				</div>
				</tbody>

			</table>
			
