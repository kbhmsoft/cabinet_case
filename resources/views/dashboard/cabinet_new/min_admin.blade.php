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

        .card {
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .count-numbers {
            font-weight: bold;
        }

        @media (max-width: 576px) {
            .count-numbers {
                font-size: 12px;
            }
        }
    </style>
@endsection


<div class="container">
    <div class="mt-4">

        <div class="row">
            <!-- Card 2 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 h-100" style="background-color: #f5f5f5;">
                    <div class="card-body d-flex flex-column justify-content-between"
                        style="border: 1px solid #CCCCCC; border-width: 1px 3px 3px 1px;">
                        <div class="d-flex align-items-center">
                            <!-- Icon -->
                            <div class="flex-shrink-0 me-3 mr-5">
                                <img src="{{ asset('uploads/IconeSCMS/7.png') }}" class="img-fluid"
                                    style="height: 50px;" alt="Logo">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="font-weight-bolder text-black mb-3">কার্যক্রম গ্রহণের জন্য অপেক্ষমান</h5>
                                <!-- Links -->
                                <div style="font-size: 13px;">
                                    <div class="d-flex justify-content-between">
                                        <a href="#" class="text-dark  flex-grow-1">আপিল দায়েরের জন্য পেন্ডিং</a>
                                        <span class="count-numbers text-black ml-10">{{ en2bn($appealPending) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourt.sentToSolicitorPending') }}"
                                            class="text-dark  flex-grow-1">জবাব প্রেরণের জন্য পেন্ডিং</a>
                                        <span
                                            class="count-numbers text-black ml-10">{{ en2bn($sent_to_solicitor_case) }}</span>
                                    </div>
                                    {{-- <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourt.pendingPostpondOrder') }}"
                                            class="text-dark  flex-grow-1">স্থগিতাদেশ সম্পর্কিত পেন্ডিং</a>
                                        <span class="count-numbers text-black ml-10">{{ en2bn($pendingPostpondOrder) }}</span>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 h-100" style="background-color: #f5f5f5;">
                    <div class="card-body d-flex flex-column justify-content-between"
                        style="border: 1px solid #CCCCCC; border-width: 1px 3px 3px 1px;">
                        <div class="d-flex align-items-center">
                            <!-- Icon -->
                            <div class="flex-shrink-0 me-3 mr-5">
                                <img src="{{ asset('uploads/IconeSCMS/danger.png') }}" class="img-fluid"
                                    style="height: 60px;" alt="Logo">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="font-weight-bolder text-black mb-3">গুরুত্বপূর্ণ মামলা সমূহ</h5>
                                <!-- Links -->
                                <div style="font-size: 15px;">
                                    <a href="{{ route('cabinet.case.highcourtAppealMostImportantCase') }}"
                                        class="text-black d-block">অতি গুরুত্বপূর্ণ মামলা</a>
                                    <a href="{{ route('cabinet.case.highcourtAppealImportantCase') }}"
                                        class="text-black d-block">গুরুত্বপূর্ণ মামলা</a>
                                    <a href="{{ route('cabinet.case.contemptCaseList') }}"
                                        class="text-black d-block">কনটেম্পট মামলা</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 h-100" style="background-color: #f5f5f5;">
                    <div class="card-body d-flex flex-column justify-content-between"
                        style="border: 1px solid #CCCCCC; border-width: 1px 3px 3px 1px;">
                        <div class="d-flex align-items-center">
                            <!-- Icon -->
                            <div class="flex-shrink-0 me-3 mr-5">
                                <img src="{{ asset('uploads/IconeSCMS/Couse_List (1).png') }}" class="img-fluid"
                                    style="height: 50px;" alt="Logo">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="font-weight-bolder text-black mb-3">কজ লিস্ট</h5>
                                <!-- Links -->
                                <div style="font-size: 15px;">
                                    <a href="https://www.supremecourt.gov.bd/web/indexn.php?page=bench_list.php&menu=00&div_id=2&lang="
                                        target="_blank" class="text-black d-block">হাইকোর্ট বিভাগ</a>
                                    <a href="https://www.supremecourt.gov.bd/web/indexn.php?page=bench_list_app.php&menu=01&div_id=1&lang="
                                        target="_blank" class="text-black d-block">আপিল বিভাগ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 h-100" style="background-color: #f5f5f5;">
                    <div class="card-body d-flex flex-column justify-content-between"
                        style="border: 1px solid #CCCCCC; border-width: 1px 3px 3px 1px;">
                        <div class="d-flex align-items-center">
                            <!-- Icon -->
                            <div class="flex-shrink-0 me-3 mr-5">
                                <img src="{{ asset('uploads/IconeSCMS/courthouse.png') }}" class="img-fluid"
                                    style="height: 50px;" alt="Logo">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="font-weight-bolder text-primary mb-3">হাইকোর্ট বিভাগ</h5>
                                <!-- Links -->
                                <div style="font-size: 15px;">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourt.running') }}"
                                            class="text-dark  flex-grow-1">চলমান মামলা</a>
                                        <span
                                            class="count-numbers text-black ml-10">{{ en2bn($running_high_court_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourt.complete') }}"
                                            class="text-dark  flex-grow-1">নিষ্পত্তিকৃত মামলা</a>
                                        <span
                                            class="count-numbers text-black ml-10">{{ en2bn($final_high_court_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourtNotAgainstGov') }}"
                                            class="text-dark  flex-grow-1">সরকারের-পক্ষে</a>
                                        <span
                                            class="count-numbers text-black ml-10">{{ en2bn($highcourt_not_against_gov) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.highcourtAgainstGov') }}"
                                            class="text-dark  flex-grow-1">সরকারের-বিপক্ষে</a>
                                        <span
                                            class="count-numbers text-black ml-10">{{ en2bn($highcourt_against_gov) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 h-100" style="background-color: #f5f5f5;">
                    <div class="card-body d-flex flex-column justify-content-between"
                        style="border: 1px solid #CCCCCC; border-width: 1px 3px 3px 1px;">
                        <div class="d-flex align-items-center">
                            <!-- Icon -->
                            <div class="flex-shrink-0 me-3 mr-5">
                                <img src="{{ asset('uploads/IconeSCMS/law.png') }}" class="img-fluid"
                                    style="height: 50px;" alt="Logo">
                            </div>
                            <!-- Content -->
                            <div>
                                <h5 class="font-weight-bolder text-primary mb-3">আপিল বিভাগ</h5>
                                <!-- Links -->
                                <div style="font-size: 15px;">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.running') }}"
                                            class="text-dark  flex-grow-1">চলমান মামলা</a>
                                        <span
                                            class="count-numbers text-black ml-10">{{ en2bn($running_appeal_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.complete') }}"
                                            class="text-dark  flex-grow-1">নিষ্পত্তিকৃত মামলা</a>
                                        <span class="count-numbers text-black ml-10">{{ en2bn($final_appeal_case) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.againstGov') }}"
                                            class="text-dark  flex-grow-1">সরকারের-বিপক্ষে</a>
                                        <span class="count-numbers text-black ml-10">{{ en2bn($appeal_against_gov) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('cabinet.case.appellateDivision.notAgainstGov') }}"
                                            class="text-dark  flex-grow-1">সরকারের-পক্ষে</a>
                                        <span
                                            class="count-numbers text-black ml-10">{{ en2bn($appeal_not_against_gov) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                <div class="card border-0 h-100" style="background-color: #f5f5f5;">
                    <div class="card-body d-flex flex-column" style="border-right: 3px solid #CCCCCC; border-bottom: 3px solid #CCCCCC; border-left: 1px solid #CCCCCC; border-top: 1px solid #CCCCCC; background-color: #f5f5f5;">
                        <div style="display: flex; align-items: center;">
                            <!-- Icon -->
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/1320101.webp') }}" style="height: 50px; width: 80%;" alt="Logo" class="brand-image">
                            </div>
                            <!-- Content -->
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

               <!-- Card 7 -->

            <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
                <div class="card border-0 h-100" style="background-color: #f5f5f5;">
                    <div class="card-body d-flex flex-column" style="border-right: 3px solid #CCCCCC; border-bottom: 3px solid #CCCCCC; border-left: 1px solid #CCCCCC; border-top: 1px solid #CCCCCC; background-color: #f5f5f5;">
                        <div style="display: flex; align-items: center;">
                            <div style="font-size: 3rem; margin-right: 2rem;">
                                <img src="{{ asset('uploads/IconeSCMS/1320101.webp') }}" style="height: 50px; width: 80%;" alt="Logo" class="brand-image">
                            </div>
                            <div>
                                <h5 class="fw-bold text-primary">প্রশাসনিক আপিল ট্রাইব্যুনাল</h5>
                                <div class="text-secondary ml-10">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('cabinet.case.appealAdministrativeTribrunal') }}"
                                            class="text-dark">চলমান মামলা</a>
                                        <span class="count-numbers ml-10">{{ en2bn($appealAdministrativeTribrunal) }}</span>
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
        </div>

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
                            {{-- <th scope="col">স্থগিতাদেশ অন্তর্বর্তীকালীন পেন্ডিং মামলা</th> --}}
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
                                {{-- <td align="center">{{ en2bn($val->against_postponed_count) }}</td> --}}
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
@endsection
