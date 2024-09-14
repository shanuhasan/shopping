<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sentinel;
use Validator;
use App\User;

class Registercontroller extends Controller
{
    public function index()
    {
    	//echo asset('/');
    	return view('admin/adminregister');
    }
    
    function user_register(Request $request){
    	if($request->email && $request->first_name && $request->last_name && $request->password){
    	    
    	$user = Sentinel::registerAndActivate([
            'email'    => $request->email,
            'password' => $request->password,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        $role=Sentinel::findRoleBySlug('super_admin');
       $res= $role->users()->attach($user);
       
       	return Redirect('admin/register')->with('success','User created successfully!');
       }else{
       	return Redirect('admin/register')->with('error','All Fields are required...');
       }
       
    		
    }	
}
