@extends('layouts.cabinet.cab_default')
@include('gov_case.case_register.create_css')
@section('content')
    <style>
        .product-text {
            position: absolute;
            display: none;
            opacity: 1;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .product-image:hover .product-text {
            display: block;
            bottom: 10px;
            left: -70px;
            z-index: 999;
            width: 200px;
            pointer-events: auto;
        }

        .product-image {
            position: relative;
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var linkElements = document.querySelectorAll('.caseLinkAppealCourt');

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
        function updateDatabase(checkbox) {

            const rowId = checkbox.getAttribute("data-row-id");
            const isChecked = checkbox.checked;

            const mostImportantValue = isChecked ? 1 : null;
            const data = {
                rowId: rowId,
                most_important: mostImportantValue
            };

            const routeUrl = "{{ route('cabinet.case.highcourtMostImportantSave') }}";

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

        // for appeal most Important
        function appealUpdateDatabase(checkbox) {
            const rowId = checkbox.getAttribute("data-row-id");
            const isChecked = checkbox.checked;
            const mostImportantValue = isChecked ? 1 : null;
            const data = {
                rowId: rowId,
                most_important: mostImportantValue
            };
            const routeUrl = "{{ route('cabinet.case.appealMostImportantSave') }}";
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
@section('content')
    <!--begin::Card-->
    <div class="card card-custom">

        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title2 }}</h3>
            </div>
        </div>
        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif

            @include('gov_case.search')
            <?php
            $roleID = Auth()->user()->role_id;
            ?>
            <table class="table table-hover mb-6 font-size-h5">
                <thead class="thead-light font-size-h6">
                    {{-- <tr>
                        <th scope="col" width="30" style="text-align:center; font-size: 12px; vertical-align: middle;">
                            ক্রমিক</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলা নং</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">পিটিশনারের
                            নাম</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মূল বিবাদী ও
                            সংশ্লিষ্ট মন্ত্রণালয়/বিভাগ</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার
                            বিষয়বস্তু</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">সর্বশেষ
                            অবস্থা</th>
                        @if ($roleID == 27)
                            <th scope="col" width=""
                                style="text-align:center; font-size: 12px; vertical-align: middle;">অতি গুরুত্বপূর্ণ</th>
                        @endif
                    </tr> --}}
                    <tr>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;" width="30">
                            ক্রমিক</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলা নং
                        </th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার
                            শ্রেণী/কেস-টাইপ</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">আপিলকারীর
                            নাম
                        </th>
                        {{-- <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">আপিলেট
                            রেসপন্ডেন্ট</th> --}}
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">বিষয়বস্তু
                        </th>
                        {{-- <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">শুনানির
                            বিবরণ
                        </th> --}}

                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">সর্বশেষ
                            অবস্থা
                        </th>

                        <th scope="col" width="170px"
                            style="text-align:center; font-size: 12px; vertical-align: middle;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $roleID = Auth()->user()->role_id;
                    ?>
                    {{-- {{dd($cases)}} --}}
                    @foreach ($appealCases as $key => $row)
                        {{-- {{dd($row->highcourtCaseDetail)}} --}}
                        <tr>
                            {{-- <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
                            <td style="width: 10px;">{{ en2bn($row->case_no) }}/{{ en2bn($row->year) }}</td>
                            <td style="text-align:center;"> {{ $row->badis['name'] ?? '' }} </td>

                            <td style="text-align:center;">
                                @if ($row->appeal_office_id !== null)
                                    {{ optional(App\Models\gov_case\GovCaseOffice::find($row->appeal_office_id))->office_name_bn ?? '' }}
                                @else
                                @endif
                            </td>

                            <?php
                            $subjectMatterData = '';
                            if ($row->highcourtCaseDetail !== null) {
                                $subjectMatterData = $row->highcourtCaseDetail['subject_matter'];
                            } else {
                                $subjectMatterData = '';
                            }
                            
                            ?> --}}
                            {{-- {{ dd($subjectMatterData) }} --}}

                            {{-- <td style="text-align:center;"> {{ Str::limit($subjectMatterData, 100) }}</td> --}}
                            {{-- <td style="text-align:center;">{{ is_null($subjectMatter) ? 'p' : '-' }}</td> --}}


                            {{-- <td style="text-align:center;">
                                <div>
                                    @if ($row->result == '1')
                                        সরকারের পক্ষে
                                    @elseif($row->result == '2')
                                        সরকারের বিপক্ষে
                                    @else
                                        চলমান
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if ($roleID == 27)
                                        <input type="checkbox" id="most_important" name="most_important" value="1"
                                            data-row-id="{{ $row->id }}" onchange="appealUpdateDatabase(this)"
                                            {{ $row->most_important == 1 ? 'checked' : '' }}>
                                        <label class="checkbox-name" for="most_important">অতি গুরুত্বপূর্ণ</label>
                                    @endif
                                </div>
                            </td> --}}


                            <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
                            <td style="width: 10px;">{{ en2bn($row->case_no) }}/{{ en2bn($row->year) }}</td>

                            <td style="text-align:center;">
                                @foreach ($gov_case_division_category_type as $value)
                                    @if ($value->id == $row['case_type_id'])
                                        {{ $value->name_bn }}
                                    @endif
                                @endforeach
                            </td>

                            <td style="text-align:center;">
                                @php
                                    $govCaseOffice = App\Models\gov_case\GovCaseOffice::where(
                                        'doptor_office_id',
                                        $row->appeal_office_id,
                                    )->first();
                                @endphp
                                {{ $govCaseOffice->office_name_bn ?? '' }}
                            </td>

                            {{-- <td style="text-align:center;"> {{ $row->badis['name'] ?? '' }} </td> --}}
                            <?php
                            $subjectMatter = $row->highcourt_case_detail;
                            if ($row->highcourt_case_detail !== null) {
                                $subjectMatterData = $row->highcourt_case_detail['subject_matter'];
                            } else {
                                $subjectMatterData = '';
                            }
                            ?>
                            <td style="text-align:center;"> {{ Str::limit($subjectMatterData, 100) }}</td>

                            {{-- <td style="text-align:center;">{{ '-' }} </td> --}}


                            <td class="notice-popup">
                                <div class="product cardhoveritem">
                                    <div class="product-image">
                                        @if ($row->is_final_order == '1')
                                            <span class="indicator">নিষ্পত্তিকৃত মামলা</span>
                                        @else
                                            <span class="indicator" style="margin-left: 5rem;">মামলা চলমান</span>
                                        @endif
                                        <div class="product-text">
                                            <div class="card card-custom">
                                                <div class="card-body">
                                                    <a href="#" data-case-division-id="{{ $row->case_division_id }}"
                                                        data-case-type-id="{{ $row->case_type_id }}"
                                                        data-case-number="{{ $row->case_no }}"
                                                        data-case-year="{{ $row->year }}"
                                                        class="caseLinkAppealCourt">শুনানির
                                                        তারিখ/সংক্ষিপ্ত আদেশ দেখতে এখানে ক্লিক করুন</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @can('show_details_info')
                                    <a class="btn btn-primary btn-sm font-weight-bold ml-10"
                                        href="{{ route('cabinet.case.appealCaseDetails', $row->id) }}">বিস্তারিত
                                        তথ্য</a>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {!! $cases->links() !!}
            </div>
        </div>




        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
            </div>
        </div>
        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif

            @include('gov_case.search')

            <table class="table table-hover mb-6 font-size-h5">
                <thead class="thead-light font-size-h6">
                    {{-- <tr>
                        <th scope="col" width="30" style="text-align:center; font-size: 12px; vertical-align: middle;">ক্রমিক</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলা নং</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">পিটিশনারের নাম</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মূল বিবাদী ও সংশ্লিষ্ট মন্ত্রণালয়/বিভাগ</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার বিষয়বস্তু</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">সর্বশেষ অবস্থা</th>
                        @if ($roleID == 27)
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">অতি গুরুত্বপূর্ণ</th>
                        @endif

                    </tr> --}}
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
                            জবাব প্রেরণের তারিখ</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">সর্বশেষ
                            অবস্থা</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;"
                            width="170px">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cases as $key => $row)
                        <tr>
                            {{-- <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
                            <td style="width: 10px;">{{ en2bn($row->case_no) }}/{{ en2bn($row->year) }}</td> --}}
                            {{-- <td>{{ $row->badis->first()->name ?? '-' }}</td> --}}
                            {{-- <td>
                                @if ($row->badis && $row->badis->first() && $row->badis->first()->name && $row->total_badi_number > 1)
                                    {{ $row->badis->first()->name . ' ও অন্যান্য' }}
                                @elseif ($row->badis && $row->badis->first() && $row->badis->first()->name)
                                    {{ $row->badis->first()->name }}
                                @endif
                            </td>

                            <td>
                                @if ($row->mainBibadis->first() !== null)
                                    {{ optional(App\Models\gov_case\GovCaseOffice::find($row->mainBibadis->first()->respondent_id))->office_name_bn ?? '' }}
                                @else

                                @endif
                            </td>

                            <td>{{ Str::limit($row->subject_matter, 100) ?? '-' }}</td>
                            <td style="">
                                @if ($row->result == '1')
                                    সরকারের পক্ষে
                                @elseif($row->result == '2')
                                    সরকারের বিপক্ষে
                                @else
                                    চলমান
                                @endif

                                <?php
                                $roleID = Auth()->user()->role_id;
                                ?>

                            </td> --}}
                            <td scope="row" style="text-align:center;" class="tg-bn">
                                {{ en2bn($key + $cases->firstItem()) }}.</td>
                            <td style="width: 10px;" style="text-align:center;">
                                {{ en2bn($row->case_no) }}/{{ en2bn($row->year) }}</td>
                            <td style="text-align:center;">
                                @foreach ($gov_case_division_category_type as $value)
                                    @if ($value->id == $row['case_type_id'])
                                        {{ $value->name_bn }}
                                    @endif
                                @endforeach
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
                                {{ $row->result_sending_date ? en2bn($row->result_sending_date) : '-' }}</td>

                            {{-- <td>
                                <div class="btn-group">
                                    @if ($roleID == 27)
                                        <input type="checkbox" id="most_important" name="most_important" value="1"
                                            data-row-id="{{ $row->id }}" onchange="updateDatabase(this)"
                                            {{ $row->most_important == 1 ? 'checked' : '' }}>
                                        <label class="checkbox-name" for="most_important">অতি গুরুত্বপূর্ণ</label>
                                    @endif
                                </div>
                            </td> --}}
                            <td class="notice-popup">
                                <div class="product cardhoveritem">
                                    <div class="product-image">
                                        @if ($row->is_final_order == '1')
                                            <span class="indicator">নিষ্পত্তিকৃত মামলা</span>
                                        @else
                                            <span class="indicator">মামলা চলমান</span>
                                        @endif
                                        <div class="product-text">
                                            <div class="card card-custom">
                                                <div class="card-body">
                                                    <a href="#"
                                                        data-case-division-id="{{ $row->case_division_id }}"
                                                        data-case-type-id="{{ $row->case_type_id }}"
                                                        data-case-number="{{ $row->case_no }}"
                                                        data-case-year="{{ $row->year }}"
                                                        class="caseLinkSupremeCourt">শুনানির
                                                        তারিখ/সংক্ষিপ্ত আদেশ দেখতে এখানে ক্লিক করুন</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @can('show_details_info')
                                    <a class="btn btn-primary btn-sm font-weight-bold ml-10"
                                        href="{{ route('cabinet.case.details', $row->id) }}">বিস্তারিত
                                        তথ্য</a>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

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
        <!-- <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
                                                                                                       <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
                                                                                                     -->
