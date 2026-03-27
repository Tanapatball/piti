<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_name' => 'required|string|max:255',
        ]);

        Warehouse::create($request->all());
        return redirect()->route('warehouses.index')->with('success', 'เพิ่มคลังสินค้าสำเร็จ');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'warehouse_name' => 'required|string|max:255',
        ]);

        $warehouse->update($request->all());
        return redirect()->route('warehouses.index')->with('success', 'แก้ไขคลังสินค้าสำเร็จ');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'ลบคลังสินค้าสำเร็จ');
    }
}
