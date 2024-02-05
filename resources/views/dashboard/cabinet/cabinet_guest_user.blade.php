@extends('layouts.cabinet.cab_default')

@section('style')
    <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!-- Add any additional styles specific to this page -->
    <style>
        /* Your custom styles go here */
    </style>
@endsection

@section('content')
    <!--begin::Dashboard-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="mb-4">স্বাগতম! আপনি একজন গেস্ট ইউজার</h1>

                        <!-- Add your Highcharts chart container here -->
                        <div id="container" style="height: 400px;"></div>

                        <!-- Include Highcharts scripts -->
                        <script src="https://code.highcharts.com/highcharts.js"></script>
                        <script src="https://code.highcharts.com/modules/data.js"></script>
                        <script src="https://code.highcharts.com/modules/drilldown.js"></script>
                        <script src="https://code.highcharts.com/modules/exporting.js"></script>
                        <script src="https://code.highcharts.com/modules/export-data.js"></script>
                        <script src="https://code.highcharts.com/modules/accessibility.js"></script>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Dashboard-->
@endsection

@section('styles')
    <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!-- Add any additional styles specific to this page -->
    <style>
        /* Your custom styles go here */
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/widgets.js') }}"></script>
    <!-- Include any additional scripts specific to this page -->

    {{-- {{-- -------------callender end---------- --}}
    {{-- @if (Auth::user()->role_id == 2)
        @include('dashboard.calendar.calender_need_js')
    @endif --}}
    {{-- {{-- -------------callender end---------- --}}
@endsection
