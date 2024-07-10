@extends('layouts.cabinet.cab_default')

@section('content')

    @php
        $pass_year_data = '<option value="">-- নির্বাচন করুন --</option>';
        for ($i = 1995; $i <= date('Y'); $i++) {
            $pass_year_data .= '<option value="' . $i . '">' . $i . '</option>';
        }

    @endphp


    @include('gov_case.case_register.create_css')
    {{-- @dd($case) --}}
    <!--begin::Row-->
    <div class="row">

        {{-- <div class="col-md-12"> --}}
        <!--begin::Card-->
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
            </div>
            <!-- <div class="loadersmall"></div> -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            <div class="card-body">
                <div class="table-responsive ajax-data-container pt-3">
                    <div class="tab-content" id="myTabContent">


                        {{-- ------------- start স্থগিতাদেশ/অন্তর্বর্তীকালীন আদেশ সম্পর্কিত------------- --}}
                        <div class="tab-pane" id="suspension_order" role="tabpanel" aria-labelledby="home-tab">
                            <form id="OrderTakenForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        <input type="hidden" id="caseIDForSuspention" name="case_id"
                                            value="{{ $case->id }}">

                                        <fieldset>

                                            <div class="form-group row">
                                                <div class="col-lg-12 mb-5">
                                                    <div class="col-md-6">
                                                        <label class="form-group font-weight-bolder font-size-h5">রায়
                                                            তামিল/বাস্তবায়নের সিদ্ধান্ত গ্রহণ করা হয়েছে
                                                        </label>
                                                        <div class="radio-inline">
                                                            <label class="radio">
                                                                <input type="radio" name="order_tamil_decision_taken"
                                                                    id="adesh_tamil_decision_taken" value="1" />
                                                                <span></span>হ্যাঁ</label>
                                                            <label class="radio">
                                                                <input type="radio" name="order_tamil_decision_taken"
                                                                    id="adesh_tamil_decision_taken_not" value="0"
                                                                    checked />
                                                                <span></span>না</label>
                                                        </div>
                                                    </div>

                                                    <div class="adesh_tamil_decision_div">
                                                        <div class="form-group row">
                                                            <div class="p-5" id="adesh_tamil_decision_data_details">
                                                                <div class="col-md-12 mb-5">
                                                                    <label>বাস্তবায়নে গৃহীত ব্যবস্থার সংক্ষিপ্ত
                                                                        বিবরণ</label>
                                                                    <textarea name="order_tamil_decision_data_details" class="form-control" id="adesh_tamil_decision_data_details"
                                                                        rows="5" spellcheck="false"></textarea>
                                                                </div>
                                                            </div>

                                                            {{-- starting সংযুক্তি  --}}
                                                            <div class="col-md-12 mt-8">
                                                                <fieldset class="">
                                                                    <div
                                                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                        <div class="d-flex align-items-center mr-2 py-2">
                                                                            <h3 class="mb-0 mr-8">সংযুক্তি (বাস্তবায়নে গৃহীত
                                                                                ব্যবস্থার কপি সংযুক্ত
                                                                                করুন)
                                                                                <sub class="text-danger">(PDF, সর্বোচ্চ
                                                                                    সাইজ:
                                                                                    5MB)</sub>
                                                                            </h3>
                                                                        </div>

                                                                        <div class="symbol-group symbol-hover py-2">
                                                                            <div class="symbol symbol-30 symbol-light-primary"
                                                                                data-toggle="tooltip" data-placement="top"
                                                                                title="" role="button"
                                                                                data-original-title="ফাইল যুক্ত করুণ">

                                                                                <div id="orderTamilDecisionFileRow">
                                                                                    <span
                                                                                        class="symbol-label font-weight-bold bg-success">
                                                                                        <i
                                                                                            class="text-white fa flaticon2-plus font-size-sm"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-3 px-5">
                                                                        <table width="100%" class="border-0 px-5"
                                                                            id="orderTamilDecisionFileDiv"
                                                                            style="border:1px solid #dcd8d8;">
                                                                            <tr></tr>
                                                                        </table>
                                                                        <input type="hidden"
                                                                            id="order_tamil_attachment_count"
                                                                            value="1">
                                                                    </div>
                                                                </fieldset>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-group font-weight-bolder font-size-h5">রায়ের
                                                            বিরুদ্ধে আপিলের সিদ্ধান্ত গ্রহণ করা হয়েছে
                                                        </label>
                                                        <div class="radio-inline">
                                                            <label class="radio">
                                                                <input type="radio"
                                                                    name="appeal_against_adesh_decision_taken"
                                                                    id="appeal_against_adesh_decision_taken"
                                                                    value="1" />
                                                                <span></span>হ্যাঁ</label>
                                                            <label class="radio">
                                                                <input type="radio"
                                                                    name="appeal_against_adesh_decision_taken"
                                                                    id="appeal_against_adesh_decision_taken_not"
                                                                    value="0" checked />
                                                                <span></span>না</label>
                                                        </div>
                                                    </div>

                                                    <div class="adesh_tamil_decision_yes_taken_div">
                                                        <div class="col-md-6">
                                                            <label class="form-group font-weight-bolder font-size-h5">আপিল
                                                                দায়েরের জন্য অনুরোধ করা হয়েছে কিনা
                                                            </label>
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio"
                                                                        name="adesh_tamil_decision_yes_taken"
                                                                        id="adesh_tamil_decision_yes_taken"
                                                                        value="1" />
                                                                    <span></span>হ্যাঁ</label>
                                                                <label class="radio">
                                                                    <input type="radio"
                                                                        name="adesh_tamil_decision_yes_taken"
                                                                        id="adesh_tamil_decision_yes_taken_not"
                                                                        value="0" checked />
                                                                    <span></span>না</label>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="p-5" id="sending_reply_data_details">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1" id="solicitor_checkbox"
                                                                name="sending_request_for_appeal_against_intreim_person_solicitor">
                                                            <label class="form-check-label lawyer_title"
                                                                for="solicitor_checkbox">
                                                                সলিসিটর বরাবর
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1" id="law_officer_checkbox"
                                                                name="sending_request_for_appeal_against_intreim_person_law_officer">
                                                            <label class="form-check-label lawyer_title"
                                                                for="law_officer_checkbox">
                                                                বিজ্ঞ আইনজীবী বরাবর
                                                            </label>
                                                        </div>
                                                    </div>


                                                    <div class="sending_reply_div">
                                                        <div class="form-group row">
                                                            <div class="col-lg-6 mb-5 mt-8">
                                                                <label>আপিল দায়েরের অনুরোধের তারিখ
                                                                </label>
                                                                <input type="text"
                                                                    name="appeal_submission_requesting_date"
                                                                    id="appeal_submission_requesting_date"
                                                                    class="form-control form-control-sm  common_datepicker"
                                                                    placeholder="দিন/মাস/বছর" autocomplete="off">
                                                            </div>

                                                            <div class="col-lg-6 mb-5 mt-8">
                                                                <label>আপিল দায়েরের অনুরোধের স্মারক
                                                                </label>
                                                                <input type="text"
                                                                    name="appeal_submission_requesting_memorial"
                                                                    id="appeal_submission_requesting_memorial"
                                                                    class="form-control form-control-sm" placeholder=""
                                                                    autocomplete="off">
                                                            </div>

                                                            {{-- starting সংযুক্তি  --}}
                                                            <div class="col-md-12 mt-8">
                                                                <fieldset class="">
                                                                    <div
                                                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                        <div class="d-flex align-items-center mr-2 py-2">
                                                                            <h3 class="mb-0 mr-8">সংযুক্তি (আপিল দায়েরের
                                                                                অনুরোধ কপি
                                                                                সংযুক্ত
                                                                                করুন)
                                                                                <sub class="text-danger">(PDF, সর্বোচ্চ
                                                                                    সাইজ:
                                                                                    5MB)</sub>
                                                                            </h3>
                                                                        </div>

                                                                        <div class="symbol-group symbol-hover py-2">
                                                                            <div class="symbol symbol-30 symbol-light-primary"
                                                                                data-toggle="tooltip" data-placement="top"
                                                                                title="" role="button"
                                                                                data-original-title="ফাইল যুক্ত করুণ">

                                                                                <div
                                                                                    id="addAppealSubmissionRequestFileRow">
                                                                                    <span
                                                                                        class="symbol-label font-weight-bold bg-success">
                                                                                        <i
                                                                                            class="text-white fa flaticon2-plus font-size-sm"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-3 px-5">
                                                                        <table width="100%" class="border-0 px-5"
                                                                            id="addAppealSubmissionRequestFileDiv"
                                                                            style="border:1px solid #dcd8d8;">
                                                                            <tr></tr>
                                                                        </table>
                                                                        <input type="hidden"
                                                                            id="appeal_submission_attachment_count"
                                                                            value="1">
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                            {{-- end সংযুক্তি --}}
                                                            <div class="col-md-8 mb-5 mt-6" id="trackingNumberField"
                                                                style="display: none;">
                                                                <label>সলিসিটর বরাবর প্রেরীত জবাব সলট্র্যাক-এ এন্ট্রি করা
                                                                    হলে
                                                                    ট্র্যাকিং নম্বর প্রদান করুন</label>
                                                                <input type="text"
                                                                    name="soltrack_tracking_number_for_appeal_against_intreim_order"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                            </div>

                                            {{-- starting সংযুক্তি  --}}
                                            <div class="col-md-12 mt-8">
                                                <fieldset class="">
                                                    <div
                                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                        <div class="d-flex align-items-center mr-2 py-2">
                                                            <h3 class="mb-0 mr-8">সংযুক্তি (রায় বাস্তবায়ন/ আপিল দায়ের
                                                                সংক্রান্ত কপি সংযুক্ত করুন)
                                                                <span class="text-danger">*</span>
                                                            </h3>
                                                        </div>

                                                        <div class="symbol-group symbol-hover py-2">
                                                            <div class="symbol symbol-30 symbol-light-primary"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                role="button" data-original-title="ফাইল যুক্ত করুণ">

                                                                <div id="addOrderTakenDecisionFileRow">
                                                                    <span class="symbol-label font-weight-bold bg-success">
                                                                        <i
                                                                            class="text-white fa flaticon2-plus font-size-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <div class="mt-3 px-5">
                                                        <table width="100%" class="border-0 px-5"
                                                            id="orderTakenDecisionFileDiv"
                                                            style="border:1px solid #dcd8d8;">
                                                            <tr></tr>
                                                        </table>
                                                        <input type="hidden" id="order_taken_decision_attachment_count"
                                                            value="1">
                                                    </div>
                                                </fieldset>
                                            </div>

                                            {{-- end সংযুক্তি --}}
                                        </fieldset>

                                    </div>
                                </div>
                                <div class="form-footer" style="display: flex;justify-content: center;">
                                    <button type="submit" id="orderTakenSaveBtn"
                                        class="action-button submit-button">সংরক্ষণ</button>
                                </div>
                            </form>
                        </div>
                        {{-- ------------- end স্থগিতাদেশ/অন্তর্বর্তীকালীন আদেশ সম্পর্কিত------------- --}}



                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
        {{-- </div> --}}

    </div>
    <!--end::Row-->

