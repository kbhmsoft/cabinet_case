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


            <form id="leaveToAppealForm" action="javascript:void(0)" class="form" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    {{-- <div class="table-responsive ajax-data-container pt-3"> --}}

                    <div class="form-group row">
                        <input type="hidden" id="caseIDForAnswer" name="case_id" value="{{ $case->id }}">
                        <div class="col-lg-4 mb-5">
                            <label>লিভ টু আপিল নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="leave_to_appeal_no" id="leave_to_appeal_no"
                                class="form-control form-control-sm"autocomplete="off" required>
                        </div>
                        <div class="col-lg-4 mb-5">
                            <label>লিভ টু আপিল দায়েরের তারিখ <span class="text-danger">*</span></label>
                            <input type="text" name="leave_to_appeal_date" id="leave_to_appeal_date"
                                class="form-control form-control-sm common_datepicker"autocomplete="off"
                                placeholder="দিন/মাস/বছর" required>
                        </div>

                        <div class="col-lg-4">
                            <label>প্রস্তাবের বিষয়বস্তু (বাংলায়)<span class="text-danger"></span></label>
                            <input type="text" name="contents_of_proposal_leave_to_appeal"
                                class="form-control form-control-sm" autocomplete="off">
                        </div>
                        <div class="col-lg-4 mb-5">
                            <label>যে মামলার পরিপ্রেক্ষিতে (বাংলায়)<span class="text-danger"></span></label>
                            <input type="text" name="sending_motions_in_view_of_that_litigation_leave_to_appeal"
                                class="form-control form-control-sm" autocomplete="off">
                        </div>
                        <div class="col-lg-4 mb-5">
                            <label>প্রস্তাব তারিখ(বাংলায়) <span class="text-danger"></span></label>
                            <input type="text" name="proposal_date_leave_to_appeal"
                                class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                autocomplete="off">
                        </div>
                        <div class="col-lg-4 mb-5">
                            <label>প্রস্তাব স্মারক নম্বর <span class="text-danger"></span></label>
                            <input type="text" name="proposal_memorial_leave_to_appeal"
                                id="proposal_memorial_leave_to_appeal"
                                class="form-control form-control-sm"autocomplete="off">
                        </div>

                        <div class="col-lg-4 mb-5">
                            <label>যোগাযোগের ইমেইল (ইংরেজিতে) <span class="text-danger"></span></label>
                            <input type="email" name="contact_email_leave_to_appeal" id="contact_email_leave_to_appeal"
                                class="form-control form-control-sm"autocomplete="off">
                        </div>

                        <div class="col-lg-4 mb-5">
                            <label>ফোকাল পার্সনের নাম (বাংলায়) <span class="text-danger"></span></label>
                            <input type="text" name="focal_person_name_leave_to_appeal"
                                id="focal_person_name_leave_to_appeal"
                                class="form-control form-control-sm "autocomplete="off">
                        </div>

                        <div class="col-lg-4 mb-5">
                            <label>ফোকাল পার্সনের পদবী (বাংলায়) <span class="text-danger"></span></label>
                            <input type="text" name="focal_person_designation_leave_to_appeal"
                                id="focal_person_designation_leave_to_appeal"
                                class="form-control form-control-sm "autocomplete="off">
                        </div>

                        <div class="col-lg-4 mb-5">
                            <label>ফোকাল পার্সনের মোবাইল নম্বর (ইংরেজিতে) <span class="text-danger"></span></label>
                            <input type="text" name="focal_person_mobile_leave_to_appeal"
                                id="focal_person_mobile_leave_to_appeal"
                                class="form-control form-control-sm "autocomplete="off">
                        </div>
                        <div class="col-md-12">
                            <fieldset class="">
                                <div
                                    class="rounded bg-success-o-75 d-flex align-items-center justify-content-between flex-wrap px-5 py-0">
                                    <div class="d-flex align-items-center mr-2 py-2">
                                        <h3 class="mb-0 mr-8">সংযুক্তি (লিভ টু আপিলের কপি সংযুক্ত করুন)
                                            <span class="text-danger">*</span>
                                        </h3>
                                    </div>

                                    <div class="symbol-group symbol-hover py-2">
                                        <div class="symbol symbol-30 symbol-light-primary" data-toggle="tooltip"
                                            data-placement="top" title="" role="button"
                                            data-original-title="ফাইল যুক্ত করুণ">

                                            <div id="addLeaveToAppealFileRow">
                                                <span class="symbol-label font-weight-bold bg-success">
                                                    <i class="text-white fa flaticon2-plus font-size-sm"></i>
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="mt-3 px-5">
                                    <table width="100%" class="border-0 px-5" id="leaveToAppealFileDiv"
                                        style="border:1px solid #dcd8d8;">
                                        <tr></tr>
                                    </table>
                                    <input type="hidden" id="leaveToAppeal_attachment_count" value="1">
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    {{-- </div> --}}
                </div>
                <div class="form-footer mb-5" style="display: flex;justify-content: center;">
                    <button type="submit" id="leaveToAppealSaveBtn" class="action-button submit-button">সংরক্ষণ</button>
                </div>

            </form>

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

            $('.common_datepicker').datepicker({
                orientation: "bottom left",
                format: "dd/mm/yyyy",
                todayHighlight: true,
                viewMode: 'years',
            });
        });
    </script>




    {{-- @include('gov_case.case_register._inc.action_js') --}}
    <script type="text/javascript"></script>
    <script type="text/javascript">
        // dynamically change high court / appeal court
        $(document).ready(function() {
            addLeaveToAppealFileRowFunc();

        });


        // ============= Add leaveToAppeal Attachment Row ========= start =========
        $("#addLeaveToAppealFileRow").click(function(e) {
            addLeaveToAppealFileRowFunc();
        });
        //add row function
        function addLeaveToAppealFileRowFunc() {
            var count = parseInt($('#leaveToAppeal_attachment_count').val());
            var formType = $('#formType').val();
            $('#leaveToAppeal_attachment_count').val(count + 1);
            var items = '';
            items += '<tr>';
            items += '<td><input type="text" name="leave_to_appeal_file_type[]" id="customFileName' + count +
                '" class="form-control form-control-sm" placeholder="" ><span class="text-danger d-none vallidation-message">This field can not be empty</span></td>';
            items +=
                '<td><div class="custom-file"><input type="file" accept="application/pdf" name="leave_to_appeal_file_name[]" onChange="leaveToAppealAttachmentTitle(' +
                count + ',this)" class="custom-file-input" id="customLeaveToAppealFile' + count +
                '" /><label id="file_error' +
                count +
                '" class="text-danger font-weight-bolder mt-2 mb-2"></label> <label class="custom-file-label custom-leaveToAppeal-input' +
                count + '" for="customFile' + count + '">ফাইল নির্বাচন করুন</label></div></td>';
            items +=
                '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            items += '</tr>';
            $('#leaveToAppealFileDiv tr:last').after(items);

            if (formType == 'edit') {
                $(`#customFile${count}`).attr('required', false);
                $(`#customFileName${count}`).attr('required', false);
            }
        }


        //remove row function
        function removeBibadiRow(id) {
            $(id).closest("tr").remove();
        }
        //Attachment Title Change
        function leaveToAppealAttachmentTitle(id) {
            // var value = $('#customFile' + id).val();
            var value = $('#customLeaveToAppealFile' + id)[0].files[0];
            $('.custom-leaveToAppeal-input' + id).text(value['name']);
        }



        $('#leaveToAppealForm').submit(function(e) {
            // alert(1);
            e.preventDefault();
            $('#leaveToAppealSaveBtn').addClass('spinner spinner-white spinner-right disabled');
            Swal.fire({
                title: 'আপনি কি লিভ টু আপিলের তথ্য সংরক্ষণ করতে চান?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(this);
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('cabinet.case.leaveToAppealStore') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: (data) => {
                            $('#leaveToAppealSaveBtn').removeClass(
                                'spinner spinner-white spinner-right disabled');
                            $orderData = data;
                            Swal.fire(
                                'Saved!',
                                'লিভ টু আপিলের তথ্য সফলভাবে সংরক্ষণ করা হয়েছে',
                                'success'
                            ).then((result) => {
                                window.location.href = "{{ route('cabinet.case.highcourt') }}";
                            })
                            console.log(data);

                        },
                        error: function(data) {
                            console.log(JSON.stringify(data['responseJSON']['errors'][
                                    'leave_to_appeal_no'
                                ]
                                [0]));

                            Swal.fire(
                                'Oops...!',
                                data['responseJSON']['errors']['leave_to_appeal_no'][0],
                                'error'
                            )
                            // swal("Oops...", data.responseJSON.message, "error");
                            $('#leaveToAppealSaveBtn').removeClass(
                                'spinner spinner-white spinner-right disabled');

                        }
                    });
                }
            })

        });
    </script>
@endsection
