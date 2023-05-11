@extends('layouts.cabinet.cab_default')

@section('content')
 

<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h2>অনুমতি প্রদান পরিচালনা </h2>
      </div>
      <div class="card-toolbar">        
       

   <!--       <button type="button" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-sm btn-primary font-weight-bolder">
            <i class="la la-plus"></i>অনুমতি তৈরি করুন
         </button> -->                
      </div>
   </div>
   <div class="card-body">
      
      <table class="table table-hover mb-6 font-size-h6">
         <thead class="thead-light ">
            <tr>
               <th width="2%" >#</th>
               <th width="35%">ব্যাবহারকারী নাম</th>
               <th width="10%">ইমেল</th>
               <th width="10%">মোবাইল</th>
               <th width="10%">ভূমিকা</th>
               <th width="20%">অফিস</th>
               <th width="5%">অবস্থা</th>
               <th width="5%">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i = 1;

            ?>
            @foreach ($users as $user)

            <tr>
               <th scope="row" class="tg-bn">{{ en2bn($i++) }}</th>
               <td>{{ $user->name }}</td>
               <td>{{ $user->email }}</td>
               <td>{{ $user->mobile_no }}</td>
               <td>{{ $user->role? $user->role->name: '' }}</td>
               <td>{{ $user->office? $user->office->office_name_bn: '' }}</td>
                
               <td>
                  
                    <span class="badge badge-primary">সক্রিয়</span>
                 
               </td>
               
               <td>
                  <a href="{{ route('cabinet.userPermissionManage', $user->id) }}" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">বিস্তারিত</a>
                   
               </td>
            </tr>
            @endforeach
           
         </tbody>
      </table>      
    
   </div>
</div>
<!--end::Card-->


 

 



@endsection

<!-- {{-- Includable CSS Related Page --}} -->
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection     

<!-- {{-- Scripts Section Related Page--}} -->
@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
<!--end::Page Scripts-->


<script>
   
 
</script>





@endsection


