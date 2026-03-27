<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แสดงรายละเอียดสินค้าทั้งหมด</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-wrap gap-4 mb-6 items-end">
                <a href="{{ route('reports.products.all', ['export_pdf' => 1]) }}" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded font-semibold">Export PDF</a>
            </div>

            @if($products->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-3 py-2">ลำดับ</th>
                                <th class="border px-3 py-2">รหัสสินค้า</th>
                                <th class="border px-3 py-2">ชื่อสินค้า</th>
                                <th class="border px-3 py-2">หมวด</th>
                                <th class="border px-3 py-2">Size</th>
                                <th class="border px-3 py-2">Pack</th>
                                <th class="border px-3 py-2">น้ำหนัก/กก.</th>
                                <th class="border px-3 py-2">น้ำหนักรวม</th>
                                <th class="border px-3 py-2">สต็อกต่ำสุด</th>
                                <th class="border px-3 py-2">สต็อกสูงสุด</th>
                                <th class="border px-3 py-2">คงเหลือ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $p)
                                <tr>
                                    <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $p->product_id }}</td>
                                    <td class="border px-3 py-2">{{ $p->name }}</td>
                                    <td class="border px-3 py-2">{{ $p->category->category_name ?? '-' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $p->size ?? '-' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $p->pack ?? '-' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $p->weight_per_kg ?? '-' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $p->weight_total ?? '-' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $p->stock_min }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $p->stock_max }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $p->current_stock }}</td>
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
