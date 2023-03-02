@php
   $user = Auth::user();
   $roleID = Auth::user()->role_id;
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
      {{-- <div class="card-title"> --}}
          <div class="container">
              <div class="row">
                  <div class="col-10"><h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3></div>
                  {{-- <div class="col-8">fdsafsad</div> --}}
                  {{-- <div class="col-2"><a href="{{ route('messages_group') }}" class="btn btn-primary float-right">Message</a></div> --}}
                 <!--  <div class="col-2">
                      @if(Auth::user()->role_id == 2)
                      <a href="{{ route('messages_group') }}?c={{ $case->id }}" class="btn btn-primary float-right">বার্তা</a>
                        @endif
                    </div> -->
              </div>
          </div>
         {{-- <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>

         <table>
             <tr align="right">
                 <th>
                     <a  href="" class="btn btn-primary float-right">Message</a>

                 </th>
             </tr>
         </table> --}}
      {{-- </div> --}}
      @if (Auth::user()->role_id == 5 || Auth::user()->role_id == 21 || Auth::user()->role_id == 22 || Auth::user()->role_id == 24)
         @if($case->action_user_role_id == Auth::user()->role_id )
            @if($case->status == 1)
               <div class="card-toolbar">
                  <a href="{{ route('rmcase.edit', $case->id) }}" class="btn btn-sm btn-primary font-weight-bolder">
                     <i class="la la-edit"></i>মামলা সংশোধন করুন
                  </a>
               </div>
            @endif
         @endif
      @endif
   </div>
   <div class="card-body">
      @if ($message = Session::get('success'))
      <div class="alert alert-success">
         {{ $message }}
      </div>
      @endif

      <div class="row">
         <div class="col-md-6">
            <table class="table table-striped border">
               <thead>
                  <tr>
                      <th class="h3" scope="col" colspan="2">সাধারণ তথ্য</th>
                  </tr>
              </thead>
               <tbody>
                     <tr>
                        <th scope="row">মামলা নং</th>
                        <td>{{ $case->case_no ?? '-'}}</td>
                     </tr>
                     <tr>
                        <th scope="row">রুল ইস্যুর তারিখ</th>
                        <td>{{ en2bn($case->date_issuing_rule_nishi) ?? '-'}}</td>
                     </tr>
                     <tr>
                        <th scope="row">বিষয়বস্তু(সংক্ষিপ্ত)</th>
                        <td>{{ $case->subject_matter }}</td>
                     </tr>
                     @if($case->postponed_details)
                        <tr>
                           <th scope="row">স্থগিতাদেশের বিবরণ</th>
                           <td>{{ $case->postponed_details }}</td>
                        </tr>
                     @endif
                     @if($case->gov_case_ref_id)
                        <tr>
                           <th scope="row">পূর্বের মামলা নং </th>
                           <td><a href="{{ route('cabinet.case.details', $case->gov_case_ref_id) }}" target="_blank">{{ $case->ref_gov_case_no }}</a></td>
                        </tr>
                     @endif
                     @if($case->important_cause)
                        <tr>
                           <th scope="row">গুরুত্বপূর্ণতার কারণ</th>
                           <td>{{ $case->important_cause }}</td>
                        </tr>
                     @endif
                     <tr>
                     @if($case->interim_order)
                        <tr>
                           <th scope="row">অন্তর্বর্তীকালীন আদেশের বিবরণ(যদি থাকে )</th>
                           <td>{{ $case->interim_order }}</td>
                        </tr>
                     @endif
                     @if($case->result_short_dtails)
                        <tr>
                           <th scope="row"> চূড়ান্ত আদেশের সংক্ষিপ্ত বিবরণ ( যদি থাকে )</th>
                           <td>{{ $case->result_short_dtails }}</td>
                        </tr>
                     @endif


                     <tr>
                        <th scope="row">ফলাফল</th>
                           <td>
                              @if($case->result == '1')
                              জয়!
                              @elseif($case->result == '0')
                              পরাজয়!
                              @else
                              চলমান
                              @endif
                           </td>
                        {{-- @dd($case->result) --}}
                     </tr> 

                     @if (!empty($case->lost_reason))
                     <tr>
                        <th scope="row">পরাজয়ের কারণ</th>
                        <td>{{ $case->lost_reason ?? '-'}}</td>
                     </tr>
                     @endif
                     <tr>
                        <th scope="row">মামলায় হেরে গিয়ে কি আপিল করা হয়েছে</th>
                        <td>@if ($case->is_lost_appeal == 2)
                                                হ্যা!
                                             @else
                                                না!
                                             @endif
                        </td>
                     </tr>
                     @if(!empty( $case->status))
                     <tr>
                        <th scope="row">মামলার বর্তমান অবস্থান</th>
                          @if ($case->status == 1)
                            <td>{{ $case->case_status->status_name ?? '-' }}</td>
                          @elseif ($case->status == 3)
                            <td> আর্কাইভ !</td>
                          @endif
                     </tr>
                     @endif
                     <tr>
                        <th scope="row">মন্তব্য</th>
                        <td>{{ $case->comments ?? '-' }}</td>
                     </tr>
                     <tr>
                        <th scope="row">বর্তমান ষ্ট্যাটাস</th>
                        <td> @if ($case->status == 1)
                                                নতুন চলমান!
                                             @elseif ($case->status == 2)
                                                আপিল করা হয়েছে!
                                             @elseif ($case->status == 3)
                                                সম্পাদিত !
                                             @endif

                        </td>
                     </tr>

               </tbody>
            </table>
         </div>
         <div class="col-md-6">
               <table class="table table-striped border">
                  <thead>
                     <tr>
                         <th class="h3" scope="col" colspan="4">বাদীর বিবরণ</th>
                     </tr>
                     <tr class="bg-light-primary">
                        <th scope="row" width="10">ক্রম</th>
                        <th scope="row" class="text-center" width="200">নাম</th>
                        <th scope="row" class="text-center">পিতা/স্বামীর নাম</th>
                        <th scope="row" class="text-center">ঠিকানা</th>
                     </tr>
                  </thead>
                  <tbody>
               @php $k = 1; @endphp
            @foreach ($caseBadi as $badi)
               <tr>
                  <td>{{en2bn($k)}}.</td>
                  <td>{{ $badi->name }}</td>
                  <td>{{ $badi->spouse_name }}</td>
                  <td>{{ $badi->address }}</td>
               </tr>
            @php $k++; @endphp
            @endforeach
                  </tbody>
               </table>


               <br>
               <table class="table table-striped border">
                  <thead>
                     <tr>
                         <th class="h3" scope="col" colspan="4">বিবাদীর বিবরণ</th>
                     </tr>
                     <tr class="bg-light-primary">
                        <th scope="row"  width="10">ক্রম</th>
                        <th scope="row" class="text-center" width="200">নাম</th>
                        <th scope="row" class="text-center">পিতা/স্বামীর নাম</th>
                        <th scope="row" class="text-center">ধরন</th>
                     </tr>
                  </thead>
                  <tbody>
               @php $k = 1; @endphp
               @foreach ($caseBibadi as $bibadi)

                     <tr>
                        <td class="tg-nluh">{{ en2bn($k) }}.</td>
                        <td class="tg-nluh">{{ $bibadi->ministry->office_name_bn ?? '-' }}</td>
                        <td class="tg-nluh">{{ $bibadi->department->office_name_bn ?? '-' }}</td>
                        <td class="tg-nluh">{{ $bibadi->is_main_bibadi == 1 ? 'মূল বিবাদী' : 'অন্যান্য বিবাদী'}}</td>
                     </tr>
               @php $k++; @endphp
               @endforeach
                  </tbody>
               </table>


         </div>
   </div>
    {{--  @php
        $hearings = App\Models\gov_case\GovCaseHearing::orderby('id', 'DESC')->where('gov_case_id', $case->id)->get();
    @endphp  --}}
    @if (count($hearings) != 0)
    <div class="row">
        <div class="col-md-12">
           <table class="table table-striped border">
            <thead>
               <tr>
                  <th class="h3" scope="col" colspan="6">শুনানির নোটিশ</th>
               </tr>
              <tr>
                 <th scope="row" width="10">ক্রম</th>
                 <th scope="row" class="text-center">শুনানির তারিখ</th>
                 <th scope="row" class="text-center">সংযুক্তি</th>
                 <th scope="row" class="text-center">মন্তব্য</th>
                 <th scope="row" class="text-center">শুনানির ফলাফলের সংযুক্তি</th>
                 <th scope="row" class="text-center">ফলাফলের মন্তব্য</th>
              </tr>
           </thead>
              <tbody class="text-center">
                @forelse ($hearings as $key=> $row)
                <tr>
                   <td class="tg-nluh text-center" scope="row">{{ en2bn($key+1) }}.</td>
                   <td class="tg-nluh text-center">{{ en2bn($row->hearing_date) }}</td>
                   <td class="tg-nluh text-center">
                        <a target="_black" href="{{ asset($row->hearing_file) }}" class="btn btn-primary btn-sm">সংযুক্তি</a>
                    </td>
                   <td class="tg-nluh text-center" class="tg-nluh">{{ $row->comment }}</td>
                   <td class="tg-nluh text-center">
                        <a target="_black" href="{{ asset($row->hearing_result_file) }}" class="btn btn-primary btn-sm">সংযুক্তি</a>
                    </td>
                   <td class="tg-nluh text-center" class="tg-nluh">{{ $row->hearing_result_comments ?? '-' }}</td>
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
           </table>
        </div>
    </div>
   <br>
   @endif
   <br>
   <br>
   <br>
   <br>

   <div class="row">
      <div class="col-md-5">
         @if($case->order_date != NULL)
          <h4 class="font-weight-bolder">আদেশের তারিখ সমুহ</h4>
            <table class="tg">
               <tr>
                  <th class="tg-19u4 text-left" width="150">আদেশের তারিখ</th>
                  <td class="tg-nluh font-size-lg font-weight-bold">{{ en2bn($case->order_date) }}</td>
               </tr>
               @endif
               @if($case->next_assign_date != NULL)
               <tr>
                  <th class="tg-19u4 text-left" width="150">পরবর্তী ধার্য তারিখ</th>
                  <td class="tg-nluh font-size-lg font-weight-bold">{{ en2bn($case->next_assign_date) }}</td>
               </tr>
               @endif
               @if($case->past_order_date != NULL)
               <tr>
                  <th class="tg-19u4 text-left" width="150">বিগত তারিখের আদেশ</th>
                  <td class="tg-nluh font-size-lg font-weight-bold">{{ en2bn($case->past_order_date) }}</td>
               </tr>
               @endif
            </table>
      </div>
   </div>
   <br>




   <div class="row">
      {{--  <div class="col-md-4">
         <h4 class="font-weight-bolder">কারণ দর্শাইবার স্ক্যান কপি</h4>
         <a href="#" class="btn btn-success btn-shadow font-weight-bold font-size-h4" data-toggle="modal" data-target="#showCauseModal">
            <i class="fa fas fa-file-pdf icon-md"></i> কারণ দর্শাইবার স্ক্যান কপি
         </a>

         <!-- Modal-->
         <div class="modal fade" id="showCauseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title font-weight-bolder font-size-h3" id="exampleModalLabel">কারণ দর্শাইবার স্ক্যান কপি</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                     </button>
                  </div>
                  <div class="modal-body">

                     <embed src="{{ asset($case->arji_file) }}" type="application/pdf" width="100%" height="400px" />

                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold font-size-h5" data-dismiss="modal">বন্ধ করুন</button>
                     </div>
                  </div>
               </div>
            </div> <!-- /modal -->
      </div>  --}}
      @if (count($files) != 0)
         @foreach ($files as $file)
            <div class="col-md-4">
               <h4 class="font-weight-bolder">{{ $file->file_type }}</h4>
               <a href="#" class="btn btn-success btn-shadow font-weight-bold font-size-h4" data-toggle="modal" data-target="#showFileModal">
                  <i class="fa fas fa-file-pdf icon-md"></i> {{ $file->file_type }}
               </a>

               <!-- Modal-->
               <div class="modal fade" id="showFileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-xl" role="document">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title font-weight-bolder font-size-h3" id="exampleModalLabel">{{ $file->file_type }}</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <i aria-hidden="true" class="ki ki-close"></i>
                           </button>
                        </div>
                        <div class="modal-body">

                           <embed src="{{ asset($file->file_name) }}" type="application/pdf" width="100%" height="400px" />

                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-light-primary font-weight-bold font-size-h5" data-dismiss="modal">বন্ধ করুন</button>
                           </div>
                        </div>
                     </div>
                  </div> <!-- /modal -->
            </div>
         @endforeach
      @endif

   </div>
   <br>
   <div class="row">
    @if($case->sf_report != NULL)
         <div class="col-md-4">
            <h4 class="font-weight-bolder">এস এফ এর চূড়ান্ত প্রতিবেদন</h4>
            <a href="#" class="btn btn-success btn-shadow font-weight-bold font-size-h4" data-toggle="modal" data-target="#sfFinalFile">
               <i class="fa fas fa-file-pdf icon-md"></i> এস এফ এর চূড়ান্ত প্রতিবেদন
            </a>

            <!-- Modal-->
            <div class="modal fade" id="sfFinalFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-xl" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title font-weight-bolder font-size-h3" id="exampleModalLabel">এস এফ এর চূড়ান্ত প্রতিবেদন</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                     </div>
                     <div class="modal-body">

                        <embed src="{{ asset('uploads/sf_report/'.$case->sf_report) }}" type="application/pdf" width="100%" height="400px" />

                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-light-primary font-weight-bold font-size-h5" data-dismiss="modal">বন্ধ করুন</button>
                        </div>
                     </div>
                  </div>
               </div> <!-- /modal -->
         </div>
         @endif
         @if($case->order_file != NULL)

         <div class="col-md-4">
            <h4 class="font-weight-bolder">আদেশের ফাইল</h4>
            <a href="#" class="btn btn-success btn-shadow font-weight-bold font-size-h4" data-toggle="modal" data-target="#orderFile">
               <i class="fa fas fa-file-pdf icon-md"></i> আদেশের ফাইল
            </a>

            <!-- Modal-->
            <div class="modal fade" id="orderFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-xl" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title font-weight-bolder font-size-h3" id="exampleModalLabel">আদেশের ফাইল</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                     </div>
                     <div class="modal-body">

                        <embed src="{{ asset('uploads/order/'.$case->order_file) }}" type="application/pdf" width="100%" height="400px" />

                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-light-primary font-weight-bold font-size-h5" data-dismiss="modal">বন্ধ করুন</button>
                        </div>
                     </div>
                  </div>
               </div> <!-- /modal -->
         </div>
   </div>
    @endif


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


