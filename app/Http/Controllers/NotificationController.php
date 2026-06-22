<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function list(Request $request)
    {
        return view('notification.list');
    }

    public function read(Request $request, string $id)
    {
        $notification = $request->user()->notifications->where('id', $id)->first();

        if (!$notification) {
            abort(404);
        }

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        if (isset($notification->data['route'])) {
            return redirect()->route(
                $notification->data['route'],
                $notification->data['routeParam'] ?? []
            );
        }

        return back();
    }
}
