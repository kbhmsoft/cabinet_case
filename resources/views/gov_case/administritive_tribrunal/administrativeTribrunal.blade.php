@extends('layouts.cabinet.cab_default')
@include('gov_case.case_register.create_css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@section('content')


    <style>
        .no-users-message {
            text-align: center;
            color: red;
            font-size: 24px;
            margin-top: 20px;
        }

        .product-image {
            position: relative;
        }

        .product-text {
            position: absolute;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-image:hover .product-text {
            display: block;
            opacity: 1;
            /* Show tooltip */
            bottom: 54px;
            left: -79px;
            z-index: 999;
            width: 200px;
        }

        .indicator {
            position: relative;
            cursor: pointer;
        }

        .indicator:hover::after {
            content: '';
            position: absolute;
            top: calc(100% + 5px);
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 10px;
            background-color: black;
            border-radius: 50%;
            z-index: 999;
        }
    </style>
@section('content')
    <!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
            </div>

            {{-- <div class="card-toolbar">
                @can('create_new_case')
                    <a href="{{ route('cabinet.case.highcourt.create') }}" class="btn btn-sm btn-primary font-weight-bolder mr-2">
                        <i class="la la-plus"></i>নতুন মামলা এন্ট্রি
                    </a>
                @endcan

            </div> --}}
        </div>
        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif

            {{-- @include('gov_case.search') --}}

            @if ($cases && $cases->isEmpty())
                <p class="no-users-message">--- তথ্য পাওয়া যায়নি ---</p>
            @else
                <table class="table table-hover mb-6 font-size-h5">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;"
                                width="30">ক্রমিক</th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলা নং
                            </th>
                             <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার
                                শ্রেণী/কেস-টাইপ</th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">
                                পিটিশনারের নাম</th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার
                                বিষয়বস্তু</th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">দফাওয়ারি
                            নোটিশ প্রেরণের তারিখ</th>
                            {{-- {{-- <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">সর্বশেষ --}}
                                {{-- অবস্থা</th> --}}
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;"
                                width="170px">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cases as $key => $row)
                            <tr>
                                <td scope="row" style="text-align:center;" class="tg-bn">
                                    {{ en2bn($key + $cases->firstItem()) }}.</td>
                                <td style="width: 10px;" style="text-align:center;">
                                    {{ en2bn($row->case_no) }}/{{ en2bn($row->case_year) }}</td>
                                <td style="text-align:center;">
                                    {{ $row->case_category_type == 1 ? 'এটি' : '' }}
                                </td>

                                <td style="text-align:center;">
                                    @if ($row->badis && $row->badis->first() && $row->badis->first()->name && $row->total_badi_number > 1)
                                        {{ $row->badis->first()->name . ' ও অন্যান্য' }}
                                    @elseif ($row->badis && $row->badis->first() && $row->badis->first()->name)
                                        {{ $row->badis->first()->name }}
                                    @endif
                                </td>

                                <td style="text-align:center;">{{ Str::limit($row->subject_matter, 100) ?? '-' }}</td>

                                <td style="text-align:center;">
                                    {{ $row->notice_given_date ? en2bn($row->notice_given_date) : '-' }}</td>

                                <td style="text-align:center;">
                                    <div class="btn-group">
                                        <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle"
                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">অ্যাকশন</button>
                                        <div class="dropdown-menu">
                                            @can('show_details_info')
                                                <a class="dropdown-item"
                                                    href="{{ route('cabinet.case.administritiveTribrunalDetails', $row->id) }}">বিস্তারিত তথ্য</a>
                                            @endcan

                                            <?php
                                            $roleID = Auth()->user()->role_id;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="btn-group">

                                        @if ($roleID == 27)
                                            <a class="btn btn-bg-danger btn-sm"
                                                href="{{ route('cabinet.case.administrative_tribrunal_case_delete', $row->id) }}">মুছে
                                                ফেলুন</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <div class="d-flex justify-content-center">
                {!! $cases->links() !!}
            </div>
        </div>
        <!--end::Card-->
    @endsection

    {{-- Includable CSS Related Page --}}
    @section('styles')
        <!-- <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" /> -->
        <!--end::Page Vendors Styles-->
    @endsection

    {{-- Scripts Section Related Page --}}
    @section('scripts')

        <script>
            $(document).ready(function() {
                $(".delete-button").click(function(e) {
                    e.preventDefault();
                    var caseId = $(this).data('case-id');
                    console.log(caseId);
                    if (confirm('Are you sure you want to delete this record?')) {
                        $.ajax({
                            type: 'GET',
                            url: '/cabinet.case.highcourt_case_delete/' + caseId,

                            success: function(data) {
                                alert(data.message);

                            },
                        });
                    }
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var linkElements = document.querySelectorAll('.caseLinkSupremeCourt');

                linkElements.forEach(function(linkElement) {
                    linkElement.addEventListener('click', function(event) {
                        event.preventDefault();
                        var division_id = linkElement.getAttribute('data-case-division-id');
                        var case_type_id = linkElement.getAttribute('data-case-type-id');
                        var case_number = linkElement.getAttribute('data-case-number');
                        var year = linkElement.getAttribute('data-case-year');

                        var link =
                            `https://supremecourt.gov.bd/web/case_history/case_history.php?div_id=${division_id}&case_type_id=${case_type_id}&case_number=${case_number}&year=${year}`;

                        if (link) {
                            window.location.href = link;
                        }
                    });
                });
            });
        </script>
        <script>
            function updateImportantCaseDatabase(checkbox) {

                const rowId = checkbox.getAttribute("data-row-id");
                const isChecked = checkbox.checked;

                const importantValue = isChecked ? 1 : null;
                const data = {
                    rowId: rowId,
                    important: importantValue
                };

                const routeUrl = "{{ route('cabinet.case.highcourtImportantSave') }}";

                fetch(routeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log('Data saved successfully.');
                        } else {

                            console.error('Failed to save data.');
                        }
                    })
                    .catch(error => {

                        console.error('Error:', error);
                    });
            }
        </script>
