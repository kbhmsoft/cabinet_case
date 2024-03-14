@extends('layouts.cabinet.cab_default')
@include('gov_case.case_register.create_css')
@section('content')
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
            @if ($cases && $cases->isEmpty())
                <p class="no-users-message">--- তথ্য পাওয়া যায়নি ---</p>
            @else
                @include('gov_case.search')
                <?php
                $roleID = Auth()->user()->role_id;
                ?>
                <table class="table table-hover mb-6 font-size-h5">
                    <thead class="thead-light font-size-h6">
                        <tr>
                            <th scope="col" width="30"
                                style="text-align:center; font-size: 12px; vertical-align: middle;">ক্রমিক</th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলা নং
                            </th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">
                                পিটিশনারের নাম</th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মূল
                                বিবাদী ও সংশ্লিষ্ট মন্ত্রণালয়/বিভাগ</th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার
                                বিষয়বস্তু</th>
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">সর্বশেষ
                                অবস্থা</th>
                            @if ($roleID == 27)
                                <th scope="col" width=""
                                    style="text-align:center; font-size: 12px; vertical-align: middle;">অতি গুরুত্বপূর্ণ
                                </th>
                            @endif
                            @if ($roleID == 29 || $roleID == 31 || $roleID == 32 || $roleID == 41 || $roleID == 44)
                                <th scope="col" width=""
                                    style="text-align:center; font-size: 12px; vertical-align: middle;">গুরুত্বপূর্ণ</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        {{-- {{dd($cases)}} --}}
                        @foreach ($appealCases as $key => $row)
                            {{-- {{dd($row->highcourtCaseDetail)}} --}}
                            <tr>
                                <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
                                <td style="width: 10px;">{{ en2bn($row->case_no) }}/{{ en2bn($row->year) }}</td>
                                <td style="text-align:center;"> {{ $row->badis['name'] ?? '' }} </td>
                                <td style="text-align:center;">
                                    {{ App\Models\gov_case\GovCaseOffice::find($row->appeal_office_id)->office_name_bn }}
                                </td>

                                <?php
                                $subjectMatterData = '';
                                if ($row->highcourtCaseDetail !== null) {
                                    $subjectMatterData = $row->highcourtCaseDetail['subject_matter'];
                                } else {
                                    $subjectMatterData = '';
                                }

                                ?>
                                {{-- {{ dd($subjectMatterData) }} --}}

                                <td style="text-align:center;"> {{ Str::limit($subjectMatterData, 100) }}</td>
                                {{-- <td style="text-align:center;">{{ is_null($subjectMatter) ? 'p' : '-' }}</td> --}}


                                <td style="text-align:center;">
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

                                    <div class="btn-group">
                                        @if ($roleID == 29 || $roleID == 31)
                                            <input type="checkbox" id="important" name="important" value="1"
                                                data-row-id="{{ $row->id }}"
                                                onchange="updateAppealImportantCaseDatabase(this)"
                                                {{ $row->important == 1 ? 'checked' : '' }}>
                                            <label class="checkbox-name" for="important"> গুরুত্বপূর্ণ</label>
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
                    <tr>
                        <th scope="col" width="30"
                            style="text-align:center; font-size: 12px; vertical-align: middle;">ক্রমিক</th>
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
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">অতি
                                গুরুত্বপূর্ণ</th>
                        @endif
                        @if ($roleID == 29 || $roleID == 31)
                            <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">
                                গুরুত্বপূর্ণ</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cases as $key => $row)
                        <tr>
                            <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
                            <td style="width: 10px;">{{ en2bn($row->case_no) }}/{{ en2bn($row->year) }}</td>
                            {{-- <td>{{ $row->badis->first()->name ?? '-' }}</td> --}}
                            <td>
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

                            </td>

                            <td>
                                {{-- <div class="btn-group">
                                    @if ($roleID == 27)
                                        <input type="checkbox" id="most_important" name="most_important" value="1"
                                            data-row-id="{{ $row->id }}" onchange="updateDatabase(this)"
                                            {{ $row->most_important == 1 ? 'checked' : '' }}>
                                        <label class="checkbox-name" for="most_important"> গুরুত্বপূর্ণ</label>
                                    @endif
                                </div> --}}

                                <div class="btn-group">
                                    @if ($roleID == 29 || $roleID == 31)
                                        <input type="checkbox" id="important" name="important" value="1"
                                            data-row-id="{{ $row->id }}" onchange="updateImportantCaseDatabase(this)"
                                            {{ $row->important == 1 ? 'checked' : '' }}>
                                        <label class="checkbox-name" for="important"> গুরুত্বপূর্ণ</label>
                                    @endif
                                </div>
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

<script>
    function updateAppealImportantCaseDatabase(checkbox) {

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
