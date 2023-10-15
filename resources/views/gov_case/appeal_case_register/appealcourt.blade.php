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
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
            </div>

            <div class="card-toolbar">
                @can('create_new_case')
                    <a href="{{ route('cabinet.case.appellateDivision.create') }}"
                        class="btn btn-sm btn-primary font-weight-bolder mr-2">
                        <i class="la la-plus"></i>নতুন মামলা এন্ট্রি
                    </a>
                @endcan

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
                        <th scope="col">মামলা নং</th>
                        <th scope="col">আপিলকারীর নাম</th>
                        <th scope="row">আপিলেট রেসপন্ডেন্ট</th>
                        <th scope="col">বিষয়বস্তু</th>
                        <th scope="col">শুনানির বিবরণ</th>
                        <th scope="col">সর্বশেষ তারিখ</th>
                        <th scope="col" width="170px">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $roleID = Auth()->user()->role_id;
                    ?>
                    @foreach ($cases as $key => $row)
                        {{-- {{dd($row->highcourt_case_detail)}} --}}
                        <tr>
                            <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
                            <td style="width: 10px;">{{ en2bn($row->case_no) }}/{{ en2bn($row->year) }}</td>
                            <td style="text-align:center;">
                                {{ App\Models\gov_case\GovCaseOffice::find($row->appeal_office_id)->office_name_bn }}
                            </td>
                            <td style="text-align:center;"> {{ $row->badis['name'] ?? '' }} </td>
                            <?php
                            $subjectMatter = $row->highcourt_case_detail;
                            if ($row->highcourt_case_detail !== null) {
                                $subjectMatterData = $row->highcourt_case_detail['subject_matter'];
                            } else {
                                $subjectMatterData = '';
                            }
                            ?>
                            {{-- {{ dd($subjectMattrData) }} --}}

                            <td style="text-align:center;"> {{ Str::limit($subjectMatterData, 100) }}</td>
                            {{-- <td style="text-align:center;">{{ is_null($subjectMatter) ? 'p' : '-' }}</td> --}}
                            <td style="text-align:center;">{{ '-' }} </td>
                            <td style="text-align:center;">{{ '-' }} </td>

                            <td style="text-align:center;">
                                <div>
                                    <div class="btn-group">
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
                                    </div>

                                    {{-- <div class="btn-group float-right">
                                        @if ($roleID == 27)
                                            <input type="checkbox" id="most_important" name="most_important"
                                                value="1" data-row-id="{{ $row->id }}">
                                            <label for="most_important">অধিক গুরুত্বপূর্ণ</label>
                                        @endif
                                    </div> --}}
                                    <div class="btn-group">
                                        @if ($roleID == 27)
                                            <input type="checkbox" id="most_important" name="most_important" value="1"
                                                data-row-id="{{ $row->id }}" onchange="updateDatabase(this)"
                                                {{ $row->most_important == 1 ? 'checked' : '' }}>
                                            <label class="checkbox-name" for="most_important">অধিক গুরুত্বপূর্ণ</label>
                                        @endif
                                    </div>

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
        {{-- <script>
            $(document).ready(function() {
                $('input[name="most_important"]').on('change', function() {
                    var checkbox = $(this);
                    var isChecked = checkbox.is(':checked');
                    // console.log('aoyon');
                    var rowId = checkbox.data('row-id');

                    $.ajax({
                        url: '/appeal/save-checkbox-state',
                        method: 'POST',
                        data: {
                            row_id: rowId,
                            is_checked: isChecked
                        },
                        success: function(response) {},
                        error: function(xhr, status, error) {}
                    });
                });
            });
        </script> --}}
    @endsection
