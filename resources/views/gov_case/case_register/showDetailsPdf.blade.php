<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $page_title ?></title>
    <style type="text/css">
        .priview-body {
            font-size: 16px;
            color: #000;
            margin: 25px;
        }

        .priview-header {
            margin-bottom: 10px;
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
            padding-bottom: 20px;
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

        .table td {
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        b {
            font-weight: 500;
        }
    </style>

</head>

<body>
    <div class="priview-body">
        <div class="priview-header">
            <div class="row">
                <div class="col-3 text-left float-left">
                    <?= en2bn(date('d-m-Y')) ?>
                </div>
                <div class="col-6 text-center float-left">
                    <p class="text-center" style="margin-top: 0;"><span style="font-size:20px;font-weight: bold;">
                            স্মার্ট কেস ম্যানেজমেন্ট সিষ্টেম, </span><br>বাংলাদেশ সচিবালয়, ঢাকা</p>
                    <div style="font-size:18px;"><u><?= $page_title ?></u></div>
                    <?php //!empty($data_status)?'ব্যাক্তিগত ডাটার স্ট্যাটাসঃ '.func_datasheet_status($data_status).'<br>':''
                    ?>
                    <?php // !empty($division_info->div_name_bn)?'বিভাগঃ '.$division_info->div_name_bn.'<br>':''
                    ?>
                </div>
                {{-- <div class="col-2 text-right float-right">
               স্লোগান
            </div> --}}
            </div>
        </div>

        <div class="priview-memorandum">
            <div class="row">
                <div class="col-12 text-center">
                    <!-- <div style="font-size:18px;"><u><?= $page_title ?></u></div> -->
                    <?php //!empty($data_status)?'ব্যাক্তিগত ডাটার স্ট্যাটাসঃ '.func_datasheet_status($data_status).'<br>':''
                    ?>
                    <?php // !empty($division_info->div_name_bn)?'বিভাগঃ '.$division_info->div_name_bn.'<br>':''
                    ?>

                </div>
            </div>
        </div>

        <div class="priview-demand">
            <table class="table table-hover table-bordered report">
                <thead class="headding">
                    @if ($case->case_category_id)
                        <tr>
                            <th scope="row">মামলার ক্যাটেগরি</th>
                            @php
                                // Find the matched category based on case_category_id
                                $matchedCategory = $GovCaseDivisionCategory->where('id', $case->case_category_id)->first();
                            @endphp
                            <td>
                                @if ($matchedCategory)
                                    {{ $matchedCategory->name_bn }}
                                @endif
                            </td>
                        </tr>
                    @endif

                    @if ($case->case_type_id)
                        <tr>
                            <th scope="row">মামলার শ্রেণী/কেস-টাইপ</th>
                            @php
                                // Find the matched category based on case_type_id
                                $matchedDivisionCategory = $GovCaseDivisionCategoryType->where('id', $case->case_type_id)->first();
                            @endphp
                            <td>
                                @if ($matchedDivisionCategory)
                                    {{ $matchedDivisionCategory->name_bn }}
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th class="tg-19u4" width="130">মামলা নং</th>
                        <td class="tg-nluh">{{ $case->case_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="tg-19u4">রুল ইস্যুর তারিখ</th>
                        <td class="tg-nluh">{{ en2bn($case->date_issuing_rule_nishi) ?? '-' }}</td>
                    </tr>

                    @if ($case->concern_person_designation)
                        <tr>
                            <th scope="row">সংশ্লিষ্ট আইন কর্মকর্তা</th>
                            @php
                                // Find the matched category based on concern_person_designation
                                $matchedConcernPerson = $concern_person_desig->where('id', $case->concern_person_designation)->first();
                            @endphp
                            <td>
                                @if ($matchedConcernPerson)
                                    {{ $matchedConcernPerson->name }}
                                @endif
                            </td>
                        </tr>
                    @endif

                    @if ($case->concern_user_id)
                        <tr>
                            <th scope="row">সংশ্লিষ্ট আইন কর্মকর্তার নাম</th>
                            @php
                                // Find the matched category based on concern_user_id
                                $matchedConcernUser = $usersInfo->where('id', $case->concern_user_id)->first();
                            @endphp
                            <td>
                                @if ($matchedConcernUser)
                                    {{ $matchedConcernUser->name }}
                                @endif
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <th class="tg-19u4">বিষয়বস্তু(সংক্ষিপ্ত)</th>
                        <td class="tg-nluh">{{ $case->subject_matter ?? '-' }}</td>
                    </tr>
                    @if ($case->postponed_details)
                        <tr>
                            <th class="tg-19u4">স্থগিতাদেশের বিবরণ</th>
                            <td class="tg-nluh">{{ $case->postponed_details ?? '-' }}</td>
                        </tr>
                    @endif
                    {{-- <tr>
                     <th class="tg-19u4">মৌজা</th>
                      <td class="tg-nluh">
                      @foreach ($moujas as $key => $mouja)
                        {{ $mouja->mouja_name_bn ?? ''}},
                      @endforeach
                    </td>
                     <!-- <td class="tg-nluh">{{ $info->mouja_name_bn ?? ''}}</td> -->
                  </tr>
                  <tr>
                     <th class="tg-19u4">মামলা নং</th>
                     <td class="tg-nluh">{{ $info->case_number ?? ''}}</td>
                  </tr>
                  <tr>
                     <th class="tg-19u4">মামলা রুজুর তারিখ</th>
                     <td class="tg-nluh">{{ en2bn($info->case_date) ?? ''}}</td>
                  </tr> --}}


                    <tr>
                        <th class="tg-19u4">ফলাফল</th>
                        <td class="tg-nluh">
                            @if ($case->result == '1')
                                সরকারের পক্ষে
                            @elseif($case->result == '2')
                                সরকারের বিপক্ষে
                            @else
                                চলমান
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="tg-19u4">মন্তব্য</th>
                        <td class="tg-nluh">{{ $case->comments ?? '-' }}</td>
                    </tr>
                    @if (!empty($case->postponed_order))
                    <tr>
                        <th scope="row">স্থগিতাদেশ</th>
                        <td>
                            @if ($case->postponed_order == '1')
                                আছে
                            @elseif($case->postponed_order == '0')
                                নেই
                            @endif
                        </td>
                    </tr>
                @endif
                    {{-- @if (!empty($info->lost_reason))
                        <tr>
                            <th class="tg-19u4">পরাজয়ের কারণ</th>
                            <td class="tg-nluh">{{ $info->lost_reason ?? '' }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th class="tg-19u4">মামলায় হেরে গিয়ে কি আপিল করা হয়েছে</th>
                        <td class="tg-nluh">
                            @if ($info->is_lost_appeal == 1)
                                হ্যা!
                            @else
                                না!
                            @endif
                        </td>
                    </tr> --}}
                    {{-- @if (!empty($info->ref_id))
                        <tr>
                            <th class="tg-19u4">পূর্বের মামলা নং</th>
                            <td class="tg-nluh"><a href="{{ route('case.details', $info->ref_id) }}"
                                    target="_blank">{{ $info->ref_case_no }}</a> </td>
                        </tr>
                    @endif
                    <tr>
                        <th class="tg-19u4">মামলার বর্তমান অবস্থান</th>
                        <td class="tg-nluh">{{ $info->status_name }}, এর জন্য {{ $info->role_name }} এর কাছে</td>
                    </tr>
                    <tr>
                        <th class="tg-19u4">বর্তমান ষ্ট্যাটাস</th>
                        <td class="tg-nluh">
                            @if ($info->status == 1)
                                নতুন চলমান!
                            @elseif ($info->status == 2)
                                আপিল করা হয়েছে!
                            @elseif ($info->status == 3)
                                সম্পাদিত !
                            @endif

                        </td>
                    </tr> --}}
                </thead>
            </table>
        </div>
        <div class="priview-demand">
            <h4 class="font-weight-bolder">বাদীর বিবরণ</h4>
            <table class="table table-hover table-bordered report">
                <thead class="headding">
                    <tr>
                        <th class="tg-19u4" width="10">ক্রম</th>
                        <th class="tg-19u4 text-center" width="200">নাম</th>
                        <th class="tg-19u4 text-center">ঠিকানা</th>
                    </tr>
                <tbody>
                    @php $k = 1; @endphp
                    @foreach ($caseBadi as $badi)
                        <tr>
                            <td class="tg-nluh">{{ en2bn($k) }}.</td>
                            <td class="tg-nluh">{{ $badi->name }}</td>
                            <td class="tg-nluh">{{ $badi->address }}</td>
                        </tr>
                        @php $k++; @endphp
                    @endforeach
                </tbody>
                </thead>
            </table>
        </div>
        <div class="priview-demand">
            <h4 class="font-weight-bolder">বিবাদীর বিবরণ</h4>
            <table class="table table-hover table-bordered report">
                <thead class="headding">
                    <tr>
                        <th class="tg-19u4" width="10">ক্রম</th>
                        <th class="tg-19u4 text-center" width="200">নাম</th>
                        <th class="tg-19u4 text-center">ঠিকানা</th>
                    </tr>
                <tbody>
                    @php $k = 1; @endphp
                    @foreach ($caseBibadi as $bibadi)
                        <tr>
                            <td class="tg-nluh">{{ en2bn($k) }}.</td>
                            <td class="tg-nluh">{{ $bibadi->ministry->office_name_bn ?? '-' }}</td>
                            <td class="tg-nluh">{{ $bibadi->is_main_bibadi == 1 ? 'মূল বিবাদী' : 'অন্যান্য বিবাদী' }}
                            </td>
                        </tr>
                        @php $k++; @endphp
                    @endforeach
                </tbody>
                </thead>
            </table>
        </div>

        {{-- <div class="priview-demand">
            <h4 class="font-weight-bolder">জমির তাফসিল</h4>
            <table class="table table-hover table-bordered report">
                <thead class="headding">
                    <tr>
                        <th class="tg-19u4" width="10">ক্রম</th>
                        <th class="tg-19u4 text-center">রেকর্ড </th>
                        <th class="tg-19u4 text-center">খতিয়ান নং</th>
                        <th class="tg-19u4 text-center">দাগ নং</th>
                        <th class="tg-19u4 text-center">জমির শ্রেণী</th>
                        <th class="tg-19u4" width="150">জমির পরিমাণ (শতক)</th>
                        <th class="tg-19u4" width="170">নালিশী জমির পরিমাণ (শতক)</th>
                    </tr>
                <tbody>
                    @php $k = 1; @endphp
                    @foreach ($surveys as $survey)
                        <tr>
                            <td class="tg-nluh">{{ en2bn($k) }}.</td>
                            <td class="tg-nluh">{{ $survey->st_name }}</td>
                            <td class="tg-nluh">{{ $survey->khotian_no }}</td>
                            <td class="tg-nluh">{{ $survey->daag_no }}</td>
                            <td class="tg-nluh">{{ $survey->lt_name }}</td>
                            <td class="tg-nluh text-right">{{ en2bn($survey->land_size) }}</td>
                            <td class="tg-nluh text-right">{{ en2bn($survey->land_demand) }}</td>

                        </tr>
                        @php $k++; @endphp
                    @endforeach
                </tbody>
                </thead>
            </table>
        </div>

        <div class="priview-demand">
            <h4 class="font-weight-bolder">বিবরণ</h4>
            <table class="table table-hover table-bordered report">
                <thead class="headding">
                    <!-- <tr>
                  <th class="tg-19u4 text-left" width="100">তফশীল বিবরণ</th>
                  <td class="tg-nluh font-size-lg font-weight-bold">{{ $info->tafsil }}</td>
               </tr> -->
                    <tr>
                        <th class="tg-19u4 text-left" width="100">চৌহদ্দীর বিবরণ</th>
                        <td class="tg-nluh font-size-lg font-weight-bold">{{ $info->chowhaddi }}</td>
                    </tr>
                    <tr>
                        <th class="tg-19u4 text-left" width="100">মন্তব্য</th>
                        <td class="tg-nluh font-size-lg font-weight-bold">{{ $info->comments }}</td>
                    </tr>
                </thead>
            </table>
        </div>
        @if (count($sf_logs) != 0)
            <div class="priview-demand">
                <h4 class="font-weight-bolder">এস এফের লগ</h4>
                <table class="table table-hover table-bordered report">
                    <thead class="headding">
                        <tr>
                            <th class="tg-19u4" width="10">ক্রম</th>
                            <th class="tg-19u4 text-center">নাম</th>
                            <th class="tg-19u4 text-center">তারিখ</th>
                            <th class="tg-19u4 text-center">বিস্তারিত</th>
                        </tr>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($sf_logs as $row)
                            <tr>
                                <td class="tg-nluh">{{ en2bn($i) }}.</td>
                                <td class="tg-nluh">{{ $row->name }}</td>
                                <td class="tg-nluh">{{ en2bn($row->created_at) }}</td>
                                <td class="tg-nluh"> {{ $row->sf_log_details }} </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                    </thead>
                </table>
            </div>
        @endif
        @if (count($hearings) != 0)
            <div class="priview-demand">
                <h4 class="font-weight-bolder">শুনানির নোটিশ</h4>
                <table class="table table-hover table-bordered report">
                    <thead class="headding">
                        <tr>
                            <th class="tg-19u4" width="10">ক্রম</th>
                            <th class="tg-19u4 text-center">শুনানির তারিখ</th>

                            <th class="tg-19u4 text-center">মন্তব্য</th>
                        </tr>
                    <tbody class="text-center">
                        @forelse ($hearings as $key=> $row)
                            <tr>
                                <td class="tg-nluh text-center" scope="row">{{ en2bn($key + 1) }}.</td>
                                <td class="tg-nluh text-center">{{ en2bn($row->hearing_date) }}</td>
                                <td class="tg-nluh text-center" class="tg-nluh">{{ $row->hearing_comment }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="tg-nluh text-center" colspan="4">
                                    <h3>
                                        শুনানির কোন নোটিশ পাওয়া যাইনি
                                    </h3>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    </thead>
                </table>
            </div>
        @endif --}}

    </div>

</body>

</html>
