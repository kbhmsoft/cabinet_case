@php
$user = Auth::user();
$roleID = Auth::user()->role_id;
@endphp

@extends('layouts.cabinet.cab_default')

@section('content')

    <style type="text/css">
        .tg {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }

        .tg td {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            font-size: 14px;
            overflow: hidden;
            padding: 6px 5px;
            word-break: normal;
        }

        .tg th {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            font-size: 14px;
            font-weight: normal;
            overflow: hidden;
            padding: 6px 5px;
            word-break: normal;
        }

        .tg .tg-nluh {
            background-color: #dae8fc;
            border-color: #cbcefb;
            text-align: left;
            vertical-align: top
        }

        .tg .tg-19u4 {
            background-color: #ecf4ff;
            border-color: #cbcefb;
            font-weight: bold;
            text-align: right;
            vertical-align: top
        }

    </style>

    <!--begin::Card-->
    <div id="DivIdToPrint" class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
            </div>
        </div>
        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    {{-- <a onclick='printDiv();' class="align-right btn btn-primary float-right" href="">Print</a> --}}
                    <a class="align-right btn btn-primary float-right" href="{{ route('case_audit.caseActivityPDFlog', $case->id) }}">Print</a>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <h5><span class="font-weight-bolder">মামলা নং: </span>{{ en2bn($case->case_no) }}</h5>
                    {{-- <h5><span class="font-weight-bolder">আদালতের নাম: </span> {{ $case->court->court_name }}</h5> --}}
                    
                </div>
                <div class="col-md-4">
                    <h5>
                        <span class="font-weight-bolder">মামলার ফলাফল:</span>
                        @if ($case->case_result == '1')
                            জয়!
                        @elseif($case->case_result == '0')
                            পরাজয়!
                        @else
                            চলমান
                        @endif
                    </h5>
                    <h5>
                        <span class="font-weight-bolder">বাদী:</span>
                        @if (count($caseBadi) == 1)
                            @foreach ($caseBadi as $badi)
                                {{ $badi->name }}
                            @endforeach
                        @else
                            @foreach ($caseBadi as $key => $badi)
                                <p class="ml-4">{{ $badi->name == null ? '' : en2bn($key+1) . '. ' . $badi->name  }}</p>
                            @endforeach
                        @endif
                    </h5>
                    <h5>
                        <span class="font-weight-bolder">ঠিকানা:</span>
                        @if (count($caseBadi) == 1)
                            @foreach ($caseBadi as $badi)
                                {{ $badi->address }}
                            @endforeach
                        @else
                            @foreach ($caseBadi as $key => $badi)
                                <p class="ml-4">{{ $badi->address  == null ? '' : en2bn($key+1) . '. ' . $badi->address }}</p>
                            @endforeach
                        @endif
                    </h5>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-2"></div>
                <div class="col-md-8 my-5">
                    <table class="tg">
                        <thead>
                            <tr>
                                <th class="font-weight-bolder">তারিখ ও সময়</th>
                                <th class="font-weight-bolder">ব্যবহারকারীর নাম</th>
                                <th class="font-weight-bolder">ব্যবহারকারীর পদবি</th>
                                <th class="font-weight-bolder">অ্যাক্টিভিটি</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($caseActivityLogs as $caseActivityLog)
                            @php
                            $data = json_decode($caseActivityLog->new_data, true);

                            @endphp
                            <tr>
                                <td>{{ en2bn($caseActivityLog->created_at)}}</td>
                                <td>{{ $caseActivityLog->user->name ?? '-'}}</td>
                                <td>{{ $caseActivityLog->role->name ?? '-'}}</td>
                                <td>
                                    @if ( $caseActivityLog->message == 'নতুন মামলা রেজিস্ট্রেশন করা হয়েছে')
                                        <h5>
                                            {{ $caseActivityLog->message ?? '-'}}
                                            <a href="{{ route('cabinet.case_audit.reg_case_details', $caseActivityLog->id) }}" target="_blank" class="btn btn-primary btn-sm float-right">{{ 'বিস্তারিত দেখুন' }}</a>
                                        </h5>
                                        @elseif ( $caseActivityLog->message == 'মামলার তথ্য হালনাগাদ করা হয়েছে')
                                        <h5>
                                            {{ $caseActivityLog->message ?? '-'}}
                                            <a href="{{ route('cabinet.case_audit.reg_case_details', $caseActivityLog->id) }}" target="_blank" class="btn btn-primary btn-sm float-right">{{ 'বিস্তারিত দেখুন' }}</a>
                                        </h5>
                                        @elseif ( $caseActivityLog->message == 'সরকারের বিপক্ষে রায় হওয়া মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে')
                                        <h5>
                                            {{ $caseActivityLog->message ?? '-'}}
                                            <a href="{{ route('cabinet.case_audit.against_gov_case_log_details', $caseActivityLog->id) }}" target="_blank" class="btn btn-primary btn-sm float-right">{{ 'বিস্তারিত দেখুন' }}</a>
                                        </h5>
                                        @elseif ( $caseActivityLog->message == 'সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে')
                                        <h5>
                                            {{ $caseActivityLog->message ?? '-'}}
                                            <a href="{{ route('cabinet.case_audit.sent_to_solcase_log_details', $caseActivityLog->id) }}" target="_blank" class="btn btn-primary btn-sm float-right">{{ 'বিস্তারিত দেখুন' }}</a>
                                        </h5>
                                        @elseif ( $caseActivityLog->message == 'সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে')
                                        <h5>
                                            {{ $caseActivityLog->message ?? '-'}}
                                            <a href="{{ route('cabinet.case_audit.sent_to_ag_from_solcase_log_details', $caseActivityLog->id) }}" target="_blank" class="btn btn-primary btn-sm float-right">{{ 'বিস্তারিত দেখুন' }}</a>
                                        </h5>
                                        @elseif ( $caseActivityLog->message == 'স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান মামলার তথ্য সফলভাবে হালনাগাদ করা হয়েছে')
                                        <h5>
                                            {{ $caseActivityLog->message ?? '-'}}
                                            <a href="{{ route('cabinet.case_audit.appeal_against_postpond_order_case_log_details', $caseActivityLog->id) }}" target="_blank" class="btn btn-primary btn-sm float-right">{{ 'বিস্তারিত দেখুন' }}</a>
                                        </h5>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="4">কোন নিরীক্ষা পাওয়া যায়নি</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Card-->
@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
    <!--end::Page Scripts-->

    <script>
        function printDiv()
{

  var divToPrint=document.getElementById('DivIdToPrint');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

}
    </script>


@endsection
