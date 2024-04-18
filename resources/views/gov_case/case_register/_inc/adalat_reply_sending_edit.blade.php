@extends('layouts.cabinet.cab_default')

@section('content')

    @php
        $pass_year_data = '<option value="">-- নির্বাচন করুন --</option>';
        for ($i = 1995; $i <= date('Y'); $i++) {
            $pass_year_data .= '<option value="' . $i . '">' . $i . '</option>';
        }

    @endphp


    @include('gov_case.case_register.create_css')
    {{-- @dd($case) --}}
    <!--begin::Row-->
    <div class="row">

        {{-- <div class="col-md-12"> --}}
        <!--begin::Card-->
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
            </div>
            <!-- <div class="loadersmall"></div> -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <div class="table-responsive ajax-data-container pt-3">
                    <div class="tab-content" id="myTabContent">
                       {{-- ------------- start আদালতে জবাব দাখিল ------------- --}}
                       <div class="tab-pane" id="adalat_reply" role="tabpanel" aria-labelledby="home-tab">
                        <form id="adalatReplySubmitForm" action="javascript:void(0)" class="form" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row_int">
                                <div class="col-lg-12">
                                    <!--begin::Card-->
                                    <input type="hidden" id="caseIDForAnswer" name="case_id"  value="{{ $case->id }}">
                                    <fieldset class="mb-8">
                                        <div class="col-lg-12 mb-5">

                                            <div class="col-md-6">
                                                <label class="form-group font-weight-bolder font-size-h5">আদালতে জবাব (affidavit) দাখিল করা হয়েছে কিনা
                                                </label>
                                                <div class="radio-inline">
                                                    <label class="radio">
                                                        <input type="radio" name="adalat_reply_submit_have"
                                                            id="adalat_reply_submit_have" value="1" />
                                                        <span></span>হ্যাঁ</label>
                                                    <label class="radio">
                                                        <input type="radio" name="adalat_reply_submit_have"
                                                            id="adalat_reply_submit_have_not" value="0" checked />
                                                        <span></span>না</label>
                                                </div>
                                            </div>

                                            <div class="adalat_reply_div">
                                                <div class="form-group row">
                                                    <div class="col-lg-6 mb-5 mt-8">
                                                        <label>আদালতে জবাব দাখিলের তারিখ </label>
                                                        <input type="text" name="adalat_reply_sending_date"
                                                            id="adalat_reply_sending_date"
                                                            class="form-control form-control-sm  common_datepicker"
                                                            placeholder="দিন/মাস/বছর" autocomplete="off">
                                                    </div>

                                                    {{-- starting সংযুক্তি  --}}
                                                    <div class="col-md-12 mt-8">
                                                        <fieldset class="">
                                                            <div
                                                                class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                                                <div class="d-flex align-items-center mr-2 py-2">
                                                                    <h3 class="mb-0 mr-8">সংযুক্তি (আদালতে জবাব দাখিল কপি সংযুক্ত
                                                                        করুন)
                                                                        <sub class="text-danger">(PDF, সর্বোচ্চ সাইজ:
                                                                            5MB)</sub>
                                                                    </h3>
                                                                </div>

                                                                <div class="symbol-group symbol-hover py-2">
                                                                    <div class="symbol symbol-30 symbol-light-primary"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="" role="button"
                                                                        data-original-title="ফাইল যুক্ত করুণ">

                                                                        <div id="addAdalatReplyFileRow">
                                                                            <span
                                                                                class="symbol-label font-weight-bold bg-success">
                                                                                <i
                                                                                    class="text-white fa flaticon2-plus font-size-sm"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-3 px-5">
                                                                <table width="100%" class="border-0 px-5"
                                                                    id="adalatReplyFileDiv"
                                                                    style="border:1px solid #dcd8d8;">
                                                                    <tr></tr>
                                                                </table>
                                                                <input type="hidden" id="adalat_reply_attachment_count"
                                                                    value="1">
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="form-footer" style="display: flex;justify-content: center;">
                                <button type="submit" id="adalatReplySubmitSaveBtn"
                                    class="action-button submit-button">সংরক্ষণ</button>
                            </div>
                        </form>
                    </div>
                    {{-- ------------- end আদালতে জবাব দাখিল  ------------- --}}



                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
        {{-- </div> --}}

    </div>
    <!--end::Row-->

@endsection

@section('styles')
@endsection

@section('scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('.tab-content .tab-pane:first-child').addClass('active');
            $('.myTab a').click(function(e) {
                e.preventDefault();
                var targetTab = $(this).attr('href');
                $('.tab-content .tab-pane').removeClass('active');
                $(targetTab).addClass('active');
            });
        });
    </script>

    @include('gov_case.case_register._inc.action_js')


    <script type="text/javascript">
        $(document).ready(function() {
            addBadiRowFunc();
            addBibadiRowFunc();
        });
    </script>

@endsection
