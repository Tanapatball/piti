<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายงานสรุปสินค้า (รับ/เบิก)</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">

            {{-- Filter Form --}}
            <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm mb-1">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" value="{{ $start }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm mb-1">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" value="{{ $end }}" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm mb-1">สินค้า</label>
                    <select name="product_id" class="w-full border rounded px-3 py-2">
                        <option value="">-- ทั้งหมด --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->product_id }}" {{ $productId == $product->product_id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->product_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        ค้นหา
                    </button>
                    <a href="{{ route('reports.summary.product') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                        ล้าง
                    </a>
                </div>
            </form>

            {{-- Export Buttons --}}
            <div class="mb-4 flex gap-2">
                <a href="{{ request()->fullUrlWithQuery(['export_pdf' => 1]) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Export PDF
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export_excel' => 1]) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Export Excel
                </a>
            </div>

            {{-- Summary Table --}}
            <div class="overflow-x-auto">
                <table class="w-full border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1" rowspan="2">ลำดับ</th>
                            <th class="border px-2 py-1" rowspan="2">รหัสสินค้า</th>
                            <th class="border px-2 py-1" rowspan="2">ชื่อสินค้า</th>
                            <th class="border px-2 py-1" rowspan="2">หมวด</th>
                            <th class="border px-2 py-1 bg-green-100" colspan="3">รับเข้า</th>
                            <th class="border px-2 py-1 bg-red-100" colspan="2">เบิกออก</th>
                            <th class="border px-2 py-1 bg-blue-100" rowspan="2">คงเหลือ</th>
                        </tr>
                        <tr>
                            <th class="border px-2 py-1 bg-green-100">Kg/ctn</th>
                            <th class="border px-2 py-1 bg-green-100">Kg/Inner</th>
                            <th class="border px-2 py-1 bg-green-100">น้ำหนัก</th>
                            <th class="border px-2 py-1 bg-red-100">Kg/ctn</th>
                            <th class="border px-2 py-1 bg-red-100">Kg/Inner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summary as $idx => $item)
                            <tr>
                                <td class="border px-2 py-1 text-center">{{ $loop->iteration }}</td>
                                <td class="border px-2 py-1">{{ $item['product_id'] }}</td>
                                <td class="border px-2 py-1">{{ $item['name'] }}</td>
                                <td class="border px-2 py-1">{{ $item['category'] }}</td>
                                <td class="border px-2 py-1 text-center bg-green-50">{{ number_format($item['received_full']) }}</td>
                                <td class="border px-2 py-1 text-center bg-green-50">{{ number_format($item['received_fraction']) }}</td>
                                <td class="border px-2 py-1 text-center bg-green-50">{{ number_format($item['received_weight'], 2) }}</td>
                                <td class="border px-2 py-1 text-center bg-red-50">{{ number_format($item['issued_full']) }}</td>
                                <td class="border px-2 py-1 text-center bg-red-50">{{ number_format($item['issued_fraction']) }}</td>
                                <td class="border px-2 py-1 text-center bg-blue-50 font-semibold">{{ number_format($item['current_stock']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="border px-2 py-4 text-center text-gray-500">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($summary) > 0)
                    <tfoot class="bg-gray-100 font-semibold">
                        <tr>
                            <td colspan="4" class="border px-2 py-1 text-center">รวม</td>
                            <td class="border px-2 py-1 text-center bg-green-100">{{ number_format($summary->sum('received_full')) }}</td>
                            <td class="border px-2 py-1 text-center bg-green-100">{{ number_format($summary->sum('received_fraction')) }}</td>
                            <td class="border px-2 py-1 text-center bg-green-100">{{ number_format($summary->sum('received_weight'), 2) }}</td>
                            <td class="border px-2 py-1 text-center bg-red-100">{{ number_format($summary->sum('issued_full')) }}</td>
                            <td class="border px-2 py-1 text-center bg-red-100">{{ number_format($summary->sum('issued_fraction')) }}</td>
                            <td class="border px-2 py-1 text-center bg-blue-100">{{ number_format($summary->sum('current_stock')) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
