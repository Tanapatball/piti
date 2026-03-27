<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">คลังสินค้า</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('warehouses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                    เพิ่มคลังสินค้า
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อคลังสินค้า</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานที่</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($warehouses as $warehouse)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $warehouse->warehouse_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $warehouse->warehouse_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $warehouse->location ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                <a href="{{ route('warehouses.edit', $warehouse) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" onsubmit="return confirm('ลบคลังสินค้านี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>

                @if($warehouses->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        ไม่มีข้อมูลคลังสินค้า
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
