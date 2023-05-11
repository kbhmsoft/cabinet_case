@extends('layouts.cabinet.cab_default')

@section('content')
   <!-- <form action="{{ route('cabinet.storeUpdateUserPermissionAll') }}"  -->



<!--begin::Card-->
<div class="card card-custom">

   <form action="" method="POST">
      @csrf

   <input type="hidden" name="user_id" value="{{ $user_id }}">
    
   <div class="card-header flex-wrap py-5 mt-5">
      <div class="card-title mb-0 mt-2" >
         <h2 style="display: contents">অনুমতি প্রদান পরিচালনা করুন </h2>

         <button type="submit" class="btn btn-sm btn-primary font-weight-bolder float-right" onclick="return confirm('আপনি কি নিশ্চিত অনুমতি বরাদ্দ সংশোধন করতে চান?')">
            <i class="far fa-check-circle"></i>অনুমতি বরাদ্দ সংশোধন
         </button>  
      </div>
      <div class="card-toolbar">        
       
      </div>
   </div>
   <div class="card-body">    
   <div class="row grid">
   @foreach($parentPermissions as $parentPermission )
      <div class="col-lg-4 col-md-4 mb-5 grid-item">
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
                        <input type="checkbox" name="permissionId[]" value="{{$permission->id}}" class="mr-1" @if($rolePermission != null) checked @endif> 
                         <span>{{$permission->name}}</span>
                     </li>
                     @endforeach
                  </ul>
               </div>
            </div>
      </div>
   @endforeach 





      <!-- 

      <div class="col-lg-4 col-md-4 mb-5 grid-item">
            <div class="card-bodys cardbody">
               <div class="cardheader">
                     Report
                </div>
               <div class="listPermission">
                  <ul>
                     
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>create</span>
                     </li>
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>all_report</span>
                     </li>
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>delete_report</span>
                     </li>
                      
                     
                  </ul>
               </div>
            </div>
      </div>
    

      <div class="col-lg-4 col-md-4 mb-5 grid-item">
            <div class="card-bodys cardbody">
               <div class="cardheader">
                     New Case Entry
                </div>
               <div class="listPermission">
                  <ul>
                     
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>add_case</span>
                     </li>
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>case_delete_test</span>
                     </li>
                   
                      
                     
                  </ul>
               </div>
            </div>
      </div>
    
      
      <div class="col-lg-4 col-md-4 mb-5 grid-item">
            <div class="card-bodys cardbody">
               <div class="cardheader">
                     Appeal Division
                </div>
               <div class="listPermission">
                  <ul>
                     
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>create_appeal</span>
                     </li>
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>approved</span>
                     </li>
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>reject</span>
                     </li>
                    
                     
                  </ul>
               </div>
            </div>
      </div>
      <div class="col-lg-4 col-md-4 mb-5 grid-item">
            <div class="card-bodys cardbody">
               <div class="cardheader">
                     Hicourt Department
                </div>
               <div class="listPermission">
                  <ul>
                     
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>test_permission</span>
                     </li>
                    
                     
                  </ul>
               </div>
            </div>
      </div>
      <div class="col-lg-4 col-md-4 mb-5 grid-item">
            <div class="card-bodys cardbody">
               <div class="cardheader">
                     Office Management
                </div>
               <div class="listPermission">
                  <ul>
                     
                     <li>
                        <input type="checkbox" name="permissionId[]" value="" class="mr-1" > 
                         <span>test_permission_one</span>
                     </li>
                    
                     
                  </ul>
               </div>
            </div>
      </div> -->
    

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
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

<script>
 
   // jQuery
$('.grid').masonry({
  
  itemSelector: '.grid-item'
});
    
</script>





@endsection


