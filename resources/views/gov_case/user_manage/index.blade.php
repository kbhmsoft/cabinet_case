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
            <div class="card-toolbar">
                @if (auth()->user()->can('create_new_user'))
                    <a href="{{ route('cabinet.user-management.create') }}" class="btn btn-sm btn-primary font-weight-bolder">
                        <i class="la la-plus"></i>নতুন ইউজার এন্ট্রি
                    </a>
                @else
                    <a href="#" class="btn btn-sm btn-secondary font-weight-bolder">
                        <i class="la la-plus"></i>নতুন ইউজার এন্ট্রি
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <form class="form-inline" method="GET">
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
                                value="{{ $value->id }}"{{ (isset($_GET['ministry']) ? $_GET['ministry'] : '') == $value->id ? 'selected' : '' }}>
                                {{ $value->office_name_bn }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2 mr-2" id="selectDivisionDiv" style="display: none;">
                    <select name="divOffice" id="divOffice" class="form-control">
                        <option value="">- বিভাগীয় প্রশাসন নির্বাচন করুন-</option>3
                        @foreach ($divOffices as $value)
                            <option
                                value="{{ $value->id }}"{{ (isset($_GET['divOffice']) ? $_GET['divOffice'] : '') == $value->id ? 'selected' : '' }}>
                                {{ $value->office_name_bn }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2 mr-2">
                    <input type="text" name="office_name" placeholder="অফিসের নাম লিখুন"
                        value="{{ isset($_GET['office_name']) ? $_GET['office_name'] : '' }}" class="form-control w-100">

                </div>
                <button type="submit" class="btn btn-success ">অনুসন্ধান করুন</button>
            </form>


            <table class="table table-hover mb-6 font-size-h6">
                <thead class="thead-light ">
                    <tr>
                        <th scope="col" width="30">#</th>
                        <th scope="col">নাম</th>
                        <th scope="col">ইউজার রোল</th>
                        <th scope="col">অফিসের নাম</th>
                        <th scope="col">ইমেইল এড্রেস</th>
                        <th scope="col" width="150">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $row)
                        <tr>
                            <th scope="row" class="tg-bn">{{ en2bn($key + $users->firstItem()) }}</th>
                            <td>{{ $row->name }}</td>
                            {{-- <td>{{ $row->username }}</td> --}}
                            <td>{{ $row->roleName }}</td>
                            <td>{{ $row->office_name_bn }}</td>
                            <td>{{ $row->email }}</td>
                            <!-- <td>
                      <span class="label label-inline label-light-primary font-weight-bold">Pending</span>
                   </td> -->
                            <td>
                                @if (auth()->user()->can('show_user_details'))
                                    <a href="{{ route('cabinet.user-management.show', $row->id) }}"
                                        class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">বিস্তারিত</a>
                                @else
                                    <a href="#" class="btn btn-secondary btn-sm font-weight-bold pt-1 pb-1">
                                        বিস্তারিত
                                    </a>
                                @endif
                                @if (auth()->user()->can('update_user_info'))
                                    <a href="{{ route('cabinet.user-management.edit', $row->id) }}"
                                        class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">সংশোধন</a>
                                @else
                                    <a href="#" class="btn btn-secondary btn-sm font-weight-bold pt-1 pb-1">
                                        সংশোধন
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $users->links() !!}
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

    <script type="text/javascript">
        jQuery(document).ready(function() {

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





            //   console.log(searchParams.get('office_type')); // true


            // District Dropdown
            // jQuery('select[name="division"]').on('change', function() {
            //     var dataID = jQuery(this).val();
            //     jQuery("#district_id").after('<div class="loadersmall"></div>');
            //     if (dataID) {
            //         jQuery.ajax({
            //             url: '/office/dropdownlist/getdependentdistrict/' + dataID,
            //             type: "GET",
            //             dataType: "json",
            //             success: function(data) {
            //                 jQuery('select[name="district"]').html(
            //                     '<div class="loadersmall"></div>');
            //                 jQuery('select[name="district"]').html(
            //                     '<option value="">-- নির্বাচন করুন --</option>');
            //                 jQuery.each(data, function(key, value) {
            //                     jQuery('select[name="district"]').append(
            //                         '<option value="' + key + '">' + value +
            //                         '</option>');
            //                 });
            //                 jQuery('.loadersmall').remove();
            //             }
            //         });
            //     } else {
            //         $('select[name="district"]').empty();
            //     }
            // });
            // Upazila Dropdown
            // jQuery('select[name="district"]').on('change', function() {
            //     var dataID = jQuery(this).val();
            //     jQuery("#upazila_id").after('<div class="loadersmall"></div>');
            //     if (dataID) {
            //         jQuery.ajax({
            //             url: '/office/dropdownlist/getdependentupazila/' + dataID,
            //             type: "GET",
            //             dataType: "json",
            //             success: function(data) {
            //                 jQuery('select[name="upazila"]').html(
            //                     '<div class="loadersmall"></div>');
            //                 jQuery('select[name="upazila"]').html(
            //                     '<option value="">-- নির্বাচন করুন --</option>');
            //                 jQuery.each(data, function(key, value) {
            //                     jQuery('select[name="upazila"]').append(
            //                         '<option value="' + key + '">' + value +
            //                         '</option>');
            //                 });
            //                 jQuery('.loadersmall').remove();
            //             }
            //         });
            //     } else {
            //         $('select[name="upazila"]').empty();
            //     }
            // });
        });
    </script>
@endsection
