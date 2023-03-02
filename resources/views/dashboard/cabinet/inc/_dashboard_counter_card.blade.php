<style type="text/css">
   .count-numbers{
      font-size: 1.5em !important;
   }
   .count-name{
      font-size: 1.1em !important;
   }
   .fa{
      font-size: 3.5em !important;
   }
    @media screen and (width: 768px) {
     .count-numbers{
        font-size: 1.5em !important;
     }
     .count-name{
        font-size: 1em !important;
     }
     .fa{
        font-size: 3.5em !important;
     }
    }
</style>

   <div class="row mb-5">

      <div class="col-md-3">
         <div class="card-counter primary">
            <a href="{{ route('cabinet.case.running') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <!-- <span class="count-numbers"><a href="{{ route('cabinet.case.running') }}"><?=en2bn($running_case)?></a></span> -->
            <span class="count-numbers"><a href="{{ route('cabinet.case.running') }}"><?=en2bn($running_case_appeal)?></a></span>
            <span class="count-name"><a href="{{ route('cabinet.case.running') }}">চলমান মামলা</a></span>
         </div>
      </div>

      <div class="col-md-3">
         <div class="card-counter danger">
            <a href="{{ route('cabinet.case.highcourt') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a href="{{ route('cabinet.case.highcourt') }}"><?=en2bn($high_court_case)?></a></span>
            <span class="count-name" style="font-size: 14px !important; font-weight: 800 !important;">
               <a href="{{ route('cabinet.case.highcourt', 2) }}">হাইকোর্ট বিভাগে চলমান মামলা</a></span>
         </div>
      </div>

      <div class="col-md-3">
         <div class="card-counter info">
            <a href="{{ route('cabinet.case.appellateDivision') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a href="{{ route('cabinet.case.appellateDivision') }}"><?=en2bn($appeal_court_case)?></a></span>
            <span class="count-name" style="font-size: 15px !important; font-weight: 800 !important;">
               <a href="{{ route('cabinet.case.appellateDivision', 1) }}">আপিল বিভাগে চলমান মামলা</a></span>
         </div>
      </div>

      <div class="col-md-3">
         <div class="card-counter success">
            <a href="{{ route('cabinet.case.complete') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a href="{{ route('cabinet.case.complete') }}"><?=en2bn($completed_case)?></a></span>
            <span class="count-name"><a href="{{ route('cabinet.case.complete') }}">নিস্পত্তিকৃত মামলা</a></span>
         </div>
      </div>
   </div>
   <div class="row mb-5">
      <div class="col-md-3">
         <div class="card-counter success">
            <a href="{{ route('cabinet.case.not_against') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a href="{{ route('cabinet.case.not_against') }}"><?=en2bn($not_against_gov)?></a></span>
            <span class="count-name"><a href="{{ route('cabinet.case.not_against') }}">সরকারের পক্ষে </a></span>
         </div>
      </div>
      <div class="col-md-3">
         <div class="card-counter primary">
            <a href="{{ route('cabinet.case.against') }}"><i class="fa fas fa-layer-group text-white"></i></a>
            <span class="count-numbers"><a href="{{ route('cabinet.case.against') }}"><?=en2bn($against_gov)?></a></span>
            <span class="count-name"><a href="{{ route('cabinet.case.against') }}">সরকারের বিপক্ষে </a></span>
         </div>
      </div>

   @if(Auth::user()->role_id ==27 || Auth::user()->role_id ==28)
      <div class="col-md-3">
         <div class="card-counter lightgreen">
            <a href="{{ route('office.level', 9) }}"><i class="fa fas fa-archway text-white"></i></a>
            <span class="count-numbers"><a href="{{ route('office.level', 9) }}"><?=en2bn($total_ministry)?></a></span>
            <span class="count-name"><a href="{{ route('office.level', 9) }}">মোট মন্ত্রনালয়</a></span>
         </div>
      </div>
   @endif
   </div>