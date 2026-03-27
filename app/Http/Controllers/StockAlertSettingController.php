<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StockAlertSettingController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('stock_alert_settings.index', compact('users'));
    }

    public function update(Request $request)
    {
        // Reset all users
        User::query()->update(['receive_stock_alert' => false]);

        // Set selected users
        if ($request->has('user_ids')) {
            User::whereIn('user_id', $request->user_ids)->update(['receive_stock_alert' => true]);
        }

        return back()->with('success', 'บันทึกการตั้งค่าผู้รับแจ้งเตือนเรียบร้อย');
    }
}
