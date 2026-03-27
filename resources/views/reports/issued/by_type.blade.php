<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">ใบเบิกสินค้าตามประเภท</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" action="{{ route('reports.issued.by-type') }}" class="flex flex-wrap gap-4 mb-6 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ประเภท</label>
                    <select name="receive_type_id" class="border-gray-300 rounded-md shadow-sm">
                        <option value="">-- เลือกประเภท --</option>
                        @foreach($receiveTypes as $type)
                        <option value="{{ $type->receive_type_id }}" {{ request('receive_type_id') == $type->receive_type_id ? 'selected' : '' }}>
                            {{ $type->name }}
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

            @if(isset($stockOuts) && $stockOuts->count())
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">วันที่เบิก</th>
                            <th class="border border-gray-300 px-4 py-2">เลขที่อ้างอิง</th>
                            <th class="border border-gray-300 px-4 py-2">ประเภท</th>
                            <th class="border border-gray-300 px-4 py-2">สินค้า</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/ctn</th>
                            <th class="border border-gray-300 px-4 py-2">Kg/Inner</th>
                            <th class="border border-gray-300 px-4 py-2">ผู้เบิก</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockOuts as $stockOut)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $stockOut->issued_date }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $stockOut->trans_id }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $stockOut->transaction->receiveType->name ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $stockOut->product->name ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $stockOut->quantity }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $stockOut->fraction_qty }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $stockOut->issued_to }}</td>
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
