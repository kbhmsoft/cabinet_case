    <div class="container">
        <div class="timeline timeline-3">
            <div class="timeline-items">
                @forelse ($caseLogs as $row)
                    <div class="timeline-item">
                        <div class="timeline-media">
                            <i class="fa fas fa-angle-double-up text-warning"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="mr-2">
                                    <a href="javascript:void(0)"
                                        class="text-dark-75 text-hover-primary font-weight-bolder font-size-h5">
                                        {{  $row->case_status->status_name ?? '' }}
                                    </a>
                                    <span class="text-muted ml-2 font-size-h6 ">
                                        {{ en2bn($row->created_at) }} |
                                        {{ $row->user->name ?? '' }} |
                                        {{  $row->user->role->role_name ?? '' }}
                                    </span>
                                </div>
                            </div>
                            @if ($row->comments)
                                <p class="p-0 font-italic font-size-h5"><?=nl2br($row->comments)?></p>
                            @endif
                        </div>
                    </div>
                @empty
                <div class="p-5 bg-secondary text-center rounded" >
                    <div class="alert-text font-size-h4">কোন তথ্য পাওয়া যাইনি</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
