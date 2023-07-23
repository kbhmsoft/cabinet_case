@extends('layouts.cabinet.cab_default')

@section('content')
 
@php
    $roleID = Auth::user()->role_id;
    $officeInfo = user_office_info();
@endphp
<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-title h2 font-weight-bolder"> {{$page_title}} </h3>
      </div>
      @if($roleID == 1 || $roleID == 2 || $roleID == 3 || $roleID == 4 || $roleID == 27 || $roleID == 28 )
         <div class="card-toolbar">        
            <a href="{{ route('cabinet.office.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
               <i class="la la-plus"></i>নতুন অফিস এন্ট্রি
            </a>                
         </div>
      @endif
   </div>
   <div class="card-body">
      @if ($message = Session::get('success'))
      <!-- <div class="alert alert-success"> -->
         <!-- <p>{{ $message }}</p> -->
      <!-- </div> -->
      @endif

      <form class="form-inline" method="GET">
            <div class="form-group mb-2 mr-2">
              <select name="office_type" class="form-control">
                 <option value="">-বিভাগ নির্বাচন করুন-</option>3
                 @foreach ($office_types as $value)
                 <option value="{{ $value->id }}"{{ (isset($_GET['office_type']) ? $_GET['office_type'] : '') == $value->id ? 'selected' : '' }}> {{ $value->type_name_bn }} </option>
                 @endforeach
              </select>
           </div>
            <div class="form-group mb-2 mr-2">
              <input type="text" name="office_name" value="{{ (isset($_GET['office_name']) ? $_GET['office_name']:'')}}" class="form-control w-100" >
              
           </div>            
           <button type="submit" class="btn btn-success ">অনুসন্ধান করুন</button>
      </form>
      <table class="table table-hover mb-6 font-size-h6">
         <thead class="thead-light">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">অফিসের নাম</th>
               <th scope="col">অফিসের ধরণ</th>
               <th scope="col">স্ট্যাটাস</th>
               <th scope="col">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($offices as $row)
            <?php
              if($row->status == 1){
                  $officeStatus = '<span class="label label-inline label-light-primary font-weight-bold">এনাবল</span>';
               }else{
                  $officeStatus = '<span class="label label-inline label-light-primary font-weight-bold">ডিসএবল</span>';
               }
            ?>
            <tr>
               <th scope="row" class="tg-bn">{{ en2bn(++$i) }}.</th>
               <td>{{ $row->office_name_bn }}</td>          
               <td>{{ $row->office_type->type_name_bn }}</td>          
               <td><?=$officeStatus?></td>
               <td>
                  <a href="{{ route('cabinet.office.edit', $row->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">সংশোধন</a>
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>      
      {!! $offices->links() !!}  
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
<script type="text/javascript">
     jQuery(document).ready(function ()
      {
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
              url : '/office/dropdownlist/getdependentdistrict/' +dataID,
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
          if(dataID)
          {
            jQuery.ajax({
              url : '/office/dropdownlist/getdependentupazila/' +dataID,
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
            }
            else
            {
               $('select[name="upazila"]').empty();
            }
         });

      // Upazila Land Office Dropdown
      /*jQuery('select[name="upazila"]').on('change',function(){
          var dataID = jQuery(this).val(); 
          // var category_id = jQuery('#category_id option:selected').val();
          jQuery("#office_id").after('<div class="loadersmall"></div>');
          // $("#loading").html("<img src='{{ asset('media/preload.gif') }}' />");
          // jQuery('select[name="mouja"]').html('<option><div class="loadersmall"></div></option');
          // jQuery('select[name="mouja"]').attr('disabled', 'disabled');
          // jQuery('.loadersmall').remove();
          if(dataID)
          {
            jQuery.ajax({
              url : '/ulo/dropdownlist/getdependentulo/' +dataID,
              type : "GET",
              dataType : "json",
              success:function(data)
              {
               jQuery('select[name="ulo"]').html('<div class="loadersmall"></div>');
                  //console.log(data);
                  // jQuery('#mouja_id').removeAttr('disabled');
                  // jQuery('#mouja_id option').remove();
                  
                  jQuery('select[name="ulo"]').html('<option value="">-- নির্বাচন করুন --</option>');
                  jQuery.each(data, function(key,value){
                   jQuery('select[name="ulo"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
                  jQuery('.loadersmall').remove();
                  // $('select[name="mouja"] .overlay').remove();
                  // $("#loading").hide();
               }
            });
            }
            else
            {
               $('select[name="upazila_land_office"]').empty();
            }
         });*/
      });
</script>
@endsection


