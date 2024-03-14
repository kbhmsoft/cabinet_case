<style type="text/css">
    .count-numbers {
        font-size: 1.5em !important;
    }

    .count-name {
        font-size: 1.1em !important;
    }

    .fa {
        font-size: 3.5em !important;
    }

    @media screen and (width: 768px) {
        .count-numbers {
            font-size: 1.5em !important;
        }

        .count-name {
            font-size: 1em !important;
        }

        .fa {
            font-size: 3.5em !important;
        }
    }
</style>

<div class="row mb-5">
    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.highcourt') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($total_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                মোট মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.highcourt') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.highcourt') }}"><?= en2bn($total_high_court_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.highcourt', 2) }}">হাইকোর্ট বিভাগে মোট মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.highcourt.running') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.highcourt.running') }}"><?= en2bn($running_high_court_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.highcourt.running') }}">হাইকোর্ট বিভাগে চলমান মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.highcourt.complete') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.highcourt.complete') }}"><?= en2bn($final_high_court_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.highcourt.complete') }}">হাইকোর্ট বিভাগে নিস্পত্তিকৃত মামলা</a></span>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.appellateDivision') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appellateDivision') }}"><?= en2bn($total_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appellateDivision') }}">আপিল বিভাগে মোট মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.appellateDivision.running') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appellateDivision.running') }}"><?= en2bn($running_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appellateDivision.running') }}">আপিল বিভাগে চলমান মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.appellateDivision.complete') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appellateDivision.complete') }}"><?= en2bn($final_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appellateDivision.complete') }}">আপিল বিভাগে নিস্পত্তিকৃত
                    মামলা</a></span>
        </div>
    </div>


    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.againstHighCourtCaseAppealPending') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.againstHighCourtCaseAppealPending') }}"><?= en2bn($against_high_court_case_appeal_pending) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.againstHighCourtCaseAppealPending') }}">সরকারের বিপক্ষে আপিলের <br>জন্য
                    পেন্ডিং</a></span>
        </div>
    </div>
    {{--
    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.against') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($against_high_court_case_appeal_pending) ?></a></span>
            <span class="count-name">সরকারের বিপক্ষে আপিলের <br>জন্য
                পেন্ডিং</a></span>
        </div>
    </div> --}}
</div>

<div class="row mb-5">

    {{-- <div class="col-md-3">
        <div class="card-counter primary">
            <a href="{{ route('cabinet.case.against') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($against_high_court_case_appeal_pending) ?></a></span>
            <span class="count-name">সরকারের বিপক্ষে আপিলের <br>জন্য
                    পেন্ডিং</a></span>
        </div>
    </div> --}}
    {{-- <div class="col-md-3">
        <div class="card-counter primary">
            <i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($sent_to_solicitor_case) ?></a></span>
            <span class="count-name">জবাব পেন্ডিং</a></span>
        </div>
    </div> --}}

    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.sentToSolicitorCaseList') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.sentToSolicitorCaseList') }}"><?= en2bn($sent_to_solicitor_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.sentToSolicitorCaseList') }}">জবাব পেন্ডিং</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.postponedInterimOrderCaseList') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.postponedInterimOrderCaseList') }}"></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.postponedInterimOrderCaseList') }}">স্থগিতাদেশ অন্তর্বর্তীকালীন
                    <br>পেন্ডিং মামলা</a></span>
        </div>
    </div>


    <div class="col-md-3">
        <div class="card-counter primary">
            <i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($five_years_running_highcourt_case) ?></a></span>
            <span class="count-name">হাইকোর্ট বিভাগে ৫ বছরের <br> অধিককাল চলমান মামলা
                </a></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-counter primary">
            <i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($five_years_running_appeal_case) ?></a></span>
            <span class="count-name">আপিল বিভাগে ৫ বছরের <br> অধিককাল চলমান মামলা
                </a></span>
        </div>
    </div>
</div>




<div class="row mb-5">
    @if (Auth::user()->role_id == 27 || Auth::user()->role_id == 28)
        <div class="col-md-3">
            <div class="card-counter lightgreen">
                <a href="#"><i class="fa fas fa-archway text-white"></i></a>
                <span class="count-numbers"><a href="#"><?= en2bn($total_ministry) ?></a></span>
                <span class="count-name"><a href="#">মোট মন্ত্রনালয়</a></span>
            </div>
        </div>
    @endif
</div>
