@extends('layouts.cabinet.cab_default')

@section('content')

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
      </div>
      <!-- @if(userInfo()->role_id == 28 ||userInfo()->role_id == 31 ||userInfo()->role_id == 33)
      <div class="card-toolbar">
         <a href="{{ route('cabinet.case.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
            <i class="la la-plus"></i>নতুন মামলা এন্ট্রি
         </a>
      </div>
      @endif -->

      <div class="card-toolbar">
         @if($roleID == 27 || $roleID == 28)
         <a href="{{ route('cabinet.case.ministry_wise_list', $ministry->id) }}" class="btn btn-sm btn-primary font-weight-bolder mr-2">{{$ministry->ministry_name}} এর তালিকা</a>
         @else
         <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary font-weight-bolder mr-2">ড্যাশবোর্ড</a>
         @endif
      </div>

      
   </div>
   <div class="card-body">

      @include('gov_case.search')
      
      <table class="table table-hover mb-6 font-size-h5">
         <thead class="thead-light font-size-h6">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">মামলা নং</th>
               <th scope="col">ক্যাটাগরি</th>
               <th scope="col">পিটিশনার</th>
               <th scope="col">মূল বিবাদী</th>
               <th scope="col">বিষয়বস্তু</th>
               <th scope="col">স্থগিতাদেশ নিষেধাজ্ঞা</th>
               <th scope="col">জবাব প্রেরনের তারিখ</th>
               <th scope="col">সর্বশেষ অবস্থা </th>
               <th scope="col" width="70">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($cases as $key => $row)
            <?php
               // if($row->status == 1){
               //    $caseStatus = '<span class="label label-inline label-light-primary font-weight-bold">নতুন মামলা</span>';
               // }
            ?>

            @php 
               $department = '';
               $ministry = '';
               if(($row->bibadis->first()->department != null && $row->bibadis->first()->department != '') && $row->bibadis->first()->is_main_bibadi == 1) {
                  $department = ' এর '. $row->bibadis->first()->department->office_name_bn;
               }

               if($row->bibadis->first()->ministry != null && $row->bibadis->first()->ministry != '' && $row->bibadis->first()->is_main_bibadi == 1) {
                  $ministry = $row->bibadis->first()->ministry->office_name_bn;
               }
            @endphp

            
            <tr>
               <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
               <td>{{ $row->case_no }}</td>
               <td>{{ $row->case_category->name_bn ?? '-' }}</td>
               <td>{{ $row->badis->first()->name ?? '-' }} </td>
               <td>{{ $ministry . $department }} </td>

               <td>{{ $row->subject_matter ?? '-'}}</td>
               <td>{{ $row->postponed_details ?? '-'}}</td>
               <!-- <td>{{ $row->case_division->name_bn ?? '-'}}</td> -->
               <td>- </td>
               <td>{{ $row->case_status->status_name ?? '-' }} </td>
               <!-- <td>{{ $row->role->role_name ?? '-' }} </td> -->
               <td>
                    <div class="btn-group float-right">
                        <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">অ্যাকশন</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('cabinet.case.details', $row->id) }}">বিস্তারিত তথ্য</a>
                            <a class="dropdown-item" href="{{ route('cabinet.case.edit', $row->id) }}">সংশোধন</a>
                            @if($row->action_user_role_id == userInfo()->role_id)
                            <a class="dropdown-item" href="{{ route('cabinet.case.action.details', $row->id) }}">জবাব প্রেরণ</a>
                            @endif
                        </div>
                    </div>
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>

      <div class="d-flex justify-content-center">
         {!! $cases->links() !!}
      </div>
   </div>
   <!--end::Card-->

   @endsection

   {{-- Includable CSS Related Page --}}
   @section('styles')
   <!-- <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" /> -->
   <!--end::Page Vendors Styles-->
   @endsection

   {{-- Scripts Section Related Page--}}
   @section('scripts')
   <!-- <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
   <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
 -->


<!--end::Page Scripts-->
@endsection


