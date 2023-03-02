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

    // print_r($surveys); exit;
    $survey_data = '<option value="">-- নির্বাচন করুন --</option>';
    for ($i = 0; $i < sizeof($surveys); $i++) {
        $survey_data .= '<option value="' . $surveys[$i]->id . '">' . $surveys[$i]->st_name . '</option>';
    }

    $land_type_data = '<option value="">-- নির্বাচন করুন --</option>';
    for ($i = 0; $i < sizeof($land_types); $i++) {
        $land_type_data .= '<option value="' . $land_types[$i]->id . '">' . $land_types[$i]->lt_name . '</option>';
    }
    ?>

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
                                <div class="col-lg-4">
                                    <label>মামলার ধরন <span class="text-danger">*</span></label>
                                    <select name="case_type" id="case_type" class="form-control form-control-sm">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        <option value="1">নতুন মামলা</option>
                                        <option value="2">চলমান মামলা</option>
                                        <option value="3">নিষ্পত্তি মামলা</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label>আদালতের নাম <span class="text-danger">*</span></label>
                                    <select name="court" id="court" class="form-control form-control-sm">
                                        <option value=""> -- নির্বাচন করুন --</option>
                                        @foreach ($courts as $value)
                                            <option value="{{ $value->id }}"
                                                {{ old('court') == $value->id ? 'selected' : '' }}>
                                                {{ $value->court_name }} </option>
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
                                    <!-- <input type="text" name="case_type" id="case_type" class="form-control form-control-sm" placeholder="মামলার ধরণ" autocomplete="off"> -->
                                    <div class="" id="CaseCategorDiv">
                                        <select name="case_category" id="CaseCategory" class="form-control form-control-sm">
                                            <option value="">-- নির্বাচন করুন --</option>
                                            @foreach ($GovCaseDivisionCategory as $value)
                                                <option value="{{ $value->id }}" {{ old('case_category') == $value->id ? 'selected' : '' }}> {{ $value->name_bn }} </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 mb-5">
                                    <label>মামলা নং <span class="text-danger">*</span></label>
                                    <input type="text" name="case_no" id="case_no" class="form-control form-control-sm"
                                        placeholder="মামলা নং/সাল(০০১/২০২৩) ">
                                        <input type="hidden" name="caseId" value="">
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label>বছর <span class="text-danger">*</span></label>
                                    <input type="text" name="case_year" id="case_year"
                                        class="form-control form-control-sm common_yearpicker" placeholder="বছর"
                                        autocomplete="off">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>রুল ইস্যুর তারিখ <span class="text-danger">*</span></label>
                                    <input type="text" name="case_date" id="case_date"
                                        class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                        autocomplete="off">
                                </div>
                                <div class="col-lg-4 mb-5">
                                <label>দফাওয়ারি জবাব প্রেরণের তারিখ </label>
                                <input type="text" name="result_sending_date" id="result_sending_date"
                                    class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                    autocomplete="off">
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>সংশ্লিষ্ট আদালতে জবাব দাখিলের তারিখ </label>
                                    <input type="text" name="reply_submission_date" id="reply_submission_date"
                                        class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                        autocomplete="off">
                                </div>

                                <div class="col-lg-4">
                                    <label>দায়িত্ব প্রাপ্ত আইন কর্মকর্তা <span class="text-danger">*</span></label>
                                    <select name="concern_person" id="concern_person" class="form-control form-control-sm">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($concern_person as $value)
                                            <option value="{{ $value->id }}" {{ old('concern_person') == $value->id ? 'selected' : '' }}> {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="highCourt_hide_show">
                                <div class="col-lg-4 mb-5" >
                                    <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span class="text-danger"></span></label>
                                    <input type="text" name="appeal_requesting_memorial" id="appeal_requesting_memorial" class="form-control form-control-sm"autocomplete="off">
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span class="text-danger"></span></label>
                                    <input type="text" name="appeal_requesting_date" id="appeal_requesting_date" class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span class="text-danger"></span></label>
                                    <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3" spellcheck="false">
                                    </textarea>
                                    
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label>প্রযোজ্য ক্ষেত্রে কন্টেম্পট মামলা নম্বর <span class="text-danger"></span></label>
                                    <input type="text" name="contempt_case_no" id="contempt_case_no" class="form-control form-control-sm"autocomplete="off">
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label> কন্টেম্পট মামলা রুল ইস্যুর তারিখ <span class="text-danger"></span></label>
                                    <input type="text" name="contempt_case_isuue_date" id="contempt_case_isuue_date" class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label>কন্টেম্পট মামলার জবাব প্রেরণের তারিখ <span class="text-danger"></span></label>
                                    <input type="text" name="contempt_case_answer_sending_date" id="contempt_case_answer_sending_date" class="form-control form-control-sm  common_datepicker"autocomplete="off">
                                </div>
                                
                                <div class="col-lg-4 mb-5" >
                                    <label>কন্টেম্পট মামলার আদেশ <span class="text-danger"></span></label>
                                    <textarea name="contempt_case_order" class="form-control" id="contempt_case_order" rows="3" spellcheck="false">
                                    </textarea>
                                </div>
                                
                                
                            </div>
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
                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2"><i
                                                        class="fas fa-plus-circle"></i></a>
                                            </th>
                                        </tr>
                                        <tr></tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-lg-12 mb-5">
                                <fieldset>
                                    <legend>রেসপন্ডেন্টের বিবরণ</legend>
                                    <table width="100%" border="1" id="MainBibadiDiv" class="mb-5" style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">মূল রেসপন্ডেন্ট <span class="text-danger">*</span></th>
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
                                        <input type="hidden" id="mainBibadi_count" value="1">
                                    </table>
                                    <table width="100%" border="1" id="bibadiDiv" class="mb-5" style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">অন্যান্য রেসপন্ডেন্ট <span class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <th>মন্ত্রণালয়ের নাম <span class="text-danger">*</span></th>
                                            <th>দপ্তরের নাম</th>
                                            <th width="50">
                                                <a href="javascript:void();" id="addBibadiRow" class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        <tr></tr>
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
                                            <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3"
                                                spellcheck="false"></textarea>
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
                                            <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3"
                                                spellcheck="false"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="appeal_hide_show_2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="mb-6">
                                            <legend>অন্তর্বর্তীকালীন আদেশের বিবরণ <br/> (যদি থাকে ) </legend>
                                            <div class="form-group row">
                                                <div class="col-lg-12">
                                                    <label></label>
                                                    <textarea name="interim_order" class="form-control" id="interim_order" rows="3"
                                                        spellcheck="false"></textarea>
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
                                                        spellcheck="false"></textarea>
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
                                                        spellcheck="false"></textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-group font-weight-bolder font-size-h5">ফলাফল </label>
                                        <div class="radio-inline">
                                            <label class="radio">
                                                <input type="radio" name="result" id="result" value="1"
                                                    checked="checke" />
                                                <span></span>সরকারের পক্ষে</label>
                                            <label class="radio">
                                                <input type="radio" name="result" id="result" value="2" />
                                                <span></span>সরকারের বিপক্ষে</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-group font-weight-bolder font-size-h5">সরকারের বিপক্ষে হলে আপিল করা হয়েছে কিনা </label>
                                        <div class="radio-inline">
                                            <label class="radio">
                                                <input type="radio" name="is_appeal" id="is_appeal" value="1"/>
                                                <span></span>হ্যাঁ </label>
                                            <label class="radio">
                                                <input type="radio" name="is_appeal" id="is_appeal" value="2" checked="checke"/>
                                                <span></span>না</label>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                            <div class="col-md-12" id="appeal_hide_show_3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset class="mb-6">
                                            <legend>লিভ টু আপিল </legend>
                                            <div class="form-group row">
                                                <div class="col-lg-6 mb-5" >
                                                    <label>লিভ টু আপিল নম্বর <span class="text-danger"></span></label>
                                                    <input type="text" name="leave_to_appeal_no" id="leave_to_appeal_no" class="form-control form-control-sm"autocomplete="off">
                                                </div>
                                                <div class="col-lg-6 mb-5" >
                                                    <label>লিভ টু আপিল দায়েরের তারিখ <span class="text-danger"></span></label>
                                                    <input type="text" name="leave_to_appeal_date" id="leave_to_appeal_date" class="form-control form-control-sm common_datepicker"autocomplete="off">
                                                </div>
                                                <div class="col-lg-6 mb-5" >
                                                    <label>লিভ টু আপিল আদেশের তারিখ <span class="text-danger"></span></label>
                                                    <input type="text" name="leave_to_appeal_order_date" id="leave_to_appeal_order_date" class="form-control form-control-sm common_datepicker"autocomplete="off">
                                                </div>
                                                <div class="col-lg-6 mb-5" >
                                                    <label>লিভ টু আপিল আদেশের বিবরণ <span class="text-danger"></span></label>
                                                    <textarea name="leave_to_appeal_order_details" class="form-control" id="leave_to_appeal_order_details" rows="3"
                                                        spellcheck="false"></textarea>
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
                                                    <input type="text" name="civil_appeal_order_date" id="civil_appeal_order_date" class="form-control form-control-sm common_datepicker"autocomplete="off">
                                                </div>
                                                <div class="col-lg-6 mb-5" >
                                                    <label>সিভিল আপিল আদেশের বিবরণ <span class="text-danger"></span></label>
                                                    <textarea name="civil_appeal_order_details" class="form-control" id="civil_appeal_order_details" rows="3"
                                                        spellcheck="false"></textarea>
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
                                                    <input type="text" name="review_case_no" id="review_case_no" class="form-control form-control-sm"autocomplete="off">
                                                </div>
                                                <div class="col-lg-6 mb-5" >
                                                    <label>রিভিউ দায়েরের তারিখ <span class="text-danger"></span></label>
                                                    <input type="text" name="review_case_date" id="review_case_date" class="form-control form-control-sm common_datepicker"autocomplete="off">
                                                </div>
                                                <div class="col-lg-6 mb-5" >
                                                    <label>রিভিউ আদেশের তারিখ <span class="text-danger"></span></label>
                                                    <input type="text" name="review_case_order_date" id="review_case_order_date" class="form-control form-control-sm common_datepicker"autocomplete="off">
                                                </div>
                                                <div class="col-lg-6 mb-5" >
                                                    <label>রিভিউ আদেশের বিবরণ <span class="text-danger"></span></label>
                                                    <textarea name="review_case_order_details" class="form-control" id="review_case_order_details" rows="3"
                                                        spellcheck="false"></textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-5">
                                <fieldset class="">
                                    <legend>মন্তব্য</legend>
                                    <textarea name="comments" class="form-control" id="comments" rows="3" spellcheck="false">
                                    </textarea>
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
        $(document).ready(function() {
            addBadiRowFunc();
            addBibadiRowFunc();
        });
    </script>

    <script type="text/javascript">
        // dynamically change high court / appeal court
        $(document).ready(function() {
            $("#court").change(function(){
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

            $("#appeal_case_id").change(function(){
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

                            $( "select[name='case_category']").find('option[value="'+response.case_category_id+'"]').attr('selected','selected');

                            $( "select[name='concern_person']").find('option[value="'+response.concern_user_id+'"]').attr('selected','selected');

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


                                
