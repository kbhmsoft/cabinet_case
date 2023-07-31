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
                            <i class="la la-list"></i> ব্যাবহারকারীর তালিকা
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
                        <legend>ব্যাবহারকারীর তথ্য</legend>
                        <div class=" col-12 row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="name" class=" form-control-label">পুরো নাম <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="পুরো নাম লিখুন"
                                        class="form-control form-control-sm"value="{{ $userManagement->name }}">
                                    <span style="color: red">
                                        {{ $errors->first('name') }}
                                    </span>
                                </div>
                            </div>
                            {{-- <div class="col-4">
                     <div class="form-group">
                         <label for="username" class=" form-control-label">ইউজারনেম <span class="text-danger">*</span></label>
                         <input type="text" id="username" name="username" placeholder="ব্যবহারকারীর নাম লিখুন" class="form-control form-control-sm" value="{{ $userManagement->username}}" readonly="readonly">
                         <span style="color: red">
                           {{ $errors->first('username') }}
                        </span>
                     </div>
                  </div> --}}

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="mobile_no" class=" form-control-label">মোবাইল নাম্বার </label>
                                    <input type="text" name="mobile_no" id="mobile_no" placeholder="মোবাইল নাম্বার লিখুন"
                                        class="form-control form-control-sm" value="{{ $userManagement->mobile_no }}">
                                </div>
                            </div>
                        </div>
                        <div class=" col-12 row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>ইমেইল এড্রেসঃ</label>
                                    <input type="text" name="email" class="form-control" placeholder=""
                                        value="{{ $userManagement->email }}" />
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="role_id" class=" form-control-label">ইউজার রোল <span
                                            class="text-danger">*</span></label>
                                    <select name="role_id" id="role_id" class="form-control-sm form-control">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($roles as $value)
                                            <option value="{{ $value->id }}"
                                                {{ $value->id == $userManagement->role_id ? 'selected' : '' }}>
                                                {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                    <span style="color: red">
                                        {{ $errors->first('role_id') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                            {{-- <div class="col-4">
                        <div class="form-group">
                          <label for="office_id" class=" form-control-label">অফিস <span class="text-danger">*</span></label>
                          <select name="office_id" id="office_id" class="form-control-sm form-control">
                           <option value="">-- নির্বাচন করুন --</option>
                                   @foreach ($offices as $value)
                                       <option value="{{ $value->id }}"{{ $value->id == $userManagement->office_id ? "selected" : ''}}> {{ $value->office_name_bn }}, {{ $value->upazila_name_bn }}, {{ $value->district_name_bn }} </option>
                                   @endforeach
                          </select>
                          <span style="color: red">
                           {{ $errors->first('office_id') }}
                           </span>
                        </div>
                     </div> --}}

                     <div class=" col-12 row">
                            <div class="col-4 mb-4">
                                <label>অফিস লেভেল</label>
                                <select name="office_type" id="office_type" class="form-control">
                                    <option value="">-বিভাগ নির্বাচন করুন-</option>3
                                    @foreach ($office_types as $value)
                                        <option
                                            value="{{ $value->id }}"{{ $value->id == $userManagement->office_type ? 'selected' : '' }}>
                                            {{ $value->type_name_bn }}

                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-4 col-4" id="selectMinDiv" style="display: none;">
                                <label>মন্ত্রণালয়/বিভাগ</label>
                                <select name="ministry" id="ministry" class="form-control">
                                    <option value="">-মন্ত্রণালয়/বিভাগ নির্বাচন করুন-</option>3
                                    @foreach ($ministries as $value)
                                        <option
                                            value="{{ $value->id }}"{{ $value->id == $userManagement->ministry ? 'selected' : '' }}>
                                            {{ $value->office_name_bn }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" col-12 row">

                            <div class="form-group mb-4 col-4" id="selectDivisionDiv" style="">
                                <label>বিভাগীয় প্রশাসন</label>


                                <select name="divOffice" id="divOffice" class="form-control">
                                    <option value="">- বিভাগীয় প্রশাসন নির্বাচন করুন-</option>3
                                    @foreach ($divOffices as $value)
                                        <option
                                            value="{{ $value->id }}"{{ $value->id == $userManagement->div_office ? 'selected' : '' }}>
                                            {{ $value->office_name_bn }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group mb-4 col-4 ">
                                <label>অফিস</label>
                                <select name="office_id" id="office_id" class="form-control-sm form-control">
                                    {{-- <option value="">-- নির্বাচন করুন --</option> --}}
                                    @foreach ($offices as $value)
                                        <option
                                            value="{{ $value->id }}"{{ $value->id == $userManagement->office_id ? 'selected' : '' }}>
                                            {{ $value->office_name_bn }} </option>
                                    @endforeach
                                </select>
                                <span style="color: red">
                                    {{ $errors->first('office_id') }}
                                </span>
                            </div>



                        </div>

                        <div class="col-lg-12 mb-5">
                            <div class="form-group row">
                                <div class="col-lg-6 mb-5">
                                    <label class=" form-control-label">স্বাক্ষরের স্ক্যান কপি সংযুক্তি <span
                                            class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <label></label>
                                        <div></div>
                                        <div class="custom-file">
                                            <input type="file" name="signature" class="custom-file-input"
                                                id="customFile" />
                                            <label class="custom-file-label" for="customFile">ফাইল নির্বাচন করুন</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-5">
                                    <label class=" form-control-label">প্রোফাইল ইমেজ সংযুক্তি <span
                                            class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <label></label>
                                        <div></div>
                                        <div class="custom-file">
                                            <input type="file" name="pro_pic" class="custom-file-input"
                                                id="customFile" />
                                            <label class="custom-file-label" for="customFile">ফাইল নির্বাচন করুন</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold mr-2">সংরক্ষণ করুন</button>
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
