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
            <a href="{{ route('cabinet.case.fiveYearsRunningHighCourt') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.fiveYearsRunningHighCourt') }}"><?= en2bn($five_years_running_highcourt_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.fiveYearsRunningHighCourt') }}">হাইকোর্ট বিভাগে ৫ বছরের <br> অধিককাল
                    চলমান মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter lightgreen">
            <a href="{{ route('cabinet.case.fiveYearsRunningAppealCase') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.fiveYearsRunningAppealCase') }}"><?= en2bn($five_years_running_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.fiveYearsRunningAppealCase') }}">আপিল বিভাগে ৫ বছরের <br> অধিককাল চলমান
                    মামলা</a></span>
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
            <a href="{{ route('cabinet.case.totalHighcourt') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.totalHighcourt') }}"><?= en2bn($total_high_court_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.totalHighcourt') }}">হাইকোর্ট বিভাগে মোট মামলা</a></span>
        </div>
    </div>
</div>

<div class="row mb-5">

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.totalHighcourtRunning') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.totalHighcourtRunning') }}"><?= en2bn($running_high_court_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.totalHighcourtRunning') }}">হাইকোর্ট বিভাগে চলমান মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.totalHighcourtComplete') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.totalHighcourtComplete') }}"><?= en2bn($final_high_court_case) ?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.totalHighcourtComplete') }}">হাইকোর্ট বিভাগে নিস্পত্তিকৃত
                    মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.totalAppellateDivision') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.totalAppellateDivision') }}"><?= en2bn($total_appeal) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.totalAppellateDivision') }}">আপিল বিভাগে মোট মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter danger">
            <a href="{{ route('cabinet.case.appellateDivisionRunning') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appellateDivisionRunning') }}"><?= en2bn($running_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appellateDivisionRunning') }}">আপিল বিভাগে চলমান মামলা</a></span>
        </div>
    </div>

</div>

<div class="row mb-5">

    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.appellateDivisionComplete') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appellateDivisionComplete') }}"><?= en2bn($final_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appellateDivisionComplete') }}">আপিল বিভাগে নিস্পত্তিকৃত
                    মামলা</a></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.appealCaseAgainstGovt') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.appealCaseAgainstGovt') }}"><?= en2bn($appealAgainstGovt) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.appealCaseAgainstGovt') }}">সরকারের বিপক্ষে আপিলের <br>জন্য
                    পেন্ডিং</a></span>
        </div>
    </div>


    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.sentToSolicitorCase') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.sentToSolicitorCase') }}"><?= en2bn($sent_to_solicitor_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.sentToSolicitorCase') }}">জবাব পেন্ডিং</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter info">
            <a href="{{ route('cabinet.case.againstCasePostponedOrder') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.againstCasePostponedOrder') }}"><?= en2bn($against_postpond_order) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.againstCasePostponedOrder') }}">স্থগিতাদেশ অন্তর্বর্তীকালীন
                    <br>পেন্ডিং মামলা</a></span>
        </div>
    </div>

</div>
{{-- <div class="row mb-5">
    <div class="col-md-3">
        <div class="card-counter primary">
            <a href="{{ route('cabinet.case.mostImportantAppealCase') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.mostImportantAppealCase') }}"><?= en2bn($most_important_appeal_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.mostImportantAppealCase') }}">আপিল বিভাগে <br> অধিক গুরুত্বপূর্ণ
                    মামলা</a></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter primary">
            <a href="{{ route('cabinet.case.mostImportantHighcourtCase') }}"><i
                    class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a
                    href="{{ route('cabinet.case.mostImportantHighcourtCase') }}"><?= en2bn($most_important_highcourt_case) ?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                <a href="{{ route('cabinet.case.mostImportantHighcourtCase') }}">হাইকোর্ট বিভাগে <br>অধিক গুরুত্বপূর্ণ
                    মামলা </a></span>
        </div>
    </div>

</div> --}}
