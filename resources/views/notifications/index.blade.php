<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">การแจ้งเตือน</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
        @endif

        <!-- Actions -->
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                ทั้งหมด {{ $notifications->total() }} รายการ
            </div>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        อ่านทั้งหมด
                    </button>
                </form>
                <span class="text-gray-300">|</span>
                <form method="POST" action="{{ route('notifications.destroy-read') }}" class="inline"
                      onsubmit="return confirm('ต้องการลบการแจ้งเตือนที่อ่านแล้วทั้งหมดหรือไม่?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                        ลบที่อ่านแล้ว
                    </button>
                </form>
            </div>
        </div>

        <!-- Notification List -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @forelse($notifications as $notification)
            <div class="border-b border-gray-100 last:border-0 {{ $notification->isRead() ? '' : 'bg-indigo-50/50' }}">
                <div class="p-4 flex gap-4">
                    <!-- Icon -->
                    <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                        @if($notification->color === 'red') bg-red-100 text-red-600
                        @elseif($notification->color === 'yellow') bg-yellow-100 text-yellow-600
                        @elseif($notification->color === 'green') bg-green-100 text-green-600
                        @else bg-blue-100 text-blue-600 @endif">
                        @if($notification->icon === 'arrow-down')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        @elseif($notification->icon === 'arrow-up')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-medium text-gray-800">
                                    {{ $notification->title }}
                                    @if(!$notification->isRead())
                                        <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full ml-1"></span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-2">
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="text-indigo-600 hover:text-indigo-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                        </svg>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                </div>
                <h4 class="text-gray-600 font-medium mb-1">ไม่มีการแจ้งเตือน</h4>
                <p class="text-sm text-gray-500">เมื่อมีการแจ้งเตือนใหม่จะแสดงที่นี่</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
        @endif

        <!-- Info -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-medium mb-1">ประเภทการแจ้งเตือน</p>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li><span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-1"></span> สต็อกต่ำกว่าขั้นต่ำ - ควรสั่งซื้อเพิ่ม</li>
                        <li><span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mr-1"></span> สต็อกเกินสูงสุด - ควรระบายสินค้า</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
