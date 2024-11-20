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
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        addBadiRowFunc();
        $('select').select2();
        var formType = $('#formType').val();
        if (formType != 'edit') {
            addMainBibadiRowFunc();
            addHighcourtAdalatRowFunc();
            addBibadiRowFunc();
            addAdvocateLawerFunc();
        }
        addFileRowFunc();
        addReplyFileRowFunc();
        addAdalatReplyFileRowFunc();
        addSuspensionOrderFileRowFunc();
        addSuspensionOrderFileRowFuncTwo();
        addContemptFileRowFunc();
        addFinalOrderFileRowFunc();
        addAdvocateLawerFunc();

        adeshTamilDecisionFileRowFunc();
        addAppealSubmissionFileRowFunc();
        appealSubmissionFileRowFunc();

        // $('select').select2();
    });

    /*********************** Add multiple badi *************************/
    $("#addBadiRow").click(function(e) {
        addBadiRowFunc();
    });

    // add row function
    function addBadiRowFunc() {
        var items = '';
        items += '<tr>';
        items +=
            '<td><input type="text" name="badi_name[]" class="form-control form-control-sm" placeholder="" required><span class="text-danger d-none validation-message">অনুগ্রহ করে পিটিশনারের নাম লিখুন</span></td>';
        items += '<input type="hidden" name="badi_id[]" value="">';
        items +=
            '<td><input type="text" name="badi_address[]" class="form-control form-control-sm" placeholder=""></td>';
        items += '</tr>';
        $('#badiDiv tr:last').after(items);
    }

    //===========caseType================//
    jQuery('select[name="appeal_office"]').on('change', function() {
        var dataID = jQuery(this).val();
        console.log(dataID);
        if (dataID == 0) {
            $('#appeal_petitioner_name').removeClass('d-none');
        } else {
            $('#appeal_petitioner_name').addClass('d-none');
        }
    });





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


    //add row function
    function addMainBibadiRowFunc() {
        var countVal = parseInt($('#mainBibadi_count').val());
        $('#mainBibadi_count').val(countVal + 1);
        var mk_main = $('#MainBibadiDiv tr').length;
        var MainCount = $('#MainBibadiDiv tr').length;
        $('#MainBibadiDiv tr:last').after(ItemMain(mk_main + 1, 'other'));


        function ItemMain(count, type = NULL) {
            var items = '';
            items += '<tr id="bibadi_' + (count) + '">';
            items +=
                '<td><select name="main_respondent[]" class="form-control form-control-sm main_respondent" required><option value="">-- নির্বাচন করুন --</option>@foreach ($mainRespondentMinistrys as $value)<option value="{{ $value->doptor_office_id }}" {{ old('main_ministry') == $value->doptor_office_id ? 'selected' : '' }}>{{ $value->office_name_bn }}</option>@endforeach</select><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';

            if (countVal != 1) {
                items +=
                    '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeMainBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            }
            items += '</tr>';
            return items;

        }

        // $('.main_respondent').select2();
    }

    //remove row function
    function removeMainBibadiRow(id) {
        $(id).closest("tr").remove();
    }
    /************************ //Add multiple Main bibadi *************************/



    function addBibadiRowFunc() {
        var mk = $('#bibadiDiv tr').length;
        $('#bibadiDiv tr:last').after(Item(mk + 1));

        function Item(count) {
            // alert(count);
            var items = '';
            items += '<tr id="bibadi_' + count + '">';
            items += '<td> <span class="form-control form-control-sm">' + count + '</span></td>'
            items += '<td><select name="other_respondent[]" onChange="getManualOtherRespondentName(' + count +
                ')" id="other_respondent_' + count + '" class="form-control form-control-sm other_respondentCls">';
            items += '<option value="">-- নির্বাচন করুন --</option>';
            items +=
                '@foreach ($ministrys as $value)<option value="{{ $value->doptor_office_id }}" {{ old('ministry') == $value->doptor_office_id }}> {{ $value->office_name_bn }} </option>@endforeach';
            items += '<option value="0">অন্যান্য</option>';
            items +=
                '</select> <br> <input type="text" name="other_respondent_manual_name[]" id="other_respondent_manual_name_' +
                count +
                '" class="form-control form-control-sm" placeholder="অন্যান্য রেসপন্ডেন্টর নাম লিখুন" style="display: none"></td>';
            items += '<input type="hidden" name="bibadi_id[]" value="">';

            items +=
                '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            items += '</tr>';
            return items;
        }


        $('.other_respondentCls').select2();
    }


    function removeBibadiRow(id) {
        $(id).closest("tr").remove();
    }

    function getManualOtherRespondentName(data) {
        var other_respondent_manual_name_ = $('#other_respondent_manual_name_' + data);
        var selectID = $('#other_respondent_' + data).val();
        if (selectID == 0) {
            $('#other_respondent_manual_name_' + data).show();
            $('#other_respondent_manual_name_' + data).addClass("w-100");
        }
        if (selectID != 0) {
            $('#other_respondent_manual_name_' + data).hide();
        }
        console.log(selectID);

        // alert(details);
    }


    /// ************ Other Respondent *************




    /************************ //Add multiple HighCourt Adalat *************************/
    // $("#addHighcourtAdalatRow").click(function(e) {
    //     addHighcourtAdalatRowFunc();
    // });

    // //add row function
    // function addHighcourtAdalatRowFunc() {
    //     var mk = $('#highcourtAdalatDiv tr').length;
    //     var MainCount = $('#MainBibadiDiv tr').length;

    //     $('#highcourtAdalatDiv tr:last').after(Item(mk + 1, 'other'));

    //     function Item(count, type = NULL) {
    //         var items = '';
    //         items += '<tr id="administrative_adalat_' + (count) + '">';
    //         items +=
    //             '<td><select name="administrative_adalat[]"  class="form-control form-control-sm other_respondentCls" required="required"><option value="">-- নির্বাচন করুন --</option>@foreach ($administrativeTribrunalAdalat as $value)<option value="{{ $value->id }}" {{ old('ministry') == $value->id ? 'selected' : '' }}> {{ $value->name }} </option>@endforeach</select></td>';
    //         items += '<input type="hidden" name="administrative_adalat_id[]" value="">';

    //         if (type == 'other') {
    //             items +=
    //                 '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeHighcourtAdalatRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
    //         }
    //         items += '</tr>';
    //         return items;
    //     }
    //     $('.other_respondentCls').select2();
    // }

    // //remove row function
    // function removeHighcourtAdalatRow(id) {
    //     $(id).closest("tr").remove();
    // }






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
</script>

<script>
    $("#addAdvocateLawer").click(function(e) {
        addAdvocateLawerFunc();
    });

    // Add row function
    function addAdvocateLawerFunc() {
        var count = parseInt($('#survey_count').val());
        $('#survey_count').val(count + 1);
        var items = '';
        items += '<tr>';
        items += '<input type="hidden" name="concern_person_id[]" value="">';
        items += '<td><select name="concernPersonDesignation[]" id="concernPersonDesignation_' + count +
            '" class="form-control form-control-sm select2" onchange="getConcernPerName(' + count +
            ')" required="required"><?php echo $concernPersonDesig; ?></select> </td>';
        items += '<td><select name="concern_user_id[]" id="concern_user_id_' + count +
            '" class="form-control form-control-sm select2" required="required"><option value="">-- নির্বাচন করুন --</option></select></td>';

        if (count != 1) {
            items +=
                '<td><a href="javascript:void(0);" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeAdvocateLawerRow(this)"> <i class="fas fa-trash"></i> </a> </td>';
        }

        items += '</tr>';

        $('#advocateLawerDiv tr:last').after(items);

        // Initialize Select2 after adding new dropdowns
        $('#concernPersonDesignation_' + count).select2();
        $('#concern_user_id_' + count).select2();
    }


    //remove row function
    function removeAdvocateLawerRow(id) {
        $(id).closest("tr").remove();
    }

    function getConcernPerName(id) {
        var desig = $(`#concernPersonDesignation_${id}`).val();
        jQuery(`#concern_user_id_${id}`).after('<div class="loadersmall"></div>');
        if (desig) {
            jQuery.ajax({
                url: '{{ url('/') }}/cabinet/case/dropdownlist/getdependentconcernperson/' +
                    desig,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    jQuery(`#concern_user_id_${id}`).html(
                        '<div class="loadersmall"></div>');

                    jQuery(`#concern_user_id_${id}`).html(
                        '<option value="">-- নির্বাচন করুন --</option>');
                    jQuery.each(data, function(key, value) {
                        jQuery(`#concern_user_id_${id}`).append(
                            '<option value="' + key + '">' + value +
                            '</option>');
                    });
                    jQuery('.loadersmall').remove();
                }
            });
        } else {
            $(`#concern_user_id_${id}`).empty();
        }

    }
