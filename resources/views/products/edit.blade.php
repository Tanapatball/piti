<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขสินค้า</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Product ID -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">รหัสสินค้า</label>
                <input type="text" name="product_id" value="{{ old('product_id', $product->product_id) }}" class="border rounded w-full p-2">
                @error('product_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Product Name -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">ชื่อสินค้า</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="border rounded w-full p-2">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">หมวดสินค้า</label>
                <select name="category_id" class="border rounded w-full p-2 mt-1">
                    <option value="">-- เลือกหมวดสินค้า --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Stock Min -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Stock Min</label>
                <input type="number" name="stock_min" value="{{ old('stock_min', $product->stock_min) }}" class="border rounded w-full p-2">
                @error('stock_min')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Stock Max -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Stock Max</label>
                <input type="number" name="stock_max" value="{{ old('stock_max', $product->stock_max) }}" class="border rounded w-full p-2">
                @error('stock_max')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Current Stock -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">จำนวนปัจจุบัน</label>
                <input type="number" name="current_stock" value="{{ old('current_stock', $product->current_stock) }}" class="border rounded w-full p-2">
                @error('current_stock')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Size -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">ขนาด (Size)</label>
                <input type="text" name="size" value="{{ old('size', $product->size) }}" class="border rounded w-full p-2">
                @error('size')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pack -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Pack</label>
                <input type="text" name="pack" value="{{ old('pack', $product->pack) }}" class="border rounded w-full p-2">
                @error('pack')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Weight per KG -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">น้ำหนักต่อกิโล (kg)</label>
                <input type="number" step="0.01" name="weight_per_kg" value="{{ old('weight_per_kg', $product->weight_per_kg) }}" class="border rounded w-full p-2">
                @error('weight_per_kg')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Weight total -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">น้ำหนักรวม (kg)</label>
                <input type="number" step="0.01" name="weight_total" value="{{ old('weight_total', $product->weight_total) }}" class="border rounded w-full p-2">
                @error('weight_total')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                บันทึกการแก้ไข
            </button>
        </form>
    </div>
</x-app-layout>
