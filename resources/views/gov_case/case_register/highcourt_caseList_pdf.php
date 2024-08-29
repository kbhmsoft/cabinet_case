<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?=$page_title?></title>
    <style type="text/css">
    .priview-body {
        font-size: 16px;
        color: #000;
        margin: 5px;
    }

    .priview-header {
        margin-bottom: 2px;
        text-align: center;
    }

    .priview-header div {
        font-size: 18px;
    }

    .priview-memorandum,
    .priview-from,
    .priview-to,
    .priview-subject,
    .priview-message,
    .priview-office,
    .priview-demand,
    .priview-signature {
        padding-bottom: 2px;
    }

    .priview-office {
        text-align: center;
    }

    .priview-imitation ul {
        list-style: none;
    }

    .priview-imitation ul li {
        display: block;
    }

    .date-name {
        width: 20%;
        float: left;
        padding-top: 23px;
        text-align: right;
    }

    .date-value {
        width: 70%;
        float: left;
    }

    .date-value ul {
        list-style: none;
    }

    .date-value ul li {
        text-align: center;
    }

    .date-value ul li.underline {
        border-bottom: 1px solid black;
    }

    .subject-content {
        text-decoration: underline;
    }

    .headding {
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }

    .col-1 {
        width: 8.33%;
        float: left;
    }

    .col-2 {
        width: 16.66%;
        float: left;
    }

    .col-3 {
        width: 25%;
        float: left;
    }

    .col-4 {
        width: 33.33%;
        float: left;
    }

    .col-5 {
        width: 41.66%;
        float: left;
    }

    .col-6 {
        width: 50%;
        float: left;
    }

    .col-7 {
        width: 58.33%;
        float: left;
    }

    .col-8 {
        width: 66.66%;
        float: left;
    }

    .col-9 {
        width: 75%;
        float: left;
    }

    .col-10 {
        width: 83.33%;
        float: left;
    }

    .col-11 {
        width: 91.66%;
        float: left;
    }

    .col-12 {
        width: 100%;
        float: left;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table td,
    .table th {
        border: 1px solid #ddd;
    }

    .table tr.bottom-separate td,
    .table tr.bottom-separate td .table td {
        border-bottom: 1px solid #ddd;
    }

    .borner-none td {
        border: 0px solid #ddd;
    }

    .headding td,
    .total td {
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
    }

    /*.table td{padding:5px;}*/
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    b {
        font-weight: 500;
    }

    table,
    th,
    td {
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
                    <p class="text-center" style="margin-top: 0;">

                        গণপ্রজাতন্ত্রী বাংলাদেশ সরকার<br> বাংলাদেশ সচিবালয়, ঢাকা</p>

                </div>

            </div>
        </div>

        <div class="priview-memorandum">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-center" style="margin-top: 0;"><span style="font-size:15px;">
                            হাইকোর্ট বিভাগে সরকারি স্বার্থ সংশ্লিষ্ট মামলার তালিকা</p>


                    <div style="font-size:13px;">সময়কাল: <u><?=en2bn(date('Y-m-d'))?> </u></div>

                </div>
            </div>
        </div>

        <div class="priview-demand">
            <table class="" style="width:100%">
                <thead class="headding">
                    <tr>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;" width="30">
                            ক্রমিক</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলা নং
                        </th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার
                            শ্রেণী/কেস-টাইপ</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">পিটিশনারের
                            নাম</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার
                            বিষয়বস্তু</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">দফাওয়ারি
                            জবাব প্রেরণের তারিখ</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">সর্বশেষ
                            অবস্থা</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cases as $key => $row) {?>
                    <tr>
                        <td scope="row" style="text-align:center;" class="tg-bn">
                            <?= en2bn($key + 1) ?></td>
                        <td style="width: 10px; text-align:center;">
                            <?=en2bn($row->case_no) . '/' . en2bn($row->year)?>
                        </td>
                        <td style="text-align:center;">
                            <?php foreach ($gov_case_division_category_type as $value) {
    if ($value->id == $row['case_type_id']) {
        echo $value->name_bn;
    }
}?>
                        </td>
                        <td style="text-align:center;">
                            <?php if ($row->badis && $row->badis->first() && $row->badis->first()->name && $row->total_badi_number > 1) {
    echo $row->badis->first()->name . ' ও অন্যান্য';
} elseif ($row->badis && $row->badis->first() && $row->badis->first()->name) {
    echo $row->badis->first()->name;
}?>
                        </td>
                        <td style="text-align:center;"><?=Str::limit($row->subject_matter, 100) ?? '-'?></td>
                        <td style="text-align:center;">
                            <?=$row->result_sending_date ? en2bn($row->result_sending_date) : '-'?>
                        </td>
                        <td class="notice-popup">
                            <div class="product">
                                <div class="product-image">
                                    <?php if ($row->is_final_order == '1') {?>
                                    <span class="indicator">নিষ্পত্তিকৃত মামলা</span>
                                    <?php } else {?>
                                    <span class="indicator">মামলা চলমান</span>
                                    <?php }?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>

    </div>
    <div class="row">
        <div class="col-md-3 text-left" style="font-size:12px;font-weight: bold;">
            তাং <?=en2bn(date('d-m-Y'))?>
        </div>
        <div class="col-md-3 text-right" style="font-size:12px;font-weight: bold;">
            সরকারি স্বার্থ সংশ্লিষ্ট মামলা ব্যাবস্থাপনা
        </div>
    </div>

</body>

</html>
<script type="text/javascript">
function myFunction() {
    window.print();
}
</script>