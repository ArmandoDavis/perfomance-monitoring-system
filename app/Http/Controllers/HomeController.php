<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $user = user();
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->isAdmin()) {
            return redirect()->route('admin_panel.dashboard');
        }
        return redirect()->route('frontend.dashboard');
    }
}
