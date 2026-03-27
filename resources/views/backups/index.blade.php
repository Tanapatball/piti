<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">สำรองข้อมูล</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
            {{ session('error') }}
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">สร้างไฟล์สำรองข้อมูล</h3>
                        <p class="text-sm text-gray-500">สำรองข้อมูลฐานข้อมูล MySQL</p>
                    </div>
                </div>
            </div>

            <div class="p-6 flex flex-wrap gap-3">
                <form method="POST" action="{{ route('backups.create-db') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/>
                        </svg>
                        สำรอง SQL
                    </button>
                </form>

                <form method="POST" action="{{ route('backups.create-excel') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125"/>
                        </svg>
                        สำรอง Excel
                    </button>
                </form>

                <form method="POST" action="{{ route('backups.create-db-excel') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        สำรองทั้ง SQL + Excel
                    </button>
                </form>
            </div>
        </div>

        <!-- Backup List -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">รายการไฟล์สำรองข้อมูล</h3>
                            <p class="text-sm text-gray-500">ทั้งหมด {{ count($files) }} ไฟล์</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(count($files) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อไฟล์</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ประเภท</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ขนาด</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่สร้าง</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($files as $file)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $bgColor = match($file['type'] ?? 'Full') {
                                            'SQL' => 'bg-emerald-100',
                                            'Excel' => 'bg-green-100',
                                            default => 'bg-blue-100'
                                        };
                                        $textColor = match($file['type'] ?? 'Full') {
                                            'SQL' => 'text-emerald-600',
                                            'Excel' => 'text-green-600',
                                            default => 'text-blue-600'
                                        };
                                    @endphp
                                    <div class="w-8 h-8 rounded {{ $bgColor }} flex items-center justify-center">
                                        <svg class="w-4 h-4 {{ $textColor }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800 text-sm">{{ $file['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeColor = match($file['type'] ?? 'Full') {
                                        'SQL' => 'bg-emerald-100 text-emerald-800',
                                        'Excel' => 'bg-green-100 text-green-800',
                                        default => 'bg-blue-100 text-blue-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    {{ $file['type'] ?? 'Full' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $file['size'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $file['date'] }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('backups.download', $file['name']) }}"
                                       class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                        </svg>
                                        ดาวน์โหลด
                                    </a>
                                    <form method="POST" action="{{ route('backups.destroy', $file['name']) }}" class="inline"
                                          onsubmit="return confirm('ต้องการลบไฟล์นี้หรือไม่?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 text-sm font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                            ลบ
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                </div>
                <h4 class="text-gray-600 font-medium mb-1">ยังไม่มีไฟล์สำรองข้อมูล</h4>
                <p class="text-sm text-gray-500">คลิกปุ่มด้านบนเพื่อสร้างไฟล์สำรองข้อมูล</p>
            </div>
            @endif
        </div>

        <!-- Info -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-medium mb-1">การสำรองข้อมูล</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li>ระบบจะสำรองฐานข้อมูลอัตโนมัติทุกวันอาทิตย์ เวลา 02:00 น.</li>
                        <li>ไฟล์ backup เก่าเกิน 30 วันจะถูกลบอัตโนมัติ</li>
                        <li>ดาวน์โหลดไฟล์ backup ไปเก็บไว้ที่อื่นด้วย (เช่น Google Drive, USB)</li>
                        <li><strong>SQL:</strong> ไฟล์ .sql สามารถนำไป import กลับได้ผ่าน phpMyAdmin</li>
                        <li><strong>Excel:</strong> ไฟล์ .xlsx แยกข้อมูลแต่ละตารางเป็น sheet สะดวกดูใน Excel</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
