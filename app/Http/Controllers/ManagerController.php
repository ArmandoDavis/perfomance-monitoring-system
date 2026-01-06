<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ManagerController extends Controller
{
    //
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
           return view('manager.dashboard');
        }


        return view('dashboard');
    }
}
