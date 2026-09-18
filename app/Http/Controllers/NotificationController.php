<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Ambil 10 notifikasi terbaru dan jumlah yang belum dibaca.
     */
    public function getNotifications()
    {
        $notifications = Notification::latest()->take(10)->get();
        $unreadCount = Notification::where('is_read', false)->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Tandai semua notifikasi yang belum dibaca menjadi sudah dibaca.
     */
    public function markAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        
        return response()->json([
            'status' => 'success'
        ]);
    }
}