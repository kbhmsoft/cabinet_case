
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$page_title?></title>
	<style type="text/css">
		.priview-body{font-size: 16px;color:#000;margin: 5px;}
		.priview-header{margin-bottom: 2px;text-align:center;}
		.priview-header div{font-size: 18px;}
		.priview-memorandum, .priview-from, .priview-to, .priview-subject, .priview-message, .priview-office, .priview-demand, .priview-signature{padding-bottom: 2px;}
		.priview-office{text-align: center;}
		.priview-imitation ul{list-style: none;}
		.priview-imitation ul li{display: block;}
		.date-name{width: 20%;float: left;padding-top: 23px;text-align: right;}
		.date-value{width: 70%;float:left;}
		.date-value ul{list-style: none;}
		.date-value ul li{text-align: center;}
		.date-value ul li.underline{border-bottom: 1px solid black;}
		.subject-content{text-decoration: underline;}
		.headding{border-top:1px solid #000;border-bottom:1px solid #000;}

		.col-1{width:8.33%;float:left;}
		.col-2{width:16.66%;float:left;}
		.col-3{width:25%;float:left;}
		.col-4{width:33.33%;float:left;}
		.col-5{width:41.66%;float:left;}
		.col-6{width:50%;float:left;}
		.col-7{width:58.33%;float:left;}
		.col-8{width:66.66%;float:left;}
		.col-9{width:75%;float:left;}
		.col-10{width:83.33%;float:left;}
		.col-11{width:91.66%;float:left;}
		.col-12{width:100%;float:left;}

		.table{width:100%;border-collapse: collapse;}
		.table td, .table th{border:1px solid #ddd;}
		.table tr.bottom-separate td,
		.table tr.bottom-separate td .table td{border-bottom:1px solid #ddd;}
		.borner-none td{border:0px solid #ddd;}
		.headding td, .total td{border-top:1px solid #ddd;border-bottom:1px solid #ddd;}
		/*.table td{padding:5px;}*/
		.text-center{text-align:center;}
		.text-right{text-align:right;}
		.text-left{text-align:left;}
		.mb-10{margin-bottom: 10px;}
		.fs-10{font-size:9px !important;}
		b{font-weight:500;}
		table, th, td {
			border: 1px solid #ddd;
			border-collapse: collapse;
		}
	</style>
</head>
<body onload="myFunction()">
	<div class="priview-body">
		<div class="priview-header">
			<div class="row">
				<div class="col-3 text-left float-left">
					 <?=en2bn(date('d-m-Y'))?>
				</div>
				<div class="col-6 text-center float-left fs-10">
					<p class="text-center" style="margin-top: 0;"><span style="font-size:18px;font-weight: bold;">সরকারি স্বার্থ সংশ্লিষ্ট মামলা ব্যাবস্থাপনা</span><br> মন্ত্রিপরিষদ বিভাগ-গণপ্রজাতন্ত্রী বাংলাদেশ সরকার,<br> বাংলাদেশ সচিবালয়, ঢাকা</p>

				</div>
				<div class="col-2 text-right float-right fs-10">
					ডিজিটাল বাংলাদেশ, সকল সেবা হাতের মুঠয়।
				</div>
			</div>
		</div>

			<div class="priview-memorandum">
				<div class="row">
					<div class="col-12 text-center mb-10">
						<div style="font-size:13px;"><u><?=$ministry->office_name_bn?><?=$page_title?></u></div>
						<?php if($date_start != '1970-01-01'){?>
						<div style="font-size:13px;"><u><?=en2bn($date_start)?> থেকে <?=en2bn($date_end)?></u></div>
						<?php }?>
					</div>
				</div>
			</div>

			<div class="priview-demand">
				<table class="" style="width:100%">
					<tr>
						<th class="text-left mb-10" >মাঠ প্রশাসন/দপ্তর </th>
                        <td class="text-left mb-10" ><?=$ministry->office_name_bn?></td>
                    </tr>
                    <tr>
                        <th class="text-left mb-10" >বিবেচ্য সময়কালেদায়েরকৃত মামলার সংখ্যা</th>
                        <td class="text-left mb-10" ><?=$dateBetween?></td>
                    </tr>
                    <tr>
						<th class="text-left mb-10" >বিবেচ্য সময়কালের পূর্ব পর্যন্ত অনিস্পন্নমামলার সংখ্যা</th>
                        <td class="text-left mb-10" ><?=$prevUndoneCase?></td>
                    </tr>
                    <tr>
						<th class="text-left mb-10" >মামলার মোট সংখ্যা</th>
                        <td class="text-left mb-10" ><?=$totalCase?></td>
                    </tr>
                    <tr>
						<th class="text-left mb-10" >বিবেচ্য সময়কালেনিস্পন্ন মামলার সংখ্যা</th>
                        <td class="text-left mb-10" ><?=$doneCase?></td>
                    </tr>
                    <tr>
						<th class="text-left mb-10" >সরকারের বিপক্ষে নিস্পত্তিকৃত মামলার সংখ্যা</th>
                        <td class="text-left mb-10" ><?=$againstGov?></td>
                    </tr>
                    <tr>
						<th class="text-left mb-10" >সরকারের বিপক্ষে নিস্পত্তিক্রিত মামলায় স্রঃপক্ষে দায়েরকৃত আপিল/ রিভিউরিভিসশন সংখ্যা</th>
                        <td class="text-left mb-10" ><?=$appealCase?></td>
                    </tr>
                    <tr>
						<th class="text-left mb-10" >বিবেচ্য সময়কালে শেষ করমদিবসে অনিস্পন্ন মামলার সংখ্যা</th>
                        <td class="text-left mb-10" ><?=$lastWorkDay?></td>
                    </tr>
                    <tr>
						<th class="text-left mb-10" >গুরত্বপূর্ণ বিবেচিতমামলার সংখ্যা</th>
                        <td class="text-left mb-10" ><?=$importantCase?></td>
					</tr>
				</table>
			</div>

		</div>

	</body>
	</html>
	<script type="text/javascript">
		function myFunction() {
		  window.print();
		}
	</script>

