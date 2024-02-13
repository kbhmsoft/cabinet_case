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



            <!--begin::User-->
            <div class="topbar-item">
                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2"
                    id="kt_quick_user_toggle" style="width:320px !important; height: min-content;">
                    <div class="row header-department-name">
                        <div class="col-12">
                            <span
                                class="font-weight-bolder font-size-base font-size-h4 d-none d-md-inline mr-3 text-dark-100">{{ Auth::user()->name }}</span>
                            <span
                                class="label label-lg label-danger label-pill label-inline"><?= auth()->user()->role->name_bn ?></span>
                        </div>

                        <div class="col-12 pb-2">
                            {{-- <span class="font-weight-bolder font-size-base font-size-h4 d-none d-md-inline mr-3 text-dark-100">{{ Auth::user()->govOffice->office_name_bn }}</span> --}}
                            <span class="custom-span d-none d-md-inline">{{ Auth::user()->unit_name_bn ?? '' }}, {{ Auth::user()->govOffice->office_name_bn ?? '' }}</span>
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
