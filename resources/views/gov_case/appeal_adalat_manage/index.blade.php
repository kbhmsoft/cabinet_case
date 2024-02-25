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

        .no-users-message {
            text-align: center;
            color: red;
            font-size: 24px;
            margin-top: 20px;
        }
    </style>
    <!--begin::Card-->
    <div class="card card-custom">

        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h2> {{ $page_title }} </h2>
            </div>

            <div class="card-toolbar">
                <a href="{{ route('cabinet.appeal-maintain.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
                    <i class="la la-plus"></i>নতুন আপিল আদালত এন্ট্রি
                </a>
            </div>

        </div>

        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif


            @if ($appealData && $appealData->isEmpty())
                <p class="no-users-message">--- তথ্য পাওয়া যায়নি ---</p>
            @else
                <table class="table table-hover mb-6 font-size-h6">
                    <thead class="thead-light ">
                        <tr>
                            <th scope="col" width="30">#</th>
                            <th scope="col">আদালতের নাম</th>
                            <th scope="col" style="text-align:center;">স্টেটাস</th>
                            <th scope="col" width="150" style="text-align:center;">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appealData as $key => $row)
                            <tr>
                                <th scope="row" class="tg-bn">{{ en2bn($key + $appealData->firstItem()) }}</th>
                                <td>{{ $row->name }}</td>
                                <td style="text-align:center;">
                                    @if ($row->status == 1)
                                        এনাবল
                                    @else
                                        ডিজেবল
                                    @endif
                                </td>

                                <td style="text-align:center;">
                                    <div style="display: flex; justify-content: space-between;">
                                        @if (auth()->user()->can('update_highcourt_adalat_info'))
                                            <a href="{{ route('cabinet.appeal-maintain.edit', $row->id) }}"
                                                class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">সংশোধন</a>
                                        @else
                                            <a href="#" class="btn btn-secondary btn-sm font-weight-bold pt-1 pb-1">
                                                সংশোধন
                                            </a>
                                        @endif

                                        @if (auth()->user()->can('delete_highcourt_adalat_info'))
                                            <form action="{{ route('cabinet.appeal-maintain.destroy', $row->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-shadow btn-sm font-weight-bold pt-1 pb-1">মুছে
                                                    ফেলুন</button>
                                            </form>
                                        @else
                                            <a href="#"
                                                class="btn btn-secondary btn-sm font-weight-bold pt-1 pb-1">মুছে ফেলুন</a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $appealData->links() !!}
            @endif
        </div>
    </div>
    <!--end::Card-->
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
@endsection