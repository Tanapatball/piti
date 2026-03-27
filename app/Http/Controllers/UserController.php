<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // แสดงรายการผู้ใช้
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('fname', 'like', "%{$s}%")
                  ->orWhere('lname', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        $users = $query->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    // แสดงฟอร์มเพิ่มผู้ใช้
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // บันทึกผู้ใช้ใหม่
    public function store(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:100',
            'lname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|confirmed|min:6',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'เพิ่มผู้ใช้เรียบร้อยแล้ว');
    }

    // แสดงฟอร์มแก้ไขผู้ใช้
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // อัปเดตผู้ใช้
    public function update(Request $request, User $user)
    {
        $request->validate([
            'fname' => 'required|string|max:100',
            'lname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->user_id.',user_id',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,role_id',
        ]);

        $user->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'อัปเดตผู้ใช้เรียบร้อยแล้ว');
    }

    // ลบผู้ใช้
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }
}
