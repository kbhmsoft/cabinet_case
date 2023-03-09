@extends('layouts.cabinet.cab_default')

@section('content')

    @php
    $pass_year_data = '<option value="">-- নির্বাচন করুন --</option>';
    for ($i = 1995; $i <= date('Y'); $i++) {
        $pass_year_data .= '<option value="' . $i . '">' . $i . '</option>';
    }
    @endphp


    <style type="text/css">
        #badiDiv td {
            padding: 5px;
            border-color: #ccc;
        }

        #badiDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }

        #bibadiDiv td {
            padding: 5px;
            border-color: #ccc;
        }

        #bibadiDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }

        #MainBibadiDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }

        #MainBibadiDiv td {
            padding: 5px;
            border-color: #ccc;
        }


        #surveyDiv td {
            padding: 5px;
            border-color: #ccc;
        }

        #surveyDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }

    </style>
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
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

                <!--begin::Form-->
                <form action="{{ route('cabinet.case.store') }}" class="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if (request('red') != null)
                            <div class="">
                                <div class="alert alert-custom alert-secondary alert-shadow fade show gutter-b" role="alert">
                                    <div class="alert-icon">
                                        <span class="svg-icon svg-icon-primary svg-icon-xl">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                                                    <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                    <div class="alert-text h4">
                                        {{-- <a target="_blank" href="{{ route('cabinet.case.edit', $case->id) }}"> --}}
                                        <a href="{{ request('red') }}">
                                        <u>পূর্বে ফিরে যান</u>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <fieldset class="mb-8">
                            <legend> মামলার সাধারণ তথ্য</legend>
                            <div class="form-group row">

                                <div class="col-lg-4 mb-5">
                                    <label>মামলার ধরন <span class="text-danger">*</span></label>
                                    <select name="case_type" id="case_type" class="form-control form-control-sm">
                                        <option value=""{{$case->case_type == ''  ? 'selected' : ''}} >-- নির্বাচন করুন --</option>
                                        <option value="1"{{$case->case_type == 1  ? 'selected' : ''}} >নতুন মামলা</option>
                                        <option value="2"{{$case->case_type == 2  ? 'selected' : ''}} >চলমান মামলা</option>
                                        <option value="3"{{$case->case_type == 3  ? 'selected' : ''}} >নিষ্পত্তি মামলা</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>মামলা নং <span class="text-danger">*</span></label>
                                    <input disabled type="text" name="case_no" id="case_no" class="form-control form-control-sm"
                                        placeholder="মামলা নং " value="{{ $case->case_no ?? '' }}">
                                    <input type="hidden" name="caseId" value="{{ $case->id ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>আদালতের নাম <span class="text-danger">*</span></label>
                                    <select name="court" id="court" class="form-control form-control-sm">
                                        <option value=""> -- নির্বাচন করুন --</option>
                                        @foreach ($courts as $value)
                                            <option value="{{ $value->id }}"
                                                {{ old('court') == $value->id || $case->court_id == $value->id ? 'selected' : '' }}>
                                                {{ $value->court_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($case->court_id == 1)
                                    <div class="col-lg-4 mb-5" style="display:block;" id="appeal_hide_show">
                                        <label>মামলা নির্বাচন করুন <span class="text-danger">*</span></label>
                                        <select name="appeal_case_id" id="appeal_case_id" class="form-control form-control-sm">
                                            <option value=""> -- নির্বাচন করুন --</option>
                                            @foreach ($appealCase as $value)
                                                <option value="{{ $value->id }}"
                                                    {{ old('appeal_case_id') == $value->id || $case->gov_case_ref_id == $value->id  ? 'selected' : '' }}>
                                                    {{ $value->case_no }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="col-lg-4 mb-5">
                                    <label>মামলার ক্যাটেগরি <span class="text-danger">*</span></label>
                                    <div class="" id="CaseCategorDiv">
                                        <select name="case_category" id="CaseCategory" class="form-control form-control-sm">
                                            <option value="">-- নির্বাচন করুন --</option>
                                            @foreach ($GovCaseDivisionCategory as $value)
                                                <option value="{{ $value->id }}" {{ old('case_category') == $value->id || $case->case_category_id == $value->id   ? 'selected' : '' }}> {{ $value->name_bn }} </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>বছর <span class="text-danger">*</span></label>
                                    <input type="text" name="case_year" id="case_year"
                                        class="form-control form-control-sm common_yearpicker" placeholder="বছর"
                                        autocomplete="off" value="{{ $case->year ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>মামলা রুল ইস্যুর তারিখ <span class="text-danger">*</span></label>
                                    <input type="text" name="case_date" id="case_date"
                                        class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                        autocomplete="off"
                                        value="{{ $case->date_issuing_rule_nishi ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>মামলার বিভাগ <span class="text-danger">*</span></label>
                                    <select onchange="caseCategoryGet(this, 'CaseCategory', 'CaseCategorDiv')"
                                        name="case_department" id="case_department" class="form-control form-control-sm">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($GovCaseDivision as $value)
                                            <option value="{{ $value->id }}"
                                                {{ old('case_department') == $value->id || $case->case_division_id == $value->id ? 'selected' : '' }}>
                                                {{ $value->name_bn }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>মামলার ক্যাটেগরি <span class="text-danger">*</span></label>
                                    <!-- <input type="text" name="case_type" id="case_type" class="form-control form-control-sm" placeholder="মামলার ধরণ" autocomplete="off"> -->
                                    <div class="" id="CaseCategorDiv">
                                        <select name="case_category" id="CaseCategory" class="form-control form-control-sm">
                                            <option value="">-- নির্বাচন করুন --</option>
                                            <option value="{{ $case->case_category_id }}" selected>
                                                {{ $case->div_category->name_bn ?? '' }} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-5">
                                <label>দফাওয়ারি জবাব প্রেরণের তারিখ </label>
                                <input type="text" name="result_sending_date" id="result_sending_date"
                                    class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর" autocomplete="off" value="{{ $case->appeal_requesting_memorial ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>সংশ্লিষ্ট আদালতে জবাব দাখিলের তারিখ </label>
                                    <input type="text" name="reply_submission_date" id="reply_submission_date"
                                        class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর" autocomplete="off" value="{{ $case->appeal_requesting_memorial ?? '' }}">
                                </div>
                                <div class="col-lg-4">
                                    <label>দায়িত্ব প্রাপ্ত কর্মকর্তা <span class="text-danger">*</span></label>
                                    <select name="concern_person" id="concern_person" class="form-control form-control-sm">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($concern_person as $value)
                                            <option value="{{ $value->id }}"
                                                {{ old('concern_person') == $value->id || $case->concern_user_id == $value->id ? 'selected' : '' }}>
                                                {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                @if($case->court_id != 1)
                                    <div class="form-group row" id="highCourt_hide_show">
                                        <div class="col-lg-4 mb-5" >
                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span class="text-danger"></span></label>
                                            <input type="text" name="appeal_requesting_memorial" id="appeal_requesting_memorial" class="form-control form-control-sm"autocomplete="off" value="{{ $case->appeal_requesting_memorial ?? '' }}">
                                        </div>
                                        
                                        <div class="col-lg-4 mb-5" >
                                            <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span class="text-danger"></span></label>
                                            <input type="text" name="appeal_requesting_date" id="appeal_requesting_date" class="form-control form-control-sm  common_datepicker"autocomplete="off"value="{{ $case->appeal_requesting_date ?? '' }}">
                                        </div>
                                        
                                        <div class="col-lg-4 mb-5" >
                                            <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span class="text-danger"></span></label>
                                            <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3" spellcheck="false">{{ $case->reason_of_not_appealing ?? '' }}
                                            </textarea>
                                            
                                        </div>
                                        
                                        <div class="col-lg-4 mb-5" >
                                            <label>প্রযোজ্য ক্ষেত্রে কন্টেম্পট মামলা নম্বর <span class="text-danger"></span></label>
                                            <input type="text" name="contempt_case_no" id="contempt_case_no" class="form-control form-control-sm"autocomplete="off"value="{{ $case->contempt_case_no ?? '' }}">
                                        </div>
                                        
                                        <div class="col-lg-4 mb-5" >
                                            <label> কন্টেম্পট মামলা রুল ইস্যুর তারিখ <span class="text-danger"></span></label>
                                            <input type="text" name="contempt_case_isuue_date" id="contempt_case_isuue_date" class="form-control form-control-sm  common_datepicker"autocomplete="off"value="{{ $case->contempt_case_isuue_date ?? '' }}">
                                        </div>
                                        
                                        <div class="col-lg-4 mb-5" >
                                            <label>কন্টেম্পট মামলার জবাব প্রেরণের তারিখ <span class="text-danger"></span></label>
                                            <input type="text" name="contempt_case_answer_sending_date" id="contempt_case_answer_sending_date" class="form-control form-control-sm  common_datepicker"autocomplete="off"value="{{ $case->contempt_case_answer_sending_date ?? '' }}">
                                        </div>
                                        
                                        <div class="col-lg-4 mb-5" >
                                            <label>কন্টেম্পট মামলার আদেশ <span class="text-danger"></span></label>
                                            <textarea name="contempt_case_order" class="form-control" id="contempt_case_order" rows="3" spellcheck="false">{{ $case->contempt_case_order ?? '' }}
                                            </textarea>
                                        </div>
                                        
                                        
                                    </div>
                                @endif
                        </fieldset>

                        <div class="form-group row">
                            <div class="col-lg-12 mb-5">
                                <fieldset>
                                    <legend>পিটিশনারের বিবরণ</legend>
                                    <table width="100%" border="1" id="badiDiv" style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th>পিটিশনারের নাম <span class="text-danger">*</span></th>
                                            <th>পিতা/স্বামীর নাম</th>
                                            <th>ঠিকানা</th>
                                            <th width="50">
                                                <a href="javascript:void();" id="addBadiRow"
                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        @foreach ($caseBadi as $value)
                                            <tr>
                                                <td>
                                                    <input type="text" name="badi_name[]"
                                                        class="form-control form-control-sm" value="{{ $value->name }}"
                                                        placeholder="">
                                                </td>
                                                <td>
                                                    <input type="text" name="badi_spouse_name[]"
                                                        class="form-control form-control-sm"
                                                        value="{{ $value->spouse_name }}" placeholder="">
                                                </td>
                                                <td>
                                                    <input type="text" name="badi_address[]"
                                                        class="form-control form-control-sm"
                                                        value="{{ $value->address }}" placeholder="">
                                                </td>
                                                <td>
                                                    <a href="javascript:void();"
                                                        class="btn btn-sm btn-danger font-weight-bolder pr-2"
                                                        data-id="{{ $value->id }}" onclick="removeRowBadiBibadiFunc(this, 'ajax_badi_del')">
                                                        <i class="fas fa-minus-circle"></i>
                                                    </a>
                                                </td>
                                                <input type="hidden" name="badi_id[]" value="{{ $value->id }}">
                                            </tr>
                                        @endforeach
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-lg-12 mb-5">
                                <fieldset>
                                    <legend>রেসপন্ডেন্টের বিবরণ</legend>
                                    <table width="100%" border="1" id="MainBibadiDiv" class="mb-5"
                                        style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">মূল রেসপন্ডেন্টের <span
                                                    class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <th>মন্ত্রণালয়ের নাম <span class="text-danger">*</span></th>
                                            <th>দপ্তরের নাম</th>
                                            <th width="50">
                                                <a href="javascript:void();" id="addMainBibadiRow" class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        <tr></tr>
                                        @php
                                            $department = '';
                                        @endphp
                                        @foreach ($caseBibadi as $key => $val)
                                            @php
                                                $department = isset($val->department) ? $val->department->id : '';
                                                // echo $department;
                                            @endphp
                                            @if ($val->is_main_bibadi == 1)
                                                <tr id="bibadi_10{{ $key }}">
                                                    <td>
                                                        <select {{ request('red') ? 'disabled' : '' }} onchange="getDoptor(this, 'bibadi_10{{ $key }}')" name="main_ministry[]"
                                                            id="ministry_id" class="form-control form-control-sm">
                                                            @foreach ($ministrys as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $item->id == $val->ministry_id ? 'selected' : '' }}>
                                                                    {{ $item->office_name_bn ?? '' }} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <input type="hidden" name="bibadi_id[]" value="{{ $val->id }}">
                                                    <td>
                                                        <select {{ request('red') ? 'disabled' : '' }} name="main_doptor[]" id="doptor_id"
                                                            class="form-control form-control-sm">
                                                            <option value="{{ $val->department->id ?? '' }}">
                                                                {{ $val->department->office_name_bn ?? '' }} </option>
                                                        </select>
                                                    </td>
                                                    @if($key != 0)
                                                    <td>
                                                        <a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2"
                                                            data-id="{{ $value->id }}" onclick="removeRowBadiBibadiFunc(this, 'ajax_bibadi_del')">
                                                            <i class="fas fa-minus-circle"></i>
                                                        </a>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                    <table width="100%" border="1" id="bibadiDiv" class="mb-5"
                                        style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">অন্যান্য বিবাদী <span
                                                    class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <th>মন্ত্রণালয়ের নাম <span class="text-danger">*</span></th>
                                            <th>দপ্তরের নাম</th>
                                            <th width="50">
                                                <a href="javascript:void();" id="addBibadiRow"
                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        @foreach ($caseBibadi as $key => $val)
                                            @if ($val->is_main_bibadi != 1)
                                                <tr id="bibadi_00{{ $key }}">
                                                    <td>
                                                        <select onchange="getDoptor(this, 'bibadi_00{{ $key }}')" name="ministry[]"
                                                            id="ministry_id" class="form-control form-control-sm">
                                                            @foreach ($ministrys as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $item->id == $val->ministry_id ? 'selected' : '' }}>
                                                                    {{ $item->office_name_bn ?? '' }} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <input type="hidden" name="bibadi_id[]" value="{{ $val->id }}">
                                                    <td>
                                                        <select name="doptor[]" id="doptor_id" class="form-control form-control-sm">
                                                            <option value="{{ $val->department->id ?? '' }}">
                                                                {{ $val->department->office_name_bn ?? '' }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2"
                                                            data-id="{{ $value->id }}" onclick="removeRowBadiBibadiFunc(this, 'ajax_bibadi_del')">
                                                            <i class="fas fa-minus-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        @endif
                                        @endforeach
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="mb-6">
                                    <legend>বিষয়বস্তু(সংক্ষিপ্ত)</legend>
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label></label>
                                            <textarea name="subject_matter" class="form-control" id="subject_matter"
                                                rows="3" spellcheck="false">{{ $case->subject_matter ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="mb-6">
                                    <legend>স্থগিতাদেশের বিবরণ (যদি থাকে)</legend>
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <label></label>
                                            <textarea name="postponed_details" class="form-control" id="postponed_details"
                                                rows="3"
                                                spellcheck="false">{{ $case->postponed_details ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        
                        <div class="row">
                            @if($case->court_id == 2)
                                <div class="col-md-12" id="appeal_hide_show_2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <fieldset class="mb-6">
                                                <legend>অন্তর্বর্তীকালীন আদেশের বিবরণ <br/> (যদি থাকে ) </legend>
                                                <div class="form-group row">
                                                    <div class="col-lg-12">
                                                        <label></label>
                                                        <textarea name="interim_order" class="form-control" id="interim_order" rows="3"
                                                            spellcheck="false">{{ $case->interim_order ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="mb-6">
                                                <legend>গুরুত্বপূর্ণ হলে তার কারণ/যৌক্তিকতা <br/> (শুধুমাত্র মন্ত্রণালয় কর্তৃক পূরন)
                                                </legend>
                                                <div class="form-group row">
                                                    <div class="col-lg-12">
                                                        <label></label>
                                                        <textarea name="important_cause" class="form-control" id="important_cause" rows="3"
                                                            spellcheck="false">{{ $case->important_cause ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <fieldset class="mb-6">
                                                <legend>মামলার রায়/ চূড়ান্ত আদেশের সংক্ষিপ্ত বিবরণ
                                                </legend>
                                                <div class="form-group row">
                                                    <div class="col-lg-12">
                                                        <label></label>
                                                        <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                                            spellcheck="false">{{ $case->result_short_dtails ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-group font-weight-bolder font-size-h5">ফলাফল </label>
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="result" id="result" value="1"{{ ($case->result=="1")? "checked" : "" }} />
                                                    <span></span>সরকারের পক্ষে</label>
                                                <label class="radio">
                                                    <input type="radio" name="result" id="result" value="2" {{ ($case->result=="2")? "checked" : "" }}/>
                                                    <span></span>সরকারের বিপক্ষে</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-group font-weight-bolder font-size-h5">সরকারের বিপক্ষে হলে আপিল করা হয়েছে কিনা </label>
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" name="is_appeal" id="is_appeal" value="1" {{ ($case->is_appeal=="1")? "checked" : "" }}/>
                                                    <span></span>হ্যাঁ </label>
                                                <label class="radio">
                                                    <input type="radio" name="is_appeal" id="is_appeal" value="2" {{ ($case->is_appeal=="2")? "checked" : "" }}/>
                                                    <span></span>না</label>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            @endif
                            @if($case->court_id == 1)
                                <div class="col-md-12" id="appeal_hide_show_3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <fieldset class="mb-6">
                                                <legend>লিভ টু আপিল </legend>
                                                <div class="form-group row">
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>লিভ টু আপিল নম্বর <span class="text-danger"></span></label>
                                                        <input type="text" name="leave_to_appeal_no" id="leave_to_appeal_no" class="form-control form-control-sm"autocomplete="off" value="{{ $case->leave_to_appeal_no ?? '' }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>লিভ টু আপিল দায়েরের তারিখ <span class="text-danger"></span></label>
                                                        <input type="text" name="leave_to_appeal_date" id="leave_to_appeal_date" class="form-control form-control-sm common_datepicker"autocomplete="off" value="{{ $case->leave_to_appeal_date ?? '' }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>লিভ টু আপিল আদেশের তারিখ <span class="text-danger"></span></label>
                                                        <input type="text" name="leave_to_appeal_order_date" id="leave_to_appeal_order_date" class="form-control form-control-sm common_datepicker"autocomplete="off" value="{{ $case->leave_to_appeal_order_date ?? '' }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>লিভ টু আপিল আদেশের বিবরণ <span class="text-danger"></span></label>
                                                        <textarea name="leave_to_appeal_order_details" class="form-control" id="leave_to_appeal_order_details" rows="3"
                                                            spellcheck="false">{{ $case->leave_to_appeal_order_details ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="mb-6">
                                                <legend>সিভিল আপিল </legend>
                                                <div class="form-group row">
                                                    
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>সিভিল আদেশের তারিখ <span class="text-danger"></span></label>
                                                        <input type="text" name="civil_appeal_order_date" id="civil_appeal_order_date" class="form-control form-control-sm common_datepicker"autocomplete="off" value="{{ $case->civil_appeal_order_date ?? '' }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>সিভিল আপিল আদেশের বিবরণ <span class="text-danger"></span></label>
                                                        <textarea name="civil_appeal_order_details" class="form-control" id="civil_appeal_order_details" rows="3"
                                                            spellcheck="false">{{ $case->civil_appeal_order_details ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="mb-6">
                                                <legend>রিভিউ মামলা </legend>
                                                <div class="form-group row">
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>রিভিউ নম্বর <span class="text-danger"></span></label>
                                                        <input type="text" name="review_case_no" id="review_case_no" class="form-control form-control-sm"autocomplete="off" value="{{ $case->review_case_no ?? '' }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>রিভিউ দায়েরের তারিখ <span class="text-danger"></span></label>
                                                        <input type="text" name="review_case_date" id="review_case_date" class="form-control form-control-sm common_datepicker"autocomplete="off" value="{{ $case->review_case_date ?? '' }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>রিভিউ আদেশের তারিখ <span class="text-danger"></span></label>
                                                        <input type="text" name="review_case_order_date" id="review_case_order_date" class="form-control form-control-sm common_datepicker"autocomplete="off" value="{{ $case->review_case_order_date ?? '' }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-5" >
                                                        <label>রিভিউ আদেশের বিবরণ <span class="text-danger"></span></label>
                                                        <textarea name="review_case_order_details" class="form-control" id="review_case_order_details" rows="3"
                                                            spellcheck="false">{{ $case->review_case_order_details ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12 mb-5">
                                <fieldset class="">
                                    <legend>মন্তব্য</legend>
                                    <textarea name="comments" class="form-control" id="comments" rows="3" spellcheck="false">{{ $case->comments ?? '' }}
                                    </textarea>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="mb-8">
                                    <div class="rounded bg-success-o-100 d-flex align-items-center justify-content-between flex-wrap px-5 py-0 mb-2">
                                        <div class="d-flex align-items-center mr-2 py-2">
                                            <h3 class="mb-0 mr-8">সংযুক্ত কৃত ফাইল সমূহ </h3>
                                        </div>
                                    </div>
                                    <table class="table table-borderless">

                                        @forelse ($files as $key => $row)
                                        <tr id="deleteFile{{ $row->id }}">
                                            <td class="m-0 p-0">
                                                <div class="form-group mb-2" id="deleteFile{{ $row->id }}">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn bg-success-o-75" type="button">{{ en2bn(++$key) . ' - নম্বর :' }}</button>
                                                        </div>
                                                        <input readonly type="text" class="form-control" value="{{ $row->file_type ?? '' }}" />
                                                        <div class="input-group-append">
                                                            <a href="{{ asset($row->file_patd . $row->file_name) }}" target="_blank" class="btn btn-sm btn-success font-size-h5 float-left">
                                                                <i class="fa fas fa-file-pdf"></i>
                                                                <b>দেখুন</b>
                                                             </a>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <a href="javascript:void(0);" id="" data-id="{{ $row->id }}" onclick="removeRowBadiBibadiFunc(this, 'ajax_case_file_del')" class="btn btn-danger">
                                                                <i class="fas fa-trash-alt"></i>
                                                                <b>মুছুন</b>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <div class="pt-5">
                                            <p class="text-center font-weight-normal font-size-lg">কোনো সংযুক্তি খুঁজে পাওয়া যায়নি</p>
                                        </div>
                                        @endforelse
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="">
                                    <div
                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                        <div class="d-flex align-items-center mr-2 py-2">
                                            <h3 class="mb-0 mr-8">সংযুক্তি (যদি থাকে)</h3>
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Users-->
                                        <div class="symbol-group symbol-hover py-2">
                                            <div class="symbol symbol-30 symbol-light-primary" data-toggle="tooltip"
                                                data-placement="top" title="" role="button" data-original-title="New user">
                                                <div id="addFileRow">
                                                    <span class="symbol-label font-weight-bold bg-success">
                                                        <i class="text-white fa flaticon2-plus font-size-sm"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Users-->
                                    </div>
                                    <div class="mt-3 px-5">
                                        <table width="100%" class="border-0 px-5" id="fileDiv"
                                            style="border:1px solid #dcd8d8;">
                                            <tr></tr>
                                        </table>
                                        <input type="hidden" id="other_attachment_count" value="1">
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <br>
                    </div>
                    <!--end::Card-body-->

                    <!-- <div class="card-footer text-right bg-gray-100 border-top-0">
                                <button type="reset" class="btn btn-primary">সংরক্ষণ করুন</button>
                            </div> -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                {{-- <button type="button" data-toggle="modal" data-target="#myModal"
                                    class="btn btn-primary mr-3" id="preview">প্রিভিউ</button> --}}
                                <button type="submit" class="btn btn-success mr-2"
                                    onclick="return confirm('আপনি কি সংরক্ষণ করতে চান?')">সংরক্ষণ করুন</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>

    </div>
    <!--end::Row-->

@endsection

{{-- Includable CSS Related Page --}}
@section('styles')

    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
    @include('gov_case.case_register.create_js')
@endsection
