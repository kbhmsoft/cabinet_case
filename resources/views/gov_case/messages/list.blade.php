@php
    $roleID = Auth::user()->role_id;
    $officeInfo = user_office_info();
@endphp

@section('styles')
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

        .nav-tabs .nav-item {
            margin-bottom: -1px;
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            transition: color 0.3s, background-color 0.3s, border-color 0.3s;
        }

        .nav-tabs .nav-link:hover {
            background-color: #faf8fa;
            border-color: #000000 #000000 #000000;
        }

        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .tab-content {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-top: none;
            background-color: #fff;
        }

        .w-30 {
            width: 30% !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            width: 290px;
        }

        .form-inline .form-control {
            width: 100%;
        }

        .form-inline .form-group {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .form-inline {
                display: block;
            }

            .form-inline .form-group {
                width: 100%;
            }
        }

        .form-group label {
            font-size: 1.2rem;
            font-weight: bold;
            margin-left: 1rem;
            padding-top: 10px;
        }

        .office-list-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }

        .office-list-item input[type="checkbox"] {
            margin-right: 30rem;
        }

        .scrollable-container {
            margin-top: 2rem;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .scrollable-tbody {
            display: block;
        }

        .scrollable-tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .scrollable-tbody td {
            border-bottom: 1px solid #ccc;
            padding: 5px;
        }

        .mb-15,
        .my-15 {
            margin-bottom: 0rem !important;
        }

        .table-container {
            max-height: 300px;
            /* Adjust as needed */
            overflow-y: auto;
        }

        #userTable {
            width: 100%;
        }

        .striped-table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .checkbox-container {
            display: block;
            position: relative;
            padding-left: 25px;
            /* margin-bottom: 12px; */
            cursor: pointer;
            text-align: center;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 18px;
            width: 18px;
            background-color: #eee;
            border-radius: 3px;
        }

        .checkbox-container:hover input~.checkmark {
            background-color: #ccc;
        }

        .checkbox-container input:checked~.checkmark {
            background-color: #2196F3;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked~.checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        .office-list-scrollable {
            max-height: 200px;
            overflow-y: auto;
        }

        .office-list-scrollable ul {
            list-style-type: none;
            padding: 0;
        }

        .office-list-scrollable li {
            cursor: pointer !important;
            padding: 5px 0;
        }

        .office-list-scrollable li:hover {
            background-color: #f0f0f0;

        }

        .office-list-scrollable li label {
            user-select: text;
        }

        .custom-radio {
            display: none;
        }

        .custom-label {
            position: relative;
            padding-left: 35px;
            margin-right: 20px;
            cursor: pointer;
            font-size: 16px;
            user-select: none;
        }

        .custom-label::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 22px;
            width: 23px;
            background-color: #ffffff;
            border: 2px solid #007bff;
            border-radius: 50%;
        }

        .custom-radio:checked+.custom-label::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 7px;
            transform: translateY(-50%);
            height: 10px;
            width: 10px;
            background-color: #007bff;
            border-radius: 50%;
        }
    </style>
@endsection

@extends('layouts.cabinet.cab_default')

