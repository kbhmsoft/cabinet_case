@extends('layouts.cabinet.cab_default')

@section('content')
 

<!--begin::Card-->
<div class="card card-custom">

   <form action="{{ route('cabinet.storeUpdateUserPermissionAll') }}" method="POST">
      @csrf

   <input type="hidden" name="user_id" value="{{ $user_id }}">
    
   <div class="card-header flex-wrap py-5 mt-5">
      <div class="card-title mb-0 mt-2" >
         <h2 style="display: contents">অনুমতি প্রদান পরিচালনা করুন </h2>

         <button type="submit" class="btn btn-sm btn-primary font-weight-bolder float-right">
            <i class="la file-check"></i>অনুমতি বরাদ্দ সংশোধন
         </button>  
      </div>
      <div class="card-toolbar">        
       
              
      </div>
   </div>
   <div class="card-body">
      
   <div class="row">

      @foreach($parentPermissions as $parentPermission )
      <div class="col-lg-4 col-md-4 mb-5">
            <div class="card-bodys cardbody">
               <div class="cardheader">
                     {{ $parentPermission->name }}
                </div>
               <div class="listPermission">
                  <ul>
                     @foreach($parentPermission->permissions as $permission)
                     <?php
                        $rolePermission = App\Models\RolePermission::where(['permission_id' => $permission->id, 'user_id' => $user_id])->first();
                     ?>
                     <li>
                        <input type="checkbox" name="permissionId[]" value="{{$permission->id}}" class="mr-1" @if($rolePermission != null) checked @endif> {{$permission->name}}
                     </li>
                     @endforeach
                  </ul>
               </div>
            </div>
      </div>
   @endforeach

   </div>

    
   </div>
</form>

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


