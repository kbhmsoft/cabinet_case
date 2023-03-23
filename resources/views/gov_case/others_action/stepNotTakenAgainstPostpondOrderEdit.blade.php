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
                <form action="{{ route('cabinet.case.othersaction.stepnottakenAgainstpostpondorderstore') }}" class="form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <fieldset class="mb-8">
                            <legend> পদক্ষেপের বিবরণ</legend>
                                <div class="form-group row">
                                    <div class="col-lg-4 mb-5">
                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিল </label>
                                        <input type="text" name="appeal_against_postpond_interim_order" id="appeal_against_postpond_interim_order" class="form-control form-control-sm" placeholder=""
                                            autocomplete="off" value="{{ $case->appeal_against_postpond_interim_order ?? '' }}">
                                    </div>
                                    <div class="col-lg-4 mb-5" >
                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিলের তারিখ <span class="text-danger"></span></label>
                                        <input type="text" name="appeal_against_postpond_interim_order_date" id="appeal_against_postpond_interim_order_date" class="form-control form-control-sm  common_datepicker"autocomplete="off" value="{{ $case->appeal_against_postpond_interim_order_date ?? '' }}">
                                    </div>
                                    <div class="col-lg-4 mb-5" >
                                        <label>স্থগিতাদেশের/অন্তর্বর্তীকালীন আদেশের বিরুদ্ধে<br> আপিলের বিবরণ <span class="text-danger"></span></label>
                                        <textarea type="text" name="appeal_against_postpond_interim_order_details" id="appeal_against_postpond_interim_order_details" class="form-control form-control-sm"autocomplete="off" rows="3">{{ $case->appeal_against_postpond_interim_order_details ?? '' }}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="caseId" value="{{ $case->id}}" required="required">
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


                                
