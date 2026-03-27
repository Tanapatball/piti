<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">ประเภทการเบิก</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <form method="GET" action="{{ route('issue-types.index') }}" class="flex flex-col sm:flex-row gap-2 flex-1">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหารหัส, ชื่อประเภท..."
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">ค้นหา</button>
                @if(request('search'))
                <a href="{{ route('issue-types.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium text-center">ล้าง</a>
                @endif
            </form>
            <a href="{{ route('issue-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow text-sm text-center">
                เพิ่มประเภทการเบิก
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        @if(request('search'))
        <div class="mb-3 text-sm text-gray-500">
            พบ {{ $issueTypes->count() }} รายการ | ค้นหา: "{{ request('search') }}"
        </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อประเภท</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($issueTypes as $type)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $type->issue_type_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $type->name }}</td>
                        <td class="px-6 py-4 text-sm font-medium flex gap-2">
                            <a href="{{ route('issue-types.edit', $type) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('issue-types.destroy', $type) }}" method="POST" onsubmit="return confirm('ลบรายการนี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table></div>
            @if($issueTypes->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    @if(request('search'))
                        ไม่พบประเภทที่ตรงกับเงื่อนไข
                    @else
                        ไม่มีข้อมูล
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
