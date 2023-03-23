@php
   $user = Auth::user();
   $roleID = Auth::user()->role_id;
   $data = json_decode($caseActivityLog->new_data, true);
   // echo $data['court_id'];
   // print_r($data);
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
              <h4 class="font-weight-bolder">মামলার ফলাফল</h4>
              <table class="tg">
                 <thead>
                    <tr>
                      <th class="tg-19u4">সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের তারিখ</th>
                      <td class="tg-nluh">{{ $data[0]['case_register'][0]['result_sending_date_solisitor_to_ag'] }}</td>
                    </tr>
                    <tr>
                      <th class="tg-19u4">সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের স্মারক</th>
                      <td class="tg-nluh">{{ $data[0]['case_register'][0]['result_sending_memorial_solisitor_to_ag'] }}</td>
                    </tr>
                 </thead>
               
              </table>
        </div>
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


