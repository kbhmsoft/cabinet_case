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
    @if ($category == 'appeal')
        <div class="card">
            <div class="card-body">
                <h3 class="card-title h2 font-weight-bolder">আপীল বিভাগ</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-weight: bold; font-size: 1.2em;">ক্রমিক নং</th>
                                <th style="font-weight: bold; font-size: 1.2em;">মামলা নং</th>
                                {{-- <th style="font-weight: bold; font-size: 1.2em;">আদালতের নাম</th> --}}
                                <th style="font-weight: bold; font-size: 1.2em;">মূল বিবাদী হিসেবে অন্তর্ভুক্তির কারণ</th>
                                <th style="font-weight: bold; font-size: 1.2em;">বিষয়বস্তু (সংক্ষিপ্ত)</th>
                                <th style="font-weight: bold; font-size: 1.2em;">মূল বিবাদী হিসেবে অন্তর্ভুক্তির (পিডিএফ)
                                </th>
                                <th style="font-weight: bold; font-size: 1.2em;">প্রক্রিয়া</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appealCases as $index => $case)
                                {{-- @dd($case); --}}
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $case->case_no }}</td>
                                    {{-- <td>আপীল বিভাগ</td> --}}
                                    <td class="text-truncate" style="max-width: 200px;">{{ $case->main_defendant_comments }}
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;">{{ $case->additional_comments }}
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;">
                                        <a href="{{ asset('storage/' . $case->main_defendant_pdf) }}" target="_blank">
                                            পিডিএফ দেখুন </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('cabinet.case.editApplications', $case->id) }}"
                                            class="btn btn-primary">সম্পাদনা</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">কোনো মূল বিদাদি হিসেবে অন্তর্ভুক্তি পাওয়া
                                        যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $appealCases->links() }}
                </div>
                <!--End paginate links -->
            </div>
        </div>
    @else
        <div class="card mt-4">
            <div class="card-body">
                <h3 class="card-title h2 font-weight-bolder">হাইকোর্ট বিভাগ</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-weight: bold; font-size: 1.2em;">ক্রমিক নং</th>
                                <th style="font-weight: bold; font-size: 1.2em;">মামলা নং</th>
                                {{-- <th style="font-weight: bold; font-size: 1.2em;">আদালতের নাম</th> --}}
                                <th style="font-weight: bold; font-size: 1.2em;">মূল বিবাদী হিসেবে অন্তর্ভুক্তির কারণ</th>
                                <th style="font-weight: bold; font-size: 1.2em;">বিষয়বস্তু (সংক্ষিপ্ত)</th>
                                <th style="font-weight: bold; font-size: 1.2em;">মূল বিবাদী হিসেবে অন্তর্ভুক্তির (পিডিএফ)
                                </th>
                                <th style="font-weight: bold; font-size: 1.2em;">প্রক্রিয়া</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($highcourtCases as $index => $case)
                                {{-- @dd($case); --}}
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $case->case_no }}</td>
                                    {{-- <td>হাইকোর্ট বিভাগ</td> --}}
                                    <td class="text-truncate" style="max-width: 200px;">{{ $case->main_defendant_comments }}
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;">{{ $case->additional_comments }}
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;">
                                        <a href="{{ asset('storage/' . $case->main_defendant_pdf) }}" target="_blank">
                                            পিডিএফ
                                            দেখুন </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('cabinet.case.editApplications', $case->id) }}"
                                            class="btn btn-primary">সম্পাদনা</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">কোনো মূল বিদাদি হিসেবে অন্তর্ভুক্তি পাওয়া
                                        যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $highcourtCases->links() }}
                </div>
                <!--End paginate links -->
            </div>
        </div>
    @endif



@endsection
