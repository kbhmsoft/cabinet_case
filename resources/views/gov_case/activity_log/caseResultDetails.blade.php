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
                        <th class="tg-19u4">রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ</th>
                        <td class="tg-nluh">{{ $data[0]['case_register'][0]['result_copy_asking_date'] }}</td>
                    </tr>
                    <tr>
                        <th class="tg-19u4">রায়ের নকল প্রাপ্তির তারিখ</th>
                        <td class="tg-nluh">{{ $data[0]['case_register'][0]['result_copy_reciving_date'] }}</td>
                    </tr>
                        <th class="tg-19u4">প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক</th>
                        <td class="tg-nluh">{{ $data[0]['case_register'][0]['appeal_requesting_memorial'] }}</td>
                    </tr>
                    <tr>
                        <th class="tg-19u4">প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ</th>
                        <td class="tg-nluh">{{ $data[0]['case_register'][0]['appeal_requesting_date'] }}</td>
                    </tr>
                    <tr>
                        <th class="tg-19u4">আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ </th>
                        <td class="tg-nluh">{{ $data[0]['case_register'][0]['reason_of_not_appealing'] }}</td>
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


