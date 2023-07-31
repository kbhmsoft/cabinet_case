@php
    $roleID = Auth::user()->role_id;
    $officeInfo = user_office_info();
@endphp

@extends('layouts.cabinet.cab_default')

@section('content')

<style type="text/css">
    #appRowDiv td{padding: 5px; border-color: #ccc;}
    #appRowDiv th{padding: 5px;text-align:center;border-color: #ccc; color: black;}
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        width: 250px;
    }
    .select2-container .select2-selection--single {
        box-sizing: border-box;
        /*cursor: pointer;
        display: block;*/
        height: 41px;
        /*user-select: none;
        -webkit-user-select: none;
        padding-top: 6px;*/
        font-size:1.2rem
    }
</style>
<!--begin::Row-->
<div class="row">

    <div class="col-md-12">
        <!--begin::Card-->
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
                <div class="card-toolbar">
                    <div class="card-toolbar">
                @if(auth()->user()->can('all_office_list'))
                        <a href="{{ route('cabinet.office') }}" class="btn btn-sm btn-primary font-weight-bolder">
                           <i class="la la-arrow-left"></i>অফিস তালিকা
                        </a>
                @else
                <a href="#" class="btn btn-sm btn-secondary font-weight-bolder">
                           <i class="la la-arrow-left"></i>অফিস তালিকা
                        </a>
                @endif
                     </div>
                    <!-- <div class="example-tools justify-content-center">
                        <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                        <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                    </div> -->
                </div>
            </div>
            	@if ($errors->any())

				     @foreach ($errors->all() as $error)
				    	<li class="alert alert-danger">{{ $error }}</li>
				     @endforeach

 				@endif
            <!--begin::Form-->
            <form action="{{ route('cabinet.office.save') }}" class="form" method="POST">
            @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <div class="container">
                            <div class="row">

                                <div class="col-lg-4 mb-5">
                                    <label>অফিস লেভেল</label>

                                    <select name="level" id="level"  class="form-control w-100">
                                          <option value="">-- নির্বাচন করুন --</option>
                                          @foreach ($office_type as $value)
                                          <option value="{{ $value->id }}"> {{ $value->type_name_bn }} </option>
                                          @endforeach
                                       </select>
                                </div>

                                <div class="col-lg-6 mb-5" id="parentMinDiv" style="display: none;">
                                    <label>মন্ত্রণালয় / বিভাগ</label><br>

                                    <select name="parentMinID" id="parentMinID"  class="form-control w-100">
                                          <option value="">-- নির্বাচন করুন --</option>
                                          @foreach ($ministries as $value)
                                          <option value="{{ $value->id }}"> {{ $value->office_name_bn }} </option>
                                          @endforeach
                                       </select>
                                </div>

                                <div class="col-lg-4 mb-5" id="DivisionalParentDiv" style="display: none;">
                                    <label>বিভাগীয় প্রশাসন</label>

                                    <select name="parentDivID" id="parentDivID"  class="form-control w-100">
                                          <option value="">-- নির্বাচন করুন --</option>
                                          @foreach ($divisions as $value)
                                          <option value="{{ $value->id }}"> {{ $value->office_name_bn }} </option>
                                          @endforeach
                                       </select>
                                </div>

                                <div class="form-group col-lg-8">
                                    <label for="office_name" class=" form-control-label">অফিসের নাম <span class="text-danger">*</span></label>
                                    <input type="text" id="office_name" name="office_name" placeholder="অফিসের নাম লিখুন" class="form-control form-control-sm">
                                    <span style="color: red">
                                        {{ $errors->first('office_name') }}
                                    </span>
                                </div>

                                <div class="col-lg-2">
                                  <label>স্ট্যাটাস <span class="text-danger">*</span></label>
                                  <div class="radio-inline">
                                    <label class="radio">
                                    <input type="radio" name="status" value="1" checked="checke" />
                                    <span></span>এনাবল</label>
                                    <label class="radio">
                                    <input type="radio" name="status" value="0" />
                                    <span></span>ডিজেবল</label>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!--end::Card-body-->

                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-5"></div>
                        <div class="col-lg-7">
                            <!-- <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary mr-3" id="preview">প্রিভিউ</button> -->
                            <button type="submit" class="btn btn-primary mr-2" onclick="return confirm('আপনি কি সংরক্ষণ করতে চান?')">সংরক্ষণ করুন</button>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Card-->
    </div>

</div>
<!--end::Row-->

@endsection

{{-- Scripts Section Related Page--}}
@section('scripts')


  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
      jQuery(document).ready(function ()
      {
        $('#parentMinID').select2();
        $('#parentDivID').select2();
        jQuery('select[name="division"]').on('change',function(){
        var dataID = jQuery(this).val();
        jQuery("#district_id").after('<div class="loadersmall"></div>');
        if(dataID)
        {
            jQuery.ajax({
                url : '{{url("/")}}/case/dropdownlist/getdependentdistrict/' +dataID,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                    jQuery('select[name="district"]').html('<div class="loadersmall"></div>');
                    jQuery('select[name="district"]').html('<option value="">-- নির্বাচন করুন --</option>');
                    jQuery.each(data, function(key,value){
                    jQuery('select[name="district"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                    jQuery('.loadersmall').remove();
                }
            });
        }
        else
        {
            $('select[name="district"]').empty();
        }
        });

        jQuery('select[name="district"]').on('change',function(){
            var dataID = jQuery(this).val();

            jQuery("#upazila_id").after('<div class="loadersmall"></div>');

                jQuery.ajax({
                url : '{{url("/")}}/case/dropdownlist/getdependentupazila/' +dataID,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                jQuery('select[name="upazila"]').html('<div class="loadersmall"></div>');

                    jQuery('select[name="upazila"]').html('<option value="">-- নির্বাচন করুন --</option>');
                    jQuery.each(data, function(key,value){
                        jQuery('select[name="upazila"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                    jQuery('.loadersmall').remove();
                }
                });

            var courtID = jQuery(this).val();
            jQuery("#court_id").after('<div class="loadersmall"></div>');

                jQuery.ajax({
                url : '{{url("/")}}/court/dropdownlist/getdependentcourt/' +courtID,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                    jQuery('select[name="court"]').html('<div class="loadersmall"></div>');

                    jQuery('select[name="court"]').html('<option value="">-- নির্বাচন করুন --</option>');
                    jQuery.each(data, function(key,value){
                        jQuery('select[name="court"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                    jQuery('.loadersmall').remove();
                }
                });

        });



        jQuery('select[name="level"]').on('change',function(){
            var levelID = jQuery(this).val();
            // alert(levelID);
            if (levelID == 2) {
                $('#parentMinDiv').show();
                $('#DivisionalParentDiv').hide();
            }else if (levelID == 4) {
                $('#DivisionalParentDiv').show();
                $('#parentMinDiv').hide();
            }else{
                $('#DivisionalParentDiv').hide();
                $('#parentMinDiv').hide();
            }
        });

   });
</script>

<script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
<script>
        // common datepicker
        $('.common_datepicker').datepicker({
            format: "dd/mm/yyyy",
            todayHighlight: true,
            orientation: "bottom left"
        });
    </script>


    @endsection


    <!--end::Page Scripts-->


