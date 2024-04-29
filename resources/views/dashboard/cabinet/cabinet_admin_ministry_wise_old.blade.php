@extends('layouts.cabinet.cab_default')
{{-- @yield('style') --}}
{{-- <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" /> --}}

@section('style')
    <style>
        #ministryWiseCard {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div id="superAdminCardLayout">
        @include('dashboard.cabinet_new.card_layout.super_admin_card_layout')
    </div>
    <div id="ministryWiseCard">
        @include('dashboard.cabinet.inc._dashboard_ministry_wise_card')
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var superAdminCardLayout = document.getElementById('superAdminCardLayout');
            var ministryWiseCard = document.getElementById('ministryWiseCard');

            function toggleComponents() {
                if (superAdminCardLayout.style.display === 'none') {
                    superAdminCardLayout.style.display = 'block';
                    ministryWiseCard.style.display = 'none';
                } else {
                    superAdminCardLayout.style.display = 'none';
                    ministryWiseCard.style.display = 'block';
                }
            }
        });
    </script>
@endsection
