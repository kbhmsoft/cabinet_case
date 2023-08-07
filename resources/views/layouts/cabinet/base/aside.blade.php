<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto" id="kt_brand">
        <!--begin::Logo-->
        <a href="{{ url('dashboard') }}" class="brand-logo">
            <!-- <img alt="Logo" src="media/logos/logo-light.png" /> -->
            <img alt="Logo" src="{{ asset(App\Models\SiteSetting::first()->site_logo) }}" height="45"
                class="mr-4" style="border: 0px solid #8a8a8a; padding: 2px;" />
            <!-- <span style="font-weight: bold; font-size: 25px; color: white;">Civil Suit</span> -->
        </a>
        <!--end::Logo-->
        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <!--begin::Svg Icon | path:media/svg/icons/Navigation/Angle-double-left.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                        <path
                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
        </button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->

    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu" data-menu-vertical="1" data-menu-scroll="1"
            data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            <ul class="menu-nav">
                <li class="menu-item {{ request()->is('dashboard') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                    <a href="{{ url('dashboard') }}" class="menu-link">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-tachometer-alt"></i>
                            ড্যাশবোর্ড</span>
                    </a>
                </li>

                @can('highcourt_division')
                    <li class="menu-item {{ request()->is('cabinet/case/highcourt', 'cabinet/case/highcourt/*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-university"></i> হাইকোর্ট
                                বিভাগ</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>

                            <ul class="menu-subnav">

                                @can('create_new_case')
                                    <li class="menu-item {{ request()->is('cabinet/case/highcourt/create') ? 'menu-item-open' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.case.highcourt.create') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">নতুন/চলমান হাইকোর্ট মামলা এন্ট্রি</span>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ request()->is('cabinet/case/highcourt/create/old') ? 'menu-item-open' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.case.highcourt.create.old') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">নিস্পত্তিকৃত হাইকোর্ট মামলা
                                                এন্ট্রি</span>
                                        </a>
                                    </li>
                                @endcan

                                <li class="menu-item {{ request()->is(['cabinet/case/highcourt']) ? 'menu-item-active' : '' }}"
                                    aria-haspopup="true">
                                    <a href="{{ route('cabinet.case.highcourt') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">সকল মামলার তালিকা</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->is(['cabinet/case/highcourt/running']) ? 'menu-item-active' : '' }}"
                                    aria-haspopup="true">
                                    <a href="{{ route('cabinet.case.highcourt.running') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">চলমান মামলার তালিকা</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->is(['cabinet/case/highcourt/complete']) ? 'menu-item-active' : '' }}"
                                    aria-haspopup="true">
                                    <a href="{{ route('cabinet.case.highcourt.complete') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">নিস্পত্তিকৃত মামলার তালিকা</span>
                                    </a>
                                </li>

                            </ul>

                        </div>
                    </li>
                @endcan

                @can('appeal_division')
                    <li class="menu-item {{ request()->is('cabinet/case/appellateDivision', 'cabinet/case/appellateDivision/*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-building"></i> আপিল
                                বিভাগ</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>

                            <ul class="menu-subnav">
                                @can('create_new_case')
                                    <li class="menu-item {{ request()->is('cabinet/case/appellateDivision/create') ? 'menu-item-open' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.case.appellateDivision.create') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder"> নতুন/চলমান আপিল মামলা এন্ট্রি</span>
                                        </a>
                                    </li>

                                    <li class="menu-item {{ request()->is('cabinet/case/appellateDivision/create/old') ? 'menu-item-open' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.case.appellateDivision.create.old') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder"> নিস্পত্তিকৃত আপিল মামলা এন্ট্রি</span>
                                        </a>
                                    </li>

                                    <li class="menu-item {{ request()->is(['cabinet/case/appellateDivision']) ? 'menu-item-active' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.case.appellateDivision') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">সকল মামলার তালিকা</span>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ request()->is(['cabinet/case/appellateDivision/running']) ? 'menu-item-active' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.case.appellateDivision.running') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">চলমান মামলার তালিকা</span>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ request()->is(['cabinet/case/appellateDivision/complete']) ? 'menu-item-active' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.case.appellateDivision.complete') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">নিস্পত্তিকৃত মামলার তালিকা</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>

                        </div>
                    </li>
                @endcan
                @can('case_audit_menu')
                    <li class="menu-item {{ request()->is('cabinet/case_audit') ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ url('cabinet/case_audit') }}" class="menu-link">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-history"></i> মামলা
                                নিরীক্ষা</span>
                        </a>
                    </li>
                @endcan
                @can('report_menu')
                    <li class="menu-item {{ request()->is('govcase/report/caselist') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-file-contract"></i> রিপোর্ট</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>

                            <ul class="menu-subnav">

                                @can('govt_case_report')
                                    <li class="menu-item {{ in_array(session()->get('currentUrlPath'), ['govcase/report/caselist']) ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('report.govcaselist') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">সরকারি মামলার রিপোর্ট</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>

                        </div>
                    </li>
                @endcan
                @can('manage_users_menu')
                    <li class="menu-item {{ in_array(session()->get('currentUrlPath'), ['cabinet/user-management', 'cabinet/user-role-management', 'cabinet/user-permissions', 'cabinet/user-permission-management']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ url('cabinet/user-management') }}" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-users"></i> ব্যাবহারকারী
                                পরিচালনা</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>

                            @can('users_list_menu')
                                <ul class="menu-subnav">
                                    <li class="menu-item {{ in_array(session()->get('currentUrlPath'), ['cabinet/user-management']) ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ url('cabinet/user-management') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">ব্যাবহারকারীর তালিকা</span>
                                        </a>
                                    </li>
                                </ul>
                            @endcan
                            @can('manage_role_menu')
                                <ul class="menu-subnav">
                                    <li class="menu-item {{ in_array(session()->get('currentUrlPath'), ['cabinet/user-role-management']) ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.roleManagement') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">ভূমিকা পরিচালনা</span>
                                        </a>
                                    </li>
                                </ul>
                            @endcan

                            @if (Auth::user()->role_id == 1 && Auth::user()->role->name == 'ডেভলপার')
                                <ul class="menu-subnav">
                                    <li class="menu-item {{ in_array(session()->get('currentUrlPath'), ['cabinet/user-permissions']) ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.permissionManagement') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">সকল অনুমতি</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @can('manage_permission_menu')
                                <ul class="menu-subnav">
                                    <li class="menu-item {{ in_array(session()->get('currentUrlPath'), ['cabinet/user-permission-management']) ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.permissionToUserManagement') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">অনুমতি প্রদান পরিচালনা করুন</span>
                                        </a>
                                    </li>
                                </ul>
                            @endcan

                        </div>
                    </li>
                @endcan

                @can('manage_office_menu')
                    <li class="menu-item {{ request()->is('cabinet/office', 'cabinet/office/create') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="la la-briefcase"></i> অফিস
                                ব্যবস্থাপনা</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            @can('create_new_office')
                                <ul class="menu-subnav">
                                    <li class="menu-item {{ in_array(session()->get('currentUrlPath'), ['cabinet/office/create']) ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.office.create') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">নতুন অফিস এন্ট্রি</span>
                                        </a>
                                    </li>
                                </ul>
                            @endcan
                            @can('all_office_list')
                                <ul class="menu-subnav">
                                    <li class="menu-item {{ in_array(session()->get('currentUrlPath'), ['cabinet/office']) ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.office') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">অফিসের তালিকা</span>
                                        </a>
                                    </li>
                                </ul>
                            @endcan
                        </div>
                    </li>
                @endcan

                @can('general_settings_menu')
                    <li class="menu-item {{ request()->is('cabinet/settings/*', 'cabinet/settings/category_type/*', 'cabinet/settings/office_type/*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-cogs"></i> জেনারেল সেটিংস</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">

                                @can('case_categories_menu')
                                    <li class="menu-item {{ request()->is('cabinet/settings/category/*') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.settings.category.list') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">মামলার ক্যাটেগরি</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('case_types_menu')
                                    <li class="menu-item {{ request()->is('cabinet/settings/category_type/*') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.settings.category_type.list') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">মামলার শ্রেণী</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('office_types_menu')
                                    <li class="menu-item {{ request()->is('cabinet/settings/office_type/*') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.settings.office_type.list') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">অফিসের শ্রেণী</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>

                        </div>
                    </li>
                @endcan

                <!-- {{-- // ========== Notification start =================== --}} -->
                @can('notification_menu')
                    <li class="menu-item {{ request()->is(['cabinet/hearing_date', 'cabinet/results_completed', 'cabinet/case/action/receive/*', 'cabinet/case/action/details/*']) ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-bell"></i> নোটিফিকেশন</span>
                            @if ($notification_count != 0)
                                <span class="menu-label">
                                    <span class="label label-rounded label-danger">{{ $notification_count }}</span>
                                </span>
                            @endif
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">

                                @can('hearing_date_fixed')
                                    <li class="menu-item {{ request()->is('cabinet/hearing_date') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.hearing_date') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">শুনানির তারিখ নির্ধারণ করা হয়েছে</span>
                                            <span class="menu-label">
                                                <span class="label label-rounded label-danger">{{ $CaseHearingCount }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endcan
                                @can('case_result_done_menu')
                                    <li class="menu-item {{ request()->is('cabinet/results_completed') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.results_completed') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">ফলাফল সম্পন্ন</span>
                                            <span class="menu-label">
                                                <span class="label label-rounded label-danger">{{ $CaseResultCount }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endcan
                                @can('main_plaintiff_for_case')

                                    @forelse($case_status as $row)
                                        <li class="menu-item {{ request()->is(['cabinet/case/action/receive/' . $row->case_status_id, 'cabinet/case/action/details/*']) ? 'hilightMenu' : '' }}"
                                            aria-haspopup="true">
                                            <a href="{{ route('cabinet.case.action.receive', $row->case_status_id) }}"
                                                class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                                <span class="menu-text font-weight-bolder">{{ $row->status_name }}</span>
                                                <span class="menu-label">
                                                    <span
                                                        class="label label-rounded label-danger">{{ $row->total_case }}</span>
                                                </span>
                                            </a>
                                        </li>
                                    @empty
                                    @endforelse
                                @else
                                    @forelse($case_status as $row)
                                        <li class="menu-item {{ request()->is('cabinet/case/action/receive/' . $row->case_status_id) ? 'hilightMenu' : '' }}"
                                            aria-haspopup="true">
                                            <a href="{{ route('cabinet.case.action.receive', $row->case_status_id) }}"
                                                class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                                <span class="menu-text font-weight-bolder">{{ $row->status_name }}</span>
                                                <span class="menu-label">
                                                    <span
                                                        class="label label-rounded label-danger">{{ $row->total_case }}</span>
                                                </span>
                                            </a>
                                        </li>
                                    @empty
                                    @endforelse
                                @endcan


                                @can('hearing_date_fixed')
                                    <li class="menu-item {{ request()->is('cabinet/case/action/receive/' . $row->case_status_id) ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.hearing_date') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">শুনানির তারিখ নির্ধারণ করা হয়েছে</span>
                                            <span class="menu-label">
                                                <span class="label label-rounded label-danger">{{ $CaseHearingCount }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endcan


                                @if (count($case_status) == 0)
                                    <div class="d-flex align-items-center rounded p-5 mb-5">
                                        <span>কোন নোটিফিকেশন পাওয়া যায়নি</span>
                                    </div>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endcan
                @can('message_menu')
                    <li class="menu-item {{ request()->is('cabinet/notice/list', 'cabinet/messages', 'cabinet/messages/*', 'cabinet/messages_recent', 'cabinet/messages_request') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-envelope" aria-hidden="true"></i>
                                বার্তা</span>
                            @if ($Ncount != 0)
                                <span class="menu-label">
                                    <span class="label label-rounded label-danger">{{ $Ncount }}</span>
                                </span>
                            @endif
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">

                                @can('recent_messages')
                                    <li class="menu-item {{ request()->is('cabinet/messages_recent') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.messages_recent') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">সাম্প্রতিক বার্তা</span>
                                            @if ($NewMessagesCount != 0)
                                                <span class="menu-label">
                                                    <span
                                                        class="label label-rounded label-danger">{{ $NewMessagesCount }}</span>
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                @endcan

                                @if ($msg_request_count != 0)
                                    <li class="menu-item {{ request()->is('cabinet/messages_request') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.messages_request') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">নতুন বার্তা অনুরোধ</span>
                                            <span class="menu-label">
                                                <span
                                                    class="label label-rounded label-danger">{{ $msg_request_count }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endif

                                @can('notice_menu')
                                    <li class="menu-item {{ request()->is('cabinet/notice/list') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.notice.list') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">নোটিশ</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('notice_users_list')
                                    <li class="menu-item {{ request()->is('cabinet/messages') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ route('cabinet.messages') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">ব্যবহারকারীর তালিকা</span>
                                        </a>
                                    </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                @endcan
                @php
                    
                    $supremeCourtCaseCout = DB::select(DB::raw("SELECT count(id) as total_hearing FROM gov_case_notify_supre_court WHERE date = '27/02/2023'"))[0]->total_hearing;
                    
                @endphp
                @can('verify_case_information_menu')
                    <li class="menu-item {{ request()->is('search/supremecourt/case', 'search/supremecourt/causelist', 'show/notification/supremecourt') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-search"></i> মামলার তথ্য
                                যাচাই</span>

                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">

                                @can('search_in_cases')
                                    <li class="menu-item {{ request()->is('search/supremecourt/case') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ url('search/supremecourt/case') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">মামলা অনুসন্ধান</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('causelist_menu')
                                    <li class="menu-item {{ request()->is('search/supremecourt/causelist') ? 'hilightMenu' : '' }}"
                                        aria-haspopup="true">
                                        <a href="{{ url('search/supremecourt/causelist') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                            <span class="menu-text font-weight-bolder">কজলিস্ট</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('today_hearing_cases')
                                    @if ($supremeCourtCaseCout != 0)
                                        <li class="menu-item {{ request()->is('show/notification/supremecourt') ? 'hilightMenu' : '' }}"
                                            aria-haspopup="true">
                                            <a href="{{ url('show/notification/supremecourt') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                                <span class="menu-text font-weight-bolder">আজকের শুনানির তালিকা</span>
                                            </a>
                                        </li>
                                    @endif
                                @endcan


                            </ul>
                        </div>
                    </li>
                @endcan



            </ul> <!--end::Menu Nav-->
        </div> <!--end::Menu Container-->
    </div> <!--end::Aside Menu-->
</div> <!-- /aside-left -->
</div> <!-- /aside-left -->
