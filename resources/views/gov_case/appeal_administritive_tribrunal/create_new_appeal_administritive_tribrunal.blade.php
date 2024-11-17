@extends('layouts.cabinet.cab_default')
<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">


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

    <?php
    $case = [];
    $case['create_by'] = '';

    ?>
    @include('gov_case.case_register.create_css')
    <style>
        .lawyer_title {
            font-size: 18px;
        }

        .form-short-title {
            font-size: 1.25rem;
            font-weight: 900;
            margin-top: -20px;
            color: rgb(235, 58, 58);
        }

        /* Tooltip container */
        .tooltip-icon {
            position: relative;
            display: inline-block;
        }

        .tooltip-button {
            color: #d80517;
        }

        /* Tooltip text */
        .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: rgb(237, 232, 232);
            color: black;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip-icon:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>

    <!--begin::Row-->
    <div class="row">

        {{-- <div class="col-md-12"> --}}
        <!--begin::Card-->
        <div style="width:100%" class="card gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
                <h5 class="form-short-title">(মামলার বিষয়বস্তুর সাথে সরাসরি সংশ্লিষ্ট অফিস কর্তৃক মামলা
                    এন্ট্রি করতে হবে। মামলার রেসপন্ডেন্ট তালিকাভুক্ত হলেও বিষয়বস্তুর সাথে সংশ্লিষ্টতা না থাকলে উক্ত মামলা
                    এন্ট্রি হতে বিরত থাকুন।)*</h5>
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
                <ul class="nav details_trainee_tab nav-tabs myTab" role="tablist"
                    style="display: flex; justify-content: center; align-items: center;">
                    <li class="nav-item nav-li-padding" role="presentation">
                        <a class="nav-link active" id="trainee_tab_item" data-toggle="tab" href="#case_general_information"
                            role="tab" aria-controls="home" aria-selected="true">মামলার সাধারণ <br> তথ্য</a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="table-responsive ajax-data-container">
                    <div class="tab-content" id="myTabContent">
                        {{-- start মামলার সাধারণ তথ্য --}}
                        <div class="tab-pane active" id="case_general_information" role="tabpanel"
                            aria-labelledby="home-tab">
                            <form id="administrativeTribrunalGeneralInfoForm" action="javascript:void(0)" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row_int">
                                    <div class="col-lg-12">
                                        <fieldset class="mb-8">
                                            <!-- <legend> মামলার সাধারণ তথ্য</legend> -->
                                            <div class="form-group row">
                                                <input type="hidden" name="court" id="court" value="3">

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলার শ্রেণী/কেস-টাইপ <span class="text-danger">*</span></label>
                                                    <div id="CaseCategorDiv">
                                                        <input type="text" name="case_category_type" id="case_category_type" class="form-control"
                                                            value="এএটি" readonly/>
                                                        <span class="text-danger d-none vallidation-message">This field can
                                                            not be empty</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলা নং <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_no" id="case_no"
                                                        class="form-control form-control-sm"
                                                        placeholder="(type digits in English)" required="required"
                                                        onkeypress="return allowBanglaAndEnglishNumerals(event)">
                                                    <input type="hidden" name="caseId" value="">
                                                    <span class="text-danger d-none vallidation-message">This field can not
                                                        be empty</span>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>বছর <span class="text-danger">*</span></label>
                                                    <input type="text" name="case_year" id="case_year"
                                                        class="form-control form-control-sm common_yearpicker"
                                                        placeholder="বছর" autocomplete="off" required="required">
                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>


                                                {{-- <div class="col-lg-4">
                                                    <table width="100%" border="1" id="highcourtAdalatDiv"
                                                        class="mb-5" style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th class="other_bibadi_name other_respondent">আদালতের নাম
                                                                (Justice Name)<span class="text-danger">*</span>
                                                            </th>
                                                            <th width="50">
                                                                <a href="javascript:void();" id="addHighcourtAdalatRow"
                                                                    class="btn btn-sm btn-primary font-weight-bolder pr-2">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                </a>
                                                            </th>
                                                        </tr>
                                                        <tr></tr>
                                                    </table>
                                                </div> --}}

                                                <div class="col-lg-4 mb-5">
                                                    <label>আদালতের নাম </label>
                                                    <div id="adalat_name">
                                                        <input type="text" name="adalat_name" id="adalat_name" class="form-control"
                                                            value="প্রশাসনিক আপিল ট্রাইব্যুনাল" readonly/>
                                                        <span class="text-danger d-none vallidation-message">This field can
                                                            not be empty</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 mb-5">
                                                    <label>মামলা দায়েরের তারিখ <span class="text-danger">*</span></label>
                                                    <input type="text" name="notice_given_date" id="notice_given_date"
                                                        class="form-control form-control-sm  common_datepicker"
                                                        placeholder="দিন/মাস/বছর" autocomplete="off" required="required">

                                                    <span class="text-danger d-none vallidation-message">This field can
                                                        not be empty</span>
                                                </div>


                                                <div class="col-lg-4 mb-5">
                                                    <label>আপিলকারী
                                                        <span class="text-danger">*</span></label>

                                                    <div class="" id="appeallateOffice">
                                                        <select name="appeal_office" id="appeallateOffice"
                                                            class="form-control form-control-sm" required="required">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($ministrys as $value)
                                                                <option value="{{ $value->doptor_office_id }}"
                                                                    {{ old('appeal_office') == $value->doptor_office_id }}>
                                                                    {{ $value->office_name_bn }} </option>
                                                            @endforeach
                                                            <option value="0">অন্যান্য</option>
                                                        </select>
                                                        <span class="text-danger d-none vallidation-message">This field
                                                            can not be empty</span>
                                                        <input type="text" name="appeal_petitioner_name"
                                                            id="appeal_petitioner_name"
                                                            class="form-control form-control-sm d-none"
                                                            placeholder="আপিলকারীর নাম লিখুন">
                                                    </div>
                                                </div>



                                                <div class="col-lg-12 mb-5">
                                                    <table width="100%" border="1" id="advocateLawerDiv"
                                                        style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th class="col-lg-6">সংশ্লিষ্ট আইন কর্মকর্তা <span
                                                                    class="text-danger">*</span></th>
                                                            <th class="col-lg-6">সংশ্লিষ্ট প্যানেল আইনজীবীর নাম <span
                                                                    class="text-danger">*</span>
                                                                <!-- Information icon button -->
                                                                <span class="tooltip-icon">
                                                                    <i class="fas fa-info-circle tooltip-button"></i>
                                                                    <span class="tooltip-text">প্যানেল আইনজীবীর নাম তালিকায়
                                                                        না থাকলে তার নামে যুক্ত করার জন্য নতুন ইউজার আইডি
                                                                        সৃজন করুন।</span>
                                                                </span>
                                                            </th>

                                                            <th width="30">
                                                                <a href="javascript:void(0);" id="addAdvocateLawer"
                                                                    class="btn btn-sm btn-primary pr-2"><i
                                                                        class="fas fa-plus-circle"></i></a>
                                                            </th>
                                                        </tr>
                                                        <tr></tr>
                                                    </table>
                                                    <input type="hidden" id="survey_count" value="1">
                                                </div>

                                                <!-- jQuery -->
                                                {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

                                                {{-- <div class="col-lg-12 mb-5">
                                                    <table width="100%" border="1" id="badiDiv"
                                                        style="border:1px solid #dcd8d8;">
                                                        <tr>
                                                            <th>পিটিশনারের নাম <span class="text-danger">*</span> </th>

                                                            <th>পিটিশনারের ঠিকানা <span class="text-danger"></span></th>

                                                        </tr>

                                                    </table>
                                                </div> --}}


                                                {{-- <div class="col-lg-12" style="display: flex;">
                                                    <div class="col-lg-5 mb-5">
                                                        <label>মোট পিটিশনারের সংখ্যা</label>
                                                        <select name="total_badi_number" id="total_badi_number"
                                                            class="form-control form-control-sm">
                                                            <option value="">মোট পিটিশনারের সংখ্যা নির্বাচন করুন
                                                            </option>
                                                            <?php
                                                            for ($i = 1; $i <= 1000; $i++) {
                                                                echo "<option value='$i'>$i</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <input type="hidden" name="caseId" value="">
                                                    </div>

                                                    <div class="col-lg-7 mb-5 product-image">

                                                        <table width="100%" border="1" id="bibadiDiv"
                                                            class="mb-5" style="border:1px solid #dcd8d8;">
                                                            <tr>
                                                                <th class="other_bibadi_name other_respondent"
                                                                    colspan="2">
                                                                    রেসপন্ডেন্ট তালিকা
                                                                    <span class="tooltip-icon">
                                                                        <i class="fas fa-info-circle tooltip-button"></i>
                                                                        <span class="tooltip-text">ড্রপডাউন থেকে অফিস
                                                                            সিলেক্ট করুণ। কোন অফিস না পেলে অন্যান্য সিলেক্ট
                                                                            করে নাম লিখুন</span>
                                                                    </span>
                                                                </th>
                                                                <th width="50">
                                                                    <a href="javascript:void();" id="addBibadiRow"
                                                                        class="btn btn-sm btn-primary font-weight-bolder pr-2"
                                                                        onclick="addBibadiRowFunc()">
                                                                        <i class="fas fa-plus-circle"></i>
                                                                    </a>
                                                                </th>
                                                            </tr>
                                                        </table>
                                                    </div>


                                                    <!-- Hidden input for "অন্যান্য" -->

                                                </div> --}}


                                                <div class="col-lg-12 mb-5">
                                                    <label>বিষয়বস্তু(সংক্ষিপ্ত) </label>
                                                    <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3" spellcheck="false"></textarea>
                                                </div>

                                                <div class="col-lg-6 mt-5 mb-5">
                                                    <label>মামলা নং(যে মামলা হতে উদ্ভূত তার মামলা নং) </label>
                                                    <div class="" id="">
                                                        <select name="case_number_at_origin" id=""
                                                            class="form-control form-control-sm">
                                                            <option value="">-- নির্বাচন করুন --</option>
                                                            @foreach ($atCase as $value)
                                                                <option value="{{ $value->id }}"
                                                                    {{ old('case_number_at_origin') == $value->id ? 'selected' : '' }}>
                                                                    {{ $value->case_no }}/{{ $value->case_year }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- <div class="col-lg-6 mb-5">
                                                    <label>মামলা সংশ্লিষ্ট অর্থের পরিমান</label>(যদি আর্থিক সংশ্লেষ থাকে বা
                                                    সরকারি অর্থ ব্যয়/প্রদানের বিষয় থাকে অথবা মামলাভুক্ত সম্পত্তির সম্ভাব্য
                                                    মূল্য ইত্যাদি)
                                                    <input name="money_amount" class="form-control" id="money_amount"
                                                        rows="1" spellcheck="false"></input>
                                                </div> --}}



                                                {{-- starting সংযুক্তি  --}}
                                                <div class="col-md-12">
                                                    <fieldset class="">
                                                        <div
                                                            class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                            <div class="d-flex align-items-center mr-2 py-2">
                                                                <h3 class="mb-0 mr-8">নোটিশ/আরজির কপি সংযুক্ত করুন

                                                                    <sub class="text-danger">(PDF, সর্বোচ্চ সাইজ :
                                                                        5MB)</sub>
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
                                                                <tr></tr>
                                                            </table>
                                                            <input type="hidden" id="other_attachment_count"
                                                                value="1">
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                {{-- end সংযুক্তি --}}
                                            </div>
                                        </fieldset>
                                        {{-- </div> --}}

                                        <!--end::Card-->
                                    </div>
                                </div>
                                <div class="form-footer" style="display: flex;justify-content: center;">
                                    <button type="submit" id="appealAdministrativeTribrunalGeneralInfoSaveBtn"
                                        class="submit-button">সংরক্ষণ</button>
                                </div>
                            </form>
                        </div>
                        {{-- ---------- end মামলার সাধারণ তথ্য----------- --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#select2Dropdown').select2();
        });
    </script>

<script>
    $(document).ready(function() {
        $('#case_year, #case_no, #case_category_type').change(function() {
            var caseNo = $('#case_no').val();
            var caseYear = $('#case_year').val();
            var caseCategory = $('#case_category_type').val();

            if (caseNo && caseYear && caseCategory) {
                $.ajax({
                    url: "{{ route('cabinet.case.appeal-administrative-check-case-no') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'case_no': caseNo,
                        'case_year': caseYear,
                        'case_category_type': caseCategory
                    },
                    success: function(data) {
                        if (data.exists) {
                            Swal.fire({
                                icon: 'error',
                                title: '<span style="color: red;font-size: larger;">দুঃখিত...',
                                html: '<strong>মামলাটি <span style="color: red;font-size: larger;">' +
                                    data.officeName +
                                    '</span> কর্তৃক মূল রেসপন্ডেন্ট হিসেবে ইতিমধ্যে এন্ট্রি করা হয়েছে!</strong>',
                                showCancelButton: false,
                                showConfirmButton: false,
                            });
                        }
                    }
                });
            }
        });
    });
</script>


    @include('gov_case.appeal_administritive_tribrunal.create_new_appeal_administritive_tribrunal_js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

@endsection