@endsection

@section('styles')
@endsection

@section('scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            $('.tab-content .tab-pane:first-child').addClass('active');
            $('.myTab a').click(function(e) {
                e.preventDefault();
                var targetTab = $(this).attr('href');
                $('.tab-content .tab-pane').removeClass('active');
                $(targetTab).addClass('active');
            });
        });
    </script>

    @include('gov_case.case_register._inc.action_js')
    <script type="text/javascript">
        $(document).ready(function() {
            addBadiRowFunc();
            addBibadiRowFunc();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.adesh_tamil_decision_div').hide();
            $('input[name="order_tamil_decision_taken"][value="0"]').prop('checked', true);
            $('input[name="order_tamil_decision_taken"]').change(function() {
                if ($(this).val() == '1') {
                    $('.adesh_tamil_decision_div').show();
                } else {
                    $('.adesh_tamil_decision_div').hide();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.adesh_tamil_decision_yes_taken_div').hide();
            $('input[name="appeal_against_adesh_decision_taken"][value="0"]').prop('checked', true);
            $('input[name="appeal_against_adesh_decision_taken"]').change(function() {
                if ($(this).val() == '1') {
                    $('.adesh_tamil_decision_yes_taken_div').show();
                } else {
                    $('.adesh_tamil_decision_yes_taken_div').hide();
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#sending_reply_data_details').hide();
            $('#trackingNumberField').hide();
            $('input[name="adesh_tamil_decision_yes_taken"]').change(function() {
                if ($(this).val() == '1') {

                    $('#sending_reply_data_details').show();
                } else {

                    $('#sending_reply_data_details').hide();
                    $('#trackingNumberField').hide();
                    $('.sending_reply_div').hide();
                    $('#sending_reply_data_details input').val('');
                    $('.sending_reply_div input').val('');
                    $('#sending_reply_data_details input[type="checkbox"]').prop('checked', false);
                }
            });

            $('#solicitor_checkbox').change(function() {
                if ($(this).is(':checked')) {

                    $('#trackingNumberField').show();
                } else {
                    $('#trackingNumberField').hide();
                }
            });

            var solicitorCheckbox = document.getElementById("solicitor_checkbox");
            var lawOfficerCheckbox = document.getElementById("law_officer_checkbox");
            var sendingReplyDiv = document.querySelector(".sending_reply_div");

            function toggleSendingReplyDiv() {
                if (solicitorCheckbox.checked || lawOfficerCheckbox.checked) {
                    sendingReplyDiv.style.display = "block";
                } else {
                    sendingReplyDiv.style.display = "none";
                }
            }

            toggleSendingReplyDiv();
            solicitorCheckbox.addEventListener("change", toggleSendingReplyDiv);
            lawOfficerCheckbox.addEventListener("change", toggleSendingReplyDiv);
        });
    </script>

    <script type="text/javascript">
        // dynamically change high court / appeal court
        $(document).ready(function() {
            $('#appeal_hide_show_3').hide();
            $('#civilRevisionDiv').hide();
            $('#civilSuitDiv').hide();
            $('#writDiv').hide();
            $('#leaveToAppealDiv').hide();
            $('#finalOrderDiv').hide();

            $('input[id="is_final_order"]').click(function() {
                if ($(this).prop("checked") == true) {
                    $('#finalOrderDiv').show();
                    console.log("Checkbox is checked.");
                } else if ($(this).prop("checked") == false) {
                    $('#finalOrderDiv').hide();
                    console.log("Checkbox is unchecked.");
                }
            });






            $("#court").change(function() {
                var getCourt = $('#court').find(":selected").val();
                if (getCourt == 1) {
                    $('#appeal_hide_show').show();
                    $('#appeal_hide_show_3').show();
                    $('#appeal_hide_show_2').hide();
                    $('#highCourt_hide_show').hide();
                } else {
                    $('#highCourt_hide_show').show();
                    $('#appeal_hide_show_3').hide();
                    $('#appeal_hide_show_2').show();
                    $('#appeal_hide_show').hide();
                }
            });

            $("#CaseCategory").change(function() {
                var getCatType = $('#CaseCategory').find(":selected").val();
                // alert(getCatType);
                if (getCatType == 4) {
                    $('#civilRevisionDiv').show();
                    $('#civilSuitDiv').hide();
                    $('#writDiv').hide();
                    $('#leaveToAppealDiv').hide();
                } else if (getCatType == 8) {
                    $('#civilSuitDiv').show();
                    $('#civilRevisionDiv').hide();
                    $('#writDiv').hide();
                    $('#leaveToAppealDiv').hide();
                } else if (getCatType == 2) {
                    $('#writDiv').show();
                    $('#civilRevisionDiv').hide();
                    $('#civilSuitDiv').hide();
                    $('#leaveToAppealDiv').hide();
                } else if (getCatType == 10) {
                    $('#leaveToAppealDiv').show();
                    $('#civilRevisionDiv').hide();
                    $('#civilSuitDiv').hide();
                    $('#writDiv').hide();
                } else {
                    $('#civilRevisionDiv').hide();
                    $('#civilSuitDiv').hide();
                    $('#writDiv').hide();
                    $('#leaveToAppealDiv').hide();
                }
            });



            $("#postponed_order_details").hide();
            $("#interim_order_details_div").hide();
            $("#postponed_order_have").click(function() {
                $("#postponed_order_details").show();
            });

            $("#postponed_order_not").click(function() {
                $("#postponed_order_details").hide();
            });

            $("#interim_order_have").click(function() {
                $("#interim_order_details_div").show();
            });

            $("#interim_order_not").click(function() {
                $("#interim_order_details_div").hide();
            });

            $("#appeal_case_id").change(function() {
                var case_id = $('#appeal_case_id').find(":selected").val();
                $.ajax({
                    url: '{{ route('cabinet.case.get_details') }}',
                    method: 'get',
                    data: {
                        case_id: case_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.id == case_id) {
                            $('#case_year').val(response.year);
                            $('#case_date').val(response.date_issuing_rule_nishi);

                            $("select[name='case_category']").find('option[value="' + response
                                .case_category_id + '"]').attr('selected', 'selected');

                            $("select[name='concern_person']").find('option[value="' + response
                                .concern_user_id + '"]').attr('selected', 'selected');

                            $('#subject_matter').val(response.subject_matter);
                            $('#postponed_details').val(response.postponed_details);
                            $('#interim_order').val(response.interim_order);
                            $('#important_cause').val(response.important_cause);
                        }
                    }
                });
            });
        });
    </script>
@endsection
