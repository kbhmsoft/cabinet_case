<style>
    .nav-link.active {
        color: #6D318D !important;
        background-color: transparent !important;
        border-color: transparent !important;
    }
    
</style>

<div class="dropdown">

    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
        <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 pulse pulse-primary" style="background-color: #ffffff">
            <span class="svg-icon svg-icon-xl svg-icon-primary">
                <i class="fas fa-bell"></i>
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

                            <li class="menu-item mt-2">
                                <a href="{{ route('cabinet.case.highcourtIndexApplications') }}" class="menu-link">
                                    <h5 class="menu-text font-weight-bolder mt-2">হাইকোর্ট বিভাগ 
                                        <?php
                                            $highCourtApplicationsCount = \App\Models\ApplicationFormAsMainDefendent::where('court', 2)->distinct('case_no')->count('case_no');
                                        ?>
                                        @if($highCourtApplicationsCount > 0)
                                            <span class="badge badge-danger float-right">{{ en2bn($highCourtApplicationsCount) }}</span>
                                        @else
                                            <span class="badge badge-danger float-right">{{ en2bn(0) }}</span>
                                        @endif
                                    </h5>
                                </a>
                            </li>
                        
                            
                            <li class="menu-item mt-2">
                                <a href="{{ route('cabinet.case.appealIndexApplications') }}" class="menu-link">
                                    <h5 class="menu-text font-weight-bolder mt-2">আপিল বিভাগ
                                        <?php
                                            $appealApplicationsCount = \App\Models\ApplicationFormAsMainDefendent::where('court', 1)->distinct('case_no')->count('case_no');
                                        ?>
                                        @if($appealApplicationsCount > 0)
                                            <span class="badge badge-danger float-right">{{ en2bn($appealApplicationsCount) }}</span>
                                        @else
                                            <span class="badge badge-danger float-right">{{ en2bn(0) }}</span>
                                        @endif
                                    </h5>
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
                                        {{-- <span class="menu-text font-weight-bolder">ব্যবহারকারীর তালিকা</span> --}}
                                        <span class="menu-text font-weight-bolder">বার্তা প্রেরন </span>
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
