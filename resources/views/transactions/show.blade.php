<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายละเอียดรายการรับสินค้า</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="mb-6">
                <a href="{{ route('transactions.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; กลับไปรายการ
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-500 text-sm">เลขที่</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->trans_id }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">วันที่เข้าคลัง</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->trans_date }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">ประเภทการรับเข้า</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->receiveType->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">เลขที่อ้างอิง</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->reference_no ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">เอกสารอ้างอิง</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->reference_doc ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">หมายเหตุ</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->note ?? '-' }}</p>
                </div>
            </div>

            <h3 class="font-semibold text-lg mb-2">รายการสินค้า</h3>
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">สินค้า</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Racking</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pack</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kg/ctn</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kg/Inner</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">น้ำหนัก (kg)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaction->items as $item)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->product->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->item_code ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->product->size ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->product->pack ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->full_qty }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->fraction_qty }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $item->net_weight }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table></div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('transactions.edit', $transaction) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded shadow">
                    แก้ไข
                </a>
                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('ต้องการลบรายการนี้?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded shadow">
                        ลบ
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
