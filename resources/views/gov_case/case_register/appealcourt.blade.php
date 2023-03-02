@extends('layouts.cabinet.cab_default')

@section('content')

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
      </div>

      <div class="card-toolbar">
         @if(userInfo()->role_id == 28 ||userInfo()->role_id == 31 ||userInfo()->role_id == 33)
         <a href="{{ route('cabinet.case.create') }}" class="btn btn-sm btn-primary font-weight-bolder mr-2">
            <i class="la la-plus"></i>নতুন মামলা এন্ট্রি
         </a>
         @endif
         
      </div>
      

   </div>
   <div class="card-body">
      @if ($message = Session::get('success'))
      <div class="alert alert-success">
         {{ $message }}
      </div>
      @endif

      @include('gov_case.search')
     
      <table class="table table-hover mb-6 font-size-h5">
         <thead class="thead-light font-size-h6">
            <tr>
               <th scope="col" width="30">ক্রমিক</th>
               <th scope="col">লিভ টু আপিল নম্বর ও দায়েরের তারিখ</th>
               <th scope="col">আপিলকারীর নাম/ঠিকানা</th>
               <th scope="row">মুল প্রতিবাদি দপ্তর-সংস্থা</th>
               <th scope="col">মামলার বিষয়বস্তু</th>
               <th scope="col" width="70">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($cases as $key => $row)
            <tr>
               <td scope="row" class="tg-bn">{{ en2bn($key + $cases->firstItem()) }}.</td>
               <td style="width: 10px;">{{ $row->case_no }},<br>{{ $row->date_issuing_rule_nishi ? en2bn($row->date_issuing_rule_nishi):''}}</td>
               <td>{{ $row->badis->first()->name ?? '-' }},<br>{{ $row->badis->first()->address ?? '-' }} </td>
               <td>
                  @foreach ($row->mainBibadis as $key=>$bibadi)
                     {{en2bn($key+1)}}.{{ $bibadi->ministry->office_name_bn ?? '-' }},{{ $bibadi->department->office_name_bn ?? '-' }}<br>
                  @endforeach
               </td>
               <td>{{ $row->subject_matter ?? '-'}}</td>
               <td>
                    <div class="btn-group float-right">
                        <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">অ্যাকশন</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('cabinet.case.register', $row->id) }}">রেজিস্টার</a>
                            <a class="dropdown-item" href="{{ route('cabinet.case.details', $row->id) }}">বিস্তারিত তথ্য</a>
                            <a class="dropdown-item" href="{{ route('cabinet.case.edit', $row->id) }}">সংশোধন</a>
                            @if($row->action_user_role_id == userInfo()->role_id)
                            <a class="dropdown-item" href="{{ route('cabinet.case.action.details', $row->id) }}">জবাব প্রেরণ</a>
                            @endif
                        </div>
                    </div>
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>

      <div class="d-flex justify-content-center">
         {!! $cases->links() !!}
      </div>
   </div>
   <!--end::Card-->

   @endsection

   {{-- Includable CSS Related Page --}}
   @section('styles')
   <!-- <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" /> -->
   <!--end::Page Vendors Styles-->
   @endsection

   {{-- Scripts Section Related Page--}}
   @section('scripts')
   <!-- <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
   <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
 -->


<!--end::Page Scripts-->
@endsection


