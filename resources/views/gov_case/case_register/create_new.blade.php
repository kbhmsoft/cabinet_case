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
    @include('gov_case.case_register.create_css')
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
                    <div class="card-body">
                            <form id="signUpForm" action="{{ route('cabinet.case.store') }}" class="form" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- start step indicators -->
                                <div class="form-header d-flex mb-4">
                                    <span class="stepIndicator">Account Setup</span> 
                                    <span class="stepIndicator">Social Profiles</span> 
                                    <span class="stepIndicator">Personal Details</span>
                                </div>
                                <!-- end step indicators -->
                                <!-- step one -->
                                <div class="step">
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
                                    <label>মামলার শ্রেণী/কেস-টাইপ <span class="text-danger">*</span></label>
                                    <div class="" id="CaseCategorDiv">
                                        <select name="case_category_type" id="case_category_type" class="form-control form-control-sm">
                                            <option value="">-- নির্বাচন করুন --</option>
                                           
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 mb-5">
                                    <label>মামলা নং <span class="text-danger">*</span></label>
                                    <input type="text" name="case_no" id="case_no" class="form-control form-control-sm"
                                        placeholder="মামলা নং/সাল(০০১/২০২৩) ">
                                        <input type="hidden" name="caseId" value="" >
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label>বছর <span class="text-danger">*</span></label>
                                    <input type="text" name="case_year" id="case_year"
                                        class="form-control form-control-sm common_yearpicker" placeholder="বছর"
                                        autocomplete="off">
                                </div>
                                <div class="col-lg-12 mb-5">
                                    
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
                                    
                                </div>
                                <div class="col-lg-6 mb-5">
                                    <table width="100%" border="1" id="MainBibadiDiv" class="mb-5" style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">মূল রেসপন্ডেন্ট <span class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <th>মূল রেসপন্ডেন্ট নাম <span class="text-danger">*</span></th>
                                            <th width="50">
                                                <a href="javascript:void();" id="addMainBibadiRow" class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            </th> 
                                        </tr>
                                        <tr></tr>
                                        <input type="hidden" id="mainBibadi_count" value="1">
                                    </table>
                                </div>
                                <div class="col-lg-6 mb-5">
                                    <table width="100%" border="1" id="bibadiDiv" class="mb-5" style="border:1px solid #dcd8d8;">
                                        <tr>
                                            <th class="bg-white text-left ml-2" colspan="3">অন্যান্য রেসপন্ডেন্ট <span class="text-danger">*</span></th>
                                        </tr>
                                        <tr>
                                            <th>অন্যান্য রেসপন্ডেন্ট নাম <span class="text-danger">*</span></th>
                                            <th width="50">
                                                <a href="javascript:void();" id="addBibadiRow" class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        <tr></tr>
                                    </table>
                                </div>
                                <div class="col-lg-4 mb-5">
                                    <label>রুল ইস্যুর তারিখ <span class="text-danger">*</span></label>
                                    <input type="text" name="case_date" id="case_date"
                                        class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                        autocomplete="off">
                                </div>
                                <div class="col-lg-8 mb-5">
                                    <label>বিষয়বস্তু(সংক্ষিপ্ত)</label>
                                    <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3"
                                        spellcheck="false"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-group font-weight-bolder font-size-h5">স্থগিতাদেশের </label>
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" name="postponed_order" id="postponed_order_have" value="1"
                                                checked="checke" />
                                            <span></span>আছে</label>
                                        <label class="radio">
                                            <input type="radio" name="postponed_order" id="postponed_order_not" value="0" />
                                            <span></span>নেই</label>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-5" id="postponed_order_details">
                                    
                                    <label>স্থগিতাদেশের বিবরণ</label>
                                    <textarea name="postponed_details" class="form-control" id="postponed_details" rows="3"
                                        spellcheck="false"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-group font-weight-bolder font-size-h5">অন্তর্বর্তীকালীন আদেশ </label>
                                    <div class="radio-inline">
                                        <label class="radio">
                                            <input type="radio" name="interim_order" id="interim_order_have" value="1"
                                                checked="checke" />
                                            <span></span>আছে</label>
                                        <label class="radio">
                                            <input type="radio" name="interim_order" id="interim_order_not" value="0" />
                                            <span></span>নেই</label>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-5" id="interim_order_details_div">
                                    <label>অন্তর্বর্তীকালীন আদেশের বিবরণ</label>
                                    <textarea name="interim_order_details" class="form-control" id="interim_order" rows="3"
                                        spellcheck="false"></textarea>
                                </div>
                            <div class="col-md-12">
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
                                </div>
                                <!-- step two -->
                                <div class="step">
                                    <p class="text-center mb-4">Your presence on the social network</p>
                                    <div class="mb-3"><input type="text" placeholder="Linked In" oninput="this.className = ''" name="linkedin" /></div>
                                    <div class="mb-3"><input type="text" placeholder="Twitter" oninput="this.className = ''" name="twitter" /></div>
                                    <div class="mb-3"><input type="text" placeholder="Facebook" oninput="this.className = ''" name="facebook" /></div>
                                </div>
                                <!-- step three -->
                                <div class="step">
                                    <p class="text-center mb-4">We will never sell it</p>
                                    <div class="mb-3"><input type="text" placeholder="Full name" oninput="this.className = ''" name="fullname" /></div>
                                    <div class="mb-3"><input type="text" placeholder="Mobile" oninput="this.className = ''" name="mobile" /></div>
                                    <div class="mb-3"><input type="text" placeholder="Address" oninput="this.className = ''" name="address" /></div>
                                </div>
                                <!-- start previous / next buttons -->
                                <div class="form-footer">
                                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                     <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                                </div>
                                <!-- end previous / next buttons -->
                            </form>
                    </div>
                    <!--end::Card-body-->
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
            $('#appeal_hide_show_3').hide();
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
            // $("#postponed_order_details").hide();
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


                                
