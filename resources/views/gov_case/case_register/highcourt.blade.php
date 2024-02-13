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
    </script>

    <style>
         .no-users-message {
            text-align: center;
            color: red;
            font-size: 24px;
            margin-top: 20px;
        }
    </style>
@section('content')
    <!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header flex-wrap py-5">
            <div class="card-title">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
            </div>

            <div class="card-toolbar">
                @can('create_new_case')
                    <a href="{{ route('cabinet.case.highcourt.create') }}" class="btn btn-sm btn-primary font-weight-bolder mr-2">
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

            @if ($cases && $cases->isEmpty())
            <p class="no-users-message">--- তথ্য পাওয়া যায়নি ---</p>
             @else
            <table class="table table-hover mb-6 font-size-h5">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;" width="30">ক্রমিক</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলা নং</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার শ্রেণী/কেস-টাইপ</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">পিটিশনারের নাম</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">মামলার বিষয়বস্তু</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">দফাওয়ারি জবাব প্রেরণের তারিখ</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;">সর্বশেষ অবস্থা</th>
                        <th scope="col" style="text-align:center; font-size: 12px; vertical-align: middle;" width="170px">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cases as $key => $row)
                        <tr>
                            <td scope="row" style="text-align:center;" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
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

                            <td style="text-align:center;">{{ $row->result_sending_date ? en2bn($row->result_sending_date) : '-' }}</td>
                            <td style="text-align:center;">
                                @if ($row->result == '1')
                                    সরকারের পক্ষে
                                @elseif($row->result == '2')
                                    সরকারের বিপক্ষে
                                @else
                                    চলমান
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div class="btn-group">
                                    <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">অ্যাকশন</button>
                                    <div class="dropdown-menu">
                                        @can('show_details_info')
                                            <a class="dropdown-item"
                                                href="{{ route('cabinet.case.details', $row->id) }}">বিস্তারিত তথ্য</a>
                                        @endcan
                                        @can('highcourt_case_update')
                                            <a class="dropdown-item"
                                                href="{{ route('cabinet.case.highcourt_edit', $row->id) }}">সংশোধন</a>
                                        @endcan
                                        <?php
                                        $roleID = Auth()->user()->role_id;
                                        ?>
                                        @can('highcoutr_send_answer')
                                            @if ($row->is_final_order == 0)
                                                @if (empty($row->result_sending_date))
                                                    <a class="dropdown-item"
                                                        href="{{ route('cabinet.case.sendingReplyEdit', $row->id) }}">
                                                        জবাব প্রেরণ</a>
                                                @endif
                                                @if ($row->postponed_order != 1)
                                                    <a class="dropdown-item"
                                                        href="{{ route('cabinet.case.suspensionOrderEdit', $row->id) }}">
                                                        স্থগিতাদেশের/অন্তর্বর্তীকালীন<br>আদেশের বিষয়ে ব্যাবস্থা</a>
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{ route('cabinet.case.finalOrderEdit', $row->id) }}">
                                                    চূড়ান্ত আদেশ</a>
                                            @elseif ($row->is_final_order == 1)
                                                @if ($row->result == 2)
                                                    @if (empty($row->leave_to_appeal_no))
                                                        <a class="dropdown-item"
                                                            href="{{ route('cabinet.case.leaveToAppealCreate', $row->id) }}">
                                                            সিএমপি/লিভ টু আপিল<br>দায়ের করুণ
                                                        </a>
                                                    @elseif (empty($row->leave_to_appeal_order_date))
                                                        <a class="dropdown-item"
                                                            href="{{ route('cabinet.case.leaveToAppealAnswerCreate', $row->id) }}">
                                                            সিএমপি/লিভ টু আপিল<br>রায়ের তথ্য প্রদান করুণ
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                            @if ($row->contempt_case_isuue_date == null)
                                                <a class="dropdown-item"
                                                    href="{{ route('cabinet.case.contemptCaseIssue', $row->id) }}">
                                                    কনটেম্প্ট মামলা / অন্যান্য<br> বিষয়ে ব্যাবস্থা</a>
                                            @endif
                                        @endcan
                                        @can('register')
                                            <a class="dropdown-item"
                                                href="{{ route('cabinet.case.register', $row->id) }}">রেজিস্টার</a>
                                        @endcan
                                        <a id="caseLink{{ $key }}" class="dropdown-item"
                                            data-case-division-id="{{ $row->case_division_id }}"
                                            data-case-type-id="{{ $row->case_type_id }}"
                                            data-case-number="{{ $row->case_no }}" data-case-year="{{ $row->year }}"
                                            href="#">
                                            মামলার বর্তমান অবস্থান
                                        </a>

                                        <!-- from মামলার বর্তমান অবস্থান button and data show from supreme court website  -->
                                        <script>
                                            document.getElementById('caseLink{{ $key }}').addEventListener('click', function(event) {
                                                event.preventDefault();
                                                // Prevent the default behavior of the anchor tag
                                                var division_id = this.getAttribute('data-case-division-id');
                                                var case_type_id = this.getAttribute('data-case-type-id');
                                                var case_number = this.getAttribute('data-case-number');
                                                var year = this.getAttribute('data-case-year');
                                                var dynamicUrl =
                                                    `https://supremecourt.gov.bd/web/case_history/case_history.php?div_id=${division_id}&case_type_id=${case_type_id}&case_number=${case_number}&year=${year}`;

                                                window.open(dynamicUrl, '_blank');
                                            });
                                        </script>

                                    </div>
                                </div>
                                <div class="btn-group">

                                    @if ($roleID == 27)
                                        <a class="btn btn-bg-danger btn-sm"
                                            href="{{ route('cabinet.case.highcourt_case_delete', $row->id) }}">মুছে
                                            ফেলুন</a>
                                    @endif
                                </div>

                                <div class="btn-group">
                                    @if ($roleID == 27)
                                        <input type="checkbox" id="most_important" name="most_important" value="1"
                                            data-row-id="{{ $row->id }}" onchange="updateDatabase(this)"
                                            {{ $row->most_important == 1 ? 'checked' : '' }}>
                                        <label class="checkbox-name" for="most_important">অতি গুরুত্বপূর্ণ</label>
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
        <!-- <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
                        <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
                        -->
        <!--end::Page Scripts-->
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
