<!-- [ View File name : preview_view.php ] -->

<style>
	body {
		font-family: 'TH SarabunPSK';		
		font-size : 14pt;
		margin : 0px;
	}
	table {
        border-collapse: collapse;
		font-size : 14pt;
		padding: 1px;
	}
	td {
		font-family: 'TH SarabunPSK';	
		border: 0.3px solid black;
        border-collapse: collapse;
		
	}
</style>
<div parser-repeat="[data_list]">
	<tbody>
	<table class="table table-bordered table-hover" >
		

			<tr>
				<td>
				<table class="table table-borderless">	
				<tr>
					<th colspan="8"><h1> สาขา {rfBranchIdBranchNick}</h1></th>
					<th align = "right" colspan="9"><p><b>บริษัท {companyIdCompanyName}</b></p></th>
				</tr>
				<tr >
					<th colspan="2"> ชื่อ - สกุล</th>
					<th colspan="4">{rfNameIdEmpName} {rfNameIdEmpSurname}</th>
					<th colspan="2"> แผนก</th>
					<th colspan="9">{paypr_section}</th>
				</tr>
				<tr>
					<th colspan="2"> รหัส</th>
					<th colspan="4">{rf_name_id}</th>
					<th colspan="2"> ประจำงวด</th>
					<th colspan="4">{rfPayIdPayFromdate} - {rfPayIdPayTodate}</th>
					<th colspan="2"> วันที่จ่าย</th>
					<th colspan="4">{rfPayIdPayAppdate}</th>
				</tr>
				</table>
				</td>
			</tr>
	</table>
	</tbody>

	<tbody>
	<table class="table table-borderless" >
		
			<tr>
				<td colspan="8">
				<table class="table table-borderless" style="padding-top: 5px;padding-bottom: 5px;">	
				<tr >
						<th colspan="2"> ค่าจ้าง</th>
						<th colspan="3" align = "center">{wageToMonth}</th>
						<th colspan="2">บาท/M</th>
					</tr>
				</table>
				
				<hr>
				<table class="table table-borderless">	
					<tr style="font-size: 12pt;">
						<th colspan="3"><br><br>
						&nbsp;&nbsp;ขาดงาน<br>
						&nbsp;&nbsp;กิจไม่ได้ค่าจ้าง<br>
						&nbsp;&nbsp;กิจได้รับค่าจ้าง<br>
						&nbsp;&nbsp;พักร้อน<br>
						&nbsp;&nbsp;ป่วย<br>
						&nbsp;&nbsp;อุบัติเหตุในงาน100<br>
						&nbsp;&nbsp;อุบัติเหตุในงาน40<br>
						&nbsp;&nbsp;ลาคลอด<br>
						&nbsp;&nbsp;หยุดประเพณี<br>
						&nbsp;&nbsp;วันลาอื่น<br>
						&nbsp;&nbsp;shut down<br>
						&nbsp;&nbsp;วันทำงาน<br>
						&nbsp;&nbsp;{rfFormatCodeTextWorks1p}<br>
						&nbsp;&nbsp;{rfFormatCodeTextWorks2p}<br>
						&nbsp;&nbsp;{rfFormatCodeTextWorks3p}</th>
						<th align = "right">(หน่วย)<br>
						{paypr_absent_num}<br>
						{paypr_bnleave_num}<br>
						{paypr_bleave_num}<br>
						{paypr_aleave_num}<br>
						{paypr_sleave_num}<br>
						{paypr_adleave_num}<br>
						{paypr_ahleave_num}<br>
						{paypr_mleave_num}<br>
						{paypr_hleave_num}<br>
						{paypr_oleave_num}<br>
						{paypr_shutdown_num}<br>
						{paypr_work_num}<br>
						{paypr_works1_num}<br>
						{paypr_works2_num}<br>
						{paypr_works3_num}</th>
						<th colspan="2" align = "right">(บาท)&nbsp;&nbsp;<br>
						{paypr_absent_pay} &nbsp;&nbsp;<br>
						{paypr_bnleave_pay} &nbsp;&nbsp;<br>
						{paypr_bleave_pay} &nbsp;&nbsp;<br>
						{paypr_aleave_pay} &nbsp;&nbsp;<br>
						{paypr_sleave_pay} &nbsp;&nbsp;<br>
						{paypr_adleave_pay} &nbsp;&nbsp;<br>
						{paypr_ahleave_pay} &nbsp;&nbsp;<br>
						{paypr_mleave_pay} &nbsp;&nbsp;<br>
						{paypr_hleave_pay} &nbsp;&nbsp;<br>
						{paypr_oleave_pay} &nbsp;&nbsp;<br>
						{paypr_shutdown_pay} &nbsp;&nbsp;<br>
						{paypr_work_pay} &nbsp;&nbsp;<br>
						{paypr_works1_pay} &nbsp;&nbsp;<br>
						{paypr_works2_pay} &nbsp;&nbsp;<br>
						{paypr_works3_pay} &nbsp;&nbsp;</th>
					</tr>
				</table>
				</td>
				<td colspan="7">
				<table class="table table-borderless">	
					<tr style="font-size: 12pt; font-family: 'TH SarabunPSK';">
						<th colspan="3"> <br><br>
						 OT100<br>
						 OT150<br>
						 OT200<br>
						 OT300<br><br>
						 {rfFormatCodeTextOt101p}<br>
						 {rfFormatCodeTextOt105p}<br>
						 {rfFormatCodeTextOt102p}<br>
						 {rfFormatCodeTextOt103p}<br>
						 {rfFormatCodeTextOt104p}
						 <br><br><br> <br><br><br><br><br><br><br>
						 <br><br> <br><br><br><br><br><br><br>
						</th>
						<th colspan="2" align = "right">(หน่วย)<br>
						{paypr_ot100_num}<br>
						{paypr_ot150_num}<br>
						{paypr_ot200_num}<br>
						{paypr_ot300_num}<br><br>
						{paypr_ot101_num}<br>
						{paypr_ot105_num}<br>
						{paypr_ot102_num}<br>
						{paypr_ot103_num}<br>
						{paypr_ot104_num}<br>
						
						</th>
						<th colspan="2" align = "right">(บาท)&nbsp;&nbsp;<br>
						{paypr_ot100_pay}&nbsp;&nbsp;<br>
						{paypr_ot150_pay}&nbsp;&nbsp;<br>
						{paypr_ot200_pay}&nbsp;&nbsp;<br>
						{paypr_ot300_pay}&nbsp;&nbsp;<br><br>
						{paypr_ot101_pay}&nbsp;&nbsp;<br>
						{paypr_ot105_pay}&nbsp;&nbsp;<br>
						{paypr_ot102_pay}&nbsp;&nbsp;<br>
						{paypr_ot103_pay}&nbsp;&nbsp;<br>
						{paypr_ot104_pay}&nbsp;&nbsp;<br>
						</th>
					</tr>
				</table>
				<table class="table table-borderless" style="padding-top: 5px;padding-bottom: 5px;">	
					<tr >
						<th> {payprSevrancePayprDeclare}</th>
					</tr>
				</table>
				</td>
				<td colspan="6">
				<table class="table table-borderless">
					<tr style="font-size: 12pt;">
						<th colspan="2"><br><br>
						 คืนเงินประกัน<br>
						 ค่ากะ<br>
						 ค่าอาหาร<br>
						 ค่ารถ<br>
						 เบี้ยขยัน<br>
						 เบี้ยงเลี้ยง<br>
						 โบนัส<br>
						 ค่าครองชีพ<br>
						 ค่าโทร<br>
						 ค่าทักษะ<br>
						 ค่าตำแหน่ง<br>
						 ค่าน้ำมัน<br>
						 Incentive<br>
						 ค่าวิชาชีพ<br>
						 ค่าใบอนุญาติ<br>
						 ทุนการศึกษาบุตร<br>
						 ค่ารักษาพยาบาล<br>
						 ค่าเสื่อมสภาพรถ<br>
						 ค่าที่พัก ตจว<br>
						 ค่าที่พักอาศัย<br>
						 เงินช่วยเหลือ<br>
						 รายได้อื่นๆ
					</th>
						<th colspan="2" align = "right">(บาท)&nbsp;&nbsp;<br>
						{paypr_assurance_pay}&nbsp;&nbsp;<br>
						{paypr_shift}&nbsp;&nbsp;<br>
						{paypr_meal}&nbsp;&nbsp;<br>
						{paypr_car}&nbsp;&nbsp;<br>
						{paypr_diligent}&nbsp;&nbsp;<br>
						{paypr_etc}&nbsp;&nbsp;<br>
						{paypr_bonus}&nbsp;&nbsp;<br>
						{paypr_cola}&nbsp;&nbsp;<br>
						{paypr_telephone}&nbsp;&nbsp;<br>
						{paypr_skill}&nbsp;&nbsp;<br>
						{paypr_position}&nbsp;&nbsp;<br>
						{paypr_gas}&nbsp;&nbsp;<br>
						{paypr_incentive}&nbsp;&nbsp;<br>
						{paypr_profession}&nbsp;&nbsp;<br>
						{paypr_license}&nbsp;&nbsp;<br>
						{paypr_childship}&nbsp;&nbsp;<br>
						{paypr_medical}&nbsp;&nbsp;<br>
						{paypr_carde}&nbsp;&nbsp;<br>
						{paypr_uptravel}&nbsp;&nbsp;<br>
						{paypr_stay}&nbsp;&nbsp;<br>
						{paypr_subsidy}&nbsp;&nbsp;<br>
						{paypr_income_1}&nbsp;&nbsp;</th>
					</tr>
				</table>
				</td>
				<td colspan="7">
				<table class="table table-borderless" style="padding-top: 5px;padding-bottom: 5px;">	
					<tr>
						<th align = "center"><b ><a style="color:black;">รายการหักเงิน</a></b></th>
					</tr>	
				</table>
				<table class="table table-borderless">
					<tr style="font-size: 12pt;">
						<th colspan="4"> <br><br>
						 ประกันสังคม<br>
						 ภาษี<br>
						 กองทุน<br>
						 หักATM<br>
						 เงินออมสหกรณ์<br>
						 ค่าบัตร<br>
						 หักค่าตรวจสุขภาพ<br>
						 หักค่ามือถือ<br>
						 หักเงินสำรองจ่ายอื่นๆ<br>
						 หักเงินสำรองจ่าย ตจว<br>
						 หักค่าตรวจประวัติ<br>
						 หักเงินกู้ยืม<br>
						 หักเงินกู้ กยศ<br>
						 หักเงินกู้บ้าน<br>
						 หักเงินกรมบังคับคดี<br>
						 ค่าความเสียหาย<br>
						 หักอื่นๆ<br>
						 {rfFormatTextdeVisa}<br>
						 {rfFormatTextdeWorkP}<br>
						 {rfFormatTextdeAbsent}<br>
						 {rfFormatTextdeLate}<br>
						 {rfFormatTextdeMulct}
						</th>
						<th colspan="2" align = "right">(บาท)&nbsp;&nbsp;<br>
						{paypr_de_ssop}&nbsp;&nbsp;<br>
						{paypr_de_tax}&nbsp;&nbsp;<br>
						{paypr_de_funp}&nbsp;&nbsp;<br>
						{paypr_de_atm}&nbsp;&nbsp;<br>
						{paypr_de_cooperative}&nbsp;&nbsp;<br>
						{paypr_de_card}&nbsp;&nbsp;<br>
						{paypr_de_health}&nbsp;&nbsp;<br>
						{paypr_de_mobile}&nbsp;&nbsp;<br>
						{paypr_de_backother}&nbsp;&nbsp;<br>
						{paypr_de_backtravel}&nbsp;&nbsp;<br>
						{paypr_de_selfemp}&nbsp;&nbsp;<br>
						{paypr_de_borrow}&nbsp;&nbsp;<br>
						{paypr_de_elond}&nbsp;&nbsp;<br>
						{paypr_de_lond}&nbsp;&nbsp;<br>
						{paypr_de_debtcase}&nbsp;&nbsp;<br>
						{paypr_de_pernicious}&nbsp;&nbsp;<br>
						{paypr_de_income_1}&nbsp;&nbsp;<br>
						{paypr_de_visa}&nbsp;&nbsp;<br>
						{paypr_de_work_p}&nbsp;&nbsp;<br>
						{paypr_de_absent}&nbsp;&nbsp;<br>
						{paypr_de_late}&nbsp;&nbsp;<br>
						{paypr_de_mulct}&nbsp;&nbsp;
					</th>
					</tr>
				</table>
			</td>
			</tr>
	</table>
	</tbody>

	<tbody>
	<table class="table table-bordered table-hover" >
		

			<tr>
				<td colspan="4" align = "center">รายได้สะสม<br><hr align = "left" style="width:387%"><br>{paypr_totalsalary}</td>
				<td colspan="4" align = "center">เงินกองทุนส่วนพนัก<br>งานสะสม<br>{paypr_totalfun}</td>
				<td colspan="4" align = "center">ประกันสังคมสะสม<br><br>{paypr_totalsso}</td>
				<td colspan="3" align = "center">ภาษีสะสม<br><br>{paypr_totaltax}</td>
				<td colspan="6">
				<table class="table table-borderless">
					<tr style="font-size: 14pt;">
						<th colspan="2"><br><br> รายรับ</th>
						<th colspan="2" align = "right"><br><br>{paypr_salary_total}&nbsp;&nbsp;</th>
					</tr>
				</table></td>
				<td colspan="7">
				<table class="table table-borderless">
					<tr style="font-size: 16pt;">
						<th colspan="3"><br><br><b> รับสุทธิ</b></th>
						<th colspan="3" align = "right"><br><br><b>{paypr_salary_net}&nbsp;&nbsp;</b></th>
					</tr>
				</table></td>
			</tr>
	</table>
	</tbody> 
</div>				