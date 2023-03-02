@extends('layouts.cabinet.cab_default')
@yield('style')
<link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

@section('content')
<!--begin::Dashboard-->

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


<div class="row">
    <div class="col-md-8">
        <div class="card card-custom">
           <div class="card-header flex-wrap bg-danger py-5">
              <div class="card-title">
                 <h3 class="card-label h3 font-weight-bolder"> পদক্ষেপ নিতে হবে এমন মামলাসমূহ</h3>
              </div>
           </div>
           <div class="card-body p-0">
              <ul class="navi navi-border navi-hover navi-active">
                 @forelse ($case_status as $row)
                    {{-- @dd($row) --}}
                 <li class="navi-item">
                    <a class="navi-link" href="{{ route('cabinet.case.action.receive', $row->case_status_id) }}">
                    {{-- <a class="navi-link" href=""> --}}
                       <span class="navi-icon"><i class="fas fa-folder-open icon-lg text-danger mr-3"></i></span>
                       <div class="navi-text">
                          <span class="d-block font-weight-bold h4 pt-2">{{ $row->case_status->status_name ?? '' }}</span>
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

   <!-- Ministry case list -->
   <br>
   <div class="container card">
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
   </div>
    <!-- end Ministry case list -->


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
   .highcharts-figure text, .text, .highcharts-data-table table caption {
    font-family: 'Kalpurush', Poppins, Helvetica, sans-serif;
    text-align: center;
  }
   .highcharts-figure  g text{
    font-size: 14px !important;
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
<!-- <div class="card card-custom mt-5">
    <figure class="highcharts-figure" style="width: 100%">
    <div id="container"></div>
    </figure>
</div> -->


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

<script type="text/javascript">
   // Create the chart
   Highcharts.chart('container', {
      chart: {
         type: 'column'
      },
      title: {
         text: 'অধিদপ্তর ভিত্তিক মামলা'
      },
      subtitle: {
         text: 'মামলা'
      },
      accessibility: {
         announceNewData: {
            enabled: true
         }
      },
      xAxis: {
         type: 'category'
      },
      yAxis: {
         title: {
            text: 'মামলার সংখ্যা'
         }

      },
      legend: {
         enabled: false
      },
      plotOptions: {
         series: {
            borderWidth: 0,
            dataLabels: {
               enabled: true,
               format: '{point.y}'
            }
         }
      },

      tooltip: {
         headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
         pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
      },

      series: [
      {
        name: "Ministry",
        colorByPoint: true,
        data: <?=json_encode($departmentdata);?>
      }
      ],

      drilldown: {
         series: <?=json_encode($department_data);?>
      }
 });
</script>
// {{-- -------------callender end---------- --}}
@if (Auth::user()->role_id == 2)
@include('dashboard.calendar.calender_need_js')
@endif
// {{-- -------------callender end---------- --}}


@endsection


