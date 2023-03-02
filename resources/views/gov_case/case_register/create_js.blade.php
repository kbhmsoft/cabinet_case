{{-- @php
    $department = '';
@endphp --}}
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

    <script type="text/javascript">
        $('form').submit(function () { $('[disabled]').removeAttr('disabled'); })
        $(document).ready(function() {
            // addBadiRowFunc();
            addMainBibadiRowFunc();
            addFileRowFunc();
            /*if("{{ userInfo()->role_id }}" != 28 && "{{ request('red') }}" == ''){
                $(function(){
                    id = "{{ userInfo()->office->parent != null ? userInfo()->office->Parent->id : userInfo()->office_id }}"
                    // console.log("{{ userInfo()->id}}");
                    // return
                    $('#MainBibadiDiv #ministry_id').val(id).attr("selected","selected");
                    $('#MainBibadiDiv #ministry_id').val(id);
                    $('#MainBibadiDiv #ministry_id').prop('disabled', true);
                    if("" == "{{ userInfo()->id }}"){
                        $('#MainBibadiDiv #doptor_id ').after('<div id="doptora" style="top: -19px;"></div>');
                        $('#MainBibadiDiv #doptora ').addClass('spinner spinner-primary spinner-left');
                        getDoptor(this, 'bibadi_3', id);
                        getDoptor(this, 'bibadi_100', id);
                        $( document ).ajaxComplete(function() {
                            $('#MainBibadiDiv #doptor_id').val('{{ $department ?? '' }}').attr("selected","selected");
                            $('#MainBibadiDiv #doptora').removeClass('spinner spinner-primary spinner-left');
                        });
                    }
                });
            }*/
        });

        /*********************** Add multiple badi *************************/
        $("#addBadiRow").click(function(e) {
            addBadiRowFunc();
        });

        //add row function
        function addBadiRowFunc() {
            var items = '';
            items += '<tr>';
            items += '<td><input type="text" name="badi_name[]" class="form-control form-control-sm" placeholder=""></td>';
            items += '<input type="hidden" name="badi_id[]" value="">';
            items += '<td><input type="text" name="badi_spouse_name[]" class="form-control form-control-sm" placeholder=""></td>';
            items += '<td><input type="text" name="badi_address[]" class="form-control form-control-sm" placeholder=""></td>';
            items += '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            items += '</tr>';
            $('#badiDiv tr:last').after(items);
        }
        function removeRowBadiBibadiFunc(id, url) {
            var dataId = $(id).attr("data-id");
            var params = $.extend({}, doAjax_params_default);
                params['url'] = "{{ url('cabinet/case/')}}/" +url+ "/" +dataId;
                params['requestType'] = "POST";
                // params['data'] = {};
                params['successCallbackFunction'] = success;
                params['errorCallBackFunction'] = error;
                if (confirm("Are you sure you want to delete this information from database?") == true) {
                    doAjax(params);
                }
            function success(data){
                $(id).closest("tr").remove();
                toastr.success(data.success, "Success");
            }
            function error(data){
                console.log(data);
            }

        }

        //remove row
        function removeBadiRow(id) {
            $(id).closest("tr").remove();
        }
        /************************ Add multiple bibadi *************************/
        function getDoptor(ministry=null, rowId, mainid=null) {
                var id = ministry.value;
                if(id==null){
                    id = mainid;
                }
                var params = $.extend({}, doAjax_params_default);
                    params['url'] = "{{ url('/') }}/case/dropdownlist/getdependentDoptor/"+id;
                    params['requestType'] = "GET";
                    params['data'] = {};
                    params['successCallbackFunction'] = success;
                    params['errorCallBackFunction'] = error;
                    doAjax(params);
                function success(data){
                    var row = '#' + rowId;
                    // console.log(data);
                    $(row + ' select[name="doptor[]"]').html('<div class="loadersmall"></div>');
                    $(row + ' select[name="doptor[]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                    $.each(data, function(key, value) {
                        $(row + ' select[name="doptor[]"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
                function error(data){
                    console.log(data);
                }

        }
        function getMainDoptor(main_ministry=null, rowId, mainid=null) {
                var id = main_ministry.value;
                if(id==null){
                    id = mainid;
                }
                var params = $.extend({}, doAjax_params_default);
                    params['url'] = "{{ url('/') }}/case/dropdownlist/getdependentDoptor/"+id;
                    params['requestType'] = "GET";
                    params['data'] = {};
                    params['successCallbackFunction'] = success;
                    params['errorCallBackFunction'] = error;
                    doAjax(params);
                function success(data){
                    var row = '#' + rowId;
                    // console.log(data);
                    $(row + ' select[name="main_doptor[]"]').html('<div class="loadersmall"></div>');
                    $(row + ' select[name="main_doptor[]"]').html('<option value="">-- নির্বাচন করুন --</option>');
                    $.each(data, function(key, value) {
                        $(row + ' select[name="main_doptor[]"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
                function error(data){
                    console.log(data);
                }

        }
        /************************ Add multiple Main bibadi *************************/

        $("#addMainBibadiRow").click(function(e) {
            addMainBibadiRowFunc();
        });

        //add row function
        function addMainBibadiRowFunc() {
            var countVal = parseInt($('#mainBibadi_count').val());
            $('#mainBibadi_count').val(countVal+1);
            var mk_main = $('#MainBibadiDiv tr').length;
            var MainCount = $('#MainBibadiDiv tr').length;
            console.log(MainCount);
            $('#MainBibadiDiv tr:last').after(ItemMain(mk_main+1, 'other'));
            /*if(MainCount ==3){
                $('#MainBibadiDiv tr:last').after(ItemMain(MainCount, 'main'));
            }*/

            function ItemMain(count, type=NULL){
                var items = '';
                items += '<tr id="bibadi_'+(count)+'">';
                items +=
                    '<td><select onchange="getMainDoptor(this, \'bibadi_'+(count)+'\')" name="main_ministry[]" id="ministry_id" class="form-control form-control-sm" ><option value="">-- নির্বাচন করুন --</option>@foreach ($ministrys as $value)<option value="{{ $value->id }}" {{ old('main_ministry') == $value->id ? 'selected' : '' }}> {{ $value->office_name_bn }} </option>@endforeach</select></td>';
                items += '<input type="hidden" name="bibadi_id[]" value="">';
                items +='<td><select name="main_doptor[]" id="doptor_id" class="form-control form-control-sm"><option value="">-- নির্বাচন করুন --</option></select></td>';
                // console.log(count);
                if(countVal != 1){
                    items += '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeMainBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
                }
                items += '</tr>';
                // console.log(items);
                return items;
            }
        }

        //remove row function
        function removeMainBibadiRow(id) {
            $(id).closest("tr").remove();
        }
        /************************ //Add multiple Main bibadi *************************/

        $("#addBibadiRow").click(function(e) {
            addBibadiRowFunc();
        });

        //add row function
        function addBibadiRowFunc() {
            var mk = $('#bibadiDiv tr').length;
            var MainCount = $('#MainBibadiDiv tr').length;
            // console.log(MainCount);
            $('#bibadiDiv tr:last').after(Item(mk+1, 'other'));
            /*if(MainCount ==3){
                $('#MainBibadiDiv tr:last').after(Item(MainCount, 'main'));
            }*/

            function Item(count, type=NULL){
                var items = '';
                items += '<tr id="bibadi_'+(count)+'">';
                items +=
                    '<td><select onchange="getDoptor(this, \'bibadi_'+(count)+'\')" name="ministry[]" id="ministry_id" class="form-control form-control-sm" ><option value="">-- নির্বাচন করুন --</option>@foreach ($ministrys as $value)<option value="{{ $value->id }}" {{ old('ministry') == $value->id ? 'selected' : '' }}> {{ $value->office_name_bn }} </option>@endforeach</select></td>';
                items += '<input type="hidden" name="bibadi_id[]" value="">';
                items +='<td><select name="doptor[]" id="doptor_id" class="form-control form-control-sm"><option value="">-- নির্বাচন করুন --</option></select></td>';
                // console.log(count);
                if(type == 'other'){
                    items += '<td><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
                }
                items += '</tr>';
                // console.log(items);
                return items;
            }
        }

        //remove row function
        function removeBibadiRow(id) {
            $(id).closest("tr").remove();
        }

        /************************ Add multiple survey *************************/
        $("#addSurveyRow").click(function(e) {
            addSurveyRowFunc();
        });
    </script>

    <script>
        var numbers = {
            0: '০',
            1: '১',
            2: '২',
            3: '৩',
            4: '৪',
            5: '৫',
            6: '৬',
            7: '৭',
            8: '৮',
            9: '৯'
        };

        function replaceNumbers(input) {
            var output = [];
            for (var i = 0; i < input.length; ++i) {
                if (numbers.hasOwnProperty(input[i])) {
                    output.push(numbers[input[i]]);
                } else {
                    output.push(input[i]);
                }
            }
            return output.join('');
        }

        // document.getElementById('r').textContent = replaceNumbers('count'); // comment on 07/11/2022 shahajahan
    </script>

    <script>
        function myFunction() {
            confirm("আপনি কি সংরক্ষণ করতে চান?");
        }

        $('document').ready(function() {
            $('#preview').on('click', function() {
                var court = $('#court option:selected').text();
                var division = $('#division_id option:selected').text();
                var district_n = $('#district_id option:selected').text();
                var upazila = $('#upazila_id option:selected').text();
                var mouja_id = $('#mouja_id option:selected').text();
                var case_type = $('#case_type option:selected').text();
                var case_no = $('#case_no').val();
                var case_date = $('#case_date').val();
                var tafsil = $('#tafsil').val();
                var chowhaddi = $('#chowhaddi').val();
                var comments = $('#comments').val();
                var count = 0;
                var badi_name = $("form input[name='badi_name[]']").map(function() {
                    count++;
                    return (count + '. ' + $(this).val() + '<br>')
                }).get();
                var badi_spouse_name = $("form input[name='badi_spouse_name[]']").map(function() {
                    return ($(this).val() + '<br>')
                }).get();
                var badi_address = $("form input[name='badi_address[]']").map(function() {
                    return ($(this).val() + '<br>')
                }).get();
                var count = 0;
                var bibadi_name = $("form input[name='bibadi_name[]']").map(function() {
                    count++;
                    return (count + '. ' + $(this).val() + '<br>')
                }).get();
                var bibadi_spouse_name = $("form input[name='bibadi_spouse_name[]']").map(function() {
                    return ($(this).val() + '<br>')
                }).get();
                var bibadi_address = $("form input[name='bibadi_address[]']").map(function() {
                    return ($(this).val() + '<br>')
                }).get();
                var count = 0;
                var st_id = $("form select[name='st_id[]']").map(function() {
                    return ($(this).find("option:selected").text()) + '   '
                }).get();
                var count = 0;
                var khotian_no = $("form input[name='khotian_no[]']").map(function() {
                    count++;
                    return (count + '. ' + $(this).val() + '<br>')
                }).get();
                var count = 0;
                var daag_no = $("form input[name='daag_no[]']").map(function() {
                    count++;
                    return (count + '. ' + $(this).val() + '<br>')
                }).get();
                var count = 0;
                var lt_id = $("form select[name='lt_id[]']").map(function() {
                    return ($(this).find("option:selected").text()) + '   '
                }).get();
                var count = 0;
                var land_size = $("form input[name='land_size[]']").map(function() {
                    count++;
                    return (count + '. ' + $(this).val() + '<br>')
                }).get();
                var count = 0;
                var land_demand = $("form input[name='land_demand[]']").map(function() {
                    count++;
                    return (count + '. ' + $(this).val() + '<br>')
                }).get();
                var count = 0;

                /* var role_id = $('#role_id option:selected').text();
                var office_id = $('#office_id option:selected').text();*/
                $('#previewCourt').html(court);
                $('#previewDivision').html(division);
                $('#previewDistrict').html(district_n);
                $('#previewUpazila').html(upazila);
                $('#previewMouja_id').html(mouja_id);
                $('#previewCase_type').html(case_type);
                $('#previewCase_no').html(case_no);
                $('#previewCase_date').html(case_date);
                $('#previewTafsil').html(tafsil);
                $('#previewChowhaddi').html(chowhaddi);
                $('#previewComments').html(comments);
                $('#previewBadi_name').html(badi_name);
                $('#previewBadi_spouse_name').html(badi_spouse_name);
                $('#previewBadi_address').html(badi_address);
                $('#previewBibadi_name').html(bibadi_name);
                $('#previewBibadi_spouse_name').html(bibadi_spouse_name);
                $('#previewBibadi_address').html(bibadi_address);
                $('#previewSt_id').html(st_id);
                $('#previewKhotian_no').html(khotian_no);
                $('#previewDaag_no').html(daag_no);
                $('#previewLt_id').html(lt_id);
                $('#previewLand_size').html(land_size);
                $('#previewLand_demand').html(land_demand);

            });
        });
    </script>
    <!--end::Page Scripts-->
    @include('components.Ajax')
    <script>
        function caseCategoryGet(caseDivitionId, category_id = null, categoryDivId=null){
            var catId = '#' + category_id;
            var divId = '#' + categoryDivId;
            $(divId).addClass('spinner spinner-primary spinner-left');
            $(catId).empty();
            var id = caseDivitionId.value;
            if(!id){
                $(catId).html('<option value="">-- মামলার বিভাগ নির্বাচন করুন --</option>');
                $(divId).removeClass('spinner spinner-primary spinner-left');
                return;
            }
            var params = $.extend({}, doAjax_params_default);
                params['url'] = "{{ url('cabinet/case/getCaseCategory') }}/"+id;
                params['requestType'] = "POST";
                params['data'] = {};
                params['successCallbackFunction'] = success;
                params['errorCallBackFunction'] = error;
                doAjax(params);
            function success(data){
                $(catId).html('<option value="">-- নির্বাচন করুন --</option>');
                $.each(data, function(key, value) {
                    $(catId).append('<option value="' + key + '">' + value + '</option>');
                });
                $(divId).removeClass('spinner spinner-primary spinner-left');
            }
            function error(data){
                console.log(data);
            }
        }

        // ============= Add Attachment Row ========= start =========
        $("#addFileRow").click(function(e) {
            addFileRowFunc();
        });
        //add row function
        function addFileRowFunc() {
            var count = parseInt($('#other_attachment_count').val());
            $('#other_attachment_count').val(count + 1);
            var items = '';
            items += '<tr>';
            items += '<td><input type="text" name="file_type[]" class="form-control form-control-sm" placeholder=""></td>';
            items += '<td><div class="custom-file"><input type="file" name="file_name[]" onChange="attachmentTitle(' +
                count + ')" class="custom-file-input" id="customFile' + count +
                '" /><label class="custom-file-label custom-input' + count + '" for="customFile' + count +
                '">ফাইল নির্বাচন করুন</label></div></td>';
            items += '<td width="40"><a href="javascript:void();" class="btn btn-sm btn-danger font-weight-bolder pr-2" onclick="removeBibadiRow(this)"> <i class="fas fa-minus-circle"></i></a></td>';
            items += '</tr>';
            $('#fileDiv tr:last').after(items);
        }
        //Attachment Title Change
        function attachmentTitle(id) {
            var value = $('#customFile' + id).val();
            $('.custom-input' + id).text(value);
        }
        //remove Attachment
        function removeBibadiRow(id) {
            $(id).closest("tr").remove();
        }
    </script>
