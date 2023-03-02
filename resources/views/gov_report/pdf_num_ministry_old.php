
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
				<div class="col-3 text-left float-left">
					 <?=en2bn(date('d-m-Y'))?>
				</div>
				<div class="col-6 text-center float-left">
					<p class="text-center" style="margin-top: 0;"><span style="font-size:12px;font-weight: bold;">সিভিলস্যুট ম্যানেজমেন্ট সিস্টেম</span><br> মন্ত্রিপরিষদ বিভাগ-গণপ্রজাতন্ত্রী বাংলাদেশ সরকার,<br> বাংলাদেশ সচিবালয়, ঢাকা</p>

				</div>
				<div class="col-2 text-right float-right">
					স্লোগান
				</div>
			</div>
		</div>

			<div class="priview-memorandum">
				<div class="row">
					<div class="col-12 text-center">
						<div style="font-size:12px;"><u><?=$page_title?></u></div>
						<div style="font-size:12px;"><u><?=en2bn($date_start)?> থেকে <?=en2bn($date_end)?></u></div>
					</div>
				</div>
			</div>

			<div class="priview-demand">
				<table class="" style="width:100%">
					<thead class="headding">
						<tr>
							<th class="text-center" width="50" >ক্রম</th>
							<th width="250" >মন্ত্রনালয়</th>
							<th width="50" style="font-size:13px;"  class="text-center" >মাঠ প্রশাসন/দপ্তর </th>
                            <th class="text-center" >বিবেচ্য সময়কালে<br> দায়েরকৃত মামলার সংখ্যা</th>
							<th class="text-center" >বিবেচ্য সময়কালের <br>পূর্ব পর্যন্ত অনিস্পন্ন<br> মামলার সংখ্যা</th>
							<th class="text-center" >মামলার মোট সংখ্যা</th>
							<th class="text-center" >বিবেচ্য সময়কালে<br> নিস্পন্ন মামলার সংখ্যা</th>
							<th class="text-center" >সরকারের বিপক্ষে <br>নিস্পত্তিকৃত মামলার সংখ্যা</th>
							<th class="text-center" >সরকারের বিপক্ষে <br>নিস্পত্তিক্রিত মামলায় <br>স্রঃপক্ষে দায়েরকৃত আপিল/ রিভিউ<br> রিভিসশন সংখ্যা</th>
							<th class="text-center" >বিবেচ্য সময়কালে শেষ <br>করমদিবসে অনিস্পন্ন <br>মামলার সংখ্যা</th>
							<th class="text-center" >গুরত্বপূর্ণ বিবেচিত<br> মামলার সংখ্যা</th>
						</tr>
					</thead>
					<tbody>
						<?php
                            foreach ($results as $key => $value) {
						        ?>
                                <tr>
                                    <td rowspan="<?=count($value['doptor']) == 0 ? '' : count($value['doptor']) ?>" class="text-center"><?=en2bn($key+1)?>.</td>
                                    <td rowspan="<?=count($value['doptor']) == 0 ? '' : count($value['doptor']) ?>"><?=$value['ministry_name_bn']?></td>
                                    <?php
                                    if(count($value['doptor']) != 0){
                                        foreach ($value['doptor'] as $mk => $row) {
                                            if($mk != 0){
                                                echo '<tr>';
                                            }
                                            ?>
                                                <td class="text-left" ><?=$row->doptor_name?></td>
                                                <td class="text-left" ><?=$row->dateBetween?></td>
                                                <td class="text-left" ><?=$row->prevUndoneCase?></td>
                                                <td class="text-left" ><?=$row->totalCase?></td>
                                                <td class="text-left" ><?=$row->doneCase?></td>
                                                <td class="text-left" ><?=$row->againstGov?></td>
                                                <td class="text-left" ><?=$row->appealCase?></td>
                                                <td class="text-left" ><?=$row->lastWorkDay?></td>
                                                <td class="text-left" ><?=$row->importantCase?></td>
                                            <?php
                                            if($mk != 0){
                                                echo '</tr>';
                                            }
                                        }
                                    } else{ ?>
                                        <td >-</td>
                                        <td >-</td>
                                        <td >-</td>
                                        <td >-</td>
                                        <td >-</td>
                                        <td >-</td>
                                        <td >-</td>
                                        <td >-</td>
                                        <td >-</td>
                                    <?php
                                    } ?>
                                </tr>
						<?php } ?>
					</tbody>

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

