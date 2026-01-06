<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    //
      public function index()
    {
       if (Auth::user()->hasRole('admin')) {
           return view('staff.dashboard');
        }
        return view('dashboard');
    }
}
