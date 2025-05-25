@php
    $roleID = Auth::user()->role_id;
    $officeInfo = user_office_info();
    $caseNo = request('case_no') ?? '';
    $caseCategoryType = request('case_category_type') ?? '';
@endphp

<form class="form-inline" id="searchForm" method="GET">
    <div class="row w-100">

        <div class="col-md-3 mb-2">
            <select name="office_type" id="office_type" class="form-control w-100">
                <option value="">-বিভাগ নির্বাচন করুন-</option>
                @foreach ($office_types as $value)
                    <option value="{{ $value->id }}" {{ request('office_type') == $value->id ? 'selected' : '' }}>
                        {{ $value->type_name_bn }}
                    </option>
                @endforeach
            </select>
        </div>

        @if ($roleID != 29 && $roleID != 31)
            <div class="col-md-3 mb-2" id="selectMinDiv" style="display: none;">
                <select name="ministry" id="ministry" class="form-control w-100">
                    <option value="">-মন্ত্রণালয়/বিভাগ নির্বাচন করুন-</option>
                    @foreach ($ministries as $value)
                        <option value="{{ $value->doptor_office_id }}"
                            {{ request('ministry') == $value->doptor_office_id ? 'selected' : '' }}>
                            {{ $value->office_name_bn }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2" id="selectDivisionDiv" style="display: none;">
                <select name="divOffice" id="divOffice" class="form-control w-100">
                    <option value="">- বিভাগীয় প্রশাসন নির্বাচন করুন-</option>
                    @foreach ($divOffices as $value)
                        <option value="{{ $value->doptor_office_id }}"
                            {{ request('divOffice') == $value->doptor_office_id ? 'selected' : '' }}>
                            {{ $value->office_name_bn }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-3 mb-2">
            <select name="office_id" id="office_id" class="form-control w-100">
                <option value="">- অফিস নির্বাচন করুন-</option>
            </select>
        </div>

        <div class="col-md-3 mb-2">
            <select name="case_category_type" class="form-control w-100">
                <option value="">মামলার শ্রেণী/কেস-টাইপ</option>
                @php
                    $selectedValue = request('case_category_type');
                @endphp
                @foreach ($gov_case_division_category_type as $value)
                    <option value="{{ $value->id }}" {{ $selectedValue == $value->id ? 'selected' : '' }}>
                        {{ $value->name_bn }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-2">
            <div class="input-group">
                <input type="text" class="form-control" name="case_no" placeholder="মামলা নং"
                    value="{{ $caseNo }}">
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <select name="concern_person_designation" id="concern_person_designation" class="form-control w-100">
                <option value="">সংশ্লিষ্ট আইন কর্মকর্তা নির্বাচন করুন</option>
                @foreach ($concernPersonDesignation as $value)
                    <option value="{{ $value->id }}"
                        {{ request('concern_person_designation') == $value->id ? 'selected' : '' }}>
                        {{ $value->name_bn }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-2" id="person_name_container">
            <select name="concern_person_name" id="concern_person_name" class="form-control w-100">
                <option value="">সংশ্লিষ্ট আইন কর্মকর্তার নাম নির্বাচন করুন</option>
                @if (request('concern_person_name'))
                    @php
                        $selectedUser = \App\Models\User::find(request('concern_person_name'));
                    @endphp
                    @if ($selectedUser)
                        <option value="{{ $selectedUser->id }}" selected>{{ $selectedUser->name }}</option>
                    @endif
                @endif
            </select>
        </div>

        <div class="col-md-3 mb-2">
            <button type="submit" class="btn btn-success w-100">অনুসন্ধান করুন</button>
        </div>
    </div>
</form>

@section('scripts')
    {{-- Datepicker --}}
    <script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
    <script>
        $('.common_datepicker').datepicker({
            format: "dd/mm/yyyy",
            todayHighlight: true,
            orientation: "bottom left"
        });
    </script>

    {{-- Select2 Initialization --}}
    <script>
        $('#ministry, #divOffice, #office_id').select2();
    </script>

    {{-- Office Type Change Handling --}}
    <script>
        function handleOfficeTypeChange(officeType) {
            if (officeType == 2) {
                $('#selectMinDiv').show();
                $('#selectDivisionDiv').hide();
                $('#divOffice').val(null).trigger('change');
            } else if (officeType == 4) {
                $('#selectDivisionDiv').show();
                $('#selectMinDiv').hide();
                $('#ministry').val(null).trigger('change');
            } else {
                $('#selectDivisionDiv, #selectMinDiv').hide();
                $('#ministry, #divOffice').val(null).trigger('change');
            }
        }

        $('select[name="office_type"]').on('change', function() {
            const officeType = $(this).val();
            handleOfficeTypeChange(officeType);
            loadDependentOffices(officeType);
        });

        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const officeType = urlParams.get('office_type') || $('#office_type').val();
            handleOfficeTypeChange(officeType);
        });
    </script>

    {{-- Dependent Dropdown Functions --}}
    <script>
        function populateOfficeDropdown(url) {
            $("#office_id").after('<div class="loadersmall"></div>');
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    const $officeSelect = $('select[name="office_id"]');
                    const selectedOfficeID = new URLSearchParams(window.location.search).get("office_id");

                    $officeSelect.html('<option value="">-- অফিস নির্বাচন করুন --</option>');
                    $.each(data, function(key, value) {
                        const selected = selectedOfficeID == key ? 'selected' : '';
                        $officeSelect.append(`<option value="${key}" ${selected}>${value}</option>`);
                    });

                    $('.loadersmall').remove();
                }
            });
        }

        function loadDependentOffices(dataID) {
            if (dataID) {
                populateOfficeDropdown(`/cabinet/office/dropdownlist/getdependentoffice/${dataID}`);
            } else {
                $('select[name="office_id"]').html('<option value="">-- অফিস নির্বাচন করুন --</option>');
            }
        }

        $('#ministry').on('change', function() {
            const id = $(this).val();
            if (id) {
                populateOfficeDropdown(`/cabinet/office/dropdownlist/getdependentchildoffice/${id}`);
            }
        });

        $('#divOffice').on('change', function() {
            const id = $(this).val();
            if (id) {
                populateOfficeDropdown(`/cabinet/office/dropdownlist/getdependentchildoffice/${id}`);
            }
        });

        $(document).ready(function() {
            if (typeof officeID !== 'undefined' && $('#office_type').val()) {
                loadDependentOffices($('#office_type').val());
            }

            if (typeof minId !== 'undefined' && minId != 0) {
                populateOfficeDropdown(`/cabinet/office/dropdownlist/getdependentchildoffice/${minId}`);
            }

            if (typeof dicOfficeID !== 'undefined' && dicOfficeID != 0) {
                populateOfficeDropdown(`/cabinet/office/dropdownlist/getdependentchildoffice/${dicOfficeID}`);
            }
        });
    </script>

    {{-- Concern Person Dropdown Handling --}}
    <script>
        // Add Select2 initialization for the concern person dropdowns
        $('#concern_person_designation, #concern_person_name').select2({
            placeholder: function() {
                return $(this).find('option:first').text();
            },
            // allowClear: true
        });

        // Update the concern person name dropdown to use Select2 properly in the AJAX success callback
        $(document).ready(function() {
            $('#concern_person_designation').change(function() {
                var designationId = $(this).val();
                var $personDropdown = $('#concern_person_name');

                // Clear and prepare the dropdown with Select2
                $personDropdown.empty().append(
                    '<option value="">সংশ্লিষ্ট আইন কর্মকর্তার নাম নির্বাচন করুন-</option>'
                ).select2({
                    placeholder: "সংশ্লিষ্ট আইন কর্মকর্তার নাম নির্বাচন করুন",
                    // allowClear: true
                });

                if (designationId) {
                    console.log('Fetching users for designation:', designationId);

                    $.ajax({
                        url: '{{ route('cabinet.case.getAppealUsersByDesignation') }}',
                        type: 'GET',
                        data: {
                            designation_id: designationId
                        },
                        success: function(data) {
                            console.log('Received users:', data);

                            if (data && data.length > 0) {
                                $.each(data, function(key, user) {
                                    $personDropdown.append(
                                        $('<option></option>')
                                        .attr('value', user.id)
                                        .text(user.name)
                                    );
                                });
                            } else {
                                console.warn('No users found for designation:', designationId);
                            }

                            // Restore previously selected value if form was submitted
                            var previousValue = '{{ request('concern_person_name') }}';
                            if (previousValue) {
                                $personDropdown.val(previousValue).trigger('change');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching users:', error);
                            alert('Failed to load users. Please try again.');
                        }
                    });
                }
            });

            // Trigger change if designation is pre-selected (form submitted)
            @if (request('concern_person_designation'))
                $('#concern_person_designation').trigger('change');
            @endif
        });
    </script>
@endsection
