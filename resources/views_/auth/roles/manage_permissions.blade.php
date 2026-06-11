@extends('layouts/contentNavbarLayout')

@section('title', 'Modify Permission Allocation')
@section('content')

<!--begin::Card-->
<div class="card">

   @include('alerts.alert')
   <form action="{{ route('storeUpdateUserPermissionAll') }}" method="POST">
      @csrf

   <input type="hidden" name="role_id" value="{{ $role->id }}">
    
   <div class="card-header flex-wrap py-5">
      <div class="card-title mb-0 mt-2" >
         <h3 style="display: contents">(Role: <strong>{{ $role->name}}</strong>)</h3>
      </div>
      <div class="card-toolbar">        
      </div>
   </div>
   <div class="card-body">    
   <div class="row grid">
   @foreach($parentPermissions as $parentPermission )
      <div class="col-lg-4 col-md-4 mb-5 grid-item">
            <div class="card-bodys cardbody">
               <div class="btn btn-secondary mb-3 btn-sm" style="font-size: 15px; width: 100%">
                     {{ $parentPermission->name }}
                </div>
               <div class="listPermission">
                  <div>
                      
                     @foreach($parentPermission->permissions as $permission)
       
                     <div>
                        <input type="checkbox" name="permissionId[]" value="{{$permission->id}}" class="mr-1" @if(isset($permission->roleHasPermission($role->id)->permission_id) && $permission->roleHasPermission($role->id)->permission_id == $permission->id) checked @endif> 
                         <span>{{$permission->name}}</span>
                     </div>
                     @endforeach
                  </div>
               </div>
            </div>
      </div>
   @endforeach 
  
         @can('edit_assign_permission')
            <button type="submit" class="btn btn-primary font-weight-bolder float-right" onclick="return confirm('Are you sure you want to modify permission assignments?')">
               <i class="far fa-check-circle"></i>Modify permission allocation
            </button>  
         @endcan
 
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


