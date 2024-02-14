@extends('layouts.cabinet.cab_default')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>

@include('gov_case.case_register.create_css')

<style>
    .card {
        margin-bottom: 15rem;


    }

    .card-body {
        font-family: 'Kalpurush', sans-serif;

    }
</style>
@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="card-title h2 font-weight-bolder">মূল বিবাদী হিসেবে অন্তর্ভুক্তির আবেদন ফরম</h3>
            <div class="table-responsive ajax-data-container pt-3">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane active" id="case_general_information" role="tabpanel" aria-labelledby="home-tab">

                        <form action="{{ route('cabinet.case.editApplications', $applicationFormAsMainDefendent->id) }}"
                            method="POST" enctype="multipart/form-data" id="caseGeneralInfoForm">
                            @csrf

                            <div class="row">
                                <div class="form-group col-lg-4 mb-3">
                                    <label for="court" style="font-weight: bold; font-size: 1.5em;">আদালতের নাম <span
                                            class="text-danger">*</span></label>
                                    <select name="court" id="court" class="form-control">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($data['courts'] as $court)
                                            <option value="{{ $court->id }}"
                                                {{ $applicationFormAsMainDefendent->court == $court->id ? 'selected' : '' }}>
                                                {{ $court->court_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 mb-3">
                                    <label for="case_no" style="font-weight: bold; font-size: 1.5em;">মামলা নং <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="case_no" id="case_no" class="form-control"
                                        value="{{ $applicationFormAsMainDefendent->case_no }}">
                                </div>

                                <div class="form-group col-lg-4 mb-3">
                                    <label style="font-weight: bold; font-size: 1.5em;">মামলার ক্যাটেগরি <span
                                            class="text-danger">*</span></label>
                                    <select name="case_category" id="case_category" class="form-control">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($GovCaseDivisionCategory as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $applicationFormAsMainDefendent->case_category == $value->id ? 'selected' : '' }}>
                                                {{ $value->name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 mb-3">
                                    <label style="font-weight: bold; font-size: 1.5em;">মামলার শ্রেণী/কেস-টাইপ <span
                                            class="text-danger">*</span></label>
                                    <select name="case_category_type" id="case_category_type" class="form-control">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($GovCaseDivisionCategoryType as $categoryType)
                                            <option value="{{ $categoryType->id }}"
                                                {{ $applicationFormAsMainDefendent->case_category_type == $categoryType->id ? 'selected' : '' }}>
                                                {{ $categoryType->name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-4 mb-3">
                                    <label for="main_defendant_comments">মূল বিবাদী হিসেবে অন্তর্ভুক্তির কারণ</label>
                                    <textarea name="main_defendant_comments" id="main_defendant_comments" class="form-control">{{ $applicationFormAsMainDefendent->main_defendant_comments }}</textarea>
                                </div>

                                <div class="form-group col-lg-4 mb-3">
                                    <label for="additional_comments">বিষয়বস্তু (সংক্ষিপ্ত)</label>
                                    <textarea name="additional_comments" id="additional_comments" class="form-control">{{ $applicationFormAsMainDefendent->additional_comments }}</textarea>
                                </div>

                                {{-- <div class="form-group col-lg-4 mb-3">
                                    <label for="main_defendant_pdf">মূল বিবাদী হিসেবে অন্তর্ভুক্তির PDF</label>
                                    <input type="file" name="main_defendant_pdf" id="main_defendant_pdf"
                                        class="form-control-file">
                                </div> --}}
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
@endsection

@include('gov_case.case_register.application_form_as_main_defendent.create_js_defendent')
