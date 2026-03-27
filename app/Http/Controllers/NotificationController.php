<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * แสดงรายการแจ้งเตือนทั้งหมด
     */
    public function index(): View
    {
        $notifications = Notification::forUser(Auth::id())
            ->with('product')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * ดึงข้อมูลแจ้งเตือนสำหรับ dropdown (AJAX)
     */
    public function dropdown(): JsonResponse
    {
        $notifications = Notification::forUser(Auth::id())
            ->with('product:id,name,code')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $unreadCount = Notification::forUser(Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'icon' => $n->icon,
                    'color' => $n->color,
                    'link' => $n->link,
                    'is_read' => $n->isRead(),
                    'created_at' => $n->created_at->diffForHumans(),
                    'product' => $n->product ? [
                        'id' => $n->product->id,
                        'name' => $n->product->name,
                        'code' => $n->product->code,
                    ] : null,
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * นับจำนวนแจ้งเตือนที่ยังไม่ได้อ่าน (AJAX)
     */
    public function unreadCount(): JsonResponse
    {
        $count = Notification::forUser(Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * อ่านแจ้งเตือน
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * อ่านทั้งหมด
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::forUser(Auth::id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * ลบแจ้งเตือน
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'ลบการแจ้งเตือนสำเร็จ');
    }

    /**
     * ลบแจ้งเตือนที่อ่านแล้วทั้งหมด
     */
    public function destroyRead()
    {
        Notification::forUser(Auth::id())
            ->whereNotNull('read_at')
            ->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'ลบการแจ้งเตือนที่อ่านแล้วสำเร็จ');
    }
}
