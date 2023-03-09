@extends('layouts.cabinet.cab_default')

@section('content')


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
                <form action="{{ route('cabinet.case.store') }}" class="form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
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

                                <div class="col-lg-4 mb-5" style="display:none;" id="appeal_hide_show">
                                    <label>মামলা নির্বাচন করুন <span class="text-danger">*</span></label>
                                    <select name="appeal_case_id" id="appeal_case_id" class="form-control form-control-sm">
                                        <option value=""> -- নির্বাচন করুন --</option>
                                        @foreach ($appealCase as $value)
                                            <option value="{{ $value->id }}"
                                                {{ old('appeal_case_id') == $value->id ? 'selected' : '' }}>
                                                {{ $value->case_no }} </option>
                                        @endforeach
                                    </select>
                                </div>

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
                                    <label>মামলার শ্রেণী/কেস-টাইপ <span class="text-danger">*</span></label>
                                    <div class="" id="CaseCategorDiv">
                                        <select name="case_category_type" id="case_category_type" class="form-control form-control-sm">
                                            <option value="">-- নির্বাচন করুন --</option>
                                            @foreach($GovCaseDivisionCategoryType as $value)
                                                <option value="{{ $value->id }}" {{ old('case_category') == $value->id || $case->case_type_id == $value->id   ? 'selected' : '' }}> {{ $value->name_en }} </option>
                                            @endforeach
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>মামলা নং <span class="text-danger">*</span></label>
                                    <input disabled type="text" name="case_no" id="case_no" class="form-control form-control-sm"
                                        placeholder="মামলা নং " value="{{ $case->case_no ?? '' }}">
                                    <input type="hidden" name="caseId" value="{{ $case->id ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>বছর <span class="text-danger">*</span></label>
                                    <input type="text" name="case_year" id="case_year"
                                        class="form-control form-control-sm common_yearpicker" placeholder="বছর"
                                        autocomplete="off" value="{{ $case->year ?? '' }}">
                                </div>
                                <div class="col-lg-12 mb-5">
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
                                </div>
                                <div class="col-lg-6 mb-5">
                                    <table width="100%" border="1" id="MainBibadiDiv" class="mb-5"
                                        style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">মূল রেসপন্ডেন্টের <span
                                                    class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <th>মন্ত্রণালয়ের নাম <span class="text-danger">*</span></th>
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
                                                        <select {{ request('red') ? 'disabled' : '' }} " name="main_respondent[]"
                                                            id="ministry_id" class="form-control form-control-sm">
                                                            @foreach ($ministrys as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $item->id == $val->respondent_id ? 'selected' : '' }}>
                                                                    {{ $item->office_name_bn ?? '' }} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <input type="hidden" name="bibadi_id[]" value="{{ $val->id }}">
                                                    @if($key <= 0)
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
                                </div>
                                <div class="col-lg-6 mb-5">
                                    <table width="100%" border="1" id="bibadiDiv" class="mb-5"
                                        style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">অন্যান্য বিবাদী <span
                                                    class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <th>মন্ত্রণালয়ের নাম <span class="text-danger">*</span></th>
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
                                                        <select onchange="getDoptor(this, 'bibadi_00{{ $key }}')" name="other_respondent[]"
                                                            id="ministry_id" class="form-control form-control-sm">
                                                            @foreach ($ministrys as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $item->id == $val->respondent_id ? 'selected' : '' }}>
                                                                    {{ $item->office_name_bn ?? '' }} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <input type="hidden" name="bibadi_id[]" value="{{ $val->id }}">
                                                    
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
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>মামলা রুল ইস্যুর তারিখ <span class="text-danger">*</span></label>
                                    <input type="text" name="case_date" id="case_date"
                                        class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                        autocomplete="off"
                                        value="{{ $case->date_issuing_rule_nishi ?? '' }}">
                                </div>
                                <div class="col-lg-8 mb-5">
                                    <label>বিষয়বস্তু(সংক্ষিপ্ত)</label>
                                    <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3"
                                        spellcheck="false">{{ $case->subject_matter ?? '' }}</textarea>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-group font-weight-bolder font-size-h5">স্থগিতাদেশের </label>
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" name="postponed_order" id="postponed_order_have" value="1"
                                                {{ ($case->postponed_order=="1")? "checked" : "" }} />
                                            <span></span>আছে</label>
                                        <label class="radio">
                                            <input type="radio" name="postponed_order" id="postponed_order_not" value="0" {{ ($case->postponed_order=="0")? "checked" : "" }}/>
                                            <span></span>নেই</label>
                                    </div>
                                </div>

                                <div class="{{ $case->postponed_order == 1 ? 'col-md-4 mb-5' : 'col-md-4 mb-5 d-none' }}" id="postponed_order_details">
                                    
                                    <label>স্থগিতাদেশের বিবরণ</label>
                                    <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3"
                                        spellcheck="false">{{ $case->postponed_details ?? '' }}</textarea>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-group font-weight-bolder font-size-h5">অন্তর্বর্তীকালীন আদেশ </label>
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" name="interim_order" id="interim_order_have" value="1"
                                                {{ ($case->interim_order=="0")? "checked" : "" }} />
                                            <span></span>আছে</label>
                                        <label class="radio">
                                            <input type="radio" name="interim_order" id="interim_order_not" value="0" {{ ($case->interim_order=="0")? "checked" : "" }}/>
                                            <span></span>নেই</label>
                                    </div>
                                </div>

                                <div class="{{ $case->interim_order_details == 1 ? 'col-md-4 mb-5' : 'col-md-4 mb-5 d-none' }}" id="interim_order_details_div">
                                    <label>অন্তর্বর্তীকালীন আদেশের বিবরণ</label>
                                    <textarea name="interim_order_details" class="form-control" id="interim_order" rows="3"
                                        spellcheck="false">{{ $case->interim_order_details ?? '' }}</textarea>
                                </div>
                            <div class="col-md-12 mb-5">
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
                            <div class="col-md-12 mb-5">
                                <fieldset class="">
                                    <div
                                        class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                        <div class="d-flex align-items-center mr-2 py-2">
                                            <h3 class="mb-0 mr-8">সংযুক্তি (রুল কপি সংযুক্ত করুন)</h3>
                                        </div>
                                        <!--end::Info-->
                                        <!--begin::Users-->
                                        <div class="symbol-group symbol-hover py-2">
                                            <div class="symbol symbol-30 symbol-light-primary" data-toggle="tooltip"
                                                data-placement="top" title="" role="button" data-original-title="ফাইল যুক্ত করুণ">
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
                                        <table width="100%" class="border-0 px-5" id="fileDiv" style="border:1px solid #dcd8d8;">
                                            <tr></tr>
                                        </table>
                                        <input type="hidden" id="other_attachment_count" value="1">
                                    </div>
                                </fieldset>
                            </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-8">
                            <legend> মামলার ফলাফল</legend>
                                <div class="form-group row">
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
                                    <div class="col-md-6">
                                        <label>মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
                                        <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
                                            spellcheck="false">{{ $case->result_short_dtails ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-group font-weight-bolder font-size-h5">সরকারের বিপক্ষে হলে আপিল করা হয়েছে কিনা </label>
                                        <div class="radio-inline">
                                            <label class="radio">
                                                <input type="radio" name="is_appeal" id="is_appeal" value="1"{{ ($case->is_appeal=="1")? "checked" : "" }}/>
                                                <span></span>হ্যাঁ </label>
                                            <label class="radio">
                                                <input type="radio" name="is_appeal" id="is_appeal" value="2" {{ ($case->is_appeal=="2")? "checked" : "" }}"/>
                                                <span></span>না</label>
                                        </div>
                                    </div>  
                                </div>  
                        </fieldset>
                        <fieldset class="mb-8">
                            <legend> পদক্ষেপের বিবরণ</legend>
                                <div class="form-group row">
                                    <div class="col-lg-6 mb-5">
                                        <label>দফাওয়ারি জবাব সলিসিটর অনুবিভাগে প্রেরণের তারিখ </label>
                                        <input type="text" name="result_sending_date" id="result_sending_date"
                                            class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                            autocomplete="off" value="{{ $case->result_sending_date ?? '' }}">
                                    </div>
                                    <div class="col-lg-6 mb-5">
                                        <label>দফাওয়ারি জবাব সলিসিটর অনুবিভাগে প্রেরণের স্মারক </label>
                                        <input type="text" name="result_sending_memorial" id="result_sending_memorial"
                                            class="form-control form-control-sm" placeholder=""
                                            autocomplete="off" value="{{ $case->result_sending_memorial ?? '' }}">
                                    </div>
                                    <div class="col-lg-6 mb-5">
                                        <label>সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের তারিখ </label>
                                        <input type="text" name="result_sending_date_solisitor_to_ag" id="result_sending_date_solisitor_to_ag"
                                            class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                            autocomplete="off" value="{{ $case->result_sending_date_solisitor_to_ag ?? '' }}">
                                    </div>
                                    <div class="col-lg-6 mb-5">
                                        <label>সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের স্মারক </label>
                                        <input type="text" name="result_sending_memorial_solisitor_to_ag" id="result_sending_memorial_solisitor_to_ag"
                                            class="form-control form-control-sm" placeholder=""
                                            autocomplete="off" value="{{ $case->result_sending_memorial_solisitor_to_ag ?? '' }}">
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label>সংশ্লিষ্ট আদালতে জবাব দাখিলের তারিখ </label>
                                        <input type="text" name="reply_submission_date" id="reply_submission_date"
                                            class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                            autocomplete="off" value="{{ $case->reply_submission_date ?? '' }}">
                                    </div>
                                    <div class="col-lg-4 mb-5" >
                                        <label>প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের স্মারক <span class="text-danger"></span></label>
                                        <input type="text" name="tamil_requesting_memorial" id="tamil_requesting_memorial" class="form-control form-control-sm"autocomplete="off" value="{{ $case->tamil_requesting_memorial ?? '' }}">
                                    </div>
                                    
                                    <div class="col-lg-4 mb-5" >
                                        <label>প্রযোজ্য ক্ষেত্রে তামিল প্রতিবেদন প্রেরণের তারিখ <span class="text-danger"></span></label>
                                        <input type="text" name="tamil_requesting_date" id="tamil_requesting_date" class="form-control form-control-sm  common_datepicker"autocomplete="off" value="{{ $case->tamil_requesting_date ?? '' }}">
                                    </div>

                                    <div class="col-lg-4 mb-5">
                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিল </label>
                                        <input type="text" name="appeal_against_postpond_interim_order" id="appeal_against_postpond_interim_order" class="form-control form-control-sm" placeholder=""
                                            autocomplete="off" value="{{ $case->appeal_against_postpond_interim_order ?? '' }}">
                                    </div>
                                    <div class="col-lg-4 mb-5" >
                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিলের তারিখ <span class="text-danger"></span></label>
                                        <input type="text" name="appeal_against_postpond_interim_order_date" id="appeal_against_postpond_interim_order_date" class="form-control form-control-sm  common_datepicker"autocomplete="off" value="{{ $case->appeal_against_postpond_interim_order_date ?? '' }}">
                                    </div>
                                    <div class="col-lg-4 mb-5" >
                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিলের বিবরণ <span class="text-danger"></span></label>
                                        <textarea type="text" name="appeal_against_postpond_interim_order_details" id="appeal_against_postpond_interim_order_details" class="form-control form-control-sm"autocomplete="off">{{ $case->appeal_against_postpond_interim_order_details ?? '' }}</textarea>
                                    </div>
                                </div>  
                        </fieldset>

                        <fieldset class="mb-8">
                            <legend> পদক্ষেপের বিবরণ</legend>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>রায় ঘোষণার তারিখ<span class="text-danger"></span></label>
                                    <input type="text" name="result_date" class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর" autocomplete="off" value="{{ $case->result_date ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span class="text-danger"></span></label>
                                    <input type="text" name="result_copy_asking_date" class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর" autocomplete="off" value="{{ $case->result_copy_asking_date ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>রায়ের নকল প্রাপ্তির তারিখ<span class="text-danger"></span></label>
                                    <input type="text" name="result_copy_reciving_date" class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর" autocomplete="off" value="{{ $case->result_copy_reciving_date ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5" >
                                    <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span class="text-danger"></span></label>
                                    <input type="text" name="appeal_requesting_memorial" id="appeal_requesting_memorial" class="form-control form-control-sm"autocomplete="off" value="{{ $case->appeal_requesting_memorial ?? '' }}">
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span class="text-danger"></span></label>
                                    <input type="text" name="appeal_requesting_date" id="appeal_requesting_date" class="form-control form-control-sm  common_datepicker"autocomplete="off" value="{{ $case->appeal_requesting_date ?? '' }}">
                                </div>
                                <div class="col-lg-4 mb-5" >
                                    <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span class="text-danger"></span></label>
                                    <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3" spellcheck="false">{{ $case->reason_of_not_appealing ?? '' }}
                                    </textarea>
                                    
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="mb-8">
                            <legend> কন্টেম্পট মামলা সংক্রান্ত</legend>
                            <div class="form-group row" id="highCourt_hide_show">
                                
                                <div class="col-lg-4 mb-5" >
                                    <label>প্রযোজ্য ক্ষেত্রে কন্টেম্পট মামলা নম্বর <span class="text-danger"></span></label>
                                    <input type="text" name="contempt_case_no" id="contempt_case_no" class="form-control form-control-sm"autocomplete="off" value="{{ $case->contempt_case_no ?? '' }}">
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label> কন্টেম্পট মামলা রুল ইস্যুর তারিখ <span class="text-danger"></span></label>
                                    <input type="text" name="contempt_case_isuue_date" id="contempt_case_isuue_date" class="form-control form-control-sm  common_datepicker"autocomplete="off" value="{{ $case->contempt_case_isuue_date ?? '' }}">
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label>কন্টেম্পট মামলার জবাব প্রেরণের তারিখ <span class="text-danger"></span></label>
                                    <input type="text" name="contempt_case_answer_sending_date" id="contempt_case_answer_sending_date" class="form-control form-control-sm  common_datepicker"autocomplete="off" value="{{ $case->contempt_case_answer_sending_date ?? '' }}">
                                </div>
                                
                                <div class="col-lg-6 mb-5" >
                                    <label>অন্যান্য পদক্ষেপের বিবরণ<br>(যদি থাকে) <span class="text-danger"></span></label>
                                    <textarea name="others_action_detials" class="form-control" id="others_action_detials" rows="3" spellcheck="false">{{ $case->others_action_detials ?? '' }}
                                    </textarea>
                                </div>
                                <div class="col-md-6 mb-5">
                                        <label>মন্তব্য</label>
                                        <textarea name="comments" class="form-control" id="comments" rows="3" spellcheck="false">{{ $case->comments ?? '' }}
                                        </textarea>
                                </div>
                            </div>
                        </fieldset>
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
                        <br>
                    </div>
                    <!--end::Card-body-->

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-center" >
                                <button type="submit" class="btn btn-success mr-2 text-center"
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
    <style>
        /*.select2-container .select2-selection--single {
            height: 37px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 25px !important;
        }*/
    </style>
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
    @include('gov_case.case_register.create_js')
   <script type="text/javascript">
        $("#postponed_order_have").click(function() {
            $("#postponed_order_details").removeClass("d-none");
        });

        $("#postponed_order_not").click(function() {
            $("#postponed_order_details").addClass("d-none");
        });
        
        $("#interim_order_have").click(function() {
            $("#interim_order_details_div").removeClass("d-none");
        });

        $("#interim_order_not").click(function() {
            $("#interim_order_details_div").addClass("d-none");
        });
   </script>
@endsection


                                
