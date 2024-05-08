<style>
    .nav-link.active {
        color: #6D318D !important;
        background-color: transparent !important;
        border-color: transparent !important;

    }
</style>
<div class="dropdown">

    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
        <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 pulse pulse-primary">
            <span class="svg-icon svg-icon-xl svg-icon-primary">
                <!--begin::Svg Icon | path:media/svg/icons/Code/Compiling.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path
                            d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
                            fill="#000000" opacity="0.3" />
                        <path
                            d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
                            fill="#000000" />
                    </g>
                </svg>

            </span>
            <span class="pulse-ring"></span>
        </div>
    </div>


    <div class="dropdown-menu mb-5 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg ">
        <form>

            <div class="d-flex flex-column pt-5 bgi-size-cover bgi-no-repeat rounded-top"
                style="background-color: #ffffff" {{-- style="background-image: url({{ asset('media/misc/bg-1.jpg') }})" --}}>
                {{-- <h4 class="d-flex flex-center rounded-top">
                    <span class="text-black">User Notifications</span>
                    <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">23 new</span>
                </h4> --}}
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="respondent-tab" data-toggle="tab" href="#respondent"
                            role="tab" aria-controls="respondent" aria-selected="true">মূল রেসপন্ডেন্ট হিসেবে
                            অন্তর্ভুক্তির আবেদন</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="message-tab" data-toggle="tab" href="#message" role="tab"
                            aria-controls="message" aria-selected="false">বার্তা</a>
                    </li>
                </ul>


                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="respondent" role="tabpanel"
                        aria-labelledby="respondent-tab">
                        <ul class="menu">
                            <li class="menu-item">
                                <a href="{{ route('cabinet.case.highcourtIndexApplications') }}" class="menu-link">
                                    <span class="menu-text font-weight-bolder">হাইকোর্ট বিভাগ</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('cabinet.case.appealIndexApplications') }}" class="menu-link">
                                    <span class="menu-text font-weight-bolder">আপিল বিভাগ</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="message" role="tabpanel" aria-labelledby="message-tab">
                        <ul class="menu-subnav">
                            @can('recent_messages')
                                <li class="menu-item {{ request()->is('cabinet/messages_recent') ? 'hilightMenu' : '' }}"
                                    aria-haspopup="true">
                                    <a href="{{ route('cabinet.messages_recent') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">সাম্প্রতিক বার্তা</span>

                                    </a>
                                </li>
                            @endcan

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

                </div>

            </div>

            {{-- <div class="tab-content">

                <div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">

                    <div class="scroll pr-7 mr-n7" data-scroll="true" data-height="300" data-mobile-height="200">

                        <div class="d-flex align-items-center mb-6">

                        </div>

                        <div class="d-flex align-items-center mb-6">

                        </div>

                        <div class="d-flex align-items-center mb-6">


                        </div>

                        <div class="d-flex align-items-center mb-6">


                        </div>

                        <div class="d-flex align-items-center mb-6">

                        </div>

                        <div class="d-flex align-items-center mb-6">

                        </div>

                        <div class="d-flex align-items-center mb-6">

                        </div>

                    </div>

                </div>
            </div> --}}

        </form>
    </div>
</div>
