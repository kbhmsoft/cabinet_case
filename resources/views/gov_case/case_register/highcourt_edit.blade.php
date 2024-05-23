@extends('layouts.cabinet.cab_default')
<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
            <input type="hidden" id="formType" value="edit">
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
            </div>

            <div class="card-body">
                <div class="table-responsive ajax-data-container pt-3">
                    <div class="tab-content" id="myTabContent">


                        {{-- start মামলার সাধারণ তথ্য --}}

                        <div class="tab-pane active" id="case_general_information" role="tabpanel"
                            aria-labelledby="home-tab">
                            <form id="caseGeneralInfoFormForEdit" action="javascript:void(0)" class="form" method="POST"
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
                                                <input type="hidden" id="" name="case_id"
                                                    value="{{ $case->id }}">

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলার ক্যাটেগরি <span class="text-danger">*</span></label>

                                                    <div class="" id="CaseCategorDiv">
                                                        <select name="case_category" id="CaseCategory"
                                                            class="form-control form-control-sm">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($GovCaseDivisionCategory as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_category') == $value->id || $case->case_category_id == $value->id ? 'selected' : '' }}>
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
                                                            @foreach ($GovCaseDivisionCategoryType as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_category') == $value->id || $case->case_type_id == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name_bn }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলা নং <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_no" id="case_no"
                                                        class="form-control form-control-sm" placeholder="মামলা নং "
                                                        value="{{ $case->case_no ?? '' }}" readonly>
                                                    <input type="hidden" name="caseId" value="{{ $case->id ?? '' }}">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>বছর <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_year" id="case_year"
                                                        class="form-control form-control-sm common_yearpicker"
                                                        placeholder="বছর" autocomplete="off"
                                                        value="{{ $case->year ?? '' }}">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>



                                                {{-- <div class="col-lg-4 mb-5">
                                                    <label>আদালতের নাম (Justice Name) <span
                                                            class="text-danger">*</span></label>

                                                    <div id="AdalatDiv">
                                                        @foreach ($caseCourts as $row)
                                                            <select name="highcourt_adalat" id="HighCourtAdalat"
                                                                class="form-control form-control-sm">
                                                                <option value="">-- নির্বাচন করুন --</option>
                                                                @foreach ($highCourtAdalat as $value)
                                                                    <option value="{{ $value->id }}"
                                                                        {{ old('highcourt_adalat') == $value->id || $row->highcourt_adalat == $value->id ? 'selected' : '' }}>
                                                                        {{ $value->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endforeach

                                                        <span class="text-danger d-none validation-message">This field
                                                            cannot be empty</span>
                                                    </div>
                                                </div> --}}


                                                     <div class="col-lg-6 mb-5">
                                                    <table width="100%" border="1" id="highcourtAdalatDiv" class="mb-5"
                                                        style="border:1px solid #dcd8d8;">

                                                        <tr>
                                                            <th>আদালতের নাম (Justice Name) <span class="text-danger">*</span>
                                                            </th>
                                                            <th width="50">
                                                                <a href="javascript:void();" id="addHighcourtAdalatRow"
                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                </a>
                                                            </th>
                                                        </tr>
                                                        <tr></tr>

                                                        @foreach ($caseCourts as $row)
                                                            <tr id="bibadi_10{{ $key }}">
                                                                <td>
                                                                    <select {{ request('red') ? 'disabled' : '' }} " name="other_respondent[]" id="ministry_id" class="form-control form-control-sm">
                                                                        @foreach ($highCourtAdalat as $value)
                                                                        <option value="{{ $value->id }}"
                                                                            {{ old('highcourt_adalat') == $value->id || $row->highcourt_adalat == $value->id ? 'selected' : '' }}>
                                                                            {{ $value->name }}</option>
                                                        @endforeach
                                                        </select>
                                                        </td>
                                                        <input type="hidden" name="bibadi_id[]"
                                                            value="{{ $value->id }}">
                                                        <td>
                                                            @if ($key > 0)
                                                                <a href="javascript:void();"
                                                                    class="btn btn-sm btn-danger font-weight-bolder pr-2"
                                                                    data-id="{{ $value->id }}"
                                                                    onclick="removeRowBadiBibadiFunc(this, 'ajax_bibadi_del')">
                                                                    <i class="fas fa-minus-circle"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>রুল ইস্যুর তারিখ <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_date" id="case_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="দিন/মাস/বছর" autocomplete="off"
                                                        value="{{ $case->date_issuing_rule_nishi ?? '' }}">

                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>

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

                                                        @foreach ($caseLawers as $key => $value)
                                                            <tr>
                                                                <td>
                                                                    <select name="concernPersonDesignation[]"
                                                                        id="concernPersonDesignation_{{ $key + 1 }}"
                                                                        class="form-control form-control-sm"
                                                                        required="required"
                                                                        onchange="getConcernPerName({{ $key + 1 }})">
                                                                        @foreach ($concern_person_desig as $data)
                                                                            <option value="{{ $data->id }}"
                                                                                {{ old('concern_person_designation') == $data->id || $value->concern_person_designation == $data->id ? 'selected' : '' }}>
                                                                                {{ $data->name_bn }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <select name="concern_user_id[]"
                                                                        id="concern_user_id_{{ $key + 1 }}"
                                                                        class="form-control form-control-sm"
                                                                        required="required">
                                                                        @foreach ($lawerInfo as $data)
                                                                            <option value="{{ $data->id }}"
                                                                                {{ old('concern_user_id') == $data->id || $value->concern_user_id == $data->id ? 'selected' : '' }}>
                                                                                {{ $data->name }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    @if ($key > 0)
                                                                        <a href="javascript:void();"
                                                                            class="btn btn-sm btn-danger font-weight-bolder pr-2"
                                                                            data-id="{{ $value->id }}"
                                                                            onclick="removeRowBadiBibadiFunc(this, 'ajax_badi_del')">
                                                                            <i class="fas fa-minus-circle"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                                <input type="hidden" name="concern_person_id[]"
                                                                    value="{{ $value->id }}">
                                                            </tr>
                                                            <input type="hidden" id="survey_count"
                                                                value="{{ $key + 2 }}">
                                                        @endforeach
                                                    </table>
                                                </div>


                                                <div class="col-lg-12 mb-5">
                                                    <table width="100%" border="1" id="badiDiv"
                                                        style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>পিটিশনারের নাম <span class="text-danger">*</span> </th>

                                                            <th>ঠিকানা <span class="text-danger">*</span></th>
                                                        </tr>

                                                        <tr>
                                                            <td>
                                                                <input type="text" name="badi_name[]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $caseBadi->name ?? '' }}" placeholder="">
                                                            </td>

                                                            <td>
                                                                <input type="text" name="badi_address[]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $caseBadi->address ?? '' }}"
                                                                    placeholder="">
                                                            </td>

                                                            <input type="hidden" name="badi_id[]"
                                                                value="{{ $caseBadi->id ?? '' }}">
                                                        </tr>
                                                    </table>
                                                </div>


                                                <div class="col-lg-12 mb-5">
                                                    <div class="col-lg-4 mb-5">
                                                        <label>মোট পিটিশনারের সংখ্যা</label>
                                                        <select name="total_badi_number" id="total_badi_number"
                                                            class="form-control form-control-sm">
                                                            <option value="">মোট পিটিশনারের সংখ্যা নির্বাচন করুন
                                                            </option>
                                                            @for ($i = 1; $i <= 1000; $i++)
                                                                <option value="{{ $i }}"
                                                                    {{ old('total_badi_number') == $i || $case->total_badi_number == $i ? 'selected' : '' }}>
                                                                    {{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                        <input type="hidden" name="caseId" value="">
                                                    </div>
                                                </div>


                                                <div class="col-lg-6 mb-5">
                                                    <table width="100%" border="1" id="bibadiDiv" class="mb-5"
                                                        style="border:1px solid #dcd8d8;">

                                                        <tr>
                                                            <th>অন্যান্য রেসপন্ডেন্ট নাম <span class="text-danger">*</span>
                                                            </th>
                                                            <th width="50">
                                                                <a href="javascript:void();" id="addBibadiRow"
                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                </a>
                                                            </th>
                                                        </tr>
                                                        <tr></tr>

                                                        @foreach ($otherBibadi as $key => $val)
                                                            <tr id="bibadi_10{{ $key }}">
                                                                <td>
                                                                    <select {{ request('red') ? 'disabled' : '' }} " name="other_respondent[]" id="ministry_id" class="form-control form-control-sm">
                                                                           @foreach ($ministrys as $item)
                                                                        <option value="{{ $item->doptor_office_id }}"
                                                                            {{ $item->doptor_office_id == $val->respondent_id ? 'selected' : '' }}>
                                                                            {{ $item->office_name_bn ?? '' }} </option>
                                                        @endforeach
                                                        </select>
                                                        </td>
                                                        <input type="hidden" name="bibadi_id[]"
                                                            value="{{ $val->doptor_office_id }}">
                                                        <td>
                                                            @if ($key > 0)
                                                                <a href="javascript:void();"
                                                                    class="btn btn-sm btn-danger font-weight-bolder pr-2"
                                                                    data-id="{{ $value->doptor_office_id }}"
                                                                    onclick="removeRowBadiBibadiFunc(this, 'ajax_bibadi_del')">
                                                                    <i class="fas fa-minus-circle"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>


                                                <div class="col-lg-12 mb-5">
                                                    <label>বিষয়বস্তু(সংক্ষিপ্ত) </label>
                                                    <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3" spellcheck="false">{{ $case->subject_matter ?? '' }}</textarea>
                                                </div>

                                                <div class="col-lg-6 mb-5">
                                                    <label>মামলা সংশ্লিষ্ট অর্থের পরিমান</label>(যদি আর্থিক সংশ্লেষ থাকে বা
                                                    সরকারি অর্থ ব্যয়/প্রদানের বিষয় থাকে অথবা মামলাভুক্ত সম্পত্তির সম্ভাব্য
                                                    মূল্য ইত্যাদি)

                                                    <input name="money_amount" class="form-control" id="money_amount"
                                                        rows="1" spellcheck="false"
                                                        value="{{ $case->money_amount ?? '' }}">
                                                    </input>
                                                </div>
                                                {{--
                                                <div class="col-lg-12 mb-5">
                                                    <div class="col-md-6">
                                                        <label class="form-group font-weight-bolder font-size-h5">
                                                            স্থগিতাদেশ/স্থিতাবস্থা/অন্তর্বর্তীকালীন আদেশ প্রদান করা হয়েছে
                                                            কিনা
                                                        </label>
                                                        <div class="radio-inline">
                                                            <label class="radio">
                                                                <input type="radio" name="postponed_interim_have"
                                                                    id="postponed_interim_have" value="1"
                                                                    {{ $case->postponed_interim_have == 1 ? 'checked' : '' }}>
                                                                <span></span>হ্যাঁ</label>
                                                            <label class="radio">
                                                                <input type="radio" name="postponed_interim_have"
                                                                    id="postponed_interim_have_not" value="0"
                                                                    {{ $case->postponed_interim_have == 0 ? 'checked' : '' }}>
                                                                <span></span>না</label>
                                                        </div>
                                                    </div>

                                                    <div class="p-5" id="postponed_interim_data_details"
                                                        style="{{ $case->postponed_interim_have == 1 ? '' : 'display:none;' }}">
                                                        <div class="col-md-12 mb-5">
                                                            <label>স্থগিতাদেশের সংক্ষিপ্ত বিবরণ</label>
                                                            <textarea name="postponed_interim_data_details" class="form-control" id="postponed_interim_data_details"
                                                                rows="3" spellcheck="false">{{ $case->postponed_interim_data_details }}</textarea>
                                                        </div>
                                                    </div>
                                                </div> --}}



                                                <div class="col-lg-12 mb-5">
                                                    <div class="col-md-6">
                                                        <label class="form-group font-weight-bolder font-size-h5">
                                                            স্থগিতাদেশ/স্থিতাবস্থা/অন্তর্বর্তীকালীন আদেশ প্রদান করা হয়েছে
                                                            কিনা
                                                        </label>
                                                        <div class="radio-inline">
                                                            <label class="radio">
                                                                <input type="radio" name="postponed_interim_have"
                                                                    id="postponed_interim_have_yes" value="1"
                                                                    {{ $case->postponed_interim_have == 1 ? 'checked' : '' }}>
                                                                <span></span>হ্যাঁ
                                                            </label>
                                                            <label class="radio">
                                                                <input type="radio" name="postponed_interim_have"
                                                                    id="postponed_interim_have_no" value="0"
                                                                    {{ $case->postponed_interim_have == 0 ? 'checked' : '' }}>
                                                                <span></span>না
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="p-5" id="postponed_interim_data_details"
                                                        style="{{ $case->postponed_interim_have == 1 ? '' : 'display:none;' }}">
                                                        <div class="col-md-12 mb-5">
                                                            <label>স্থগিতাদেশের সংক্ষিপ্ত বিবরণ</label>
                                                            <textarea name="postponed_interim_data_details" class="form-control" id="postponed_interim_data_details_textarea"
                                                                rows="3" spellcheck="false">{{ $case->postponed_interim_data_details }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>






                                                {{-- starting সংযুক্তি  --}}
                                                <div class="col-md-12">
                                                    <fieldset class="">
                                                        <div
                                                            class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                            <div class="d-flex align-items-center mr-2 py-2">
                                                                <h3 class="mb-0 mr-8">সংযুক্তি (রুল কপি সংযুক্ত করুন)
                                                                    <span class="text-danger">*</span>
                                                                </h3>
                                                            </div>
                                                            <div class="symbol-group symbol-hover py-2">
                                                                <div class="symbol symbol-30 symbol-light-primary"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="" role="button"
                                                                    data-original-title="ফাইল যুক্ত করুণ">
                                                                    <div id="addMainFileRow">
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
                                                            <table width="100%" class="border-0 px-5" id="mainFileDiv"
                                                                style="border:1px solid #dcd8d8;">


                                                                <tr>
                                                                    @foreach ($files as $row)
                                                                        <div class="form-group mb-2"
                                                                            id="deleteFile{{ $row->id }}">
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <button class="btn bg-success-o-75"
                                                                                        type="button">{{ en2bn(++$key) . ' - নম্বর :' }}</button>
                                                                                </div>

                                                                                <input readonly type="text"
                                                                                    class="form-control"
                                                                                    value="{{ $row->file_type ?? '' }}" />
                                                                                <div class="input-group-append">
                                                                                    <a href="{{ asset($row->file_path . $row->file_name) }}"
                                                                                        target="_blank"
                                                                                        class="btn btn-sm btn-success font-size-h5 float-left">
                                                                                        <i class="fa fas fa-file-pdf"></i>
                                                                                        <b>দেখুন</b>

                                                                                    </a>

                                                                                </div>
                                                                                <div class="input-group-append">
                                                                                    <a href="javascript:void(0);"
                                                                                        id="deleteRuleFileBtn_({{ $row->id }}"
                                                                                        onclick="deleteRuleFile({{ $row->id }} )"
                                                                                        class="btn btn-danger">
                                                                                        <i class="fas fa-trash-alt"></i>
                                                                                        <b>মুছুন</b>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </tr>
                                                            </table>
                                                            <input type="hidden" id="other_main_attachment_count"
                                                                value="1">
                                                        </div>
                                                    </fieldset>
                                                </div>
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
                                        <input type="hidden" id="" name="case_id"
                                            value="{{ $case->id }}">

                                        <fieldset class="mb-8">
                                            <div class="col-lg-12 mb-5">

                                                <div class="col-md-6">
                                                    <label class="form-group font-weight-bolder font-size-h5">জবাব প্রেরণ
                                                        করা হয়েছে কিনা
                                                    </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="sending_reply_have"
                                                                id="sending_reply_have" value="1"
                                                                {{ $case->sending_reply_have == 1 ? 'checked' : '' }} />
                                                            <span></span>হ্যাঁ</label>
                                                        <label class="radio">
                                                            <input type="radio" name="sending_reply_have"
                                                                id="sending_reply_have_not" value="0"
                                                                {{ $case->sending_reply_have == 0 ? 'checked' : '' }} />
                                                            <span></span>না</label>
                                                    </div>
                                                </div>


                                                <div class="p-5" id="sending_reply_data_details">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="solicitor_checkbox" name="sending_reply_person_solicitor"
                                                            {{ in_array(1, explode(',', $case->sending_reply_person_unit)) ? 'checked' : '' }}>
                                                        <label class="form-check-label lawyer_title"
                                                            for="solicitor_checkbox">সলিসিটর বরাবর</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="law_officer_checkbox"
                                                            name="sending_reply_person_law_officer"
                                                            {{ in_array(2, explode(',', $case->sending_reply_person_unit)) ? 'checked' : '' }}>
                                                        <label class="form-check-label lawyer_title"
                                                            for="law_officer_checkbox">আইন কর্মকর্তা/প্যানেল আইনজীবী
                                                            বরাবর</label>
                                                    </div>
                                                </div>

                                                <div class="sending_reply_div">
                                                    <div class="form-group row">
                                                        <div class="col-lg-6 mb-5">
                                                            <label>দফাওয়ারি জবাব সলিসিটর অনুবিভাগে প্রেরণের তারিখ </label>
                                                            <input type="text" name="result_sending_date"
                                                                id="result_sending_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="" autocomplete="off"
                                                                value="{{ $case->result_sending_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-6 mb-5">
                                                            <label>দফাওয়ারি জবাব সলিসিটর অনুবিভাগে প্রেরণের স্মারক </label>
                                                            <input type="text" name="result_sending_memorial"
                                                                id="result_sending_memorial"
                                                                class="form-control form-control-sm" placeholder=""
                                                                autocomplete="off"
                                                                value="{{ $case->result_sending_memorial ?? '' }}">
                                                        </div>

                                                        {{-- starting সংযুক্তি  --}}
                                                        <div class="col-md-12">
                                                            <fieldset class="">
                                                                <div
                                                                    class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                    <div class="d-flex align-items-center mr-2 py-2">
                                                                        <h3 class="mb-0 mr-8">সংযুক্তি (জবাব কপি সংযুক্ত
                                                                            করুন)
                                                                            <span class="text-danger">*</span>
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
                                                                        id="fileDiv" style="border:1px solid #dcd8d8;">
                                                                        @foreach ($replyFiles as $key => $value)
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="text"
                                                                                        name="file_type[]"
                                                                                        id="customFileName"
                                                                                        class="form-control form-control-sm"
                                                                                        value="{{ old('file_type', $value->file_type) }}">
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-file">

                                                                                        @if ($value->file_name)
                                                                                            <a target="_blank"
                                                                                                class="text-center font-weight-bolder text-primary text-decoration-none d-block w-100 p-2 mb-2 rounded bg-light "
                                                                                                href="{{ asset($value->file_name) }}">সংযুক্তি
                                                                                                কপি</a><br>
                                                                                        @else
                                                                                            <input type="file"
                                                                                                accept="application/pdf"
                                                                                                name="file_name[]"
                                                                                                onChange="attachmentTitle(this)"
                                                                                                class="custom-file-input"
                                                                                                id="customFile" />
                                                                                            <label id="file_error"
                                                                                                class="text-danger font-weight-bolder mt-2 mb-2"></label>
                                                                                            <label
                                                                                                class="custom-file-label custom-input"
                                                                                                for="customFile">
                                                                                            </label>
                                                                                        @endif
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        <tr></tr>
                                                                    </table>
                                                                    <input type="hidden" id="other_attachment_count"
                                                                        value="1">
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

                                                        <div class="col-md-8 mb-5 mt-6" id="trackingNumberField">
                                                            <label>সলিসিটর বরাবর প্রেরীত জবাব সলট্র্যাক-এ এন্ট্রি করা হলে
                                                                ট্র্যাকিং নম্বর প্রদান করুন</label>
                                                            <input type="text" name="soltrack_tracking_number"
                                                                class="form-control form-control-sm"
                                                                value="{{ $case->soltrack_tracking_number }}">
                                                        </div>

                                                        <div class="col-lg-6 mb-5 mt-5">
                                                            <label>সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের তারিখ
                                                            </label>
                                                            <input type="text"
                                                                name="result_sending_date_solisitor_to_ag"
                                                                id="result_sending_date_solisitor_to_ag"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $case->result_sending_date_solisitor_to_ag ?? '' }}">
                                                        </div>

                                                        <div class="col-lg-6 mb- mt-5">
                                                            <label>সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের
                                                                স্মারক </label>
                                                            <input type="text"
                                                                name="result_sending_memorial_solisitor_to_ag"
                                                                id="result_sending_memorial_solisitor_to_ag"
                                                                class="form-control form-control-sm" placeholder=""
                                                                autocomplete="off"
                                                                value="{{ $case->result_sending_memorial_solisitor_to_ag ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-6 mb-5">
                                                            <label>সংশ্লিষ্ট আদালতে জবাব দাখিলের তারিখ </label>
                                                            <input type="text" name="reply_submission_date"
                                                                id="reply_submission_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $case->reply_submission_date ?? '' }}">
                                                        </div>
                                                        {{-- <div class="col-md-6 mb-5">
                                                            <label>মন্তব্য</label>
                                                            <textarea name="comments" class="form-control" id="comments" rows="3" spellcheck="false">{{ $case->comments ?? '' }}
                                                            </textarea>
                                                        </div> --}}

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

                        {{-- ------------- start স্থগিতাদেশ/অন্তর্বর্তীকালীন আদেশ সম্পর্কিত------------- --}}
                        <div class="tab-pane" id="suspension_order" role="tabpanel" aria-labelledby="home-tab">
                            <form id="suspensionOrderForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        <input type="hidden" id="caseIDForSuspention" name="case_id"
                                            value="{{ $case->id }}">

                                        <fieldset>

                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label class="form-group font-weight-bolder font-size-h5">স্থগিতাদেশের
                                                    </label>
                                                    <div class="radio-inline">

                                                        <label class="radio">
                                                            <input type="radio" name="postponed_order"
                                                                id="postponed_order_have" value="1"
                                                                {{ $case->postponed_order == '1' ? 'checked' : '' }} />
                                                            <span></span>আছে</label>

                                                        <label class="radio">
                                                            <input type="radio" name="postponed_order"
                                                                id="postponed_order_not" value="0"
                                                                {{ $case->postponed_order == '0' ? 'checked' : '' }} />
                                                            <span></span>নেই</label>
                                                    </div>
                                                </div>

                                                @if ($case->postponed_order == '1')
                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিল
                                                        </label>
                                                        <input type="text" name="appeal_against_postpond_interim_order"
                                                            id="appeal_against_postpond_interim_order"
                                                            class="form-control form-control-sm" placeholder=""
                                                            autocomplete="off"
                                                            value="{{ $case->appeal_against_postpond_interim_order ?? '' }}">
                                                    </div>

                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিলের
                                                            তারিখ <span class="text-danger"></span></label>
                                                        <input type="text"
                                                            name="appeal_against_postpond_interim_order_date"
                                                            id="appeal_against_postpond_interim_order_date"
                                                            class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                            value="{{ $case->appeal_against_postpond_interim_order_date ?? '' }}">
                                                    </div>

                                                    <div class="col-md-6 mb-5">

                                                        <label>স্থগিতাদেশের বিবরণ</label>
                                                        <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3" spellcheck="false">{{ $case->postponed_details ?? '' }}</textarea>
                                                    </div>
                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের আদেশের বিরুদ্ধে আপিলের বিবরণ <span
                                                                class="text-danger"></span></label>
                                                        <textarea type="text" name="appeal_against_postpond_interim_order_details"
                                                            id="appeal_against_postpond_interim_order_details" class="form-control form-control-sm"autocomplete="off">{{ $case->appeal_against_postpond_interim_order_details ?? '' }}</textarea>
                                                    </div>
                                                    <div class="col-lg-6 mb-5">
                                                        <label>প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের স্মারক <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="tamil_requesting_memorial"
                                                            id="tamil_requesting_memorial"
                                                            class="form-control form-control-sm"autocomplete="off"
                                                            value="{{ $case->tamil_requesting_memorial ?? '' }}">
                                                    </div>

                                                    <div class="col-lg-6 mb-5">
                                                        <label>প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের তারিখ <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="tamil_requesting_date"
                                                            id="tamil_requesting_date"
                                                            class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                            value="{{ $case->tamil_requesting_date ?? '' }}">
                                                    </div>
                                                @endif

                                                <div class="row p-5" id="postponed_order_details">

                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিল
                                                        </label>
                                                        <input type="text" name="appeal_against_postpond_interim_order"
                                                            id="appeal_against_postpond_interim_order"
                                                            class="form-control form-control-sm" placeholder=""
                                                            autocomplete="off"
                                                            value="{{ $case->appeal_against_postpond_interim_order ?? '' }}">
                                                    </div>

                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিলের
                                                            তারিখ <span class="text-danger"></span></label>
                                                        <input type="text"
                                                            name="appeal_against_postpond_interim_order_date"
                                                            id="appeal_against_postpond_interim_order_date"
                                                            class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                            value="{{ $case->appeal_against_postpond_interim_order_date ?? '' }}">
                                                    </div>

                                                    <div class="col-md-6 mb-5">

                                                        <label>স্থগিতাদেশের বিবরণ</label>
                                                        <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3" spellcheck="false">{{ $case->postponed_details ?? '' }}</textarea>
                                                    </div>
                                                    <div class="col-lg-6 mb-5">
                                                        <label>স্থগিতাদেশের আদেশের বিরুদ্ধে আপিলের বিবরণ <span
                                                                class="text-danger"></span></label>
                                                        <textarea type="text" name="appeal_against_postpond_interim_order_details"
                                                            id="appeal_against_postpond_interim_order_details" class="form-control form-control-sm"autocomplete="off">{{ $case->appeal_against_postpond_interim_order_details ?? '' }}</textarea>
                                                    </div>
                                                    <div class="col-lg-6 mb-5">
                                                        <label>প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের স্মারক <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="tamil_requesting_memorial"
                                                            id="tamil_requesting_memorial"
                                                            class="form-control form-control-sm"autocomplete="off"
                                                            value="{{ $case->tamil_requesting_memorial ?? '' }}">
                                                    </div>

                                                    <div class="col-lg-6 mb-5">
                                                        <label>প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের তারিখ <span
                                                                class="text-danger"></span></label>
                                                        <input type="text" name="tamil_requesting_date"
                                                            id="tamil_requesting_date"
                                                            class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                            value="{{ $case->tamil_requesting_date ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label
                                                        class="form-group font-weight-bolder font-size-h5">অন্তর্বর্তীকালীন
                                                        আদেশ </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="interim_order"
                                                                id="interim_order_have" value="1"
                                                                {{ $case->interim_order == '1' ? 'checked' : '' }} />
                                                            <span></span>আছে</label>
                                                        <label class="radio">
                                                            <input type="radio" name="interim_order"
                                                                id="interim_order_not" value="0"
                                                                {{ $case->interim_order == '0' ? 'checked' : '' }} />
                                                            <span></span>নেই</label>
                                                    </div>
                                                </div>

                                                @if ($case->interim_order == '0')
                                                    <div class="col-md-6 mb-5" id="">
                                                        <label>অন্তর্বর্তীকালীন আদেশের বিবরণ</label>
                                                        <textarea name="interim_order_details" class="form-control" id="interim_order" rows="3" spellcheck="false">{{ $case->interim_order_details ?? '' }}</textarea>
                                                    </div>
                                                @endif


                                                <div class="col-md-6 mb-5" id="interim_order_details_div">
                                                    <label>অন্তর্বর্তীকালীন আদেশের বিবরণ</label>
                                                    <textarea name="interim_order_details" class="form-control" id="interim_order" rows="3" spellcheck="false">{{ $case->interim_order_details ?? '' }}</textarea>
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
                                                        <table width="100%" class="border-0 px-5" id="fileDiv"
                                                            style="border:1px solid #dcd8d8;">
                                                            @foreach ($suspensionFiles as $key => $value)
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" name="file_type[]"
                                                                            id="customFileName"
                                                                            class="form-control form-control-sm"
                                                                            value="{{ old('file_type', $value->file_type) }}">
                                                                    </td>
                                                                    <td>
                                                                        <div class="custom-file">

                                                                            @if ($value->file_name)
                                                                                <a target="_blank"
                                                                                    class="text-center font-weight-bolder text-primary text-decoration-none d-block w-100 p-2 mb-2 rounded bg-light "
                                                                                    href="{{ asset($value->file_name) }}">সংযুক্তি
                                                                                    কপি</a><br>
                                                                            @else
                                                                                <input type="file"
                                                                                    accept="application/pdf"
                                                                                    name="file_name[]"
                                                                                    onChange="attachmentTitle(this)"
                                                                                    class="custom-file-input"
                                                                                    id="customFile" />
                                                                                <label id="file_error"
                                                                                    class="text-danger font-weight-bolder mt-2 mb-2"></label>
                                                                                <label
                                                                                    class="custom-file-label custom-input"
                                                                                    for="customFile">
                                                                                </label>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr></tr>
                                                        </table>
                                                        <input type="hidden" id="other_attachment_count" value="1">
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
                                <div class="form-footer" style="display: flex;justify-content: center;">
                                    <button type="submit" id="suspensionOrderSaveBtn"
                                        class="action-button submit-button">সংরক্ষণ</button>
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
                                        <input type="hidden" id="caseIDForFinalOrder" name="case_id"
                                            value="{{ $case->id }}">
                                        <fieldset class="mb-8">
                                            {{-- <legend> মামলার ফলাফল</legend> --}}
                                            <div class="form-group row">
                                                <div class="col-md-12 mb-5">
                                                    <input type="checkbox" id="is_final_order" name="is_final_order"
                                                        value="1" onclick="showAlert()"
                                                        {{ $case->is_final_order == '1' ? 'checked' : '' }}>
                                                    <label for="is_final_order"> মামলার রায়/চুড়ান্ত আদেশ
                                                        হয়ে থাকলে সিলেক্ট করুন</label><br>
                                                </div>
                                            </div>
                                            @if ($case->is_final_order == '1')
                                                <div id="">
                                                    <div class="form-group row">
                                                        <div class="col-md-6 mb-5">
                                                            <label class="form-group font-weight-bolder font-size-h5">ফলাফল
                                                            </label>
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio" name="result" id="result"
                                                                        value="1"
                                                                        {{ $case->result == '1' ? 'checked' : '' }} />
                                                                    <span></span>সরকারের পক্ষে</label>
                                                                <label class="radio">
                                                                    <input type="radio" name="result" id="result"
                                                                        value="2"
                                                                        {{ $case->result == 2 ? 'checked' : '' }} />
                                                                    <span></span> সরকারের বিপক্ষে</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
                                                            <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                                spellcheck="false">{{ $case->result_short_dtails ?? '' }}</textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-group font-weight-bolder font-size-h5">সরকারের
                                                                বিপক্ষে হলে আপিল করা হয়েছে কিনা </label>
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio" name="is_appeal" id="is_appeal"
                                                                        value="1"{{ $case->is_appeal == '1' ? 'checked' : '' }} />
                                                                    <span></span>হ্যাঁ </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="is_appeal" id="is_appeal"
                                                                        value="2"
                                                                        {{ $case->is_appeal == '2' ? 'checked' : '' }} />
                                                                    <span></span>না</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <label>রায় ঘোষণার তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $case->result_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_copy_asking_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $case->result_copy_asking_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>রায়ের নকল প্রাপ্তির তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_copy_reciving_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $case->result_copy_reciving_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="appeal_requesting_memorial"
                                                                id="appeal_requesting_memorial"
                                                                class="form-control form-control-sm"autocomplete="off"
                                                                value="{{ $case->appeal_requesting_memorial ?? '' }}">
                                                        </div>

                                                        <div class="col-lg-4 mb-5">
                                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="appeal_requesting_date"
                                                                id="appeal_requesting_date"
                                                                class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                                value="{{ $case->appeal_requesting_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span
                                                                    class="text-danger"></span></label>
                                                            <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3"
                                                                spellcheck="false">{{ $case->reason_of_not_appealing ?? '' }}
                                                                    </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="civilRevisionDiv">
                                                        <div class="col-lg-4">
                                                            <label>প্রস্তাবের বিষয়বস্তু (বাংলায়)<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text"
                                                                name="contents_of_proposal_civil_revision"
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
                                                            <input type="text"
                                                                name="focal_person_mobile_civil_revision"
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
                                                            <label>রিট মোকাদ্দমা নং<span
                                                                    class="text-danger"></span></label>
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

                                                    {{-- starting সংযুক্তি  --}}
                                                    <div class="col-md-12">
                                                        <fieldset class="">
                                                            <div
                                                                class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                <div class="d-flex align-items-center mr-2 py-2">
                                                                    <h3 class="mb-0 mr-8">সংযুক্তি
                                                                        (চূড়ান্ত আদেশ/রায় সম্পর্কিত কপি সংযুক্ত করুন)
                                                                        <span class="text-danger">*</span>
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
                                                                    id="fileDiv" style="border:1px solid #dcd8d8;">
                                                                    @foreach ($finalFiles as $key => $value)
                                                                        <tr>
                                                                            <td>
                                                                                <input type="text" name="file_type[]"
                                                                                    id="customFileName"
                                                                                    class="form-control form-control-sm"
                                                                                    value="{{ old('file_type', $value->file_type) }}">
                                                                            </td>
                                                                            <td>
                                                                                <div class="custom-file">

                                                                                    @if ($value->file_name)
                                                                                        <a target="_blank"
                                                                                            class="text-center font-weight-bolder text-primary text-decoration-none d-block w-100 p-2 mb-2 rounded bg-light "
                                                                                            href="{{ asset($value->file_name) }}">সংযুক্তি
                                                                                            কপি</a><br>
                                                                                    @else
                                                                                        <input type="file"
                                                                                            accept="application/pdf"
                                                                                            name="file_name[]"
                                                                                            onChange="attachmentTitle(this)"
                                                                                            class="custom-file-input"
                                                                                            id="customFile" />
                                                                                        <label id="file_error"
                                                                                            class="text-danger font-weight-bolder mt-2 mb-2"></label>
                                                                                        <label
                                                                                            class="custom-file-label custom-input"
                                                                                            for="customFile">
                                                                                        </label>
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr></tr>
                                                                </table>
                                                                <input type="hidden" id="other_attachment_count"
                                                                    value="1">
                                                            </div>
                                                            <div class="mt-3 px-5">
                                                                <table width="100%" class="border-0 px-5"
                                                                    id="finalOrderFileDiv"
                                                                    style="border:1px solid #dcd8d8;">
                                                                    <tr></tr>
                                                                </table>
                                                                <input type="hidden" id="final_order_attachment_count"
                                                                    value="1">
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    {{-- end সংযুক্তি --}}
                                                </div>
                                            @else
                                                <div id="finalOrderDiv">
                                                    <div class="form-group row">
                                                        <div class="col-md-6 mb-5">
                                                            <label class="form-group font-weight-bolder font-size-h5">ফলাফল
                                                            </label>
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio" name="result" id="result"
                                                                        value="1"
                                                                        {{ $case->result == '1' ? 'checked' : '' }} />
                                                                    <span></span>সরকারের পক্ষে</label>
                                                                <label class="radio">
                                                                    <input type="radio" name="result" id="result"
                                                                        value="2"
                                                                        {{ $case->result == '2' ? 'checked' : '' }} />
                                                                    <span></span> সরকারের বিপক্ষে</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
                                                            <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                                spellcheck="false">{{ $case->result_short_dtails ?? '' }}</textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-group font-weight-bolder font-size-h5">সরকারের
                                                                বিপক্ষে হলে আপিল করা হয়েছে কিনা </label>
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio" name="is_appeal" id="is_appeal"
                                                                        value="1"{{ $case->is_appeal == '1' ? 'checked' : '' }} />
                                                                    <span></span>হ্যাঁ </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="is_appeal" id="is_appeal"
                                                                        value="2"
                                                                        {{ $case->is_appeal == '2' ? 'checked' : '' }} />
                                                                    <span></span>না</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <label>রায় ঘোষণার তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $case->result_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_copy_asking_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $case->result_copy_asking_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>রায়ের নকল প্রাপ্তির তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_copy_reciving_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $case->result_copy_reciving_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="appeal_requesting_memorial"
                                                                id="appeal_requesting_memorial"
                                                                class="form-control form-control-sm"autocomplete="off"
                                                                value="{{ $case->appeal_requesting_memorial ?? '' }}">
                                                        </div>

                                                        <div class="col-lg-4 mb-5">
                                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="appeal_requesting_date"
                                                                id="appeal_requesting_date"
                                                                class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                                value="{{ $case->appeal_requesting_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span
                                                                    class="text-danger"></span></label>
                                                            <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3"
                                                                spellcheck="false">{{ $case->reason_of_not_appealing ?? '' }}
                                                                    </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" id="civilRevisionDiv">
                                                        <div class="col-lg-4">
                                                            <label>প্রস্তাবের বিষয়বস্তু (বাংলায়)<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text"
                                                                name="contents_of_proposal_civil_revision"
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
                                                            <input type="text"
                                                                name="proposal_memorial_civil_revision"
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
                                                            <input type="text"
                                                                name="focal_person_name_civil_revision"
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
                                                            <input type="text"
                                                                name="focal_person_mobile_civil_revision"
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
                                                            <label>রিট মোকাদ্দমা নং<span
                                                                    class="text-danger"></span></label>
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

                                                    {{-- starting সংযুক্তি  --}}
                                                    <div class="col-md-12">
                                                        <fieldset class="">
                                                            <div
                                                                class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                <div class="d-flex align-items-center mr-2 py-2">
                                                                    <h3 class="mb-0 mr-8">সংযুক্তি
                                                                        (চূড়ান্ত আদেশ/রায় সম্পর্কিত কপি সংযুক্ত করুন)
                                                                        <span class="text-danger">*</span>
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
                                                                    id="finalOrderFileDiv"
                                                                    style="border:1px solid #dcd8d8;">
                                                                    <tr></tr>
                                                                </table>
                                                                <input type="hidden" id="final_order_attachment_count"
                                                                    value="1">
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    {{-- end সংযুক্তি --}}
                                                </div>
                                            @endif
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

                                        <input type="hidden" id="caseIDForContempt" name="case_id"
                                            value="{{ $case->id }}">
                                        <fieldset>
                                            <div class="form-group row">
                                                <div class="col-lg-4 mb-5">
                                                    <label>প্রযোজ্য ক্ষেত্রে কন্টেম্পট মামলা নম্বর <span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="contempt_case_no"
                                                        id="contempt_case_no"
                                                        class="form-control form-control-sm"autocomplete="off"
                                                        value="{{ $case->contempt_case_no ?? '' }}">
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label> কন্টেম্পট মামলা রুল ইস্যুর তারিখ <span
                                                            class="text-danger"></span></label>

                                                    <input type="text" name="contempt_case_isuue_date"
                                                        id="contempt_case_isuue_date"
                                                        class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                        value="{{ $case->contempt_case_isuue_date ?? '' }}">
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>কন্টেম্পট মামলার জবাব প্রেরণের তারিখ <span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="contempt_case_answer_sending_date"
                                                        id="contempt_case_answer_sending_date"
                                                        class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                        value="{{ $case->contempt_case_answer_sending_date ?? '' }}">
                                                </div>

                                                <div class="col-lg-6 mb-5">
                                                    <label>অন্যান্য পদক্ষেপের বিবরণ<br>(যদি থাকে) <span
                                                            class="text-danger"></span></label>
                                                    <textarea name="others_action_detials" class="form-control" id="others_action_detials" rows="3"
                                                        spellcheck="false">{{ $case->others_action_detials ?? '' }}
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
                                                                (চূড়ান্ত আদেশ/রায় সম্পর্কিত কপি সংযুক্ত করুন)
                                                                <span class="text-danger">*</span>
                                                            </h3>
                                                        </div>

                                                        <div class="symbol-group symbol-hover py-2">
                                                            <div class="symbol symbol-30 symbol-light-primary"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="" role="button"
                                                                data-original-title="ফাইল যুক্ত করুণ">

                                                                <div id="addContemptFileRow">
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
                                                            id="contemptFileDiv" style="border:1px solid #dcd8d8;">
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
        function deleteRuleFile(id) {
            // alert(id);
            Swal.fire({
                title: 'আপনি কি মামলার রুল কপি মুছে ফেলতে চান?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'হ্যাঁ',
                cancelButtonText: 'না'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteRuleFileBtn_' + id).addClass('loadersmall')
                    jQuery.ajax({
                        url: '{{ url('/') }}/cabinet/case/highcourt/ruleFile/delete/' +
                            id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            Swal.fire(
                                'সফল!',
                                data.message,
                                'success'
                            )
                            addMainFileRowFunc();
                            $('#deleteFile' + id).remove();

                        }
                    });
                }
            });
        }

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



<script>
      // Function to add a new row for "অন্যান্য রেসপন্ডেন্টর"
      $("#addBibadiRow").click(function(e) {
        addBibadiRowFunc();
    });

    // Function to add a new row for "অন্যান্য রেসপন্ডেন্টর"
    function addBibadiRowFunc() {
        var mk = $('#bibadiDiv tr').length;
        $('#bibadiDiv tr:last').after(Item(mk + 1));

        function Item(count) {
            var items = '';
            items += '<tr id="bibadi_' + count + '">';
            items +=
                '<td><select name="other_respondent[]" class="form-control form-control-sm other_respondentCls" hello-id="' +
                count + '">';
            items += '<option value="">-- নির্বাচন করুন --</option>';
            items +=
                '@foreach ($ministrys as $value)<option value="{{ $value->doptor_office_id }}" {{ old('ministry') == $value->doptor_office_id }}> {{ $value->office_name_bn }} </option>@endforeach';
            items += '<option value="0">অন্যান্য</option>';
            items += '</select></td>';
            items +=
                '<td><input type="text" name="other_respondent_manual_name[]" class="form-control form-control-sm d-none" placeholder="অন্যান্য রেসপন্ডেন্টর নাম লিখুন"></td>';
            items +=
                '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            items += '</tr>';
            return items;
        }

        // Initialize select2 for the newly added select element
        $('.other_respondentCls').select2();
    }

    // Function to remove a row
    function removeBibadiRow(id) {
        $(id).closest("tr").remove();
    }


</script>



    <script>
        $(document).ready(function() {
            function togglePostponedInterimDataDetails() {
                if ($('#postponed_interim_have_yes').is(':checked')) {
                    $('#postponed_interim_data_details').show();
                } else {
                    $('#postponed_interim_data_details').hide();
                }
            }

            // Call the function on page load to set the correct initial state
            togglePostponedInterimDataDetails();

            // Add change event listeners to the radio buttons
            $('input[name="postponed_interim_have"]').change(function() {
                togglePostponedInterimDataDetails();
            });
        });
    </script>
    <script>
        function showAlert() {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to check/uncheck this checkbox?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
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
            $('#select2Dropdown').select2();
        });
    </script>
    <script>
        /************************ Add multiple advocate  *************************/
        $("#addAdvocateLawer").click(function(e) {
            addAdvocateLawerFunc();
            // $('select').select2();
        });

        //add row function
        function addAdvocateLawerFunc() {

            var count = parseInt($('#survey_count').val());
            // alert(count)
            $('#survey_count').val(count + 1);
            var items = '';
            items += '<tr>';

            items += '<input type="hidden" name="concern_person_id[]" value="">';
            items +=
                '<td><select name="concernPersonDesignation[]" id="concernPersonDesignation_' + count +
                '" class="form-control form-control-sm select2" onchange="getConcernPerName(' + count +
                ')" required="required"><?php echo $concernPersonDesig; ?></select> </td>';
            items +=
                '<td><select name="concern_user_id[]" id="concern_user_id_' + count +
                '" class="form-control form-control-sm select2" required="required"><option value="">-- নির্বাচন করুন --</option></select></td>';

            if (count > 0) {
                items +=
                    '<td><a href="javascript:void(0);" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeAdvocateLawerRow(this)"> <i class="fas fa-trash"></i> </a> </td>';
            }
            items += '</tr>';

            $('#advocateLawerDiv tr:last').after(items);

            $('.select2').select2();
            //scout_id_select2_dd();
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

    <script type="text/javascript">
        var count = parseInt($('#other_attachment_count').val());
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
    <script>




        // ================================Case General Info save==================================

        $(document).ready(function() {
            $('#caseGeneralInfoFormForEdit').submit(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'আপনি কি মামলার তথ্য সংরক্ষণ করতে চান?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'হ্যাঁ',
                    cancelButtonText: 'না'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(this);
                        console.log(formData);

                        $.ajax({
                            url: "{{ route('cabinet.case.caseGeneralInfoForEdit') }}",
                            type: 'POST',
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                $('#caseGeneralInfoSaveBtn').removeClass(
                                    'spinner spinner-white spinner-right disabled');
                                $orderData = response;
                                Swal.fire(
                                    'Saved!',
                                    'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                                    'success'
                                )
                                console.log(response);

                                $("#sending_reply_tab").click();
                                $("#caseIDForAnswer").val(response.caseId);
                                $("#caseIDForSuspention").val(response.caseId);
                                $("#caseIDForFinalOrder").val(response.caseId);
                                $("#caseIDForContempt").val(response.caseId);

                                $('#sendingReplySaveBtn').prop('disabled', false);
                                $('#sendingReplySaveBtn').removeClass("disable-button");
                                $('#suspensionOrderSaveBtn').prop('disabled', false);
                                $('#suspensionOrderSaveBtn').removeClass(
                                    "disable-button");
                                $('#finalOrderSaveBtn').prop('disabled', false);
                                $('#finalOrderSaveBtn').removeClass("disable-button");
                                $('#contemptCaseSaveBtn').prop('disabled', false);
                                $('#contemptCaseSaveBtn').removeClass("disable-button");
                                console.log(response);

                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    }
                });
            });
        });





        // ============= Add Attachment Row ========= start =========
        $("#addMainFileRow").click(function(e) {
            addMainFileRowFunc();
        });
        //add row function
        function addMainFileRowFunc() {
            var count = parseInt($('#other_main_attachment_count').val());

            var formType = $('#formType').val();
            // alert(formType);
            $('#other_main_attachment_count').val(count + 1);
            var items = '';
            items += '<tr>';
            items += '<td><input type="text" name="file_type[]" id="customFileName' + count +
                '" class="form-control form-control-sm" placeholder="" ><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
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
            $('#mainFileDiv tr:last').after(items);
            console.log(items);

            // if (formType == 'edit') {
            //     $(`#customFile${count}`).attr('required', false);
            //     $(`#customFileName${count}`).attr('required', false);
            // }
        }









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
                confirmButtonText: 'Yes'
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
                } else {
                    $('#sendingReplySaveBtn').removeClass(
                        'spinner spinner-white spinner-right disabled');
                    Swal.fire(
                        'Canceled!',
                        'মামলার জবাব প্রেরনের তথ্য সংরক্ষণ বাতিল করা হয়েছে',
                        'info'
                    );
                }
            })

        });
        // ================================Sending Replay Save==================================//


        // ================================Sending Replay Save==================================//
        $('#adalatReplySubmitForm').submit(function(e) {
            e.preventDefault();
            $('#adalatReplySubmitSaveBtn').addClass('spinner spinner-white spinner-right disabled');

            Swal.fire({
                title: 'আপনি কি মামলার আদালতে জবাব দাখিলের তথ্য সংরক্ষণ করতে চান?',
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
                        url: "{{ route('cabinet.case.adalatReplySubmitStore') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: (data) => {
                            $('#adalatReplySubmitSaveBtn').removeClass(
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
                            $('#adalatReplySubmitSaveBtn').prop('disabled', false);
                            $('#adalatReplySubmitSaveBtn').removeClass("disable-button");
                            $('#suspensionOrderSaveBtn').prop('disabled', false);
                            $('#suspensionOrderSaveBtn').removeClass("disable-button");
                            $('#finalOrderSaveBtn').prop('disabled', false);
                            $('#finalOrderSaveBtn').removeClass("disable-button");
                            $('#contemptCaseSaveBtn').prop('disabled', false);
                            $('#contemptCaseSaveBtn').removeClass("disable-button");

                        },
                        error: function(data) {
                            console.log(data);
                            $('#adalatReplySubmitSaveBtn').removeClass(
                                'spinner spinner-white spinner-right disabled');

                        }
                    });
                } else {
                    $('#adalatReplySubmitSaveBtn').removeClass(
                        'spinner spinner-white spinner-right disabled');
                    Swal.fire(
                        'Canceled!',
                        'মামলার আদালতে জবাব দাখিল সংরক্ষণ বাতিল করা হয়েছে',
                        'info'
                    );
                }
            })

        });
        // ================================Sending Replay Save==================================//



        // ================================Suspention Order Save ======================//



        $('#suspensionOrderForm').submit(function(e) {
            // alert(1);
            e.preventDefault();
            $('#suspensionOrderSaveBtn').addClass('spinner spinner-white spinner-right disabled');

            Swal.fire({
                title: 'আপনি কি মামলার স্থগিতাদেশ অন্তর্বর্তীকালীন তথ্য সংরক্ষণ করতে চান?',
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
                } else {
                    $('#suspensionOrderSaveBtn').removeClass(
                        'spinner spinner-white spinner-right disabled');
                    Swal.fire(
                        'Canceled!',
                        'মামলার স্থগিতাদেশ অন্তর্বর্তীকালীন সংরক্ষণ বাতিল করা হয়েছে',
                        'info'
                    );
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
                title: 'আপনি কি মামলার চূড়ান্ত আদেশ তথ্য সংরক্ষণ করতে চান?',
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
                } else {
                    $('#finalOrderSaveBtn').removeClass(
                        'spinner spinner-white spinner-right disabled');
                    Swal.fire(
                        'Canceled!',
                        'মামলার চূড়ান্ত আদেশ সংরক্ষণ বাতিল করা হয়েছে',
                        'info'
                    );
                }
            })

        });
        // ================================Final Order Save==================================//

        // ================================Final Order Save==================================//



        $('#contemptCaseForm').submit(function(e) {
            e.preventDefault();
            $('#contemptCaseSaveBtn').addClass('spinner spinner-white spinner-right disabled');

            Swal.fire({
                title: 'আপনি কি কনটেম্প্ট মামলা তথ্য সংরক্ষণ করতে চান?',
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
                } else {
                    $('#contemptCaseSaveBtn').removeClass(
                        'spinner spinner-white spinner-right disabled');
                    Swal.fire(
                        'Canceled!',
                        'মামলার কনটেম্প্ট মামলা সংরক্ষণ বাতিল করা হয়েছে',
                        'info'
                    );
                }
            })

        });
        // ================================Final Order Save==================================//
    </script>
@endsection
