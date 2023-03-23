    <div class="row">
       <div class="col-md-8">
           <div class="card card-custom">
              <div class="card-header flex-wrap bg-danger py-5">
                 <div class="card-title">
                    <h3 class="card-label h3 font-weight-bolder"> পদক্ষেপ নিতে হবে এমন মামলাসমূহ</h3>
                 </div>
              </div>
              <div class="card-body p-0">
                 <ul class="navi navi-border navi-hover navi-active">
                    @forelse ($gov_case_status as $row)
                       {{-- @dd($row) --}}
                    <li class="navi-item">
                       <a class="navi-link" href="{{ route('cabinet.case.action.receive', $row->case_status_id) }}">
                       {{-- <a class="navi-link" href=""> --}}
                          <span class="navi-icon"><i class="fas fa-folder-open icon-lg text-danger mr-3"></i></span>
                          <div class="navi-text">
                             <span class="d-block font-weight-bold h4 pt-2">{{ $row->case_status->status_name }}</span>
                          </div>
                          <span class="navi-label">
                             <span class="label label-xl label-danger h5">{{ en2bn($row->total_case) }}</span>
                          </span>
                       </a>
                    </li>

                    @empty
                    <!-- <li class="navi-item">
                       <div class="alert alert-custom alert-light-success fade show m-5" role="alert">
                          <div class="alert-icon">
                             <i class="flaticon-list"></i>
                          </div>
                          <div class="alert-text font-size-h4">পদক্ষেপ নিতে হবে এমন কোন মামলা পাওয়া যায়নি</div>
                       </div>
                    </li> -->
                    @endforelse
                    @if(!empty($against_gov_case))
                      <li class="navi-item">
                         <a class="navi-link" href="{{ route('cabinet.case.othersaction.againstgov') }}">
                            <span class="navi-icon"><i class="fas fa-folder-open icon-lg text-danger mr-3"></i></span>
                            <div class="navi-text">
                               <span class="d-block font-weight-bold h4 pt-2">সরকারের বিপক্ষে রায় হওয়া মামলা</span>
                            </div>
                            <span class="navi-label">
                               <span class="label label-xl label-danger h5">{{ en2bn($against_gov_case) }}</span>
                            </span>
                         </a>
                      </li>
                    @endif
                    @if(!empty($sent_to_solicitor_case))
                      <li class="navi-item">
                         <a class="navi-link" href="{{ route('cabinet.case.othersaction.senttosol') }}">
                            <span class="navi-icon"><i class="fas fa-folder-open icon-lg text-danger mr-3"></i></span>
                            <div class="navi-text">
                               <span class="d-block font-weight-bold h4 pt-2">সলিসিটর অনুবিভাগে জবাব প্রেরণের জন্য অপেক্ষমান </span>
                            </div>
                            <span class="navi-label">
                               <span class="label label-xl label-danger h5">{{ en2bn($sent_to_solicitor_case) }}</span>
                            </span>
                         </a>
                      </li>
                    @endif
                    @if(!empty($sent_to_ag_from_sol_case))
                      <li class="navi-item">
                         <a class="navi-link" href="{{ route('cabinet.case.othersaction.senttoagfromsol') }}">
                            <span class="navi-icon"><i class="fas fa-folder-open icon-lg text-danger mr-3"></i></span>
                            <div class="navi-text">
                               <span class="d-block font-weight-bold h4 pt-2">সলিসিটর অফিস হতে এটর্নি জেনারেল অফিসে জবাব প্রেরণের জন্য অপেক্ষমান </span>
                            </div>
                            <span class="navi-label">
                               <span class="label label-xl label-danger h5">{{ en2bn($sent_to_ag_from_sol_case) }}</span>
                            </span>
                         </a>
                      </li>
                    @endif
                    @if(!empty($against_postpond_order))
                      <li class="navi-item">
                         <a class="navi-link" href="{{ route('cabinet.case.othersaction.stepnottakenAgainstpostpondorder') }}">
                            <span class="navi-icon"><i class="fas fa-folder-open icon-lg text-danger mr-3"></i></span>
                            <div class="navi-text">
                               <span class="d-block font-weight-bold h4 pt-2">স্থগিতাদেশের বিপরীতে ব্যাবস্থা গ্রহণের জন্য অপেক্ষমান </span>
                            </div>
                            <span class="navi-label">
                               <span class="label label-xl label-danger h5">{{ en2bn($against_postpond_order) }}</span>
                            </span>
                         </a>
                      </li>
                    @endif
                 </ul>
              </div>
           </div>
        </div>
    </div>