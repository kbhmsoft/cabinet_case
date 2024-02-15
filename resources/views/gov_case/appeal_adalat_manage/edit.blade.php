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

            <form id="editForm" action="{{ route('cabinet.appeal-maintain.update', $adalatManagement->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <fieldset>
                        <legend>আদালতের তথ্য</legend>
                        <div class="col-12 row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">আদালতের নাম <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="আদালতের নাম লিখুন"
                                        class="form-control form-control-sm" value="{{ $adalatManagement->name }}">
                                    <span style="color: red">
                                        {{ $errors->first('name') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">স্ট্যাটাস</label>
                                    <select name="status" id="status" class="form-control form-control-sm">
                                        <option value="1" @if ($adalatManagement->status == 1) selected @endif>এনাবল
                                        </option>
                                        <option value="0" @if ($adalatManagement->status == 0) selected @endif>ডিজেবল
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <!-- Call showConfirmation() function on button click -->
                                <button type="button" onclick="showConfirmation()"
                                    class="btn btn-primary font-weight-bold mr-2">সংরক্ষণ করুন</button>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function showConfirmation() {
        Swal.fire({
            title: 'আপনি কি আদালতের তথ্য আপডেট করতে চান?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'হ্যা',
            cancelButtonText: 'না'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user clicks OK, submit the form
                document.getElementById('editForm').submit();
            }
        });
    }
</script>

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





            //   console.log(searchParams.get('office_type')); // true


            // Level Wise Office
            jQuery('select[name="office_type"]').on('change', function() {
                var dataID = jQuery(this).val();
                jQuery("#office_id").after('<div class="loadersmall"></div>');
                if (dataID) {
                    jQuery.ajax({
                        url: '/cabinet/office/dropdownlist/getdependentoffice/' + dataID,
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

                        // jQuery('select[name="office_id"]').html(
                        //     '<div class="loadersmall"></div>');
                        // jQuery('select[name="office_id"]').html(
                        //     '<option value="">-- অফিস নির্বাচন করুন --</option>');
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
@endsection
