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
        <a href="{{ route('cabinet.totalMinistryOffice') }}" style="text-decoration: none; color: inherit;">
            <div class="card-counter success">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($total_ministry) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">মোট
                    ব্যাবহারকারী মন্ত্রণালয়</span>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('cabinet.totalDoptor') }}" style="text-decoration: none; color: inherit;">
            <div class="card-counter success">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($total_doptor) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                    ব্যাবহারকারী দপ্তর</span>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('cabinet.totalDivisionOffice') }}" style="text-decoration: none; color: inherit;">
            <div class="card-counter success">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($total_division) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                    ব্যাবহারকারী বিভাগীয় প্রশাসন</span>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('cabinet.totalDistrictOffice') }}" style="text-decoration: none; color: inherit;">
            <div class="card-counter success">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($total_district) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                    ব্যাবহারকারী জেলা প্রশাসন</span>
            </div>
        </a>
    </div>
</div>


<div class="row mb-5">


    <div class="col-md-3">
        <a href="{{ route('cabinet.case.fiveYearsRunningHighCourt') }}" style="text-decoration: none; color: inherit;">
            <div class="card-counter lightgreen">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($five_years_running_highcourt_case) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                    হাইকোর্ট বিভাগে ৫ বছরের <br> অধিককাল
                    চলমান মামলা</span>
            </div>
        </a>
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
