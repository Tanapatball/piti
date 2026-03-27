<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- ข้อมูลผู้ใช้ -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 overflow-hidden rounded-xl shadow-md mb-4 sm:mb-6">
                <div class="p-4 sm:p-6 flex items-center gap-3 sm:gap-4">
                    <div class="w-11 h-11 sm:w-14 sm:h-14 shrink-0 rounded-full bg-white/20 flex items-center justify-center text-white text-lg sm:text-xl font-bold ring-2 ring-white/30">
                        {{ mb_substr(Auth::user()->fname, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-lg sm:text-2xl font-bold text-white truncate">สวัสดี {{ Auth::user()->fname }} {{ Auth::user()->lname }}</h1>
                        <p class="mt-0.5 text-indigo-200 text-xs sm:text-sm truncate">{{ Auth::user()->email }} | {{ Auth::user()->role->role_name ?? 'Role: ' . Auth::user()->role_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-4 sm:mb-6">
                <!-- จำนวนสินค้า -->
                <div class="bg-white overflow-hidden rounded-xl shadow-md hover:shadow-lg transition-shadow border-l-4 border-blue-500">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs sm:text-sm font-medium text-gray-500">สินค้าทั้งหมด</div>
                                <div class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold text-gray-800">{{ \App\Models\Product::count() }}</div>
                                <div class="mt-0.5 sm:mt-1 text-xs text-gray-400">รายการ</div>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-50 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- จำนวนหมวดหมู่ -->
                <div class="bg-white overflow-hidden rounded-xl shadow-md hover:shadow-lg transition-shadow border-l-4 border-green-500">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs sm:text-sm font-medium text-gray-500">หมวดสินค้า</div>
                                <div class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold text-gray-800">{{ \App\Models\Category::count() }}</div>
                                <div class="mt-0.5 sm:mt-1 text-xs text-gray-400">หมวด</div>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-green-50 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- จำนวนรับเข้า -->
                <div class="bg-white overflow-hidden rounded-xl shadow-md hover:shadow-lg transition-shadow border-l-4 border-indigo-500">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs sm:text-sm font-medium text-gray-500">รายการรับสินค้า</div>
                                <div class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold text-gray-800">{{ \App\Models\Transaction::count() }}</div>
                                <div class="mt-0.5 sm:mt-1 text-xs text-gray-400">รายการ</div>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-indigo-50 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- จำนวนเบิกออก -->
                <div class="bg-white overflow-hidden rounded-xl shadow-md hover:shadow-lg transition-shadow border-l-4 border-red-500">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs sm:text-sm font-medium text-gray-500">รายการเบิกสินค้า</div>
                                <div class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold text-gray-800">{{ \App\Models\StockOut::count() }}</div>
                                <div class="mt-0.5 sm:mt-1 text-xs text-gray-400">รายการ</div>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-red-50 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stock Overview --}}
            @php
                $allProducts = \App\Models\Product::orderBy('product_id')
                    ->withSum('transactionItems as total_ctn_in', 'full_qty')
                    ->withSum('transactionItems as total_inner_in', 'fraction_qty')
                    ->withSum('stockOuts as total_ctn_out', 'quantity')
                    ->withSum('stockOuts as total_inner_out', 'fraction_qty')
                    ->get()
                    ->map(function ($p) {
                        $p->ctn_remaining = ($p->total_ctn_in ?? 0) - ($p->total_ctn_out ?? 0);
                        $p->inner_remaining = ($p->total_inner_in ?? 0) - ($p->total_inner_out ?? 0);
                        return $p;
                    });
                $lowCount = 0;
                $overCount = 0;
                $normalCount = 0;
                foreach ($allProducts as $p) {
                    $isLow = $p->stock_min > 0 && $p->ctn_remaining < $p->stock_min;
                    $isOver = $p->stock_max > 0 && $p->ctn_remaining > $p->stock_max;
                    if ($isLow) $lowCount++;
                    elseif ($isOver) $overCount++;
                    else $normalCount++;
                }
                $alertCount = $lowCount + $overCount;
            @endphp

            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="p-4 sm:p-6">
                    {{-- Header --}}
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">สต็อกสินค้าทั้งหมด</h3>

                        {{-- Status badges --}}
                        @if($lowCount > 0)
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">⚠ ต่ำ {{ $lowCount }}</span>
                        @endif
                        @if($overCount > 0)
                        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">⚠ เกิน {{ $overCount }}</span>
                        @endif
                        @if($alertCount == 0)
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">✓ ปกติทั้งหมด</span>
                        @endif

                        {{-- Admin buttons --}}
                        @if(Auth::user()->isAdmin())
                        <div class="ml-auto flex items-center gap-2">
                            <form method="POST" action="{{ route('stock.sync') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition whitespace-nowrap" onclick="return confirm('Sync current_stock ให้ตรงกับยอดรับ-เบิกจริง?')">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M2.985 19.644l3.181-3.183"/></svg>
                                    Sync Stock
                                </button>
                            </form>
                            @if($alertCount > 0)
                            <form method="POST" action="{{ route('stock-alert.send') }}" class="flex items-center gap-2" onsubmit="return confirm('ส่งอีเมลแจ้งเตือนไปที่ ' + this.email.value + ' ?')">
                                @csrf
                                <input type="email" name="email" value="{{ env('STOCK_ALERT_EMAIL') }}" required
                                    class="border border-gray-300 rounded-lg text-xs px-2.5 py-1.5 w-48 focus:ring-indigo-500 focus:border-indigo-500" placeholder="อีเมลผู้รับ">
                                <button type="submit" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                    ส่งอีเมล
                                </button>
                            </form>
                            @endif
                        </div>
                        @endif
                    </div>

                    @if(session('success'))
                    <div class="mb-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-2">
                        {{ session('success') }}
                    </div>
                    @endif

                    {{-- Stock Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm" id="stock-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase">รหัส</th>
                                    <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อสินค้า</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase">CTN คงเหลือ</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase">Inner คงเหลือ</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase">Min</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase">Max</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-600 uppercase">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($allProducts as $p)
                                @php
                                    $isLow = $p->stock_min > 0 && $p->ctn_remaining < $p->stock_min;
                                    $isOver = $p->stock_max > 0 && $p->ctn_remaining > $p->stock_max;
                                    $rowBg = $isLow ? 'bg-red-50/60' : ($isOver ? 'bg-orange-50/60' : '');
                                @endphp
                                <tr class="{{ $rowBg }} hover:bg-gray-50 transition-colors">
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-700 font-mono text-xs">{{ $p->product_id }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-800">{{ $p->name }}</td>
                                    <td class="px-3 py-2 text-center font-bold {{ $isLow ? 'text-red-600' : ($isOver ? 'text-orange-600' : 'text-gray-800') }}">
                                        {{ $p->ctn_remaining }}
                                    </td>
                                    <td class="px-3 py-2 text-center text-gray-700">
                                        {{ $p->inner_remaining }}
                                    </td>
                                    <td class="px-3 py-2 text-center text-gray-400">{{ $p->stock_min ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center text-gray-400">{{ $p->stock_max ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if($isLow)
                                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/></svg>
                                                ขาด {{ $p->stock_min - $p->ctn_remaining }}
                                            </span>
                                        @elseif($isOver)
                                            <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                                                เกิน {{ $p->ctn_remaining - $p->stock_max }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                ปกติ
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
                        <span>ทั้งหมด {{ $allProducts->count() }} รายการ | ปกติ {{ $normalCount }} | ต่ำ {{ $lowCount }} | เกิน {{ $overCount }}</span>

                        {{-- Pagination Controls --}}
                        <div class="flex items-center gap-2">
                            <button id="prevPage" onclick="changePage(-1)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>
                            <span class="text-gray-600 font-medium">หน้า <span id="currentPage">1</span> / <span id="totalPages">1</span></span>
                            <button id="nextPage" onclick="changePage(1)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </button>
                            <select id="perPageSelect" onchange="changePerPage(this.value)" class="ml-2 border border-gray-300 rounded-lg px-2 py-1 text-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="10">10 / หน้า</option>
                                <option value="25">25 / หน้า</option>
                                <option value="50">50 / หน้า</option>
                                <option value="100">100 / หน้า</option>
                            </select>
                        </div>

                        <span id="last-updated">อัปเดตล่าสุด: {{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>

            {{-- Pagination Script --}}
            <script>
                (function() {
                    let currentPage = 1;
                    let perPage = 10;
                    const table = document.querySelector('#stock-table tbody');
                    const rows = Array.from(table.querySelectorAll('tr'));
                    const totalItems = rows.length;

                    function updatePagination() {
                        const totalPages = Math.ceil(totalItems / perPage);
                        document.getElementById('currentPage').textContent = currentPage;
                        document.getElementById('totalPages').textContent = totalPages;

                        document.getElementById('prevPage').disabled = currentPage <= 1;
                        document.getElementById('nextPage').disabled = currentPage >= totalPages;

                        const start = (currentPage - 1) * perPage;
                        const end = start + perPage;

                        rows.forEach((row, index) => {
                            row.style.display = (index >= start && index < end) ? '' : 'none';
                        });
                    }

                    window.changePage = function(delta) {
                        const totalPages = Math.ceil(totalItems / perPage);
                        const newPage = currentPage + delta;
                        if (newPage >= 1 && newPage <= totalPages) {
                            currentPage = newPage;
                            updatePagination();
                        }
                    };

                    window.changePerPage = function(value) {
                        perPage = parseInt(value);
                        currentPage = 1;
                        updatePagination();
                    };

                    // Initialize
                    updatePagination();
                })();
            </script>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- รายการรับสินค้าล่าสุด -->
                <div class="bg-white overflow-hidden rounded-xl shadow-md">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center gap-2 mb-3 sm:mb-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15" /></svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800">รับสินค้าล่าสุด</h3>
                        </div>
                        @php
                            $recentTransactions = \App\Models\Transaction::with('receiveType')
                                ->orderBy('trans_date', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @if($recentTransactions->count())
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เลขที่</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ประเภท</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($recentTransactions as $t)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2.5 text-sm text-gray-700 whitespace-nowrap">{{ $t->trans_id }}</td>
                                            <td class="px-3 py-2.5 text-sm text-gray-700 whitespace-nowrap">{{ $t->trans_date }}</td>
                                            <td class="px-3 py-2.5 text-sm text-gray-700 whitespace-nowrap">{{ $t->receiveType->name ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-400 text-sm text-center py-4">ยังไม่มีรายการ</p>
                        @endif
                    </div>
                </div>

                <!-- รายการเบิกสินค้าล่าสุด -->
                <div class="bg-white overflow-hidden rounded-xl shadow-md">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center gap-2 mb-3 sm:mb-4">
                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800">เบิกสินค้าล่าสุด</h3>
                        </div>
                        @php
                            $recentStockOuts = \App\Models\StockOut::with('product')
                                ->orderBy('issued_date', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @if($recentStockOuts->count())
                            <div class="overflow-x-auto -mx-4 sm:mx-0">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สินค้า</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวน</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ผู้เบิก</th>
                                            <th class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($recentStockOuts as $s)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2.5 text-sm text-gray-700 whitespace-nowrap">{{ $s->product->name ?? '-' }}</td>
                                            <td class="px-3 py-2.5 text-sm text-gray-700 whitespace-nowrap">{{ $s->quantity }}</td>
                                            <td class="px-3 py-2.5 text-sm text-gray-700 whitespace-nowrap">{{ $s->issued_to }}</td>
                                            <td class="px-3 py-2.5 text-sm text-gray-700 whitespace-nowrap">{{ $s->issued_date }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-400 text-sm text-center py-4">ยังไม่มีรายการ</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
