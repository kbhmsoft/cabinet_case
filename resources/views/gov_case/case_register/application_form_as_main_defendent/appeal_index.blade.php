@extends('layouts.cabinet.cab_default')

@section('css')
    @include('gov_case.case_register.create_css')
    <style>
        .table-responsive {
            margin-top: 3rem;
            margin-left: 20rem;
            font-family: 'Kalpurush', sans-serif;
        }

        @media screen and (max-width: 768px) {
            .table-responsive {
                margin-top: 1rem;
                margin-left: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h3 class="card-title h2 font-weight-bolder">{{ $page_title }} </h3>
            <div class="table-responsive">
                @if ($users && $users->isEmpty())
                    <p class="no-users-message">--- তথ্য পাওয়া যায়নি ---</p>
                @else
                    <table class="table table-hover mb-6 font-size-h6">
                        <thead class="thead-light ">
                            <tr>
                                <th scope="col" width="30">ক্রমিক নং</th>
                                <th scope="col">মামলা নং</th>
                                <th scope="col">অনুরোধকারী মন্ত্রণালয়</th>
                                <th style="font-weight: bold; font-size: 1.2em;">মূল বিবাদী হিসেবে অন্তর্ভুক্তির কারণ
                                </th>
                                <th style="font-weight: bold; font-size: 1.2em;">মূল বিবাদী হিসেবে অন্তর্ভুক্তির
                                    (পিডিএফ)
                                </th>
                                <th style="font-weight: bold; font-size: 1.2em;">কার্যক্রম গ্রহণ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $row)
                                <tr>
                                    <th scope="row" class="tg-bn">{{ en2bn($key + $users->firstItem()) }}</th>
                                    <td>{{ $row->case_no }}</td>

                                    <td style="text-align:center;">
                                        @php
                                            $govCaseOffice = App\Models\gov_case\GovCaseOffice::where('doptor_office_id', $row->office_id)->first();
                                        @endphp
                                        {{ $govCaseOffice->office_name_bn ?? '' }}
                                    </td>

                                    <td style="text-align:center;">{{ Str::limit($row->main_defendant_comments, 100)  ??
                                        '-'}}</td>

                                    <td class="text-truncate" style="max-width: 200px;">
                                        <a href="{{ asset($row->main_defendant_pdf) }}" target="_blank">
                                            পিডিএফ দেখুন
                                        </a>
                                    </td>

                                    <td>
                                        <a href="{{ route('cabinet.case.editApplications', $row->id) }}"
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
@endsection
