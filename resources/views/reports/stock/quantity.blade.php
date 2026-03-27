<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายงานสินค้าคงเหลือปริมาณ</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" action="{{ route('reports.stock-quantity') }}" class="flex flex-wrap gap-4 mb-6 items-end">
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-semibold">แสดงรายงาน</button>
                    <button type="submit" name="export_pdf" value="1" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded font-semibold">Export PDF</button>
                    <button type="submit" name="export_excel" value="1" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold">Export Excel</button>
                </div>
            </form>

            @if($products && $products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2">ลำดับ</th>
                            <th class="border border-gray-300 px-4 py-2">รหัสสินค้า</th>
                            <th class="border border-gray-300 px-4 py-2">ชื่อสินค้า</th>
                            <th class="border border-gray-300 px-4 py-2">หมวด</th>
                            <th class="border border-gray-300 px-4 py-2">คงเหลือ</th>
                            <th class="border border-gray-300 px-4 py-2">สต็อกต่ำสุด</th>
                            <th class="border border-gray-300 px-4 py-2">สต็อกสูงสุด</th>
                            <th class="border border-gray-300 px-4 py-2">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $product->product_id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $product->name }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $product->category->category_name ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $product->current_stock }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $product->stock_min }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $product->stock_max }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                @if($product->current_stock < $product->stock_min)
                                    <span class="text-red-600 font-semibold">ต่ำกว่า Min</span>
                                @elseif($product->current_stock > $product->stock_max)
                                    <span class="text-orange-500 font-semibold">เกิน Max</span>
                                @else
                                    <span class="text-green-600 font-semibold">ปกติ</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500 mt-4">ไม่มีข้อมูล</p>
            @endif
        </div>
    </div>
</x-app-layout>
