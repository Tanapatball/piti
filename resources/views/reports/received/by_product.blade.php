<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายงานตรวจสอบใบรับ/รหัสสินค้า</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" action="{{ route('reports.received.by-product') }}" class="flex flex-wrap gap-4 mb-6 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เลขที่เอกสาร</label>
                    <input type="text" name="trans_id" value="{{ request('trans_id') }}" placeholder="เลขที่เอกสาร" class="border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สินค้า</label>
                    <select name="product_id" class="border-gray-300 rounded-md shadow-sm">
                        <option value="">-- เลือกสินค้า --</option>
                        @foreach($products as $product)
                        <option value="{{ $product->product_id }}" {{ request('product_id') == $product->product_id ? 'selected' : '' }}>
                            {{ $product->product_id }} - {{ $product->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-semibold">แสดงรายงาน</button>
                    <button type="submit" name="export_pdf" value="1" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded font-semibold">Export PDF</button>
                    <button type="submit" name="export_excel" value="1" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold">Export Excel</button>
                </div>
            </form>

            @if(isset($transactions) && $transactions->count())
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">วันที่</th>
                            <th class="border border-gray-300 px-4 py-2">เลขที่เอกสาร</th>
                            <th class="border border-gray-300 px-4 py-2">เอกสารอ้างอิง</th>
                            <th class="border border-gray-300 px-4 py-2">เลขอ้างอิง</th>
                            <th class="border border-gray-300 px-4 py-2">ประเภทการรับ</th>
                            <th class="border border-gray-300 px-4 py-2">สินค้า</th>
                            <th class="border border-gray-300 px-4 py-2">Code</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/ctn</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/Inner</th>
                            <th class="border border-gray-300 px-4 py-2">หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            @foreach($transaction->items as $item)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $transaction->trans_date }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $transaction->trans_id }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $transaction->reference_doc }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $transaction->reference_no }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $transaction->receiveType->name ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->product->name ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->item_code }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->full_qty }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->fraction_qty }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $transaction->note }}</td>
                            </tr>
                            @endforeach
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
