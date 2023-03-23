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
                <form action="{{ route('cabinet.case.othersaction.senttoagfromsolstore') }}" class="form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <fieldset class="mb-8">
                            <legend> পদক্ষেপের বিবরণ</legend>
                                <div class="form-group row">
                                    <div class="col-lg-6 mb-5">
                                        <label>সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের তারিখ </label>
                                        <input type="text" name="result_sending_date_solisitor_to_ag" id="result_sending_date_solisitor_to_ag"
                                            class="form-control form-control-sm  common_datepicker" placeholder="দিন/মাস/বছর"
                                            autocomplete="off" value="{{ $case->result_sending_date_solisitor_to_ag ?? '' }}">
                                    </div>
                                    <div class="col-lg-6 mb-5">
                                        <label>সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের স্মারক </label>
                                        <input type="text" name="result_sending_memorial_solisitor_to_ag" id="result_sending_memorial_solisitor_to_ag"
                                            class="form-control form-control-sm" placeholder=""
                                            autocomplete="off" value="{{ $case->result_sending_memorial_solisitor_to_ag ?? '' }}">
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


                                
