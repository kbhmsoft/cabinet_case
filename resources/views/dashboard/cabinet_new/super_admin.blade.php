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

        .custom-card-style {
            background-color: #565656;
            border-right: 3px solid #565656;
            border-bottom: 3px solid #565656;
            border-left: 1px solid #565656;
            border-top: 1px solid #565656;
        }

        /* Responsive styles for 768px */
        @media only screen and (max-width: 768px) {
            .card-body {
                padding: 10px;
            }

            .card {
                max-width: none;
            }
        }

        /* Responsive styles for 1440px */
        @media (min-width: 768px) and (max-width: 1440px) {
            .card-body {
                padding: 20px;
            }
        }

        /* Responsive styles for 425px */
        @media (max-width: 425px) {
            .card-body {
                padding: 15px;
            }

            .count-item a {
                font-size: 14px;
            }
        }
    </style>
@endsection
<div class="container-fluid">
    <div class="mt-5">
        <div class="row">
            <!-- Card 7 -->
            <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                <div class="card border-0" style="background-color: #F5F5F5;">
                    <div class="card-body"
                        style="background-color: #F5F5F5; border-right: 3px solid #CCCCCC; border-bottom: 3px solid #CCCCCC; border-left: 1px solid #CCCCCC; border-top: 1px solid #CCCCCC;">
                        <div class="d-flex align-items-center">
                            <div style="font-size: 3rem; margin-right: 1rem;">
                                <img src="{{ asset('uploads/IconeSCMS/office.png') }}"
                                    style="height: 30px; width: 100%; margin-top: -1rem;" alt="Logo"
                                    class="brand-image">
                            </div>
                            <div class="ml-2">
                                <h5 class="card-title font-weight-bolder" style="color: black;">ব্যবহারকারী অফিস</h5>
                                <div class="case-info">
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.totalMinistryOffice') }}"
                                            style="text-decoration: none; color: black;">মন্ত্রণালয়/বিভাগ</a>
                                        <span class="count-numbers" style="margin-left: 10rem; color: black;">
                                            <?= en2bn($total_ministry) ?>
                                        </span>
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.totalDoptor') }}"
                                            style="text-decoration: none; color: black;">দপ্তর-সংস্থা</a>
                                        <span class="count-numbers" style="margin-left: 10rem; color: black;">
                                            <?= en2bn($total_doptor) ?>
                                        </span>
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.totalDivisionOffice') }}"
                                            style="text-decoration: none; color: black;">বিভাগীয় কমিশনারের কার্যালয়</a>
                                        <span class="count-numbers" style="margin-left: 10rem; color: black;">
                                            <?= en2bn($total_division) ?>
                                        </span>
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ route('cabinet.totalDistrictOffice') }}"
                                            style="text-decoration: none; color: black;">জেলা প্রশাসকের কার্যালয়</a>
                                        <span class="count-numbers" style="margin-left: 10rem; color: black;">
                                            <?= en2bn($total_district) ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Card 8 -->
            <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 " style=" background-color: #f5f5f5;">
                    <div class="card-body"
                        style="background-color: #f5f5f5; border-right: 3px solid #CCCCCC; border-bottom: 3px solid #CCCCCC; border-left: 1px solid #CCCCCC; border-top: 1px solid #CCCCCC;">
                        <div class="d-flex align-items-center " style=" margin-bottom: 3.8rem;">
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/group.png') }}"
                                    style=" height: 30px; width: 100%; margin-top: 3rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <div>
                                <h5 class="card-title font-weight-bolder" style="color: black;">
                                    ব্যবহারকারীর সংখ্যা
                                </h5>
                                <div class="case-info">
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size:17px;">
                                        <a href="{{ route('cabinet.assignedENothiUserManagement') }}"
                                            style="text-decoration: none; color: black;">
                                            ই-নথি আইডি</a>
                                        <span class="count-numbers"
                                            style="margin-left:10rem; color: black">{{ en2bn($doptorLoginCount) }}</span>
                                    </span>
                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size: 17px;">
                                        <a href="{{ url('cabinet/office/wise/users/external') }}"
                                            style="text-decoration: none; color: black;"> ই-নথি বহির্ভুত আইডি</a>
                                        <span class="count-numbers"
                                            style="margin-left:10rem; color: black">{{ en2bn($generalLoginCount) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 1 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body p-3 border custom-card-style">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('uploads/IconeSCMS/7.png') }}" alt="Logo" class="img-fluid"
                                    style="height: 50px;">
                            </div>
                            <div>
                                <h5 class="card-title fw-bold text-dark">
                                    কার্যক্রম গ্রহণের জন্য অপেক্ষমান মামলার তালিকা
                                </h5>
                                <div class="text-secondary ml-10">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appealAgainstGovtPending') }}"
                                        class="text-dark">আপিল দায়েরের জন্য পেন্ডিং</a>
                                        <span class="count-numbers ml-10">{{ en2bn($appealAgainstGovt) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.sendingReplyPending') }}" class="text-dark">জবাব প্রেরণের জন্য পেন্ডিং</a>
                                        <span class="count-numbers ml-10">{{ en2bn($sent_to_solicitor_case) }}</span>
                                    </div>
                                    {{-- <div class="d-flex justify-content-between">
                                        <a href="#" class="text-dark">স্থগিতাদেশ সম্পর্কিত পেন্ডিং</a>
                                        <span class="count-numbers ml-10">{{ en2bn($against_postpond_order) }}</span>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body p-3 border custom-card-style">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('uploads/IconeSCMS/danger.png') }}" alt="Logo"
                                    class="img-fluid" style="height: 50px;">
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-3">গুরুত্বপূর্ণ মামলা সমূহ</h5>
                                <div class="text-secondary ml-10">
                                    <a href="{{ route('cabinet.case.highcourtAppealMostImportantCase') }}"
                                        class="text-dark d-block">অতি গুরুত্বপূর্ণ মামলা</a>
                                    <a href="{{ route('cabinet.case.highcourtAppealImportantCase') }}"
                                        class="text-dark d-block">গুরুত্বপূর্ণ মামলা</a>
                                    <a href="{{ route('cabinet.case.contemptCaseList') }}"
                                        class="text-dark d-block">কনটেম্পট মামলা</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body p-3 border custom-card-style">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('uploads/IconeSCMS/Couse_List (1).png') }}" alt="Logo"
                                    class="img-fluid" style="height: 50px;">
                            </div>
                            <div>
                                <h5 class="card-title fw-bold text-dark">কজ লিস্ট</h5>
                                <div class="text-secondary ml-10">
                                    <a href="https://www.supremecourt.gov.bd/web/indexn.php?page=bench_list.php&menu=00&div_id=2&lang="
                                        target="_blank" class="text-dark d-block">হাইকোর্ট বিভাগ</a>
                                    <a href="https://www.supremecourt.gov.bd/web/indexn.php?page=bench_list_app.php&menu=01&div_id=1&lang="
                                        target="_blank" class="text-dark d-block">আপিল বিভাগ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body p-3 border custom-card-style">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('uploads/IconeSCMS/courthouse.png') }}" alt="Logo"
                                    class="img-fluid" style="height: 50px;">
                            </div>
                            <div>
                                <h5 class="fw-bold text-primary d-flex justify-content-center">হাইকোর্ট বিভাগ</h5>
                                <div class="text-secondary ml-10">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourt.running') }}"
                                            class="text-dark">চলমান মামলা</a>
                                        <span class="count-numbers ml-10">{{ en2bn($running_high_court_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourt.complete') }}"
                                            class="text-dark">নিষ্পত্তিকৃত মামলা</a>
                                        <span class="count-numbers ml-10">{{ en2bn($final_high_court_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourtNotAgainstGov') }}"
                                            class="text-dark">সরকারের-পক্ষে</a>
                                        <span
                                            class="count-numbers ml-10">{{ en2bn($highcourt_not_against_gov) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourtAgainstGov') }}"
                                            class="text-dark">সরকারের-বিপক্ষে</a>
                                        <span class="count-numbers ml-10">{{ en2bn($highcourt_against_gov) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body p-3 border custom-card-style">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('uploads/IconeSCMS/law.png') }}" alt="Logo" class="img-fluid"
                                    style="height: 50px;">
                            </div>
                            <div>
                                <h5 class="fw-bold text-primary">আপিল বিভাগ</h5>
                                <div class="text-secondary ml-10">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.running') }}"
                                            class="text-dark">চলমান মামলা</a>
                                        <span class="count-numbers ml-10">{{ en2bn($running_appeal_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.complete') }}"
                                            class="text-dark">নিষ্পত্তিকৃত মামলা</a>
                                        <span class="count-numbers ml-10">{{ en2bn($final_appeal_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.notAgainstGov') }}"
                                            class="text-dark">সরকারের-পক্ষে</a>
                                        <span class="count-numbers ml-10">{{ en2bn($appeal_not_against_gov) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.againstGov') }}"
                                            class="text-dark">সরকারের-বিপক্ষে</a>
                                        <span class="count-numbers ml-10">{{ en2bn($appeal_against_gov) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body p-3 border custom-card-style">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('uploads/IconeSCMS/tribunal.png') }}" alt="Logo"
                                    class="img-fluid" style="height: 50px;">
                            </div>
                            <div>
                                <h5 class="fw-bold text-primary">প্রশাসনিক ট্রাইব্যুনাল</h5>
                                <div class="text-secondary ml-10">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('cabinet.case.administrativeTribrunal') }}"
                                            class="text-dark">চলমান মামলা</a>
                                        <span class="count-numbers ml-10">{{ en2bn($atRunningCaseTotal) }}</span>

                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="#" class="text-dark">নিষ্পত্তিকৃত মামলা</a>
                                    </div>
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
