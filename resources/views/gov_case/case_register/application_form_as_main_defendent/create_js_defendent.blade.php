<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#caseGeneralInfoForm').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            console.log(formData);

            $.ajax({
                url: "{{ route('cabinet.case.storeApplicationForm') }}",
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });

    jQuery('select[name="case_category"]').on('change', function() {
        var dataID = jQuery(this).val();
        jQuery("#case_category_type").after('<div class="loadersmall"></div>');

        if (dataID) {
            jQuery.ajax({
                url: '{{ url('/') }}/cabinet/case/dropdownlist/getdependentcasecategorytype/' +
                    dataID,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    jQuery('select[name="case_category_type"]').html(
                        '<div class="loadersmall"></div>');

                    jQuery('select[name="case_category_type"]').html(
                        '<option value="">-- নির্বাচন করুন --</option>');
                    jQuery.each(data, function(key, value) {
                        jQuery('select[name="case_category_type"]').append(
                            '<option value="' + key + '">' + value +
                            '</option>');
                    });
                    jQuery('.loadersmall').remove();
                }
            });
        } else {
            $('select[name="case_category_type"]').empty();
        }
    });
</script>
