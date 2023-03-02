>@extends('layouts.cabinet.cab_default')


@section('content')

@php
$new=$running=$finished=$applied=0;

foreach ($cases as $val)
{
   if($val->status == 1){
   $new++;
}
if($val->status == 2){
$running++;
}
if($val->status == 3){
$applied++;
}
if($val->status == 4){
$finished++;
}
}

@endphp

<!--begin::Dashboard-->
<!-- Dashboard Counter -->
@include('dashboard.cabinet.inc._dashboard_counter_card')
<!-- //Dashboard Counter -->


<!--begin::Row-->

<div class="row">
    <div class="col-md-8">
        <div class="card card-custom">
           <div class="card-header flex-wrap bg-danger py-5">
              <div class="card-title">
                 <h3 class="card-label h3 font-weight-bolder"> পদক্ষেপ নিতে হবে এমন সরকারি মামলাসমূহ</h3>
              </div>
           </div>
           <div class="card-body p-0">
              <ul class="navi navi-border navi-hover navi-active">
                 @forelse ($gov_case_status as $row)
                    {{-- @dd($row) --}}
                 <li class="navi-item">
                    <a class="navi-link" href="{{ route('cabinet.case.action.receive', $row->case_status_id) }}">
                    {{-- <a class="navi-link" href=""> --}}
                       <span class="navi-icon"><i class="fas fa-folder-open icon-lg text-danger mr-3"></i></span>
                       <div class="navi-text">
                          <span class="d-block font-weight-bold h4 pt-2">{{ $row->case_status->status_name }}</span>
                       </div>
                       <span class="navi-label">
                          <span class="label label-xl label-danger h5">{{ $row->total_case }}</span>
                       </span>
                    </a>
                 </li>

                 @empty

                 <li class="navi-item">
                    <div class="alert alert-custom alert-light-success fade show m-5" role="alert">
                       <div class="alert-icon">
                          <i class="flaticon-list"></i>
                       </div>
                       <div class="alert-text font-size-h4">পদক্ষেপ নিতে হবে এমন কোন মামলা পাওয়া যায়নি</div>
                    </div>
                 </li>

                 @endforelse
              </ul>
           </div>
        </div>
     </div>
</div>
<!--end::Row-->

<!--end::Dashboard-->
@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
<link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page--}}
@section('scripts')

<script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<!--end::Page Vendors-->
<script src="{{ asset('js/pages/widgets.js') }}"></script>
<!--end::Page Scripts-->
@endsection
