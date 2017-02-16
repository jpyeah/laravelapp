<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Users;

class UserController extends Controller
{
    public function index()
    {   
    	return view('admin/user/index')->withUsers(Users::all());
    }
}
