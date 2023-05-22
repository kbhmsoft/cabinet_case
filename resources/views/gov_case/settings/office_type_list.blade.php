@extends('layouts.cabinet.cab_default')


@section('content')

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-title h2 font-weight-bolder">{{ $page_title }}</h3>
      </div>
      <div class="card-toolbar">        
          @if(auth()->user()->can('create_new_case_category'))
            <a href="{{ route('cabinet.settings.category.add') }}" class="btn btn-sm btn-primary font-weight-bolder">
               <i class="la la-plus"></i>নতুন ক্যাটেগরি এন্ট্রি
            </a> 
          @else 
            <a href="#" class="btn btn-sm btn-secondary font-weight-bolder">
               <i class="la la-plus"></i>নতুন ক্যাটেগরি এন্ট্রি
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
      <table class="table table-hover mb-6 font-size-h6">
         <thead class="thead-light">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">নাম বাংলা</th>
               <th scope="col">নাম ইংরেজি</th>
               <th scope="col" width="150">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($office_types as $key => $row)
            <tr>
               <td scope="row" class="tg-bn">{{ en2bn($key + $office_types->firstItem()) }}.</td>
               <td>{{ $row->type_name_bn }}</td>             
               <td>{{ $row->type_name }}</td>
               <td>
                  
          @if(auth()->user()->can('office_type_update'))
            <a href="{{ route('cabinet.settings.office_type.edit', $row->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">সংশোধন</a> 
          @else 
            <a href="#" class="btn btn-secondary btn-sm font-weight-bold pt-1 pb-1">সংশোধন</a> 
          @endif


               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
    <div class="d-flex justify-content-center">
         {!! $office_types->links() !!}
      </div>   

   </div>
   <!--end::Card-->

   @endsection

   {{-- Includable CSS Related Page --}}
   @section('styles')
   <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
   <!--end::Page Vendors Styles-->
   @endsection     

   {{-- Scripts Section Related Page--}}
   @section('scripts')
   <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
   <script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
   <!--end::Page Scripts-->
   @endsection


