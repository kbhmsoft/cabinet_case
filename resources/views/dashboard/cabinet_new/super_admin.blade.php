@extends('layouts.cabinet.cab_default')
@yield('style')
<link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

@section('content')
@section('style')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kalpurush&display=swap">
    <style>
        body {
            font-family: 'Kalpurush', sans-serif;
        }

        .count-item a:hover {
            color: black;
        }
    </style>
@endsection
<div class="container">
    <div class="mt-5">
        <div class="row">
             <!-- Card 7 -->
             <div class="col-md-6 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;">
                    <div class="card-body"
                        style="margin-bottom: 1rem; background-color: #f5f5f5; border-radius: 1rem;">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 3rem; margin-right: 1rem;">
                                <img src="{{ asset('uploads/IconeSCMS/office.png') }}"
                                    style=" height: 50px; width: 100%; margin-top: -1rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <div class="ml-2">
                                <h5 class="card-title font-weight-bolder" style="color: black;">ব্যবহারকারী অফিস</h5>
                                <div class="case-info">
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size:17px;">
                                        <a href="{{ route('cabinet.totalMinistryOffice') }}"
                                            style="text-decoration: none; color: black;">
                                            মন্ত্রণালয়/বিভাগ</a>
                                        <span class="count-numbers" style="margin-left:10rem; color: black">
                                            <?= en2bn($total_ministry) ?> </span>
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.totalDoptor') }}"
                                            style="text-decoration: none; color: black;"> দপ্তর-সংস্থা</a>
                                        <span class="count-numbers" style="margin-left:10rem; color: black">
                                            <?= en2bn($total_doptor) ?> </span>
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.totalDivisionOffice') }}"
                                            style="text-decoration: none; color: black;">বিভাগীয় কমিশনারের কার্যালয়</a>
                                        <span class="count-numbers" style="margin-left:10rem; color: black">
                                            <?= en2bn($total_division) ?> </span>
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.totalDistrictOffice') }}"
                                            style="text-decoration: none; color: black;"> জেলা প্রশাসকের কার্যালয়</a>
                                        <span class="count-numbers" style="margin-left:10rem; color: black">
                                            <?= en2bn($total_district) ?> </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-6 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;">
                    <div class="card-body"
                        style="margin-bottom: 3rem; background-color: #f5f5f5; border-radius: 1rem;">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/all (1).png') }}"
                                    style=" height: 50px; width: 100%; margin-top: 6rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <div style="margin-top: 2rem">
                                <h5 class="card-title font-weight-bolder" style="color: black;">
                                    মোট এন্ট্রিকৃত মামলার সংখ্যা (<span
                                        class="count-numbers"><?= en2bn($total_case) ?></span>)
                                </h5>
                                <div class="case-info">
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size:17px;">
                                        <a href="{{ route('cabinet.case.highcourt') }}"
                                            style="text-decoration: none; color: black;">
                                            হাইকোর্ট বিভাগে মোট মামলা</a>
                                        <span class="count-numbers"
                                            style="margin-left:10rem; color: black">{{ en2bn($total_high_court_case) }}</span>
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.case.appellateDivision') }}"
                                            style="text-decoration: none; color: black;"> আপিল বিভাগে মোট মামলা</a>
                                        <span class="count-numbers"
                                            style="margin-left:10rem; color: black">{{ en2bn($total_appeal_case) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- Card 8 -->
            <div class="col-md-6 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;">
                    <div class="card-body"
                        style="background-color: #f5f5f5; border-radius: 1rem; margin-bottom: 3rem;">
                        <div class="d-flex align-items-center " style=" margin-top: 2rem;">
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/group.png') }}"
                                    style=" height: 50px; width: 100%; margin-top: 3rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <div>
                                <h5 class="card-title font-weight-bolder" style="color: black;">
                                    ব্যবহারকারীর সংখ্যা
                                </h5>
                                <div class="case-info">
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size:17px;">
                                        <a href="{{ route('cabinet.case.highcourt') }}"
                                            style="text-decoration: none; color: black;">
                                            ই-নথি আইডি</a>
                                        {{-- <span class="count-numbers" style="margin-left:10rem; color: black">{{ en2bn($total_high_court_case) }}</span> --}}
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.case.appellateDivision') }}"
                                            style="text-decoration: none; color: black;"> ই-নথি বহির্ভুত আইডি</a>
                                        {{-- <span class="count-numbers"
                                        style="margin-left:10rem; color: black">{{ en2bn($total_appeal_case) }}</span> --}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 1 -->
            <div class="col-md-4 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;">
                    <div class="card-body" style="background-color: #f5f5f5; border-radius: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/7.png') }}"
                                    style=" height: 50px; width: 100%; margin-top: 2.5rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <div>
                                <h5 class="card-title font-weight-bolder" style="color:rgb(0, 0, 0);">
                                    কার্যক্রম গ্রহণের জন্য অপেক্ষমান মামলার তালিকা
                                </h5>
                                <div style="font-size: 16px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="#" style="color: black;">আপিল দায়েরের জন্য পেন্ডিং</a>
                                        <span class="count-numbers"
                                            style="margin-left:rem; color: black">{{ en2bn($appealAgainstGovt) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="#" style=" color: black;">জবাব প্রেরণের জন্য পেন্ডিং</a>
                                        <span class="count-numbers"
                                            style="margin-left:rem; color: black">{{ en2bn($sent_to_solicitor_case) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="#" style=" color: black;">স্থগিতাদেশ সম্পর্কিত
                                            পেন্ডিং</a>
                                        <span class="count-numbers"
                                            style="margin-left:rem; color: black">{{ en2bn($against_postpond_order) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;;">
                    <div class="card-body"
                        style="margin-bottom: 2rem; background-color: #f5f5f5;; border-radius: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/danger.png') }}"
                                    style=" height: 50px; width: 100%; margin-top: 2rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <!-- Content -->
                            <div sy>
                                <h5 class="font-weight-bolder" style="color:rgb(0, 0, 0); margin-bottom: 2rem">
                                    গুরুত্বপূর্ণ মামলা সমূহ
                                </h5>
                                <!-- Links -->
                                <div style="font-size: 17px;">
                                    <a href="{{ route('cabinet.case.highcourtAppealMostImportantCase') }}"
                                        style="color: black;">অতি গুরুত্বপূর্ণ মামলা</a><br>
                                    <a href="{{ route('cabinet.case.highcourtAppealImportantCase') }}"
                                        style=" color: black;">গুরুত্বপূর্ণ মামলা</a><br>
                                    <a href="{{ route('cabinet.case.contemptCaseList') }}"
                                        style=" color: black;">কনটেম্পট মামলা</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;">
                    <div class="card-body" style="margin-bottom: 3.5rem; background-color: #f5f5f5; border-radius: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/Couse_List (1).png') }}"
                                    style=" height: 50px; width: 80%; margin-top: 4rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <div>
                                <h5 class="card-title font-weight-bolder" style="color:rgb(0, 0, 0);">
                                    কজ লিস্ট
                                </h5>
                                <div style="font-size: 1.4rem;">
                                    <a href="https://www.supremecourt.gov.bd/web/indexn.php?page=bench_list.php&menu=00&div_id=2&lang="
                                        target="_blank" style=" color: black;">হাইকোর্ট বিভাগ</a><br>
                                    <a href="https://www.supremecourt.gov.bd/web/indexn.php?page=bench_list_app.php&menu=01&div_id=1&lang="
                                        target="_blank" style=" color: black;">আপিল বিভাগ</a><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col-md-4 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;">
                    <div class="card-body" style="margin-bottom: 3rem; background-color: #f5f5f5; border-radius: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 1.5rem;">
                                <img src="{{ asset('uploads/IconeSCMS/courthouse.png') }}"
                                    style="height: 50px; width: 100%; " alt="Logo" class="brand-image">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="font-weight-bolder" style="color:rgb(3, 62, 164);">
                                    হাইকোর্ট বিভাগ
                                </h5>
                                <!-- Links -->
                                <div style="font-size: 17px;">
                                    <div style="display: flex; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourt.running') }}"
                                            style="color: black; flex: 1; margin-right:30px;">চলমান মামলা</a>
                                        <span class="count-numbers"
                                            style="color: black;">{{ en2bn($running_high_court_case) }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourt.complete') }}"
                                            style="color: black; flex: 1; margin-right:30px;">নিষ্পত্তিকৃত
                                            মামলা</a>
                                        <span class="count-numbers"
                                            style="color: black;">{{ en2bn($final_high_court_case) }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourtNotAgainstGov') }}"
                                            style="color: black; flex: 1; margin-right:30px;">সরকারের-পক্ষে</a>
                                        <span class="count-numbers" style="color: black;"></span>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourtAgainstGov') }}"
                                            style="color: black; flex: 1; margin-right:30px;">সরকারের-বিপক্ষে</a>
                                        <span class="count-numbers" style="color: black;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 5 -->
            <div class="col-md-4 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;">
                    <div class="card-body"
                        style="margin-bottom: 3rem; background-color: #f5f5f5; border-radius: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 1.5rem; margin-right: 1.5rem;">
                                <img src="{{ asset('uploads/IconeSCMS/law.png') }}"
                                    style="height: 50px; width: 100%;" alt="Logo" class="brand-image">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="font-weight-bolder" style="color:rgb(3, 62, 164);">
                                    আপিল বিভাগ
                                </h5>
                                <!-- Links -->
                                <div style="font-size: 17px;">
                                    <div style="display: flex; align-items: center;">
                                        <a href="{{ route('cabinet.case.appellateDivision.running') }}"
                                            style="color: black; flex: 1; margin-right:30px;">চলমান মামলা</a>
                                        <span class="count-numbers"
                                            style="color: black;">{{ en2bn($running_appeal_case) }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <a href="{{ route('cabinet.case.appellateDivision.complete') }}"
                                            style="color: black; flex: 1; margin-right:30px;">নিষ্পত্তিকৃত
                                            মামলা</a>
                                        <span class="count-numbers"
                                            style="color: black;">{{ en2bn($final_appeal_case) }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <a href="{{ route('cabinet.case.appellateDivision.notAgainstGov') }}"
                                            style="color: black; flex: 1; margin-right:30px;">সরকারের-পক্ষে</a>
                                        <span class="count-numbers" style="color: black;">
                                        </span>
                                    </div>
                                    <div style="display: flex; align-items: center;">
                                        <a href="{{ route('cabinet.case.appellateDivision.againstGov') }}"
                                            style="color: black; flex: 1; margin-right:30px;">সরকারের-বিপক্ষে</a>
                                        <span class="count-numbers" style="color: black;">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 6 -->
            <div class="col-md-4 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: #f5f5f5;">
                    <div class="card-body"
                        style="background-color: #f5f5f5; border-radius: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/tribunal.png') }}"
                                    style=" height: 60px; width: 100%; margin-bottom: -.5rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="card-title font-weight-bolder"
                                    style="color:rgb(0, 0, 0); margin-bottom: 1.5rem">
                                    প্রশাসনিক ট্রাইব্যুনাল
                                </h5>
                                <!-- Links -->
                                <div style="font-size: 1.4rem;">
                                    <a href="#" style="color:black">চলমান মামলা</a><br>
                                    <a href="#" style="color:black">নিষ্পত্তিকৃত মামলা</a><br>
                                    <a href="#" style="color:black">সরকার-পক্ষে</a><br>
                                    <a href="#" style="color:black">সরকার-বিপক্ষে</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 5 - Table with Pie Chart -->
            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="card-body" style="margin-bottom: -6.5rem">
                            <table class="table table-hover mb-6 font-size-h5">
                            </table>
                            <canvas id="pieChart1" width="100" height="100" style="margin-bottom: 5rem"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" style="border-radius: 1rem; ">
                        <div class="card-body" style="margin-bottom: -5rem">
                            <canvas id="pieChart2" width="100" height="100" style="margin-bottom: 5rem"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class=" row mt-4">
                @include('dashboard.cabinet.inc._dashboard_min_wise_card')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data for second Pie Chart (You need to provide data for this chart)
        var pieData1 = {
            labels: ["হাইকোর্ট বিভাগ", "আপিল বিভাগ"],
            datasets: [{
                data: [
                    {{ $total_high_court_case }},
                    {{ $total_appeal_case }},
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    // 'rgba(255, 206, 86, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    // 'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Data for second Pie Chart (You need to provide data for this chart)
        var pieData2 = {
            labels: ["আপিল দায়েরের জন্য পেন্ডিং", "জবাব প্রেরণের জন্য পেন্ডিং", "স্থগিতাদেশ সম্পর্কিত পেন্ডিং"],
            datasets: [{
                data: [
                    {{ $appealAgainstGovt }},
                    {{ $sent_to_solicitor_case }},
                    {{ $against_postpond_order }}
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(54, 162, 35, 0.5)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 62, 35, 1)',
                ],
                borderWidth: 1
            }]
        };

        // Render first Pie Chart
        var ctx1 = document.getElementById('pieChart1').getContext('2d');
        var myPieChart1 = new Chart(ctx1, {
            type: 'pie',
            data: pieData1
        });

        // Render second Pie Chart
        var ctx2 = document.getElementById('pieChart2').getContext('2d');
        var myPieChart2 = new Chart(ctx2, {
            type: 'pie',
            data: pieData2
        });
    </script>
@endsection
