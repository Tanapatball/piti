<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายละเอียดการเบิกสินค้า</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="mb-6">
                <a href="{{ route('stock-outs.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; กลับไปรายการเบิก
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-500 text-sm">เลขที่เบิก</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->note ?? $stockOut->id }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">รหัสสินค้า</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->product_id }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">ชื่อสินค้า</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->product->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Code</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->code ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Kg/ctn</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->quantity }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Kg/Inner</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->fraction_qty ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">เบิกให้</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->issued_to ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">วันที่เบิก</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->issued_date }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">เอกสารอ้างอิง</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->reference_doc ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">เลขที่อ้างอิง</p>
                    <p class="font-semibold text-gray-800">{{ $stockOut->reference_no ?? '-' }}</p>
                </div>
            </div>

            <form action="{{ route('stock-outs.destroy', $stockOut) }}" method="POST" onsubmit="return confirm('ต้องการลบรายการนี้?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded shadow">
                    ลบรายการเบิก
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
