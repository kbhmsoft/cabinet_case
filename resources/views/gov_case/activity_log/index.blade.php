@extends('layouts.cabinet.cab_default')

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
                        <th scope="col">মামলা নং</th>
                        <th scope="col">ক্যাটাগরি</th>
                        <th scope="col">পিটিশনারের নাম ও ঠিকানা</th>
                        <th scope="col">রুল ইস্যুর তারিখ/প্রাপ্তির তারিখ</th>
                        <th scope="col" width="70">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- {{dd($cases)}} --}}
                    @foreach ($cases as $key => $row)
                        <tr>
                            {{-- {{dd()}} --}}
                            <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
                            <td>{{ $row->case_no }}</td>
                            <td>{{ $row->case_category->name_bn ?? '-' }}</td>
                            <td>{{ $row->badis->first()->name ?? '-' }},<br>{{ $row->badis->first()->address ?? '-' }} </td>
                            <td>{{ $row->date_issuing_rule_nishi ?? '-' }}</td>
                            <td>
                                @if (auth()->user()->can('case_audit_details_show'))
                                    <a href="{{ route('cabinet.case_audit.show', $row->id) }}" type="button"
                                        class="btn btn-primary">বিস্তারিত </a>
                                @else
                                    <a href="#" type="button" class="btn btn-secondary">বিস্তারিত </a>
                                @endif
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