</script>


<script>
    // ================================Case General Info save==================================

    $('#administrativeTribrunalGeneralInfoForm').submit(function(e) {
        e.preventDefault();
        // Validation check
        var isValid = true;
        $('input[name="badi_name[]"]').each(function() {
            var value = $(this).val().trim();
            if (value === "") {

                $(this).next('.validation-message').removeClass('d-none');
                $(this).focus();
                isValid = false;
                return false;
            } else {
                $(this).next('.validation-message').addClass('d-none');
            }
        });

        if (!isValid) {
            return;
        }

        $('#appealAdministrativeTribrunalGeneralInfoSaveBtn').addClass(
            'spinner spinner-white spinner-right disabled');
        Swal.fire({
            title: 'আপনি কি আপিল প্রশাসনিক মামলার সাধারন তথ্য সংরক্ষণ করতে চান?',
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
                    url: "{{ route('cabinet.case.appealAdministrativeTribrunalGeneralInfo') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $('#appealAdministrativeTribrunalGeneralInfoSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        $orderData = data;
                        Swal.fire(
                            'Saved!',
                            'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                            'success'
                        ).then(() => {
                            window.location.href =
                                "{{ route('cabinet.case.appealAdministrativeTribrunal') }}";
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#appealAdministrativeTribrunalGeneralInfoSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        if (xhr.status ===
                            422) { // HTTP status code for Unprocessable Entity
                            Swal.fire('সমস্যা...!', xhr.responseJSON.error, 'error');
                        } else {
                            console.log('Error occurred:', xhr, status, error);
                            Swal.fire('সমস্যা...!', 'অনুগ্রহ করে সকল ফিল্ড গুলো পূরণ করুন',
                                'error');
                        }
                    }
                });
            } else {
                $('#appealAdministrativeTribrunalGeneralInfoSaveBtn').removeClass(
                    'spinner spinner-white spinner-right disabled');
                Swal.fire(
                    'Canceled!',
                    'মামলার সাধারণ তথ্য সংরক্ষণ বাতিল করা হয়েছে',
                    'info'
                );
            }
        });
    });



    // ================================Case General Info save==================================
