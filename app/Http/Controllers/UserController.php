<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index()
    {
        if(Auth::check()) {
            if(Auth::user()->role == 'staff') {
                return view('staff.dashboard');
            } else if(
                Auth::user()->role == 'admin') {
                return view('admin.dashboard');
            }
            else if(
                Auth::user()->role == 'manager') {
                return view('manager.dashboard');
            }
        } 
          else {
        return redirect('/');
          }
    }

}
