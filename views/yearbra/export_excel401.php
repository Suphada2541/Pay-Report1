
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

			<table border="1">
				<thead class="well">
					<tr>
						<th>ชื่อสาขา</th>
						<th>เลขประจำตัวผู้เสียภาษี</th>
						<th>ชื่อ-สกุล</th>
						<th>ยอดรายได้</th>
						<th>ยอดเสียภาษี</th>
						<th>ยอดเงินหักประกันสังคม</th>
						<th>ยอดเงินหักกองทุน</th>
						<th>ยอดเงินรวมลดหย่อน</th>
						<th>Company</th>
					</tr>
				</thead>
				<tbody>
				<div parser-repeat="[data_list]">

					<tr >
						<td class="text-center" >{}</td>
						<td class="text-center" >{}</td>
						<td>{rfPernameIdPreName}{rfNameIdEmpName}  {rfNameIdEmpSurname}</td>
						<td class="text-center" >{}</td>
						<td>{}</td>
						<td>{}</td>
						<td>{}</td>
						<td></td>
						<td></td>
					</tr>
				</div>
				</tbody>
			</table>
			
