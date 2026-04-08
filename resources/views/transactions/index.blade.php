<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">รายการรับสินค้า</h2>
            <a href="{{ route('transactions.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow text-sm">
                รับสินค้า
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow">{{ session('success') }}</div>
        @endif

        {{-- Search / Filter --}}
        <form method="GET" action="{{ route('transactions.index') }}" class="bg-white shadow rounded-lg p-4 mb-4">
            <div class="flex flex-col sm:flex-row gap-3 items-end flex-wrap">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">ค้นหา</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="เลขที่, เลขอ้างอิง, หมายเหตุ..."
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">ประเภทรับเข้า</label>
                    <select name="receive_type" class="w-full border border-gray-300 rounded-lg text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- ทั้งหมด --</option>
                        @foreach($receiveTypes as $rt)
                        <option value="{{ $rt->receive_type_id }}" {{ request('receive_type') == $rt->receive_type_id ? 'selected' : '' }}>{{ $rt->name }}</option>
                        @endforeach
                    </select>
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
                    @if(request()->hasAny(['search', 'receive_type', 'date_from', 'date_to', 'product_from', 'product_to']))
                    <a href="{{ route('transactions.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">ล้าง</a>
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

        @if(request()->hasAny(['search', 'receive_type', 'date_from', 'date_to', 'product_from', 'product_to']))
        <div class="mb-3 text-sm text-gray-500">
            พบ {{ $transactions->count() }} รายการ
            @if(request('search'))| ค้นหา: "{{ request('search') }}"@endif
            @if(request('receive_type'))| ประเภท: {{ $receiveTypes->firstWhere('receive_type_id', request('receive_type'))->name ?? '' }}@endif
            @if(request('date_from'))| ตั้งแต่: {{ request('date_from') }}@endif
            @if(request('date_to'))| ถึง: {{ request('date_to') }}@endif
            @if(request('product_from'))| สินค้าเริ่ม: {{ request('product_from') }}@endif
            @if(request('product_to'))| สินค้าสิ้นสุด: {{ request('product_to') }}@endif
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-4">
            {{-- ฝั่งซ้าย: เลขที่ --}}
            <div class="w-full lg:w-1/4 bg-white shadow rounded-lg overflow-y-auto max-h-[600px]">
                <h3 class="font-semibold text-lg p-4 border-b">รายการเลขที่ ({{ $transactions->count() }})</h3>
                @if($transactions->isEmpty())
                    <div class="p-4 text-center text-gray-500 text-sm">
                        @if(request()->hasAny(['search', 'receive_type', 'date_from', 'date_to', 'product_from', 'product_to']))
                            ไม่พบรายการที่ตรงกับเงื่อนไข
                        @else
                            ไม่มีรายการรับสินค้า
                        @endif
                    </div>
                @endif
                <ul id="transList">
                    @foreach($transactions as $t)
                        <li class="cursor-pointer p-3 border-b hover:bg-gray-100"
                            data-trans-id="{{ $t->trans_id }}">
                            <div class="font-semibold text-gray-800">{{ $t->trans_id }}</div>
                            <div class="text-xs text-gray-500">วันที่: {{ $t->trans_date }}</div>
                            <div class="text-xs text-gray-500">Ref: {{ $t->reference_no ?? '-' }}</div>
                            <div class="text-xs text-gray-500">ประเภท: {{ $t->receiveType->name ?? '-' }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- ฝั่งขวา: รายละเอียด / ฟอร์มแก้ไข --}}
            <div class="w-full lg:w-3/4 bg-white shadow rounded-lg p-4 overflow-x-auto">
                <div id="transDetail">
                    <p class="text-gray-500">เลือกเลขที่จากฝั่งซ้ายเพื่อดูรายละเอียด</p>
                </div>
            </div>
        </div>
    </div>

