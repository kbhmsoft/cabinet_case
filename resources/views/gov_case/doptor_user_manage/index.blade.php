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
    <div class="card card-custom">

        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h2> {{ $page_title }} </h2>
            </div>
            {{-- <div class="card-toolbar">
                @if (auth()->user()->can('create_new_user'))
                    <a href="{{ route('cabinet.user-management.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
                        <i class="la la-plus"></i>নতুন ইউজার এন্ট্রি
                    </a>
                @else
                    <a href="#" class="btn btn-sm btn-secondary font-weight-bolder">
                        <i class="la la-plus"></i>নতুন ইউজার এন্ট্রি
                    </a>
                @endif
            </div> --}}
        </div>

        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <form class="form-inline" method="POST" id="doptorOfficeForm">
                <div class="form-group mb-2 mr-2">
                    <select name="office_type" id="office_type" class="form-control">
                        <option value="">-দপ্তরের ধরন নির্বাচন করুন-</option>3
                        @foreach ($office_types as $value)
                            <option
                                value="{{ $value->id }}"{{ (isset($_GET['office_type']) ? $_GET['office_type'] : '') == $value->id ? 'selected' : '' }}>
                                {{ $value->type_name_bn }} </option>
                        @endforeach
                    </select>
                </div>
                @if (Auth::user()->role_id != 29 && Auth::user()->role_id != 31)
                    <div class="form-group mb-2 mr-2" id="selectMinDiv" style="display: none;">
                        <select name="ministry" id="ministry" class="form-control">
                            <option value="">-মন্ত্রণালয়/বিভাগ নির্বাচন করুন-</option>3
                            @foreach ($ministries as $value)
                                <option
                                    value="{{ $value->doptor_office_id }}"{{ (isset($_GET['ministry']) ? $_GET['ministry'] : '') == $value->doptor_office_id ? 'selected' : '' }}>
                                    {{ $value->office_name_bn }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 mr-2" id="selectDivisionDiv" style="display: none;">
                        <select name="divOffice" id="divOffice" class="form-control">
                            <option value="">- বিভাগীয় প্রশাসন নির্বাচন করুন-</option>3
                            @foreach ($divOffices as $value)
                                <option
                                    value="{{ $value->doptor_office_id }}"{{ (isset($_GET['divOffice']) ? $_GET['divOffice'] : '') == $value->doptor_office_id ? 'selected' : '' }}>
                                    {{ $value->office_name_bn }} </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="form-group mb-2 mr-2">
                    <select name="office_id" id="office_id" class="form-control">
                        <option value="">- অফিস নির্বাচন করুন-</option>3

                    </select>
                </div>

                <button type="submit" class="btn btn-success" id="doptorOfficeSearch">অনুসন্ধান করুন</button>
            </form>


            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" width="30">#</th>
                        <th scope="col" style="text-align:center;">পদবি</th>
                        <th scope="col" style="text-align:center;">শাখা</th>
                        <th scope="col" style="text-align:center;">বর্তমান কর্মকর্তার নাম</th>
                        <th scope="col" style="text-align:center;">রোল</th>
                        <th scope="col" width="150" style="text-align:center;">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody id="tableBody">

                </tbody>

            </table>

        </div>
    </div>
    <!--end::Card-->
@endsection
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>


{{-- Includable CSS Related Page --}}
@section('styles')
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
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
    @if (request()->get('divOffice'))
        <script>
            var dicOfficeID = {{ request()->get('divOffice') }};
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
        $(document).ready(function() {

            $('#ministry').select2();
            $('#divOffice').select2();
            jQuery('select[name="office_type"]').on('change', function() {
                var officeType = jQuery(this).val();
                // alert(officeType);
                if (officeType == 2) {
                    $('#selectMinDiv').show();
                    $('#selectDivisionDiv').hide();
                    $('#divOffice').val('');
                } else if (officeType == 4) {
                    $('#selectDivisionDiv').show();
                    $('#selectMinDiv').hide();
                    $('#ministry').val('');
                } else {
                    $('#selectDivisionDiv').hide();
                    $('#selectMinDiv').hide();
                    $('#ministry').val('');
                    $('#divOffice').val('');
                }
            });


            const searchParams = new URLSearchParams(window.location.search);
            var officeType = searchParams.get('office_type')
            if (officeType == 2) {
                $('#selectMinDiv').show();
                $('#selectDivisionDiv').hide();
                $('#divOffice').val('');
            } else if (officeType == 4) {
                $('#selectDivisionDiv').show();
                $('#selectMinDiv').hide();
                $('#ministry').val('');

            } else {
                $('#ministry').val('');
                $('#divOffice').val('');
                $('#selectDivisionDiv').hide();
                $('#selectMinDiv').hide();
            }

            // Level Wise Office

            jQuery(document).ready(function($) {
                // Your existing change event handler
                $('select[name="office_type"]').on('change', function() {
                    var dataID = $(this).val();
                    console.log(dataID);
                    $("#office_id").after('<div class="loadersmall"></div>');
                    if (dataID) {
                        $.ajax({
                            url: '/cabinet/office/dropdownlist/getdependentoffice/' +
                                dataID,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                $('select[name="office_id"]').html(
                                    '<option value="">-- অফিস নির্বাচন করুন --</option>'
                                );
                                $.each(data, function(key, value) {
                                    $('select[name="office_id"]').append(
                                        '<option value="' + key + '">' +
                                        value + '</option>');
                                });
                                $('.loadersmall').remove();

                                // Initialize Select2 for the office_id select element
                                $('select[name="office_id"]').select2();
                            }
                        });
                    } else {
                        $('select[name="office_id"]').empty()
                            .select2(); // Clear options and reset Select2
                    }
                });

                // Initialize Select2 for the initial state
                $('select[name="office_id"]').select2();
            });

            // Ministry Wise Office
            jQuery('select[name="ministry"]').on('change', function() {

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var timerInterval;
        $(document).ready(function() {
            $('#doptorOfficeForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                Swal.fire({
                    title: `<h3 class="text-center text-success font-weight-bolder">লোডিং হচ্ছে...</h3>`,
                    html: `<h4>অপেক্ষা করুন...</h4>`,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('doptor.user.manage') }}",
                            data: formData,
                            success: function(response) {
                                clearInterval(timerInterval);
                                Swal.close();
                                console.log('Response:', response);
                                $('#tableBody').append(response.tableHtml);
                            },
                            error: function() {
                                clearInterval(timerInterval);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!',
                                });
                                console.error('Error fetching data.');
                            }
                        });
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                });
            });
        });
    </script>
@endsection
