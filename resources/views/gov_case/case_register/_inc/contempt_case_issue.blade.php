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
        <div class="card card-custom gutter-b example example-compact" style="width: 100%;">
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
                        {{-- ------------- start কনটেম্প্ট মামলা সম্পর্কিত------------- --}}
                        <div class="tab-pane" id="contempt_case" role="tabpanel" aria-labelledby="home-tab">
                            <form id="contemptCaseForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->

                                        <input type="hidden" id="caseIDForContempt" name="case_id" value="{{ $case->id }}">
                                        <fieldset>
                                            <div class="form-group row">
                                                <div class="col-lg-4 mb-5">
                                                    <label>প্রযোজ্য ক্ষেত্রে কন্টেম্পট মামলা নম্বর <span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="contempt_case_no" id="contempt_case_no"
                                                        class="form-control form-control-sm"autocomplete="off">
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label> কন্টেম্পট মামলা রুল ইস্যুর তারিখ <span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="contempt_case_isuue_date"
                                                        id="contempt_case_isuue_date"
                                                        class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>কন্টেম্পট মামলার জবাব প্রেরণের তারিখ <span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="contempt_case_answer_sending_date"
                                                        id="contempt_case_answer_sending_date"
                                                        class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                                </div>

                                                <div class="col-lg-6 mb-5">
                                                    <label>অন্যান্য পদক্ষেপের বিবরণ<br>(যদি থাকে) <span
                                                            class="text-danger"></span></label>
                                                    <textarea name="others_action_detials" class="form-control" id="others_action_detials" rows="3"
                                                        spellcheck="false">
                                                </textarea>
                                                </div>
                                            </div>
                                            {{-- starting সংযুক্তি  --}}
                                            <div class="col-md-12">
                                                <fieldset class="">
                                                    <div
                                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                        <div class="d-flex align-items-center mr-2 py-2">
                                                            <h3 class="mb-0 mr-8">সংযুক্তি
                                                                (কনটেম্প্ট মামলা সম্পর্কিত কপি সংযুক্ত করুন)
                                                                <span class="text-danger">*</span>
                                                                <sub class="text-danger">(PDF, সাইজ সর্বোচ্চ: 5MB)</sub>
                                                            </h3>
                                                        </div>

                                                        <div class="symbol-group symbol-hover py-2">
                                                            <div class="symbol symbol-30 symbol-light-primary"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                role="button" data-original-title="ফাইল যুক্ত করুণ">

                                                                <div id="addContemptFileRow">
                                                                    <span class="symbol-label font-weight-bold bg-success">
                                                                        <i
                                                                            class="text-white fa flaticon2-plus font-size-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <div class="mt-3 px-5">
                                                        <table width="100%" class="border-0 px-5" id="contemptFileDiv"
                                                            style="border:1px solid #dcd8d8;">
                                                            <tr></tr>
                                                        </table>
                                                        <input type="hidden" id="contempt_attachment_count" value="1">
                                                    </div>
                                                </fieldset>
                                            </div>
                                            {{-- end সংযুক্তি --}}
                                        </fieldset>

                                    </div>
                                </div>
                                <div class="form-footer" style="display: flex;justify-content: center;">
                                    <button type="submit" id="contemptCaseSaveBtn"
                                        class="action-button submit-button">সংরক্ষণ</button>
                                </div>
                            </form>
                        </div>
                        {{-- ------------- end কনটেম্প্ট মামলা------------- --}}



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
                            /*Swal.close();
                            $('.perssion_list').html(response.html);*/
                        }
                    }
                });
            });
        });
    </script>
@endsection
