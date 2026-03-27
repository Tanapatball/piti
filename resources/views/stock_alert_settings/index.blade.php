<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">ตั้งค่าผู้รับแจ้งเตือนสต็อก</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">เลือกผู้รับอีเมลแจ้งเตือน</h3>
                        <p class="text-sm text-gray-500">ระบบจะส่งอีเมลอัตโนมัติทันทีเมื่อสต็อกสินค้าต่ำกว่า Min หรือเกิน Max</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('stock-alert-settings.update') }}">
                @csrf
                @method('PUT')

                <div class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <label class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 cursor-pointer transition">
                        <input type="checkbox" name="user_ids[]" value="{{ $user->user_id }}"
                            {{ $user->receive_stock_alert ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-sm font-bold">
                                    {{ mb_substr($user->fname, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $user->fname }} {{ $user->lname }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full
                            {{ $user->role->role_name === 'admin' ? 'bg-red-100 text-red-700' : ($user->role->role_name === 'staff' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $user->role->role_name }}
                        </span>
                    </label>
                    @endforeach
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <span class="text-sm text-gray-500">
                        เลือกอยู่ <strong id="selectedCount">{{ $users->where('receive_stock_alert', true)->count() }}</strong> คน
                    </span>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg text-sm transition">
                        บันทึกการตั้งค่า
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="user_ids[]"]').forEach(cb => {
            cb.addEventListener('change', () => {
                document.getElementById('selectedCount').textContent =
                    document.querySelectorAll('input[name="user_ids[]"]:checked').length;
            });
        });
    </script>
</x-app-layout>
