// (function($) {
//     "use strict";

    //* Form js
    function verificationForm(){
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches

        $(".nextBtn").click(function () {
            var form = $("#signUpForm");
                form.validate({
                    errorElement: 'span',
                    errorClass: 'help-block',
                    highlight: function(element, errorClass, validClass) {
                        $(element).closest('.form-group').addClass("has-error");
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).closest('.form-group').removeClass("has-error");
                    },
                    onfocusout: function(e) {
                        this.element(e);
                    },
                    rules: {
                        case_type: {
                            required: true,
                        },
                        case_no : {
                            required: true,
                        },
                        designation : {
                            required: true,
                        },
                        address : {
                            required: true,
                        },
                        mail_id : {
                            required: true,
                        },
                        mobile_no : {
                            required: true,
                        },
                        organization_name : {
                            required: true,
                        },
                        study_place : {
                            required: true,
                        },
                        study_duration : {
                            required: true,
                        },
                        total_amount_budget : {
                            required: true,
                        },
                        approval_of_the_head_file : {
                            required: true,
                            extension: "PDF",
                        },
                        area_of_research : {
                            required: true,
                        },
                        executive_summary_file : {
                            required: true,
                            extension: "pdf",
                        },
                        proposal_title : {
                            required: true,
                        },
                        area_of_research : {
                            required: true,
                        },
                        investigators_remark : {
                            required: false,
                        },
                        hr_yr1 : {
                            required: true,
                        },
                        workshop_yr1 : {
                            required: true,
                        },
                        fexpense_yr1 : {
                            required: true,
                        },
                        lcost_yr1 : {
                            required: true,
                        },
                        materials_yr1 : {
                            required: true,
                        },
                        patient_cost_yr1 : {
                            required: true,
                        },
                        travel_cost_yr1 : {
                            required: true,
                        },
                        office_stationary_yr1 : {
                            required: true,
                        },
                        data_process_yr1 : {
                            required: true,
                        },
                        pp_yr1 : {
                            required: true,
                        },
                        m_equipment_yr1 : {
                            required: true,
                        },
                        administrative_overhead_yr1 : {
                            required: true,
                        },
                        miscellaneous__yr1 : {
                            required: true,
                        },
                        total_yr1 : {
                            required: true,
                        },
                        source_of_fund : {
                            required: true,
                        },
                        year_of_fund : {
                            required: true,
                        },
                        amount_of_fund : {
                            required: true,
                        },
                        proposal_title_was : {
                            required: true,
                        },
                        why_ncp_explain : {
                            required: true,
                        },
                        data_of_completion : {
                            required: true,
                        },
                        is_report_submitted : {
                            required: true,
                        },
                        name_of_journal_inc : {
                            required: true,
                        },
                        cv_info_file : {
                            required: true,
                            extension: "pdf",
                        },
                        technical_information_pdf : {
                            required: true,
                            extension: "pdf",
                        },
                        technical_information_word : {
                            required: true,
                            extension: "docx|doc",
                        },
                        budget_details_xls : {
                            required: true,
                            extension: "xlsx|xls|xlsm",
                        },
                        declarataion_file : {
                            required: true,
                            extension: "pdf",
                        },



                    },
                    messages: {
                        case_type: {
                            required: "Case type field is required",
                        },
                        case_no : {
                            required: "Case no is required",
                        },
                        designation : {
                            required: "Designation is required",
                        },
                        address : {
                            required: "Address is required",
                        },
                        mail_id : {
                            required: "Email is required",
                        },
                        mobile_no : {
                            required: "Mobile number is required",
                        },
                        organization_name : {
                            required: "Organization name is required",
                        },
                        study_place : {
                            required: "Study place is required",
                        },
                        study_duration : {
                            required: "Study duration is required",
                        },
                        total_amount_budget : {
                            required: "Total amount of budget required",
                        },
                        approval_of_the_head_file : {
                            required: "Approval of the head file is required",
                            extension:"Please select PDF file format",
                        },
                        area_of_research : {
                            required: "Area of research minimum 1 row is required",
                        },
                        executive_summary_file : {
                            required: "Executive summary file is required",
                            extension:"Please select PDF file format",
                        },
                        proposal_title : {
                            required: "Title is required",
                        },

                        area_of_research : {
                            required: "Area of research is required",
                        },
                        investigators_remark : {
                            required: "Remark field is required",
                        },
                        hr_yr1 : {
                            required: "Year one data is required",
                        },
                        workshop_yr1 : {
                            required: "Year one data is required",
                        },
                        fexpense_yr1 : {
                            required: "Year one data is required",
                        },
                        lcost_yr1 : {
                            required: "Year one data is required",
                        },
                        materials_yr1 : {
                            required: "Year one data is required",
                        },
                        patient_cost_yr1 : {
                            required: "Year one data is required",
                        },
                        travel_cost_yr1 : {
                            required: "Year one data is required",
                        },
                        office_stationary_yr1 : {
                            required: "Year one data is required",
                        },
                        data_process_yr1 : {
                            required: "Year one data is required",
                        },
                        pp_yr1 : {
                            required: "Year one data is required",
                        },
                        m_equipment_yr1 : {
                            required: "Year one data is required",
                        },
                        administrative_overhead_yr1 : {
                            required: "Year one data is required",
                        },
                        miscellaneous__yr1 : {
                            required: "Year one data is required",
                        },
                        total_yr1 : {
                            required: "Year one data is required",
                        },
                        source_of_fund : {
                            required: "Source of funding is required",
                        },
                        year_of_fund : {
                            required: "Year of funding is required",
                        },
                        amount_of_fund : {
                            required: "Amount of funding is required",
                        },
                        proposal_title_was : {
                            required: "Proposal title is required",
                        },
                        why_ncp_explain : {
                            required: "Explain field is required",
                        },
                        data_of_completion : {
                            required: "date of completion is required",
                        },
                        is_report_submitted : {
                            required: "Report submit field is required",
                        },
                        name_of_journal_inc : {
                            required: "Name of journal is required",
                        },
                        cv_info_file : {
                            required: "CV information file is required",
                            extension:"Please select PDF file format",
                        },
                        technical_information_pdf : {
                            required: "Technical information pdf format is required",
                            extension:"Please select PDF file format",
                        },
                        technical_information_word : {
                            required: "Technical information word format is required",
                            extension:"Please select docx OR doc file format",
                        },
                        budget_details_xls : {
                            required: "Budget details xls format is required",
                            extension:"Please select correct file format",
                        },
                        declarataion_file : {
                            required: "Declarataion file is required",
                            extension:"Please select PDF file format",
                        },


                    }
                });

        if (form.valid() === true){

            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'position': 'absolute'
                    });
                    next_fs.css({
                        'left': left,
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        }
        });

        $(".previous").click(function () {
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'left': left
                    });
                    previous_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".submit").click(function () {
            return false;
        })
    };


    /*Function Calls*/
    verificationForm ();

// })(jQuery);
