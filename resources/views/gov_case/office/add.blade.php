@php
    $roleID = Auth::user()->role_id;
    $officeInfo = user_office_info();
@endphp

@extends('layouts.cabinet.cab_default')

@section('content')

<style type="text/css">
    #appRowDiv td{padding: 5px; border-color: #ccc;}
    #appRowDiv th{padding: 5px;text-align:center;border-color: #ccc; color: black;}

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
                                <div class="form-group col-lg-8">
                                    <label for="office_name" class=" form-control-label">অফিসের নাম <span class="text-danger">*</span></label>
                                    <input type="text" id="office_name" name="office_name" placeholder="অফিসের নাম লিখুন" class="form-control form-control-sm">
                                    <span style="color: red">
                                        {{ $errors->first('office_name') }}
                                    </span>
                                </div>
                                <div class="col-lg-5 mb-5">
                                    <label>অফিসের ধরণ <span class="text-danger">*</span></label>
                                    
                                    <select name="type" id="type"  class="form-control w-100">
                                          <option value="">-- নির্বাচন করুন --</option>
                                          <option value="1"> সরকারি </option>
                                          <option value="2"> সংবিধিবদ্ধ/স্বশাসিত </option>
                                          <option value="3"> আধা-সরকারি </option>
                                       </select>
                                </div>
                                <div class="form-group col-lg-5">
                                    <label for="office_head_desig" class=" form-control-label">অফিস প্রধানের পদবি<span class="text-danger">*</span></label>
                                    <input type="text" id="office_head_desig" name="office_head_desig" placeholder="অফিসের নাম লিখুন" class="form-control form-control-sm">
                                    <span style="color: red">
                                        {{ $errors->first('office_head_desig') }}
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
                    <div class="modal fade" id="myModal">
                    <div class="modal-dialog">
                      <div class="modal-content">
                      
                        <!-- Modal Header -->
                        <div class="modal-header">
                          <h4 class="modal-title">আদালত এন্ট্রি প্রিভিউ</h4>
                          <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        
                        <!-- Modal body -->
                        <div class="modal-body">
                           <table class="tg">
                                <tr>
                                    <th class="tg-19u4 text-center">বিভাগের নাম</th>
                                    <td class="tg-nluh" id="previewDivision_id"></td>
                                </tr>
                                <tr>
                                    <th class="tg-19u4 text-center">জেলা</th>
                                    <td class="tg-nluh" id="previewDistrict_id"></td>
                                </tr>
                                <tr>
                                    <th class="tg-19u4 text-center">উপজেলা</th>
                                    <td class="tg-nluh" id="previewUpazila_id"></td>
                                </tr>
                                <tr>
                                    <th class="tg-19u4 text-center">অফিসের ধরণ </th>
                                    <td class="tg-nluh" id="previewCt_id"></td>
                                </tr>
                                <tr>
                                    <th class="tg-19u4 text-center">অফিসের নাম</th>
                                    <td class="tg-nluh" id="previewOffice_name"></td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Modal footer -->
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                        
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

{{-- Includable CSS Related Page --}}
@section('styles')

<!--end::Page Vendors Styles-->
@endsection     

{{-- Scripts Section Related Page--}}
@section('scripts')

