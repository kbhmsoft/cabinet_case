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
         <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary font-weight-bolder mr-2">ড্যাশবোর্ড</a>
      </div>

      
   </div>
   <div class="card-body">

      <table class="table table-hover mb-6 font-size-h5">
         <thead class="thead-light font-size-h6">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">দপ্তর-সংস্থার নাম</th>
               <th scope="col">চলমান মামলা</th>
               <th scope="col">নিস্পত্তি মামলা</th>
               <th scope="col">সরকারের পক্ষে</th>
               <th scope="col">সরকারের বিপক্ষে</th>
            </tr>
         </thead>
         <tbody> 
            @foreach ($ministry_wise as $key => $row)
            <tr>
               <td>{{ en2bn($key + $ministry_wise->firstItem()) }}</td>
               <td><a href="{{ route('cabinet.case.department_wise_list', $row->id) }}">{{ $row->office_name_bn }}</a></td>
               <td align="center">{{ en2bn($row->running_case) }}</td>
               <td align="center">{{ en2bn($row->completed_case) }}</td>
               <td align="center">{{ en2bn($row->against_gov) }}</td>
               <td align="center">{{ en2bn($row->not_against_gov) }}</td>
            </tr>
            @endforeach
         </tbody>
      </table>

      <div class="d-flex justify-content-center">
         {!! $ministry_wise->links() !!}
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


