>@extends('layouts.cabinet.cab_default')


@section('content')

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
      </div>
      @if(Auth::user()->role_id == 5 || Auth::user()->role_id == 21 || Auth::user()->role_id == 22 || Auth::user()->role_id == 24)
      <div class="card-toolbar">
         <a href="{{ route('rmcase.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
            <i class="la la-plus"></i>নতুন মামলা এন্ট্রি
         </a>
      </div>
      @endif
   </div>
   <div class="card-body">
      @if ($message = Session::get('success'))
      <div class="alert alert-success">
         {{ $message }}
      </div>
      @endif

     {{-- @include('case.search') --}}
        <form action="{{ route('rmcase.case_audit.index') }}" class="form-inline" method="GET">
            <div class="container mb-4">
                <div class="row">
                    <div class="col-lg-3">
                            <input type="text" class="form-control " name="case_no" placeholder="মামলা নং" value="">
                    </div>
                    <div class="col-lg-2 mt-1">
                        <button type="submit" class="btn btn-success font-weight-bolder mb-2 ml-2">অনুসন্ধান করুন</button>
                    </div>
                </div>
            </div>
        </form>

      <table class="table table-hover mb-6 font-size-h5">
         <thead class="thead-light font-size-h6">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">মামলা নং</th>
               <th scope="col">মামলার তারিখ</th>
               <th scope="col">মামলার ধরণ</th>
               <th scope="col">জেলা</th>
               {{-- <th scope="col">উপজেলা</th> --}}
               <th scope="col">মৌজা</th>
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
            <tr>
               <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
               <td>{{ $row->case_no }}</td>
               <td>{{ en2bn($row->case_date) }}</td>
               <td>{{ $row->case_type->type_name ?? ''}}</td>
               <td>{{ $row->district->district_name_bn ?? '' }}</td>
               {{-- <td>{{ $row->upazila->upazila_name_bn ?? '' }}</td> --}}
               <td>{{ $row->mouja->mouja_name_bn ?? '' }}</td>
               <td>
                <a href="{{ route('rmcase.case_audit.show', $row->id) }}" class="btn btn-primary font-weight-bold btn-sm">নিরীক্ষা</a>
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


