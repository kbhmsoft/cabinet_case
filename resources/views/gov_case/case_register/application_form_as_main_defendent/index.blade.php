@extends('layouts.cabinet.cab_default')

@section('css')
    @include('gov_case.case_register.create_css')
@endsection

@section('content')
    <style>
       

        /* General styling for the Select2 container */
        .select2-container .select2-selection--single {
            height: 41px !important;
            /* Match your desired height */
            font-size: 1.2rem !important;
            /* Match your desired font size */
            box-sizing: border-box !important;
            /* Match your box-sizing */
            padding: 5px 10px;
            /* Adjust padding if needed */
        }

        /* Ensure the dropdown arrow is vertically centered */
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 100%;
            /* Fill the height */
            right: 10px;
            /* Adjust spacing */
        }

        /* Adjust placeholder text alignment and spacing */
        .select2-container .select2-selection--single .select2-selection__placeholder {
            line-height: 41px !important;
            /* Center align text vertically */
            color: #999;
            /* Optional: change placeholder color */
        }

        /* Style for dropdown menu */
        .select2-container .select2-dropdown {
            font-size: 1.2rem;
            /* Match the font size */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height: 13px !important;
        }  

    </style>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title h2 font-weight-bolder">{{ $page_title }} </h3>
            <form class="form-inline" method="GET">
                <div class="container">
                    <div class="row">


                        <div class="col-lg-4 mb-5">
                            <input type="text" class="form-control w-100" name="case_no" placeholder="মামলা নং"
                                value="">
                        </div>
                        <div class="col-lg-4 mb-5">
                            <div class="form-group mb-2">
                                <select name="office_id" id="office_id" class="form-control w-100">
                                    <option value="">-অনুরোধকারী নির্বাচন করুন-</option>
                                    @foreach ($ministrys as $value)
                                        <option value="{{ $value->doptor_office_id }}"
                                            {{ $value->doptor_office_id == (isset($_GET['office_id']) ? $_GET['office_id'] : '') ? 'selected' : '' }}>
                                            {{ $value->office_name_bn }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-5">
                            <button type="submit" class="btn btn-success font-weight-bolder mb-2 ml-2">অনুসন্ধান
                                করুন</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                @if ($users && $users->isEmpty())
                    <p class="no-users-message">--- তথ্য পাওয়া যায়নি ---</p>
                @else
                    <table class="table table-hover mb-6 font-size-h6">
                        <thead class="thead-light ">
                            <tr>
                                <th scope="col" width="30">ক্রমিক নং</th>
                                <th scope="col" style="text-align:center;">মামলা নং</th>
                                <th scope="col" style="text-align:center;">অনুরোধকারী মন্ত্রণালয়</th>
                                <th style="font-weight: bold; font-size: 1.2em;" style="text-align:center;">মূল বিবাদী
                                    হিসেবে অন্তর্ভুক্তির কারণ
                                </th>
                                <th style="font-weight: bold; font-size: 1.2em;" style="text-align:center;">সংযুক্তি
                                </th>
                                <th style="font-weight: bold; font-size: 1.2em;" style="text-align:center;">কার্যক্রম গ্রহণ
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $row)
                                {{-- @dd($row) --}}
                                <tr>
                                    <th scope="row" class="tg-bn">{{ en2bn($key + $users->firstItem()) }}</th>
                                    <td style="text-align:center;">{{ $row->case_no }}</td>

                                    <td style="text-align:center;">

                                        {{ $row->office->office_name_bn ?? '' }}
                                    </td>

                                    <td style="text-align:center;">
                                        {{ Str::limit($row->main_defendant_comments, 100) ?? '-' }}
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;" style="text-align:center;">
                                        <a href="{{ asset($row->main_defendant_pdf) }}" target="_blank">
                                            পিডিএফ দেখুন
                                        </a>
                                    </td>
                                    {{-- @dd($row) --}}
                                    {{-- <td style="text-align:center;">
                                        <a href="{{ route('cabinet.case.editHighcourtCaseApplication', [
                                            'case_no' => $row->case_no,
                                            'case_year' => $row->case_year,
                                            'case_category_type' => $row->case_category_type
                                        ]) }}"
                                           class="btn btn-primary">সম্পাদনা</a>
                                    </td> --}}
                                    <td style="text-align:center;">
                                        <a href="{{ route('cabinet.case.editHighcourtCaseApplication', [
                                            'case_no' => $row->case_no,
                                            'case_year' => $row->case_year ?? null,
                                            'case_category_type' => $row->case_category_type,
                                        ]) }}"
                                            class="btn btn-primary">সম্পাদনা</a>
                                    </td>



                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $users->links() !!}
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            //*************add SELECT2*********************//
            $('#office_id').select2();
        });
    </script>
@endsection
