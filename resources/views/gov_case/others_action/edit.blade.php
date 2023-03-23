@extends('layouts.cabinet.cab_default')

@section('content')


    <style type="text/css">
        #badiDiv td {
            padding: 5px;
            border-color: #ccc;
        }

        #badiDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }

        #bibadiDiv td {
            padding: 5px;
            border-color: #ccc;
        }
        #bibadiDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }
        #MainBibadiDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }
        #MainBibadiDiv td {
            padding: 5px;
            border-color: #ccc;
        }


        #surveyDiv td {
            padding: 5px;
            border-color: #ccc;
        }

        #surveyDiv th {
            padding: 5px;
            text-align: center;
            border-color: #ccc;
            color: black;
        }

    </style>
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
                
                
                     @if($case->court_id == 2)
                         <a href="{{ route('cabinet.case.highcourt') }}" class="btn btn-sm btn-primary font-weight-bolder float-right mt-5" style="height: 30px;">তালিকা
                         </a>
                     @else
                         <a href="{{ route('cabinet.case.appellateDivision') }}" class="btn btn-sm btn-primary font-weight-bolder float-right mt-5" style="height: 30px;">তালিকা
                         </a>
                     @endif
                     
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

                <!--begin::Form-->
                <form action="{{ route('cabinet.case.othersaction.againstgovstore') }}" class="form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <fieldset class="mb-8">
                            <legend> পদক্ষেপের বিবরণ</legend>
                            <div class="form-group row">
                                <div class="col-lg-4 mb-5">
                                    <label>রায়ের নকল প্রাপ্তির জন্য আবেদনের তারিখ<span class="text-danger">*</span></label>
                                    <input type="text" name="result_copy_asking_date" class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর" autocomplete="off" value="{{ $case->result_copy_asking_date ?? '' }}" required>
                                </div>
                                <div class="col-lg-3 mb-5">
                                    <label>রায়ের নকল প্রাপ্তির তারিখ<span class="text-danger">*</span></label>
                                    <input type="text" name="result_copy_reciving_date" class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর" autocomplete="off" value="{{ $case->result_copy_reciving_date ?? '' }}" required>
                                </div>
                                <div class="col-lg-5 mb-5" >
                                    <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের স্মারক <span class="text-danger">*</span></label>
                                    <input type="text" name="appeal_requesting_memorial" id="appeal_requesting_memorial" class="form-control form-control-sm"autocomplete="off" value="{{ $case->appeal_requesting_memorial ?? '' }}">
                                </div>
                                
                                <div class="col-lg-5 mb-5" >
                                    <label>প্রযোজ্য ক্ষেত্রে আপিল দায়েরের জন্য অনুরোধের তারিখ <span class="text-danger">*</span></label>
                                    <input type="text" name="appeal_requesting_date" id="appeal_requesting_date" class="form-control form-control-sm  common_datepicker"autocomplete="off" value="{{ $case->appeal_requesting_date ?? '' }}" >
                                </div>
                                <div class="col-lg-7 mb-5" >
                                    <label>আপিল/রিভিউ দায়ের না করার সিদ্বান্ত হলে তার কারণ <span class="text-danger">*</span></label>
                                    <textarea name="reason_of_not_appealing" class="form-control" id="reason_of_not_appealing" rows="3" spellcheck="false">{{ $case->reason_of_not_appealing ?? '' }}
                                    </textarea>
                                    
                                </div>
                                <input type="hidden" name="caseId" value="{{ $case->id}}">
                            </div>
                        </fieldset>
                    </div>
                    <!--end::Card-body-->

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-center" >
                                <button type="submit" class="btn btn-success mr-2 text-center"
                                    onclick="return confirm('আপনি কি সংরক্ষণ করতে চান?')">সংরক্ষণ করুন</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>

    </div>
    <!--end::Row-->

@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
    <style>
        /*.select2-container .select2-selection--single {
            height: 37px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 25px !important;
        }*/
    </style>
    <!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page --}}
@section('scripts')
   <script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
    <script>
        // common datepicker
        $('.common_datepicker').datepicker({
            orientation: "bottom left",
            format: "dd/mm/yyyy",
            todayHighlight: true,
            viewMode: 'years',
        });
        $('.common_yearpicker').datepicker({
            format: 'yyyy',
            startView: 'years',
            minViewMode: 'years',
            orientation: "bottom left",
            // format: "dd/mm/yyyy",
            // todayHighlight: true,
            // viewMode: 'years',
        });
    </script>
@endsection


                                
