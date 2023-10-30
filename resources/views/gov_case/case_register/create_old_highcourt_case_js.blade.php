{{-- @php
    $department = '';
@endphp --}}
<script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // common datepicker
    $('.common_datepicker').datepicker({
        orientation: "bottom left",
        format: "dd/mm/yyyy",
        todayHighlight: true,
        viewMode: 'years',
    });
    $('.common_yearpicker').datepicker({
        format: 'yyyy',
        startView: 'years',
        minViewMode: 'years',
        orientation: "bottom left",
        // format: "dd/mm/yyyy",
        // todayHighlight: true,
        // viewMode: 'years',
    });
</script>

<script type="text/javascript">
    // $('form').submit(function() {
    //     $('[disabled]').removeAttr('disabled');
    // })
    $(document).ready(function() {
        // addBadiRowFunc();

        var formType = $('#formType').val();
        if (formType != 'edit') {
            addMainBibadiRowFunc();
        }
        addFileRowFunc();
        addReplyFileRowFunc();
        addSuspensionOrderFileRowFunc();
        addFinalOrderFileRowFunc();
        addContemptFileRowFunc();
        $('select').select2();



        //===========caseType================//


        jQuery('select[name="case_category"]').on('change', function() {
            var dataID = jQuery(this).val();
            jQuery("#case_category_type").after('<div class="loadersmall"></div>');

            if (dataID) {
                jQuery.ajax({
                    url: '{{ url('/') }}/cabinet/case/dropdownlist/getdependentcasecategorytype/' +
                        dataID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        jQuery('select[name="case_category_type"]').html(
                            '<div class="loadersmall"></div>');

                        jQuery('select[name="case_category_type"]').html(
                            '<option value="">-- নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key, value) {
                            jQuery('select[name="case_category_type"]').append(
                                '<option value="' + key + '">' + value +
                                '</option>');
                        });
                        jQuery('.loadersmall').remove();
                        // $('select[name="mouja"] .overlay').remove();
                        // $("#loading").hide();
                    }
                });
            } else {
                $('select[name="case_category_type"]').empty();
            }
        });



        //===========GetConsernPersonByDesignation================//


        jQuery('select[name="concern_person_designation"]').on('change', function() {
            var dataID = jQuery(this).val();
            jQuery("#concern_user_id").after('<div class="loadersmall"></div>');

            if (dataID) {
                jQuery.ajax({
                    url: '{{ url('/') }}/cabinet/case/dropdownlist/getdependentconcernperson/' +
                        dataID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        jQuery('select[name="concern_user_id"]').html(
                            '<div class="loadersmall"></div>');

                        jQuery('select[name="concern_user_id"]').html(
                            '<option value="">-- নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key, value) {
                            jQuery('select[name="concern_user_id"]').append(
                                '<option value="' + key + '">' + value +
                                '</option>');
                        });
                        jQuery('.loadersmall').remove();
                        // $('select[name="mouja"] .overlay').remove();
                        // $("#loading").hide();
                    }
                });
            } else {
                $('select[name="concern_user_id"]').empty();
            }
        });
    });

    /*********************** Add multiple badi *************************/
    $("#addBadiRow").click(function(e) {
        addBadiRowFunc();
    });

    //add row function
    function addBadiRowFunc() {
        var items = '';
        items += '<tr>';
        items +=
            '<td><input type="text" required="required" name="badi_name[]" class="form-control form-control-sm" id="petisioner_name" placeholder=""><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items += '<input type="hidden" name="badi_id[]" value="">';

        items +=
            '<td><input type="text" required="required" name="badi_address[]" class="form-control form-control-sm" id="petisioner_address" placeholder=""><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
        items += '</tr>';
        $('#badiDiv tr:last').after(items);
    }

    function removeRowBadiBibadiFunc(id, url) {
        var dataId = $(id).attr("data-id");
        var params = $.extend({}, doAjax_params_default);
        params['url'] = "{{ url('cabinet/case/') }}/" + url + "/" + dataId;
        params['requestType'] = "POST";
        // params['data'] = {};
        params['successCallbackFunction'] = success;
        params['errorCallBackFunction'] = error;
        if (confirm("Are you sure you want to delete this information from database?") == true) {
            doAjax(params);
        }

        function success(data) {
            $(id).closest("tr").remove();
            toastr.success(data.success, "Success");
        }

        function error(data) {
            console.log(data);
        }

    }

    //remove row
    function removeBadiRow(id) {
        $(id).closest("tr").remove();
    }
    /************************ Add multiple bibadi *************************/
    function getDoptor(ministry = null, rowId, mainid = null) {
        var id = ministry.value;
        if (id == null) {
            id = mainid;
        }
        var params = $.extend({}, doAjax_params_default);
        params['url'] = "{{ url('/') }}/case/dropdownlist/getdependentDoptor/" + id;
        params['requestType'] = "GET";
        params['data'] = {};
        params['successCallbackFunction'] = success;
        params['errorCallBackFunction'] = error;
        doAjax(params);

        function success(data) {
            var row = '#' + rowId;
            // console.log(data);
            $(row + ' select[name="doptor[]"]').html('<div class="loadersmall"></div>');
            $(row + ' select[name="doptor[]"]').html('<option value="">-- নির্বাচন করুন --</option>');
            $.each(data, function(key, value) {
                $(row + ' select[name="doptor[]"]').append('<option value="' + key + '">' + value +
                    '</option>');
            });
        }

        function error(data) {
            console.log(data);
        }

    }

    function getMainDoptor(main_ministry = null, rowId, mainid = null) {
        var id = main_ministry.value;
        if (id == null) {
            id = mainid;
        }
        var params = $.extend({}, doAjax_params_default);
        params['url'] = "{{ url('/') }}/case/dropdownlist/getdependentDoptor/" + id;
        params['requestType'] = "GET";
        params['data'] = {};
        params['successCallbackFunction'] = success;
        params['errorCallBackFunction'] = error;
        doAjax(params);

        function success(data) {
            var row = '#' + rowId;
            // console.log(data);
            $(row + ' select[name="main_doptor[]"]').html('<div class="loadersmall"></div>');
            $(row + ' select[name="main_doptor[]"]').html('<option value="">-- নির্বাচন করুন --</option>');
            $.each(data, function(key, value) {
                $(row + ' select[name="main_doptor[]"]').append('<option value="' + key + '">' + value +
                    '</option>');
            });
        }

        function error(data) {
            console.log(data);
        }

    }
    /************************ Add multiple Main bibadi *************************/

    $("#addMainBibadiRow").click(function(e) {
        addMainBibadiRowFunc();
    });

    //add row function
    function addMainBibadiRowFunc() {
        var countVal = parseInt($('#mainBibadi_count').val());
        $('#mainBibadi_count').val(countVal + 1);
        var mk_main = $('#MainBibadiDiv tr').length;
        var MainCount = $('#MainBibadiDiv tr').length;
        // console.log(MainCount);
        $('#MainBibadiDiv tr:last').after(ItemMain(mk_main + 1, 'other'));
        /*if(MainCount ==3){
            $('#MainBibadiDiv tr:last').after(ItemMain(MainCount, 'main'));
        }*/

        function ItemMain(count, type = NULL) {
            var items = '';
            items += '<tr id="bibadi_' + (count) + '">';
            items +=
                '<td><select  required="required"  name="main_respondent[]" id="main_babadi_name" class="form-control form-control-sm"><option value="">-- নির্বাচন করুন --</option>@foreach ($ministrys as $value)<option value="{{ $value->id }}" {{ old('main_ministry') == $value->id ? 'selected' : '' }}> {{ $value->office_name_bn }} </option>@endforeach</select><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
            items += '<input type="hidden" name="bibadi_id[]" value="">';
            // items +='<td><select name="main_doptor[]" id="doptor_id" class="form-control form-control-sm"><option value="">-- নির্বাচন করুন --</option></select></td>';
            // console.log(count);
            if (countVal != 1) {
                items +=
                    '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeMainBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            }
            items += '</tr>';
            // console.log(items);
            return items;
        }
    }

    //remove row function
    function removeMainBibadiRow(id) {
        $(id).closest("tr").remove();
    }
    /************************ //Add multiple Main bibadi *************************/

    $("#addBibadiRow").click(function(e) {
        addBibadiRowFunc();
    });

    //add row function
    function addBibadiRowFunc() {
        var mk = $('#bibadiDiv tr').length;
        var MainCount = $('#MainBibadiDiv tr').length;
        // console.log(MainCount);
        $('#bibadiDiv tr:last').after(Item(mk + 1, 'other'));
        /*if(MainCount ==3){
            $('#MainBibadiDiv tr:last').after(Item(MainCount, 'main'));
        }*/

        function Item(count, type = NULL) {
            var items = '';
            items += '<tr id="bibadi_' + (count) + '">';
            items +=
                '<td><select required="required" name="other_respondent[]"  class="form-control form-control-sm other_respondentCls"><option value="">-- নির্বাচন করুন --</option>@foreach ($ministrys as $value)<option value="{{ $value->id }}" {{ old('ministry') == $value->id ? 'selected' : '' }}> {{ $value->office_name_bn }} </option>@endforeach</select><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
            items += '<input type="hidden" name="bibadi_id[]" value="">';
            // items +='<td><select name="doptor[]" id="doptor_id" class="form-control form-control-sm"><option value="">-- নির্বাচন করুন --</option></select></td>';
            // console.log(count);
            if (type == 'other') {
                items +=
                    '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            }
            items += '</tr>';
            // console.log(items);
            return items;
        }
        $('.other_respondentCls').select2();
    }

    //remove row function
    function removeBibadiRow(id) {
        $(id).closest("tr").remove();
    }

    /************************ Add multiple survey *************************/
    $("#addSurveyRow").click(function(e) {
        addSurveyRowFunc();
    });
</script>




<script>
    var numbers = {
        0: '০',
        1: '১',
        2: '২',
        3: '৩',
        4: '৪',
        5: '৫',
        6: '৬',
        7: '৭',
        8: '৮',
        9: '৯'
    };

    function replaceNumbers(input) {
        var output = [];
        for (var i = 0; i < input.length; ++i) {
            if (numbers.hasOwnProperty(input[i])) {
                output.push(numbers[input[i]]);
            } else {
                output.push(input[i]);
            }
        }
        return output.join('');
    }

    // document.getElementById('r').textContent = replaceNumbers('count'); // comment on 07/11/2022 shahajahan
</script>

{{-- start HighCourt Old Form Vallidation --}}
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}


// {{-- end HighCourt Old Form Vallidation --}}







<script>
    function myFunction() {
        confirm("আপনি কি সংরক্ষণ করতে চান?");
    }

    $('document').ready(function() {
        $('#preview').on('click', function() {
            var court = $('#court option:selected').text();
            var division = $('#division_id option:selected').text();
            var district_n = $('#district_id option:selected').text();
            var upazila = $('#upazila_id option:selected').text();
            var mouja_id = $('#mouja_id option:selected').text();
            var case_type = $('#case_type option:selected').text();
            var case_no = $('#case_no').val();
            var case_date = $('#case_date').val();
            var tafsil = $('#tafsil').val();
            var chowhaddi = $('#chowhaddi').val();
            var comments = $('#comments').val();
            var count = 0;
            var badi_name = $("form input[name='badi_name[]']").map(function() {
                count++;
                return (count + '. ' + $(this).val() + '<br>')
            }).get();
            var badi_spouse_name = $("form input[name='badi_spouse_name[]']").map(function() {
                return ($(this).val() + '<br>')
            }).get();
            var badi_address = $("form input[name='badi_address[]']").map(function() {
                return ($(this).val() + '<br>')
            }).get();
            var count = 0;
            var bibadi_name = $("form input[name='bibadi_name[]']").map(function() {
                count++;
                return (count + '. ' + $(this).val() + '<br>')
            }).get();
            var bibadi_spouse_name = $("form input[name='bibadi_spouse_name[]']").map(function() {
                return ($(this).val() + '<br>')
            }).get();
            var bibadi_address = $("form input[name='bibadi_address[]']").map(function() {
                return ($(this).val() + '<br>')
            }).get();
            var count = 0;
            var st_id = $("form select[name='st_id[]']").map(function() {
                return ($(this).find("option:selected").text()) + '   '
            }).get();
            var count = 0;
            var khotian_no = $("form input[name='khotian_no[]']").map(function() {
                count++;
                return (count + '. ' + $(this).val() + '<br>')
            }).get();
            var count = 0;
            var daag_no = $("form input[name='daag_no[]']").map(function() {
                count++;
                return (count + '. ' + $(this).val() + '<br>')
            }).get();
            var count = 0;
            var lt_id = $("form select[name='lt_id[]']").map(function() {
                return ($(this).find("option:selected").text()) + '   '
            }).get();
            var count = 0;
            var land_size = $("form input[name='land_size[]']").map(function() {
                count++;
                return (count + '. ' + $(this).val() + '<br>')
            }).get();
            var count = 0;
            var land_demand = $("form input[name='land_demand[]']").map(function() {
                count++;
                return (count + '. ' + $(this).val() + '<br>')
            }).get();
            var count = 0;

            /* var role_id = $('#role_id option:selected').text();
            var office_id = $('#office_id option:selected').text();*/
            $('#previewCourt').html(court);
            $('#previewDivision').html(division);
            $('#previewDistrict').html(district_n);
            $('#previewUpazila').html(upazila);
            $('#previewMouja_id').html(mouja_id);
            $('#previewCase_type').html(case_type);
            $('#previewCase_no').html(case_no);
            $('#previewCase_date').html(case_date);
            $('#previewTafsil').html(tafsil);
            $('#previewChowhaddi').html(chowhaddi);
            $('#previewComments').html(comments);
            $('#previewBadi_name').html(badi_name);
            $('#previewBadi_spouse_name').html(badi_spouse_name);
            $('#previewBadi_address').html(badi_address);
            $('#previewBibadi_name').html(bibadi_name);
            $('#previewBibadi_spouse_name').html(bibadi_spouse_name);
            $('#previewBibadi_address').html(bibadi_address);
            $('#previewSt_id').html(st_id);
            $('#previewKhotian_no').html(khotian_no);
            $('#previewDaag_no').html(daag_no);
            $('#previewLt_id').html(lt_id);
            $('#previewLand_size').html(land_size);
            $('#previewLand_demand').html(land_demand);

        });
    });
</script>
<script>
    // ===========================Button Disable=========================//
    var caseIDForAnswer = $('#caseIDForAnswer').val();
    if (!(caseIDForAnswer)) {
        $('#sendingReplySaveBtn').prop('disabled', true);
        $('#sendingReplySaveBtn').addClass("disable-button");
    }

    var caseIDForSuspention = $('#caseIDForSuspention').val();
    if (!(caseIDForSuspention)) {
        $('#suspensionOrderSaveBtn').prop('disabled', true);
        $('#suspensionOrderSaveBtn').addClass("disable-button");
    }

    var caseIDForFinalOrder = $('#caseIDForFinalOrder').val();
    if (!(caseIDForFinalOrder)) {
        $('#finalOrderSaveBtn').prop('disabled', true);
        $('#finalOrderSaveBtn').addClass("disable-button");
    }

    var caseIDForContempt = $('#caseIDForContempt').val();
    if (!(caseIDForContempt)) {
        $('#contemptCaseSaveBtn').prop('disabled', true);
        $('#contemptCaseSaveBtn').addClass("disable-button");
    }
    // ===========================Button Disable=========================//



    // ================================Case General Info save==================================

    $('#caseGeneralInfoForm').submit(function(e) {
        e.preventDefault();
        $('#caseGeneralInfoSaveBtn').addClass('spinner spinner-white spinner-right disabled');
        Swal.fire({
            title: 'আপনি কি মামলার সাধারন তথ্য সংরক্ষণ করতে চান?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.isConfirmed) {

                var formData = new FormData(this);
                $.ajax({

                    type: 'POST',
                    url: "{{ route('cabinet.case.store') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: (data) => {
                        $('#caseGeneralInfoSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        $orderData = data;
                        Swal.fire(
                            'Saved!',
                            'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                            'success'
                        )
                        console.log(data);

                        $("#sending_reply_tab").click();
                        $("#caseIDForAnswer").val(data.caseId);
                        $("#caseIDForSuspention").val(data.caseId);
                        $("#caseIDForFinalOrder").val(data.caseId);
                        $("#caseIDForContempt").val(data.caseId);


                        $('#sendingReplySaveBtn').prop('disabled', false);
                        $('#sendingReplySaveBtn').removeClass("disable-button");
                        $('#suspensionOrderSaveBtn').prop('disabled', false);
                        $('#suspensionOrderSaveBtn').removeClass("disable-button");
                        $('#finalOrderSaveBtn').prop('disabled', false);
                        $('#finalOrderSaveBtn').removeClass("disable-button");
                        $('#contemptCaseSaveBtn').prop('disabled', false);
                        $('#contemptCaseSaveBtn').removeClass("disable-button");

                    },
                    error: function(data) {
                        console.log(data);
                        $('#caseGeneralInfoSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');

                    }
                });
            }
        })

    });
    // ================================Case General Info save==================================

    // ================================Sending Replay Save==================================//



    $('#sendingReplyForm').submit(function(e) {
        // alert(1);
        e.preventDefault();
        $('#sendingReplySaveBtn').addClass('spinner spinner-white spinner-right disabled');

        Swal.fire({
            title: 'আপনি কি মামলার জবাব প্রেরনের তথ্য সংরক্ষণ করতে চান?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.isConfirmed) {

                var formData = new FormData(this);
                $.ajax({

                    type: 'POST',
                    url: "{{ route('cabinet.case.sendingReplyStore') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: (data) => {
                        $('#sendingReplySaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        $orderData = data;
                        Swal.fire(
                            'Saved!',
                            'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                            'success'
                        )
                        console.log(data);
                        // console.log(data.caseId);
                        $("#suspension_order").click();
                        $("#caseIDForSuspention").val(data.caseId);
                        $("#caseIDForFinalOrder").val(data.caseId);
                        $("#caseIDForContempt").val(data.caseId);
                        $('#sendingReplySaveBtn').prop('disabled', false);
                        $('#sendingReplySaveBtn').removeClass("disable-button");
                        $('#suspensionOrderSaveBtn').prop('disabled', false);
                        $('#suspensionOrderSaveBtn').removeClass("disable-button");
                        $('#finalOrderSaveBtn').prop('disabled', false);
                        $('#finalOrderSaveBtn').removeClass("disable-button");
                        $('#contemptCaseSaveBtn').prop('disabled', false);
                        $('#contemptCaseSaveBtn').removeClass("disable-button");

                    },
                    error: function(data) {
                        console.log(data);
                        $('#sendingReplySaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');

                    }
                });
            }
        })

    });
    // ================================Sending Replay Save==================================//

    // ================================Suspention Order Save==================================//



    $('#suspensionOrderForm').submit(function(e) {
        // alert(1);
        e.preventDefault();
        $('#suspensionOrderSaveBtn').addClass('spinner spinner-white spinner-right disabled');

        Swal.fire({
            title: 'আপনি কি মামলার জবাব প্রেরনের তথ্য সংরক্ষণ করতে চান?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.isConfirmed) {

                var formData = new FormData(this);
                $.ajax({

                    type: 'POST',
                    url: "{{ route('cabinet.case.suspensionOrderStore') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: (data) => {
                        $('#suspensionOrderSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        $orderData = data;
                        Swal.fire(
                            'Saved!',
                            'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                            'success'
                        )
                        console.log(data);
                        // console.log(data.caseId);
                        $("#final_order").click();
                        $("#caseIDForSuspention").val(data.caseId);
                        $("#caseIDForFinalOrder").val(data.caseId);
                        $("#caseIDForContempt").val(data.caseId);
                        $('#sendingReplySaveBtn').prop('disabled', false);
                        $('#sendingReplySaveBtn').removeClass("disable-button");
                        $('#suspensionOrderSaveBtn').prop('disabled', false);
                        $('#suspensionOrderSaveBtn').removeClass("disable-button");
                        $('#finalOrderSaveBtn').prop('disabled', false);
                        $('#finalOrderSaveBtn').removeClass("disable-button");
                        $('#contemptCaseSaveBtn').prop('disabled', false);
                        $('#contemptCaseSaveBtn').removeClass("disable-button");

                    },
                    error: function(data) {
                        console.log(data);
                        $('#suspensionOrderSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');

                    }
                });
            }
        })

    });
    // ================================Suspention Order Save==================================//

    // ================================Final Order Save==================================//



    $('#finalOrderForm').submit(function(e) {
        // alert(1);
        e.preventDefault();
        $('#finalOrderSaveBtn').addClass('spinner spinner-white spinner-right disabled');

        Swal.fire({
            title: 'আপনি কি মামলার জবাব প্রেরনের তথ্য সংরক্ষণ করতে চান?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.isConfirmed) {

                var formData = new FormData(this);
                $.ajax({

                    type: 'POST',
                    url: "{{ route('cabinet.case.finalOrderStore') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: (data) => {
                        $('#finalOrderSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        $orderData = data;
                        Swal.fire(
                            'Saved!',
                            'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                            'success'
                        )
                        console.log(data);
                        // console.log(data.caseId);
                        $("#contempt_case").click();
                        $("#caseIDForSuspention").val(data.caseId);
                        $("#caseIDForFinalOrder").val(data.caseId);
                        $("#caseIDForContempt").val(data.caseId);
                        $('#sendingReplySaveBtn').prop('disabled', false);
                        $('#sendingReplySaveBtn').removeClass("disable-button");
                        $('#suspensionOrderSaveBtn').prop('disabled', false);
                        $('#suspensionOrderSaveBtn').removeClass("disable-button");
                        $('#finalOrderSaveBtn').prop('disabled', false);
                        $('#finalOrderSaveBtn').removeClass("disable-button");
                        $('#contemptCaseSaveBtn').prop('disabled', false);
                        $('#contemptCaseSaveBtn').removeClass("disable-button");

                    },
                    error: function(data) {
                        console.log(data);
                        $('#finalOrderSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');

                    }
                });
            }
        })

    });
    // ================================Final Order Save==================================//

    // ================================Final Order Save==================================//



    $('#contemptCaseForm').submit(function(e) {
        // alert(1);
        e.preventDefault();
        $('#contemptCaseSaveBtn').addClass('spinner spinner-white spinner-right disabled');

        Swal.fire({
            title: 'আপনি কি মামলার জবাব প্রেরনের তথ্য সংরক্ষণ করতে চান?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.isConfirmed) {

                var formData = new FormData(this);
                $.ajax({

                    type: 'POST',
                    url: "{{ route('cabinet.case.contemptCaseStore') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: (data) => {
                        $('#contemptCaseSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        $orderData = data;
                        Swal.fire(
                            'Saved!',
                            'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                            'success'
                        )
                        console.log(data);
                        // console.log(data.caseId);
                        // $("#contempt_case").click();
                        $("#caseIDForSuspention").val(data.caseId);
                        $("#caseIDForFinalOrder").val(data.caseId);
                        $("#caseIDForContempt").val(data.caseId);

                    },
                    error: function(data) {
                        console.log(data);
                        $('#contemptCaseSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');

                    }
                });
            }
        })

    });
    // ================================Final Order Save==================================//
