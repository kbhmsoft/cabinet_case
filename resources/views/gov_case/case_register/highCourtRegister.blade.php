@php
   $user = Auth::user();
   $roleID = Auth::user()->role_id;
@endphp

@extends('layouts.cabinet.cab_default')

@section('content')

<!-- <style type="text/css">
   .tg {border-collapse:collapse;border-spacing:0;width: 100%;}
   .tg td{border-color:black;border-style:solid;border-width:1px;font-size:14px;overflow:hidden;padding:6px 5px;word-break:normal;}
   .tg th{border-color:black;border-style:solid;border-width:1px;font-size:14px;font-weight:normal;overflow:hidden;padding:6px 5px;word-break:normal;}
   .tg .tg-nluh{background-color:#dae8fc;border-color:#cbcefb;text-align:left;vertical-align:top}
   .tg .tg-19u4{background-color:#ecf4ff;border-color:#cbcefb;font-weight:bold;text-align:right;vertical-align:top}
</style> -->

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
     
          <div class="container">
              <div class="row">
                  <div class="col-10"><h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3></div>
                  
                 
              </div>
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
              <table class="table ">
                <thead>
                  <tr class="bg-light-primary">
                    <th scope="row">মামলা নম্বর/ক্যাটাগরি</th>
                    <th scope="row">পিটিশনারের নাম ও ঠিকানা</th>
                    <th scope="row">মুল প্রতিবাদি দপ্তর-সংস্থা</th>
                    <th scope="row">মামলার বিষয়বস্তু</th>
                    <th scope="row">রুল ইস্যুর তারিখ</th>
                    <th scope="row">দফাওয়ারি জবাব প্রেরণের তারিখ</th>
                    <th scope="row">এটর্নি জেনারেল অফিসে প্রেরণের তারিখ</th>
                    <th scope="row">সংশ্লিষ্ট আদালতে জবাব দাখিলের তারিখ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $case->case_no ?? '-'}}/{{ $case->case_category->name_bn ?? '-'}}</td>
                    <td>
                      @foreach ($caseBadi as $key=>$badi)
                         
                            {{en2bn($key+1)}}. {{ $badi->name }} ,{{ $badi->address }}<br>
                         
                      @endforeach

                    </td>
                    <td>
                      @foreach ($caseMainBibadi as $key=>$bibadi)
                        {{en2bn($key+1)}}.{{ $bibadi->ministry->office_name_bn ?? '-' }}<br>
                      @endforeach
                    </td>
                    <td>{{ $case->subject_matter }}</td>
                    <td>{{ $case->date_issuing_rule_nishi ? en2bn($case->date_issuing_rule_nishi) : '-'}}</td>
                    <td>{{ $case->result_sending_date ? en2bn($case->result_sending_date) : '-'}}</td>
                    <td>{{ $case->result_sending_date_solisitor_to_ag ? en2bn($case->result_sending_date_solisitor_to_ag) : '-'}}</td>
                    <td>{{ $case->reply_submission_date ? en2bn($case->reply_submission_date) : '-'}}</td>
                  </tr>
                </tbody>
              </table>
              <table class="table ">
                <thead>
                  <tr class="bg-light-primary">
                    <th scope="row">শুনানির তারিখ সমুহ</th>
                    <th scope="row">রায়/আদেশ ঘোষণার তারিখ</th>
                    <th scope="row">রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ</th>
                    <th scope="row">রায়ের নকল প্রাপ্তির তারিখ</th>
                    <th scope="row">প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক ও তারিখ</th>
                    <th scope="row">আপিল মামলা নম্বর ও দায়েরের তারিখ</th>
                    <th scope="row">আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ</th>
                    <th scope="row">প্রযোজ্য ক্ষেত্রে কন্টেম্পট মামলা নম্বর ও রুল ইস্যুর তারিখ</th>
                    <th scope="row">কন্টেম্পট মামলায় জবাব প্রেরণের তারিখ</th>
                    <th scope="row">কন্টেম্পট মামলার আদেশ</th>
                    <th scope="row">অন্যান্য পদক্ষেপ</th>
                  </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    @forelse ($hearings as $key=> $row)
                      {{ en2bn($key+1) }}. {{ en2bn($row->hearing_date) }}
                    @empty
                          শুনানির কোন নোটিশ পাওয়া যাইনি
                    @endforelse
                  </td>
                  <td>{{ $case->result_date ? en2bn($case->result_date) : '-'}}</td>
                  <td>{{ $case->result_copy_asking_date ? en2bn($case->result_copy_asking_date) : '-'}}</td>
                  <td>{{ $case->result_copy_reciving_date ? en2bn($case->result_copy_reciving_date) : '-'}}</td>
                  <td>{{ $case->appeal_requesting_memorial ?? '-' }},{{ $case->appeal_requesting_date ? en2bn($case->appeal_requesting_date) : '-'}}</td>
                  <td></td>
                  <td>{{ $case->reason_of_not_appealing ?? '-' }}</td>
                  <td>{{ $case->contempt_case_no ?? '-' }},{{ $case->contempt_case_isuue_date ? en2bn($case->contempt_case_isuue_date) : '-'}}</td>
                  <td>{{ $case->contempt_case_answer_sending_date ? en2bn($case->contempt_case_answer_sending_date) : '-'}}</td>
                  <td>{{ $case->contempt_case_order ?? '-' }}</td>
                  <td>{{ $case->others_action_detials ?? '-' }}</td>
                </tr>
              </tbody>
            </table>
            <table class="table ">
              <thead>
                  <tr class="bg-light-primary">
                    @if($case->postponed_details)
                      <th scope="row">স্থগিতাদেশের বিবরণ</th>
                    @endif
                    @if($case->gov_case_ref_id)
                       <th scope="row">পূর্বের মামলা নং </th>
                    @endif
                    @if($case->important_cause)
                       <th scope="row">গুরুত্বপূর্ণতার কারণ</th>
                    @endif
                    @if($case->interim_order)
                       <th scope="row">অন্তর্বর্তীকালীন আদেশের বিবরণ(যদি থাকে )</th>
                    @endif
                    @if($case->result_short_dtails)
                       <th scope="row"> চূড়ান্ত আদেশের সংক্ষিপ্ত বিবরণ ( যদি থাকে )</th>
                    @endif
                    @if(!empty($case->lost_reason))
                      <th scope="row">পরাজয়ের কারণ</th>
                    @endif
                    @if(!empty( $case->status))
                      <th scope="row">মামলার বর্তমান অবস্থান</th>
                    @endif
                  </tr>
              </thead>
              <tbody>
                <tr>
                    @if($case->postponed_details)
                      <td>{{ $case->postponed_details }}</td>
                    @endif
                    @if($case->gov_case_ref_id)
                      <td><a href="{{ route('cabinet.case.details', $case->gov_case_ref_id) }}" target="_blank">{{ $case->ref_gov_case_no }}</a></td>
                    @endif
                    @if($case->important_cause)
                      <td>{{ $case->important_cause }}</td>
                    @endif
                    @if($case->interim_order)
                      <td>{{ $case->interim_order }}</td>
                    @endif
                    @if($case->result_short_dtails)
                      <td>{{ $case->result_short_dtails }}</td>
                    @endif
                    @if (!empty($case->lost_reason))
                      <td>{{ $case->lost_reason ?? '-'}}</td>
                    @endif
                    @if(!empty( $case->status))
                      @if ($case->status == 1)
                        <td>{{ $case->case_status->status_name ?? '-' }}</td>
                      @elseif ($case->status == 3)
                        <td> আর্কাইভ !</td>
                      @endif
                    @endif
                </tr>
               </tbody>
            </table>
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

{{-- Scripts Section Related Page--}}
@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
<!--end::Page Scripts-->
@endsection