@section('content')

    <div class="d-flex flex-column pt-5 bgi-size-cover bgi-no-repeat rounded-top" style="background-color: #ffffff">
        <div class="container">
            <div class="form-check form-check-inline">
                <input class="custom-radio" type="radio" name="tabOptions" id="respondentAllUser" value="respondentAll"
                    checked>
                <label class="custom-label" for="respondentAllUser">সকলকে পাঠান</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="custom-radio" type="radio" name="tabOptions" id="respondentOfficeUser"
                    value="messageOffice">
                <label class="custom-label" for="respondentOfficeUser">অফিসওয়ারী পাঠান</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="custom-radio" type="radio" name="tabOptions" id="respondentPersonUser" value="messageOne">
                <label class="custom-label" for="respondentPersonUser">পার্সনওয়ারী পাঠান</label>
            </div>
        </div>

        {{-- -------------------------- start সকলকে পাঠান ---------------------------------- --}}
        <form id="messageSendingForm" action="javascript:void(0)" class="form" method="POST">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="respondentAll" role="tabpanel">
                    <!-- Content for সকলকে পাঠান -->
                    <div class="col-md-12">
                        <fieldset>
                            <legend class="font-weight-bold text-center">বার্তা প্রেরণ</legend>
                            <div class="col-12 row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">বার্তা তৈরি করুন <span
                                                class="text-danger">*</span></label>
                                        <textarea name="messages" id="" class="form-control form-control-sm" rows="10"></textarea>
                                        <span style="color: red">{{ $errors->first('messages') }}</span>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary font-weight-bold mr-2">প্রেরণ
                                        করুন</button>
                                </div>
                            </div> --}}
                        </fieldset>
                    </div>
                </div>

                {{-- ------------------------------- End সকলকে পাঠান ------------------------------------- --}}

                {{-- ------------------------------- Strat অফিসওয়ারী পাঠান ------------------------------------- --}}

                <div class="tab-pane fade" id="messageOffice" role="tabpanel" aria-labelledby="messageOffice-tab">

                    <div class="d-flex justify-space-between mb-15">
                        <div class="form-group mb-2 mr-2">
                            <select name="office_type" id="office_type" class="form-control">
                                <option value="">-বিভাগ নির্বাচন করুন-</option>
                                @foreach ($office_types as $value)
                                    <option
                                        value="{{ $value->id }}"{{ (isset($_GET['office_type']) ? $_GET['office_type'] : '') == $value->id ? 'selected' : '' }}>
                                        {{ $value->type_name_bn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-2 mr-2" id="selectMinDiv" style="display: none;">
                            <select name="ministry" id="ministry" class="form-control">
                                <option value="">-মন্ত্রণালয়/বিভাগ নির্বাচন করুন-</option>
                                @foreach ($ministries as $value)
                                    <option
                                        value="{{ $value->doptor_office_id }}"{{ (isset($_GET['ministry']) ? $_GET['ministry'] : '') == $value->doptor_office_id ? 'selected' : '' }}>
                                        {{ $value->office_name_bn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-2 mr-2" id="selectDivisionDiv" style="display: none;">
                            <select name="divOffice" id="divOffice" class="form-control">
                                <option value="">- বিভাগীয় প্রশাসন নির্বাচন করুন-</option>
                                @foreach ($divOffices as $value)
                                    <option
                                        value="{{ $value->doptor_office_id }}"{{ (isset($_GET['divOffice']) ? $_GET['divOffice'] : '') == $value->doptor_office_id ? 'selected' : '' }}>
                                        {{ $value->office_name_bn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="{{ asset('js/custom-script.js') }}"></script>
                    <script>
                        $(document).ready(function() {
                            function fetchOffices() {
                                var office_type = $('#office_type').val();
                                var ministry = $('#ministry').val();
                                var divOffice = $('#divOffice').val();

                                // Check if any dropdown value is selected
                                if (office_type || ministry || divOffice) {
                                    // alert('{{ route('cabinet.fetchoffices') }}');
                                    $.ajax({
                                        url: '{{ route('cabinet.fetchoffices') }}',
                                        type: 'GET',
                                        data: {
                                            office_type: office_type,
                                            ministry: ministry,
                                            divOffice: divOffice
                                        },
                                        success: function(data) {
                                            $('#office_list').html('');

                                            $.each(data.offices, function(index, office) {
                                                $('#office_list').append(
                                                    '<li class="office-list-item ">' +
                                                    '<input type="checkbox" name="selected_office_ids[]" id="office_id_' +
                                                    office.id + '" value="' + office.id + '">' +
                                                    '<label for="office_id_' + office.id + '">' + office
                                                    .office_name_bn + '</label>' +
                                                    '</li>'
                                                );
                                            });
                                        }
                                    });
                                } else {
                                    $('#office_list').html('');
                                }
                            }

                            $('#office_type, #ministry, #divOffice').on('change', function() {
                                fetchOffices();
                            });

                            fetchOffices();
                        });
                    </script>

                    <div class="form-group striped-table">
                        <div class="office-list-container mt-5 border p-3 rounded mb-5 bg-white shadow ">
                            <label for="office_id" class="office-item text-primary">অফিস নির্বাচন করুন:</label>
                            <div class="office-list-scrollable">
                                <ul class="list-unstyled" id="office_list">
                                    @foreach ($offices as $office)
                                        <li class="office-list-item">
                                            <input type="checkbox" name="selected_office_ids[]"
                                                id="office_id_{{ $office->doptor_office_id }}"
                                                value="{{ $office->doptor_office_id }}"{{ (isset($_GET['office_id']) ? $_GET['office_id'] : '') == $office->doptor_office_id ? 'checked' : '' }}>
                                            <label
                                                for="office_id_{{ $office->doptor_office_id }}">{{ $office->office_name_bn }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <fieldset>
                            <legend class="font-weight-bold text-center">বার্তা প্রেরণ</legend>
                            <div class=" col-12 row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class=" form-control-label">বার্তা তৈরি করুন <span
                                                class="text-danger">*</span></label>
                                        <textarea name="messages_office" id="" class="form-control form-control-sm" rows="10"></textarea>
                                        <span style="color: red">
                                            {{ $errors->first('messages_office') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                    {{-- ------------------------------- End অফিসওয়ারী পাঠান ------------------------------------- --}}
                </div>


                {{-- ------------------------------- Strat পার্সনওয়ারী পাঠান ------------------------------------- --}}

                <div class="tab-pane fade" id="messageOne" role="tabpanel" aria-labelledby="messageOne-tab">
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="form-group mb-2 mr-2">
                            <select name="office_type_personwise" id="office_type_pw" class="form-control">
                                <option value="">-বিভাগ নির্বাচন করুন-</option>
                                @foreach ($office_types as $value)
                                    <option
                                        value="{{ $value->id }}"{{ (isset($_GET['office_type']) ? $_GET['office_type'] : '') == $value->id ? 'selected' : '' }}>
                                        {{ $value->type_name_bn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if (Auth::user()->role_id != 29 && Auth::user()->role_id != 31)
                            <div class="form-group mb-2 mr-2" id="selectMinDiv_pw" style="display: none;">
                                <select name="ministry_persionwise" id="ministry_pw" class="form-control">
                                    <option value="">-মন্ত্রণালয়/বিভাগ নির্বাচন করুন-</option>
                                    @foreach ($ministries as $value)
                                        <option
                                            value="{{ $value->doptor_office_id }}"{{ (isset($_GET['ministry']) ? $_GET['ministry'] : '') == $value->doptor_office_id ? 'selected' : '' }}>
                                            {{ $value->office_name_bn }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2 mr-2" id="selectDivisionDiv_pw" style="display: none;">
                                <select name="divOffice_persionwise" id="divOffice_pw" class="form-control">
                                    <option value="">- বিভাগীয় প্রশাসন নির্বাচন করুন-</option>
                                    @foreach ($divOffices as $value)
                                        <option
                                            value="{{ $value->doptor_office_id }}"{{ (isset($_GET['divOffice']) ? $_GET['divOffice'] : '') == $value->doptor_office_id ? 'selected' : '' }}>
                                            {{ $value->office_name_bn }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group mb-2 mr-2">
                            <select name="office_id_persionwise" id="office_id_pw" class="form-control">
                                <option value="">- অফিস নির্বাচন করুন-</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <select name="role_persionwise" class="form-control w-100" id="role">
                                <option value=''>-ইউজার রোল নির্বাচন করুন-</option>
                                @foreach ($user_role as $value)
                                    <option
                                        value="{{ $value->id }}"{{ $value->id == (isset($_GET['role']) ? $_GET['role'] : '') ? 'selected' : '' }}>
                                        {{ $value->name_bn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="table-container mt-5 border p-3 rounded mb-5 bg-white shadow ">
                            <label for="office_id" class="office-item text-primary font-size-h5 mb-3 ml-3"
                                style="font-weight: bold">পার্সন নির্বাচন করুন:</label>
                            <table id="userTable" class="table-hover mb-6 font-size-h6 striped-table">
                                <tbody id="user_list" class="scrollable-tbody">
                                    @if ($users && $users->isEmpty())
                                        <tr>
                                            <td colspan="1">
                                                <p class="no-users-message">--- তথ্য পাওয়া যায়নি ---</p>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($users as $key => $row)
                                            <tr class="striped">
                                                <td>
                                                    <label class="checkbox-container">
                                                        {{ $row->name }}
                                                        <input type="checkbox" name="selected_user_ids[]"
                                                            value="{{ $row->id }}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card card-custom">
                        <div class="col-md-12">
                            <fieldset>
                                <legend class="font-weight-bold text-center">বার্তা প্রেরণ</legend>
                                <div class="col-12 row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">বার্তা তৈরি করুন <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="messages_persons" id="" class="form-control form-control-sm" rows="10"></textarea>
                                            <span style="color: red">
                                                {{ $errors->first('messages_persons') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </div>
                </div>


                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        function filterUsers() {
                            var office_type = $('#office_type_pw').val();
                            var ministry = $('#ministry_pw').val();
                            var divOffice = $('#divOffice_pw').val();
                            var office_id = $('#office_id_pw').val();
                            var role = $('#role').val();

                            // Check if any dropdown value is selected
                            if (office_type || ministry || divOffice || office_id || role) {
                                $.ajax({
                                    url: '{{ route('cabinet.filterUsers') }}',
                                    type: 'GET',
                                    data: {
                                        office_type_pw: office_type,
                                        ministry_pw: ministry,
                                        divOffice_pw: divOffice,
                                        office_id_pw: office_id,
                                        role: role
                                    },
                                    success: function(data) {
                                        // Populate offices
                                        $('#office_list').html('');
                                        $.each(data.offices, function(index, office) {
                                            $('#office_list').append(
                                                '<div class="office-list-item striped">' +
                                                '<input type="checkbox" name="office_id[]" id="office_id_' +
                                                office.id + '" value="' + office.id + '">' +
                                                '<label for="office_id_' + office.id + '">' + office
                                                .office_name_bn + '</label>' +
                                                '</div>'
                                            );
                                        });

                                        // Populate users
                                        $('#user_list').html('');
                                        $.each(data.users, function(index, user) {
                                            $('#user_list').append(
                                                '<tr class="striped">' +
                                                '<td>' +
                                                '<label class="checkbox-container">' +
                                                user.name +
                                                '<input type="checkbox" name="user[]" id="user_id_' +
                                                user.id + '" value="' + user.id + '">' +
                                                '<span class="checkmark"></span>' +
                                                '</label>' +
                                                '</td>' +
                                                '</tr>'
                                            );
                                        });
                                    }
                                });
                            } else {
                                $('#office_list').html('');
                                $('#user_list').html('');
                            }
                        }
                        $('#office_type_pw, #ministry_pw, #divOffice_pw, #office_id_pw, #role').on('change', function() {
                            filterUsers();
                        });
                        filterUsers();
                    });
                </script>
                {{-- ------------------------------- End পার্সনওয়ারী পাঠান ------------------------------------- --}}
            </div>
            <div class="container mt-4">
                <button type="submit" id="messageSendingSaveBtn" class="btn btn-primary">প্রেরণ করুন</button>
            </div>
        </form>

    </div>

@endsection


@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('scripts')
    <!--end::Page Scripts-->
    @if (request()->get('office_type'))
        <script>
            var officeTypeID = {{ request()->get('office_type') }};
        </script>
    @else
        <script>
            var officeTypeID = 0;
        </script>
    @endif

    @if (request()->get('ministry'))
        <script>
            var minId = {{ request()->get('ministry') }};
        </script>
    @else
        <script>
            var minId = 0;
        </script>
    @endif
    @if (request()->get('ministry_pw'))
        <script>
            var minId = {{ request()->get('ministry_pw') }};
        </script>
    @else
        <script>
            var minId = 0;
        </script>
    @endif
    @if (request()->get('divOffice'))
        <script>
            var dicOfficeID = {{ request()->get('divOffice') }};
        </script>
    @else
        <script>
            var dicOfficeID = 0;
        </script>
    @endif
    @if (request()->get('divOffice_pw'))
        <script>
            var dicOfficeID = {{ request()->get('divOffice_pw') }};
        </script>
    @else
        <script>
            var dicOfficeID = 0;
        </script>
    @endif
    @if (request()->get('office_id'))
        <script>
            var officeID = {{ request()->get('office_id') }};
        </script>
    @else
        <script>
            var officeID = 0;
        </script>
    @endif
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $('#ministry').select2();
            $('#ministry_pw').select2();
            $('#divOffice').select2();
            $('#divOffice_pw').select2();
            $('#office_id').select2();


            jQuery('select[name="office_type"]').on('change', function() {
                var officeType = jQuery(this).val();
                // alert(officeType);
                if (officeType == 2) {
                    $('#selectMinDiv').show();
                    $('#selectMinDiv_pw').show();
                    $('#selectDivisionDiv').hide();
                    $('#selectDivisionDiv_pw').hide();
                    $('#divOffice').val('');
                    $('#divOffice_pw').val('');
                } else if (officeType == 4) {
                    $('#selectDivisionDiv').show();
                    $('#selectDivisionDiv_pw').show();
                    $('#selectMinDiv').hide();
                    $('#selectMinDiv_pw').hide();
                    $('#ministry').val('');
                    $('#ministry_pw').val('');
                } else {
                    $('#selectDivisionDiv_pw').hide();
                    $('#selectMinDiv').hide();
                    $('#selectMinDiv_pw').hide();
                    $('#ministry').val('');
                    $('#ministry_pw').val('');
                    $('#divOffice').val('');
                    $('#divOffice_pw').val('');
                }
            });


            const searchParams = new URLSearchParams(window.location.search);
            var officeType = searchParams.get('office_type')
            if (officeType == 2) {
                $('#selectMinDiv').show();
                $('#selectMinDiv_pw').show();
                $('#selectDivisionDiv_pw').hide();
                $('#divOffice').val('');
                $('#divOffice_pw').val('');
            } else if (officeType == 4) {
                $('#selectDivisionDiv').show();
                $('#selectDivisionDiv_pw').show();
                $('#selectMinDiv').hide();
                $('#selectMinDiv_pw').hide();
                $('#ministry').val('');
                $('#ministry_pw').val('');

            } else {
                $('#ministry_pw').val('');
                $('#divOffice_pw').val('');
                $('#selectDivisionDiv').hide();
                $('#selectDivisionDiv_pw').hide();
                $('#selectMinDiv').hide();
                $('#selectMinDiv_pw').hide();
            }


            jQuery('select[name="office_type_personwise"]').on('change', function() {

                var dataID = jQuery(this).val();

                jQuery("#office_id_pw").after('<div class="loadersmall"></div>');
                if (dataID) {
                    jQuery.ajax({
                        url: '/cabinet/office/dropdownlist/getdependentoffice/' + dataID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            // alert(data);
                            jQuery('select[name="office_id_persionwise"]').html(
                                '<div class="loadersmall"></div>');
                            jQuery('select[name="office_id_persionwise"]').html(
                                '<option value="">-- অফিস নির্বাচন করুন --</option>');
                            jQuery.each(data, function(key, value) {
                                jQuery('select[name="office_id_persionwise"]').append(
                                    '<option value="' + key +
                                    '">' + value + '</option>');
                            });
                            jQuery('.loadersmall').remove();
                        }
                    });
                } else {
                    $('select[name="office_id"]').empty();
                }
            });


            jQuery('select[name="office_type"]').on('change', function() {

                var dataID = jQuery(this).val();
                // alert(dataID);
                jQuery("#office_id").after('<div class="loadersmall"></div>');
                if (dataID) {
                    jQuery.ajax({
                        url: '/cabinet/office/dropdownlist/getdependentoffice/' + dataID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            // alert(data);
                            jQuery('select[name="office_id"]').html(
                                '<div class="loadersmall"></div>');
                            jQuery('select[name="office_id"]').html(
                                '<option value="">-- অফিস নির্বাচন করুন --</option>');
                            jQuery.each(data, function(key, value) {
                                jQuery('select[name="office_id"]').append(
                                    '<option value="' + key +
                                    '">' + value + '</option>');
                            });
                            jQuery('.loadersmall').remove();
                        }
                    });
                } else {
                    $('select[name="office_id"]').empty();
                }
            });

            // Ministry Wise Office
            jQuery('select[name="ministry"]').on('change', function() {
                var dataID = jQuery(this).val();
                jQuery("#office_id").after('<div class="loadersmall"></div>');
                if (dataID) {
                    jQuery.ajax({
                        url: '/cabinet/office/dropdownlist/getdependentchildoffice/' + dataID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            jQuery('select[name="office_id"]').html(
                                '<div class="loadersmall"></div>');
                            jQuery('select[name="office_id"]').html(
                                '<option value="">-- অফিস নির্বাচন করুন --</option>');
                            jQuery.each(data, function(key, value) {
                                jQuery('select[name="office_id"]').append(
                                    '<option value="' + key +
                                    '">' + value + '</option>');
                            });
                            jQuery('.loadersmall').remove();
                        }
                    });
                } else {
                    $('select[name="office_id"]').empty();
                }
            });


            // DivisionOffice Wise Office
            jQuery('select[name="divOffice"]').on('change', function() {
                var dataID = jQuery(this).val();
                // alert(dataID);
                jQuery("#office_id").after('<div class="loadersmall"></div>');
                if (dataID) {
                    jQuery.ajax({
                        url: '/cabinet/office/dropdownlist/getdependentchildoffice/' + dataID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            jQuery('select[name="office_id"]').html(
                                '<div class="loadersmall"></div>');
                            jQuery('select[name="office_id"]').html(
                                '<option value="">-- অফিস নির্বাচন করুন --</option>');
                            jQuery.each(data, function(key, value) {
                                jQuery('select[name="office_id"]').append(
                                    '<option value="' + key +
                                    '">' + value + '</option>');
                                //
                            });
                            jQuery('.loadersmall').remove();
                        }
                    });
                } else {
                    $('select[name="office_id"]').empty();
                }
            });



            var officeTypeID = $('#office_type').find(":selected").val();

            if (officeTypeID !== "undefined") {
                jQuery.ajax({
                    url: '/cabinet/office/dropdownlist/getdependentoffice/' + officeTypeID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        jQuery('select[name="office_id"]').html(
                            '<div class="loadersmall"></div>');
                        jQuery('select[name="office_id"]').html(
                            '<option value="">-- অফিস নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key, value) {
                            if (officeID == key) {
                                var selected = 'selected';
                            } else {
                                var selected = ' ';
                            }
                            jQuery('select[name="office_id"]').append(
                                '<option value="' + key +
                                '"' + selected + '>' + value + '</option>');
                        });
                        jQuery('.loadersmall').remove();
                    }
                });
            } else {
                $('select[name="office_id"]').empty();
                jQuery('select[name="office_id"]').html('<option value="">-- অফিস নির্বাচন করুন --</option>');
            }
            console.log(minId);
            if (minId !== 0) {
                jQuery.ajax({
                    url: '/cabinet/office/dropdownlist/getdependentchildoffice/' + minId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        jQuery('select[name="office_id"]').html(
                            '<div class="loadersmall"></div>');
                        jQuery('select[name="office_id"]').html(
                            '<option value="">-- অফিস নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key, value) {
                            if (officeID == key) {
                                var selected = 'selected';
                            } else {
                                var selected = ' ';
                            }
                            jQuery('select[name="office_id"]').append(
                                '<option value="' + key +
                                '"' + selected + '>' + value + '</option>');
                        });
                        jQuery('.loadersmall').remove();
                    }
                });
            } else {
                $('select[name="office_id"]').empty();
                jQuery('select[name="office_id"]').html('<option value="">-- অফিস নির্বাচন করুন --</option>');
            }

            if (dicOfficeID !== 0) {
                jQuery.ajax({
                    url: '/cabinet/office/dropdownlist/getdependentchildoffice/' + dicOfficeID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        jQuery('select[name="office_id"]').html(
                            '<div class="loadersmall"></div>');
                        jQuery('select[name="office_id"]').html(
                            '<option value="">-- অফিস নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key, value) {
                            if (officeID == key) {
                                var selected = 'selected';
                            } else {
                                var selected = ' ';
                            }
                            jQuery('select[name="office_id"]').append(
                                '<option value="' + key +
                                '"' + selected + '>' + value + '</option>');
                        });
                        jQuery('.loadersmall').remove();
                    }
                });
            } else {
                $('select[name="office_id"]').empty();
                jQuery('select[name="office_id"]').html('<option value="">-- অফিস নির্বাচন করুন --</option>');
            }

        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[name="tabOptions"]').on('change', function() {
                var selectedTab = $(this).val();
                console.log(selectedTab);
                $('.tab-pane').removeClass('show active');
                $('#' + selectedTab).addClass('show active');
            });
        });
    </script>




    <script>
        $(document).ready(function() {
            $('#messageSendingForm').submit(function(e) {
                e.preventDefault();

                $('#messageSendingSaveBtn').addClass('spinner spinner-white spinner-right disabled');
                Swal.fire({
                    title: 'আপনি কি বার্তা প্রেরণ করতে চান?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'হ্যাঁ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(this);
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('cabinet.storeMessage') }}",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                $('#messageSendingSaveBtn').removeClass(
                                    'spinner spinner-white spinner-right disabled');
                                Swal.fire('Saved!', 'বার্তা সফলভাবে প্রেরণ করা হয়েছে',
                                    'success');
                            },
                            error: function(xhr, status, error) {
                                $('#messageSendingSaveBtn').removeClass(
                                    'spinner spinner-white spinner-right disabled');
                                if (xhr.status === 422) {
                                    Swal.fire('সমস্যা...!', xhr.responseJSON.error,
                                        'error');
                                } else {
                                    console.log('Error occurred:', xhr, status, error);
                                    Swal.fire('সমস্যা...!',
                                        'অনুগ্রহ করে সকল ফিল্ড গুলো পূরণ করুন',
                                        'error');
                                }
                            }
                        });
                    } else {
                        $('#messageSendingSaveBtn').removeClass(
                            'spinner spinner-white spinner-right disabled');
                        Swal.fire('Canceled!', 'মামলার সাধারণ তথ্য সংরক্ষণ বাতিল করা হয়েছে',
                            'info');
                    }
                });
            });
        });
    </script>
@endsection
