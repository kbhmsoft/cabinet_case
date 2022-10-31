<div class="container">
    <div class="row">
        @if (userInfo()->role_id == 31 || userInfo()->role_id == 33)
            @if ($case->case_status_id == 41)
            <div class="col-md-12">
                <div class="alert alert-custom alert-secondary alert-shadow fade show gutter-b" role="alert">
                    <div class="alert-icon">
                        <span class="svg-icon svg-icon-primary svg-icon-xl">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                                    <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                    </div>
                    <div class="alert-text h4">
                        {{-- <a target="_blank" href="{{ route('cabinet.case.edit', $case->id) }}"> --}}
                        <a href="{{ route('cabinet.case.edit', $case->id) }}?red={{  url()->current() }}">
                        <u>মামলার নিবন্ধন সম্পূর্ণ করুন</u>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        @endif
        <div class="col-md-6">
            <table class="table table-striped border">
                <thead>
                    <tr>
                        <th class="h3" scope="col" colspan="2">সাধারণ তথ্য</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">মামলা নং</th>
                        <td>{{ $case->case_no ?? '' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">মামলা রুজুর তারিখ</th>
                        <td>{{ en2bn($case->date_issuing_rule_nishi) ?? '' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">বিষয়বস্তু(সংক্ষিপ্ত)</th>
                        <td>{{ $case->subject_matter }}</td>
                    </tr>
                    @if($case->gov_case_ref_id)
                        <tr>
                           <th scope="row">পূর্বের মামলা নং </th>
                           <td><a href="{{ route('cabinet.case.details', $case->gov_case_ref_id) }}" target="_blank">{{ $case->ref_gov_case_no }}</a></td>
                        </tr>
                     @endif
                    <tr>
                        <th scope="row">স্থগিতাদেশের বিবরণ</th>
                        <td>{{ $case->postponed_details ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">গুরুত্বপূর্ণতার কারণ</th>
                        <td>{{ $case->important_cause ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">অন্তর্বর্তীকালীন আদেশের বিবরণ(যদি থাকে )</th>
                        <td>{{ $case->interim_order ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">ফলাফল</th>
                        <td>
                            {{ $case->case_result ?? 'চলমান' }}
                            {{-- @if ($case->case_result == '1')
                              জয়!
                              @elseif($case->case_result == '0')
                              পরাজয়!
                              @else
                              চলমান
                              @endif --}}
                        </td>
                        {{-- @dd($case->case_result) --}}
                    </tr>
                    @if (!empty($case->lost_reason))
                        <tr>
                            <th scope="row">পরাজয়ের কারণ</th>
                            <td>{{ $case->lost_reason ?? '' }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th scope="row">মামলায় হেরে গিয়ে কি আপিল করা হয়েছে</th>
                        <td>
                            @if ($case->is_lost_appeal == 1)
                                হ্যা!
                            @else
                                না!
                            @endif
                        </td>
                    </tr>
                    @if (!empty($case->rm_case_ref_id))
                        <tr>
                            <th scope="row">পূর্বের মামলা নং</th>
                            <td><a href="{{ route('rmcase.show', $case->rm_case_ref_id) }}"
                                    target="_blank">{{ $case->ref_rm_case_no }}</a> </td>
                        </tr>
                    @endif
                    @if (!empty($case->status))
                        <tr>
                            <th scope="row">মামলার বর্তমান অবস্থান</th>
                            <td>{{ $case->case_status->status_name ?? '-' }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th scope="row">মন্তব্য</th>
                        <td>{{ $case->comments ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">বর্তমান ষ্ট্যাটাস</th>
                        <td>
                            @if ($case->status == 1)
                                নতুন চলমান!
                            @elseif ($case->status == 2)
                                আপিল করা হয়েছে!
                            @elseif ($case->status == 3)
                                সম্পাদিত !
                            @endif

                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-striped border">
                <thead>
                    <tr>
                        <th class="h3" scope="col" colspan="4">বাদীর বিবরণ</th>
                    </tr>
                    <tr class="">
                        <th scope="row" width="10">ক্রম</th>
                        <th scope="row" class="">নাম</th>
                        <th scope="row" class="">পিতা/স্বামীর নাম</th>
                        <th scope="row" class="">ঠিকানা</th>
                    </tr>
                </thead>
                <tbody>
                    @php $k = 1; @endphp
                    @foreach ($caseBadi as $badi)
                        <tr>
                            <td>{{ en2bn($k) }}.</td>
                            <td>{{ $badi->name }}</td>
                            <td>{{ $badi->spouse_name }}</td>
                            <td>{{ $badi->address }}</td>
                        </tr>
                        @php $k++; @endphp
                    @endforeach
                </tbody>
            </table>


            <br>
            <table class="table table-striped border">
                <thead>
                    <tr>
                        <th class="h3" scope="col" colspan="4">বিবাদীর বিবরণ</th>
                    </tr>
                    <tr class="">
                        <th scope="row" width="10">ক্রম</th>
                        <th scope="row" class="">মন্ত্রণালয়ের নাম</th>
                        <th scope="row" class="">দপ্তরের নাম</th>
                        <th scope="row" class="">ধরন</th>
                    </tr>
                </thead>
                <tbody>
                    @php $k = 1; @endphp
                    @foreach ($caseBibadi as $bibadi)

                        <tr>
                            <td class="tg-nluh">{{ en2bn($k) }}.</td>
                            <td class="tg-nluh">{{ $bibadi->ministry->office_name_bn ?? '-' }}</td>
                            <td class="tg-nluh">{{ $bibadi->department->office_name_bn ?? '-' }}</td>
                            <td class="tg-nluh">
                                {{ $bibadi->is_main_bibadi == 1 ? 'মূল বিবাদী' : 'অন্যান্য বিবাদী' }}</td>
                        </tr>
                        @php $k++; @endphp
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <fieldset class=" mb-8">
                <div class="rounded bg-success-o-100 d-flex align-items-center justify-content-between flex-wrap px-5 py-0 mb-2">
                    <div class="d-flex align-items-center mr-2 py-2">
                        <h3 class="mb-0 mr-8">সংযুক্তি সমূহ </h3>
                    </div>
                </div>
                    @forelse ($files as $key => $row)
                        <div class="form-group mb-2" id="deleteFile{{ $row->id }}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn bg-success-o-75" type="button">{{ en2bn(++$key) . ' - নম্বর :' }}</button>
                                </div>
                                {{-- <input readonly type="text" class="form-control" value="{{ asset($row->file_path . $row->file_name) }}" /> --}}
                                <input readonly type="text" class="form-control" value="{{ $row->file_type ?? '' }}" />
                                <div class="input-group-append">
                                    <a href="{{ asset($row->file_path . $row->file_name) }}" target="_blank" class="btn btn-sm btn-success font-size-h5 float-left">
                                        <i class="fa fas fa-file-pdf"></i>
                                        <b>দেখুন</b>
                                        {{-- <embed src="{{ asset('uploads/sf_report/'.$data[0]['case_register'][0]['sf_report']) }}" type="application/pdf" width="100%" height="600px" />  --}}
                                     </a>
                                    {{-- <a href="minarkhan.com" class="btn btn-success" type="button">দেখুন </a> --}}
                                </div>
                                {{-- <div class="input-group-append">
                                    <a href="javascript:void(0);" id="" onclick="deleteFile({{ $row->id }} )" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                        <b>মুছুন</b>
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                    @empty
                    <div class="pt-5">
                        <p class="text-center font-weight-normal font-size-lg">কোনো সংযুক্তি খুঁজে পাওয়া যায়নি</p>
                    </div>
                    @endforelse
            </fieldset>
        </div>
    </div>
</div>
<!--end::Tab Content-->
