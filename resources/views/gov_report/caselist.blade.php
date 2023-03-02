@extends('layouts.cabinet.cab_default')

@section('content')

<!--begin::Row-->
<div class="row">
   <div class="col-md-12">
      <!--begin::Card-->
      <div class="card card-custom gutter-b example example-compact">
         <div class="card-header">
            <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
            <div class="card-toolbar">
               <!-- <div class="example-tools justify-content-center">
                  <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                  <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
               </div> -->
            </div>
         </div>

         <!-- <div class="loadersmall"></div> -->

         <!--begin::Form-->
         <!-- <form class="form" method="GET"> -->
         <form action="{{ url('govcase/report/pdf') }}" class="form" method="POST" target="_blank">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <fieldset class="text-center mb-6">
                            <legend>ফিল্টারিং ফিল্ড সমূহ</legend>

                            <div class="form-group row">
                                <div class="col-lg-6 mb-5">
                                <select name="ministry" id="department_id" class="form-control form-control-sm">
                                    <option value="">-মন্ত্রণালয় নির্বাচন করুন-</option>
                                    @foreach ($ministry as $value)
                                    <option value="{{ $value->id }}"> {{ $value->office_name_bn }} </option>
                                    @endforeach
                                </select>
                                </div>
                                <div class="col-lg-6 mb-5">

                                <select name="department" id="department_id" class="form-control form-control-sm">
                                    <option value="">-অধিদপ্তর নির্বাচন করুন-</option>
                                </select>
                                </div>

                                {{--  <div class="col-lg-4 mb-5">
                                <select id="year" class="form-control form-control-sm" name="year">
                                    <option value="">সাল নির্বাচন করুন</option>
                                    {{ $year = date('Y') }}
                                    @for ($year = 1971; $year <= 2021; $year++)
                                    <option value="{{ $year }}">{{ en2bn($year) }}</option>
                                    @endfor
                                </select>
                                </div>

                                <div class="col-lg-4 mb-5">
                                <select  class="form-control" id="month" name="month">
                                    <option value=""> মাস নির্বাচন করুন </option>
                                    @foreach(range(1,12) as $month)
                                    <option value="{{$month}}">
                                        {{ date("M", strtotime('2016-'.$month)) }}
                                    </option>
                                    @endforeach
                                </select>
                                </div> --}}

                                <div class="col-lg-6 mb-5">
                                <input type="text" name="date_start"  class="form-control form-control-sm common_datepicker" placeholder="তারিখ হতে" autocomplete="off">
                                </div>
                                <div class="col-lg-6 mb-5">
                                <input type="text" name="date_end" class="form-control form-control-sm common_datepicker" placeholder="তারিখ পর্যন্ত" autocomplete="off">
                                </div>


                            </div>
                        </fieldset>
                        <div class="row">
                            <div class="col-lg-12 mb-6 ml-1 ">
                            <fieldset class="text-center">
                                <legend>সংখ্যা ভিত্তিক রিপোর্ট বাটন</legend>

                                    <button type="submit" name="btnsubmit" value="pdf_num_ministry" class="btn btn-info btn-cons margin-top"> উচ্চ আদালতে চলমান</button>

                                    <button type="submit" name="btnsubmit" value="pdf_num_importance" class="btn btn-info btn-cons margin-top"> গুরুত্বপূর্ণ ভিত্তিক </button>

                            </fieldset>
                            </div>

                        </div>
                    </div>
                </div>


            </div> <!--end::Card-body-->


         </form>
         <!--end::Form-->
      </div>
      <!--end::Card-->
   </div>

</div>
<!--end::Row-->

@endsection

{{-- Includable CSS Related Page --}}
@section('styles')

<!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page--}}
@section('scripts')
<script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
<script>
   // common datepicker
   $('.common_datepicker').datepicker({
      format: "dd/mm/yyyy",
      todayHighlight: true,
      orientation: "bottom left"
   });
</script>
<script type="text/javascript">
  jQuery(document).ready(function ()
   {
   // Doptor Dropdown
      jQuery('select[name="ministry"]').on('change',function(){
         var dataID = jQuery(this).val();
            // var category_id = jQuery('#category_id option:selected').val();
            jQuery("#department_id").after('<div class="loadersmall"></div>');
            if(dataID)
            {
               jQuery.ajax({
                  url : '{{url("/")}}/case/dropdownlist/getdependentDoptor/' +dataID,
                  type : "GET",
                  dataType : "json",
                  success:function(data)
                  {
                     jQuery('select[name="department"]').html('<div class="loadersmall"></div>');

                     jQuery('select[name="department"]').html('<option value="">-- নির্বাচন করুন --</option>');
                     jQuery.each(data, function(key,value){
                        jQuery('select[name="department"]').append('<option value="'+ key +'">'+ value +'</option>');
                     });
                     jQuery('.loadersmall').remove();

                  }
               });
            }
            else
            {
               $('select[name="department"]').empty();
            }
      });
   });
</script>
<!--end::Page Scripts-->
@endsection