</script>


<script>
    // Case no can only be bangla and english
    function allowBanglaAndEnglishNumerals(event) {
        var charCode = event.which || event.keyCode;
        if ((charCode >= 2400 && charCode <= 2409) || (charCode >= 48 && charCode <= 57)) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    }
</script>



<!--end::Page Scripts-->
@include('components.Ajax')
<script>
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
            '" class="form-control form-control-sm" placeholder="" ></td>';
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




    //Attachment Title Change  && vallidation
    function attachmentTitle(id, selectObject) {
        // var fileType = document.getElementById('file_type' + id).value;
        var fileType = $('#customFile' + id).val();
        if (fileType != '') {

            //===================For CSS Change of Duynamic File Name =============//
            $('#file_type' + id).css("background-color", "FFFFFF");
            $('#file_type_error' + id).hide();
            $('#file_type' + id).css("border-color", "#FFFFFF");

            console.log(selectObject.value);
            var value = $('#customFile' + id)[0].files[0];
            $('.custom-input' + id).text(value['name']);

            var filePath = selectObject.value;
            var fileData = selectObject;
            if (typeof(fileData.files) != "undefined") {
                $('#file_error' + id).hide();
                $('.custom-input' + id).css("border-color", "#FFFFFF");

                var size = parseFloat(fileData.files[0].size / 1024).toFixed(2);
                if (size > 5120) {
                    // alert('Invalid file type');
                    document.getElementById('customFile' + id).value = '';
                    $('.custom-input' + id).html('ফাইল নির্বাচন করুন');
                    $('.custom-input' + id).css("border-color", "#FF0000");
                    $('#file_error' + id).show();
                    $('#file_error' + id).html('ফাইলের আকার 5MB এর বেশি');
                    return false;
                }
            } else {
                alert("This browser does not support HTML5.");
            }

            // Allowing file type
            var allowedExtensions = /(\.pdf)$/i;

            if (!allowedExtensions.exec(filePath)) {
                // alert('Invalid file type');
                document.getElementById('customFile' + id).value = '';
                $('.custom-input' + id).html('ফাইল নির্বাচন করুন');
                $('.custom-input' + id).css("border-color", "#FF0000");
                $('#file_error' + id).show();
                $('#file_error' + id).html("পিডিএফ ফাইল নির্বাচন করুন");
                return false;
            }
        } else {
            $('#file_type' + id).css("border-color", "#FF0000");
            $('#file_type_error' + id).show();
            $('#file_type_error' + id).html('ফাইলের নাম লিখুন');
        }

    }
    //Attachment Title Change
    function replyAttachmentTitle(id) {
        var value = $('#customReplyFile' + id)[0].files[0];
        $('.custom-reply-input' + id).text(value['name']);
    }

    //Attachment Title Change
    function adalatReplyAttachmentTitle(id) {
        // var value = $('#customFile' + id).val();
        var value = $('#customAdalatReplyFile' + id)[0].files[0];
        $('.custom-adalat-reply-input' + id).text(value['name']);
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



<script>
    // when writing about money about case
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function removeCommas(x) {
        return x.replace(/,/g, '');
    }

    document.getElementById('money_amount').addEventListener('input', function() {
        var value = this.value;
        value = removeCommas(value);
        this.value = numberWithCommas(value);
    });
</script>
