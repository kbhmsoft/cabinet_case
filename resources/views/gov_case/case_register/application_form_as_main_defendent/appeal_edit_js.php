{{-- @php
    $department = '';
@endphp --}}
<style>
    .readonly-field {
        background-color: #e7b00b;
    }
</style>
<script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
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
    $(document).ready(function() {
        var formType = $('#formType').val();
        if (formType != 'edit') {
            addMainBibadiRowFunc();
        }
        addFileRowFunc();
        addFinalOrderFileRowFunc();
        $('select').select2();
        // addAppealFinalOrderFileRowFunc();

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



        //=========== start CASE Origin case No   ================//


        // jQuery('select[name="case_category_origin"]').on('change', function() {
        //     var dataID = jQuery(this).val();
        //     // alert(dataID);
        //     jQuery("#case_number_origin").after('<div class="loadersmall"></div>');

        //     if (dataID) {
        //         jQuery.ajax({
        //             url: '{{ url('/') }}/cabinet/case/dropdownlist/getdependentorigincasenumber/' +
        //                 dataID,
        //             // alert(url);
        //             type: "GET",
        //             dataType: "json",
        //             success: function(data) {
        //                 jQuery('select[name="case_number_origin"]').html(
        //                     '<div class="loadersmall"></div>');

        //                 jQuery('select[name="case_number_origin"]').html(
        //                     '<option value="">-- নির্বাচন করুন --</option>');
        //                 jQuery.each(data, function(key, value) {
        //                     jQuery('select[name="case_number_origin"]').append(
        //                         '<option value="' + key + '">' + value +
        //                         '</option>');
        //                 });
        //                 jQuery('.loadersmall').remove();

        //             }
        //             // console.log(data);
        //         });
        //     } else {
        //         $('select[name="case_number_origin"]').empty();
        //     }
        // });





        jQuery('select[name="case_category_origin"]').on('change', function() {
            var dataID = jQuery(this).val();
            var caseNumberDropdown = jQuery('select[name="case_number_origin"]');
            var loadersmall = '<div class="loadersmall"></div>';

            caseNumberDropdown.after(loadersmall);

            if (dataID) {
                jQuery.ajax({
                    url: '{{ url('/') }}/cabinet/case/dropdownlist/getdependentorigincasenumber/' +
                        dataID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {

                        caseNumberDropdown.empty();
                        caseNumberDropdown.append(
                            '<option value="">-- নির্বাচন করুন --</option>');

                        jQuery.each(data, function(key, value) {
                            caseNumberDropdown.append(
                                `<option value="${value.id}">${value.case_no}/${value.year}</option>`
                            );
                        });

                        jQuery('.loadersmall').remove();
                    }
                });
            } else {
                caseNumberDropdown.empty();
                jQuery('.loadersmall').remove();
            }
        });


        jQuery('select[name="case_number_origin"]').on('change', function() {
            var dataID = jQuery(this).val();
            //   console.log(dataID);
            var showHighCourtCaseDiv = $('#showHighCourtCaseDiv');

            if (dataID) {
                jQuery.ajax({
                    url: '{{ url('/') }}/cabinet/case/highcourtcasedetails/' + dataID,
                    type: "GET",
                    success: function(response) {
                        showHighCourtCaseDiv.html(
                            response);
                        // console.log(response);
                    },
                    error: function() {
                        showHighCourtCaseDiv.empty();
                    }
                });
            } else {
                showHighCourtCaseDiv.empty();
            }
        });

        //===========GetConsernPersonByDesignation================//


        jQuery('select[name="concern_new_appeal_person_designation"]').on('change', function() {
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
            '<td><input type="text" name="badi_name[]" class="form-control form-control-sm" placeholder="" required><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items += '<input type="hidden" name="badi_id[]" value="">';

        items +=
            '<td><input type="text" name="badi_address[]" class="form-control form-control-sm" placeholder="" required><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        // items +=
        //     '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
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
            // console.log(data);
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
            // console.log(data);
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
            // console.log(data);
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
                '<td><select  name="main_respondent[]" class="form-control form-control-sm main_respondent" required><option value="">-- নির্বাচন করুন --</option>@foreach ($ministrys as $value)<option value="{{ $value->doptor_office_id }}" {{ old('main_ministry') == $value->doptor_office_id ? 'selected' : '' }}> {{ $value->office_name_bn }} </option>@endforeach</select><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
            items += '<input type="hidden" name="bibadi_id[]" value="">';

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
                '<td><select name="other_respondent[]" class="form-control form-control-sm other_respondentCls" required><option value="">-- নির্বাচন করুন --</option>@foreach ($ministrys as $value)<option value="{{ $value->doptor_office_id }}" {{ old('ministry') == $value->doptor_office_id ? 'selected' : '' }}> {{ $value->office_name_bn }} </option>@endforeach</select><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
            items += '<input type="hidden" name="bibadi_id[]" value="">';
            // items +='<td><select name="doptor[]" id="doptor_id" class="form-control form-control-sm"><option value="">-- নির্বাচন করুন --</option></select></td>';
            // console.log(count);
            // if (type == 'other') {
            //     items +=
            //         '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            // }
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
    // var caseIDForAnswer = $('#caseIDForAnswer').val();
    // if (!(caseIDForAnswer)) {
    //     $('#sendingReplySaveBtn').prop('disabled', true);
    //     $('#sendingReplySaveBtn').addClass("disable-button");
    // }

    var caseIDForFinalOrder = $('#caseIDForFinalOrder').val();
    if (!(caseIDForFinalOrder)) {
        $('#appealFinalOrderSaveBtn').prop('disabled', true);
        $('#appealFinalOrderSaveBtn').addClass("disable-button");
    }

    // ===========================Button Disable=========================//



    // ================================Case General Info save==================================

    $('#appealCaseGeneralInfoEditForm').submit(function(e) {
        // alert(1);
        e.preventDefault();
        $('#appealCaseGeneralInfoEditSaveBtn').addClass('spinner spinner-white spinner-right disabled');
        Swal.fire({
            title: 'আপনি কি মামলার সাধারন তথ্য সংরক্ষণ করতে চান?',
            // text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {

                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('cabinet.case.appealEditStore') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: (data) => {
               
                        $('#appealCaseGeneralInfoEditSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        $orderData = data;
                        Swal.fire(
                            'Saved!',
                            'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                            'success'
                        )

                        $("#final_order").click();
                        $("#caseIDForFinalOrder").val(data.caseId);
                        $('#finalOrderSaveBtn').prop('disabled', false);
                        $('#finalOrderSaveBtn').removeClass("disable-button");
                    },
                    error: function(data) {
                        console.log(data);
                        $('#appealCaseGeneralInfoEditSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                    }
                });
            } else {
                $('#appealCaseGeneralInfoEditSaveBtn').removeClass(
                    'spinner spinner-white spinner-right disabled');
                Swal.fire(
                    'Canceled!',
                    'মামলার সাধারণ তথ্য সংরক্ষণ বাতিল করা হয়েছে',
                    'info'
                );
            }
        })

    });
    // ================================Case General Info save==================================
    // ================================Final Order Save==================================//



    $('#finalOrderForm').submit(function(e) {
        e.preventDefault();
        $('#finalOrderSaveBtn').addClass('spinner spinner-white spinner-right disabled');

        Swal.fire({
            title: 'আপনি কি মামলার জবাব প্রেরনের তথ্য সংরক্ষণ করতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('cabinet.case.appealFinalOrderStore') }}",
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
                        $("#caseIDForFinalOrder").val(data.caseId);
                        $("#caseIDForContempt").val(data.caseId);

                        $('#finalOrderSaveBtn').prop('disabled', false);
                        $('#finalOrderSaveBtn').removeClass("disable-button");
                    },
                    error: function(data) {
                        $('#finalOrderSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');

                    }
                });
            } else {
                $('#finalOrderSaveBtn').removeClass(
                    'spinner spinner-white spinner-right disabled');
                Swal.fire(
                    'Canceled!',
                    'মামলার চূড়ান্ত আদেশ তথ্য সংরক্ষণ বাতিল করা হয়েছে',
                    'info'
                );
            }
        })

    });
    // ================================Final Order Save==================================//
    // ================================Sending Replay Save==================================//



    // ================================Sending Replay Save==================================//
