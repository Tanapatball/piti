<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายงานสต็อกการ์ดแยกตาม Code</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" action="{{ route('reports.stock-card-by-code') }}" class="flex flex-wrap gap-4 mb-6 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Code</label>
                    <input type="text" name="item_code" value="{{ request('item_code') }}" placeholder="กรอก Item Code" class="border border-gray-300 rounded px-3 py-2 w-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="border border-gray-300 rounded px-3 py-2">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-semibold">แสดงรายงาน</button>
                    <button type="submit" name="export_pdf" value="1" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded font-semibold">Export PDF</button>
                    <button type="submit" name="export_excel" value="1" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold">Export Excel</button>
                </div>
            </form>

            @if(isset($items) && $items->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2">ลำดับ</th>
                            <th class="border border-gray-300 px-4 py-2">วันที่</th>
                            <th class="border border-gray-300 px-4 py-2">เลขที่เอกสาร</th>
                            <th class="border border-gray-300 px-4 py-2">รหัสสินค้า</th>
                            <th class="border border-gray-300 px-4 py-2">ชื่อสินค้า</th>
                            <th class="border border-gray-300 px-4 py-2">Code</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/ctn</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/Inner</th>
                            <th class="border border-gray-300 px-4 py-2">น้ำหนักสุทธิ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->transaction->trans_date ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->transaction->trans_id ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->product_id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $item->product->name ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->item_code }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->full_qty }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->fraction_qty }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->net_weight }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @elseif(request('item_code'))
            <p class="text-gray-500 mt-4">ไม่มีข้อมูล</p>
            @endif
        </div>
    </div>
</x-app-layout>
