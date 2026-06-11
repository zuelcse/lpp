 @foreach($parentPermissions as $parentPermission )
      <div class="col-lg-4 col-md-4 mb-5 grid-item">
            <div class="card-bodys cardbody">
               <div class="cardheader" style="background: #839289;">
                     {{ $parentPermission->name }}
                </div>
               <div class="listPermission">
                  <ul>
                     @foreach($parentPermission->permissions as $permission)
                     <li>
                        <input type="checkbox" name="permissionId[]" value="{{$permission->id}}" class="mr-1" 
@if($permission->roleHasPermission($role_id) != null && $permission->roleHasPermission($role_id)->permission_id == $permission->id) checked @endif disabled> 
                         <span>{{$permission->name}}</span>
                     </li>
                     @endforeach
                  </ul>
               </div>
            </div>
      </div>
   @endforeach 