</script>
<!--end::Page Scripts-->
@include('components.Ajax')
<script>
    function caseCategoryGet(caseDivitionId, category_id = null, categoryDivId = null) {
        var catId = '#' + category_id;
        var divId = '#' + categoryDivId;
        $(divId).addClass('spinner spinner-primary spinner-left');
        $(catId).empty();
        var id = caseDivitionId.value;
        if (!id) {
            $(catId).html('<option value="">-- মামলার বিভাগ নির্বাচন করুন --</option>');
            $(divId).removeClass('spinner spinner-primary spinner-left');
            return;
        }
        var params = $.extend({}, doAjax_params_default);
        params['url'] = "{{ url('cabinet/case/getCaseCategory') }}/" + id;
        params['requestType'] = "POST";
        params['data'] = {};
        params['successCallbackFunction'] = success;
        params['errorCallBackFunction'] = error;
        doAjax(params);

        function success(data) {
            $(catId).html('<option value="">-- নির্বাচন করুন --</option>');
            $.each(data, function(key, value) {
                $(catId).append('<option value="' + key + '">' + value + '</option>');
            });
            $(divId).removeClass('spinner spinner-primary spinner-left');
        }

        function error(data) {
            console.log(data);
        }
    }

    // ============= Add Attachment Row ========= start =========
    $("#addFileRow").click(function(e) {
        addFileRowFunc();
    });
    //add row function
    function addFileRowFunc() {
        var count = parseInt($('#other_attachment_count').val());
        var formType = $('#formType').val();
        $('#other_attachment_count').val(count + 1);
        var items = '';
        items += '<tr>';
        items += '<td><input type="text" required="required" name="file_type[]" id="customFileName' + count +
            '" class="form-control form-control-sm" placeholder=""><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><div class="custom-file"><input type="file" accept="application/pdf" name="file_name[]" onChange="attachmentTitle(' +
            count + ',this)" class="custom-file-input" id="customFile' + count + '" /><label id="file_error' + count +
            '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-input' +
            count + '" for="customFile' + count + '">ফাইল নির্বাচন করুন</label></div></td>';
        items +=
            '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
        items += '</tr>';
        $('#fileDiv tr:last').after(items);

        if (formType == 'edit') {
            $(`#customFile${count}`).attr('required', false);
            $(`#customFileName${count}`).attr('required', false);
        }
    }




    // ============= Add Reply Attachment Row ========= start =========
    $("#addReplyFileRow").click(function(e) {
        addReplyFileRowFunc();
    });
    //add row function
    function addReplyFileRowFunc() {
        var count = parseInt($('#reply_attachment_count').val());
        var formType = $('#formType').val();
        $('#reply_attachment_count').val(count + 1);
        var items = '';
        items += '<tr>';
        items += '<td><input type="text" name="reply_file_type[]" id="customreplyFileName' + count +
            '" class="form-control form-control-sm" placeholder=""><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><div class="custom-file"><input type="file" accept="application/pdf" name="reply_file_name[]" onChange="replyAttachmentTitle(' +
            count + ',this)" class="custom-file-input" id="customReplyFile' + count + '" /><label id="file_error' +
            count +
            '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-reply-input' +
            count + '" for="customReplyFile' + count + '">ফাইল নির্বাচন করুন</label></div></td>';
        items +=
            '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
        items += '</tr>';
        $('#replyFileDiv tr:last').after(items);

        if (formType == 'edit') {
            $(`#customReplyFile${count}`).attr('required', false);
            $(`#customreplyFileName${count}`).attr('required', false);
        }
    }






    // ============= Add Suspention Order Attachment Row ========= start =========
    $("#addSuspensionOrderFileRow").click(function(e) {
        addSuspensionOrderFileRowFunc();
    });
    //add row function
    function addSuspensionOrderFileRowFunc() {
        var count = parseInt($('#suspension_order_attachment_count').val());
        var formType = $('#formType').val();
        $('#suspension_order_attachment_count').val(count + 1);
        var items = '';
        items += '<tr>';
        items += '<td><input type="text" name="suspension_file_type[]" id="customSuspensionFileName' + count +
            '" class="form-control form-control-sm" placeholder=""><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><div class="custom-file"><input type="file" accept="application/pdf" name="suspension_file_name[]" onChange="suspensionAttachmentTitle(' +
            count + ',this)" class="custom-file-input" id="customSuspensionFile' + count + '" /><label id="file_error' +
            count +
            '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-suspension-input' +
            count + '" for="customSuspensionFile' + count + '">ফাইল নির্বাচন করুন</label></div></td>';
        items +=
            '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
        items += '</tr>';
        $('#suspensionOrderFileDiv tr:last').after(items);

        if (formType == 'edit') {
            $(`#customSuspensionFile${count}`).attr('required', false);
            $(`#customSuspensionFileName${count}`).attr('required', false);
        }
    }






    // ============= Add Final Order Attachment Row ========= start =========
    $("#addFinalOrderFileRow").click(function(e) {
        addFinalOrderFileRowFunc();
    });
    //add row function
    function addFinalOrderFileRowFunc() {
        var count = parseInt($('#final_order_attachment_count').val());
        var formType = $('#formType').val();
        $('#final_order_attachment_count').val(count + 1);
        var items = '';
        items += '<tr>';
        items += '<td><input type="text" name="final_order_file_type[]" id="customFinalOrderFileName' + count +
            '" class="form-control form-control-sm" placeholder=""><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><div class="custom-file"><input type="file" accept="application/pdf" name="final_order_file_name[]" onChange="finalAttachmentTitle(' +
            count + ',this)" class="custom-file-input" id="customFinalFile' + count + '" /><label id="file_error' +
            count +
            '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-final-input' +
            count + '" for="customFinalFile' + count + '">ফাইল নির্বাচন করুন</label></div></td>';
        items +=
            '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
        items += '</tr>';
        $('#finalOrderFileDiv tr:last').after(items);

        if (formType == 'edit') {
            $(`#customFinalFile${count}`).attr('required', false);
            $(`#customFinalOrderFileName${count}`).attr('required', false);
        }
    }





    // ============= Add Contempt Case Attachment Row ========= start =========
    $("#addContemptFileRow").click(function(e) {
        addContemptFileRowFunc();
    });
    //add row function
    function addContemptFileRowFunc() {
        var count = parseInt($('#contempt_attachment_count').val());
        var formType = $('#formType').val();
        $('#contempt_attachment_count').val(count + 1);
        var items = '';
        items += '<tr>';
        items += '<td><input type="text" name="contempt_file_type[]" id="customContemptFileName' + count +
            '" class="form-control form-control-sm" placeholder=""><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><div class="custom-file"><input type="file" accept="application/pdf" name="contempt_file_name[]" onChange="contemptAttachmentTitle(' +
            count + ',this)" class="custom-file-input" id="customContemptFile' + count + '" /><label id="file_error' +
            count +
            '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-contempt-input' +
            count + '" for="customContemptFile' + count + '">ফাইল নির্বাচন করুন</label></div></td>';
        items +=
            '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
        items += '</tr>';
        $('#contemptFileDiv tr:last').after(items);

        if (formType == 'edit') {
            $(`#customContemptFile${count}`).attr('required', false);
            $(`#customContemptFileName${count}`).attr('required', false);
        }
    }


    $(document).ready(function() {
        $('select').select2();
    });




    //Attachment Title Change
    function attachmentTitle(id) {
        // var value = $('#customFile' + id).val();
        var value = $('#customFile' + id)[0].files[0];
        $('.custom-input' + id).text(value['name']);
    }
    //Attachment Title Change
    function replyAttachmentTitle(id) {
        // var value = $('#customFile' + id).val();
        var value = $('#customReplyFile' + id)[0].files[0];
        $('.custom-reply-input' + id).text(value['name']);
    }
    //Attachment Title Change
    function suspensionAttachmentTitle(id) {
        // var value = $('#customFile' + id).val();
        var value = $('#customSuspensionFile' + id)[0].files[0];
        $('.custom-suspension-input' + id).text(value['name']);
    }
    //Attachment Title Change
    function finalAttachmentTitle(id) {
        // var value = $('#customFile' + id).val();
        var value = $('#customFinalFile' + id)[0].files[0];
        $('.custom-final-input' + id).text(value['name']);
    }
    //Attachment Title Change
    function contemptAttachmentTitle(id) {
        // var value = $('#customFile' + id).val();
        var value = $('#customContemptFile' + id)[0].files[0];
        $('.custom-contempt-input' + id).text(value['name']);
    }
    //remove Attachment
    function removeBibadiRow(id) {
        $(id).closest("tr").remove();
    }
