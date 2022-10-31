    <div class="container">

                <fieldset class="">
                    <div class="rounded bg-success-o-100 d-flex align-items-center justify-content-between flex-wrap px-5 py-0 mb-2">
                        <div class="d-flex align-items-center mr-2 py-2">
                            <h3 class="mb-0 mr-8">এসএফ এর প্রতিবেদন </h3>
                        </div>
                    </div>
                        @forelse ($files as $key => $row)
                            @if ($row->file_type == 'SF')
                                <div class="form-group mb-2" id="deleteFile{{ $row->id }}">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn bg-success-o-75" type="button">{{ en2bn(++$key) . ' - নম্বর :' }}</button>
                                        </div>
                                        <input readonly type="text" class="form-control" value="{{ $row->file_type ?? '' }}" />
                                        <div class="input-group-append">
                                            <a href="{{ asset($row->file_path . $row->file_name) }}" target="_blank" class="btn btn-sm btn-success font-size-h5 float-left">
                                                <i class="fa fas fa-file-pdf"></i>
                                                <b>দেখুন</b>
                                            </a>
                                        </div>
                                        {{-- <div class="input-group-append">
                                            <a href="javascript:void(0);" id="" onclick="deleteFile({{ $row->id }} )" class="btn btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                                <b>মুছুন</b>
                                            </a>
                                        </div> --}}
                                    </div>
                                </div>
                            @endif
                        @empty
                        <div class="pt-5">
                            <p class="text-center font-weight-normal font-size-lg">কোনো এসএফ এর প্রতিবেদন খুঁজে পাওয়া যায়নি</p>
                        </div>
                        @endforelse
                </fieldset>
            {{-- @endif --}}
        {{-- @endif --}}
        <br>
        <br>

        <div class="alert alert-danger" style="display:none"></div>

        <div class="alert alert-custom alert-light-success fade show mb-9" role="alert" id="sfReportUploadSuccess"
            style="display:none">
            <div class="alert-icon">
                <i class="flaticon2-check-mark"></i>
            </div>
            <div class="alert-text font-size-h3"> সফলভাবে এস এফের প্রতিবেদন আপলোড করা হয়েছে</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">
                        <i class="ki ki-close"></i>
                    </span>
                </button>
            </div>
        </div>
        <div id="sfInstantView">
            {{-- @if($case->sf_scan1 || $case->sf_scan2)
                <embed class="hide_old_finalSF" src="{{ asset('uploads/sf_report/' . $case->sf_scan1) }}" type="application/pdf" width="100%"
                height="600px" />
            @else
            <!--begin::Notice-->
                <div id="hide_old_finalSF" class="alert alert-custom alert-light-danger fade show mb-9" role="alert">
                    <div class="alert-icon">
                        <i class="flaticon-warning"></i>
                    </div>
                    <div class="alert-text font-size-h3">চুড়ান্ত প্রতিবেদন আপলোড করা হয়নি</div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">
                                <i class="ki ki-close"></i>
                            </span>
                        </button>
                    </div>
                </div>
            <!--end::Notice-->
            @endif --}}
        </div>

        @if(userInfo()->role_id == 33 || userInfo()->role_id == 31 || userInfo()->role_id == 28)
            @if($case->case_status_id == 38)
                <div class="row justify-content-md-center mt-5">
                    <form method="POST" action="javascript:void(0)" id="ajax-file-upload" enctype="multipart/form-data">
                        @csrf
                        <fieldset style="width: 500px;">
                            <legend> প্রতিবেদনের স্ক্যান কপি সংযুক্তি <span class="text-danger">*</span></legend>
                            <div class="col-lg-12 mb-5">
                                <div class="form-group">
                                    <label></label>
                                    <div></div>
                                    <div class="custom-file">
                                        <input type="file" name="sf_report" class="custom-file-input" id="customFile" required/>
                                        <label class="custom-file-label" for="customFile">ফাইল নির্বাচন
                                            করুন</label>
                                    </div>
                                </div>

                                <input type="hidden" name="hide_case_id" id="hide_case_id" value="{{ $case->id }}">
                                <div class="progress">
                                    <div class="progress-bar"></div>
                                </div>

                                <!-- <div id="uploadStatus"></div> -->
                            </div>
                        </fieldset>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-7">
                                    <button type="submit" class="btn btn-primary font-weight-bold font-size-h2 px-5 py-3"><i
                                            class="flaticon2-box icon-3x"></i> আপলোড করুন</button>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class='progress' id="progress_div"></div>
                    <div class='bar' id='bar1'></div>
                    <div class='percent' id='percent1'></div>

                </div>
            @endif
        @endif

    </div>