<script>
    const transactions = @json($transactions);
    const allProducts = @json($products);
    const allReceiveTypes = @json($receiveTypes);
    const csrfToken = '{{ csrf_token() }}';
    const listItems = document.querySelectorAll('#transList li');
    const detailDiv = document.querySelector('#transDetail');

    let currentTransId = null;

    listItems.forEach(item => {
        item.addEventListener('click', () => {
            listItems.forEach(li => li.classList.remove('bg-gray-200'));
            item.classList.add('bg-gray-200');

            const transId = item.getAttribute('data-trans-id');
            currentTransId = transId;
            showReadOnly(transId);
        });
    });

    // === แสดงแบบ Read-Only ===
    function showReadOnly(transId) {
        const trans = transactions.find(t => t.trans_id === transId);
        if (!trans) return;

        let html = `
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-semibold text-lg text-gray-800">เลขที่: ${trans.trans_id}</h3>
                    <p class="text-sm text-gray-500">วันที่: ${trans.trans_date}</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="showEditForm('${trans.trans_id}')"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm shadow">
                        แก้ไข
                    </button>
                    <form action="/transactions/${trans.trans_id}" method="POST" onsubmit="return confirm('ยืนยันการลบ? สต๊อกสินค้าจะถูกคืนค่าเดิม');">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm shadow">
                            ลบ
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 bg-gray-50 p-3 rounded-lg border">
                <div>
                    <p class="text-sm"><span class="font-medium">ประเภท:</span> ${trans.receive_type ? trans.receive_type.name : '-'}</p>
                    <p class="text-sm"><span class="font-medium">เลขที่อ้างอิง:</span> ${trans.reference_no ?? '-'}</p>
                </div>
                <div>
                    <p class="text-sm"><span class="font-medium">เอกสารอ้างอิง:</span> ${trans.reference_doc ?? '-'}</p>
                    <p class="text-sm"><span class="font-medium">หมายเหตุ:</span> ${trans.note ?? '-'}</p>
                </div>
            </div>

            <div class="overflow-x-auto border rounded-lg"><table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">รหัส</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อสินค้า</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Size</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Pack</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">รับเต็ม</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">รับเศษ</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">น้ำหนัก(kg)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
        `;

        trans.items.forEach(item => {
            html += `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-900">${item.product_id}</td>
                    <td class="px-4 py-2 text-sm text-gray-900">${item.product ? item.product.name : '-'}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">${item.product ? (item.product.size ?? '-') : '-'}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">${item.product ? (item.product.pack ?? '-') : '-'}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 text-right font-medium">${item.full_qty}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 text-right">${item.fraction_qty}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 text-right">${item.net_weight}</td>
                </tr>
            `;
        });

        html += `</tbody></table></div>`;
        detailDiv.innerHTML = html;
    }

    // === แสดงฟอร์มแก้ไข (เหมือนหน้ารับสินค้าเข้าคลัง) ===
    function showEditForm(transId) {
        const trans = transactions.find(t => t.trans_id === transId);
        if (!trans) return;

        // สร้าง options สำหรับ select ประเภทการรับเข้า
        let receiveTypeOptions = '<option value="">เลือกประเภท</option>';
        allReceiveTypes.forEach(rt => {
            const selected = (trans.receive_type_id == rt.receive_type_id) ? 'selected' : '';
            receiveTypeOptions += `<option value="${rt.receive_type_id}" ${selected}>${rt.name}</option>`;
        });

        // สร้าง options สำหรับ select สินค้า (เก็บไว้ใช้ซ้ำ)
        let productOptionsHtml = '<option value="">เลือก</option>';
        allProducts.forEach(p => {
            productOptionsHtml += `<option value="${p.product_id}" data-name="${p.name || ''}" data-pack="${p.pack || ''}" data-size="${p.size || ''}" data-weight="${p.weight_per_kg || 0}">${p.product_id}</option>`;
        });

        // สร้าง items rows
        let itemsRowsHtml = '';
        trans.items.forEach((item, idx) => {
            let productSelect = '<option value="">เลือก</option>';
            allProducts.forEach(p => {
                const sel = (item.product_id == p.product_id) ? 'selected' : '';
                productSelect += `<option value="${p.product_id}" data-name="${p.name || ''}" data-pack="${p.pack || ''}" data-size="${p.size || ''}" data-weight="${p.weight_per_kg || 0}" ${sel}>${p.product_id}</option>`;
            });

            itemsRowsHtml += `
                <tr>
                    <td class="border px-2 py-1">
                        <select name="items[${idx}][product_id]" class="w-full border rounded px-2 py-1" onchange="editUpdateProductInfo(this)">
                            ${productSelect}
                        </select>
                    </td>
                    <td class="border px-2 py-1">
                        <input type="text" class="w-full border rounded px-2 py-1 bg-gray-50 product-name" readonly value="${item.product ? (item.product.name || '') : ''}">
                    </td>
                    <td class="border px-2 py-1">
                        <input type="number" step="0.01" name="items[${idx}][net_weight]" value="${item.net_weight || 0}" class="w-full border rounded px-2 py-1">
                    </td>
                    <td class="border px-2 py-1">
                        <input type="text" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${item.product ? (item.product.size || '') : ''}">
                    </td>
                    <td class="border px-2 py-1">
                        <input type="text" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${item.product ? (item.product.pack || '') : ''}">
                    </td>
                    <td class="border px-2 py-1">
                        <input type="text" name="items[${idx}][code]" class="w-full border rounded px-2 py-1" value="${item.code || ''}">
                    </td>
                    <td class="border px-2 py-1">
                        <input type="text" name="items[${idx}][item_code]" class="w-full border rounded px-2 py-1" value="${item.item_code || ''}">
                    </td>
                    <td class="border px-2 py-1">
                        <input type="number" name="items[${idx}][full_qty]" value="${item.full_qty || 0}" class="w-full border rounded px-2 py-1" oninput="editCalcNetWeight(this)">
                    </td>
                    <td class="border px-2 py-1">
                        <input type="number" name="items[${idx}][fraction_qty]" value="${item.fraction_qty || 0}" class="w-full border rounded px-2 py-1" oninput="editCalcNetWeight(this)">
                    </td>
                    <td class="border px-2 py-1 text-center">
                        <button type="button" class="text-red-600 hover:text-red-800 font-medium" onclick="editRemoveRow(this)">ลบ</button>
                    </td>
                </tr>
            `;
        });

        // Store productOptionsHtml globally for addRow
        window._editProductOptions = productOptionsHtml;
        window._editRowIdx = trans.items.length;

        let html = `
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-semibold text-lg text-gray-800">
                    <span class="text-yellow-600">✏️</span> แก้ไขรายการ: ${trans.trans_id}
                </h3>
                <button onclick="showReadOnly('${trans.trans_id}')"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm shadow">
                    ยกเลิก
                </button>
            </div>

            <form action="/transactions/${trans.trans_id}" method="POST" id="editForm">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PUT">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">เลขที่</label>
                        <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 cursor-not-allowed" value="${trans.trans_id}" readonly>
                        <input type="hidden" name="trans_id" value="${trans.trans_id}">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">วันเข้าคลัง</label>
                        <input type="date" name="trans_date" class="w-full border rounded px-3 py-2" value="${trans.trans_date}">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">เอกสารอ้างอิง</label>
                        <input type="text" name="doc_ref" class="w-full border rounded px-3 py-2" value="${trans.reference_doc || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">เลขที่อ้างอิง</label>
                        <input type="text" name="ref_no" class="w-full border rounded px-3 py-2" value="${trans.reference_no || ''}">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">ประเภทการรับเข้า</label>
                        <select name="receive_type_id" class="w-full border rounded px-3 py-2">
                            ${receiveTypeOptions}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-medium mb-1">หมายเหตุ</label>
                        <textarea name="note" class="w-full border rounded px-3 py-2">${trans.note || ''}</textarea>
                    </div>
                </div>

                <h3 class="font-semibold text-lg mb-2">รายละเอียดสินค้า</h3>
                <div class="overflow-x-auto"><table class="w-full border mb-4" id="editItemsTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-sm">รหัส</th>
                            <th class="border px-2 py-1 text-sm">ชื่อสินค้า</th>
                            <th class="border px-2 py-1 text-sm">น้ำหนัก (ก.ก.)</th>
                            <th class="border px-2 py-1 text-sm">Size</th>
                            <th class="border px-2 py-1 text-sm">Pack</th>
                            <th class="border px-2 py-1 text-sm">Code</th>
                            <th class="border px-2 py-1 text-sm">Racking</th>
                            <th class="border px-2 py-1 text-sm">Kg/ctn</th>
                            <th class="border px-2 py-1 text-sm">Kg/Inner</th>
                            <th class="border px-2 py-1 text-sm">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsRowsHtml}
                    </tbody>
                </table></div>

                <div class="flex justify-between items-center">
                    <button type="button" onclick="editAddRow()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                        + เพิ่มสินค้า
                    </button>
                    <div class="flex gap-2">
                        <button type="button" onclick="showReadOnly('${trans.trans_id}')"
                            class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded shadow text-sm">
                            ยกเลิก
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow text-sm">
                            บันทึกการแก้ไข
                        </button>
                    </div>
                </div>
            </form>
        `;

        detailDiv.innerHTML = html;
    }

    // === ฟังก์ชันสำหรับฟอร์มแก้ไข ===
    function editAddRow() {
        const tbody = document.querySelector('#editItemsTable tbody');
        const idx = window._editRowIdx++;

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="border px-2 py-1">
                <select name="items[${idx}][product_id]" class="w-full border rounded px-2 py-1" onchange="editUpdateProductInfo(this)">
                    ${window._editProductOptions}
                </select>
            </td>
            <td class="border px-2 py-1">
                <input type="text" class="w-full border rounded px-2 py-1 bg-gray-50 product-name" readonly>
            </td>
            <td class="border px-2 py-1">
                <input type="number" step="0.01" name="items[${idx}][net_weight]" value="0" class="w-full border rounded px-2 py-1">
            </td>
            <td class="border px-2 py-1">
                <input type="text" class="w-full border rounded px-2 py-1 bg-gray-50" readonly>
            </td>
            <td class="border px-2 py-1">
                <input type="text" class="w-full border rounded px-2 py-1 bg-gray-50" readonly>
            </td>
            <td class="border px-2 py-1">
                <input type="text" name="items[${idx}][code]" class="w-full border rounded px-2 py-1">
            </td>
            <td class="border px-2 py-1">
                <input type="text" name="items[${idx}][item_code]" class="w-full border rounded px-2 py-1">
            </td>
            <td class="border px-2 py-1">
                <input type="number" name="items[${idx}][full_qty]" value="0" class="w-full border rounded px-2 py-1" oninput="editCalcNetWeight(this)">
            </td>
            <td class="border px-2 py-1">
                <input type="number" name="items[${idx}][fraction_qty]" value="0" class="w-full border rounded px-2 py-1" oninput="editCalcNetWeight(this)">
            </td>
            <td class="border px-2 py-1 text-center">
                <button type="button" class="text-red-600 hover:text-red-800 font-medium" onclick="editRemoveRow(this)">ลบ</button>
            </td>
        `;
        tbody.appendChild(newRow);
    }

    function editRemoveRow(btn) {
        const tbody = document.querySelector('#editItemsTable tbody');
        if (tbody.rows.length > 1) {
            btn.closest('tr').remove();
        }
    }

    function editUpdateProductInfo(select) {
        const option = select.options[select.selectedIndex];
        const name = option.getAttribute('data-name') || '';
        const size = option.getAttribute('data-size') || '';
        const pack = option.getAttribute('data-pack') || '';
        const weight = parseFloat(option.getAttribute('data-weight')) || 0;

        const row = select.closest('tr');
        row.querySelector('.product-name').value = name;
        const readonlyInputs = row.querySelectorAll('input[readonly]:not(.product-name)');
        if (readonlyInputs.length >= 2) {
            readonlyInputs[0].value = size;
            readonlyInputs[1].value = pack;
        }
        row.setAttribute('data-weight', weight);

        editCalcNetWeight(row.querySelector('input[name*="[full_qty]"]'));
    }

    function editCalcNetWeight(input) {
        const row = input.closest('tr');
        const fullQty = parseFloat(row.querySelector('input[name*="[full_qty]"]').value) || 0;
        const fractionQty = parseFloat(row.querySelector('input[name*="[fraction_qty]"]').value) || 0;
        const weight = parseFloat(row.getAttribute('data-weight')) || 0;
        row.querySelector('input[name*="[net_weight]"]').value = ((fullQty + fractionQty) * weight).toFixed(2);
    }
</script>
</x-app-layout>
