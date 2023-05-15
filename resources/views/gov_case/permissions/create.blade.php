@extends('layouts.cabinet.cab_default')

@section('content')
  
  <!--begin::Card-->
<div class="card card-custom">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h2>অনুমতি পরিচালনা </h2>
      </div>
      <div class="card-toolbar">        
         <button type="button" data-toggle="modal" data-target="#parentPermissionNameModal" class="btn btn-sm btn-success mr-3 font-weight-bolder">
            <i class="la la-plus"></i>পেরেন্ট অনুমতি নামে তৈরি
         </button>  

         <button type="button" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-sm btn-primary font-weight-bolder">
            <i class="la la-plus"></i>অনুমতি তৈরি করুন
         </button>                
      </div>
   </div>
   <div class="card-body">
      @if($errors->any())
      <div class="alert alert-danger">
          <ul>
             @foreach($errors->all() as $error)
               <li>{{ $error }}</li>
             @endforeach
          </ul>
      </div>
      @endif
      @if ($message = Session::get('success'))
      <div class="alert alert-success">
         <p>{{ $message }}</p>
      </div>
      @endif
      <table class="table table-hover mb-6 font-size-h6">
         <thead class="thead-light ">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">প্রদর্শনী নাম</th>
               <th scope="col">অনুমতি নাম</th>
               <th scope="col">প্যারেন্ট অনুমতি নাম</th>
               <th scope="col">প্রস্তুতকারক</th>
               <th scope="col">অবস্থা</th>
               <th scope="col" width="150">অ্যাকশন</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i = (($permissions->currentPage() -1) * $permissions->perPage() + 1);

            ?>
            @foreach ($permissions as $permission)
 
            <?php
                $parentName = App\Models\ParentPermissionName::find($permission->parent_permission_name_id);
                $user = App\Models\User::find($permission->user_id);

            ?>

            <tr>
               <th scope="row" class="tg-bn">{{ en2bn($i++) }}</th>
               <td>{{ $permission->display_name }}</td>
               <td>{{ $permission->name }}</td>
               <td>{{ $parentName? $parentName->name: '' }}</td>
               <td>{{ $user? $user->name: '' }}</td>
               <td>
                  @if($permission->status == 1)
                     <span class="badge badge-primary">সক্রিয়</span>
                  @else
                     <span class="badge badge-secondary">নিশক্রিয়</span>
                  @endif

               </td>
               
               <td>
                  <button type="button" onclick="updatePermissionModal({{$permission->id}}, '{{$permission->name}}', '{{$permission->status}}')" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">সংশোধন</button>
                  <a href="{{ route('cabinet.permissionItemDelete', $permission->id) }}" onclick="return confirm('আপনি কি নিশ্চিত ?')" class="btn btn-warning btn-shadow btn-sm font-weight-bold pt-1 pb-1">মুছে দিন</a>
               </td>
            </tr>
            @endforeach
           
         </tbody>
      </table>      
        {{ $permissions->links() }}
   </div>
</div>
<!--end::Card-->

 

      <!-- update Modal -->
      <div class="modal fade" id="updateRoleItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">ভূমিকা সংশোধন করুন</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('cabinet.updatePermission') }}" method="POST">
               @csrf
               <input type="hidden" name="permission_id" id="roleID">
               <div class="modal-body">
                   <div class="card-body card-block">
                        <div class="form-group">
                            <label for="name" class=" form-control-label">ভূমিকা নাম <span class="text-danger">*</span></label>
                            <input type="text" id="update_name" name="name" class="form-control form-control-sm" required>
                             
                        </div>
                        <div class="form-group">
                            <label for="name" class=" form-control-label">অবস্থা<span class="text-danger">*</span></label>
                             <select name="status" class="form-control">
                                <option class="status1" value="1">সক্রিয়</option>
                                <option class="status2" value="0">নিশক্রিয়</option>
                             </select>
                        </div>
                      
                  </div>
               </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
              <button type="submit" class="btn btn-primary">সংশোধন করুন</button>
            </div>
         </form>
          </div>
        </div>
      </div>


<!-- update Modal -->


 

<!-- create Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">অনুমতি তৈরি করুন</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('cabinet.storePermission') }}" method="POST">
         @csrf
         <div class="modal-body">
             <div class="card-body card-block">

                <div class="form-group">
                    <label for="name" class=" form-control-label">প্যারেন্ট অনুমতি নাম<span class="text-danger">*</span></label>
                     <select name="parent_permission_id" class="form-control">
                        @foreach($parentPermissions as $parent)
                        <option value="{{$parent->id}}">{{$parent->name}}</option>
                        @endforeach
                     </select>
                </div>

                  <div class="form-group">
                      <label for="name" class=" form-control-label">অনুমতি নাম <span class="text-danger">*</span></label>
                      <input type="text" id="name" name="name" placeholder="অনুমতির নাম লিখুন" class="form-control form-control-sm" required>
                     
                  </div>
                
            </div>
         </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
        <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
      </div>
   </form>
    </div>
  </div>
</div>

 
<!-- parent permission name Modal -->
<div class="modal fade" id="parentPermissionNameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">প্যারেন্ট অনুমতি নাম তৈরি করুন</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('cabinet.storePatentPermissionName') }}" method="POST">
         @csrf
         <div class="modal-body">
             <div class="card-body card-block">
                  <div class="form-group">
                      <label for="name" class=" form-control-label">প্যারেন্ট নাম <span class="text-danger">*</span></label>
                      <input type="text" id="name" name="name" placeholder="প্যারেন্ট অনুমতির নাম লিখুন" class="form-control form-control-sm" required>
                     
                  </div>
                
            </div>
         </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">বন্ধ</button>
        <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
      </div>
   </form>
    </div>
  </div>
</div>



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
   
   function updatePermissionModal(id, name, status){
       $('#updateRoleItem').modal().show();

       $('#roleID').val(id);
       $('#update_name').val(name);

       var checkstatus = status;

        $('.status2').attr('selected', false);
        $('.status2').attr('selected', false);
       if(checkstatus == '0'){
            $('.status2').attr('selected','selected');
       }else{
          $('.status1').attr('selected','selected');
       }

   }
</script>





@endsection


