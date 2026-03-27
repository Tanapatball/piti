<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">ผู้ใช้ทั้งหมด</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row gap-2 flex-1">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ, นามสกุล, Email..."
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <select name="role" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- ทุก Role --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->role_id }}" {{ request('role') == $role->role_id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">ค้นหา</button>
                    @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium text-center">ล้าง</a>
                    @endif
                </form>
                <a href="{{ route('users.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow text-sm text-center">
                    เพิ่มผู้ใช้
                </a>
            </div>

            @if(request('search') || request('role'))
            <div class="mb-3 text-sm text-gray-500">
                พบ {{ $users->count() }} รายการ
                @if(request('search')) | ค้นหา: "{{ request('search') }}" @endif
                @if(request('role')) | Role: {{ $roles->firstWhere('role_id', request('role'))->role_name ?? '' }} @endif
            </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">นามสกุล</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->user_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->fname }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->lname }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($user->role->role_name === 'admin')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">admin</span>
                                @elseif($user->role->role_name === 'staff')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">staff</span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">user</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('ลบผู้ใช้นี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>

                @if($users->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        @if(request('search') || request('role'))
                            ไม่พบผู้ใช้ที่ตรงกับเงื่อนไข
                        @else
                            ไม่มีผู้ใช้ในระบบ
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
