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
    <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
        <div class="row">
            <div class="container card">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('min_wise_list.print') }}" target="_blank"
                     class="btn btn-info "><i class="fas fa-print" aria-hidden="true"></i></a>
                 </div>
                <div class="card-body">
                    <table class="table table-hover mb-6 font-size-h5">
                        <thead class="thead-light font-size-h6">
                            <tr>
                                <th scope="col" width="30">#</th>
                                <th scope="col">অফিসের নাম</th>
                                @if (Auth::user()->role_id == 27)
                                    <th scope="col">চলমান মামলা</th>
                                @endif
                                <th scope="col">হাইকোর্ট বিভাগে চলমান মামলা</th>
                                <th scope="col">আপিল বিভাগে চলমান মামলা</th>
                                <th scope="col">সরকারের বিপক্ষে আপিলের জন্য পেন্ডিং</th>
                                <th scope="col">জবাব পেন্ডিং</th>
                                <th scope="col">স্থগিতাদেশ অন্তর্বর্তীকালীন পেন্ডিং মামলা</th>
                                {{-- <th scope="col">মোট এন্ট্রিকৃত মামলার সংখ্যা </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ministry as $key => $val)
                                <tr>
                                    <td>{{ en2bn($key + 1) }}</td>
                                    <td>
                                        <h4><a
                                                href="{{ route('cabinet.case.ministryWiseData', $val->doptor_office_id) }}">{{ $val->office_name_bn }}</a>
                                        </h4>
                                    </td>
                                    @if (Auth::user()->role_id == 27)
                                        <td align="center">{{ en2bn($val->total_running_case) }}</td>
                                    @endif
                                    <td align="center">{{ en2bn($val->highcourt_running_case) }}</td>
                                    <td align="center">{{ en2bn($val->appeal_running_case) }}</td>
                                    <td align="center">{{ en2bn($val->against_gov) }}</td>
                                    <td align="center">{{ en2bn($val->result_sending_count) }}</td>
                                    <td align="center">{{ en2bn($val->against_postponed_count) }}</td>
                                    {{-- <td align="center">{{ en2bn($total_case) }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- <div class="d-flex justify-content-center">
                      {!! $ministry->links() !!}
                   </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
