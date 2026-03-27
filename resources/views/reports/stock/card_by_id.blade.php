<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายงานสต็อกการ์ด ตามรหัส</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" action="{{ route('reports.stock-card-by-id') }}" class="flex flex-wrap gap-4 mb-6 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สินค้า</label>
                    <select name="product_id" class="border border-gray-300 rounded px-3 py-2 w-72">
                        <option value="">-- เลือกสินค้า --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->product_id }}" {{ request('product_id') == $p->product_id ? 'selected' : '' }}>
                            {{ $p->product_id }} - {{ $p->name }}
                        </option>
                        @endforeach
                    </select>
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

            @if(isset($product) && $product)
            <div class="mb-6 p-4 bg-gray-50 rounded border">
                <h3 class="font-bold text-lg mb-2">ข้อมูลสินค้า</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                    <div><span class="font-semibold">รหัสสินค้า:</span> {{ $product->product_id }}</div>
                    <div><span class="font-semibold">ชื่อสินค้า:</span> {{ $product->name }}</div>
                    <div><span class="font-semibold">หมวด:</span> {{ $product->category->category_name ?? '-' }}</div>
                    <div><span class="font-semibold">Size:</span> {{ $product->size }}</div>
                    <div><span class="font-semibold">Pack:</span> {{ $product->pack }}</div>
                    <div><span class="font-semibold">คงเหลือ:</span> {{ $product->current_stock }}</div>
                </div>
            </div>

            {{-- Section 1: รายการรับเข้า --}}
            <h3 class="text-lg font-bold mt-4 mb-2">รายการรับเข้า</h3>
            @if($receivedItems && $receivedItems->count() > 0)
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2">ลำดับ</th>
                            <th class="border border-gray-300 px-4 py-2">วันที่</th>
                            <th class="border border-gray-300 px-4 py-2">เลขที่เอกสาร</th>
                            <th class="border border-gray-300 px-4 py-2">Code</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/ctn</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/Inner</th>
                            <th class="border border-gray-300 px-4 py-2">น้ำหนักสุทธิ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receivedItems as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->transaction->trans_date ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->transaction->trans_id ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->item_code }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->full_qty }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->fraction_qty }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->net_weight }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500 mb-6">ไม่มีรายการรับเข้า</p>
            @endif

            {{-- Section 2: รายการเบิกออก --}}
            <h3 class="text-lg font-bold mt-4 mb-2">รายการเบิกออก</h3>
            @if($issuedItems && $issuedItems->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2">ลำดับ</th>
                            <th class="border border-gray-300 px-4 py-2">วันที่</th>
                            <th class="border border-gray-300 px-4 py-2">เลขที่อ้างอิง</th>
                            <th class="border border-gray-300 px-4 py-2">จำนวน</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/Inner</th>
                            <th class="border border-gray-300 px-4 py-2">ผู้เบิก</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($issuedItems as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->issued_date }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->trans_id }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->quantity }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->fraction_qty }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->issued_to }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500">ไม่มีรายการเบิกออก</p>
            @endif

            @elseif(request('product_id'))
            <p class="text-gray-500 mt-4">ไม่มีข้อมูล</p>
            @endif
        </div>
    </div>
</x-app-layout>
