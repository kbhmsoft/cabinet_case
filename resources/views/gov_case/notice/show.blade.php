@extends('layouts.cabinet.cab_default')

@section('content')

@php //echo $userManagement->name;
//exit(); @endphp

<!--begin::Card-->
<div class="card card-custom col-7">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-label"> {{ $page_title }} </h3>
      </div>
      <div class="card-toolbar">
         <a href="{{ url('cabinet/notice/list') }}" class="btn btn-sm btn-primary font-weight-bolder">
            <i class="la la-list"></i> নোটীশের তালিকা
         </a>
      </div>
   </div>
   <div class="card-body">
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">নোটিশ</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">{{ $notice->description}}</span>
      </div>
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">ইউজার রোল</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">{{ $notice->role->role_name}}</span>
      </div>
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">প্রকাশের তারিখ</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">{{ $notice->publish_date}}</span>
      </div>
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">প্রকাশের তারিখ</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">{{ $notice->expiry_date}}</span>
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


