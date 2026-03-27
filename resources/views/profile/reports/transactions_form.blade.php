<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">รายงานรับสินค้าเข้าคลัง</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 bg-white shadow rounded-lg p-6">
        <form action="{{ route('reports.transactions.pdf') }}" method="POST" target="_blank">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-gray-700">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                ออกรายงาน PDF
            </button>
        </form>
    </div>
</x-app-layout>
