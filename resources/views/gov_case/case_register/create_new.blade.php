@extends('layouts.cabinet.cab_default')
<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

<!-- Include SweetAlert JS -->
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script> --}}

@section('content')

    @php
        $concernPersonDesig = '<option value="">-- নির্বাচন করুন --</option>';

        for ($i = 0; $i < sizeof($concern_person_desig); $i++) {
            $concernPersonDesig .=
                '<option value="' .
                $concern_person_desig[$i]->id .
                '">' .
                $concern_person_desig[$i]->name_bn .
                '</option>';
        }
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
    <style>
        .lawyer_title {
            font-size: 18px;
        }

        .form-short-title {
            font-size: 1.25rem;
            font-weight: 900;
            margin-top: -20px;
            color: rgb(235, 58, 58);
        }
    </style>
    <!--begin::Row-->
    <div class="row">

        {{-- <div class="col-md-12"> --}}
        <!--begin::Card-->
        <div style="width:100%" class="card gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
                <h5 class="form-short-title">(মামলার বিষয়বস্তুর সাথে সরাসরি সংশ্লিষ্ট মূল বিবাদী অফিস মামলার তথ্য এন্ট্রি
                    করবে)*</h5>
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

            <div id="tab_header_tabs tab-design" class="trainee_details_card_header course_details_new_tabs">
                <ul class="nav details_trainee_tab nav-tabs myTab" role="tablist">
                    <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link active" id="trainee_tab_item" data-toggle="tab" href="#case_general_information"
                            role="tab" aria-controls="home" aria-selected="true">মামলার সাধারণ <br> তথ্য</a>
                    </li>

                    <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link" id="sending_reply_tab" data-toggle="tab" href="#sending_reply" role="tab"
                            aria-controls="profile" aria-selected="false">জবাব <br>প্রেরণ</a>
                    </li>

                    <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link" id="adalat_reply_tab" data-toggle="tab" href="#adalat_reply" role="tab"
                            aria-controls="profile" aria-selected="false">আদালতে জবাব <br>দাখিল</a>
                    </li>

                    <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link" id="suspension_order_tab" href="#suspension_order" data-toggle="tab"
                            role="tab" aria-controls="contact" aria-selected="false">স্থগিতাদেশ/<br>অন্তর্বর্তীকালীন
                            আদেশ সম্পর্কিত</a>
                    </li>

                    <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link" id="final_order_tab" href="#final_order" data-toggle="tab" role="tab"
                            aria-controls="contact" aria-selected="false">চূড়ান্ত আদেশ/<br>রায় সম্পর্কিত</a>
                    </li>

                    <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link" id="contempt_case_tab" href="#contempt_case" data-toggle="tab" role="tab"
                            aria-controls="contact" aria-selected="false">কনটেম্প্ট মামলা/<br>অন্যান্য</a>
                    </li>
                </ul>
            </div>

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
                                                <input type="hidden" name="court" id="court" value="2">

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
                                                    <label>মামলার শ্রেণী/কেস-টাইপ <span
                                                            class="text-danger">*</span></label>
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
                                                        class="form-control form-control-sm"
                                                        placeholder="(type digits in English)" required="required"
                                                        onkeypress="return allowBanglaAndEnglishNumerals(event)">
                                                    <input type="hidden" name="caseId" value="">
                                                    <span class="text-danger d-none vallidation-message">This field can not
                                                        be empty</span>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>বছর <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_year" id="case_year"
                                                        class="form-control form-control-sm common_yearpicker"
                                                        placeholder="বছর" autocomplete="off" required="required">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>

                                                {{-- <div class="col-lg-4 mb-5">
                                                    <label>আদালতের নাম (Justice Name)</label>
                                                    <div class="" id="AdalatDiv">
                                                        <select name="highcourt_adalat" id="HighCourtAdalat"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($highCourtAdalat as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('highcourt_adalat') == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div> --}}


                                                <div class="col-lg-4 mb-5">
                                                    <table width="100%" border="1" id="highcourtAdalatDiv"
                                                        class="mb-5" style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th class="other_bibadi_name other_respondent">আদালতের নাম (Justice Name)
                                                            </th>
                                                            <th width="50">
                                                                <a href="javascript:void();" id="addHighcourtAdalatRow"
                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                </a>
                                                            </th>
                                                        </tr>
                                                        <tr></tr>
                                                    </table>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>রুল ইস্যুর তারিখ <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_date" id="case_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="দিন/মাস/বছর" autocomplete="off" required="required">

                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>

                                                {{-- <div class="col-lg-4 mb-5">
                                                    <label>সংশ্লিষ্ট আইন কর্মকর্তা <span
                                                            class="text-danger">*</span></label>

                                                <div class="col-lg-12 mb-5">
                                                    <table width="100%" border="1" id="advocateLawerDiv"
                                                        style="border:1px solid #dcd8d8;">
                                                        <tr>

                                                <div class="col-lg-4 mb-5">
                                                    <label>সংশ্লিষ্ট আইন কর্মকর্তার নাম<span
                                                            class="text-danger">*</span></label>

                                                    <div class="" id="concernPersonNameDiv">
                                                        <select name="concern_user_id" id="concern_user_id"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>

                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div> --}}

                                                {{-- <div class="container">
                                                    <div id="dynamicDivs">
                                                        <!-- Initial divs -->
                                                        <div class="row mb-3 dynamic-div">
                                                            <div class="col-lg-4 mb-5">
                                                                <label>সংশ্লিষ্ট আইন কর্মকর্তা <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="concernPersonDesignationDiv">
                                                                    <select name="concern_person_designation"
                                                                        class="form-control form-control-sm"
                                                                        required="required">
                                                                        <option value="">-- নির্বাচন করুন --</option>
                                                                        <!-- Assuming $concern_person_desig is a PHP variable containing designations -->
                                                                        @foreach ($concern_person_desig as $value)
                                                                            <option value="{{ $value->id }}">
                                                                                {{ $value->name_bn }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span
                                                                        class="text-danger d-none validation-message">This
                                                                        field can not be empty</span>
                                                                </div>
                                                            </div>


                                                            <div class="col-lg-4 mb-5">
                                                                <label>সংশ্লিষ্ট আইন কর্মকর্তার নাম<span
                                                                        class="text-danger">*</span></label>
                                                                <div class="concernPersonNameDiv">
                                                                    <select name="concern_user_id"
                                                                        class="form-control form-control-sm"
                                                                        required="required">
                                                                        <option value="">-- নির্বাচন করুন --</option>
                                                                    </select>
                                                                    <span
                                                                        class="text-danger d-none validation-message">This
                                                                        field can not be empty</span>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-lg-4 mb-5 align-self-end">
                                                                <button class="btn btn-primary add-btn mb-1"><i
                                                                        class="fas fa-plus-circle"></i></button>
                                                                <button class="btn btn-danger remove-btn d-none"><i
                                                                        class="fas fa-minus-circle"></i></button>
                                                            </div> --}}
                                                {{-- </div>
                                                    </div> --}}
                                                {{-- </div> --}}
                                                <div class="col-lg-12 mb-5">
                                                    <table width="100%" border="1" id="advocateLawerDiv"
                                                        style="border:1px solid #dcd8d8;">
                                                        <tr>

                                                            <th class="col-lg-6">সংশ্লিষ্ট আইন কর্মকর্তা <span
                                                                    class="text-danger">*</span></th>
                                                            <th class="col-lg-6">সংশ্লিষ্ট আইন কর্মকর্তার নাম <span
                                                                    class="text-danger">*</span></th>
                                                            <th width="30">
                                                                <a href="javascript:void(0);" id="addAdvocateLawer"
                                                                    class="btn btn-sm btn-primary pr-2"><i
                                                                        class="fas fa-plus-circle"></i></a>
                                                            </th>
                                                        </tr>
                                                        <tr></tr>
                                                    </table>
                                                    <input type="hidden" id="survey_count" value="1">
                                                </div>
                                                <!-- jQuery -->
                                                {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}




                                                <div class="col-lg-12 mb-5">
                                                    <table width="100%" border="1" id="badiDiv"
                                                        style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>পিটিশনারের নাম <span class="text-danger">*</span> </th>

                                                            <th>পিটিশনারের ঠিকানা <span class="text-danger"></span></th>
                                                            {{-- <th width="50">
                                                                <a href="javascript:void();" id="addBadiRow"
                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2"><i
                                                                        class="fas fa-plus-circle"></i></a>
                                                            </th> --}}
                                                        </tr>
                                                        {{-- <tr></tr> --}}
                                                    </table>
                                                </div>


                                                <div class="col-lg-12" style="display: flex;">
                                                    <div class="col-lg-5 mb-5">
                                                        <label>মোট পিটিশনারের সংখ্যা</label>
                                                        <select name="total_badi_number" id="total_badi_number"
                                                            class="form-control form-control-sm">
                                                            <option value="">মোট পিটিশনারের সংখ্যা নির্বাচন করুন
                                                            </option>
                                                            <?php
                                                            for ($i = 1; $i <= 1000; $i++) {
                                                                echo "<option value='$i'>$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <input type="hidden" name="caseId" value="">
                                                    </div>

                                                    <div class="col-lg-7 mb-5">
                                                        <table width="100%" border="1" id="bibadiDiv"
                                                            class="mb-5" style="border:1px solid #dcd8d8;">
                                                            <tr>
                                                                <th class="other_bibadi_name other_respondent">অন্যান্য রেসপন্ডেন্টদের নাম
                                                                </th>
                                                                <th width="50">
                                                                    <a href="javascript:void();" id="addBibadiRow"
                                                                        class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                                        <i class="fas fa-plus-circle"></i>
                                                                    </a>
                                                                </th>
                                                            </tr>
                                                            <tr></tr>
                                                        </table>
                                                    </div>
                                                </div>


                                                <div class="col-lg-12 mb-5">
                                                    <label>বিষয়বস্তু(সংক্ষিপ্ত) </label>
                                                    <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3" spellcheck="false"></textarea>
                                                </div>

                                                <div class="col-lg-6 mb-5">
                                                    <label>মামলা সংশ্লিষ্ট অর্থের পরিমান</label>(যদি আর্থিক সংশ্লেষ থাকে বা
                                                    সরকারি অর্থ ব্যয়/প্রদানের বিষয় থাকে অথবা মামলাভুক্ত সম্পত্তির সম্ভাব্য
                                                    মূল্য ইত্যাদি)

                                                    <input name="money_amount" class="form-control" id="money_amount"
                                                        rows="1" spellcheck="false"></input>
                                                </div>

                                                <div class="col-lg-12 mb-5">
                                                    <div class="col-md-6">
                                                        <label
                                                            class="form-group font-weight-bolder font-size-h5">স্থগিতাদেশ/স্থিতাবস্থা/অন্তর্বর্তীকালীন
                                                            আদেশ প্রদান করা হয়েছে কিনা
                                                        </label>
                                                        <div class="radio-inline">
                                                            <label class="radio">
                                                                <input type="radio" name="postponed_interim_have"
                                                                    id="postponed_interim_have" value="1" />
                                                                <span></span>হ্যাঁ</label>
                                                            <label class="radio">
                                                                <input type="radio" name="postponed_interim_have"
                                                                    id="postponed_interim_have_not" value="0"
                                                                    checked />
                                                                <span></span>না</label>
                                                        </div>
                                                    </div>

                                                    <div class="p-5" id="postponed_interim_data_details">
                                                        <div class="col-md-12 mb-5">
                                                            <label>স্থগিতাদেশের সংক্ষিপ্ত
                                                                বিবরণ</label>
                                                            <textarea name="postponed_interim_data_details" class="form-control" id="postponed_interim_data_details"
                                                                rows="3" spellcheck="false"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- starting সংযুক্তি  --}}
                                                <div class="col-md-12">
                                                    <fieldset class="">
                                                        <div
                                                            class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                            <div class="d-flex align-items-center mr-2 py-2">
                                                                <h3 class="mb-0 mr-8">রুলের কপি সংযুক্ত করুন
                                                                    <span class="text-danger">*</span>
                                                                    <sub class="text-danger">(PDF, সর্বোচ্চ সাইজ :
                                                                        5MB)</sub>
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

                        {{-- ------------- start জবাব প্রেরণ ------------- --}}
                        <div class="tab-pane" id="sending_reply" role="tabpanel" aria-labelledby="home-tab">
                            <form id="sendingReplyForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        <input type="hidden" id="caseIDForAnswer" name="case_id">

                                        <fieldset class="mb-8">
                                            <div class="col-lg-12 mb-5">

                                                <div class="col-md-6">
                                                    <label class="form-group font-weight-bolder font-size-h5">জবাব প্রেরণ
                                                        করা হয়েছে কিনা
                                                    </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="sending_reply_have"
                                                                id="sending_reply_have" value="1" />
                                                            <span></span>হ্যাঁ</label>
                                                        <label class="radio">
                                                            <input type="radio" name="sending_reply_have"
                                                                id="sending_reply_have_not" value="0" checked />
                                                            <span></span>না</label>
                                                    </div>
                                                </div>

                                                <div class="p-5" id="sending_reply_data_details">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="solicitor_checkbox" name="sending_reply_person_solicitor">
                                                        <label class="form-check-label lawyer_title"
                                                            for="solicitor_checkbox">
                                                            সলিসিটর বরাবর
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="law_officer_checkbox"
                                                            name="sending_reply_person_law_officer">
                                                        <label class="form-check-label lawyer_title"
                                                            for="law_officer_checkbox">
                                                            আইন কর্মকর্তা/প্যানেল আইনজীবী বরাবর
                                                        </label>
                                                    </div>
                                                </div>


                                                <div class="sending_reply_div">
                                                    <div class="form-group row">
                                                        <div class="col-lg-6 mb-5 mt-8">
                                                            <label>দফাওয়ারি জবাব প্রেরণের তারিখ <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="result_sending_date"
                                                                id="result_sending_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off" required>
                                                        </div>

                                                        <div class="col-lg-6 mb-5 mt-8">
                                                            <label>দফাওয়ারি জবাব প্রেরণের স্মারক <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="result_sending_memorial"
                                                                id="result_sending_memorial"
                                                                class="form-control form-control-sm" placeholder=""
                                                                autocomplete="off" required>
                                                        </div>

                                                        {{-- starting সংযুক্তি  --}}
                                                        <div class="col-md-12 mt-8">
                                                            <fieldset class="">
                                                                <div
                                                                    class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                    <div class="d-flex align-items-center mr-2 py-2">
                                                                        <h3 class="mb-0 mr-8">সংযুক্তি (জবাব কপি সংযুক্ত
                                                                            করুন)
                                                                            <sub class="text-danger">(PDF, সর্বোচ্চ সাইজ:
                                                                                5MB) <span
                                                                                    class="text-danger">*</span></sub>
                                                                        </h3>
                                                                    </div>

                                                                    <div class="symbol-group symbol-hover py-2">
                                                                        <div class="symbol symbol-30 symbol-light-primary"
                                                                            data-toggle="tooltip" data-placement="top"
                                                                            title="" role="button"
                                                                            data-original-title="ফাইল যুক্ত করুণ">

                                                                            <div id="addReplyFileRow">
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
                                                                        id="replyFileDiv"
                                                                        style="border:1px solid #dcd8d8;">
                                                                        <tr></tr>
                                                                    </table>
                                                                    <input type="hidden" id="reply_attachment_count"
                                                                        value="1">
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        {{-- end সংযুক্তি --}}
                                                        <div class="col-md-8 mb-5 mt-6" id="trackingNumberField"
                                                            style="display: none;">
                                                            <label>সলিসিটর বরাবর প্রেরীত জবাব সলট্র্যাক-এ এন্ট্রি করা হলে
                                                                ট্র্যাকিং নম্বর প্রদান করুন</label>
                                                            <input type="text" name="soltrack_tracking_number"
                                                                class="form-control">
                                                        </div>

                                                        <div class="col-lg-6 mb-5 mt-10">
                                                            <label>সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের তারিখ
                                                            </label>
                                                            <input type="text"
                                                                name="result_sending_date_solisitor_to_ag"
                                                                id="result_sending_date_solisitor_to_ag"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off">
                                                        </div>
                                                        <div class="col-lg-6 mb-5 mt-10">
                                                            <label>সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের
                                                                স্মারক </label>
                                                            <input type="text"
                                                                name="result_sending_memorial_solisitor_to_ag"
                                                                id="result_sending_memorial_solisitor_to_ag"
                                                                class="form-control form-control-sm" placeholder=""
                                                                autocomplete="off">
                                                        </div>
                                                        <div class="col-lg-6 mb-5">
                                                            <label>সংশ্লিষ্ট আদালতে জবাব দাখিলের তারিখ </label>
                                                            <input type="text" name="reply_submission_date"
                                                                id="reply_submission_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="form-footer" style="display: flex;justify-content: center;">
                                    <button type="submit" id="sendingReplySaveBtn"
                                        class="action-button submit-button">সংরক্ষণ</button>
                                </div>
                            </form>
                        </div>
                        {{-- ------------- end জবাব প্রেরণ ------------- --}}

                        {{-- ------------- start আদালতে জবাব দাখিল ------------- --}}
                        <div class="tab-pane" id="adalat_reply" role="tabpanel" aria-labelledby="home-tab">
                            <form id="adalatReplySubmitForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        <input type="hidden" id="caseIDForAnswer" name="case_id">
                                        <fieldset class="mb-8">
                                            <div class="col-lg-12 mb-5">

                                                <div class="col-md-6">
                                                    <label class="form-group font-weight-bolder font-size-h5">আদালতে জবাব
                                                        (affidavit) দাখিল করা হয়েছে কিনা
                                                    </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="adalat_reply_submit_have"
                                                                id="adalat_reply_submit_have" value="1" />
                                                            <span></span>হ্যাঁ</label>
                                                        <label class="radio">
                                                            <input type="radio" name="adalat_reply_submit_have"
                                                                id="adalat_reply_submit_have_not" value="0"
                                                                checked />
                                                            <span></span>না</label>
                                                    </div>
                                                </div>

                                                <div class="adalat_reply_div">
                                                    <div class="form-group row">
                                                        <div class="col-lg-6 mb-5 mt-8">
                                                            <label>আদালতে জবাব দাখিলের তারিখ </label>
                                                            <input type="text" name="adalat_reply_sending_date"
                                                                id="adalat_reply_sending_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off">
                                                        </div>

                                                        {{-- starting সংযুক্তি  --}}
                                                        <div class="col-md-12 mt-8">
                                                            <fieldset class="">
                                                                <div
                                                                    class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                    <div class="d-flex align-items-center mr-2 py-2">
                                                                        <h3 class="mb-0 mr-8">সংযুক্তি (আদালতে জবাব দাখিল
                                                                            কপি সংযুক্ত
                                                                            করুন)
                                                                            <sub class="text-danger">(PDF, সর্বোচ্চ সাইজ:
                                                                                5MB)</sub>
                                                                        </h3>
                                                                    </div>

                                                                    <div class="symbol-group symbol-hover py-2">
                                                                        <div class="symbol symbol-30 symbol-light-primary"
                                                                            data-toggle="tooltip" data-placement="top"
                                                                            title="" role="button"
                                                                            data-original-title="ফাইল যুক্ত করুণ">

                                                                            <div id="addAdalatReplyFileRow">
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
                                                                        id="adalatReplyFileDiv"
                                                                        style="border:1px solid #dcd8d8;">
                                                                        <tr></tr>
                                                                    </table>
                                                                    <input type="hidden"
                                                                        id="adalat_reply_attachment_count" value="1">
                                                                </div>
                                                            </fieldset>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="form-footer" style="display: flex;justify-content: center;">
                                    <button type="submit" id="adalatReplySubmitSaveBtn"
                                        class="action-button submit-button">সংরক্ষণ</button>
                                </div>
                            </form>
                        </div>
                        {{-- ------------- end আদালতে জবাব দাখিল  ------------- --}}

                        {{-- ------------- start স্থগিতাদেশ/অন্তর্বর্তীকালীন আদেশ সম্পর্কিত------------- --}}
                        <div class="tab-pane" id="suspension_order" role="tabpanel" aria-labelledby="home-tab">
                            <form id="suspensionOrderForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        <input type="hidden" id="caseIDForSuspention" name="case_id">

                                        <fieldset>

                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label class="form-group font-weight-bolder font-size-h5">স্থগিতাদেশের
                                                    </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="postponed_order"
                                                                id="postponed_order_have" value="1" />
                                                            <span></span>আছে</label>
                                                        <label class="radio">
                                                            <input type="radio" name="postponed_order"
                                                                id="postponed_order_not" value="0" checked />
                                                            <span></span>নেই</label>
                                                    </div>
                                                </div>



                                                <div class="row p-5" id="postponed_order_details">
                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিল

                                                        </label>
                                                        <input type="text" name="appeal_against_postpond_interim_order"
                                                            id="appeal_against_postpond_interim_order"
                                                            class="form-control form-control-sm" placeholder=""
                                                            autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিলের
                                                            তারিখ <span class="text-danger"></span></label>
                                                        <input type="text"
                                                            name="appeal_against_postpond_interim_order_date"
                                                            id="appeal_against_postpond_interim_order_date"
                                                            class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                                    </div>
                                                    <div class="col-md-6 mb-5">

                                                        <label>স্থগিতাদেশের বিবরণ</label>
                                                        <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3" spellcheck="false"></textarea>
                                                    </div>
                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের আদেশের বিরুদ্ধে আপিলের বিবরণ <span
                                                                class="text-danger"></span></label>
                                                        <textarea type="text" name="appeal_against_postpond_interim_order_details"
                                                            id="appeal_against_postpond_interim_order_details" rows="3" class="form-control"autocomplete="off"></textarea>
                                                    </div>
                                                    <div class="col-lg-6 mb-5">
                                                        <label>প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের স্মারক <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="tamil_requesting_memorial"
                                                            id="tamil_requesting_memorial"
                                                            class="form-control form-control-sm"autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-6 mb-5">
                                                        <label>প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের তারিখ <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="tamil_requesting_date"
                                                            id="tamil_requesting_date"
                                                            class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                                    </div>

                                                    <div class="col-md-12">
                                                        <fieldset class="">
                                                            <div
                                                                class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                <div class="d-flex align-items-center mr-2 py-2">
                                                                    <h3 class="mb-0 mr-8">সংযুক্তি (স্থগিতাদেশের
                                                                        আদেশের কপি সংযুক্ত করুন) <small
                                                                            class="text-danger">*(PDF, সর্বোচ্চ সাইজ:
                                                                            5MB)</small>
                                                                    </h3>

                                                                </div>

                                                                <div class="symbol-group symbol-hover py-2">
                                                                    <div class="symbol symbol-30 symbol-light-primary"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="" role="button"
                                                                        data-original-title="ফাইল যুক্ত করুণ">

                                                                        <div id="addSuspensionOrderFileRow">
                                                                            <span
                                                                                class="symbol-label font-weight-bold bg-success">
                                                                                <i
                                                                                    class="text-white fa flaticon2-plus font-size-sm"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="mt-3 px-5">
                                                                <table width="100%" class="border-0 px-5"
                                                                    id="suspensionOrderFileDiv"
                                                                    style="border:1px solid #dcd8d8;">
                                                                    <tr></tr>
                                                                </table>
                                                                <input type="hidden"
                                                                    id="suspension_order_attachment_count" value="1">
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    {{-- <div class="form-footer"
                                                        style="display: flex;justify-content: center;">
                                                        <button type="submit" id="suspensionOrderSaveBtn"
                                                            class="action-button submit-button mt-3 ml-40">সংরক্ষণ</button>
                                                    </div> --}}
                                                </div>


                                                <div class="col-md-6">
                                                    <label
                                                        class="form-group font-weight-bolder font-size-h5">অন্তর্বর্তীকালীন
                                                        আদেশ </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="interim_order"
                                                                id="interim_order_have" value="1" />
                                                            <span></span>আছে</label>
                                                        <label class="radio">
                                                            <input type="radio" name="interim_order"
                                                                id="interim_order_not" value="0" checked />
                                                            <span></span>নেই</label>
                                                    </div>
                                                </div>

                                                <div class="row p-5" id="interim_order_details_div">
                                                    <label class="ml-2">অন্তর্বর্তীকালীন আদেশের বিবরণ</label>
                                                    <textarea name="interim_order_details" class="form-control ml-2" id="interim_order" rows="5"
                                                        spellcheck="false"></textarea>

                                                    <div class="col-md-12 mt-5">
                                                        <fieldset class="">
                                                            <div
                                                                class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                <div class="d-flex align-items-center mr-2 py-2">
                                                                    <h3 class="mb-0 mr-8">সংযুক্তি (অন্তর্বর্তীকালীন
                                                                        আদেশের কপি সংযুক্ত করুন)
                                                                        <small class="text-danger">*(PDF, সর্বোচ্চ
                                                                            সাইজ:5MB)</small>
                                                                    </h3>
                                                                </div>

                                                                <div class="symbol-group symbol-hover py-2">
                                                                    <div class="symbol symbol-30 symbol-light-primary"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="" role="button"
                                                                        data-original-title="ফাইল যুক্ত করুণ">

                                                                        <div id="addSuspensionOrderFileRowTwo">
                                                                            <span
                                                                                class="symbol-label font-weight-bold bg-success">
                                                                                <i
                                                                                    class="text-white fa flaticon2-plus font-size-sm"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                    </div>
                                                    <br>
                                                    <div class="mt-3 px-5">
                                                        <table width="100%" class="border-0 px-5"
                                                            id="suspensionOrderFileDivTwo"
                                                            style="border:1px solid #dcd8d8;">
                                                            <tr></tr>
                                                        </table>
                                                        <input type="hidden" id="suspension_order_attachment_count"
                                                            value="1">
                                                    </div>
                                                </div>
                                        </fieldset>
                                    </div>
                                    <div class="form-footer" style="display: flex;justify-content: center;">
                                        <button type="submit" id="suspensionOrderSaveBtn"
                                            class="action-button submit-button mt-3 ml-40">সংরক্ষণ</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                        {{-- ------------- end স্থগিতাদেশ/অন্তর্বর্তীকালীন আদেশ সম্পর্কিত------------- --}}


                        <div class="tab-pane" id="final_order" role="tabpanel" aria-labelledby="home-tab">
                            <form id="finalOrderForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        {{-- <div class="step"> --}}
                                        <input type="hidden" id="caseIDForFinalOrder" name="case_id">
                                        <fieldset class="mb-8">
                                            {{-- <legend> মামলার ফলাফল</legend> --}}
                                            <div class="form-group row">
                                                <div class="col-md-12 mb-5">
                                                    <input type="checkbox" id="is_final_order" name="is_final_order"
                                                        value="1" onclick="showAlert()">
                                                    <label for="is_final_order"> মামলার রায়/চুড়ান্ত আদেশ
                                                        হয়ে থাকলে সিলেক্ট করুন</label><br>
                                                </div>
                                            </div>

                                            <div id="finalOrderDiv">
                                                <div class="form-group row">
                                                    <div class="col-md-6 mb-5">
                                                        <label class="form-group font-weight-bolder font-size-h5">ফলাফল
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
                                                    <div class="col-md-6">
                                                        <label>মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
                                                        <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                            spellcheck="false"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-group font-weight-bolder font-size-h5">সরকারের
                                                            বিপক্ষে হলে আপিল করা হয়েছে কিনা</label>
                                                        <div class="radio-inline">
                                                            <label class="radio">
                                                                <input type="radio" name="is_appeal" id="is_appeal"
                                                                    value="1" />
                                                                <span></span>হ্যাঁ </label>
                                                            <label class="radio">
                                                                <input type="radio" name="is_appeal" id="is_appeal"
                                                                    value="2" checked="checke" />
                                                                <span></span>না</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label>রায় ঘোষণার তারিখ<span class="text-danger"></span></label>
                                                        <input type="text" name="result_date"
                                                            class="form-control form-control-sm  common_datepicker"
                                                            placeholder="দিন/মাস/বছর" autocomplete="off">
                                                    </div>
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
                                                    <div class="col-lg-4 mb-5">
                                                        <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="appeal_requesting_memorial"
                                                            id="appeal_requesting_memorial"
                                                            class="form-control form-control-sm"autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="appeal_requesting_date"
                                                            id="appeal_requesting_date"
                                                            class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 mb-5">
                                                        <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span
                                                                class="text-danger"></span></label>
                                                        <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3"
                                                            spellcheck="false">
                                                                </textarea>

                                                    </div>
                                                </div>
                                                <div class="form-group row" id="civilRevisionDiv">
                                                    <div class="col-lg-4">
                                                        <label>প্রস্তাবের বিষয়বস্তু (বাংলায়)<span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="contents_of_proposal_civil_revision"
                                                            class="form-control form-control-sm" autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 mb-5">
                                                        <label>যে মোকদ্দমার পরিপ্রেক্ষিতে প্রস্তাব প্রেরণ (বাংলায়)<span
                                                                class="text-danger"></span></label>
                                                        <input type="text"
                                                            name="sending_motions_in_view_of_that_litigation_civil_revision"
                                                            class="form-control form-control-sm" autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 mb-5">
                                                        <label>প্রস্তাব তারিখ(বাংলায়) <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="proposal_date_civil_revision"
                                                            class="form-control form-control-sm  common_datepicker"
                                                            placeholder="দিন/মাস/বছর" autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 mb-5">
                                                        <label>প্রস্তাব স্মারক নম্বর <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="proposal_memorial_civil_revision"
                                                            id="proposal_memorial_civil_revision"
                                                            class="form-control form-control-sm"autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>যোগাযোগের ইমেইল (ইংরেজিতে) <span
                                                                class="text-danger"></span></label>
                                                        <input type="email" name="contact_email_civil_revision"
                                                            id="contact_email_civil_revision"
                                                            class="form-control form-control-sm"autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>ফোকাল পার্সনের নাম (বাংলায়) <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="focal_person_name_civil_revision"
                                                            id="focal_person_name_civil_revision"
                                                            class="form-control form-control-sm "autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>ফোকাল পার্সনের পদবী (বাংলায়) <span
                                                                class="text-danger"></span></label>
                                                        <input type="text"
                                                            name="focal_person_designation_civil_revision"
                                                            id="focal_person_designation_civil_revision"
                                                            class="form-control form-control-sm "autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>ফোকাল পার্সনের মোবাইল নম্বর (ইংরেজিতে) <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="focal_person_mobile_civil_revision"
                                                            id="focal_person_mobile_civil_revision"
                                                            class="form-control form-control-sm "autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="form-group row" id="writDiv">
                                                    <div class="col-lg-4">
                                                        <label>প্রস্তাবের বিষয়বস্তু (বাংলায়)<span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="contents_of_proposal_writ"
                                                            class="form-control form-control-sm" autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 mb-5">
                                                        <label>রিট মোকাদ্দমা নং<span class="text-danger"></span></label>
                                                        <input type="text" name="case_number_writ"
                                                            class="form-control form-control-sm" autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 mb-5">
                                                        <label>প্রস্তাব তারিখ(বাংলায়) <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="proposal_date_writ"
                                                            class="form-control form-control-sm common_datepicker"
                                                            placeholder="দিন/মাস/বছর" autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 mb-5">
                                                        <label>প্রস্তাব স্মারক নম্বর <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="proposal_memorial_writ"
                                                            id="proposal_memorial_writ"
                                                            class="form-control form-control-sm"autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>যোগাযোগের ইমেইল (ইংরেজিতে) <span
                                                                class="text-danger"></span></label>
                                                        <input type="email" name="contact_email_writ"
                                                            id="contact_email_writ"
                                                            class="form-control form-control-sm"autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>ফোকাল পার্সনের নাম (বাংলায়) <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="focal_person_name_writ"
                                                            id="focal_person_name_writ"
                                                            class="form-control form-control-sm "autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>ফোকাল পার্সনের পদবী (বাংলায়) <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="focal_person_designation_writ"
                                                            id="focal_person_designation_writ"
                                                            class="form-control form-control-sm "autocomplete="off">
                                                    </div>

                                                    <div class="col-lg-4 mb-5">
                                                        <label>ফোকাল পার্সনের মোবাইল নম্বর (ইংরেজিতে) <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="focal_person_mobile_writ"
                                                            id="focal_person_mobile_writ"
                                                            class="form-control form-control-sm "autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <fieldset class="">
                                                        <div
                                                            class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                            <div class="d-flex align-items-center mr-2 py-2">
                                                                <h3 class="mb-0 mr-8">সংযুক্তি
                                                                    (চূড়ান্ত আদেশ/রায় সম্পর্কিত কপি সংযুক্ত করুন)
                                                                    <span class="text-danger">*</span>
                                                                    <sub class="text-danger">(PDF, সর্বোচ্চ সাইজ:
                                                                        5MB)</sub>
                                                                </h3>
                                                            </div>

                                                            <div class="symbol-group symbol-hover py-2">
                                                                <div class="symbol symbol-30 symbol-light-primary"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="" role="button"
                                                                    data-original-title="ফাইল যুক্ত করুণ">

                                                                    <div id="addFinalOrderFileRow">
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
                                                                id="finalOrderFileDiv" style="border:1px solid #dcd8d8;">
                                                                <tr></tr>
                                                            </table>
                                                            <input type="hidden" id="final_order_attachment_count"
                                                                value="1">
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                {{-- end সংযুক্তি --}}
                                            </div>
                                        </fieldset>
                                        {{-- </div> --}}

                                    </div>
                                </div>
                                <div class="form-footer" style="display: flex;justify-content: center;">
                                    <button type="submit" id="finalOrderSaveBtn"
                                        class="action-button submit-button">সংরক্ষণ</button>
                                </div>
                            </form>
                        </div>


                        {{-- ------------- start কনটেম্প্ট মামলা সম্পর্কিত------------- --}}
                        <div class="tab-pane" id="contempt_case" role="tabpanel" aria-labelledby="home-tab">
                            <form id="contemptCaseForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->

                                        <input type="hidden" id="caseIDForContempt" name="case_id">
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
                                                                <sub class="text-danger">(PDF, সর্বোচ্চ সাইজ: 5MB)</sub>
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
                                                        <input type="hidden" id="contempt_attachment_count"
                                                            value="1">
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
    </div>
    <!--end::Row-->

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#select2Dropdown').select2();
        });
    </script>
    <script>
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
        // $(document).ready(function() {
        //     addBadiRowFunc();
        //     addBibadiRowFunc();

        //     // $('select').select2();
        // });
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

            $(document).ready(function() {
                //------------ Initially hide the sections---------------

                $("#postponed_order_details").hide();
                $("#interim_order_details_div").hide();

                //---------------- Event handlers for the "স্থগিতাদেশের" radio buttons----------------

                $("input[name='postponed_order']").change(function() {
                    if ($("#postponed_order_have").is(":checked")) {
                        $("#postponed_order_details").show();

                        //--------------- Disable the "interim_order_have" radio button----------------------

                        $("#interim_order_have").prop("disabled", true);
                    } else {
                        $("#postponed_order_details").hide();

                        //--------------- Enable the "interim_order_have" radio button------------------------

                        $("#interim_order_have").prop("disabled", false);
                    }
                });

                //--------------- Event handlers for the "অন্তর্বর্তীকালীন আদেশ" radio buttons--------------

                $("input[name='interim_order']").change(function() {
                    if ($("#interim_order_have").is(":checked")) {
                        $("#interim_order_details_div").show();
                        $("#postponed_order_have").prop("disabled", true);
                    } else {
                        $("#interim_order_details_div").hide();
                        $("#postponed_order_have").prop("disabled", false);
                    }
                });
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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            var createApplicationFormRoute = "{{ route('cabinet.case.createApplicationForm', ':caseNo') }}";

            // Function to check case number when case year changes
            $('#case_year').change(function() {
                var caseNo = $('#case_no').val(); // Get the case number
                var caseYear = $(this).val(); // Get the case year

                // Proceed with AJAX request only if both fields are filled
                if (caseNo && caseYear) {
                    $.ajax({
                        url: "{{ route('cabinet.case.check-case-no') }}",
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'case_no': caseNo,
                            'case_year': caseYear
                        },
                        success: function(data) {
                            if (data.exists) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '<span style="color: red;font-size: larger;">দুঃখিত...',
                                    html: '<strong>মামলাটি <span style="color: red;font-size: larger;">' +
                                        data.officeName +
                                        '</span> কর্তৃক মূল রেসপন্ডেন্ট হিসেবে এন্ট্রি করা হয়েছে। আপনি মূল রেসপন্ডেন্ট হয়ে থাকলে মন্ত্রিপরিষদ বিভাগের কাছে পরিবর্তন/সংশোধনের অনুরোধ করুন!</strong>',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    onOpen: function() {
                                        Swal.getPopup().appendChild(
                                            $('<button>', {
                                                text: 'অনুরোধ করুন',
                                                id: 'saveButton',
                                                class: 'btn btn-success',
                                                click: function() {
                                                    var url =
                                                        createApplicationFormRoute
                                                        .replace(
                                                            ':caseNo',
                                                            caseNo);
                                                    window.location
                                                        .href = url;
                                                }
                                            })[0]
                                        );
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.adalat_reply_div').hide();
            $('input[name="adalat_reply_submit_have"][value="0"]').prop('checked', true);
            $('input[name="adalat_reply_submit_have"]').change(function() {
                if ($(this).val() == '1') {
                    $('.adalat_reply_div').show();
                } else {
                    $('.adalat_reply_div').hide();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#sending_reply_data_details').hide();
            $('#trackingNumberField').hide();
            $('input[name="sending_reply_have"]').change(function() {
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

    <script>
        $(document).ready(function() {
            // Counter to keep track of added divs
            var count = 1;

            // Function to add more divs
            $("#addDivBtn").click(function() {
                count++;
                var newDiv = $("#container").children().first().clone();
                newDiv.find("select").each(function() {
                    // Clear selected options
                    $(this).val('');
                });
                $("#container").append(newDiv);
            });
        });
    </script>
@endsection
