
    <table class="table table-hover mb-6 font-size-h6">
        <thead class="thead-light ">
            <tr>
               <th scope="col" width="30">#</th>
               <th scope="col">প্রদর্শনী নাম</th>
               <th class="text-center" scope="col">অনুমতি নাম</th>
               <th class="text-center" scope="col">প্যারেন্ট অনুমতি নাম</th>
               <th class="text-center" scope="col">প্রস্তুতকারক</th>
               <th class="text-center" scope="col">অবস্থা</th>
               <th class="text-center" scope="col" width="150">অ্যাকশন</th>
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
               <td class="text-center"><span class="badge bg-success" style="font-size: 16px" >{{ $permission->name }}</span></td>
               <td class="text-center">{{ $parentName? $parentName->name: '' }}</td>
               <td class="text-center">{{ $user? $user->name: '' }}</td>
               <td class="text-center">
                  @if($permission->status == 1)
                     <span class="badge badge-primary">সক্রিয়</span>
                  @else
                     <span class="badge badge-secondary">নিশক্রিয়</span>
                  @endif
               </td>
               
               <td class="text-center">
                  <button type="button" onclick="updatePermissionModal('{{$permission->id}}', '{{$permission->name}}','{{$permission->display_name}}', '{{$permission->status}}')" class="btn btn-success btn-shadow btn-sm font-weight-bold pt-1 pb-1">সংশোধন</button>
                  <a href="{{ route('cabinet.permissionItemDelete', $permission->id) }}" onclick="return confirm('আপনি কি নিশ্চিত ?')" class="btn btn-warning btn-shadow btn-sm font-weight-bold pt-1 pb-1">মুছে দিন</a>
               </td>
            </tr>
            @endforeach
           
         </tbody>
    </table>
         
        {{ $permissions->links() }}