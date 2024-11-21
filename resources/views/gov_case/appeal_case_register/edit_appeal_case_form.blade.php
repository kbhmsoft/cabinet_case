@extends('layouts.cabinet.cab_default')

@section('content')

    @php
        $pass_year_data = '<option value="">-- নির্বাচন করুন --</option>';
        for ($i = 1995; $i <= date('Y'); $i++) {
            $pass_year_data .= '<option value="' . $i . '">' . $i . '</option>';
        }

    @endphp

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

            <div id="tab_header_tabs tab-design" class="trainee_details_card_header course_details_new_tabs">
                <ul class="nav details_trainee_tab nav-tabs myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="trainee_tab_item" data-toggle="tab" href="#case_general_information"
                            role="tab" aria-controls="home" aria-selected="true">মামলার সাধারণ তথ্য</a>
                    </li>
                    <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link" id="final_order_tab" href="#final_order" data-toggle="tab" role="tab"
                            aria-controls="contact" aria-selected="false">চূড়ান্ত আদেশ/<br>রায় সম্পর্কিত</a>
                    </li>
            </div>


            <div class="card-body">
                <div class="table-responsive ajax-data-container pt-3">
                    <div class="tab-content" id="myTabContent">
                        {{-- start মামলার সাধারণ তথ্য --}}

                        <div class="tab-pane active" id="case_general_information" role="tabpanel"
                            aria-labelledby="home-tab">
                            <form id="appealCaseGeneralInfoForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">

                                        <fieldset class="mb-8">

                                            <div class="form-group row">
                                                <input type="hidden" id="" name="case_id"
                                                    value="{{ $case->id }}">
                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলার ক্যাটেগরি <span class="text-danger">*</span></label>

                                                    <div class="" id="CaseCategorDiv">
                                                        <select name="case_category" id="CaseCategory"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            {{-- {{dd($GovCaseDivisionCategory)}} --}}
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
                                                        value="{{ $case->case_no ?? '' }}" readonly required="required">
                                                    <input type="hidden" name="caseId" value="{{ $case->id ?? '' }}">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>



                                                <div class="col-lg-4 mb-5">
                                                    <label>বছর <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_year" id="case_year"
                                                        class="form-control form-control-sm common_yearpicker"
                                                        placeholder="বছর" autocomplete="off"
                                                        value="{{ $case->year ?? '' }}" required="required">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>



                                                <div class="col-lg-4 mb-5">
                                                    <table width="100%" border="1" id="AppealAdalatDiv" class="mb-5"
                                                        style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>আদালতের নাম (Justice Name) <span
                                                                    class="text-danger">*</span></th>
                                                            <th width="30">
                                                                <a href="javascript:void(0);" id="AppealAdalatRow"
                                                                    class="btn btn-sm btn-primary pr-2">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                </a>
                                                            </th>
                                                        </tr>
                                                        <tr></tr>

                                                        @foreach ($caseCourts as $key => $row)
                                                            <tr id="adalat_{{ $row->id }}">
                                                                <td>
                                                                    <select name="appeal_adalat[]"
                                                                        id="ministry_id_{{ $key }}"
                                                                        class="form-control form-control-sm">
                                                                        @foreach ($appealCourtAdalat as $value)
                                                                            <option value="{{ $value->id }}"
                                                                                {{ old('appeal_adalat') == $value->id || $row->appeal_adalat == $value->id ? 'selected' : '' }}>
                                                                                {{ $value->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input type="hidden" name="appeal_adalat_id[]"
                                                                        value="{{ $row->id }}">
                                                                </td>
                                                                <td>
                                                                    @if ($key > 0)
                                                                        <a href="javascript:void(0);"
                                                                            class="btn btn-sm btn-danger font-weight-bolder pr-2"
                                                                            data-id="{{ $row->id }}"
                                                                            id="deleteAdalatBtn_{{ $row->id }}"
                                                                            onclick="deleteAdalat({{ $row->id }})">
                                                                            <i class="fas fa-minus-circle"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>




                                                <div class="col-lg-4 mb-5">
                                                    <label>আপিলকারী <span class="text-danger">*</span></label>
                                                    <div id="appeallateOffice">
                                                        <select name="appeal_office" id="appeallateOffice"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($ministrys as $value)
                                                                <option value="{{ $value->doptor_office_id }}"
                                                                    {{ old('appeal_office') == $value->doptor_office_id || $case->appeal_office_id == $value->doptor_office_id ? 'selected' : '' }}>
                                                                    {{ $value->office_name_bn }}
                                                                </option>
                                                            @endforeach
                                                            <option value="0">অন্যান্য</option>
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field can
                                                            not be empty</span>

                                                        <input type="text" name="appeal_petitioner_name"
                                                            id="appeal_petitioner_name"
                                                            class="form-control form-control-sm d-none"
                                                            placeholder="আপিলকারীর নাম লিখুন">
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলা দায়েরের তারিখ<span class="text-danger"></span></label>
                                                    <input type="text" name="case_entry_date" id="case_entry_date"
                                                        class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                        value="{{ $case->case_entry_date ?? '' }}">
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
                                                            <tr id="advocate_{{ $value->id }}">
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
                                                                            id="deleteAdvocateBtn_{{ $value->id }}"
                                                                            onclick="deleteAdvocate({{ $value->id }})">
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


                                                <div class="col-md-12 mb-5">
                                                    <label>স্থগিতাদেশের বিবরণ</label>
                                                    <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3" spellcheck="false">
                                                        {{ $case->postponed_details ?? '' }}

                                                    </textarea>
                                                </div>


                                                {{-- starting সংযুক্তি  --}}
                                                <div class="col-md-12">
                                                    <fieldset class="">
                                                        <div
                                                            class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                            <div class="d-flex align-items-center mr-2 py-2">
                                                                <h3 class="mb-0 mr-8">সংযুক্তি (রুল কপি সংযুক্ত করুন)

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
                                                                <tr>
                                                                    @foreach ($appealAttachment as $row)
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
                                                            <input type="hidden" id="other_attachment_count"
                                                                value="1">
                                                        </div>
                                                    </fieldset>
                                                </div>



                                                <div class="col-lg-6 mt-5 mb-5">
                                                    <label>ধরনর মামলা উদ্ভূত</label>

                                                    <div class="" id="CaseCategorOriginDiv">
                                                        <select name="case_category_origin" id="CaseCategory"
                                                            class="form-control form-control-sm">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($GovCaseDivisionCategoryHighcourt as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_category_origin') == $value->id || $case->case_category_origin == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name_bn }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>


                                                <div class="col-lg-6 mt-5 mb-5">
                                                    <label>মামলা নং(উদ্ভূত)</label>

                                                    <div class="" id="CaseCategorOriginDiv">
                                                        <select name="case_number_origin" id="case_number_origin"
                                                            class="form-control form-control-sm">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            {{-- {{dd($caseNumberOrigin)}} --}}
                                                            @foreach ($originCaseNumber as $value)
                                                                <option value="{{ $value->case_no }}"
                                                                    {{ old('case_number_origin') == $value->case_no || $case->case_number_origin == $value->case_no ? 'selected' : '' }}>
                                                                    {{ $value->case_no }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>

                                        </fieldset>
                                        {{-- </div> --}}

                                        <!--end::Card-->
                                    </div>
                                    <div class="col-md-12" id="showHighCourtCaseDiv">
                                        <fieldset>
                                            <div class="form-group row">

                                                <div class="col-lg-12 mb-5">
                                                    <table class="table mb-5" width="100%" border="1"
                                                        id="" style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>রিট পিটিশনারের নাম <span class="text-danger">*</span> </th>
                                                            <th>ঠিকানা <span class="text-danger">*</span></th>
                                                        </tr>
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ $caseBadi->name ?? '-' }}</td>
                                                                <td>{{ $caseBadi->address ?? '-' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <table width="100%" border="1" id=""
                                                        class="table mb-5" style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>মূল রেসপন্ডেন্ট নাম <span class="text-danger">*</span>
                                                            </th>

                                                        </tr>
                                                        <tbody>
                                                            @foreach ($mainBibadi as $bibadi)
                                                                <tr>
                                                                    <td class="tg-nluh">
                                                                        {{ $bibadi->ministry->office_name_bn ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <table width="100%" border="1" id=""
                                                        class="table mb-5" style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>অন্যান্য রেসপন্ডেন্ট নাম <span class="text-danger">*</span>
                                                        </tr>
                                                        <tbody>
                                                            @foreach ($otherBibadi as $bibadi)
                                                                <tr>
                                                                    <td class="tg-nluh">
                                                                        {{ $bibadi->ministry->office_name_bn ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <label>এফিডেভিট দাখিলকারী রেসপন্ডেন্ট <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="cmp_no" id="cmp_no"
                                                        class="form-control form-control-sm" placeholder=""
                                                        required="required" disabled>
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>


                                                <div class="col-lg-6 mb-5">
                                                    <label>সংশ্লিষ্ট আইন কর্মকর্তা <br> (ধরনর মামলা উদ্ভূত)<span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="concern_person_designation"
                                                        id="concern_person_designation"
                                                        class="form-control form-control-sm" placeholder=""
                                                        autocomplete="off" disabled
                                                        value="{{ $concernpersondesig->name ?? '' }}">
                                                </div>

                                                <div class="col-lg-6 mb-5">
                                                    <label>সংশ্লিষ্ট আইন কর্মকর্তার নাম<br> (ধরনর মামলা উদ্ভূত)<span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="appeal_concern_user_id"
                                                        id="appeal_concern_user_id" class="form-control form-control-sm"
                                                        placeholder="" autocomplete="off" disabled
                                                        value="{{ $concernPersonName->name ?? '' }}">
                                                </div>


                                            </div>

                                            <div class="form-group row mt-5">

                                            </div>


                                            <div class="col-md-12">

                                            </div>

                                            <div class="form-group row mt-5">

                                            </div>

                                        </fieldset>


                                    </div>
                                </div>
                                <div class="form-footer mt-5" style="display: flex;justify-content: center;">
                                    <button type="submit" id="appealCaseGeneralInfoEditSaveBtn"
                                        class="submit-button">সংরক্ষণ</button>
                                </div>
                            </form>
                        </div>

                        {{-- ---------- end মামলার সাধারণ তথ্য----------- --}}

                        <div class="tab-pane" id="final_order" role="tabpanel" aria-labelledby="home-tab">
                            <form id="finalOrderForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">

                                        <input type="hidden" id="caseIDForFinalOrder" name="case_id"
                                            value="{{ $case->id }}">

                                        <fieldset class="mb-8">

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

<script>
    /////// For Adalat Delete ----------///////////////////

    function deleteAdalat(id) {
        Swal.fire({
            title: 'আপনি কি মামলার আদালতটি মুছে ফেলতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'হ্যাঁ',
            cancelButtonText: 'না'
        }).then((result) => {
            if (result.isConfirmed) {
                var deleteButton = $('#deleteAdalatBtn_' + id);
                deleteButton.addClass('loadersmall');
                $.ajax({
                    url: '{{ url('/') }}/cabinet/case/appeal/adalat/delete/' + id,
                    type: "post",
                    dataType: "json",
                    success: function(data) {
                        Swal.fire(
                            'সফল!',
                            data.message,
                            'success'
                        ).then(() => {
                            $('#adalat_' + id).remove();
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        Swal.fire(
                            'ব্যর্থ!',
                            'কিছু ভুল হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।',
                            'error'
                        );
                    },
                    complete: function() {
                        // Remove the loader class after the request completes
                        deleteButton.removeClass('loadersmall');
                    }
                });
            }
        });
    }


    /////// For Advocate Delete ----------///////////////////

    function deleteAdvocate(id) {
        Swal.fire({
            title: 'আপনি কি মামলার সংশ্লিষ্ট আইন কর্মকর্তাটি মুছে ফেলতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'হ্যাঁ',
            cancelButtonText: 'না'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#deleteAdvocateBtn_' + id).addClass('loadersmall');
                $.ajax({
                    url: '{{ url('/') }}/cabinet/case/appeal/advocate/delete/' + id,
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        Swal.fire(
                            'সফল!',
                            data.message,
                            'success'
                        ).then(() => {
                            // Remove the corresponding row
                            $('#advocate_' + id).remove();
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                    }
                });
            }
        });
    }
</script>


@section('scripts')
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


    <script>
        /************************ Add multiple advocate  *************************/
        $("#addAdvocateLawer").click(function(e) {
            addAdvocateLawerFunc();
        });

        //add row function
        function addAdvocateLawerFunc() {
            var count = parseInt($('#survey_count').val());
            $('#survey_count').val(count + 1);
            var items = '';
            items += '<tr>';

            items += '<input type="hidden" name="concern_person_id[]" value="">';
            items +=
                '<td><select name="concernPersonDesignation[]" id="concernPersonDesignation_' + count +
                '" class="form-control form-control-sm concern_select2" onchange="getConcernPerName(' + count +
                ')" required="required"><?php echo $concernPersonDesig; ?></select> </td>';
            items +=
                '<td><select name="concern_user_id[]" id="concern_user_id_' + count +
                '" class="form-control form-control-sm concern_select2" required="required"><option value="">-- নির্বাচন করুন --</option></select></td>';

            if (count > 0) {
                items +=
                    '<td><a href="javascript:void(0);" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeAdvocateLawerRow(this)"> <i class="fas fa-trash"></i> </a> </td>';
            }
            items += '</tr>';
            // return items;

            $('#advocateLawerDiv tr:last').after(items);

            $('.concern_select2').select2();
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
        /************************ //Add multiple HighCourt Adalat *************************/
        $("#AppealAdalatRow").click(function(e) {
            AppealAdalatRowFunc();
        });

        //add row function
        function AppealAdalatRowFunc() {
            var mk = $('#AppealAdalatDiv tr').length;


            $('#AppealAdalatDiv tr:last').after(Item(mk + 1, 'other'));

            function Item(count, type = null) {
                var items = '';
                items += '<tr id="appeal_adalat_' + (count) + '">';
                items +=
                    '<td><select name="appeal_adalat[]" class="form-control form-control-sm other_respondentCls"><option value="">-- নির্বাচন করুন --</option>@foreach ($appealCourtAdalat as $value)<option value="{{ $value->id }}" > {{ $value->name }} </option>@endforeach</select></td>';
                items += '<input type="hidden" name="appeal_adalat_id[]" value="">';

                if (type == 'other') {
                    items +=
                        '<td><a href="javascript:void(0);" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeHighcourtAdalatRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
                }
                items += '</tr>';
                return items;
            }
            $('.other_respondentCls').select2();
        }

        //remove row function
        function removeHighcourtAdalatRow(id) {
            $(id).closest("tr").remove();
        }
    </script>

    <script>
        $('select').select2();
        jQuery('select[name="appeal_office"]').on('change', function() {
            var dataID = jQuery(this).val();
            console.log(dataID);
            if (dataID == 0) {
                $('#appeal_petitioner_name').removeClass('d-none');
            } else {
                $('#appeal_petitioner_name').addClass('d-none');
            }
        });
    </script>

    <script>
        // ============= Add Attachment Row ========= start =========
        $("#addFileRow").click(function(e) {
            addFileRowFunc();
        });
        //add row function
        function addFileRowFunc() {
            var count = parseInt($('#other_attachment_count').val());

            var formType = $('#formType').val();
            // alert(formType);
            $('#other_attachment_count').val(count + 1);
            var items = '';
            items += '<tr>';
            items += '<td><input type="text" name="file_type[]" id="customFileName' + count +
                '" class="form-control form-control-sm" placeholder="" ></td>';
            items +=
                '<td><div class="custom-file"><input type="file" accept="application/pdf" name="file_name[]" onChange="attachmentTitle(' +
                count + ',this)" class="custom-file-input" id="customFile' + count + '" /><label id="file_error' +
                count +
                '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-input' +
                count + '" for="customFile' + count +
                '">ফাইল নির্বাচন করুন</label></div></td>';
            items +=
                '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            items += '</tr>';
            $('#fileDiv tr:last').after(items);
            console.log(items);
        }
    </script>

    <script>
        $('#appealCaseGeneralInfoForm').submit(function(e) {
            e.preventDefault();

            $('#appealCaseGeneralInfoEditSaveBtn').addClass('spinner spinner-white spinner-right disabled');
            Swal.fire({
                title: 'আপনি কি মামলার সাধারন তথ্য সংরক্ষণ করতে চান?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(this);
                    console.log([...formData.entries()]);

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('cabinet.case.appealEditStore') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            console.log('Success response:', data);

                            $('#appealCaseGeneralInfoEditSaveBtn').removeClass(
                                'spinner spinner-white spinner-right disabled');
                            Swal.fire('Saved!', 'মামলার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                                'success');

                            $("#final_order").click();
                            $("#caseIDForFinalOrder").val(data.caseId);
                            $('#finalOrderSaveBtn').prop('disabled', false);
                            $('#finalOrderSaveBtn').removeClass("disable-button");
                        },
                        error: (xhr, status, error) => {
                            console.log('Error response:', xhr, status, error);

                            $('#appealCaseGeneralInfoEditSaveBtn').removeClass(
                                'spinner spinner-white spinner-right disabled');
                            if (xhr.status === 422) {
                                Swal.fire('সমস্যা...!', xhr.responseJSON.error, 'error');
                            } else {
                                Swal.fire('সমস্যা...!', 'অনুগ্রহ করে সকল ফিল্ড গুলো পূরণ করুন',
                                    'error');
                            }
                        }
                    });
                } else {
                    $('#appealCaseGeneralInfoSaveBtn').removeClass(
                        'spinner spinner-white spinner-right disabled');
                    Swal.fire('Canceled!', 'মামলার সাধারণ তথ্য সংরক্ষণ বাতিল করা হয়েছে', 'info');
                }
            });
        });
    </script>


    {{-- @include('gov_case.appeal_case_register.create_new_appeal_js') --}}
    <script type="text/javascript">
        $(document).ready(function() {
            addBadiRowFunc();
            addBibadiRowFunc();
        });
    </script>



    <script type="text/javascript">
        $(document).ready(function() {
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
                        url: '{{ url('/') }}/cabinet/case/appeal/ruleFile/delete/' +
                            id,
                        type: "post",
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
    </script>
@endsection
