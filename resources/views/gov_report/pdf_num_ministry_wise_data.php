

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
				<div class="col-12 text-center float-left">
					<p class="text-center" style="margin-top: 0;"><span style="font-size:12px;font-weight: bold;">সরকারি স্বার্থ সংশ্লিষ্ট মামলা ব্যাবস্থাপনা</span><br> <?= auth()->user()->govOffice->office_name_bn ?? '' ?>-গণপ্রজাতন্ত্রী বাংলাদেশ সরকার,<br> বাংলাদেশ সচিবালয়, ঢাকা</p>

				</div>

			</div>
		</div>

			<div class="priview-memorandum">
				<div class="row">
					<div class="col-12 text-center">
                    <p class="text-center" style="margin-top: 0;"><span style="font-size:15px;">উচ্চ আদালতে সকল চলমান সরকারি স্বার্থ সংশ্লিষ্ট মামলার পরিসংখ্যান</p>

						<?php if ($date_start != '1970-01-01') {?>
						<div style="font-size:13px;">সময়কাল:<u><?=en2bn($date_start)?> থেকে <?=en2bn($date_end)?></u></div>
						<?php }?>
					</div>
				</div>
			</div>

			<div class="priview-demand">
				<table class="" style="width:100%">
					<thead class="headding">
						<tr>
							<th class="text-center" width="50" >ক্রম</th>
							<th width="50" style="font-size:13px;"  class="text-center" >মন্ত্রণালয় / বিভাগ </th>
                            <th class="text-center" >বিবেচ্য সময়কালে<br> দায়েরকৃত মামলার সংখ্যা</th>
							<th class="text-center" >বিবেচ্য সময়কালের <br>পূর্ব পর্যন্ত অনিস্পন্ন<br> মামলার সংখ্যা</th>
							<th class="text-center" >মামলার মোট সংখ্যা</th>
							<th class="text-center" >বিবেচ্য সময়কালে<br> নিস্পত্তিকৃত মামলার সংখ্যা</th>
							<th class="text-center" >সরকারের পক্ষে <br>নিস্পত্তিকৃত মামলার সংখ্যা</th>
							<th class="text-center" >সরকারের বিপক্ষে <br>নিস্পত্তিকৃত মামলার সংখ্যা</th>
							<th class="text-center" >বিবেচ্য সময়কালে শেষ <br>করমদিবসে অনিস্পন্ন <br>মামলার সংখ্যা</th>
						</tr>
					</thead>
					<tbody>

                        <?php
                            foreach ($ministryWiseData as $key => $value) {
                                ?>
                                <tr>

                        <td class="text-center" ><?= en2bn($key+1) ?></td>
                            <td class="text-center" ><?= en2bn($value->office_name_bn)   ?></td>
                            <td class="text-center" ><?= en2bn($value->dateBetween) ?? '-' ?></td>
                            <td class="text-center" ><?= en2bn($value->prevUndoneCase) ?? '-' ?></td>
                            <td class="text-center" ><?= en2bn($value->totalCase) ?? '-' ?></td>
                            <td class="text-center" ><?= en2bn($value->doneCase) ?? '-' ?></td>
                            <td class="text-center" ><?= en2bn($value->favouredGov) ?? '-' ?></td>
                            <td class="text-center" ><?= en2bn($value->againstGov) ?? '-' ?></td>
                            <td class="text-center" ><?= en2bn($value->lastWorkDay) ?? '-' ?></td>
                         </tr>
                        <?php
                     }?>

					</tbody>

				</table>
			</div>

		</div>
        <div class="row">
        <div class="col-3 text-left float-left">
					 <?=en2bn(date('d-m-Y'))?>
        </div>
        </div>

	</body>
	</html>
	<script type="text/javascript">
		function myFunction() {
		  window.print();
		}
	</script>

