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
                        <option value="">-বিভাগ নির্বাচন করুন-</option>3
                        @foreach ($office_types as $value)
                            <option
                                value="{{ $value->id }}"{{ (isset($_GET['office_type']) ? $_GET['office_type'] : '') == $value->id ? 'selected' : '' }}>
                                {{ $value->type_name_bn }} </option>
                        @endforeach
                    </select>
                </div>

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

                <div class="form-group mb-2 mr-2">
                    <select name="office_id" id="office_id" class="form-control">
                        <option value="">- অফিস নির্বাচন করুন-</option>3

                    </select>
                </div>
                {{-- <div class="form-group mb-2">
                    <select name="role" class="form-control w-100">
                        <option value=''>-ইউজার রোল নির্বাচন করুন-</option>
                        @foreach ($user_role as $value)
                            <option value="{{ $value->id }}"
                                {{ $value->id == (isset($_GET['role']) ? $_GET['role'] : '') ? 'selected' : '' }}>
                                {{ $value->name }} </option>
                        @endforeach
                    </select>
                </div> --}}

                <button type="submit" class="btn btn-success" id="doptorOfficeSearch">অনুসন্ধান করুন</button>
            </form>


            <table  id="example" class="table table-striped table-bordered" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" width="30">#</th>
                        <th scope="col">পদবি</th>
                        <th scope="col">শাখা</th>
                        <th scope="col">রোল</th>
                        <th scope="col" width="150">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody id="tableBody">

                </tbody>

            </table>

            {{-- {!! $users->links() !!} --}}
        </div>
    </div>
    <!--end::Card-->
@endsection
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
{{-- Includable CSS Related Page --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
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
        jQuery(document).ready(function() {

            $('#ministry').select2();
            $('#divOffice').select2();
            // $('#office_id').select2();

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
            jQuery('select[name="office_type"]').on('change', function() {
                var dataID = jQuery(this).val();
                console.log(dataID);
                jQuery("#office_id").after('<div class="loadersmall"></div>');
                if (dataID) {
                    jQuery.ajax({
                        url: '/cabinet/office/dropdownlist/getdependentoffice/' + dataID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
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

    <script>
        // $(document).ready(function () {

        //     $("#doptorOfficeForm").submit(function (e) {
        //         e.preventDefault();
        //         var formData = $(this).serialize();

        //         $.ajax({
        //             url: "{{ route('doptor.user.manage') }}",
        //             type: "POST",
        //             data: formData,
        //             success: function (response) {

        //                 console.log(response);
        //             },
        //             error: function (xhr, status, error) {

        //                 console.error(xhr.responseText);
        //             }
        //         });
        //     });
        // });
    </script>



    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        // $(document).ready(function() {
        //     $('#doptorOfficeForm').submit(function(e) {
        //         e.preventDefault();
        //         var formData = $(this).serialize();
        //         $.ajax({
        //             type: 'POST',
        //             url: "{{ route('doptor.user.manage') }}",
        //             data: formData,
        //             success: function(response) {
        //                 console.log('Response:', response);
        //                 $('#tableBody').append(response.tableHtml);
        //             },
        //             error: function() {
        //                 console.error('Error fetching data.');
        //             }
        //         });
        //     });
        // });

       $(document).ready(function() {
       $('#doptorOfficeForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: "{{ route('doptor.user.manage') }}",
            data: formData,
            success: function(response) {
                Swal.fire({
                    title: `<h3 class="text-center text-success font-weight-bolder">Auto-Close Alert</h3>`,
                    html: `<h4>I will close in <b></b> milliseconds.</h4>`,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {
                    console.log('Response:', response);
                    $('#tableBody').append(response.tableHtml);
                });
            },
            error: function() {
                console.error('Error fetching data.');
            }
        });
    });
});


// $(document).ready(function() {
//     $('#doptorOfficeForm').submit(function(e) {
//         e.preventDefault();
//         var formData = $(this).serialize();

//         $.ajax({
//             type: 'POST',
//             url: "{{ route('doptor.user.manage') }}",
//             data: formData,
//             success: function(response) {
//                 // Show SweetAlert here
//                 Swal.fire({
//                     title: "Auto-Close Alert",
//                     html: `
//                         <div class="custom-swal-content">
//                             <p>I will close in <b id="timer"></b> milliseconds.</p>
//                             <div class="progress-bar-container">
//                                 <div class="progress-bar"></div>
//                             </div>
//                         </div>
//                     `,
//                     timer: 2500,
//                     onBeforeOpen: () => {
//                         Swal.showLoading();
//                         const timer = Swal.getPopup().querySelector("#timer");
//                         const progressBar = Swal.getPopup().querySelector(".progress-bar");

//                         timerInterval = setInterval(() => {
//                             timer.textContent = `${Math.ceil(Swal.getTimerLeft() / 1000)}`;
//                             const progress = (Swal.getTimerLeft() / 2500) * 100;
//                             progressBar.style.width = `${progress}%`;
//                         }, 100);
//                     },
//                     onClose: () => {
//                         clearInterval(timerInterval);
//                     }
//                 }).then((result) => {
//                     // Continue with your existing code
//                     console.log('Response:', response);
//                     $('#tableBody').append(response.tableHtml);
//                 });
//             },
//             error: function() {
//                 console.error('Error fetching data.');
//             }
//         });
//     });
// });

    </script>
@endsection
