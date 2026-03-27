<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขคลังสินค้า</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">ชื่อคลังสินค้า</label>
                        <input type="text" name="warehouse_name" class="border rounded w-full p-2 mt-1"
                            value="{{ old('warehouse_name', $warehouse->warehouse_name) }}" required>
                        @error('warehouse_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">สถานที่</label>
                        <input type="text" name="location" class="border rounded w-full p-2 mt-1"
                            value="{{ old('location', $warehouse->location) }}">
                        @error('location')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                        บันทึกการแก้ไข
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