</script>
<script>
    // ==================== মামলার রায়/চুড়ান্ত আদেশ হয়ে থাকলে সিলেক্ট করুন  =========== //
    function showAlert() {
        Swal.fire({
            title: "আপনি কি নিশ্চিত?",
            text: "আপনি কি মামলার রায়ের অবস্থা পরিবর্তন করতে চান?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "হ্যাঁ",
            cancelButtonText: "না",
        }).then((result) => {
            if (result.isConfirmed) {

            } else {

                document.getElementById('is_final_order').checked = false;
                console.log("aoyon");
                $('#finalOrderDiv').hide();
            }
        });
    }
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
            // console.log(data);
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
        items += '<td><input type="text" name="file_type[]" id="customFileName' + count +
            '" class="form-control form-control-sm" placeholder="" required><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><div class="custom-file"><input type="file" accept="application/pdf" name="file_name[]" onChange="attachmentTitle(' +
            count + ',this)" class="custom-file-input" id="customFile' + count + '" required/><label id="file_error' +
            count +
            '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-input' +
            count + '" for="customFile' + count +
            '">ফাইল নির্বাচন করুন</label><span class="text-danger d-none vallidation-message">This field can not be empty</span></div></td>';
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
        items += '<td><input type="text" name="file_type[]" id="customFileName' + count +
            '" class="form-control form-control-sm" placeholder="" required><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><div class="custom-file"><input type="file" accept="application/pdf" name="file_name[]" onChange="attachmentTitle(' +
            count + ',this)" class="custom-file-input" id="customFile' + count + '" required/><label id="file_error' +
            count +
            '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-input' +
            count + '" for="customFile' + count +
            '">ফাইল নির্বাচন করুন</label><span class="text-danger d-none vallidation-message">This field can not be empty</span></div></td>';
        items +=
            '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
        items += '</tr>';
        $('#replyFileDiv tr:last').after(items);

        if (formType == 'edit') {
            $(`#customFile${count}`).attr('required', false);
            $(`#customFileName${count}`).attr('required', false);
        }
    }



    /// appeal final


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
        items += '<td><input type="text" name="final_order_file_type[]" id="customFileName' + count +
            '" class="form-control form-control-sm" placeholder="" required><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
        items +=
            '<td><div class="custom-file"><input type="file" accept="application/pdf" name="final_order_file_name[]" onChange="finalAttachmentTitle(' +
            count + ',this)" class="custom-file-input" id="customFinalFile' + count + '" /><label id="file_error' +
            count +
            '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-final-input' +
            count + '" for="customFile' + count + '">ফাইল নির্বাচন করুন</label></div></td>';
        items +=
            '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
        items += '</tr>';
        $('#finalOrderFileDiv tr:last').after(items);

        if (formType == 'edit') {
            $(`#customFile${count}`).attr('required', false);
            $(`#customFileName${count}`).attr('required', false);
        }
    }








    //Attachment Title Change
    function attachmentTitle(id) {
        // var value = $('#customFile' + id).val();
        var value = $('#customFile' + id)[0].files[0];
        $('.custom-input' + id).text(value['name']);
    }

    function finalAttachmentTitle(id) {
        var value = $('#customFinalFile' + id)[0].files[0];
        $('.custom-final-input' + id).text(value['name']);
    }
    //remove Attachment
    function removeBibadiRow(id) {
        $(id).closest("tr").remove();
    }
</script>


{{-- Complete Appeal Case Entry --}}
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