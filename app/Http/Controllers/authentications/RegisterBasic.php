<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class RegisterBasic extends Controller
{
  public function index()
  {
    $data['users'] = User::where('id','!=',1)->paginate(200);
  	$data['roles'] = Role::where('id','!=',1)->paginate(25);
    return view('content.authentications.auth-register-basic',$data);
  }
}
