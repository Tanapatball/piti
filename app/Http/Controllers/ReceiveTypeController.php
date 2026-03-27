<?php

namespace App\Http\Controllers;

use App\Models\ReceiveType;
use Illuminate\Http\Request;

class ReceiveTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = ReceiveType::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('receive_type_id', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%");
            });
        }

        $receiveTypes = $query->get();
        return view('receive_types.index', compact('receiveTypes'));
    }

    public function create()
    {
        return view('receive_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'receive_type_id' => 'nullable|integer|min:1|unique:receive_types,receive_type_id',
            'name' => 'required|string|max:100|unique:receive_types,name',
        ], [
            'receive_type_id.unique' => 'รหัสประเภทการรับเข้านี้มีอยู่แล้ว',
            'name.unique' => 'ชื่อประเภทการรับเข้านี้มีอยู่แล้ว',
        ]);

        $id = $request->receive_type_id ?: (ReceiveType::max('receive_type_id') ?? 0) + 1;

        ReceiveType::create([
            'receive_type_id' => $id,
            'name' => $request->name,
        ]);

        return redirect()->route('receive-types.index')
                         ->with('success', 'เพิ่มประเภทการรับเข้าเรียบร้อยแล้ว');
    }

    public function edit(ReceiveType $receiveType)
    {
        return view('receive_types.edit', compact('receiveType'));
    }

    public function update(Request $request, ReceiveType $receiveType)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:receive_types,name,' . $receiveType->receive_type_id . ',receive_type_id',
        ], [
            'name.unique' => 'ชื่อประเภทการรับเข้านี้มีอยู่แล้ว',
        ]);

        $receiveType->update([
            'name' => $request->name,
        ]);

        return redirect()->route('receive-types.index')
                         ->with('success', 'อัปเดตประเภทการรับเข้าเรียบร้อยแล้ว');
    }

    public function destroy(ReceiveType $receiveType)
    {
        $receiveType->delete();

        return redirect()->route('receive-types.index')
                         ->with('success', 'ลบประเภทการรับเข้าเรียบร้อยแล้ว');
    }
}
