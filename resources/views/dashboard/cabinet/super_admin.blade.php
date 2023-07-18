@extends('layouts.cabinet.cab_default')
@yield('style')
<link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

@section('content')
<!--begin::Dashboard-->


   <!--begin::Dashboard-->
    <!-- Dashboard Counter -->
    @include('dashboard.cabinet.inc._dashboard_counter_card')
    <!-- //Dashboard Counter -->

  
    <!-- Dashboard Action Card -->
    @include('dashboard.cabinet.inc._dashboard_action_card')
    <!-- //Dashboard Action Card -->
 

   <!-- Ministry wise case list -->
   
    <!-- end Ministry wise case list -->



   <!--begin::Subheader-->
   
   <script src="https://code.highcharts.com/highcharts.js"></script>
   <script src="https://code.highcharts.com/modules/data.js"></script>
   <script src="https://code.highcharts.com/modules/drilldown.js"></script>
   <script src="https://code.highcharts.com/modules/exporting.js"></script>
   <script src="https://code.highcharts.com/modules/export-data.js"></script>
   <script src="https://code.highcharts.com/modules/accessibility.js"></script>
   <style type="text/css">

   /*highchart css*/

   .highcharts-figure, .highcharts-data-table table {
     /*min-width: 310px; */
     /*max-width: 1000px;*/
     /*margin: 1em auto;*/
   }

   #container {
      /*height: 400px;*/
   }

  .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      /*max-width: 500px;*/
   }
   .highcharts-data-table caption {
     padding: 1em 0;
     font-size: 1.2em;
     color: #555;
   }
   .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
   }
   .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
     padding: 0.5em;
   }
   .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
     background: #f8f8f8;
   }
   .highcharts-data-table tr:hover {
     background: #f1f7ff;
   }


   /*Pie chart*/
   .highcharts-figure, .highcharts-data-table table {
    min-width: 320px;
    max-width: 1030px;
    margin: 1em auto;
   }

   .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #EBEBEB;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
   }
   .highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
   }
   .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
   }
   .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
   }
   .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
   }
   .highcharts-data-table tr:hover {
    background: #f1f7ff;
   }
 

   input[type="number"] {
      min-width: 50px;
   }
   </style>

   <?php
   // $divisiondata=array();
   // $districtdata=array();
   // $result = array_merge($districtdata, $upazilatdata);
   ?>

   <!--end::Subheader-->
   {{-- -------------callender start---------- --}}
   @if (Auth::user()->role_id == 2)
   @include('dashboard.calendar.calender_need')
   @endif
   {{-- -------------callender end---------- --}}

   <!-- <figure class="highcharts-figure" style="width: 100%">
      <div id="container"></div>
   </figure> -->

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

   
   // {{-- -------------callender end---------- --}}
   @if (Auth::user()->role_id == 2)
   @include('dashboard.calendar.calender_need_js')
   @endif
   // {{-- -------------callender end---------- --}}


@endsection


