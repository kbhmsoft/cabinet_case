<style>
    .notification_count {
        position: absolute;
        top: 36px;
    }

    .header-department-name {
        line-height: 28px;
    }
    .custom-span {
        font-weight: bold;
        font-size: 1.05rem;
        color: #fff;
        background-color: #3498db;
        padding: 0.25rem 1.25rem;
        border-radius: 5px;
        margin-right: 0.75rem;
        margin-top: 10px;
    }
</style>

@php
    $officeInfo = user_office_info();
    $roleID = Auth::user()->role_id;

    $case_status = DB::table('gov_case_registers')
        ->select('gov_case_registers.case_status_id', 'case_status.status_name', DB::raw('COUNT(gov_case_registers.id) as total_case'))
        ->leftJoin('case_status', 'gov_case_registers.case_status_id', '=', 'case_status.id')
        ->groupBy('gov_case_registers.case_status_id')
        ->where('gov_case_registers.action_user_role_id', $roleID)
        ->get();
    // dd($case_status);
    $notification_count = 0;

@endphp
@forelse ($case_status as $row)
    @php
        $notification_count += $row->total_case;
    @endphp
@empty
@endforelse

<div id="kt_header" class="header header-fixed">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Header Menu Wrapper-->
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">

        </div>
        <!--end::Header Menu Wrapper-->

        <!--begin::Topbar-->
        <div class="topbar">
            <!--begin::Notifications-->
            @include('layouts.partials.notifications')
            <!--end::Notifications-->

            <!--begin::Quick panel-->
            <div class="topbar-item">
                {{-- Header Notification Bar --}}
                {{-- <div class="btn btn-icon btn-clean btn-lg mr-1" id="kt_quick_panel_toggle">
                    <span class="svg-icon svg-icon-xl svg-icon-primary">
                        <!--begin::Svg Icon | path:media/svg/icons/Layout/Layout-4-blocks.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
                                <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                </div>
                @if ($notification_count != 0)
                    <div class="notification_count">
                        <span class="label bg-danger text-white font-size-lg" style="
                        font-size: 13px !important;
                    ">{{ $notification_count}} </span>
                    </div>
                @endif --}}
            </div>
            <!--end::Quick panel-->

            <!--begin::User-->
            <div class="topbar-item">
                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2"
                    id="kt_quick_user_toggle" style="width:320px !important; height: min-content;">
                    <div class="row header-department-name">
                        <div class="col-12">
                            <span
                                class="font-weight-bolder font-size-base font-size-h4 d-none d-md-inline mr-3 text-dark-100">{{ Auth::user()->name }}</span>
                            <span
                                class="label label-lg label-danger label-pill label-inline"><?= auth()->user()->role->name ?></span>
                        </div>

                        <div class="col-12 pb-2">
                            {{-- <span class="font-weight-bolder font-size-base font-size-h4 d-none d-md-inline mr-3 text-dark-100">{{ Auth::user()->govOffice->office_name_bn }}</span> --}}
                            <span
                                class="custom-span d-none d-md-inline">{{ Auth::user()->govOffice->office_name_bn ?? ''}}</span>

                        </div>
                    </div>
                </div>
            </div>


            <!--end::User-->
        </div>
        <!--end::Topbar-->

    </div>
    <!--end::Container-->
</div>
