<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายงาน - หมวดสินค้า</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" action="{{ route('reports.main.categories') }}" class="flex flex-wrap gap-4 mb-6 items-end">
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-semibold">แสดงรายงาน</button>
                    <button type="submit" name="export_pdf" value="1" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded font-semibold">Export PDF</button>
                    <button type="submit" name="export_excel" value="1" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold">Export Excel</button>
                </div>
            </form>

            @if($categories->count())
                <div class="overflow-x-auto"><table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2">ลำดับ</th>
                            <th class="border px-3 py-2">รหัสหมวด</th>
                            <th class="border px-3 py-2">ชื่อหมวด</th>
                            <th class="border px-3 py-2">จำนวนสินค้า</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $cat)
                            <tr>
                                <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                                <td class="border px-3 py-2 text-center">{{ $cat->category_id }}</td>
                                <td class="border px-3 py-2">{{ $cat->category_name }}</td>
                                <td class="border px-3 py-2 text-center">{{ $cat->products_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table></div>
            @else
                <p class="text-gray-500 mt-4">ไม่มีข้อมูล</p>
            @endif
        </div>
    </div>
</x-app-layout>