</script>
{{-- for form validation error in modal show --}}
<script>
    $(document).ready(function() {
        $(".save-button").click(function(e) {
            e.preventDefault();

            var formValid = true;
            var emptyFields = [];

            $("#firstrequriedfields [required]").each(function() {
                var fieldValue = $(this).val().trim();
                if (fieldValue === '') {
                    formValid = false;
                    var labelForField = $("label[for='" + $(this).attr("id") + "']");

                    emptyFields.push(labelForField.text().trim().replace('*', ''));
                }
            });

            $("#secondrequriedfields [required]").each(function() {
                var fieldValue = $(this).val().trim();
                var fieldName = $(this).attr("name").replace('[]', '');

                if (fieldValue === '') {
                    formValid = false;
                    // var labelForField = $("label[for='" + $(this).attr("id") + "']");
                    emptyFields.push($("." + fieldName).text().trim().replace('*', ''));
                }
            });


            $("#thirdrequriedfields [required]").each(function() {
                var fieldValue = $(this).val().trim();
                var fieldName = $(this).attr("name").replace('[]', '');

                if (fieldValue === '') {
                    formValid = false;
                    emptyFields.push($("." + fieldName).text().trim().replace('*', ''));
                }
            })

            if (formValid) {
                console.log("Form is valid.");
                $('#saveOldHighCourtCaseBtn').addClass('spinner spinner-white spinner-right disabled');
                Swal.fire({
                    title: 'আপনি কি মামলার সাধারন তথ্য সংরক্ষণ করতে চান?',

                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        alert('okay')
                        // var formData = new FormData(this);
                        var formData = new FormData(document.getElementById(
                            "oldCaseGeneralInfoFrom"));

                        console.log(formData);
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('cabinet.case.store') }}",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                console.log(data);
                                $('#saveOldHighCourtCaseBtn').removeClass(
                                    'spinner spinner-white spinner-right disabled'
                                );
                                $orderData = data;
                                Swal.fire(
                                    'Saved!',
                                    'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                                    'success'
                                )
                                console.log(data);
                                $("#sending_reply_tab").click();
                                $("#caseIDForAnswer").val(data.caseId);
                                $("#caseIDForSuspention").val(data.caseId);
                                $("#caseIDForFinalOrder").val(data.caseId);
                                $("#caseIDForContempt").val(data.caseId);
                                $('#sendingReplySaveBtn').prop('disabled', false);
                                $('#sendingReplySaveBtn').removeClass(
                                    "disable-button");
                                $('#suspensionOrderSaveBtn').prop('disabled',
                                    false);
                                $('#suspensionOrderSaveBtn').removeClass(
                                    "disable-button");
                                $('#finalOrderSaveBtn').prop('disabled', false);
                                $('#finalOrderSaveBtn').removeClass(
                                    "disable-button");
                                $('#contemptCaseSaveBtn').prop('disabled', false);
                                $('#contemptCaseSaveBtn').removeClass(
                                    "disable-button");
                            },
                            error: function(data) {
                                console.log(data);
                                Swal.fire(
                                    'Oops...!',
                                    data['responseJSON']['errors']['case_no'][0],
                                    'error'
                                )
                                $('#saveOldHighCourtCaseBtn').removeClass(
                                    'spinner spinner-white spinner-right disabled'
                                );
                            }
                        });
                    }
                })

            } else {

                $("#emptyFieldsList").empty();
                emptyFields.forEach(function(fieldLabel, index) {
                    $("#emptyFieldsList").append("<li> " + (index + 1) + ". " +
                        "Please fill out this field " + fieldLabel +
                        "</li>");
                });
                $("#myModal").css("display", "block");
            }
        });

        $(".close-button").click(function() {
            $("#myModal").css("display", "none");
        });

        $(".save-button").click(function(event) {
            if (event.target.id === "myModal") {
                $("#myModal").css("display", "none");
            }
        });

    });
</script>
<script>
    // Case no can only be bangla and english
    function allowBanglaAndEnglishNumerals(event) {
        var charCode = event.which || event.keyCode;
        // Allow Bangla numerals: ০-৯ (ASCII range: 2400-2409) and English numerals: 0-9 (ASCII range: 48-57)
        if ((charCode >= 2400 && charCode <= 2409) || (charCode >= 48 && charCode <= 57)) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    }
</script>
