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
        <a href="{{ route('cabinet.case.fiveYearsRunningAppealCase') }}" style="text-decoration: none; color: inherit;">
            <div class="card-counter lightgreen">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($five_years_running_appeal_case) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                    আপিল বিভাগে ৫ বছরের <br> অধিককাল
                    চলমান
                    মামলা</span>
            </div>
        </a>
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
        <a href="{{ route('cabinet.case.totalHighcourt') }}" style="text-decoration: none; color: inherit;">
            <div class="card-counter lightgreen">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($total_high_court_case) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                    হাইকোর্ট বিভাগে মোট মামলা</span>
            </div>
        </a>
    </div>

</div>

<div class="row mb-5">

    <div class="col-md-3">
        <a href="{{ route('cabinet.case.totalHighcourtRunning') }}">
            <div class="card-counter danger">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($running_high_court_case) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                    হাইকোর্ট বিভাগে চলমান মামলা</span>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('cabinet.case.totalHighcourtComplete') }}">
            <div class="card-counter danger">
                <i class="fa fas fa-layer-group text-white"></i>
                <span class="count-numbers"><?= en2bn($final_high_court_case) ?></span>
                <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
                    হাইকোর্ট বিভাগে নিস্পত্তিকৃত
                    মামলা</span>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('cabinet.case.totalAppellateDivision') }}">
        <div class="card-counter danger">
           <i class="fa fas fa-layer-group text-white"></i>
            <span class="count-numbers"><?= en2bn($total_appeal) ?></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
              আপিল বিভাগে মোট মামলা</span>
        </div>
    </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('cabinet.case.appellateDivisionRunning') }}">
        <div class="card-counter danger">
            <i class="fa fas fa-layer-group text-white"></i>
            <span class="count-numbers"><?= en2bn($running_appeal_case) ?></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
               আপিল বিভাগে চলমান মামলা</span>
        </div>
    </a>
    </div>

</div>

<div class="row mb-5">
    <div class="col-md-3">
        <a href="{{ route('cabinet.case.appellateDivisionComplete') }}">
        <div class="card-counter info">
            <i class="fa fas fa-layer-group text-white"></i>
            <span class="count-numbers"><?= en2bn($final_appeal_case) ?></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                আপিল বিভাগে নিস্পত্তিকৃত
                    মামলা</span>
        </div>
    </div>
    <div class="col-md-3">
        <a href="{{ route('cabinet.case.appealCaseAgainstGovt') }}">
        <div class="card-counter info">
            <i class="fa fas fa-layer-group text-white"></i>
            <span class="count-numbers"><?= en2bn($appealAgainstGovt) ?></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                সরকারের বিপক্ষে আপিলের <br>জন্য
                    পেন্ডিং</span>
        </div>
    </a>
    </div>


    <div class="col-md-3">
        <a href="{{ route('cabinet.case.sentToSolicitorCase') }}">
        <div class="card-counter info">
            <i class="fa fas fa-layer-group text-white"></i>
            <span class="count-numbers"><?= en2bn($sent_to_solicitor_case) ?></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
               জবাব পেন্ডিং</span>
        </div>
    </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('cabinet.case.againstCasePostponedOrder') }}">
        <div class="card-counter info">
           <i class="fa fas fa-layer-group text-white"></i>
            <span class="count-numbers"><?= en2bn($against_postpond_order) ?></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
                স্থগিতাদেশ অন্তর্বর্তীকালীন
                    <br>পেন্ডিং মামলা</span>
        </div>
    </a>
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
