<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                <h2 class="text-xl font-bold text-gray-800">รายการเบิกสินค้า</h2>
                <a href="{{ route('stock-outs.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 text-sm font-medium text-center">
                    + เบิกสินค้า
                </a>
            </div>

            {{-- Search / Filter --}}
            <form method="GET" action="{{ route('stock-outs.index') }}" class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                <div class="flex flex-col sm:flex-row gap-3 items-end flex-wrap">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">ค้นหา</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="เลขที่รับ, ชื่อสินค้า"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">วันที่เริ่มต้น</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full border border-gray-300 rounded-lg text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">วันที่สิ้นสุด</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full border border-gray-300 rounded-lg text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 items-end flex-wrap mt-3">
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">สินค้าเริ่มต้น</label>
                        <select name="product_from" class="w-full border border-gray-300 rounded-lg text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- เลือก --</option>
                            @foreach($products as $p)
                            <option value="{{ $p->product_id }}" {{ request('product_from') == $p->product_id ? 'selected' : '' }}>{{ $p->product_id }} - {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[180px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">สินค้าสิ้นสุด</label>
                        <select name="product_to" class="w-full border border-gray-300 rounded-lg text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- เลือก --</option>
                            @foreach($products as $p)
                            <option value="{{ $p->product_id }}" {{ request('product_to') == $p->product_id ? 'selected' : '' }}>{{ $p->product_id }} - {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">รูปแบบรายงาน</label>
                        <select name="report_type" class="w-full border border-gray-300 rounded-lg text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="normal" {{ request('report_type') == 'normal' ? 'selected' : '' }}>ปกติ</option>
                            <option value="by_size" {{ request('report_type') == 'by_size' ? 'selected' : '' }}>จัดกลุ่มตาม Size</option>
                            <option value="by_pack" {{ request('report_type') == 'by_pack' ? 'selected' : '' }}>จัดกลุ่มตาม Pack</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">ค้นหา</button>
                        @if(request()->hasAny(['search', 'date_from', 'date_to', 'product_from', 'product_to']))
                        <a href="{{ route('stock-outs.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">ล้าง</a>
                        @endif
                        <button type="submit" name="export_pdf" value="1" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            PDF
                        </button>
                        <button type="submit" name="export_excel" value="1" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Excel
                        </button>
                    </div>
                </div>
            </form>

            @if(request()->hasAny(['search', 'date_from', 'date_to', 'product_from', 'product_to']))
            <div class="mb-3 text-sm text-gray-500">
                พบ {{ $stockOuts->total() }} รายการ
                @if(request('search'))| ค้นหา: "{{ request('search') }}"@endif
                @if(request('date_from'))| ตั้งแต่: {{ request('date_from') }}@endif
                @if(request('date_to'))| ถึง: {{ request('date_to') }}@endif
                @if(request('product_from'))| สินค้าเริ่ม: {{ request('product_from') }}@endif
                @if(request('product_to'))| สินค้าสิ้นสุด: {{ request('product_to') }}@endif
            </div>
            @endif

            <div class="overflow-x-auto"><table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">เลขที่เบิก</th>
                        <th class="border px-4 py-2 text-left">รหัสสินค้า</th>
                        <th class="border px-4 py-2 text-left">ชื่อสินค้า</th>
                        <th class="border px-4 py-2 text-left">เลขที่อ้างอิง</th>
                        <th class="border px-4 py-2 text-left">จำนวน</th>
                        <th class="border px-4 py-2 text-left">วันที่</th>
                        <th class="border px-4 py-2 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockOuts as $stockOut)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $stockOut->id }}</td>
                            <td class="border px-4 py-2">{{ $stockOut->product->product_id ?? $stockOut->product_id }}</td>
                            <td class="border px-4 py-2">{{ $stockOut->product->name ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $stockOut->reference_no ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $stockOut->quantity }}</td>
                            <td class="border px-4 py-2">{{ $stockOut->issued_date }}</td>
                            <td class="border px-4 py-2 text-center whitespace-nowrap">
                                <a href="{{ route('stock-outs.show', $stockOut->id) }}"
                                   class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded hover:bg-blue-200 mr-1" title="ดูรายละเอียด">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('stock-outs.edit', $stockOut->id) }}"
                                   class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded hover:bg-yellow-200 mr-1" title="แก้ไข">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('stock-outs.destroy', $stockOut->id) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันการลบข้อมูล? การกระทำนี้จะคืนสต๊อกสินค้ากลับ');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200" title="ลบ">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border px-4 py-6 text-center text-gray-500">
                                @if(request()->hasAny(['search', 'date_from', 'date_to', 'product_from', 'product_to']))
                                    ไม่พบรายการที่ตรงกับเงื่อนไข
                                @else
                                    ไม่มีรายการเบิกสินค้า
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table></div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $stockOuts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
