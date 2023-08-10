@include('gov_case.case_register.create_css')
<fieldset>

    <div class="form-group row">
        <div class="col-lg-6 mb-5 mb-5">
            <label>সিএমপি নং <span class="text-danger">*</span></label>
            <input type="text" name="cmp_no" id="cmp_no" class="form-control form-control-sm" placeholder="মামলা নং "
                required="required" value="{{ $case->leave_to_appeal_no }}" disabled>
        </div>



        <div class="col-lg-6 mb-5 mb-5">
            <label>লিভ টু আপীল নং <span class="text-danger">*</span></label>
            <input type="text" name="leave_to_appeal_no" id="leave_to_appeal_no" class="form-control form-control-sm"
                placeholder="মামলা নং " required="required" value="{{ $case->leave_to_appeal_no }}" disabled>

        </div>



        <div class="col-lg-12 mb-5">
            <table class="table mb-5" width="100%" border="1" id="badiDiv" style="border:1px solid #dcd8d8;">
                <tr>
                    <th>রিট পিটিশনারের নাম <span class="text-danger">*</span> </th>
                    <th>ঠিকানা <span class="text-danger">*</span></th>
                </tr>
                <tbody>
                    @foreach ($caseBadi as $badi)
                        <tr>
                            <td>{{ $badi->name }}</td>
                            <td>{{ $badi->address }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="col-lg-6 mb-5 mb-5">
            <table width="100%" border="1" id="MainBibadiDiv" class="table mb-5" style="border:1px solid #dcd8d8;">

                <tr>
                    <th>মূল রেসপন্ডেন্ট নাম <span class="text-danger">*</span>
                    </th>

                </tr>
                <tbody>
                    @foreach ($mainBibadi as $bibadi)
                        <tr>
                            <td class="tg-nluh">{{ $bibadi->ministry->office_name_bn ?? '-' }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="col-lg-6 mb-5 mb-5">
            <table width="100%" border="1" id="bibadiDiv" class="table mb-5" style="border:1px solid #dcd8d8;">

                <tr>
                    <th>অন্যান্য রেসপন্ডেন্ট নাম <span class="text-danger">*</span>
                </tr>
                <tbody>
                    @foreach ($otherBibadi as $bibadi)
                        <tr>
                            <td class="tg-nluh">{{ $bibadi->ministry->office_name_bn ?? '-' }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>



        <div class="col-lg-6 mb-5 mb-5">
            <label>বিষয়বস্তু(সংক্ষিপ্ত)<small class="text-danger">
                </small> </label>
            <textarea name="subject_matter" class="form-control" id="subject_matter" rows="3" spellcheck="false" disabled>{{ $case->subject_matter }}</textarea>
        </div>


        <div class="col-lg-6 mb-5 mb-5">
            <label>এফিডেভিট দাখিলকারী রেসপন্ডেন্ট <span class="text-danger">*</span></label>
            <input type="text" name="cmp_no" id="cmp_no" class="form-control form-control-sm" placeholder=""
                required="required" disabled>
            {{-- <input type="hidden" name="caseId" value=""> --}}
            <span class="text-danger d-none vallidation-message">This field can
                not be empty</span>
        </div>


        <div class="col-lg-6 mb-5">
            <label>সংশ্লিষ্ট আইন কর্মকর্তা <br> (ধরনর মামলা উদ্ভূত)<span class="text-danger"></span></label>
            <input type="text" name="concern_person_designation" id="concern_person_designation"
                class="form-control form-control-sm" placeholder="" autocomplete="off" disabled
                value="{{ $concernpersondesig->name ?? ''}}">
        </div>

        <div class="col-lg-6 mb-5">
            <label>সংশ্লিষ্ট আইন কর্মকর্তার নাম<br> (ধরনর মামলা উদ্ভূত)<span class="text-danger"></span></label>
            <input type="text" name="appeal_concern_user_id" id="appeal_concern_user_id"
                class="form-control form-control-sm" placeholder="" autocomplete="off" disabled
                value="{{ $concernPersonName->name ?? ''}}">
        </div>


    </div>

    <div class="form-group row mt-5">
        <div class="col-lg-6 mb-5">
            <label>রায় ঘোষণার তারিখ<span class="text-danger"></span></label>
            <input type="text" name="result_date" id="result_date"
                class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর" autocomplete="off"
                disabled value="{{ $case->result_date }}">
        </div>


        <div class="col-md-6">
            <label class="form-group font-weight-bolder font-size-h5">রায় ফলাফল
            </label>
            <div class="radio-inline">
                <label class="radio">
                    <input type="radio" name="result" id="result" value="1"
                        {{ $case->in_favour_govt == 1 ? 'checked' : '' }} disabled />
                    <span></span>সরকারের পক্ষে</label>
                <label class="radio">
                    <input type="radio" name="result" id="result" value="2"
                        {{ $case->in_favour_govt == 2 ? 'checked' : '' }} disabled />
                    <span></span>সরকারের বিপক্ষে</label>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <label class="form-group font-weight-bolder font-size-h5">মামলার রায়ের সংক্ষিপ্ত বিবরণ</label>
        <textarea name="result_short_dtails" class="form-control" id="result_short_dtails" rows="3"
            spellcheck="false" disabled> {{ $case->result_short_dtails }} </textarea>
    </div>

    <div class="form-group row mt-5">
        <div class="col-lg-6 mb-5 mb-5">
            <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span class="text-danger"></span></label>
            <input type="text" name="result_copy_asking_date" id="result_copy_asking_date"
                class="form-control form-control-sm  common_datepicker" placeholder="" autocomplete="off"
                value="{{ $case->result_copy_asking_date }}" disabled>
        </div>


        <div class="col-lg-6 mb-5 mb-5">
            <label>রায়ের নকল প্রাপ্তির তারিখ<span class="text-danger"></span></label>
            <input type="text" name="result_copy_reciving_date" id="result_copy_reciving_date"
                class="form-control form-control-sm  common_datepicker" placeholder="" autocomplete="off"
                value="{{ $case->result_copy_reciving_date }}" disabled>
        </div>
    </div>

    {{-- <div class="col-md-12">
            <fieldset class="">
                <div class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                    <div class="d-flex align-items-center mr-2 py-2">
                        <h3 class="mb-0 mr-8">রায়
                            <span class="text-danger">*</span>
                        </h3>
                    </div>

                    <div class="symbol-group symbol-hover py-2">
                        <div class="symbol symbol-30 symbol-light-primary" data-toggle="tooltip" data-placement="top"
                            title="" role="button" data-original-title="ফাইল যুক্ত করুণ">

                            <div id="addFileRow">
                                <span class="symbol-label font-weight-bold bg-success">
                                    <i class="text-white fa flaticon2-plus font-size-sm"></i>
                                </span>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="mt-3 px-5">
                    <table width="100%" class="border-0 px-5" id="fileDiv" style="border:1px solid #dcd8d8;">
                        <tr></tr>
                    </table>
                    <input type="hidden" id="other_attachment_count" value="1">
                </div>
            </fieldset>
        </div> --}}

    {{-- end সংযুক্তি --}}
    </div>
</fieldset>
