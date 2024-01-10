@extends('layouts.cabinet.cab_default')
<script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
@section('content')
    <style>
        #select2-case_type_id-container {
            line-height: 12px !important;
        }

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 36px;
            user-select: none;
            -webkit-user-select: none;
            /* font-size: 1.2rem; */
        }
        .iframe_resizing{
            height: 900px;
            width: 100%
        }
    </style>

    <div class="card card-custom">
        <div class="card-title">
            <h3 class="card-title h2 font-weight-bolder text-center mt-2">{{ $page_title }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <form action="" id="suprime_court_case_search_form">
                        <div class="form-group row">
                            <div class="col-md-4 text-center"><label for="exampleFormControlInput1"
                                    class="form-label"><b>বিভাগ</b></label>
                                <select class="form-control form-select required1" name="division_id" id="division_id"
                                    required>
                                    <option value="">-- নির্বাচন করুন --</option>
                                    @foreach ($GovCaseDivision as $value)
                                        <option value="{{ $value->id }}"
                                            {{ old('case_category') == $value->id ? 'selected' : '' }}>
                                            {{ $value->name_bn }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 text-center"><label for="exampleFormControlInput1"
                                    class="form-label"><b>মামলার ক্যাটেগরি</b></label>
                                <select class="form-control form-select required1" name="nature_id" id="nature_id" required>
                                    <option value="">-- নির্বাচন করুন --</option>
                                </select>
                            </div>

                            <div class="col-md-4 text-center"><label for="exampleFormControlInput1"
                                    class="form-label"><b>মামলার শ্রেণী/কেস-টাইপ</b></label>
                                <select class="form-control form-select required1" name="case_type_id" id="case_type_id"
                                    required>
                                    <option value="">-- নির্বাচন করুন --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-4"><label for="exampleFormControlInput1" class="form-label"><b>মামলা
                                        নং</b></label>
                                <input type="text" class="form-control required1" name="case_number" id="case_number"
                                    placeholder="" required>
                            </div>
                            <div class="col-md-4"><label for="exampleFormControlInput1"
                                    class="form-label"><b>বছর</b></label>
                                <input type="text" class="form-control required1 common_yearpicker" id="year"
                                    name="year" placeholder="" value="{{ date('Y') }}">
                            </div>
                            <div class="col-md-4"></div>
                        </div>

                    </form>
                    <div class="col-md-3"><button class="btn btn-primary mt-4"
                            id="suprime_court_case_search_button">অনুসন্ধান</button></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="search_result_supream_court">
                    <iframe src="" title="description" id="itr" class="d-none iframe_resizing"></iframe>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('.common_yearpicker').datepicker({
            format: 'yyyy',
            startView: 'years',
            minViewMode: 'years',
            orientation: "bottom left",
        });
    </script>
    <script>
        //$('#division_id').select2();
        $('#division_id').on('change', function() {
            var division_id = $(this).find('option:selected').val();
            var options_12 = [{
                    value: 8,
                    text: 'দেওয়ানী মামলা'
                },
                {
                    value: 10,
                    text: 'অবমাননা মামলা'
                },
                {
                    value: 9,
                    text: 'জেল মামলা'
                },
            ]

            var options_21 = [{
                    value: 3,
                    text: 'সিভিল আপিল'
                },
                {
                    value: 4,
                    text: 'সিভিল রিভিশন'
                },
                {
                    value: 1,
                    text: 'মূল মামলা'
                },
                {
                    value: 2,
                    text: 'রিট মামলা'
                },
            ]


            if (division_id == 1) {
                $('#nature_id').empty();
                var option_html = '<option value="">-- নির্বাচন করুন --</option>';
                $.each(options_12, function(i, item) {
                    option_html += '<option value="' + item.value + '">' + item.text + '</option>';
                });
            } else if (division_id == 2) {
                $('#nature_id').empty();
                var option_html = '<option value="">-- নির্বাচন করুন --</option>';
                $.each(options_21, function(i, item) {
                    option_html += '<option value="' + item.value + '">' + item.text + '</option>';
                });
            } else {
                $('#nature_id').empty();
                var option_html = '<option value="">-- নির্বাচন করুন --</option>';
            }
            $('#nature_id').append(option_html);
        })



        jQuery('select[name="nature_id"]').on('change', function() {
            var dataID = jQuery(this).val();
            jQuery("#case_type_id").after('<div class="loadersmall"></div>');

            if (dataID) {
                jQuery.ajax({
                    url: '{{ url('/') }}/cabinet/case/dropdownlist/getdependentcasecategorytype/' +
                        dataID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        jQuery('select[name="case_type_id"]').html(
                            '<div class="loadersmall"></div>');

                        jQuery('select[name="case_type_id"]').html(
                            '<option value="">-- নির্বাচন করুন --</option>');
                        jQuery.each(data, function(key, value) {
                            jQuery('select[name="case_type_id"]').append(
                                '<option value="' + key + '">' + value +
                                '</option>');
                        });
                        jQuery('.loadersmall').remove();
                    }
                });
            } else {
                $('select[name="case_type_id"]').empty();
            }
        });

        $('#suprime_court_case_search_button').on('click', function(e) {
            e.preventDefault();

            let passport = true;
            $('.required1').each(function() {
                if ($(this).val() == '') {
                    passport = false;
                }
            });

            if (passport) {
                let division_id = $('#division_id').find('option:selected').val();
                let case_type_id = $('#case_type_id').find('option:selected').val();
                let case_number = $('#case_number').val();
                let year = $('#year').val();
                let url =
                    `https://supremecourt.gov.bd/web/case_history/case_history.php?div_id=${division_id}&case_type_id=${case_type_id}&case_number=${case_number}&year=${year}`;

                    $('#itr').removeClass('d-none');
                    $('#itr').attr('src',url);

            }
        });
    </script>
@endsection
