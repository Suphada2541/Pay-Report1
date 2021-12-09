
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

			<table border="1">
				<thead class="well">
					<tr>
						<th class="text-right fit">Name</th>
						<th>เลขที่บัญชี</th>
						<th>จำนวนเงิน</th>
						<th>Code</th>
					</tr>
				</thead>
				<tbody>
				<div parser-repeat="[data_list]">

					<tr >
						<td valign="top">{rfPernameIdPreName}{rfNameIdEmpName}  {rfNameIdEmpSurname}</td>
						<td valign="top">&#8203;{rfNameIdBankAccount}</td>
						<td>{paypr_salary_net}</td>
						<td>{rfBankIdBankCode}</td>
					</tr>
				</div>
				</tbody>
			</table>
