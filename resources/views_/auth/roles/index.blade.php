@extends('layouts/contentNavbarLayout')

@section('title', 'Role Management')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Role Management </span> 
  @can('add_role')
    <span class="text-muted fw-light pull-right">
      <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" class="btn btn-sm btn-primary font-weight-bolder">
      <box-icon name='plus' color="white"></box-icon> Create Role 
      </button>  
    </span>
  @endcan
</h4>

<!--begin::Card-->
<div class="card">
   <div class="card-body">
      @include('alerts.alert')
      <table id="example" class="display dataTable table table-hover table-bordered mb-6 font-size-h6" cellspacing="0" width="100%" role="grid" aria-describedby="example_info">
         <thead class="thead-light ">
            <tr>
               <th class="text-center">Sl.No</th>
               <th class="text-center">Role Name</th>
               <th class="text-center">Status</th>
               @can(['edit_role','edit_assign_permission','delete_role'])
               <th class="text-center">Action</th>
               @endCan
            </tr>
         </thead>
         <tbody>
            <?php
               $i = (($roles->currentPage() -1) * $roles->perPage() + 1);
            ?>
            @foreach ($roles as $role)
                <?php
                    $user = App\Models\User::find($role->created_by);
                ?>
            <tr>
               <th scope="row" class="tg-bn">{{ ($i++) }}</th>
               <td class="text-left">{{ $role->name??'' }}</td>
               <td class="text-center">
                  @if($role->status == 1)
                     <span class="btn btn-success btn-xs">Active</span>
                  @else
                     <span class="btn btn-danger btn-xs">Inactive</span>
                  @endif
               </td>
               @can(['edit_role','edit_assign_permission','delete_role'])
                   <td class="text-center">
                        @can('edit_role')
                          <button type="button" onclick="updateRoleModal('{{$role->id}}', '{{$role->name}}', '{{$role->status}}')" class="btn btn-secondary btn-shadow btn-sm font-weight-bold pt-1 pb-1">Update</button>
                        @endcan
                        @can('delete_role')
                          <a href="{{ route('roleItemDelete', $role->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-shadow btn-sm font-weight-bold pt-1 pb-1">Delete</a>
                        @endcan
        
                        @can('edit_assign_permission')
                          <a href="{{ route('acl-updateRolePermissions', $role->id) }}" class="btn btn-primary btn-shadow btn-sm font-weight-bold  pt-1 pb-1">Modify Permissions Allocation</a>
                        @endcan
                   </td>
               @endCan
            </tr>
            @endforeach
         </tbody>
      </table>      
      {{ $roles->appends($_GET)->links() }}   
   </div>
</div>
<!--end::Card-->

<!-- update Modal -->
<div class="modal fade" id="updateRoleItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Role Updatew</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <form action="{{ route('updateRoleItem') }}" method="POST">
         @csrf
         <input type="hidden" name="role_id" id="roleID">
         <div class="modal-body">
             <div class="card-body2 card-block">
                  <div class="form-group mb-3">
                      <label for="name" class=" form-control-label">Role Name <span class="text-danger">*</span></label>
                      <input type="text" id="update_name" name="name" class="form-control form-control-sm" required>
                       
                  </div>
                 
                  <div class="form-group mb-3">
                      <label for="name" class=" form-control-label">Status<span class="text-danger">*</span></label>
                       <select name="status" class="form-control">
                          <option class="status1" value="1">Active</option>
                          <option class="status2" value="0">Inactive</option>
                       </select>
                  </div>
                
            </div>
         </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Updae</button>
      </div>
   </form>
    </div>
  </div>
</div>
<!-- update Modal -->

<!-- create Modal -->
<div class="modal fade " id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 1100px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <form action="{{ route('storeRole') }}" method="POST">
         @csrf
         <div class="modal-body">
             <div class="card-body2 card-block">
                  <div class="form-group row">
                    <div class="col-md-6">
                        <label for="name" class=" form-control-label">Role Name<span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" placeholder="Role Name" class="form-control form-control-sm" required>
                    </div>
                  </div>
                  <hr>
                  @can('assign_permission')
                  <div class="card-body mb-3">    
                     <div class="row grid" id="updateAjaxPermissons">
                         @foreach($parentPermissions as $parentPermission )
                            <div class="col-lg-3 col-md-3 mb-5 grid-item">
                                  <div class="card-bodys cardbody">
                                     <div class="btn btn-secondary mb-3 btn-sm" style="font-size: 15px; width: 100%">
                                           {{ $parentPermission->name }}
                                      </div>
                                     <div class="listPermission">
                                        <div>
                                           @foreach($parentPermission->permissions as $permission)
                                           <div>
                                              <input type="checkbox" name="permissionId[]" value="{{$permission->id}}" class="mr-1"> 
                                               <span style="font-size: 14px">{{$permission->name}}</span>
                                           </div>
                                           @endforeach
                                        </div>
                                     </div>
                                  </div>
                            </div>
                         @endforeach 
                     </div>
                 </div>
                  @endcan
            </div>
         </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
   </form>
    </div>
  </div>
</div>

@endsection


<!-- {{-- Scripts Section Related Page--}} -->
@section('vendor-script')
 
<script type="text/javascript">
   $(document).ready(function() {
       $('#example').DataTable();
   });
</script>

<script>
   
   function updateRoleModal(id, name, status){
       $('#updateRoleItem').modal('show');

       $('#roleID').val(id);
       $('#update_name').val(name);
  
       var checkstatus = status;
       if(checkstatus == '0'){
            $('.status2').attr('selected','selected');
       }else{
          $('.status1').attr('selected','selected');
       }

   }
</script>
@endsection


