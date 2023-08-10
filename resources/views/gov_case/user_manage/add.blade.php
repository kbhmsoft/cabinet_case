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
    <br>
    <!-- <a href="" style="float: right;"> <button  class="btn btn-success">Back</button></a> -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <form method="POST" action="{{ route('cabinet.user-management.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
                        </div>

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{{ $error }}</li>
                            @endforeach
                        @endif
                        <div class="card-body card-block row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="name" class=" form-control-label">পুরো নাম <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="পুরো নাম লিখুন"
                                        class="form-control form-control-sm">
                                    <span style="color: red">
                                        {{ $errors->first('name') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="mobile_no" class=" form-control-label">মোবাইল নাম্বার </label>
                                    <input type="text" name="mobile_no" id="mobile_no" placeholder="মোবাইল নাম্বার লিখুন"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="email" class=" form-control-label">ইমেল</label>
                                    <input type="email" id="email" name="email" placeholder="ইমেল লিখুন"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="role_id" class=" form-control-label">ইউজার রোল <span
                                            class="text-danger">*</span></label>
                                    <select name="role_id" id="role_id" class="form-control-sm form-control">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach ($roles as $value)
                                            <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                    <span style="color: red">
                                        {{ $errors->first('role_id') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-4 mb-4">
                                <div class="form-group">

                                    <label for="office_type" class=" form-control-label">অফিস লেভেল</label>

                                    <select name="office_type" id="office_type" class="form-control">
                                        <option value="">-বিভাগ নির্বাচন করুন-</option>3
                                        @foreach ($office_types as $value)
                                            <option
                                                value="{{ $value->id }}"{{ (isset($_GET['office_type']) ? $_GET['office_type'] : '') == $value->id ? 'selected' : '' }}>
                                                {{ $value->type_name_bn }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4 mb-4" id="selectMinDiv" style="display: none;">

                                <div class="form-group mb-4">
                                    <label class=" form-control-label">মন্ত্রণালয়/বিভাগ</label>
                                    <select name="ministry" id="ministry" class="form-control">
                                        <option value="">-মন্ত্রণালয়/বিভাগ নির্বাচন করুন-</option>3
                                        @foreach ($ministries as $value)
                                            <option
                                                value="{{ $value->id }}"{{ (isset($_GET['ministry']) ? $_GET['ministry'] : '') == $value->id ? 'selected' : '' }}>
                                                {{ $value->office_name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-4 mb-4" id="selectDivisionDiv" style="display: none;">
                                <div class="form-group mb-4">
                                    <label class=" form-control-label">বিভাগীয় প্রশাসন</label>

                                    <select name="divOffice" id="divOffice" class="form-control">
                                        <option value="">- বিভাগীয় প্রশাসন নির্বাচন করুন-</option>3
                                        @foreach ($divOffices as $value)
                                            <option
                                                value="{{ $value->id }}"{{ (isset($_GET['divOffice']) ? $_GET['divOffice'] : '') == $value->id ? 'selected' : '' }}>
                                                {{ $value->office_name_bn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group mb-4 col-lg-4 ">
                                <label>অফিস</label>
                                <select name="office_id" id="office_id" class="form-control">
                                    <option value="">- অফিস নির্বাচন করুন-</option>3
                                </select>
                                <span style="color: red">
                                    {{ $errors->first('office_id') }}
                                </span>
                            </div>


                            <div class="col-4">
                                <div class="form-group">
                                    <label for="password" class=" form-control-label">পাসওয়ার্ড <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="password" id="password" placeholder="পাসওয়ার্ড লিখুন"
                                        class="form-control">
                                    <span style="color: red">
                                        {{ $errors->first('password') }}
                                    </span>
                                </div>
                            </div>




                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <button type="submit" class="btn btn-success mr-2"
                                    onclick="return confirm('আপনি কি সংরক্ষণ করতে চান?')">সংরক্ষণ করুন</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">নতুন ইউজার তথ্য</h4>
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <table class="tg">
                                        <tr>
                                            <th class="tg-19u4 text-center">পুরো নাম </th>
                                            <td class="tg-nluh" id="previewName"></td>
                                        </tr>
                                        <tr>
                                            <th class="tg-19u4 text-center">ইউজারনেম</th>
                                            <td class="tg-nluh" id="previewUsername"></td>
                                        </tr>
                                        <tr>
                                            <th class="tg-19u4 text-center">মোবাইল নাম্বার </th>
                                            <td class="tg-nluh" id="previewMobile_no"></td>
                                        </tr>
                                        <tr>
                                            <th class="tg-19u4 text-center">ইমেল</th>
                                            <td class="tg-nluh" id="previewEmail"></td>
                                        </tr>
                                        <tr>
                                            <th class="tg-19u4 text-center">ভূমিকা </th>
                                            <td class="tg-nluh" id="previewRole_id"></td>
                                        </tr>
                                        <tr>
                                            <th class="tg-19u4 text-center">অফিস</th>
                                            <td class="tg-nluh" id="previewOffice_id"></td>
                                        </tr>
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
            </div>
        </div>
    </div>
    <style>
        /* .select2-container .select2-selection--single {
                            height: 37px !important;
                        }

                        .select2-container--default .select2-selection--single .select2-selection__arrow {
                            top: 5px !important;
                        }

                        .select2-container--default .select2-selection--single .select2-selection__rendered {
                            line-height: 25px !important;
                        } */
    </style>
@endsection
@section('scripts')
    <script>
        function myFunction() {
            confirm("আপনি কি সংরক্ষণ করতে চান?");
        }

        $('document').ready(function() {
            $('#preview').on('click', function() {
                var name = $('#name').val();
                // .console.log(name);
                var username = $('#username').val();
                var email = $('#email').val();
                var mobile_no = $('#mobile_no').val();
                var role_id = $('#role_id option:selected').text();
                var office_id = $('#office_id option:selected').text();
                $('#previewName').html(name);
                $('#previewUsername').html(username);
                $('#previewEmail').html(email);
                $('#previewMobile_no').html(mobile_no);
                $('#previewRole_id').html(role_id);
                $('#previewOffice_id').html(office_id);
            });
        });
    </script>
    <script type="text/javascript">
        $('#office_id').select2();
    </script>


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
@endsection
