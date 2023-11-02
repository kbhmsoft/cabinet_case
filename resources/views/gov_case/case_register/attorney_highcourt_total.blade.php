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
@section('content')
    <!--begin::Card-->
    <div class="card card-custom">
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
                        <th scope="col" width="30">ক্রমিক</th>
                        <th scope="col" style="text-align:center;">মামলা নং</th>
                        <th scope="col" style="text-align:center;">পিটিশনারের নাম</th>
                        <th scope="col" style="text-align:center;">মামলার বিষয়বস্তু</th>
                        <th scope="col">রুল ইস্যুর তারিখ/প্রাপ্তির তারিখ</th>
                        <th scope="col">দফাওয়ারি জবাব প্রেরণের তারিখ</th>
                        {{-- <th scope="col" width="170px">অ্যাকশন</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cases as $key => $row)
                        <tr>
                            {{-- {{dd($row->total_badi_number)}} --}}
                            <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
                            <td style="width: 10px;" style="text-align:center;">{{ en2bn($row->case_no) }}/{{ en2bn($row->year) }}</td>
                            <td>
                                @if ($row->badis && $row->badis->first() && $row->badis->first()->name && $row->total_badi_number > 1)
                                    {{ $row->badis->first()->name . ' ও অন্যান্য' }}
                                @elseif ($row->badis && $row->badis->first() && $row->badis->first()->name)
                                    {{ $row->badis->first()->name }}
                                @endif
                            </td>

                            <td>{{ Str::limit($row->subject_matter, 100) ?? '-' }}</td>
                            <td>{{ $row->date_issuing_rule_nishi ? en2bn($row->date_issuing_rule_nishi) : '-' }}</td>
                            <td>{{ $row->result_sending_date ? en2bn($row->result_sending_date) : '-' }}</td>
                            {{-- <td style="text-align:center;">
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

                                            @if ($row->action_user_role_id == userInfo()->role_id)
                                                <a class="dropdown-item"
                                                    href="{{ route('cabinet.case.action.details', $row->id) }}">জবাব প্রেরণ</a>
                                            @endif
                                        @endcan
                                        @can('register')
                                            <a class="dropdown-item"
                                                href="{{ route('cabinet.case.register', $row->id) }}">রেজিস্টার</a>
                                        @endcan
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
                                        <label class="checkbox-name" for="most_important">অধিক গুরুত্বপূর্ণ</label>
                                    @endif
                                </div>
                            </td> --}}
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
