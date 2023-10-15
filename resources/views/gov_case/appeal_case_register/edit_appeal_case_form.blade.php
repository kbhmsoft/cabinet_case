@extends('layouts.cabinet.cab_default')

@section('content')

    @php
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
                            <form id="appealCaseGeneralInfoForm" action="javascript:void(0)" class="form"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <!--begin::Card-->
                                        {{-- <div class="step" id=""> --}}
                                        <fieldset class="mb-8">
                                            <!-- <legend> মামলার সাধারণ তথ্য</legend> -->
                                            <div class="form-group row">

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলার ক্যাটেগরি <span class="text-danger">*</span></label>

                                                    <div class="" id="CaseCategorDiv">
                                                        <select name="case_category" id="CaseCategory"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            {{-- {{dd($GovCaseDivisionCategory)}} --}}
                                                            @foreach ($GovCaseDivisionCategory as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_category') == $value->id || $appealCaseData->case_category_id == $value->id ? 'selected' : '' }}>
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
                                                                    {{ old('case_category') == $value->id || $appealCaseData->case_type_id == $value->id ? 'selected' : '' }}>
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
                                                        value="{{ $appealCaseData->case_no ?? '' }}" readonly
                                                        required="required">
                                                    <input type="hidden" name="caseId"
                                                        value="{{ $appealCaseData->id ?? '' }}">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>



                                                <div class="col-lg-4 mb-5">
                                                    <label>বছর <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_year" id="case_year"
                                                        class="form-control form-control-sm common_yearpicker"
                                                        placeholder="বছর" autocomplete="off"
                                                        value="{{ $appealCaseData->year ?? '' }}" required="required">
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
                                                            @foreach ($ministrys as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('appeal_office') == $value->id || $appealCaseData->appeal_office_id == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->office_name_bn }} </option>
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
                                                        <select name="concern_new_appeal_person_designation"
                                                            id="concern_new_appeal_person_designation"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($concern_person_desig as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('concern_person_designation') == $value->id || $appealCaseData->concern_new_appeal_person_designation == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>



                                                <div class="col-lg-4 mb-5">
                                                    <label>সংশ্লিষ্ট আইন কর্মকর্তার নাম<span
                                                            class="text-danger">*</span></label>

                                                    <div class="" id="concernPersonNameDiv">
                                                        <select name="concern_user_id" id="concern_user_id"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($usersInfo as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('concern_user_id') == $value->id || $appealCaseData->concern_user_id == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>স্থগিতাদেশের তারিখ(প্রযোজ্য ক্ষেত্রে)<span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="postpond_date" id="postpond_date"
                                                        class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                        value="{{ $appealCaseData->postpond_date ?? '' }}">
                                                </div>


                                                <div class="col-md-4 mb-5">
                                                    <label>স্থগিতাদেশের বিবরণ</label>
                                                    <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3" spellcheck="false">
                                                        {{ $appealCaseData->postponed_details ?? '' }}

                                                    </textarea>
                                                </div>


                                                {{-- starting সংযুক্তি  --}}

                                                <div class="col-md-12">
                                                    <fieldset class="">
                                                        <div
                                                            class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                            <div class="d-flex align-items-center mr-2 py-2">
                                                                <h3 class="mb-0 mr-8">সংযুক্তি
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
                                                                @foreach ($appealAttachment as $key => $value)
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" name="file_type[]"
                                                                            id="customFileName"
                                                                            class="form-control form-control-sm"
                                                                            value="{{ old('file_type', $value->file_type) }}">
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                                <tr></tr>
                                                            </table>
                                                            <input type="hidden" id="other_attachment_count"
                                                                value="1">
                                                        </div>
                                                    </fieldset>
                                                </div>



                                                <div class="col-lg-6 mt-5 mb-5">
                                                    <label>ধরনর মামলা উদ্ভূত<span class="text-danger">*</span></label>

                                                    <div class="" id="CaseCategorOriginDiv">
                                                        <select name="case_category_origin" id="CaseCategory"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($GovCaseDivisionCategoryHighcourt as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_category_origin') == $value->id || $appealCaseData->case_category_origin == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->name_bn }} </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                    </div>
                                                </div>


                                                <div class="col-lg-6 mt-5 mb-5">
                                                    <label>মামলা নং(উদ্ভূত)<span class="text-danger">*</span></label>

                                                    <div class="" id="CaseCategorOriginDiv">
                                                        <select name="case_number_origin" id="case_number_origin"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            {{-- {{dd($appealCaseDataNumberOrigin)}} --}}
                                                            @foreach ($originCaseNumber as $value)
                                                                <option value="{{ $value->case_no }}"
                                                                    {{ old('case_number_origin') == $value->case_no || $appealCaseData->case_number_origin == $value->case_no ? 'selected' : '' }}>
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
                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <label>সিএমপি নং <span class="text-danger">*</span></label>
                                                    <input type="text" name="cmp_no" id="cmp_no"
                                                        class="form-control form-control-sm" placeholder="মামলা নং "
                                                        required="required"
                                                        value="{{ $govCaseRegister['case']->leave_to_appeal_no }}"
                                                        disabled>
                                                </div>

                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <label>লিভ টু আপীল নং <span class="text-danger">*</span></label>
                                                    <input type="text" name="leave_to_appeal_no"
                                                        id="leave_to_appeal_no" class="form-control form-control-sm"
                                                        placeholder="মামলা নং " required="required"
                                                        value="{{ $govCaseRegister['case']->leave_to_appeal_no }}"
                                                        disabled>

                                                </div>

                                                <div class="col-lg-12 mb-5">
                                                    <table class="table mb-5" width="100%" border="1"
                                                        id="" style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>রিট পিটিশনারের নাম <span class="text-danger">*</span> </th>
                                                            <th>ঠিকানা <span class="text-danger">*</span></th>
                                                        </tr>
                                                        <tbody>

                                                            @foreach ($govCaseRegister['caseBadi'] as $badi)
                                                                <tr>
                                                                    <td>{{ $badi->name }}</td>
                                                                    <td>{{ $badi->address }}</td>
                                                                </tr>
                                                            @endforeach
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

                                                            @foreach ($govCaseRegister['mainBibadi'] as $bibadi)
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
                                                            @foreach ($govCaseRegister['otherBibadi'] as $bibadi)
                                                                <tr>
                                                                    {{-- {{dd($bibadi->ministry->office_name_bn)}} --}}

                                                                    <td class="tg-nluh">
                                                                        {{ $bibadi->ministry->office_name_bn ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>



                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <label>বিষয়বস্তু(সংক্ষিপ্ত)<small class="text-danger">
                                                        </small> </label>
                                                    <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3" spellcheck="false"
                                                        disabled>{{ $govCaseRegister['case']->subject_matter }}</textarea>
                                                </div>


                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <label>এফিডেভিট দাখিলকারী রেসপন্ডেন্ট <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="cmp_no" id="cmp_no"
                                                        class="form-control form-control-sm" placeholder=""
                                                        required="required" disabled>
                                                    {{-- <input type="hidden" name="caseId" value=""> --}}
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
                                                <div class="col-lg-6 mb-5">
                                                    <label>রায় ঘোষণার তারিখ<span class="text-danger"></span></label>
                                                    <input type="text" name="result_date" id="result_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="দিন/মাস/বছর" autocomplete="off" disabled
                                                        value="{{ $govCaseRegister['case']->result_date }}">
                                                </div>


                                                <div class="col-md-6">
                                                    <label class="form-group font-weight-bolder font-size-h5">রায় ফলাফল
                                                    </label>
                                                    <div class="radio-inline">
                                                        <label class="radio">
                                                            <input type="radio" name="result" id="result"
                                                                value="1"
                                                                {{ $govCaseRegister['case']->in_favour_govt == 1 ? 'checked' : '' }}
                                                                disabled />
                                                            <span></span>সরকারের পক্ষে</label>
                                                        <label class="radio">
                                                            <input type="radio" name="result" id="result"
                                                                value="2"
                                                                {{ $govCaseRegister['case']->in_favour_govt == 2 ? 'checked' : '' }}
                                                                disabled />
                                                            <span></span>সরকারের বিপক্ষে</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <label class="form-group font-weight-bolder font-size-h5">মামলার রায়ের
                                                    সংক্ষিপ্ত বিবরণ</label>
                                                <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                    spellcheck="false" disabled> {{ $govCaseRegister['case']->result_short_dtails }} </textarea>
                                            </div>

                                            <div class="form-group row mt-5">
                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="result_copy_asking_date"
                                                        id="result_copy_asking_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="" autocomplete="off"
                                                        value="{{ $govCaseRegister['case']->result_copy_asking_date }}"
                                                        disabled>
                                                </div>

                                                <div class="col-lg-6 mb-5 mb-5">
                                                    <label>রায়ের নকল প্রাপ্তির তারিখ<span
                                                            class="text-danger"></span></label>
                                                    <input type="text" name="result_copy_reciving_date"
                                                        id="result_copy_reciving_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="" autocomplete="off"
                                                        value="{{ $govCaseRegister['case']->result_copy_reciving_date }}"
                                                        disabled>
                                                </div>
                                            </div>

                                        </fieldset>


                                    </div>
                                </div>
                                <div class="form-footer mt-5" style="display: flex;justify-content: center;">
                                    <button type="submit" id="appealCaseGeneralInfoSaveBtn"
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
                                        <!--begin::Card-->
                                        {{-- <div class="step"> --}}
                                        <input type="hidden" id="caseIDForFinalOrder" name="case_id"
                                            value="{{ $appealCaseData->id }}">
                                            {{-- {{dd($appealCaseData->is_final_order)}} --}}
                                        <fieldset class="mb-8">
                                            {{-- <legend> মামলার ফলাফল</legend> --}}
                                            <div class="form-group row">
                                                <div class="col-md-12 mb-5">
                                                    <input type="checkbox" id="is_final_order" name="is_final_order"
                                                        value="1" onclick="showAlert()"
                                                        {{ $appealCaseData->is_final_order == '1' ? 'checked' : '' }}>
                                                    <label for="is_final_order"> মামলার রায়/চুড়ান্ত আদেশ
                                                        হয়ে থাকলে সিলেক্ট করুন</label><br>
                                                </div>
                                            </div>
                                            @if ($appealCaseData->is_final_order == '1')
                                                <div id="">
                                                    <div class="form-group row">
                                                        <div class="col-md-6 mb-5">
                                                            <label class="form-group font-weight-bolder font-size-h5">ফলাফল
                                                            </label>
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio" name="result" id="result"
                                                                        value="1"
                                                                        {{ $appealCaseData->result == '1' ? 'checked' : '' }} />
                                                                    <span></span>সরকারের পক্ষে</label>
                                                                <label class="radio">
                                                                    <input type="radio" name="result" id="result"
                                                                        value="2"
                                                                        {{ $appealCaseData->result == 2 ? 'checked' : '' }} />
                                                                    <span></span> সরকারের বিপক্ষে</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
                                                            <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                                spellcheck="false">{{ $appealCaseData->result_short_dtails ?? '' }}</textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-group font-weight-bolder font-size-h5">সরকারের
                                                                বিপক্ষে হলে আপিল করা হয়েছে কিনা </label>
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio" name="is_appeal" id="is_appeal"
                                                                        value="1"{{ $appealCaseData->is_appeal == '1' ? 'checked' : '' }} />
                                                                    <span></span>হ্যাঁ </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="is_appeal" id="is_appeal"
                                                                        value="2"
                                                                        {{ $appealCaseData->is_appeal == '2' ? 'checked' : '' }} />
                                                                    <span></span>না</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <label>রায় ঘোষণার তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $appealCaseData->result_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_copy_asking_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $appealCaseData->result_copy_asking_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>রায়ের নকল প্রাপ্তির তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_copy_reciving_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $appealCaseData->result_copy_reciving_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="appeal_requesting_memorial"
                                                                id="appeal_requesting_memorial"
                                                                class="form-control form-control-sm"autocomplete="off"
                                                                value="{{ $appealCaseData->appeal_requesting_memorial ?? '' }}">
                                                        </div>

                                                        <div class="col-lg-4 mb-5">
                                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="appeal_requesting_date"
                                                                id="appeal_requesting_date"
                                                                class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                                value="{{ $appealCaseData->appeal_requesting_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span
                                                                    class="text-danger"></span></label>
                                                            <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3"
                                                                spellcheck="false">{{ $appealCaseData->reason_of_not_appealing ?? '' }}
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
                                                                        {{ $appealCaseData->result == '1' ? 'checked' : '' }} />
                                                                    <span></span>সরকারের পক্ষে</label>
                                                                <label class="radio">
                                                                    <input type="radio" name="result" id="result"
                                                                        value="2"
                                                                        {{ $appealCaseData->result == '2' ? 'checked' : '' }} />
                                                                    <span></span> সরকারের বিপক্ষে</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
                                                            <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                                spellcheck="false">{{ $appealCaseData->result_short_dtails ?? '' }}</textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label
                                                                class="form-group font-weight-bolder font-size-h5">সরকারের
                                                                বিপক্ষে হলে আপিল করা হয়েছে কিনা </label>
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio" name="is_appeal" id="is_appeal"
                                                                        value="1"{{ $appealCaseData->is_appeal == '1' ? 'checked' : '' }} />
                                                                    <span></span>হ্যাঁ </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="is_appeal" id="is_appeal"
                                                                        value="2"
                                                                        {{ $appealCaseData->is_appeal == '2' ? 'checked' : '' }} />
                                                                    <span></span>না</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <label>রায় ঘোষণার তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $appealCaseData->result_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_copy_asking_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $appealCaseData->result_copy_asking_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>রায়ের নকল প্রাপ্তির তারিখ<span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="result_copy_reciving_date"
                                                                class="form-control form-control-sm  common_datepicker"
                                                                placeholder="দিন/মাস/বছর" autocomplete="off"
                                                                value="{{ $appealCaseData->result_copy_reciving_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="appeal_requesting_memorial"
                                                                id="appeal_requesting_memorial"
                                                                class="form-control form-control-sm"autocomplete="off"
                                                                value="{{ $appealCaseData->appeal_requesting_memorial ?? '' }}">
                                                        </div>

                                                        <div class="col-lg-4 mb-5">
                                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span
                                                                    class="text-danger"></span></label>
                                                            <input type="text" name="appeal_requesting_date"
                                                                id="appeal_requesting_date"
                                                                class="form-control form-control-sm  common_datepicker"autocomplete="off"
                                                                value="{{ $appealCaseData->appeal_requesting_date ?? '' }}">
                                                        </div>
                                                        <div class="col-lg-4 mb-5">
                                                            <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span
                                                                    class="text-danger"></span></label>
                                                            <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3"
                                                                spellcheck="false">{{ $appealCaseData->reason_of_not_appealing ?? '' }}
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

    @include('gov_case.appeal_case_register.create_new_appeal_js')
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
@endsection
