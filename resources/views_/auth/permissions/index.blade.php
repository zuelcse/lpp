@extends('layouts/contentNavbarLayout')

@section('title', 'Permission Management')
@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Permission Management </span> 
  @can('add_permission')
    <span class="text-muted fw-light pull-right">
      
      <button type="button" data-bs-toggle="modal" data-bs-target="#parentPermissionNameModal" class="btn btn-sm btn-success mr-3 font-weight-bolder">
            <box-icon name='plus' color="white"></box-icon> Parent Permission Create
         </button>  

         <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" class="btn btn-sm btn-primary font-weight-bolder">
            <box-icon name='plus' color="white"></box-icon> Permission Create
         </button> 
    </span>
  @endcan
</h4>
  <!--begin::Card-->
<div class="card">
   <div class="card-body">
      @include('alerts.alert')
      <table class="table table-hover table-bordered mb-6 font-size-h6">
         <thead class="thead-light ">
            <tr>
               <th width="30">Sl. No</th>
               <th>Permission Name</th>
               <th class="text-center">Parent Permission Name</th>
               <th class="text-center" scope="col">Status</th>
               <th class="text-center">Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $i = (($permissions->currentPage() -1) * $permissions->perPage() + 1);
            ?>
            @foreach ($permissions as $permission)
 
            
            <tr>
               <th scope="row" class="tg-bn">{{ ($i++) }}</th>
               <td>{{ $permission->name }}</td>
               
               <td class="text-center">{{ $permission->parent? $permission->parent->name: '' }}</td>
               <td class="text-center">
                  @if($permission->status == 1)
                     <span class="btn btn-success btn-xs">Active</span>
                  @else
                     <span class="btn btn-danger btn-xs">Inactive</span>
                  @endif
               </td>
               
               <td class="text-center">
                  @can('edit_permission')
                  <button type="button" onclick="updatePermissionModal('{{$permission->id}}','{{$permission->parent->id}}', '{{$permission->name}}', '{{$permission->status}}')" class="btn btn-secondary btn-shadow btn-sm font-weight-bold pt-1 pb-1">Update</button>
                  @endcan
                  @can('delete_permission')
                  <a href="{{ route('permissionItemDelete', $permission->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-shadow btn-sm font-weight-bold pt-1 pb-1">Delete</a>
                  @endcan
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
              <h5 class="modal-title" id="exampleModalLongTitle">Update Permission</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('updatePermission') }}" method="POST">
               @csrf
               <input type="hidden" name="permission_id" id="roleID">
               <div class="modal-body">
                   <div class="card-body card-block">

                <div class="form-group mb-3">
                    <label for="name" class=" form-control-label">Parent Permission<span class="text-danger">*</span></label>
                     <select name="parent_permission_id" id="parentPermissionId" class="form-control" required>
                        <option value="">--Select Parent Permission--</option>
                        @foreach($parentPermissions as $parent)
                        <option value="{{$parent->id}}">{{$parent->name}}</option>
                        @endforeach
                     </select>
                </div>
                  <div class="form-group mb-3">
                      <label for="name" class=" form-control-label">Permission Name<span class="text-danger">*</span></label>
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
              <button type="submit" class="btn btn-primary">Update</button>
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
        <h5 class="modal-title" id="exampleModalLongTitle">Create Prmission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('storePermission') }}" method="POST">
         @csrf
         <div class="modal-body">
             <div class="card-body card-block">

                <div class="form-group mb-3">
                    <label for="name" class=" form-control-label">Parent Permission Name<span class="text-danger">*</span></label>
                     <select name="parent_permission_id" class="form-control" required>
                        <option value="">--Select Parent Permission--</option>
                        @foreach($parentPermissions as $parent)
                        <option id="parent_{{$parent->id}}" value="{{$parent->id}}">{{$parent->name}}</option>
                        @endforeach
                     </select>
                </div>

                  <div class="form-group mb-3">
                      <label for="name" class=" form-control-label">Permission Name<span class="text-danger">*</span></label>
                      <input type="text" id="name" name="name" placeholder="" class="form-control form-control-sm" required>
                  </div>

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

 
<!-- parent permission name Modal -->
<div class="modal fade" id="parentPermissionNameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create Parent Permission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </button>
      </div>
      <form action="{{ route('storePatentPermission') }}" method="POST">
         @csrf
         <div class="modal-body">
             <div class="card-body card-block">
                  <div class="form-group">
                      <label for="name" class=" form-control-label">Parent Name <span class="text-danger">*</span></label>
                      <input type="text" id="name" name="name" placeholder="" class="form-control form-control-sm" required>
                     
                  </div>
                
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

<!-- {{-- Includable CSS Related Page --}} -->
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection     

<!-- {{-- Scripts Section Related Page--}} -->
@section('vendor-script')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
<!--end::Page Scripts-->


<script>
   
   function updatePermissionModal(id, parent_name, name, status){

       $('#updateRoleItem').modal('show');

       $('#roleID').val(id);
       $('#update_name').val(name);
       // document.getElementById("parent_"+parent_name).selected = "true";
       $('#parentPermissionId').val(parent_name);

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


