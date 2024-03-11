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

        .hover-effect:hover {
            font-size: 25px !important;
        }
    </style>
@endsection


<div class="container">
    <div class="mt-5">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-6 mb-4">
                {{-- <div class="card" style="border-radius: 1rem; background-color: rgb(215, 137, 200);"> --}}
                <div class="card" style="border-radius: 1rem; background-color: rgb(118, 127, 232);">
                    <div class="card-body"
                        style="margin-bottom: 1.5rem; background-color: rgb(118, 127, 232); border-radius: 1rem;">
                        {{-- style="margin-bottom: 1.5rem; background-color: rgb(255, 255, 255); border-radius: 1rem;"> --}}
                        <div class="d-flex align-items-center">
                            <div style="font-size: 3rem; margin-right: 1rem;">
                                <img src="{{ asset('uploads/IconeSCMS/all.png') }}"
                                    style=" height: 50px; width: 100%; margin-top: 3rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <div>
                                <h5 class="card-title font-weight-bolder" style="color: black;">
                                    মোট এন্ট্রিকৃত মামলার সংখ্যা (<span
                                        class="count-numbers"><?= en2bn($total_case) ?></span>)
                                </h5>
                                <div class="case-info">

                                    <span class="count-item"
                                        style="display: flex; justify-content: space-between; align-items: center; font-size:17px;">
                                        <a href="{{ route('cabinet.case.highcourt') }}" class="hover-effect"
                                            style="text-decoration: none; color: black;">
                                            হাইকোর্ট বিভাগে মোট মামলা
                                        </a>
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
            </div>



            <!-- Card 2 -->
            <div class="col-md-6 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: rgb(219, 100, 100);">
                    {{-- <div class="card-body" style="background-color: rgb(135, 222, 170); border-radius: 1rem;"> --}}
                    <div class="card-body" style="background-color: rgb(219, 100, 100); border-radius: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/7.png') }}"
                                    style=" height: 50px; width: 100%; margin-top: 2rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="card-title font-weight-bolder" style="color:rgb(0, 0, 0);">
                                    কার্যক্রম গ্রহণের জন্য অপেক্ষমান মামলার তালিকা
                                </h5>
                                <!-- List of pending cases -->
                                <div style="font-size: 16px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="#" style="color: black;">আপিল দায়েরের জন্য পেন্ডিং</a>
                                        <span class="count-numbers"
                                            style="margin-left:10rem; color: black">{{ en2bn($appealPending) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourt.sentToSolicitorPending') }}" style=" color: black;">জবাব প্রেরণের জন্য পেন্ডিং</a>
                                        <span class="count-numbers"
                                            style="margin-left:10rem; color: black">{{ en2bn($sent_to_solicitor_case) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourt.pendingPostpondOrder') }}" style=" color: black;">স্থগিতাদেশ সম্পর্কিত
                                            পেন্ডিং</a>
                                        <span class="count-numbers"
                                            style="margin-left:10rem; color: black">{{ en2bn($pendingPostpondOrder) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Card 3 -->
            <div class="col-md-4 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: rgb(130, 208, 130);">
                    {{-- <div class="card" style="border-radius: 1rem; background-color: rgb(155, 229, 240);"> --}}
                    {{-- <div class="card-body" style="margin-bottom: 1.5rem; background-color: rgb(155, 229, 240); border-radius: 1rem;"> --}}
                    <div class="card-body"
                        style="margin-bottom: 1.5rem; background-color: rgb(130, 208, 130); border-radius: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                {{-- <i class="fas fa-exclamation-circle text-danger fa-fw mb-md-0 mr-3"
                                        style="font-size: 4rem;"></i> --}}
                                <img src="{{ asset('uploads/IconeSCMS/danger.png') }}"
                                    style=" height: 60px; width: 100%; margin-top: 2rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="card-title font-weight-bolder" style="color:rgb(0, 0, 0);">
                                    গুরুত্বপূর্ণ মামলা সমূহ
                                </h5>
                                <!-- Links -->
                                <div style="font-size: 18px;">
                                    <a href="{{ route('cabinet.case.highcourtAppealMostImportantCase') }}"
                                        style="color: black;">অতি গুরুত্বপূর্ণ মামলা</a><br>
                                    <a href="#" style=" color: black;">গুরুত্বপূর্ণ মামলা</a><br>
                                    <a href="#" style=" color: black;">কনটেম্পট মামলা</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-4 mb-4">
                {{-- <div class="card" style="border-radius: 1rem; background-color: rgb(215, 227, 89);"> --}}
                <div class="card" style="border-radius: 1rem; background-color: rgb(149, 85, 213);">
                    {{-- <div class="card-body" style="margin-bottom: 3.5rem; background-color: rgb(215, 227, 89); border-radius: 1rem;"> --}}
                    <div class="card-body"
                        style="margin-bottom: 3.5rem; background-color: rgb(149, 85, 213); border-radius: 1rem;">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                {{-- <i class="fas fa-calendar-alt text-primary fa-fw mb-md-0 mr-3"
                                        style="font-size: 4rem;"></i> --}}
                                <img src="{{ asset('uploads/IconeSCMS/Couse_List (1).png') }}"
                                    style=" height: 50px; width: 80%; margin-top: 4rem; " alt="Logo"
                                    class="brand-image">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="card-title font-weight-bolder" style="color:rgb(0, 0, 0);">
                                    কজ লিস্ট
                                </h5>
                                <!-- Links -->
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

            <div class="col-md-4 mb-4">
                {{-- <div class="card" style="border-radius: 1rem; background-color: rgb(128, 217, 131);"> --}}
                <div class="card" style="border-radius: 1rem; background-color: rgb(118, 127, 232);">
                    <div class="card-body" style="background-color: rgb(118, 127, 232); border-radius: 1rem;">
                        {{-- <div class="card-body" style="background-color: rgb(161, 142, 213); border-radius: 1rem;"> --}}
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/1320101.webp') }}"
                                    style=" height: 60px; width: 100%; margin-bottom: -1rem; " alt="Logo"
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

        </div>
        <div class="row mt-4">
            <!-- Card 5 -->
            <div class="col-md-6 mb-4">
                {{-- <div class="card" style="border-radius: 1rem; background-color: rgb(89, 190, 104);"> --}}
                <div class="card" style="border-radius: 1rem; background-color: rgb(219, 100, 100);">
                    {{-- <div class="card-body" style="background-color: rgb(89, 190, 104); border-radius: 1rem; margin-bottom: 1rem"> --}}
                    <div class="card-body"
                        style="background-color: rgb(219, 100, 100); border-radius: 1rem; margin-bottom: 1rem">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 1rem;">
                                {{-- <i class="fas fa-university text-dark fa-fw mb-md-0 mr-3"
                                        style="font-size: 4rem;"></i> --}}
                                <img src="{{ asset('uploads/IconeSCMS/2689736.png') }}"
                                    style=" height: 80px; width: 100%; " alt="Logo" class="brand-image">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="card-title font-weight-bolder"
                                    style="color:rgb(0, 0, 0); margin-bottom: 1.5rem;">
                                    হাইকোর্ট বিভাগ
                                </h5>
                                <!-- List of pending cases -->
                                <div style="font-size: 1.4rem">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourt.running') }}"
                                            style="text-decoration: none; color: black;">চলমান মামলা</a>
                                        <span class="count-numbers"
                                            style="margin-left: 12rem; color: black">{{ en2bn($running_high_court_case) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourt.complete') }}"
                                            style="text-decoration: none; color: black;">নিষ্পত্তিকৃত মামলা</a>
                                        <span class="count-numbers"
                                            style="margin-left: 8rem; color: black">{{ en2bn($final_high_court_case) }}</span>
                                    </div>

                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourtNotAgainstGov') }}" style="text-decoration: none; color: black;">সরকারের-পক্ষে</a>
                                        <span class="count-numbers" style="margin-left: 8rem; color: black">
                                            {{ en2bn($highcourt_not_against_gov) }}
                                        </span>
                                    </div>

                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <a href="{{ route('cabinet.case.highcourtAgainstGov') }}"
                                            style="text-decoration: none; color: black;">সরকারের-বিপক্ষে</a>
                                        <span class="count-numbers" style="margin-left: 8rem; color: black">
                                            {{ en2bn($highcourt_against_gov) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col-md-6 mb-4">
                <div class="card" style="border-radius: 1rem; background-color: rgb(130, 208, 130);">
                    {{-- <div class="card" style="border-radius: 1rem; background-color: rgb(142, 180, 228);"> --}}
                    <div class="card-body"
                        style="display: flex; flex-direction: column; background-color: rgb(130, 208, 130); border-radius: 1rem;">
                        {{-- <div class="card-body" style="display: flex; flex-direction: column; background-color: rgb(142, 180, 228); border-radius: 1rem;"> --}}
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 1rem;">
                                {{-- <i class="fas fa-balance-scale text-dark fa-fw mb-md-0 mr-3"
                                        style="font-size: 4rem;"></i> --}}
                                <img src="{{ asset('uploads/IconeSCMS/2689736.png') }}"
                                    style=" height: 80px; width: 100%; " alt="Logo" class="brand-image">
                            </div>
                            <!-- Content -->
                            <div style="font-size: 18px;">
                                <h5 class="card-title font-weight-bolder" style="color: rgb(0, 0, 0);">আপিল বিভাগ</h5>
                                <div class="flex-grow-1" style="display: flex; flex-direction: column;">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.running') }}"
                                            style="text-decoration: none; color: black;">চলমান মামলা</a>
                                        <span class="count-numbers"
                                            style="margin-left: 12rem; color: black">{{ en2bn($running_appeal_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.complete') }}"
                                            style="text-decoration: none; color: black;">নিষ্পত্তিকৃত মামলা</a>
                                        <span class="count-numbers"
                                            style="margin-left: 8rem; color: black">{{ en2bn($final_appeal_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.notAgainstGov') }}" style="text-decoration: none; color: black;">সরকারের-পক্ষে</a>
                                        <span class="count-numbers" style="margin-left: 8rem; color: black">{{ en2bn($appeal_not_against_gov) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.againstGov') }}"
                                            style="text-decoration: none; color: black;">সরকারের-বিপক্ষে</a>
                                        <span class="count-numbers" style="margin-left: 8rem;   color: black">{{ en2bn($appeal_against_gov) }}</span>
                                    </div>
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

        <div class="row mt-4">
            <!-- Card 7 -->
            <div class="col-md-12 mb-4 ">

                <table class="table table-hover mb-6 font-size-h5">
                    <thead class="bg-light-primary font-size-h6">
                        <tr>
                            {{-- <th scope="col" width="30"></th> --}}
                            <th scope="col"> বিভাগ</th>
                            <th scope="col">হাইকোর্ট বিভাগে সরকারি স্বার্থ সংশ্লিষ্ট চলমান
                                 মামলা</th>
                            <th scope="col">আপিল বিভাগে সরকারি স্বার্থ সংশ্লিষ্ট চলমান মামলা</th>
                            <th scope="col">আপিলের জন্য পেন্ডিং</th>
                            <th scope="col">জবাব পেন্ডিং</th>
                            <th scope="col">স্থগিতাদেশ অন্তর্বর্তীকালীন পেন্ডিং মামলা</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $index = 1;
                        @endphp
                        @foreach ($ministry as $key => $val)
                            <tr>
                                {{-- <td >{{ en2bn($key + $ministry->firstItem()) }}</td> --}}
                                <td class="font-weight-bolder">{{ $val->office_name_bn }}</td>
                                <td align="center">{{ en2bn($val->highcourt_running_case) }}</td>
                                <td align="center">{{ en2bn($val->appeal_running_case) }}</td>
                                <td align="center">{{ en2bn($val->against_gov) }}</td>
                                <td align="center">{{ en2bn($val->result_sending_count) }}</td>
                                <td align="center">{{ en2bn($val->against_postponed_count) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {!! $ministry->links() !!}
                </div>
            </div>
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
                {{ $appealPending  }},
                {{ $sent_to_solicitor_case }},
                {{ $pendingPostpondOrder }}
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
