@php
   $user = Auth::user();
   $roleID = Auth::user()->role_id;
@endphp

@extends('layouts.cabinet.cab_default')

@section('content')
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
                    <th scope="row">লিভ টু আপিল নম্বর ও দায়েরের তারিখ</th>
                    <th scope="col">আপিলকারীর নাম/ঠিকানা</th>
                    <th scope="row">মুল প্রতিবাদি দপ্তর-সংস্থা</th>
                    <th scope="col">মামলার বিষয়বস্তু</th>
                    <th scope="row">যে রিট পিটিশন হতে উদ্ভূত</th>
                    <th scope="row">শুনানির তারিখ সমুহ</th>
                    <th scope="row">লিভ টু আপিলের আদেশের তারিখ ও বিবরণ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $case->leave_to_appeal_no ?? '-' }}/{{ $case->leave_to_appeal_date ? en2bn($case->leave_to_appeal_date):'-'}}</td>
                    <td>
                      @foreach ($caseBadi as $key=>$badi)
                         
                            {{en2bn($key+1)}}. {{ $badi->name }} ,{{ $badi->address }}<br>
                         
                      @endforeach

                    </td>
                    <td>
                      @foreach ($caseMainBibadi as $key=>$bibadi)
                        {{en2bn($key+1)}}.{{ $bibadi->ministry->office_name_bn ?? '-' }},{{ $bibadi->department->office_name_bn ?? '-' }}<br>
                      @endforeach
                    </td>
                    <td>{{ $case->subject_matter }}</td>
                    <td><a href="{{ route('cabinet.case.details', $case->gov_case_ref_id) }}" target="_blank">{{ $case->ref_gov_case_no }}</a></td>
                    <td>
                      @forelse ($hearings as $key=> $row)
                        {{ en2bn($key+1) }}. {{ en2bn($row->hearing_date) }}
                      @empty
                            শুনানির কোন নোটিশ পাওয়া যাইনি
                      @endforelse
                    </td>
                    <td>{{ $case->leave_to_appeal_order_date ? en2bn($case->leave_to_appeal_order_date) : '-'}},{{ $case->leave_to_appeal_order_details ?? '-' }}</td>
                  </tr>
                </tbody>
              </table>

              <table class="table ">
                <thead>
                  <tr class="bg-light-primary">
                    <th scope="row">সিভিল আপিল নম্বর ও দায়েরের তারিখ</th>
                    <th scope="row">সিভিল আপিলের আদেশের তারিখ ও বিবরণ</th>
                    <th scope="row">রিভিউ নম্বর ও দায়েরের তারিখ</th>
                    <th scope="row">শুনানির তারিখ সমুহ</th>
                    <th scope="row">রিভিউ আদেশের তারিখ ও বিবরণ</th>
                    <th scope="row">মন্তব্য</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $case->case_no ?? '-'}},{{ $case->date_issuing_rule_nishi ? en2bn($case->date_issuing_rule_nishi) : '-'}}</td>
                    
                    <td>{{ $case->review_case_no }}<br>{{ $case->review_case_no ? en2bn($case->review_case_no) : '-'}}</td>
                    <td>{{ $case->civil_appeal_order_date ? en2bn($case->civil_appeal_order_date) : '-'}}<br>{{ $case->civil_appeal_order_details ?? '-'}}</td>
                    <td>
                      @forelse ($hearings as $key=> $row)
                        {{ en2bn($key+1) }}. {{ en2bn($row->hearing_date) }}
                      @empty
                            শুনানির কোন নোটিশ পাওয়া যাইনি
                      @endforelse
                    </td>
                    <td>{{ $case->review_case_order_date ? en2bn($case->review_case_order_date) : '-'}}<br>{{ $case->review_case_order_details ?? '-'}}</td>
                    <td>
                      {{ $case->comments ?? '-'}}
                    </td>
                  </tr>
                </tbody>
              </table>
         </div>
      </div>
   <br>




   <div class="row">
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


