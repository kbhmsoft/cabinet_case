<div class="row">
    <div class="col-xl-9">
        <!--begin::Card-->
        <div class="card card-custom gutter-b bg-primary text-light">
            <div class="card-body">
                <div class="mr-0">
                    <div class="h4 pb-1"><i class="fa fas fa-hashtag text-light icon-md"></i>
                        <span class="">&nbsp;মামলা নং-</span> {{ $case->case_no  ?? ''}}
                    </div>
                    <div class="h4 pt-0"><i class="fa fas fa-map-marker-alt text-light icon-md ml-0 mr-1"></i>
                        <span class="">&nbsp;মামলার অবস্থানঃ</span> {{ $case->case_status->status_name ?? '' }}
                    </div>
                    <?php //echo CommonController::bn2en("This is ২০১৬\n");
                    ?>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <div class="col-xl-3">
        @if ($case->status != 3)
            @if ($case->action_user_role_id == userInfo()->role_id)
                <a href="javascript:void(0)" class="btn btn-primary btn-shadow font-weight-bolder font-size-h1 px-12 py-4"
                    data-toggle="modal" data-target="#caseForwardModal" id="forwardButton">
                    প্রেরণ করুন <i class="fa fas fa-paper-plane icon-3x"></i>
                </a>
            @endif
        @endif

        <!-- Modal-->
        <div class="modal fade" id="caseForwardModal" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bolder font-size-h3" id="exampleModalLabel">নথি প্রেরণ করুন
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>

                        <form class="form" method="post" action="{{ url('action.forward') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row mb-0">
                                <div class="col-lg-5">
                                    <!-- <div class="form-group"> -->
                                    <label class="text-primary font-size-h4">প্রাপক নির্বাচন করুন <span
                                            class="text-danger">*</span></label>
                                    <div class="radio-list">
                                        @foreach ($roles as $key => $row)
                                                @php
                                                $disable = '';
                                                $radio_disable = '';
                                                $checked = '';

                                                if ($row->id == Auth::user()->role_id) {
                                                    $disable = 'disabled';
                                                    $radio_disable = 'radio-disabled';
                                                }

                                                if ($row->id == 27 && $row->id != Auth::user()->role_id) {
                                                    $checked = 'checked';
                                                } elseif($row->id == 28 && Auth::user()->role_id == 27) {
                                                    $checked = 'checked';
                                                }
                                                else {
                                                    $checked = '';
                                                }
                                            @endphp
                                            <label class="radio {{ $radio_disable }}">
                                                <input type="radio" name="group" value="{{ $row->id }}"
                                                    {{ $disable }} {{ $checked }} />
                                                <!-- <span></span>{{ $row->role_name . $row->id }} -->
                                                <span></span>{{ $row->role_name }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <!-- </div> -->
                                </div>

                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <input type="hidden" name="hide_forward_id" value="{{ $forward_map->forward_role_id ?? ''}}">
                                        <label class="text-primary font-size-h4">স্ট্যাটাস নির্বাচন করুন <span
                                                class="text-danger">*</span></label>
                                            <select name="status_id" id="status_id" class="form-control form-control-sm">
                                                <!-- <span id="loading"></span> -->
                                                <option value="">-- নির্বাচন করুন --</option>
                                            </select>

                                    </div>

                                    <div class="form-group" id="changeRemarks">
                                        <label class="text-primary font-size-h4">মন্তব্য প্রদান করুন
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="comment" id="comment" class="form-control form-control-solid" rows="7" style="border: 1px solid #ccc;"></textarea>
                                    </div>
                                    <div class="form-group" id="minSelection" style="display: none;">
                                        <fieldset>
                                            <legend>রেস্পন্ডেন্ট মন্ত্রণালয় নির্বাচন করুণ</legend>
                                            @foreach($caseMainBibadi as $key=>$bibadi)
                                                    <input type="radio" name="main_min_id" id="main_min_id" value="{{ $bibadi->ministry_id }}"/>
                                                    <span></span><b>{{ $bibadi->ministry->office_name_bn ?? '-' }}</b><br>
                                            @endforeach
                                        </fieldset>  
                                    </div>
                                    <div class="form-group" id="deptSelection" style="display: none;">
                                        <fieldset>
                                            <legend>রেস্পন্ডেন্ট অধিদপ্তর নির্বাচন করুণ</legend>
                                            @foreach($caseMainBibadi as $key=>$bibadi)
                                                    <input type="radio" name="main_dept_id" id="main_dept_id" value="{{ $bibadi->department_id }}"/>
                                                    <span></span><b>{{ $bibadi->department->office_name_bn ?? '-' }}</b><br>
                                            @endforeach
                                        </fieldset>  
                                    </div>
                                    <input type="hidden" name="hide_case_id" id="hide_case_id"
                                        value="{{ $case->id ?? '' }}">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold font-size-h5"
                            data-dismiss="modal">বন্ধ করুন</button>
                        <button type="button" id="formSubmit" class="btn btn-primary font-weight-bold font-size-h5">প্রাপককে পাঠিয়ে দিন</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
