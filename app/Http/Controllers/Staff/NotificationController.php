<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('staff.notifications.index', compact('notifications'));
    }

    public function read(string $notification)
    {
        $item = auth()->user()->notifications()->whereKey($notification)->firstOrFail();
        $item->markAsRead();
        return redirect($item->data['url'] ?? route('staff.dashboard'));
    }

    public function readAll(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
