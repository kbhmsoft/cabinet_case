@extends('layouts.cabinet.cab_default')

@section('content')

    @php
        $pass_year_data = '<option value="">-- নির্বাচন করুন --</option>';
        for ($i = 1995; $i <= date('Y'); $i++) {
            $pass_year_data .= '<option value="' . $i . '">' . $i . '</option>';
        }

    @endphp

    <?php
    $case = [];
    $case['create_by'] = '';

    ?>
    @include('gov_case.case_register.create_css')
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
            {{--
            <div id="tab_header_tabs tab-design" class="trainee_details_card_header course_details_new_tabs">
                <ul class="nav details_trainee_tab nav-tabs myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="trainee_tab_item" data-toggle="tab" href="#case_general_information"
                            role="tab" aria-controls="home" aria-selected="true">মামলার সাধারণ তথ্য</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="sending_reply_tab" data-toggle="tab" href="#sending_reply" role="tab"
                            aria-controls="profile" aria-selected="false">জবাব প্রেরণ</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="suspension_order_tab" href="#suspension_order" data-toggle="tab"
                            role="tab" aria-controls="contact" aria-selected="false">স্থগিতাদেশ/<br>অন্তর্বর্তীকালীন
                            আদেশ সম্পর্কিত</a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="final_order_tab" href="#final_order" data-toggle="tab" role="tab"
                            aria-controls="contact" aria-selected="false">চূড়ান্ত আদেশ/<br>রায় সম্পর্কিত</a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contempt_case_tab" href="#contempt_case" data-toggle="tab" role="tab"
                            aria-controls="contact" aria-selected="false">কনটেম্প্ট মামলা/<br>অন্যান্য</a>
                    </li>
                </ul>
            </div> --}}

            <div class="card-body">
                <div class="table-responsive ajax-data-container pt-3">
                    <div class="tab-content" id="myTabContent">


                        {{-- start মামলার সাধারণ তথ্য --}}

                        <div class="tab-pane active" id="case_general_information" role="tabpanel"
                            aria-labelledby="home-tab">
                            <form id="caseGeneralInfoForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        {{-- <div class="step" id=""> --}}
                                        <fieldset class="mb-8">
                                            <!-- <legend> মামলার সাধারণ তথ্য</legend> -->
                                            <div class="form-group row">


                                                {{-- <div class="col-lg-4 mb-5">
                                                    <label>আদালতের নাম <span class="text-danger">*</span></label>
                                                    <select name="court" id="court"
                                                        class="form-control form-control-sm" required="required">
                                                        <option value=""> -- নির্বাচন করুন --</option>


                                                        @foreach ($courts as $value)
                                                            <option value="{{ $value->id }}"
                                                                {{ old('court') == $value->id ? 'selected' : '' }}>
                                                                {{ $value->court_name }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div> --}}

                                                {{-- <div class="col-lg-4 mb-5" style="display:none;" id="appeal_hide_show">
                                                    <label>মামলা নির্বাচন করুন <span class="text-danger">*</span></label>
                                                    <select name="appeal_case_id" id="appeal_case_id"
                                                        class="form-control form-control-sm">
                                                        <option value=""> -- নির্বাচন করুন --</option>
                                                        @foreach ($appealCase as $value)
                                                            <option value="{{ $value->id }}"
                                                                {{ old('appeal_case_id') == $value->id ? 'selected' : '' }}>
                                                                {{ $value->case_no }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div> --}}

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলার ক্যাটেগরি <span class="text-danger">*</span></label>

                                                    <div class="" id="CaseCategorDiv">
                                                        <select name="case_category" id="CaseCategory"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($GovCaseDivisionCategory as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_category') == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name_bn }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলার শ্রেণী/কেস-টাইপ <span class="text-danger">*</span></label>
                                                    <div class="" id="CaseCategorDiv">
                                                        <select name="case_category_type" id="case_category_type"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলা নং <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_no" id="case_no"
                                                        class="form-control form-control-sm" placeholder="মামলা নং "
                                                        required="required">
                                                    <input type="hidden" name="caseId" value="">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>



                                                <div class="col-lg-4 mb-5">
                                                    <label>বছর <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_year" id="case_year"
                                                        class="form-control form-control-sm common_yearpicker"
                                                        placeholder="বছর" autocomplete="off" required="required">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>আপিলকারি অফিস
                                                        <span class="text-danger">*</span></label>

                                                    <div class="" id="appeallateOffice">
                                                        <select name="appeal_office" id="appeallateOffice"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($GovCaseDivisionCategory as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('appeal_office') == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name_bn }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>সংশ্লিষ্ট আইন কর্মকর্তা <span
                                                            class="text-danger">*</span></label>

                                                    <div class="" id="concernPersonDesignationDiv">
                                                        <select name="concern_person_designation"
                                                            id="concern_person_designation"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($concern_person_desig as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('concern_person_designation') == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>


                                                <div class="col-lg-6 mb-5">
                                                    <label>স্থগিতাদেশের তারিখ(প্রযোজ্য ক্ষেত্রে)<span class="text-danger"></span></label>
                                                    <input type="text" name="postpond_date" id="postpond_date"
                                                        class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                                </div>


                                                <div class="col-md-6 mb-5">
                                                    <label>স্থগিতাদেশের বিবরণ</label>
                                                    <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3" spellcheck="false"></textarea>
                                                </div>


                                                {{-- starting সংযুক্তি  --}}

                                                <div class="col-md-12">
                                                    <fieldset class="">
                                                        <div
                                                            class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                            <div class="d-flex align-items-center mr-2 py-2">
                                                                <h3 class="mb-0 mr-8">স্থগিতাদেশের সংযুক্তি
                                                                    <span class="text-danger">*</span>
                                                                </h3>
                                                            </div>

                                                            <div class="symbol-group symbol-hover py-2">
                                                                <div class="symbol symbol-30 symbol-light-primary"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="" role="button"
                                                                    data-original-title="ফাইল যুক্ত করুণ">

                                                                    <div id="addFileRow">
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
                                                            <table width="100%" class="border-0 px-5" id="fileDiv"
                                                                style="border:1px solid #dcd8d8;">
                                                                <tr></tr>
                                                            </table>
                                                            <input type="hidden" id="other_attachment_count"
                                                                value="1">
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                {{-- end সংযুক্তি --}}


                                                <div class="col-lg-4 mb-5">
                                                    <label>ধরনর মামলা উদ্ভূত <span class="text-danger">*</span></label>

                                                    <div class="" id="CaseCategorDiv">
                                                        <select name="case_origin" id="CaseCategory"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($GovCaseDivisionCategory as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_origin') == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name_bn }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলা নং(উদ্ভূত)<span class="text-danger">*</span></label>

                                                    <div class="" id="CaseCategorDiv">
                                                        <select name="case_number_origin" id="CaseCategory"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($GovCaseDivisionCategory as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_number_origin') == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name_bn }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>সিএমপি নং <span class="text-danger">*</span></label>
                                                    <input type="text" name="cmp_no" id="cmp_no"
                                                        class="form-control form-control-sm" placeholder="মামলা নং "
                                                        required="required">
                                                    {{-- <input type="hidden" name="caseId" value=""> --}}
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>



                                                <div class="col-lg-4 mb-5">
                                                    <label>লিভ টু আপীল নং <span class="text-danger">*</span></label>
                                                    <input type="text" name="cmp_no" id="cmp_no"
                                                        class="form-control form-control-sm" placeholder="মামলা নং "
                                                        required="required">
                                                    {{-- <input type="hidden" name="caseId" value=""> --}}
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>




                                                <div class="col-lg-12 mb-5">
                                                    <table width="100%" border="1" id="badiDiv"
                                                        style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>রিট পিটিশনারের নাম <span class="text-danger">*</span> </th>

                                                            <th>ঠিকানা <span class="text-danger">*</span></th>
                                                            {{-- <th width="50">
                                                                <a href="javascript:void();" id="addBadiRow"
                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2"><i
                                                                        class="fas fa-plus-circle"></i></a>
                                                            </th> --}}
                                                        </tr>

                                                    </table>
                                                </div>


                                                <div class="col-lg-6 mb-5">
                                                    <table width="100%" border="1" id="MainBibadiDiv"
                                                        class="mb-5" style="border:1px solid #dcd8d8;">

                                                        <tr>
                                                            <th>মূল রেসপন্ডেন্ট নাম <span class="text-danger">*</span>
                                                            </th>
                                                            {{-- <th width="50">
                                                                <a href="javascript:void();" id="addMainBibadiRow"
                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                </a>
                                                            </th> --}}
                                                        </tr>
                                                        <tr></tr>
                                                        {{-- <input type="hidden" id="mainBibadi_count" value="1"> --}}
                                                    </table>
                                                </div>

                                                <div class="col-lg-6 mb-5">
                                                    <table width="100%" border="1" id="bibadiDiv" class="mb-5"
                                                        style="border:1px solid #dcd8d8;">

                                                        <tr>
                                                            <th>অন্যান্য রেসপন্ডেন্ট নাম <span class="text-danger">*</span>
                                                            </th>
                                                            {{-- <th width="50">
                                                                <a href="javascript:void();" id="addBibadiRow"
                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                </a>
                                                            </th> --}}
                                                        </tr>
                                                        <tr></tr>
                                                    </table>
                                                </div>



                                                <div class="col-lg-8 mb-5">
                                                    <label>বিষয়বস্তু(সংক্ষিপ্ত)<small class="text-danger">(১০০ অক্ষরের
                                                            বেশি নয়)
                                                        </small> </label>
                                                    <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3" spellcheck="false"></textarea>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>এফিডেভিট দাখিলকারী রেসপন্ডেন্ট <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="cmp_no" id="cmp_no"
                                                        class="form-control form-control-sm" placeholder=""
                                                        required="required">
                                                    {{-- <input type="hidden" name="caseId" value=""> --}}
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>সংশ্লিষ্ট আইন কর্মকর্তা <br>(ধরনর মামলা উদ্ভূত)<span
                                                            class="text-danger">*</span></label>

                                                    <div class="" id="concernPersonDesignationDiv">
                                                        <select name="concern_person_designation"
                                                            id="concern_person_designation"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($concern_person_desig as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('concern_person_designation') == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-lg-4">
                                                    <label>রায় ঘোষণার তারিখ<span class="text-danger"></span></label>
                                                    <input type="text" name="result_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="দিন/মাস/বছর" autocomplete="off">
                                                </div>


                                                <div class="col-md-4">
                                                    <label class="form-group font-weight-bolder font-size-h5">রায় ফলাফল
                                                    </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="result" id="result"
                                                                value="1" />
                                                            <span></span>সরকারের পক্ষে</label>
                                                        <label class="radio">
                                                            <input type="radio" name="result" id="result"
                                                                value="2" />
                                                            <span></span>সরকারের বিপক্ষে</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-8">
                                                <label>মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
                                                <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                    spellcheck="false"></textarea>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-lg-4 mb-5">
                                                    <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="result_copy_asking_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="দিন/মাস/বছর" autocomplete="off">
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>রায়ের নকল প্রাপ্তির তারিখ<span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="result_copy_reciving_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="দিন/মাস/বছর" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <fieldset class="">
                                                    <div
                                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                        <div class="d-flex align-items-center mr-2 py-2">
                                                            <h3 class="mb-0 mr-8">রায়
                                                                <span class="text-danger">*</span>
                                                            </h3>
                                                        </div>

                                                        <div class="symbol-group symbol-hover py-2">
                                                            <div class="symbol symbol-30 symbol-light-primary"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="" role="button"
                                                                data-original-title="ফাইল যুক্ত করুণ">

                                                                <div id="addFileRow">
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
                                                        <table width="100%" class="border-0 px-5" id="fileDiv"
                                                            style="border:1px solid #dcd8d8;">
                                                            <tr></tr>
                                                        </table>
                                                        <input type="hidden" id="other_attachment_count"
                                                            value="1">
                                                    </div>
                                                </fieldset>
                                            </div>



                                        </fieldset>
                                        {{-- </div> --}}

                                        <!--end::Card-->
                                    </div>
                                </div>
                                <div class="form-footer" style="display: flex;justify-content: center;">
                                    <button type="submit" id="caseGeneralInfoSaveBtn"
                                        class="submit-button">সংরক্ষণ</button>
                                </div>
                            </form>
                        </div>
                        {{-- ---------- end মামলার সাধারণ তথ্য----------- --}}


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

    @include('gov_case.case_register.create_js')
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
                alert(getCatType);
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
