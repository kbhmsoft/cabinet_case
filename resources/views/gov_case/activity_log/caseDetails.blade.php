@php
   $user = Auth::user();
   $roleID = Auth::user()->role_id;
   $data = json_decode($caseActivityLog->new_data, true);
   // echo $data['court_id'];
//    print_r($data);
@endphp

@extends('layouts.cabinet.cab_default')
@section('content')
<style type="text/css">
   .tg {border-collapse:collapse;border-spacing:0;width: 100%;}
   .tg td{border-color:black;border-style:solid;border-width:1px;font-size:14px;overflow:hidden;padding:6px 5px;word-break:normal;}
   .tg th{border-color:black;border-style:solid;border-width:1px;font-size:14px;font-weight:normal;overflow:hidden;padding:6px 5px;word-break:normal;}
   .tg .tg-nluh{background-color:#dae8fc;border-color:#cbcefb;text-align:left;vertical-align:top}
   .tg .tg-19u4{background-color:#ecf4ff;border-color:#cbcefb;font-weight:bold;text-align:right;vertical-align:top}
</style>

<!--begin::Card-->
<div class="card card-custom">
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
         <div class="col-md-6">
            <h4 class="font-weight-bolder">সাধারণ তথ্য</h4>
            <table class="tg">
                <thead>
                    {{-- <tr>
                        <th class="tg-19u4" width="130">আদালতের নাম</th>
                        <td class="tg-nluh">
                            {{ App\Models\Court::where('id', $data['court_id'])->first()->court_name }}
                        </td>
                    </tr> --}}
                    {{-- {{dd($data)}} --}}
                    <tr>
                        <th class="tg-19u4" width="130">মামলার ক্যাটেগরি</th>
                        <td class="tg-nluh">
                            {{ App\Models\gov_case\GovCaseDivisionCategory::where('id', $data['case_category_id'])->first()->name_bn }}
                        </td>
                    </tr>
                    <tr>
                        <th class="tg-19u4" width="130">মামলার শ্রেণী/কেস-টাইপ</th>
                        <td class="tg-nluh">
                            {{ App\Models\gov_case\GovCaseDivisionCategoryType::where('id', $data['case_type_id'])->first()->name_en }}
                        </td>
                    </tr>
                    <tr>
                        <th class="tg-19u4">মামলা নং</th>
                        <td class="tg-nluh">{{ en2bn($data['case_no']) }}</td>
                    </tr>
                    <tr>
                        <th class="tg-19u4">মামলা রুজুর তারিখ</th>
                        <td class="tg-nluh">{{ en2bn($data['date_issuing_rule_nishi']) }}</td>
                    </tr>
                    <tr>
                        <th class="tg-19u4">মামলা বিষয়বস্তু(সংক্ষিপ্ত)</th>
                        <td class="tg-nluh">{{ en2bn($data['subject_matter']) }}</td>
                    </tr>


                    @if(!empty($data['ref_gov_case_no']))
                    <tr>
                        <th class="tg-19u4">পূর্বের মামলা নং</th>
                        <td class="tg-nluh"><a href="{{ route('case.details', $data['ref_gov_case_no']) }}" target="_blank">{{ $data['ref_gov_case_no'] }}</a></td>
                    </tr>
                    @endif
                    {{-- <tr>
                        <th class="tg-19u4">মামলার বর্তমান অবস্থান</th>
                        <td class="tg-nluh">
                            {{ DB::table('case_status')->select('status_name')->where('id', '=', $data['case_status_id'])->first()->status_name }} এর জন্য
                            <b>
                                {{App\Models\Role::where('id', $data['action_user_role_id'])->first()->name}}
                            </b>
                            এর কাছে
                        </td>
                    </tr> --}}
                    <tr>
                        <th class="tg-19u4">বর্তমান ষ্ট্যাটাস</th>
                        <td class="tg-nluh">
                            @if ($data['status'] == 1) নতুন চলমান! @elseif ($data['status'] == 2) আপিল করা হয়েছে! @elseif ($data['status'] == 3) সম্পাদিত ! @endif
                        </td>
                    </tr>
                    @if($data['postponed_details'])
                    <tr>
                        <th class="tg-19u4" width="130">স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে আপিল</th>
                        <td class="tg-nluh">
                            {{ $data['appeal_against_postpond_interim_order'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="tg-19u4" width="130">স্স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিলের তারিখ</th>
                        <td class="tg-nluh">
                            {{ $data['appeal_against_postpond_interim_order_date'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="tg-19u4" width="130">স্স্থগিতাদেশের বিবরণ</th>
                        <td class="tg-nluh">
                            {{ $data['postponed_details'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="tg-19u4" width="130">স্স্থগিতাদেশের আদেশের বিরুদ্ধে আপিলের বিবরণ</th>
                        <td class="tg-nluh">
                            {{ $data['appeal_against_postpond_interim_order_details'] }}
                        </td>
                    </tr>
                    <tr>
                        <th class="tg-19u4" width="130">অন্তর্বর্তীকালীন আদেশের বিবরণ</th>
                        <td class="tg-nluh">
                            {{ $data['interim_order_details'] }}
                        </td>
                    </tr>
            @endif
                </thead>
            </table>

          </div>
          @if($data['result'])
            <div class="col-md-6">
                  <h4 class="font-weight-bolder">মামলার ফলাফল</h4>
                  <table class="tg">
                     <thead>
                        <tr>
                            <th class="tg-19u4">ফলাফল</th>
                            <td class="tg-nluh">@if($data['result'] == 1) সরকারের পক্ষে! @elseif($data['result'] === 2) সরকারের বিপক্ষে! @else চলমান @endif</td>
                        </tr>
                        @if (!empty($data['govt_lost_reason']))
                          <tr>
                              <th class="tg-19u4">পরাজয়ের কারণ</th>
                              <td class="tg-nluh">{{ $data['govt_lost_reason'] }}</td>
                          </tr>
                        @endif
                        @if (!empty($data['is_appeal']))
                        <tr>
                            <th class="tg-19u4">সরকারের বিপক্ষে হলে আপিল করা হয়েছে কিনা</th>
                            <td class="tg-nluh">@if ($data['is_appeal'] == 1) হ্যা! @else না! @endif</td>
                        </tr>
                        @endif
                        @if (!empty($data['result_date']))
                        <tr>
                            <th class="tg-19u4">রায় ঘোষণার তারিখ</th>
                            <td class="tg-nluh">{{ $data['result_date'] }}</td>
                        </tr>
                        @endif
                        @if (!empty($data['result_copy_asking_date']))
                        <tr>
                            <th class="tg-19u4">রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ</th>
                            <td class="tg-nluh">{{ $data['result_copy_asking_date'] }}</td>
                        </tr>
                        @endif
                        @if (!empty($data['result_copy_reciving_date']))
                        <tr>
                            <th class="tg-19u4">রায়ের নকল প্রাপ্তির তারিখ</th>
                            <td class="tg-nluh">{{ $data['result_copy_reciving_date'] }}</td>
                        </tr>
                        @endif
                        @if (!empty($data['appeal_requesting_memorial']))
                        <tr>
                            <th class="tg-19u4">প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক</th>
                            <td class="tg-nluh">{{ $data['appeal_requesting_memorial'] }}</td>
                        </tr>
                        @endif
                        @if (!empty($data['appeal_requesting_date']))
                        <tr>
                            <th class="tg-19u4">প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ</th>
                            <td class="tg-nluh">{{ $data['appeal_requesting_date'] }}</td>
                        </tr>
                        @endif
                        @if (!empty($data['reason_of_not_appealing']))
                        <tr>
                            <th class="tg-19u4">আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ </th>
                            <td class="tg-nluh">{{ $data['reason_of_not_appealing'] }}</td>
                        </tr>
                        @endif
                     </thead>

                  </table>
            </div>
          @endif
       </div>

      <div class="row">

          <div class="col-md-6 mt-5">
                <h4 class="font-weight-bolder ">পিটিশনারের বিবরণ</h4>
                <table class="tg">
                   <thead>
                      <tr>
                         <th class="tg-19u4" width="10">ক্রম</th>
                         <th class="tg-19u4 text-center" width="200">নাম</th>
                         {{-- <th class="tg-19u4 text-center">পিতা/স্বামীর নাম</th> --}}
                         <th class="tg-19u4 text-center">ঠিকানা</th>
                      </tr>
                   </thead>
                   <tbody>
                    @for ( $i=0; $i < count($data['badi']); $i++)
                        <tr>
                            <td class="tg-nluh">{{en2bn($i+1)}}.</td>
                            <td class="tg-nluh text-center">{{ $data['badi'][$i]['name'] }}</td>
                            {{-- <td class="tg-nluh">{{ $data['badi'][$i]['spouse_name'] }}</td> --}}
                            <td class="tg-nluh text-center">{{ $data['badi'][$i]['address'] }}</td>
                        </tr>
                    @endfor
                   </tbody>
                </table>

                <br>
                <h4 class="font-weight-bolder">রেস্পন্ডেন্টের বিবরণ</h4>
                <table class="tg">
                   <thead>
                      <tr>
                         <th class="tg-19u4" width="10">ক্রম</th>
                         <th class="tg-19u4 text-center" width="200">নাম</th>
                         <th class="tg-19u4 text-center" width="200">ধরণ</th>
                      </tr>
                   </thead>
                   <tbody>
                    @for ( $i=0; $i < count($data['bibadi']); $i++)
                        <tr>
                            <td class="tg-nluh">{{en2bn($i+1)}}.</td>
                            <td class="tg-nluh text-center">{{ App\Models\gov_case\GovCaseOffice::where('id', $data['bibadi'][$i]['respondent_id'])->first()->office_name_bn }}</td>
                            <td class="tg-nluh text-center">
                              @if($data['bibadi'][$i]['is_main_bibadi'] == 1)
                                মুল রেস্পন্ডেন্ট
                              @else
                                অন্যান্য রেস্পন্ডেন্ট
                              @endif
                            </td>
                        </tr>
                    @endfor
                   </tbody>
                </table>
          </div>
          @if($data['result_sending_date'])
            <div class="col-md-6 mt-5">
                <h4 class="font-weight-bolder ">পদক্ষেপের বিবরণ</h4>
                <table class="tg">
                   <thead>
                      <tr>
                        <th class="tg-19u4">দফাওয়ারি জবাব সলিসিটর অনুবিভাগে প্রেরণের তারিখ</th>
                        <td class="tg-nluh">{{ $data['result_sending_date'] }}</td>
                      </tr>
                      @if($data['result_sending_memorial'])
                        <tr>
                          <th class="tg-19u4">দফাওয়ারি জবাব সলিসিটর অনুবিভাগে প্রেরণের স্মারক</th>
                          <td class="tg-nluh">{{ $data['result_sending_memorial'] }}</td>
                        </tr>
                      @endif
                      @if($data['result_sending_date_solisitor_to_ag'])
                        <tr>
                          <th class="tg-19u4">সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের তারিখ</th>
                          <td class="tg-nluh">{{ $data['result_sending_date_solisitor_to_ag'] }}</td>
                        </tr>
                      @endif
                      @if($data['result_sending_memorial_solisitor_to_ag'])
                        <tr>
                          <th class="tg-19u4">সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের স্মারক</th>
                          <td class="tg-nluh">{{ $data['result_sending_memorial_solisitor_to_ag'] }}</td>
                        </tr>
                      @endif
                      @if($data['reply_submission_date'])
                        <tr>
                          <th class="tg-19u4">সংশ্লিষ্ট আদালতে জবাব দাখিলের তারিখ</th>
                          <td class="tg-nluh">{{ $data['reply_submission_date'] }}</td>
                        </tr>
                      @endif
                      @if($data['tamil_requesting_memorial'])
                        <tr>
                          <th class="tg-19u4">প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের স্মারক</th>
                          <td class="tg-nluh">{{ $data['tamil_requesting_memorial'] }}</td>
                        </tr>
                      @endif
                      @if($data['tamil_requesting_date'])
                        <tr>
                          <th class="tg-19u4">প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের তারিখ</th>
                          <td class="tg-nluh">{{ $data['tamil_requesting_date'] }}</td>
                        </tr>
                      @endif
                      @if($data['contempt_case_no'])
                        <tr>
                          <th class="tg-19u4">প্রযোজ্য ক্ষেত্রে কন্টেম্পট মামলা নম্বর</th>
                          <td class="tg-nluh">{{ $data['contempt_case_no'] }}</td>
                        </tr>
                      @endif
                      @if($data['contempt_case_isuue_date'])
                        <tr>
                          <th class="tg-19u4">কন্টেম্পট মামলা রুল ইস্যুর তারিখ </th>
                          <td class="tg-nluh">{{ $data['contempt_case_isuue_date'] }}</td>
                        </tr>
                      @endif
                      @if($data['contempt_case_answer_sending_date'])
                        <tr>
                          <th class="tg-19u4">কন্টেম্পট মামলার জবাব প্রেরণের তারিখ </th>
                          <td class="tg-nluh">{{ $data['contempt_case_answer_sending_date'] }}</td>
                        </tr>
                      @endif
                   </thead>
                </table>
            </div>
          @endif
       </div>
   <br>
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


