@extends('layouts.cabinet.cab_default')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<title>মূল বিদাদি হিসেবে অন্তর্ভুক্তির আবেদন ফর্ম </title>
<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
@include('gov_case.case_register.create_css')

<style>
    .card-body {
        margin-top: 5rem;
        margin-left: 20rem;
        font-family: 'Kalpurush', sans-serif;
    }

    @media screen and (max-width: 768px) {
        .card-body {
            margin-top: 1rem;
            margin-left: 0;
        }
    }
</style>

<link href="https://fonts.googleapis.com/css2?family=Kalpurush&display=swap" rel="stylesheet">
{{-- <link rel="icon" type="image/png" href="public/logo/bangladesh-govt-logo.png"> --}}
<div class="card">
    <div class="card-body">
        <h3 class="card-title h2 font-weight-bolder">মূল বিবাদী হিসেবে অন্তর্ভুক্তির আবেদন ফরম </h3>
        <div class="table-responsive ajax-data-container pt-3">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane active" id="case_general_information" role="tabpanel" aria-labelledby="home-tab">

                    <form method="POST" id="caseGeneralInfoForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="court" style="font-weight: bold; font-size: 1.5em;">আদালতের নাম<span
                                        class="text-danger">*</span></label>
                                <select name="court" id="court" class="form-control form-control-sm"
                                    required="required">
                                    <option value=""> -- নির্বাচন করুন --</option>

                                    @foreach ($GovCaseDivision as $value)
                                        <option value="{{ $value->id }}"
                                            {{ old('court') == $value->id ? 'selected' : '' }}>
                                            {{ $value->name_bn }} </option>
                                    @endforeach
                                </select>
                                <span class="text-danger d-none vallidation-message">This field can not be empty</span>
                            </div>

                            <div class="col-lg-4 mb-5">
                                <label style="font-weight: bold; font-size: 1.5em;">মামলার ক্যাটেগরি <span
                                        class="text-danger">*</span></label>

                                <div class="" id="CaseCategorDiv">
                                    <select name="case_category" id="CaseCategory" class="form-control form-control-sm"
                                        required="required">
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

                            <div class="col-lg-4 mb-3">
                                <label style="font-weight: bold; font-size: 1.5em;">মামলার শ্রেণী/কেস-টাইপ <span
                                        class="text-danger">*</span></label>
                                <div class="" id="CaseCategorDiv">
                                    <select name="case_category_type" id="case_category_type"
                                        class="form-control form-control-sm" required="required">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($GovCaseDivisionCategoryType as $categoryType)
                                            <option value="{{ $categoryType->id }}">{{ $categoryType->name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger d-none vallidation-message">This field can not be
                                        empty</span>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="case_no" style="font-weight: bold; font-size: 1.5em;">মামলা নং <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="case_no" id="case_no" class="form-control form-control-sm"
                                    placeholder="(type digits in English)" required="required"
                                    onkeypress="return allowBanglaAndEnglishNumerals(event)">
                                <input type="hidden" name="caseId" value="">
                                <span class="text-danger d-none vallidation-message">This field can not be empty</span>
                            </div>

                            <div class="col-lg-12 mt-5">
                                <label for="main_defendant_comments" style="font-weight: bold; font-size: 1.5em;">মূল
                                    বিবাদী হিসেবে অন্তর্ভুক্তির কারণ <span class="text-danger">*</span> </label>
                                <textarea name="main_defendant_comments" class="form-control" id="main_defendant_comments" rows="3"
                                    spellcheck="false"></textarea>
                            </div>

                            <div class="col-lg-12 mt-5">
                                <label for="main_defendant_pdf" style="font-weight: bold; font-size: 1.5em;">মূল বিবাদী
                                    হিসেবে অন্তর্ভুক্তির (PDF ফাইল) <span class="text-danger">*</span> <sub
                                        class="text-danger">(PDF, সর্বোচ্চ সাইজ :5MB)</sub> </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="main_defendant_pdf"
                                        name="main_defendant_pdf">
                                    <label class="custom-file-label" for="main_defendant_pdf">ফাইল বাছাই করুন...</label>
                                </div>
                            </div>


                        </div>
                        <div class="form-footer mt-5" style="display: flex;justify-content: center;">
                            <button type="submit" id="saveButton" class="btn btn-primary">সংরক্ষণ</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@include('gov_case.case_register.application_form_as_main_defendent.create_js_defendent')
