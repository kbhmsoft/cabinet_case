<?php
$roleID = Auth::user()->role_id;
$officeInfo = user_office_info();
?>
<form class="form-inline" method="GET">
   <div class="row">
      <div class="col-lg-2 mb-5 px-2">
         <select name="case_category_id" class="w-100 form-control">
            <option value=""> মামলা ক্যাটেগরি </option>
            @foreach ($division_categories as $value)
               <option value="{{ $value->id }}"
                  {{ old('division_categories') == $value->id ? 'selected' : '' }}>
                  {{ $value->name_bn }} 
               </option>
             @endforeach
         </select>
         <!-- <input type="text" name="case_category_id"  class="w-100 form-control" placeholder="মামলার ক্যাটেগরি" autocomplete="off"> -->
      </div>

      <div class="col-lg-2 mb-5 px-2">
         <input type="text" name="date_start"  class="w-100 form-control common_datepicker" placeholder="তারিখ হতে" autocomplete="off">
      </div>
      <div class="col-lg-2 mb-5 px-2">
         <input type="text" name="date_end" class="w-100 form-control common_datepicker" placeholder="তারিখ পর্যন্ত" autocomplete="off">
      </div>

      <div class="col-lg-4 px-2">
         <div class="input-group mb-3">
            <input type="text" class="form-control" name="case_no" placeholder="মামলা নং" value="">
           <div class="input-group-append">
            <button type="submit" class="input-group-text btn btn-success ">অনুসন্ধান করুন</button>
           </div>
         </div>
      </div>
   </div>
</form>

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
      jQuery(document).ready(function () {
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
      });
   </script>
@endsection
