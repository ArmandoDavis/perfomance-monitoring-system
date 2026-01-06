<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
     public function index()
    {
       if (Auth::user()->hasRole('admin')) {
          return view('admin.dashboard');
        }
        return view('dashboard');
       
    }
}
