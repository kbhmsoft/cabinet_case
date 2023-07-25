  

    $("#update_return_proposal_form_submit").click(function () {
        var form = $("#update_return_proposal_form");
            form.validate({
                errorElement: 'span',
                errorClass: 'final_submit_error',
                highlight: function(element, errorClass, validClass) {
                    $(element).closest('#return_proposal_error').addClass("has-error text-danger");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).closest('.form-group').removeClass("has-error");
                },
                onfocusout: function(e) {
                    this.element(e);
                },
                rules: {
                    executive_summary_file: {
                        required: true,
                    }, 
                    cv_info_file: {
                        required: true,
                    }, 
                    investigators_remark: {
                        required: true,
                    }, 
                    technical_information_pdf: {
                        required: true,
                    }, 
                    technical_information_word: {
                        required: true,
                    }, 
                    budget_info: {
                        required: true,
                    }, 
                    budget_details_xls: {
                        required: true,
                    },  
                },
                messages: {
                    executive_summary_file: {
                        required: " is must not be empty",
                    },
                    cv_info_file: {
                        required: " is must not be empty",
                    },
                    investigators_remark: {
                        required: " is must not be empty",
                    },
                    technical_information_pdf: {
                        required: " is must not be empty",
                    },
                    technical_information_word: {
                        required: " is must not be empty",
                    },
                    budget_info: {
                        required: " is must not be empty",
                    },
                    budget_details_xls: {
                        required: " is must not be empty",
                    },
                }
            });

        if (form.valid() === true){
            $("#update_return_proposal_form").click(function () {
                return false;
            })
        }
    })

 