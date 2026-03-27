<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * แสดงรายการหมวดสินค้า
     */
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('category_id', 'like', "%{$s}%")
                  ->orWhere('category_name', 'like', "%{$s}%");
            });
        }

        $categories = $query->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * แสดงฟอร์มเพิ่มหมวดสินค้าใหม่
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * บันทึกข้อมูลหมวดสินค้าใหม่ลง DB
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|string|max:20|unique:categories,category_id',
            'category_name' => 'required|string|max:100',
        ]);

        Category::create([
            'category_id'   => $request->category_id,
            'category_name' => $request->category_name,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'เพิ่มหมวดสินค้าเรียบร้อยแล้ว');
    }

    /**
     * แสดงฟอร์มแก้ไขหมวดสินค้า
     */
    public function edit(string $category)
    {
        $category = Category::where('category_id', urldecode($category))->firstOrFail();
        return view('categories.edit', compact('category'));
    }

    /**
     * อัปเดตข้อมูลหมวดสินค้า
     */
    public function update(Request $request, string $category)
    {
        $category = Category::where('category_id', urldecode($category))->firstOrFail();

        $request->validate([
            'category_name' => 'required|string|max:100',
        ]);

        $category->update([
            'category_name' => $request->category_name,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'อัปเดตหมวดสินค้าเรียบร้อยแล้ว');
    }

    /**
     * ลบหมวดสินค้า
     */
    public function destroy(string $category)
    {
        $category = Category::where('category_id', urldecode($category))->firstOrFail();
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'ลบหมวดสินค้าเรียบร้อยแล้ว');
    }
}
