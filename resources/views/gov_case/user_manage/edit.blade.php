@extends('layouts.cabinet.cab_default')

@section('content')
    <style type="text/css">
        #appRowDiv td {
            padding: 5px;
            border-color: #ccc;
        }

        #appRowDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            width: 250px;
        }


        .select2-container .select2-selection--single {
            box-sizing: border-box;
            height: 41px;
            font-size: 1.2rem
        }
    </style>
    <!--begin::Card-->
    <div class="row">
        <div class="card card-custom col-12">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
                </div>
                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 6)
                    <div class="card-toolbar">
                        <a href="{{ url('user-management') }}" class="btn btn-sm btn-primary font-weight-bolder">
                            <i class="la la-list"></i> ব্যবহারকারীর তালিকা
                        </a>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form action="{{ route('cabinet.user-management.update', $userManagement->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <fieldset>
                        <legend>ব্যবহারকারীর তথ্য</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">পুরো নাম <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="পুরো নাম লিখুন"
                                        class="form-control form-control-sm" value="{{ $userManagement->name }}">
                                    <span style="color: red">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mobile_no" class="form-control-label">মোবাইল নাম্বার</label>
                                    <input type="text" name="mobile_no" id="mobile_no" placeholder="মোবাইল নাম্বার লিখুন"
                                        class="form-control form-control-sm" value="{{ $userManagement->mobile_no }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ইমেইল এড্রেসঃ</label>
                                    <input type="text" name="email" class="form-control" placeholder=""
                                        value="{{ $userManagement->email }}" />
                                </div>
                            </div>
                        </div>
                        {{-- @dd($userManagement) --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role_id" class="form-control-label">ইউজার রোল <span
                                            class="text-danger">*</span></label>
                                    <select name="role_id" id="role_id" class="form-control-sm form-control">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($roles as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $userManagement->role_id ? 'selected' : '' }}>
                                                {{ $value->name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span style="color: red">{{ $errors->first('role_id') }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>অফিস লেভেল</label>
                                    <select name="office_type" id="office_type" class="form-control">
                                        <option value="">-বিভাগ নির্বাচন করুন-</option>
                                        @foreach ($office_types as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $userManagement->office_type ? 'selected' : '' }}>
                                                {{ $value->type_name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4" id="selectMinDiv" style="display: none;">
                                <div class="form-group">
                                    <label>মন্ত্রণালয়/বিভাগ</label>
                                    <select name="ministry" id="ministry" class="form-control">
                                        <option value="">-মন্ত্রণালয়/বিভাগ নির্বাচন করুন-</option>
                                        @foreach ($ministries as $value)
                                            <option value="{{ $value->doptor_office_id }}"
                                                {{ $value->doptor_office_id == $userManagement->ministry ? 'selected' : '' }}>
                                                {{ $value->office_name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4" style="display: none;">
                                <div class="form-group">
                                    <label>বিভাগীয় প্রশাসন</label>
                                    <select name="divOffice" id="divOffice" class="form-control">
                                        <option value="">- বিভাগীয় প্রশাসন নির্বাচন করুন-</option>
                                        @foreach ($divOffices as $value)
                                            <option value="{{ $value->doptor_office_id }}"
                                                {{ $value->doptor_office_id == $userManagement->div_office ? 'selected' : '' }}>
                                                {{ $value->office_name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>অফিস</label>
                                    <select name="office_id" id="office_id" class="form-control-sm form-control">
                                        @foreach ($offices as $value)
                                            <option value="{{ $value->doptor_office_id }}"
                                                {{ $value->doptor_office_id == $userManagement->office_id ? 'selected' : '' }}>
                                                {{ $value->office_name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span style="color: red">{{ $errors->first('office_id') }}</span>
                                </div>
                            </div>
                        </div>

                        @can('password_change_access_user_list')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>নতুন পাসওয়ার্ড</label>
                                        <input id="new_password" type="password" class="form-control"
                                            placeholder="নতুন পাসওয়ার্ড" name="new_password" autocomplete="current-password"
                                            onkeyup="CheckPassword(this)">
                                        <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"
                                            onclick="myFunctionNew()"></span>
                                        <div id="passwordValidation" style="color:red"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>নতুন কনফার্ম পাসওয়ার্ড</label>
                                        <input id="new_confirm_password" type="password" class="form-control"
                                            placeholder="নতুন কনফার্ম পাসওয়ার্ড" name="new_confirm_password"
                                            autocomplete="current-password">
                                        <span toggle="#password" class="fa fa-fw fa-eye field_icon toggle-password"
                                            onclick="myFunctionConfirm()"></span>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-control-label">স্বাক্ষরের স্ক্যান কপি সংযুক্তি <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="signature" class="custom-file-input"
                                            id="customFile" />
                                        <label class="custom-file-label" for="customFile">ফাইল নির্বাচন করুন</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-control-label">প্রোফাইল ইমেজ সংযুক্তি <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="pro_pic" class="custom-file-input"
                                            id="customFile" />
                                        <label class="custom-file-label" for="customFile">ফাইল নির্বাচন করুন</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary font-weight-bold">সংরক্ষণ করুন</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <!--end::Card-->
    <style>
        .select2-container .select2-selection--single {
            height: 37px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 25px !important;
        }
    </style>
@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
    <script type="text/javascript">
        $('#office_id').select2();
    </script>
    <!--end::Page Scripts-->

    <script type="text/javascript">
        jQuery(document).ready(function() {
            $('#ministry, #divOffice').select2();

            function toggleSelectDivs(officeType) {
                console.log(officeType);
                if (officeType == 2) {
                    $('#selectMinDiv').show();
                    $('#divOffice').hide();
                    $('#divOffice').val('');
                } else if (officeType == 4) {
                    $('#divOffice').show();
                    $('#selectMinDiv').hide();
                    $('#ministry').val('');
                } else {
                    $('#divOffice, #selectMinDiv').hide();
                    $('#ministry, #divOffice').val('');
                }
            }
            jQuery(document).ready(function() {
                const officeTypeValue = $('#office_type').val(); // Get the initial office type value
                toggleSelectDivs(officeTypeValue); // Pass it to the toggle function
            });

            jQuery('select[name="office_type"]').on('change', function() {
                toggleSelectDivs($(this).val());
            });

            const searchParams = new URLSearchParams(window.location.search);
            toggleSelectDivs(searchParams.get('office_type'));

            function loadOfficeData(url, targetSelect, selectedID) {
                $(targetSelect).after('<div class="loadersmall"></div>');
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $(targetSelect).html('<option value="">-- অফিস নির্বাচন করুন --</option>');
                        $.each(data, function(key, value) {
                            let selected = key == selectedID ? 'selected' : '';
                            $(targetSelect).append(
                                `<option value="${key}" ${selected}>${value}</option>`);
                        });
                        $('.loadersmall').remove();
                    },
                    error: function() {
                        $('.loadersmall').remove();
                    }
                });
            }

            jQuery('select[name="office_type"]').on('change', function() {
                let dataID = $(this).val();
                if (dataID) loadOfficeData(`/cabinet/office/dropdownlist/getdependentoffice/${dataID}`,
                    'select[name="office_id"]');
                else $('select[name="office_id"]').empty();
            });

            jQuery('select[name="ministry"], select[name="divOffice"]').on('change', function() {
                let dataID = $(this).val();
                if (dataID) loadOfficeData(`/cabinet/office/dropdownlist/getdependentchildoffice/${dataID}`,
                    'select[name="office_id"]');
                else $('select[name="office_id"]').empty();
            });

            if (typeof officeTypeID !== "undefined") {
                loadOfficeData(`/cabinet/office/dropdownlist/getdependentoffice/${officeTypeID}`,
                    'select[name="office_id"]', officeID);
            }
            if (minId) loadOfficeData(`/cabinet/office/dropdownlist/getdependentchildoffice/${minId}`,
                'select[name="office_id"]', officeID);
            if (dicOfficeID) loadOfficeData(`/cabinet/office/dropdownlist/getdependentchildoffice/${dicOfficeID}`,
                'select[name="office_id"]', officeID);
        });



        // for password vallidation
        function CheckPassword(inputtxt) {
            var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{7,20}$/;
            var message = [];

            if (!inputtxt.value.match(passw)) {
                if (!inputtxt.value.match(/^(?=.*\d)/)) {
                    message.push("কমপক্ষে একটি সংখ্যাসূচক থাকতে হবে");
                }
                if (!inputtxt.value.match(/^(?=.*[a-z])/)) {
                    message.push("কমপক্ষে একটি ছোট হাতের অক্ষর থাকতে হবে");
                }
                if (!inputtxt.value.match(/^(?=.*[A-Z])/)) {
                    message.push("কমপক্ষে একটি বড় হাতের অক্ষর থাকতে হবে");
                }
                if (!inputtxt.value.match(/^(?=.*[^a-zA-Z0-9])/)) {
                    message.push("কমপক্ষে একটি বিশেষ ক্যারেক্টার থাকতে হবে");
                }
                if (inputtxt.value.length < 8 || inputtxt.value.length > 20) {
                    message.push("পাসওয়ার্ডের দৈর্ঘ্য 8 থেকে 20 অক্ষরের মধ্যে হওয়া উচিত");
                }

                $("#passwordValidation").html(message.join(', <br>'));
                return false;
            } else {
                $("#passwordValidation").html("");
                return true;
            }
        }



        function myFunctionNew() {
            var x = document.getElementById("new_password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function myFunctionConfirm() {
            var x = document.getElementById("new_confirm_password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection
