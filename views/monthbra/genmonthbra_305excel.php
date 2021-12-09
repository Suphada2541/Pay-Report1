<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

<table border="1">
	<thead class="well">
		<tr>
			<th> Num_card </th>
			<th> คำนำหน้า </th>
			<th> ชื่อ </th>
			<th> นามสกุล </th>
			<th> ค่าจ้างที่จ่ายจริง </th>
			<th> ยอดเงินสมทบ </th>
			<th> สมทบนายจ้าง4% </th>
		</tr>
	</thead>
	<tbody>
		<div parser-repeat="[data_list]">

			<tr>
				<td>{record_number} </td>
				<td>{rfPernameIdPreName}</td>
				<td>{rfNameIdEmpName} </td>
				<td>{rfNameIdEmpSurname} </td>
				<td>{month_mony_sso} </td>
				<td>{month_de_ssop} </td>
				<td>{month_de_ssoc} </td>
			</tr>
		</div>
	</tbody>

</table>