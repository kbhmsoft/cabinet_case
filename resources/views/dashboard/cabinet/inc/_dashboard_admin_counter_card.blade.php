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
        <div class="card-counter success">
            <a href="{{ route('cabinet.totalMinistryOffice') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.totalMinistryOffice') }}"><?= en2bn($total_ministry) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.totalMinistryOffice') }}">মোট ব্যাবহারকারী মন্ত্রণালয়</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter success">
            <a href="{{ route('cabinet.totalDoptor') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.totalDoptor') }}"><?= en2bn($total_doptor) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.totalDoptor') }}">ব্যাবহারকারী দপ্তর</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter success">
            <a href="{{ route('cabinet.totalDivisionOffice') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.totalDivisionOffice') }}"><?= en2bn($total_division) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.totalDivisionOffice') }}">ব্যাবহারকারী বিভাগীয় প্রশাসন</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter success">
            <a href="{{ route('cabinet.totalDistrictOffice') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.totalDistrictOffice') }}"><?= en2bn($total_district) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.totalDistrictOffice') }}">ব্যাবহারকারী জেলা প্রশাসন</a></span>
        </div>
    </div>
</div>


<div class="row mb-5">

    <div class="col-md-3">
        <div class="card-counter lightgreen">
            <a href="{{ route('cabinet.case.fiveYearsRunningHighCourt') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.fiveYearsRunningHighCourt') }}"><?= en2bn($five_years_running_highcourt_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.fiveYearsRunningHighCourt') }}">হাইকোর্ট বিভাগে ৫ বছরের <br> অধিককাল চলমান মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter lightgreen">
            <a href="{{ route('cabinet.case.fiveYearsRunningAppealCase') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.fiveYearsRunningAppealCase') }}"><?= en2bn($five_years_running_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.fiveYearsRunningAppealCase') }}">আপিল বিভাগে ৫ বছরের <br> অধিককাল চলমান মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter lightgreen">
            <i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($total_case) ?></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                মোট মামলা</a></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-counter lightgreen">
            <a href="{{ route('cabinet.case.highcourt') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.highcourt') }}"><?= en2bn($total_high_court_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.highcourt') }}">হাইকোর্ট বিভাগে মোট মামলা</a></span>
        </div>
    </div>


</div>



<div class="row mb-5">

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.highcourt.running') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
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

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.appellateDivision') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appellateDivision') }}"><?= en2bn($total_appeal) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appellateDivision') }}">আপিল বিভাগে মোট মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.appellateDivision.running') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appellateDivision.running') }}"><?= en2bn($running_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appellateDivision.running') }}">আপিল বিভাগে চলমান মামলা</a></span>
        </div>
    </div>

</div>

<div class="row mb-5">
    {{-- <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.appellateDivision') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appellateDivision') }}"><?= en2bn($total_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appellateDivision') }}">আপিল বিভাগে মোট মামলা</a></span>
        </div>
    </div> --}}



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
    {{-- <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.against') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($against_high_court_case_appeal_pending) ?></a></span>
            <span class="count-name">সরকারের বিপক্ষে আপিলের <br>জন্য
                পেন্ডিং</a></span>
        </div>
    </div> --}}


    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.appealAgainstGovt') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appealAgainstGovt') }}"><?= en2bn($appealAgainstGovt) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appealAgainstGovt') }}">সরকারের বিপক্ষে আপিলের <br>জন্য
                    পেন্ডিং</a></span>
        </div>
    </div>


    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.sentToSolicitor') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.sentToSolicitor') }}"><?= en2bn($sent_to_solicitor_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.sentToSolicitor') }}">সরকারের বিপক্ষে আপিলের <br>জন্য
                    পেন্ডিং</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.againstPostponedOrder') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.againstPostponedOrder') }}"><?= en2bn($against_postpond_order) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.againstPostponedOrder') }}">স্থগিতাদেশ অন্তর্বর্তীকালীন
                    <br>পেন্ডিং মামলা</a></span>
        </div>
    </div>

</div>

{{-- <div class="row mb-5">
    <div class="col-md-3">
        <div class="card-counter primary">
            <i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($sent_to_solicitor_case) ?></a></span>
            <span class="count-name">জবাব পেন্ডিং</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter primary">
            <i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><?= en2bn($against_postpond_order) ?></a></span>
            <span class="count-name">স্থগিতাদেশ অন্তর্বর্তীকালীন
                <br>পেন্ডিং মামলা</a></span>
        </div>
    </div>
</div> --}}





