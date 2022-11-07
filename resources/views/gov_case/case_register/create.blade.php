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
                                    <select name="appeal_case" id="appeal_case" class="form-control form-control-sm">
                                        <option value=""> -- নির্বাচন করুন --</option>
                                        @foreach ($appealCase as $value)
                                            <option value="{{ $value->id }}"
                                                {{ old('appeal_case') == $value->id ? 'selected' : '' }}>
                                                {{ $value->case_no }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label>মামলা নং <span class="text-danger">*</span></label>
                                    <input type="text" name="case_no" id="case_no" class="form-control form-control-sm"
                                        placeholder="মামলা নং ">
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
                                <!-- <div class="col-lg-4 mb-5">
                                    <label>মামলার বিভাগ <span class="text-danger">*</span></label>
                                    <select onchange="caseCategoryGet(this, 'CaseCategory', 'CaseCategorDiv')" name="case_department" id="case_department" class="form-control form-control-sm">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($GovCaseDivision as $value)
                                            <option value="{{ $value->id }}" {{ old('case_department') == $value->id ? 'selected' : '' }}> {{ $value->name_bn }} </option>
                                        @endforeach
                                    </select>
                                </div> -->
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
                        </fieldset>

                        <div class="form-group row">
                            <div class="col-lg-6 mb-5">
                                <fieldset>
                                    <legend>বাদীর বিবরণ</legend>
                                    <table width="100%" border="1" id="badiDiv" style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th>বাদীর নাম <span class="text-danger">*</span></th>
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
                            <div class="col-lg-6 mb-5">
                                <fieldset>
                                    <legend>বিবাদীর বিবরণ</legend>
                                    <table width="100%" border="1" id="MainBibadiDiv" class="mb-5" style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">মূল বিবাদী <span class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <th>মন্ত্রণালয়ের নাম <span class="text-danger">*</span></th>
                                            <th>দপ্তরের নাম</th>
                                            {{-- <th width="50">
                                                <a href="javascript:void();" id="addBibadiRow" class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            </th> --}}
                                        </tr>
                                        <tr></tr>
                                    </table>
                                    <table width="100%" border="1" id="bibadiDiv" class="mb-5" style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">অন্যান্য বিবাদী <span class="text-danger">*</span></th>
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
                            <div class="col-lg-6 mb-5">
                                <label class="form-group font-weight-bolder font-size-h5">সরকারের পক্ষে জবাব প্রেরনের তারিখ </label>
                                <input type="text" name="result_sending_date" id="result_sending_date"
                                    class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                    autocomplete="off">
                            </div>
                            <div class="col-lg-6 mb-5">
                                <label class="form-group font-weight-bolder font-size-h5">সংশ্লিষ্ট আদালতে জবাব দাখিলের তারিখ </label>
                                <input type="text" name="reply_submission_date" id="reply_submission_date"
                                    class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                    autocomplete="off">
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

                    <!-- <div class="card-footer text-right bg-gray-100 border-top-0">
                        <button type="reset" class="btn btn-primary">সংরক্ষণ করুন</button>
                    </div> -->
                    <div class="card-footer">
                        <div class="row">
                            {{-- <div class="col-lg-4"></div> --}}
                            <div class="col-lg-12 text-center" >
                                {{-- <button type="button" data-toggle="modal" data-target="#myModal"
                                    class="btn btn-primary mr-3" id="preview">প্রিভিউ</button> --}}
                                <button type="submit" class="btn btn-success mr-2 text-center"
                                    onclick="return confirm('আপনি কি সংরক্ষণ করতে চান?')">সংরক্ষণ করুন</button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="myModal">

                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h3 class="modal-title">নতুন মামলার তথ্য</h3>
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div class="row">

                                        <label>
                                            <h4>সাধারণ তথ্য</h4>
                                        </label>
                                        <table class="tg">
                                            <thead>
                                                <tr>
                                                    <th class="tg-19u4 text-center">আদালতের নাম </th>
                                                    <!-- <th class="tg-19u4 text-center">বিভাগ </th>
                                        <th class="tg-19u4 text-center">জেলা </th> -->
                                                    <th class="tg-19u4 text-center">উপজেলা </th>
                                                    <th class="tg-19u4 text-center">মৌজা </th>
                                                </tr>
                                                <tr>
                                                    <td class="tg-nluh" id="previewCourt"></td>
                                                    <!--  <td class="tg-nluh" id="previewDivision"></td>
                                        <td class="tg-nluh" id="previewDistrict"></td> -->
                                                    <td class="tg-nluh" id="previewUpazila"></td>
                                                    <td class="tg-nluh" id="previewMouja_id"></td>

                                                </tr>
                                                <tr>
                                                    <!--  <th class="tg-19u4 text-center">মামলার ধরণ</th> -->
                                                    <th class="tg-19u4 text-center">মামলা নং </th>
                                                    <th class="tg-19u4 text-center">মামলা রুজুর তারিখ</th>
                                                </tr>
                                                <tr>
                                                    <!-- <td class="tg-nluh" id="previewCase_type"></td> -->
                                                    <td class="tg-nluh" id="previewCase_no"></td>
                                                    <td class="tg-nluh" id="previewCase_date"></td>

                                                </tr>
                                            </thead>
                                        </table>
                                        <br>
                                        <br>
                                        <table class="tg">
                                            <label>
                                                <h4>বাদীর তথ্য</h4>
                                            </label>
                                            <thead>
                                                <tr>
                                                    <th class="tg-19u4 text-center">বাদীর নাম</th>
                                                    <th class="tg-19u4 text-center">পিতা/স্বামীর নাম</th>
                                                    <th class="tg-19u4 text-center">ঠিকানা</th>
                                                </tr>
                                                <tr>
                                                    <td class="tg-nluh" id="previewBadi_name"></td>
                                                    <td class="tg-nluh" id="previewBadi_spouse_name"></td>
                                                    <td class="tg-nluh" id="previewBadi_address"></td>

                                                </tr>
                                            </thead>
                                        </table>
                                        <br>
                                        <br>
                                        <table class="tg">
                                            <label>
                                                <h4>বিবাদীর তথ্য</h4>
                                            </label>
                                            <thead>
                                                <tr>
                                                    <th class="tg-19u4 text-center">মন্ত্রণালয়ের নাম </th>
                                                    <th class="tg-19u4 text-center">দপ্তরের নাম</th>
                                                    <!-- <th class="tg-19u4 text-center">ঠিকানা</th> -->
                                                </tr>
                                                <tr>
                                                    <td class="tg-nluh" id="previewBibadi_name"></td>
                                                    <td class="tg-nluh" id="previewBibadi_spouse_name"></td>
                                                    <td class="tg-nluh" id="previewBibadi_address"></td>
                                                </tr>

                                            </thead>
                                        </table>
                                        <br>
                                        <br>
                                        <table class="tg">
                                            <label>
                                                <h4>জরিপের বিবরণ</h4>
                                            </label>
                                            <thead>
                                                <tr>
                                                    <th class="tg-19u4 text-center">জরিপের ধরণ </th>
                                                    <th class="tg-19u4 text-center">দাগ নং</th>
                                                    <th class="tg-19u4 text-center">খতিয়ান নং</th>
                                                </tr>
                                                <tr>
                                                    <td class="tg-nluh" id="previewSt_id"></td>
                                                    <td class="tg-nluh" id="previewKhotian_no"></td>
                                                    <td class="tg-nluh" id="previewDaag_no"></td>
                                                </tr>

                                                <tr>
                                                    <th class="tg-19u4 text-center">জমির শ্রেণী</th>
                                                    <th class="tg-19u4 text-center">নালিশী জমির পরিমাণ (শতক)</th>
                                                    <th class="tg-19u4 text-center">জমির পরিমাণ (শতক)</th>
                                                </tr>
                                                <tr>
                                                    <td class="tg-nluh" id="previewLt_id"></td>
                                                    <td class="tg-nluh" id="previewLand_size"></td>
                                                    <td class="tg-nluh" id="previewLand_demand"></td>

                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="col-lg-6 mb-5"></div>
                                        <table class="tg">
                                            <thead>
                                                <tr>
                                                    <th class="tg-19u4 text-center">তফশীল বিবরণ</th>
                                                    <th class="tg-19u4 text-center">চৌহদ্দীর বিবরণ</th>
                                                    <th class="tg-19u4 text-center">মন্তব্য</th>

                                                </tr>
                                                <tr>
                                                    <td class="tg-nluh" id="previewTafsil"></td>
                                                    <td class="tg-nluh" id="previewChowhaddi"></td>
                                                    <td class="tg-nluh" id="previewComments"></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>

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
                } else {
                    $('#appeal_hide_show').hide();
                }
            });

            $("#appeal_case").change(function(){
                var case_id = $('#appeal_case').find(":selected").val();
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


            /*$("#court").change(function(){
                var items = '';
                var getCourt = $('#court').find(":selected").val();
                if (getCourt == 1) {
                    items += '<div class="col-lg-4 mb-5" id="getAppealCase">';
                    items += '<label>মামলা অনুসন্ধান করুন <span class="text-danger">*</span></label>';
                    items += '<select name="old_case_no" id="old_case_no" class="form-control form-control-sm">';
                    items += '<option value="">-- নির্বাচন করুন --</option>';
                    items += '</select>';
                    items += '</div>';
                    $('#selectCourt').after(items);
                    // $('#old_case_no').select2();
                } else {
                    $('#getAppealCase').remove();
                    // $('form').trigger("reset");
                }
            });*/
        });
    </script>

@endsection


                                
