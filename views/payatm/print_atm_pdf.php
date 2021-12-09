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
<div parser-repeat="[data_list]">
			<table class="table table-bordered table-hover">
				<thead class="well">
					<tr>
						<th class="text-center">Name</th>
						<th class="text-center">เลขที่บัญชี</th>
						<th class="text-center">จำนวนเงิน</th>
						<th class="text-center">Code</th>
					</tr>
				</thead>
				<tbody>
				</table>
<div parser-repeat="[data_list]">
<table>
					<tr>
						<td class="text-right fit">{rfPernameIdPreName}{rfNameIdEmpName} {rfNameIdEmpSurname}</td>
						<td class="text-center">{rfNameIdBankAccount}</td>
						<td class="text-left fit">{paypr_salary_net}</td>
						<td class="text-right fit">{rfBankIdBankCode}</td>
					</tr>
				</tbody>
			</table>
</div>