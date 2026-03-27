<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * แสดงรายการสินค้า
     */
    public function index(Request $request)
    {
        $query = Product::with(['category'])
            ->withSum('transactionItems as total_ctn_in', 'full_qty')
            ->withSum('transactionItems as total_inner_in', 'fraction_qty')
            ->withSum('stockOuts as total_ctn_out', 'quantity')
            ->withSum('stockOuts as total_inner_out', 'fraction_qty');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('product_id', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->get()->map(function ($p) {
            $p->ctn_remaining = ($p->total_ctn_in ?? 0) - ($p->total_ctn_out ?? 0);
            $p->inner_remaining = ($p->total_inner_in ?? 0) - ($p->total_inner_out ?? 0);
            return $p;
        });

        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * ฟอร์มเพิ่มสินค้าใหม่
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * บันทึกสินค้าใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|string|max:20|unique:products,product_id',
            'name'           => 'required|string|max:150',
            'category_id'    => 'required|exists:categories,category_id',
            'stock_min'      => 'nullable|integer',
            'stock_max'      => 'nullable|integer',
            'current_stock'  => 'nullable|integer',
            'size'           => 'nullable|string|max:50',
            'pack'           => 'nullable|string|max:50',
            'weight_per_kg'  => 'nullable|numeric',
            'weight_total'   => 'nullable|numeric',
        ]);

        Product::create([
            'product_id'     => $request->product_id,
            'name'           => $request->name,
            'category_id'    => $request->category_id,
            'stock_min'      => $request->stock_min,
            'stock_max'      => $request->stock_max,
            'current_stock'  => $request->current_stock ?? 0,
            'size'           => $request->size,
            'pack'           => $request->pack,
            'weight_per_kg'  => $request->weight_per_kg ?? 0,
            'weight_total'   => $request->weight_total ?? 0,
        ]);

        return redirect()->route('products.index')->with('success', 'เพิ่มสินค้าสำเร็จ');
    }

    /**
     * ฟอร์มแก้ไขสินค้า
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * อัปเดตสินค้า
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_id'     => 'required|string|max:20|unique:products,product_id,' . $product->product_id . ',product_id',
            'name'           => 'required|string|max:150',
            'category_id'    => 'required|exists:categories,category_id',
            'stock_min'      => 'nullable|integer',
            'stock_max'      => 'nullable|integer',
            'current_stock'  => 'nullable|integer',
            'size'           => 'nullable|string|max:50',
            'pack'           => 'nullable|string|max:50',
            'weight_per_kg'  => 'nullable|numeric',
            'weight_total'   => 'nullable|numeric',
        ]);

        $product->update([
            'product_id'     => $request->product_id,
            'name'           => $request->name,
            'category_id'    => $request->category_id,
            'stock_min'      => $request->stock_min,
            'stock_max'      => $request->stock_max,
            'current_stock'  => $request->current_stock,
            'size'           => $request->size,
            'pack'           => $request->pack,
            'weight_per_kg'  => $request->weight_per_kg ?? 0,
            'weight_total'   => $request->weight_total ?? 0,
        ]);

        return redirect()->route('products.index')->with('success', 'อัปเดตสินค้าสำเร็จ');
    }

    /**
     * ลบสินค้า
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'ลบสินค้าสำเร็จ');
    }

    public function getProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'size' => $product->size,
            'pack' => $product->pack,
            'weight_per_kg' => $product->weight_per_kg,
        ]);
    }

}