<!-- <script type="text/javascript">
        jQuery(document).ready(function ()
        {
            //Load First row

            // Dynamic Dropdown
            var load_url = "{{ asset('media/custom/preload.gif') }}";

            jQuery('select[name="division"]').on('change',function(){
                var dataID = jQuery(this).val(); 

                // var category_id = jQuery('#category_id option:selected').val();
                jQuery("#district_id").after('<div class="loadersmall"></div>');
                // $("#loading").html("<img src='{{ asset('media/preload.gif') }}' />");
                // jQuery('select[name="mouja"]').html('<option><div class="loadersmall"></div></option');
                // jQuery('select[name="mouja"]').attr('disabled', 'disabled');
                // jQuery('.loadersmall').remove();

                if(dataID)
                {
                  jQuery.ajax({
                    url : '/court-setting/dropdownlist/getdependentdistrict/' +dataID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        jQuery('select[name="district"]').html('<div class="loadersmall"></div>');
                        //console.log(data);
                        // jQuery('#mouja_id').removeAttr('disabled');
                        // jQuery('#mouja_id option').remove();
                        
                        jQuery('select[name="district"]').html('<option value="">-- নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key,value){
                            jQuery('select[name="district"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        jQuery('.loadersmall').remove();
                        // $('select[name="mouja"] .overlay').remove();
                        // $("#loading").hide();
                    }
                });
              }
              else
              {
                  $('select[name="district"]').empty();
              }
          });

        });
</script>  -->     
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
  <script type="text/javascript">
      jQuery(document).ready(function ()
      {

          $('#parent_id').select2();
          // $('#district_id').select2();
         // District Dropdown
         jQuery('select[name="division"]').on('change',function(){
            var dataID = jQuery(this).val();
            // var category_id = jQuery('#category_id option:selected').val();
            jQuery("#district_id").after('<div class="loadersmall"></div>');
            // $("#loading").html("<img src='{{ asset('media/preload.gif') }}' />");
            // jQuery('select[name="mouja"]').html('<option><div class="loadersmall"></div></option');
            // jQuery('select[name="mouja"]').attr('disabled', 'disabled');
            // jQuery('.loadersmall').remove();
            if(dataID)
            {
               jQuery.ajax({
                  url : '{{url("/")}}/case/dropdownlist/getdependentdistrict/' +dataID,
                  type : "GET",
                  dataType : "json",
                  success:function(data)
                  {
                     jQuery('select[name="district"]').html('<div class="loadersmall"></div>');
                     //console.log(data);
                     // jQuery('#mouja_id').removeAttr('disabled');
                     // jQuery('#mouja_id option').remove();

                     jQuery('select[name="district"]').html('<option value="">-- নির্বাচন করুন --</option>');
                     jQuery.each(data, function(key,value){
                        jQuery('select[name="district"]').append('<option value="'+ key +'">'+ value +'</option>');
                     });
                     jQuery('.loadersmall').remove();
                     // $('select[name="mouja"] .overlay').remove();
                     // $("#loading").hide();
                  }
               });
            }
            else
            {
               $('select[name="district"]').empty();
            }
         });

      // Upazila Dropdown
      jQuery('select[name="district"]').on('change',function(){
         var dataID = jQuery(this).val();
         // var category_id = jQuery('#category_id option:selected').val();
         jQuery("#upazila_id").after('<div class="loadersmall"></div>');
          // $("#loading").html("<img src='{{ asset('media/preload.gif') }}' />");
          // jQuery('select[name="mouja"]').html('<option><div class="loadersmall"></div></option');
          // jQuery('select[name="mouja"]').attr('disabled', 'disabled');
          // jQuery('.loadersmall').remove();
          /*if(dataID)
          {*/
            jQuery.ajax({
             url : '{{url("/")}}/case/dropdownlist/getdependentupazila/' +dataID,
             type : "GET",
             dataType : "json",
             success:function(data)
             {
               jQuery('select[name="upazila"]').html('<div class="loadersmall"></div>');
                  //console.log(data);
                  // jQuery('#mouja_id').removeAttr('disabled');
                  // jQuery('#mouja_id option').remove();

                  jQuery('select[name="upazila"]').html('<option value="">-- নির্বাচন করুন --</option>');
                  jQuery.each(data, function(key,value){
                    jQuery('select[name="upazila"]').append('<option value="'+ key +'">'+ value +'</option>');
                 });
                  jQuery('.loadersmall').remove();
                  // $('select[name="mouja"] .overlay').remove();
                  // $("#loading").hide();
               }
            });
         //}

         // Load Court
         var courtID = jQuery(this).val();
         // var category_id = jQuery('#category_id option:selected').val();
         jQuery("#court_id").after('<div class="loadersmall"></div>');
         // $("#loading").html("<img src='{{ asset('media/preload.gif') }}' />");
         // jQuery('select[name="mouja"]').html('<option><div class="loadersmall"></div></option');
         // jQuery('select[name="mouja"]').attr('disabled', 'disabled');
         // jQuery('.loadersmall').remove();
         // if(courtID)
         // {
            jQuery.ajax({
               url : '{{url("/")}}/court/dropdownlist/getdependentcourt/' +courtID,
               type : "GET",
               dataType : "json",
               success:function(data)
               {
                  jQuery('select[name="court"]').html('<div class="loadersmall"></div>');
                  //console.log(data);
                  // jQuery('#mouja_id').removeAttr('disabled');
                  // jQuery('#mouja_id option').remove();

                  jQuery('select[name="court"]').html('<option value="">-- নির্বাচন করুন --</option>');
                  jQuery.each(data, function(key,value){
                     jQuery('select[name="court"]').append('<option value="'+ key +'">'+ value +'</option>');
                  });
                  jQuery('.loadersmall').remove();
                  // $('select[name="mouja"] .overlay').remove();
                  // $("#loading").hide();
               }
            });
         //}
         /*else
         {
            $('select[name="upazila"]').empty();
            $('select[name="court"]').empty();
         }*/
      });

            // Court Dropdown
      /*jQuery('select[name="district"]').on('change',function(){
         var dataID = jQuery(this).val();
         // var category_id = jQuery('#category_id option:selected').val();
         jQuery("#court_id").after('<div class="loadersmall"></div>');
         // $("#loading").html("<img src='{{ asset('media/preload.gif') }}' />");
         // jQuery('select[name="mouja"]').html('<option><div class="loadersmall"></div></option');
         // jQuery('select[name="mouja"]').attr('disabled', 'disabled');
         // jQuery('.loadersmall').remove();
         if(dataID)
         {
            jQuery.ajax({
               url : '{{url("/")}}/court/dropdownlist/getdependentcourt/' +dataID,
               type : "GET",
               dataType : "json",
               success:function(data)
               {
                  jQuery('select[name="upazila"]').html('<div class="loadersmall"></div>');
                  //console.log(data);
                  // jQuery('#mouja_id').removeAttr('disabled');
                  // jQuery('#mouja_id option').remove();

                  jQuery('select[name="court"]').html('<option value="">-- নির্বাচন করুন --</option>');
                  jQuery.each(data, function(key,value){
                     jQuery('select[name="court"]').append('<option value="'+ key +'">'+ value +'</option>');
                  });
                  jQuery('.loadersmall').remove();
                  // $('select[name="mouja"] .overlay').remove();
                  // $("#loading").hide();
               }
            });
         }
         else
         {
            $('select[name="court"]').empty();
         }
      });*/

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




    <script>
        $('document').ready(function(){
            $('#preview').on('click',function(){
                var division_id = $('#division_id option:selected').text();
                var district_id = $('#district_id option:selected').text();
                var ct_id = $('#ct_id option:selected').text();
                var court_name = $('#court_name').val();
                $('#previewDivision_id').html(division);
                $('#previewDistrict_id').html(district);
                $('#previewUpazila_id').html(upazila);
                $('#previewCt_id').html(ct_id);
                $('#previewOffice_name').html(court_name);
                
            });
        }); 

    </script>
    @endsection     

   
    <!--end::Page Scripts-->


