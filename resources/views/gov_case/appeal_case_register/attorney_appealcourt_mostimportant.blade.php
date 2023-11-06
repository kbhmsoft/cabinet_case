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
    <!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                {{-- if from controller there are no "$page_title" then it will be "Attorney Apeealcourt" --}}
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title ?? 'Attorney AppealCourt' }}</h3>
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
                        <th scope="col" width="30">ক্রমিক</th>
                        <th scope="col" style="text-align:center;">মামলা নং</th>
                        <th scope="col" style="text-align:center;">পিটিশনারের নাম</th>
                        <th scope="col" style="text-align:center;">মূল বিবাদী ও সংশ্লিষ্ট মন্ত্রণালয়/বিভাগ</th>
                        <th scope="col" style="text-align:center;">মামলার বিষয়বস্তু</th>
                        <th scope="col" style="text-align:center;">সর্বশেষ অবস্থা</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $roleID = Auth()->user()->role_id;
                    ?>
                    {{-- {{dd($cases)}} --}}
                    @foreach ($cases as $key => $row)
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
                                    {{-- <div class="btn-group">
                                        <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle"
                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">অ্যাকশন</button>
                                        <div class="dropdown-menu">
                                            @can('show_details_info')
                                                <a class="dropdown-item"
                                                    href="{{ route('cabinet.case.appealCaseDetails', $row->id) }}">বিস্তারিত
                                                    তথ্য</a>
                                            @endcan

                                            @can('appeal_update')
                                                <a class="dropdown-item"
                                                    href="{{ route('cabinet.case.editAppealCaseForm', $row->id) }}">সংশোধন</a>
                                            @endcan
                                            @if ($row->is_final_order != 1)
                                                <a class="dropdown-item"
                                                    href="{{ route('cabinet.case.appealFinalOrderEdit', $row->id) }}">
                                                    চূড়ান্ত আদেশ</a>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="btn-group">
                                        @if ($roleID == 27)
                                            <a class="btn btn-bg-danger btn-sm"
                                                href="{{ route('cabinet.case.appeal_case_delete', $row->id) }}">মুছে
                                                ফেলুন</a>
                                        @endif
                                    </div> --}}

                                    {{-- <div class="btn-group float-right">
                                        @if ($roleID == 27)
                                            <input type="checkbox" id="most_important" name="most_important"
                                                value="1" data-row-id="{{ $row->id }}">
                                            <label for="most_important">অধিক গুরুত্বপূর্ণ</label>
                                        @endif
                                    </div> --}}
                                    {{-- <div class="btn-group">
                                        @if ($roleID == 27)
                                            <input type="checkbox" id="most_important" name="most_important" value="1"
                                                data-row-id="{{ $row->id }}" onchange="updateDatabase(this)"
                                                {{ $row->most_important == 1 ? 'checked' : '' }}>
                                            <label class="checkbox-name" for="most_important">অধিক গুরুত্বপূর্ণ</label>
                                        @endif
                                    </div> --}}

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

    {{-- Scripts Section Related Page --}}
    @section('scripts')
        <!-- <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
                                                               <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
                                                             -->
        <!--end::Page Scripts-->
    @endsection
    @section('scripts')
    @endsection
