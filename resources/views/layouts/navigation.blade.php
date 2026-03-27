<nav x-data="{ open: false }" class="bg-gradient-to-r from-indigo-700 to-indigo-900 shadow-lg">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-auto fill-current text-white" />
                        <span class="text-white font-bold text-lg hidden lg:block">WMS</span>
                    </a>
                </div>

                <div class="hidden space-x-3 sm:-my-px sm:ms-8 sm:flex items-center whitespace-nowrap">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1" /></svg>
                        Dashboard
                    </x-nav-link>

                    @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        Users
                    </x-nav-link>
                    @endif

                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        Products
                    </x-nav-link>

                    @if(!Auth::user()->isUser())
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                        หมวดสินค้า
                    </x-nav-link>

                    <x-nav-link href="{{ route('receive-types.index') }}" :active="request()->routeIs('receive-types.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        ประเภทรับเข้า
                    </x-nav-link>

                    <x-nav-link href="{{ route('issue-types.index') }}" :active="request()->routeIs('issue-types.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        ประเภทการเบิก
                    </x-nav-link>

                    <x-nav-link href="{{ route('transactions.index') }}" :active="request()->routeIs('transactions.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859" /></svg>
                        รับสินค้า
                    </x-nav-link>

                    <x-nav-link :href="route('stock-outs.index')" :active="request()->routeIs('stock-outs.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                        เบิกสินค้า
                    </x-nav-link>
                    @endif

                    @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('stock-alert-settings.index')" :active="request()->routeIs('stock-alert-settings.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        แจ้งเตือน
                    </x-nav-link>

                    <x-nav-link :href="route('backups.index')" :active="request()->routeIs('backups.*')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/></svg>
                        สำรองข้อมูล
                    </x-nav-link>
                    @endif

                    <!-- Desktop: รายงาน Mega Menu -->
                    <div x-data="{ reportOpen: false }" class="relative flex items-center h-full">
                        <button @click="reportOpen = !reportOpen"
                            class="inline-flex items-center gap-1.5 px-1 pt-1 border-b-2 text-sm font-medium leading-5 h-full transition duration-150 ease-in-out
                            {{ request()->routeIs('reports.*') ? 'border-white text-white font-semibold' : 'border-transparent text-indigo-200 hover:text-white hover:border-indigo-300' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                            <div>รายงาน</div>
                            <div class="ms-0.5">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>

                        <div x-show="reportOpen" @click.away="reportOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute top-full right-0 mt-1 w-[750px] bg-white border border-gray-200 rounded-xl shadow-2xl z-50 p-5"
                            style="display: none;">

                            <div class="grid grid-cols-3 gap-5">
                                <!-- Column 1 -->
                                <div>
                                    <h3 class="font-bold text-indigo-800 text-sm mb-2 pb-1 border-b border-indigo-100">เมนูหลัก</h3>
                                    <a href="{{ route('reports.main.receive-types') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">ประเภทจัดเก็บ</a>
                                    <a href="{{ route('reports.main.categories') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">หมวดสินค้า</a>
                                    <a href="{{ route('reports.main.products') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">รายละเอียดสินค้า</a>

                                    <h3 class="font-bold text-indigo-800 text-sm mt-4 mb-2 pb-1 border-b border-indigo-100">รายละเอียดสินค้า</h3>
                                    <a href="{{ route('reports.products.by-category') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">แสดงสินค้าแยกตามหมวด</a>
                                    <a href="{{ route('reports.products.by-date') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">แสดงสินค้าตามวันเข้าคลัง</a>
                                    <a href="{{ route('reports.products.all') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">แสดงรายละเอียดสินค้าทั้งหมด</a>
                                    <a href="{{ route('reports.products.by-size') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">แสดงรายละเอียดตาม Size</a>
                                    <a href="{{ route('reports.products.by-pack') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">แสดงรายละเอียดตาม Pack</a>
                                </div>

                                <!-- Column 2 -->
                                <div>
                                    <h3 class="font-bold text-indigo-800 text-sm mb-2 pb-1 border-b border-indigo-100">รายงานรับสินค้าเข้าคลัง</h3>
                                    <a href="{{ route('reports.received.check') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">รายงานตรวจสอบใบรับเบิก</a>
                                    <a href="{{ route('reports.received.by-product') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">รายงานตรวจสอบใบรับ/รหัสสินค้า</a>
                                    <a href="{{ route('reports.received.by-type') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">ใบรับสินค้าตามประเภท</a>

                                    <h3 class="font-bold text-indigo-800 text-sm mt-4 mb-2 pb-1 border-b border-indigo-100">รายงานเบิกสินค้าจากคลัง</h3>
                                    <a href="{{ route('reports.issued.check') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">รายงานตรวจสอบใบเบิก</a>
                                    <a href="{{ route('reports.issued.by-product') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">รายงานตรวจสอบเบิก/รหัสสินค้า</a>
                                    <a href="{{ route('reports.issued.by-type') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">ใบเบิกสินค้าตามประเภท</a>
                                </div>

                                <!-- Column 3 -->
                                <div>
                                    <h3 class="font-bold text-indigo-800 text-sm mb-2 pb-1 border-b border-indigo-100">สินค้าคงเหลือ ณ ปัจจุบัน</h3>
                                    <a href="{{ route('reports.stock-remaining-size') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">คงเหลือ ณ ปัจจุบัน/Size</a>
                                    <a href="{{ route('reports.stock-remaining-pack') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">คงเหลือ ณ ปัจจุบัน/Pack</a>

                                    <h3 class="font-bold text-indigo-800 text-sm mt-4 mb-2 pb-1 border-b border-indigo-100">สต็อกการ์ด</h3>
                                    <a href="{{ route('reports.stock-card-by-id') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">สต็อกการ์ด ตามรหัส</a>
                                    <a href="{{ route('reports.stock-card-by-code') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">สต็อกการ์ดแยกตาม Code</a>

                                    <h3 class="font-bold text-indigo-800 text-sm mt-4 mb-2 pb-1 border-b border-indigo-100">รายงานคงเหลืออื่นๆ</h3>
                                    <a href="{{ route('reports.stock-by-product') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">คงเหลือตาม Product</a>
                                    <a href="{{ route('reports.stock-quantity') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">คงเหลือปริมาณ</a>
                                    <a href="{{ route('reports.stock-by-product-no-code') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">คงเหลือตาม Product ไม่โชว์ Code</a>

                                    <h3 class="font-bold text-indigo-800 text-sm mt-4 mb-2 pb-1 border-b border-indigo-100">รายงานสรุป</h3>
                                    <a href="{{ route('reports.summary.product') }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 rounded transition">สรุปสินค้า (รับ/เบิก)</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Bell + User Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:gap-2">
                <!-- Notification Bell -->
                <div x-data="notificationBell()" x-init="init()" class="relative">
                    <button @click="toggle()" class="relative p-2 text-indigo-200 hover:text-white rounded-lg hover:bg-indigo-600/50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                        <!-- Badge -->
                        <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount"
                              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full px-1">
                        </span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 overflow-hidden"
                         style="display: none;">

                        <!-- Header -->
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800">การแจ้งเตือน</h3>
                            <button x-show="unreadCount > 0" @click="markAllRead()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                อ่านทั้งหมด
                            </button>
                        </div>

                        <!-- Notification List -->
                        <div class="max-h-80 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                    </svg>
                                    <p class="text-sm">ไม่มีการแจ้งเตือน</p>
                                </div>
                            </template>

                            <template x-for="n in notifications" :key="n.id">
                                <a :href="n.link || '#'" @click="markRead(n.id)"
                                   class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition"
                                   :class="{ 'bg-indigo-50/50': !n.is_read }">
                                    <div class="flex gap-3">
                                        <!-- Icon -->
                                        <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                                             :class="{
                                                 'bg-red-100 text-red-600': n.color === 'red',
                                                 'bg-yellow-100 text-yellow-600': n.color === 'yellow',
                                                 'bg-green-100 text-green-600': n.color === 'green',
                                                 'bg-blue-100 text-blue-600': n.color === 'blue'
                                             }">
                                            <template x-if="n.icon === 'arrow-down'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                                </svg>
                                            </template>
                                            <template x-if="n.icon === 'arrow-up'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                                </svg>
                                            </template>
                                            <template x-if="!n.icon || (n.icon !== 'arrow-down' && n.icon !== 'arrow-up')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                                </svg>
                                            </template>
                                        </div>
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-800" x-text="n.title"></p>
                                            <p class="text-xs text-gray-500 truncate" x-text="n.message"></p>
                                            <p class="text-xs text-gray-400 mt-1" x-text="n.created_at"></p>
                                        </div>
                                        <!-- Unread dot -->
                                        <div x-show="!n.is_read" class="shrink-0 w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-2 bg-gray-50 border-t border-gray-200">
                            <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                ดูทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 text-sm leading-4 font-medium rounded-lg text-indigo-100 hover:text-white focus:outline-none transition">
                            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-bold ring-2 ring-indigo-300/50">
                                {{ mb_substr(Auth::user()->fname, 0, 1) }}
                            </div>
                            <div class="hidden lg:block">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</div>
                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-indigo-200 hover:text-white hover:bg-indigo-600 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-indigo-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            @if(Auth::user()->isAdmin())
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                Users
            </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                Products
            </x-responsive-nav-link>
            @if(!Auth::user()->isUser())
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                หมวดสินค้า
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('receive-types.index') }}" :active="request()->routeIs('receive-types.*')">
                ประเภทการรับเข้า
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('issue-types.index') }}" :active="request()->routeIs('issue-types.*')">
                ประเภทการเบิก
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                รับสินค้า / รายการสินค้า
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stock-outs.index')" :active="request()->routeIs('stock-outs.*')">
                เบิกสินค้า
            </x-responsive-nav-link>
            @endif
            @if(Auth::user()->isAdmin())
            <x-responsive-nav-link :href="route('stock-alert-settings.index')" :active="request()->routeIs('stock-alert-settings.*')">
                ตั้งค่าแจ้งเตือน
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('backups.index')" :active="request()->routeIs('backups.*')">
                สำรองข้อมูล
            </x-responsive-nav-link>
            @endif

            <!-- Mobile: รายงาน Accordion -->
            <div x-data="{ rptMain: false, rptProduct: false, rptReceived: false, rptIssued: false, rptStock: false }">
                <button @click="rptMain = !rptMain" class="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-indigo-200 hover:text-white hover:bg-indigo-700/50 hover:border-indigo-300 transition">
                    เมนูหลัก
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': rptMain }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="rptMain" x-collapse class="space-y-1 bg-indigo-900/30">
                    <x-responsive-nav-link href="{{ route('reports.main.receive-types') }}" class="ps-8">ประเภทจัดเก็บ</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.main.categories') }}" class="ps-8">หมวดสินค้า</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.main.products') }}" class="ps-8">รายละเอียดสินค้า</x-responsive-nav-link>
                </div>

                <button @click="rptProduct = !rptProduct" class="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-indigo-200 hover:text-white hover:bg-indigo-700/50 hover:border-indigo-300 transition">
                    รายละเอียดสินค้า
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': rptProduct }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="rptProduct" x-collapse class="space-y-1 bg-indigo-900/30">
                    <x-responsive-nav-link href="{{ route('reports.products.by-category') }}" class="ps-8">แสดงสินค้าแยกตามหมวด</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.products.by-date') }}" class="ps-8">แสดงสินค้าตามวันเข้าคลัง</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.products.all') }}" class="ps-8">แสดงรายละเอียดสินค้าทั้งหมด</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.products.by-size') }}" class="ps-8">แสดงรายละเอียดตาม Size</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.products.by-pack') }}" class="ps-8">แสดงรายละเอียดตาม Pack</x-responsive-nav-link>
                </div>

                <button @click="rptReceived = !rptReceived" class="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-indigo-200 hover:text-white hover:bg-indigo-700/50 hover:border-indigo-300 transition">
                    รายงานรับสินค้าเข้าคลัง
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': rptReceived }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="rptReceived" x-collapse class="space-y-1 bg-indigo-900/30">
                    <x-responsive-nav-link href="{{ route('reports.received.check') }}" class="ps-8">รายงานตรวจสอบใบรับเบิก</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.received.by-product') }}" class="ps-8">รายงานตรวจสอบใบรับ/รหัสสินค้า</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.received.by-type') }}" class="ps-8">ใบรับสินค้าตามประเภท</x-responsive-nav-link>
                </div>

                <button @click="rptIssued = !rptIssued" class="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-indigo-200 hover:text-white hover:bg-indigo-700/50 hover:border-indigo-300 transition">
                    รายงานเบิกสินค้าจากคลัง
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': rptIssued }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="rptIssued" x-collapse class="space-y-1 bg-indigo-900/30">
                    <x-responsive-nav-link href="{{ route('reports.issued.check') }}" class="ps-8">รายงานตรวจสอบใบเบิก</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.issued.by-product') }}" class="ps-8">รายงานตรวจสอบเบิก/รหัสสินค้า</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.issued.by-type') }}" class="ps-8">ใบเบิกสินค้าตามประเภท</x-responsive-nav-link>
                </div>

                <button @click="rptStock = !rptStock" class="w-full flex items-center justify-between ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-indigo-200 hover:text-white hover:bg-indigo-700/50 hover:border-indigo-300 transition">
                    สินค้าคงเหลือ / สต็อกการ์ด
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': rptStock }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="rptStock" x-collapse class="space-y-1 bg-indigo-900/30">
                    <x-responsive-nav-link href="{{ route('reports.stock-remaining-size') }}" class="ps-8">คงเหลือ ณ ปัจจุบัน/Size</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.stock-remaining-pack') }}" class="ps-8">คงเหลือ ณ ปัจจุบัน/Pack</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.stock-card-by-id') }}" class="ps-8">สต็อกการ์ด ตามรหัส</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.stock-card-by-code') }}" class="ps-8">สต็อกการ์ดแยกตาม Code</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.stock-by-product') }}" class="ps-8">คงเหลือตาม Product</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.stock-quantity') }}" class="ps-8">คงเหลือปริมาณ</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.stock-by-product-no-code') }}" class="ps-8">คงเหลือตาม Product ไม่โชว์ Code</x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('reports.summary.product') }}" class="ps-8">สรุปสินค้า (รับ/เบิก)</x-responsive-nav-link>
                </div>
            </div>
        </div>

        <!-- Mobile User Info -->
        <div class="pt-4 pb-1 border-t border-indigo-600">
            <div class="px-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm font-bold ring-2 ring-indigo-300/50">
                    {{ mb_substr(Auth::user()->fname, 0, 1) }}
                </div>
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</div>
                    <div class="font-medium text-sm text-indigo-300">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
function notificationBell() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.fetchNotifications();
            // Refresh every 30 seconds
            setInterval(() => this.fetchUnreadCount(), 30000);
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.fetchNotifications();
            }
        },

        async fetchNotifications() {
            try {
                const response = await fetch('{{ route("notifications.dropdown") }}');
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        },

        async fetchUnreadCount() {
            try {
                const response = await fetch('{{ route("notifications.unread-count") }}');
                const data = await response.json();
                this.unreadCount = data.count;
            } catch (error) {
                console.error('Error fetching unread count:', error);
            }
        },

        async markRead(id) {
            try {
                await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                });
                const notification = this.notifications.find(n => n.id === id);
                if (notification && !notification.is_read) {
                    notification.is_read = true;
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllRead() {
            try {
                await fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                });
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        }
    }
}
</script>
