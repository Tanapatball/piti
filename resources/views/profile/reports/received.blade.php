<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายงานรับสินค้าเข้าคลัง</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" action="{{ route('reports.received') }}" class="flex flex-wrap gap-4 mb-6 items-end">
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded p-2 w-40">
                </div>

                <div class="flex flex-col">
                    <label class="mb-1 font-medium">ถึงวันที่</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="border rounded p-2 w-40">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-semibold">
                        แสดงรายงาน
                    </button>
                    <button type="submit" name="export_pdf" value="1" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded font-semibold">
                        Export PDF
                    </button>
                    <button type="submit" name="export_excel" value="1" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded font-semibold">
                        Export Excel
                    </button>
                </div>
            </form>

            @if(isset($transactions) && $transactions->count())
                <div class="overflow-x-auto"><table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2">วันที่</th>
                            <th class="border px-3 py-2">เลขที่เอกสาร</th>
                            <th class="border px-3 py-2">ประเภทการรับ</th>
                            <th class="border px-3 py-2">สินค้า</th>
                            <th class="border px-3 py-2">Kg/ctn</th>
                            <th class="border px-3 py-2">Kg/Inner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
                            @foreach($t->items as $i)
                                <tr>
                                    <td class="border px-3 py-2">{{ $t->trans_date }}</td>
                                    <td class="border px-3 py-2">{{ $t->trans_id }}</td>
                                    <td class="border px-3 py-2">{{ $t->receiveType->name ?? '-' }}</td>
                                    <td class="border px-3 py-2">{{ $i->product->name ?? '-' }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $i->full_qty }}</td>
                                    <td class="border px-3 py-2 text-center">{{ $i->fraction_qty }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table></div>
            @else
                <p class="text-gray-500 mt-4">ไม่มีข้อมูลในช่วงวันที่ที่เลือก</p>
            @endif
        </div>
    </div>
</x-app-layout>
