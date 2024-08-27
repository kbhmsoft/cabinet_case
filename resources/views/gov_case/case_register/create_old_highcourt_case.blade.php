@extends('layouts.cabinet.cab_default')

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

        /* Tooltip container */
        .tooltip-icon {
            position: relative;
            display: inline-block;
        }

        .tooltip-button {
            color: #d80517;
        }

        /* Tooltip text */
        .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: rgb(237, 232, 232);
            color: black;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip-icon:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
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
                        <a class="nav-link" id="agaist_gov_order_taken_tab" href="#agaist_gov_order_taken" data-toggle="tab"
                            role="tab" aria-controls="contact" aria-selected="false">সরকারের বিপক্ষে <br>প্রদত্ত রায়
                            বাস্তবায়ন</a>
                    </li>

                    {{-- <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link" id="contempt_case_tab" href="#contempt_case" data-toggle="tab" role="tab"
                            aria-controls="contact" aria-selected="false">কনটেম্প্ট মামলা/<br>অন্যান্য</a>
                    </li> --}}
                </ul>
            </div>

            <div class="card-body">
                <div class="table-responsive ajax-data-container pt-3">
                    <div class="tab-content" id="myTabContent">

                        <form id="oldCaseGeneralInfoFrom" action="{{ route('cabinet.case.store') }}" class="form"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- start মামলার সাধারণ তথ্য --}}

                            <div class="tab-pane active" id="case_general_information" role="tabpanel"
                                aria-labelledby="home-tab">

                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        {{-- <div class="step" id=""> --}}
                                        <fieldset class="mb-8">
                                            <!-- <legend> মামলার সাধারণ তথ্য</legend> -->
                                            <div class="form-group row">
                                                <input type="hidden" name="court" id="court" value="2">

                                                <div class="col-sm-12">
                                                    <div id="firstrequriedfields" class="form-group row">
                                                        <div class="col-lg-4 mb-5">
                                                            <label for="CaseCategory">মামলার ক্যাটেগরি <span
                                                                    class="text-danger">*</span></label>

                                                            <div class="" id="CaseCategorDiv">
                                                                <select name="case_category" id="CaseCategory"
                                                                    class="form-control form-control-sm"
                                                                    required="required">
                                                                    <option value="">-- নির্বাচন করুন --</option>
                                                                    @foreach ($GovCaseDivisionCategory as $value)
                                                                        <option value="{{ $value->id }}"
                                                                            {{ old('case_category') == $value->id ? 'selected' : '' }}>
                                                                            {{ $value->name_bn }} </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="text-danger d-none vallidation-message">This
                                                                    field
                                                                    can not be empty</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 mb-5">
                                                            <label for="case_category_type">মামলার শ্রেণী/কেস-টাইপ <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="" id="CaseCategorDiv">
                                                                <select name="case_category_type" id="case_category_type"
                                                                    class="form-control form-control-sm"
                                                                    required="required">
                                                                    <option value="">-- নির্বাচন করুন --</option>
                                                                </select>
                                                                <span class="text-danger d-none vallidation-message">This
                                                                    field
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
                                                            <span class="text-danger d-none vallidation-message">This field
                                                                can not be empty</span>
                                                        </div>

                                                        <div class="col-lg-4 mb-5">
                                                            <label for="case_year">বছর <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="case_year" id="case_year"
                                                                class="form-control form-control-sm common_yearpicker"
                                                                placeholder="বছর" autocomplete="off" required="required">
                                                            <span class="text-danger d-none vallidation-message">This field
                                                                can
                                                                not be empty</span>
                                                        </div>


                                                        <div class="col-lg-4">
                                                            <table width="100%" border="1" id="highcourtAdalatDiv"
                                                                class="mb-5" style="border:1px solid #dcd8d8;">
                                                                <tr>
                                                                    <th class="other_bibadi_name other_respondent">আদালতের নাম
                                                                        (Justice Name)<span class="text-danger">*</span>
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
                                                            <label for="case_date">রুল ইস্যুর তারিখ <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="case_date" id="case_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                required="required">

                                                            <span class="text-danger d-none vallidation-message">This field
                                                                can
                                                                not be empty</span>
                                                        </div>

                                                        <div class="col-lg-12 mb-5">
                                                            <table width="100%" border="1" id="advocateLawerDiv"
                                                                style="border:1px solid #dcd8d8;">
                                                                <tr>
                                                                    <th>সংশ্লিষ্ট আইন কর্মকর্তা <span
                                                                            class="text-danger">*</span></th>
                                                                    <th>সংশ্লিষ্ট আইন কর্মকর্তার নাম <span
                                                                            class="text-danger">*</span></th>
                                                                    <th width="30">
                                                                        <a href="javascript:void(0);"
                                                                            id="addAdvocateLawer"
                                                                            class="btn btn-sm btn-primary pr-2"><i
                                                                                class="fas fa-plus-circle"></i></a>

                                                                    </th>
                                                                </tr>
                                                                <tr></tr>
                                                            </table>
                                                            <input type="hidden" id="survey_count" value="1">
                                                        </div>




                                                    </div>

                                                    <div id="secondrequriedfields" class="form-group row">
                                                        <div class="col-lg-12 mb-5">
                                                            <table width="100%" border="1" id="badiDiv"
                                                                style="border:1px solid #dcd8d8;">
                                                                <tr>
                                                                    <th class="petisioner_name badi_name">পিটিশনারের নাম
                                                                        <span class="text-danger">*</span>
                                                                    </th>

                                                                    <th class="petisioner_address badi_address">পিটিশনারের
                                                                        ঠিকানা

                                                                    </th>
                                                                    {{-- <th width="50">
                                                                        <a href="javascript:void();" id="addBadiRow"
                                                                            class="btn btn-sm btn-primary font-weight-bolder pr-2"><i
                                                                                class="fas fa-plus-circle"></i></a>
                                                                    </th> --}}
                                                                </tr>
                                                                {{-- <tr></tr> --}}
                                                            </table>


                                                            <div class="col-lg-12" style="display: flex;">
                                                                <div class="col-lg-5 mb-5">
                                                                    <label>মোট পিটিশনারের সংখ্যা</label>
                                                                    <select name="total_badi_number"
                                                                        id="total_badi_number"
                                                                        class="form-control form-control-sm">
                                                                        <option value="">মোট পিটিশনারের সংখ্যা
                                                                            নির্বাচন করুন</option>
                                                                        <?php
                                                                        for ($i = 1; $i <= 1000; $i++) {
                                                                            echo "<option value='$i'>$i</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <input type="hidden" name="caseId" value="">
                                                                </div>


                                                                <div class="col-lg-7 mb-5 product-image">
                                                                    <table width="100%" border="1" id="bibadiDiv"
                                                                        class="mb-5" style="border:1px solid #dcd8d8;">
                                                                        <tr>
                                                                            <th class="other_bibadi_name other_respondent"
                                                                                colspan="2">
                                                                                রেসপন্ডেন্ট তালিকা
                                                                                <span class="tooltip-icon">
                                                                                    <i class="fas fa-info-circle tooltip-button"></i>
                                                                                    <span class="tooltip-text">ড্রপডাউন থেকে অফিস
                                                                                        সিলেক্ট করুণ। কোন অফিস না পেলে অন্যান্য সিলেক্ট
                                                                                        করে নাম লিখুন</span>
                                                                                </span>
                                                                            </th>
                                                                            <th width="50">
                                                                                <a href="javascript:void();" id="addBibadiRow"
                                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2"
                                                                                    onclick="addBibadiRowFunc()">
                                                                                    <i class="fas fa-plus-circle"></i>
                                                                                </a>
                                                                            </th>
                                                                        </tr>
                                                                    </table>
                                                                </div>


                                                            </div>

                                                        </div>


                                                        <div class="col-lg-12 mb-5">
                                                            <label>বিষয়বস্তু(সংক্ষিপ্ত) </label>
                                                            <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3" spellcheck="false"></textarea>
                                                        </div>

                                                        <div class="col-lg-6 mb-5">
                                                            <label>মামলা সংশ্লিষ্ট অর্থের পরিমান</label>(যদি আর্থিক সংশ্লেষ
                                                            থাকে
                                                            বা সরকারি অর্থ ব্যয়/প্রদানের বিষয় থাকে অথবা মামলাভুক্ত সম্পত্তির
                                                            সম্ভাব্য মূল্য ইত্যাদি)

                                                            <input name="money_amount" class="form-control"
                                                                id="money_amount" rows="1"
                                                                spellcheck="false"></input>
                                                        </div>

                                                        <div class="col-lg-12 mb-5">
                                                            <div class="col-md-6">
                                                                <label
                                                                    class="form-group font-weight-bolder font-size-h5">স্থগিতাদেশ/স্থিতাবস্থা/অন্তর্বর্তীকালীন
                                                                    আদেশ প্রদান করা হয়েছে কিনা
                                                                </label>
                                                                <div class="radio-inline">
                                                                    <label class="radio">
                                                                        <input type="radio"
                                                                            name="postponed_interim_have"
                                                                            id="postponed_interim_have" value="1" />
                                                                        <span></span>হ্যাঁ</label>
                                                                    <label class="radio">
                                                                        <input type="radio"
                                                                            name="postponed_interim_have"
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
                                                    </div>
                                                    {{-- starting সংযুক্তি  --}}
                                                    <div class="col-md-12">
                                                        <div id="thirdrequriedfields">
                                                            <fieldset class="">
                                                                <div
                                                                    class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                    <div
                                                                        class="d-flex align-items-center mr-2 py-2 file_type">
                                                                        <th class="mb-0 mr-8 ">রুলের কপি সংযুক্ত করুন
                                                                            <span class="text-danger">*</span>
                                                                        </th>
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
                                                                    <table width="100%" class="border-0 px-5"
                                                                        id="fileDiv" style="border:1px solid #dcd8d8;">
                                                                        <tr></tr>
                                                                    </table>
                                                                    <input type="hidden" id="other_attachment_count"
                                                                        value="1">
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>

                                                    {{-- end সংযুক্তি --}}
                                                </div>
                                            </div>
                                        </fieldset>

                                    </div>
                                </div>
                                <div class="form-footer mt-5">
                                    <div class="from-group row">
                                        <div class="col-md-6" style="display: flex;justify-content: left;">

                                        </div>
                                        <div class="col-md-6" style="display: flex;justify-content: right;">
                                            <button type="button" id="caseGeneralInfoNextBtn"
                                                class="submit-button">পরবর্তী <i class="fas fa-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- ---------- end মামলার সাধারণ তথ্য----------- --}}

                            {{-- ------------- start জবাব প্রেরণ ------------- --}}
                            <div class="tab-pane" id="sending_reply" role="tabpanel" aria-labelledby="home-tab">

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
                                                            <label>দফাওয়ারি জবাব সলিসিটর অনুবিভাগে প্রেরণের তারিখ </label>
                                                            <input type="text" name="result_sending_date"
                                                                id="result_sending_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off">
                                                        </div>

                                                        <div class="col-lg-6 mb-5 mt-8">
                                                            <label>দফাওয়ারি জবাব সলিসিটর অনুবিভাগে প্রেরণের স্মারক </label>
                                                            <input type="text" name="result_sending_memorial"
                                                                id="result_sending_memorial"
                                                                class="form-control form-control-sm" placeholder=""
                                                                autocomplete="off">
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
                                                                                5MB)</sub>
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

                                                        {{-- <div class="col-md-6 mb-5">
                                                            <label>মন্তব্য</label>
                                                            <textarea name="comments" class="form-control" id="comments" rows="3" spellcheck="false">
                                                                </textarea>
                                                        </div> --}}


                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>

                                    </div>
                                </div>
                                <div class="form-footer mt-5">
                                    <div class="from-group row">
                                        <div class="col-md-6" style="display: flex;justify-content: left;">
                                            <button type="button" id="seendingReplyPrevtBtn" class="submit-button"><i
                                                    class="fas fa-arrow-left"></i> পূর্ববর্তী </button>
                                        </div>
                                        <div class="col-md-6" style="display: flex;justify-content: right;">
                                            <button type="button" id="seendingReplyNextBtn"
                                                class="submit-button">পরবর্তী <i class="fas fa-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- ------------- end জবাব প্রেরণ ------------- --}}



                            {{-- ------------- start আদালতে জবাব দাখিল ------------- --}}
                            <div class="tab-pane" id="adalat_reply" role="tabpanel" aria-labelledby="home-tab">

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


                                <div class="form-footer mt-5">
                                    <div class="from-group row">
                                        <div class="col-md-6" style="display: flex;justify-content: left;">
                                            <button type="button" id="adalatReplySendingPrevtBtn"
                                                class="submit-button"><i class="fas fa-arrow-left"></i> পূর্ববর্তী
                                            </button>
                                        </div>
                                        <div class="col-md-6" style="display: flex;justify-content: right;">
                                            <button type="button" id="adalatReplySendingNextBtn"
                                                class="submit-button">পরবর্তী <i class="fas fa-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- ------------- end আদালতে জবাব দাখিল  ------------- --}}

                            {{-- ------------- start স্থগিতাদেশ/অন্তর্বর্তীকালীন আদেশ সম্পর্কিত------------- --}}
                            <div class="tab-pane" id="suspension_order" role="tabpanel" aria-labelledby="home-tab">

                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        <input type="hidden" id="caseIDForSuspention" name="case_id">

                                        <fieldset>

                                            <div class="form-group row">


                                                <div class="col-md-6">
                                                    <label class="form-group font-weight-bolder font-size-h5">
                                                        স্থগিতাদেশ/স্থিতাবস্থা/অন্তর্বর্তীকালীন আদেশ প্রদান করা হয়েছে
                                                        কিনা
                                                    </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="postponed_interim_have"
                                                                id="postponed_interim_have" value="1">
                                                            <span></span>হ্যাঁ</label>
                                                        <label class="radio">
                                                            <input type="radio" name="postponed_interim_have"
                                                                id="postponed_interim_have_not" value="0">
                                                            <span></span>না</label>
                                                    </div>
                                                </div>


                                                <div class="col-md-6" id="postponed_interim_data_details">
                                                    <div class="col-md-12 mb-5">
                                                        <label>স্থগিতাদেশের সংক্ষিপ্ত বিবরণ</label>
                                                        <textarea name="postponed_interim_data_details" class="form-control" id="postponed_interim_data_details"
                                                            rows="5" spellcheck="false"></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 mb-5">
                                                    <div class="col-md-6">
                                                        <label class="form-group font-weight-bolder font-size-h5">আদেশ
                                                            তামিল/বাস্তবায়নের সিদ্ধান্ত গ্রহণ করা হয়েছে
                                                        </label>
                                                        <div class="radio-inline">
                                                            <label class="radio">
                                                                <input type="radio" name="adesh_tamil_decision_taken"
                                                                    id="adesh_tamil_decision_taken" value="1" />
                                                                <span></span>হ্যাঁ</label>
                                                            <label class="radio">
                                                                <input type="radio" name="adesh_tamil_decision_taken"
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
                                                                    <textarea name="adesh_tamil_decision_data_details" class="form-control" id="adesh_tamil_decision_data_details"
                                                                        rows="5" spellcheck="false"></textarea>
                                                                </div>
                                                            </div>

                                                            {{-- starting সংযুক্তি  --}}
                                                            <div class="col-md-12 mt-8">
                                                                <fieldset class="">
                                                                    <div
                                                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                        <div class="d-flex align-items-center mr-2 py-2">
                                                                            <h3 class="mb-0 mr-8">সংযুক্তি (বাস্তবায়নে
                                                                                গৃহীত
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

                                                                                <div id="adeshTamilDecisionFileRow">
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
                                                                            id="adeshTamilDecisionFileDiv"
                                                                            style="border:1px solid #dcd8d8;">
                                                                            <tr></tr>
                                                                        </table>
                                                                        <input type="hidden"
                                                                            id="adesh_tamil_attachment_count"
                                                                            value="1">
                                                                    </div>
                                                                </fieldset>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-group font-weight-bolder font-size-h5">আদেশের
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


                                                    <div class="p-5" id="suspension_order_data_details">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1" id="suspension_order_solicitor_checkbox"
                                                                name="sending_request_for_appeal_against_intreim_person_solicitor">
                                                            <label class="form-check-label lawyer_title"
                                                                for="suspension_order_solicitor_checkbox">
                                                                সলিসিটর বরাবর
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1" id="suspension_order_law_officer_checkbox"
                                                                name="sending_request_for_appeal_against_intreim_person_law_officer">
                                                            <label class="form-check-label lawyer_title"
                                                                for="law_officer_checkbox">
                                                                আইন কর্মকর্তা/প্যানেল আইনজীবী বরাবর
                                                            </label>
                                                        </div>
                                                    </div>


                                                    <div class="suspension_order_div">
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

                                                                                <div id="appealSubmissionFileRow">
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
                                                                            id="appealSubmissionFileDiv"
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
                                                            <div class="col-md-8 mb-5 mt-6"
                                                                id="suspensionOrderTrackingNumberField"
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
                                                </div>
                                            </div>

                                            {{-- starting সংযুক্তি  --}}
                                            <div class="col-md-12">
                                                <fieldset class="">
                                                    <div
                                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                        <div class="d-flex align-items-center mr-2 py-2">
                                                            <h3 class="mb-0 mr-8">সংযুক্তি (স্থগিতাদেশের/অন্তর্বর্তীকালীন
                                                                আদেশের কপি সংযুক্ত করুন)
                                                                <span class="text-danger">*</span>
                                                            </h3>
                                                        </div>

                                                        <div class="symbol-group symbol-hover py-2">
                                                            <div class="symbol symbol-30 symbol-light-primary"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                role="button" data-original-title="ফাইল যুক্ত করুণ">

                                                                <div id="addSuspensionOrderFileRow">
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
                                                            id="suspensionOrderFileDiv" style="border:1px solid #dcd8d8;">
                                                            <tr></tr>
                                                        </table>
                                                        <input type="hidden" id="suspension_order_attachment_count"
                                                            value="1">
                                                    </div>
                                                </fieldset>
                                            </div>

                                            {{-- end সংযুক্তি --}}
                                        </fieldset>

                                    </div>
                                </div>
                                <div class="form-footer mt-5">
                                    <div class="from-group row">
                                        <div class="col-md-6" style="display: flex;justify-content: left;">
                                            <button type="button" id="suspensionOrderPrevtBtn" class="submit-button"><i
                                                    class="fas fa-arrow-left"></i> পূর্ববর্তী </button>
                                        </div>
                                        <div class="col-md-6" style="display: flex;justify-content: right;">
                                            <button type="button" id="suspensionOrderNextBtn"
                                                class="submit-button">পরবর্তী <i class="fas fa-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- ------------- end স্থগিতাদেশ/অন্তর্বর্তীকালীন আদেশ সম্পর্কিত------------- --}}


                            <div class="tab-pane" id="final_order" role="tabpanel" aria-labelledby="home-tab">

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
                                                    <div class="col-lg-4">
                                                        <label>রায় ঘোষণার তারিখ<span class="text-danger"></span></label>
                                                        <input type="text" name="result_date"
                                                            class="form-control form-control-sm  common_datepicker"
                                                            placeholder="দিন/মাস/বছর" autocomplete="off">
                                                    </div>

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

                                                    <div class="col-md-12">
                                                        <label>মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
                                                        <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                            spellcheck="false"></textarea>
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
                                                </div>

                                                <div class="col-md-12">
                                                    <fieldset class="">
                                                        <div
                                                            class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                            <div class="d-flex align-items-center mr-2 py-2">
                                                                <h3 class="mb-0 mr-8">সংযুক্তি
                                                                    (চূড়ান্ত আদেশ/রায় সম্পর্কিত কপি সংযুক্ত করুন)

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
                                <div class="form-footer mt-5">
                                    <div class="from-group row">
                                        <div class="col-md-6" style="display: flex;justify-content: left;">
                                            <button type="button" id="finalOrderPrevtBtn" class="submit-button"><i
                                                    class="fas fa-arrow-left"></i> পূর্ববর্তী </button>
                                        </div>
                                        <div class="col-md-6" style="display: flex;justify-content: right;">
                                            <button type="button" id="finalOrderNextBtn" class="submit-button">পরবর্তী
                                                <i class="fas fa-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- ------------- start সরকারের বিপক্ষে প্রদত্ত রায় বাস্তবায়ন সম্পর্কিত------------- --}}
                            <div class="tab-pane" id="agaist_gov_order_taken" role="tabpanel"
                                aria-labelledby="home-tab">

                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        {{-- <input type="hidden" id="caseIDForSuspention" name="case_id"
                                            value="{{ $case->id }}"> --}}

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
                                                                            <h3 class="mb-0 mr-8">সংযুক্তি (বাস্তবায়নে
                                                                                গৃহীত
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
                                                                    name="against_gov_appeal_against_adesh_decision_taken"
                                                                    id="appeal_against_adesh_decision_taken"
                                                                    value="1" />
                                                                <span></span>হ্যাঁ</label>
                                                            <label class="radio">
                                                                <input type="radio"
                                                                    name="against_gov_appeal_against_adesh_decision_taken"
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
                                                                        name="against_gov_adesh_tamil_decision_yes_taken"
                                                                        id="adesh_tamil_decision_yes_taken"
                                                                        value="1" />
                                                                    <span></span>হ্যাঁ</label>
                                                                <label class="radio">
                                                                    <input type="radio"
                                                                        name="against_gov_adesh_tamil_decision_yes_taken"
                                                                        id="adesh_tamil_decision_yes_taken_not"
                                                                        value="0" checked />
                                                                    <span></span>না</label>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="p-5" id="against_order_taken_solicitor_div">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1"
                                                                id="against_gov_order_taken_solicitor_checkbox"
                                                                name="against_order_taken_person_solicitor"
                                                                id="against_order_taken_solicitor_checkbox">
                                                            <label class="form-check-label lawyer_title"
                                                                for="solicitor_checkbox">
                                                                সলিসিটর বরাবর
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1"
                                                                id="against_gov_order_taken_law_officer_checkbox"
                                                                name="against_order_taken_person_law_officer">
                                                            <label class="form-check-label lawyer_title"
                                                                for="law_officer_checkbox">
                                                                বিজ্ঞ আইনজীবী বরাবর
                                                            </label>
                                                        </div>
                                                    </div>


                                                    <div class="against_order_taken_appeal_submission_div">
                                                        <div class="form-group row">
                                                            <div class="col-lg-6 mb-5 mt-8">
                                                                <label>আপিল দায়েরের অনুরোধের তারিখ
                                                                </label>
                                                                <input type="text"
                                                                    name="against_gov_appeal_submission_requesting_date"
                                                                    id="appeal_submission_requesting_date"
                                                                    class="form-control form-control-sm  common_datepicker"
                                                                    placeholder="দিন/মাস/বছর" autocomplete="off">
                                                            </div>

                                                            <div class="col-lg-6 mb-5 mt-8">
                                                                <label>আপিল দায়েরের অনুরোধের স্মারক
                                                                </label>
                                                                <input type="text"
                                                                    name="against_gov_appeal_submission_requesting_memorial"
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
                                                                                    id="addAgainstGovAppealSubmissionRequestFileRow">
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
                                                                            id="againstGovAppealSubmissionRequestFileDiv"
                                                                            style="border:1px solid #dcd8d8;">
                                                                            <tr></tr>
                                                                        </table>
                                                                        <input type="hidden"
                                                                            id="against_gov_order_taken_appeal_submission_attachment_count"
                                                                            value="1">
                                                                    </div>
                                                                </fieldset>
                                                            </div>

                                                            <div class="col-md-8 mb-5 mt-6"
                                                                id="againstGovOrderTakenTrackingNumberField"
                                                                style="display: none;">
                                                                <label>সলিসিটর বরাবর প্রেরীত জবাব সলট্র্যাক-এ এন্ট্রি করা
                                                                    হলে
                                                                    ট্র্যাকিং নম্বর প্রদান করুন</label>
                                                                <input type="text"
                                                                    name="against_gov_intreim_order_soltrack_tracking_number"
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

                                                                <div id="addAgainstGovOrderTakenDecisionFileRow">
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
                                                            id="againstGovOrderTakenDecisionFileDiv"
                                                            style="border:1px solid #dcd8d8;">
                                                            <tr></tr>
                                                        </table>
                                                        <input type="hidden" id="against_gov_order_taken_decision_attachment_count"
                                                            value="1">
                                                    </div>
                                                </fieldset>
                                            </div>

                                            {{-- end সংযুক্তি --}}
                                        </fieldset>

                                    </div>
                                </div>
                                <div class="form-footer mt-5">
                                    <div class="from-group row">
                                        <div class="col-md-6" style="display: flex;justify-content: left;">
                                            <button type="button" id="againstOrderTakenPrevtBtn"
                                                class="submit-button"><i class="fas fa-arrow-left"></i> পূর্ববর্তী
                                            </button>
                                        </div>
                                        <div class="col-md-6" style="display: flex;justify-content: right;">
                                            <button type="submit" class="action-button submit-button save-button"
                                                id="saveOldHighCourtCaseBtn">সংরক্ষণ</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- ------------- start কনটেম্প্ট মামলা সম্পর্কিত------------- --}}

                            {{-- ------------- end কনটেম্প্ট মামলা------------- --}}
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
        {{-- </div> --}}

    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <h2>Please fill out the following required fields:</h2>
            <ul id="emptyFieldsList"></ul>
            <button class="close-button">Close X</button>
        </div>
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

    @include('gov_case.case_register.create_old_highcourt_case_js')
    <script type="text/javascript">
        $(document).ready(function() {
            addBadiRowFunc();
            addBibadiRowFunc();
            addAdvocateLawerFunc();
        });
    </script>


    <script type="text/javascript">


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

            // ============JS For Next & Prev Btn==============//
            $('#caseGeneralInfoNextBtn').on("click", function() {
                $("#sending_reply_tab").trigger('click');
            });

            $('#seendingReplyPrevtBtn').on("click", function() {
                $("#trainee_tab_item").trigger('click');
            });

            $('#seendingReplyNextBtn').on("click", function() {
                $("#adalat_reply_tab").trigger('click');
            });

            $('#adalatReplySendingPrevtBtn').on("click", function() {
                $("#sending_reply_tab").trigger('click');
            });

            $('#adalatReplySendingNextBtn').on("click", function() {
                $("#suspension_order_tab").trigger('click');
            });

            $('#suspensionOrderPrevtBtn').on("click", function() {
                $("#adalat_reply_tab").trigger('click');
            });

            $('#suspensionOrderNextBtn').on("click", function() {
                $("#final_order_tab").trigger('click');
            });

            $('#finalOrderNextBtn').on("click", function() {
                $("#agaist_gov_order_taken_tab").trigger('click');
            });

            $('#finalOrderPrevtBtn').on("click", function() {
                $("#suspension_order_tab").trigger('click');
            });

            $('#againstOrderTakenPrevtBtn').on("click", function() {
                $("#final_order_tab").trigger('click');
            });

            $('#finalOrderNextBtn').on("click", function() {
                $("#contempt_case_tab").trigger('click');
            });

            // $('#contemptCasePrevtBtn').on("click", function() {
            //     $("#final_order_tab").trigger('click');
            // });

            // ==========//JS For Next & Prev Btn==============//



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



            // $("#postponed_order_details").hide();
            // $("#interim_order_details_div").hide();
            // $("#postponed_order_have").click(function() {
            //     $("#postponed_order_details").show();
            // });

            // $("#postponed_order_not").click(function() {
            //     $("#postponed_order_details").hide();
            // });

            // $("#interim_order_have").click(function() {
            //     $("#interim_order_details_div").show();
            // });

            // $("#interim_order_not").click(function() {
            //     $("#interim_order_details_div").hide();
            // });

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

                        //---------------- Disable the "postponed_order_have" radio button-------------------------

                        $("#postponed_order_have").prop("disabled", true);
                    } else {
                        $("#interim_order_details_div").hide();
                        //--------------- Enable the "postponed_order_have" radio button--------------------------

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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            var createApplicationFormRoute = "{{ route('cabinet.case.createApplicationForm', ':caseNo') }}";
            // Function to trigger validation when any of the input fields change
            $('#case_year, #case_no, #case_category_type').change(function() {
                var caseNo = $('#case_no').val(); // Get the case number
                var caseYear = $('#case_year').val(); // Get the case year
                var caseCategory = $('#case_category_type').val(); // Get the case category

                // Proceed with AJAX request only if all fields are filled
                if (caseNo && caseYear && caseCategory) {
                    $.ajax({
                        url: "{{ route('cabinet.case.check-case-no') }}",
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'case_no': caseNo,
                            'case_year': caseYear,
                            'case_category': caseCategory
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

            $('#postponed_interim_data_details').hide();

            $('input[name="postponed_interim_have"][value="0"]').prop('checked', true);
            $('input[name="postponed_interim_have"]').change(function() {
                if ($(this).val() == '1') {
                    $('#postponed_interim_data_details').show();
                } else {
                    $('#postponed_interim_data_details').hide();
                }
            });
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
@endsection
