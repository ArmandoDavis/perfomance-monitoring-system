<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('flash_success', __('All notifications marked as read.'));
    }

    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['action_url'] ?? route('dashboard'));
    }

    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('pages.admin.notifications.index', compact('notifications'));
    }

    public function hodNotifications()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('pages.hod.notifications.index', compact('notifications'));
    }

    public function staffNotifications()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('pages.frontend.notifications.index', compact('notifications'));
    }
}
