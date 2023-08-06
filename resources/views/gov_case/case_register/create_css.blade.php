<style type="text/css">
    /*custom font*/
    /*body {
    font-family: "Open Sans", sans-serif;
}*/
    #signUpForm {
        /*max-width: 500px;*/
        background-color: #ffffff;
        /*margin: 40px auto;*/
        padding: 40px;
        box-shadow: 0px 6px 18px rgb(0 0 0 / 9%);
        border-radius: 12px;
    }

    #signUpForm .form-header {
        gap: 5px;
        text-align: center;
        font-size: 0.9em;
    }

    #signUpForm .form-header .stepIndicator {
        position: relative;
        flex: 1;
        padding-bottom: 30px;
        font-size: 18px;
    }

    #signUpForm .form-header .stepIndicator.active {
        font-weight: 600;
    }

    #signUpForm .form-header .stepIndicator.finish {
        font-weight: 600;
        color: #009688;
    }

    #signUpForm .form-header .stepIndicator::before {
        content: "";
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        z-index: 9;
        width: 30px;
        height: 30px;
        background-color: #d5efed;
        border-radius: 50%;
        border: 3px solid #ecf5f4;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #signUpForm .form-header .one::before {
        content: "1";
    }

    #signUpForm .form-header .two::before {
        content: "2";
    }

    #signUpForm .form-header .three::before {
        content: "3";
    }

    #signUpForm .form-header .stepIndicator.active::before {
        background-color: #a7ede8;
        border: 3px solid #d5f9f6;
    }

    #signUpForm .form-header .stepIndicator.finish::before {
        background-color: #009688;
        border: 3px solid #b7e1dd;
    }

    #signUpForm .form-header .stepIndicator::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: 14px;
        width: 100%;
        height: 3px;
        background-color: #f3f3f3;
    }

    #signUpForm .form-header .stepIndicator.active::after {
        background-color: #a7ede8;
    }

    #signUpForm .form-header .stepIndicator.finish::after {
        background-color: #009688;
    }

    #signUpForm .form-header .stepIndicator:last-child:after {
        display: none;
    }

    /*#signUpForm input {
    padding: 15px 20px;
    width: 100%;
    font-size: 1em;
    border: 1px solid #e3e3e3;
    border-radius: 5px;
}*/
    #signUpForm input:focus {
        border: 2px solid #009688;
        outline: 0;
    }

    #signUpForm input.invalid {
        border: 2px solid #ffaba5;
    }

    #signUpForm .invalid {
        border: 2px solid #ffaba5;
    }

    #signUpForm .step {
        display: none;
    }

    #signUpForm .form-footer {
        overflow: auto;
        gap: 20px;
    }

    #signUpForm .form-footer button {
        background-color: #009688;
        border: 1px solid #009688 !important;
        color: #ffffff;
        border: none;
        padding: 13px 30px;
        font-size: 1em;
        cursor: pointer;
        border-radius: 5px;
        flex: 1;
        margin-top: 5px;
    }

    #signUpForm .form-footer button:hover {
        opacity: 0.8;
    }

    #signUpForm .form-footer #prevBtn {
        background-color: #fff;
        color: #009688;
    }


    .submit-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background-color: #009688;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .disable-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        color: #0c0c0c;
        background-color: #595b5b;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .submit-button:hover {
        background-color: #0056b3;
    }


    .submit-button:active {
        background-color: #003c80;
    }

    .submit-button:disabled {
        background-color: #c0c0c0;
        cursor: not-allowed;
    }

    .tabs {
        max-width: 600px;
        margin: 0 auto;
    }

    .tab-navigation {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: center;
        background-color: #f0f0f0;
        border-radius: 10px 10px 0 0;
    }

    .tab-navigation li {
        padding: 15px 20px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .tab-navigation li a {
        color: #333;
        text-decoration: none;
        font-weight: bold;
    }

    .tab-navigation li:hover {
        background-color: #ddd;
    }

    .tab-content .tab-pane {
        display: none;
        padding: 20px;
        background-color: #f0f0f0;
        border-radius: 0 0 10px 10px;
    }

    .tab-content .tab-pane.active {
        display: block;
    }

    .swal2-icon-show {
        margin: 0 auto !important;
    }

    ul.nav.details_trainee_tab.nav-tabs.myTab {
        cursor: pointer;
        text-align: center;
        transition: background-color 0.2s ease;
        font-size: 16px;
        font-weight: 600;
        background-color: #f0f0f0;
    }

    /* for form validation error in modal  */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background-color: aliceblue;
        margin: 6% auto;
        padding: 10px;
        border: 4px solid mediumpurple;
        border-radius: 5px;
        max-width: 500px;
    }

    .close-button {
        background-color: #f44336;
        color: #fff;
        /* padding: 8px 16px; */
        border: none;
        border-radius: 3px;
        font-size: 15px;
        cursor: pointer;
        width: 90px;
    }

    .close-button:hover {
        background-color: #d32f2f;
    }

    #emptyFieldsList {
        list-style-type: none;
        padding: 0;
    }

    #emptyFieldsList li {
        margin: 25px 25px 5px 10px;
        color: red;
        font-size: 15px;
    }

    /* .modal-content li {
        color: red;
        font-size: 18px;
    } */
</style>
