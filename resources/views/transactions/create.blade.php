<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">รับสินค้าเข้าคลัง</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf

                {{-- ข้อมูลหลัก --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700">เลขที่</label>
                        <input type="text" name="trans_id" class="w-full border rounded px-3 py-2" value="{{ old('trans_id') }}">
                        @error('trans_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-gray-700">วันเข้าคลัง</label>
                        <input type="date" name="trans_date" class="w-full border rounded px-3 py-2" value="{{ old('trans_date') }}">
                        @error('trans_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-gray-700">เอกสารอ้างอิง</label>
                        <input type="text" name="doc_ref" class="w-full border rounded px-3 py-2" value="{{ old('doc_ref') }}">
                        @error('doc_ref')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-gray-700">เลขที่อ้างอิง</label>
                        <input type="text" name="ref_no" class="w-full border rounded px-3 py-2" value="{{ old('ref_no') }}">
                        @error('ref_no')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-gray-700">ประเภทการรับเข้า</label>
                        <select name="receive_type_id" class="w-full border rounded px-3 py-2">
                            <option value="">เลือกประเภท</option>
                            @foreach($receiveTypes as $type)
                                <option value="{{ $type->receive_type_id }}" {{ old('receive_type_id') == $type->receive_type_id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('receive_type_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700">หมายเหตุ</label>
                        <textarea name="note" class="w-full border rounded px-3 py-2">{{ old('note') }}</textarea>
                        @error('note')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- รายการสินค้า --}}
                <h3 class="font-semibold text-lg mb-2">รายละเอียดสินค้า</h3>
                <div class="overflow-x-auto"><table class="w-full border mb-4" id="itemsTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1">รหัส</th>
                            <th class="border px-2 py-1">ชื่อสินค้า</th>
                            <th class="border px-2 py-1">น้ำหนัก (ก.ก.)</th>
                            <th class="border px-2 py-1">Size</th>
                            <th class="border px-2 py-1">Pack</th>
                            <th class="border px-2 py-1">Code</th>
                            <th class="border px-2 py-1">Racking</th>
                            <th class="border px-2 py-1">Kg/ctn</th>
                            <th class="border px-2 py-1">Kg/Inner</th>
                            <th class="border px-2 py-1">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1">
                                <select name="items[0][product_id]" 
                                        class="w-full border rounded px-2 py-1" 
                                        onchange="updateProductInfo(this)">
                                    <option value="">เลือก</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}" 
                                            data-name="{{ $product->name }}"
                                            data-pack="{{ $product->pack }}"
                                            data-size="{{ $product->size }}"
                                            data-weight="{{ $product->weight_per_kg }}">
                                            {{ $product->product_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" class="w-full border rounded px-2 py-1 bg-gray-50 product-name" readonly>
                            </td>
                            <td class="border px-2 py-1">
                                <input type="number" step="0.01" name="items[0][net_weight]" value="0"
                                    class="w-full border rounded px-2 py-1">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="items[0][size]" 
                                    class="w-full border rounded px-2 py-1" readonly>
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="items[0][pack]" 
                                    class="w-full border rounded px-2 py-1" readonly>
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="items[0][code]" class="w-full border rounded px-2 py-1">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="items[0][item_code]" class="w-full border rounded px-2 py-1">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="number" name="items[0][full_qty]" value="0" 
                                    class="w-full border rounded px-2 py-1" oninput="calcNetWeight(this)">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="number" name="items[0][fraction_qty]" value="0" 
                                    class="w-full border rounded px-2 py-1" oninput="calcNetWeight(this)">
                            </td>
                            
                            <td class="border px-2 py-1 text-center">
                                <button type="button" class="text-red-600" onclick="removeRow(this)">ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table></div>

                <button type="button" onclick="addRow()" class="mb-4 bg-blue-600 text-white px-3 py-1 rounded">+ เพิ่มสินค้า</button>

                <div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                        บันทึกการรับสินค้า
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    let rowIdx = 1;

    function addRow() {
        const tbody = document.querySelector('#itemsTable tbody');
        const newRow = tbody.rows[0].cloneNode(true);

        newRow.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\d+/, rowIdx);
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            if (el.type === 'text' || el.type === 'number') el.value = (el.readOnly) ? '' : 0;
        });

        newRow.removeAttribute('data-weight');

        tbody.appendChild(newRow);
        rowIdx++;
    }

    function removeRow(btn) {
        const tbody = document.querySelector('#itemsTable tbody');
        if (tbody.rows.length > 1) btn.closest('tr').remove();
    }

    function updateProductInfo(select) {
        const option = select.options[select.selectedIndex];
        const name = option.getAttribute('data-name') || '';
        const size = option.getAttribute('data-size') || '';
        const pack = option.getAttribute('data-pack') || '';
        const weight = parseFloat(option.getAttribute('data-weight')) || 0;

        const row = select.closest('tr');
        row.querySelector('.product-name').value = name;
        row.querySelector('input[name*="[size]"]').value = size;
        row.querySelector('input[name*="[pack]"]').value = pack;
        row.setAttribute('data-weight', weight);

        calcNetWeight(row.querySelector('input[name*="[full_qty]"]'));
    }

    function calcNetWeight(input) {
        const row = input.closest('tr');
        const fullQty = parseFloat(row.querySelector('input[name*="[full_qty]"]').value) || 0;
        const fractionQty = parseFloat(row.querySelector('input[name*="[fraction_qty]"]').value) || 0;
        const weight = parseFloat(row.getAttribute('data-weight')) || 0;
        row.querySelector('input[name*="[net_weight]"]').value = ((fullQty + fractionQty) * weight).toFixed(2);
    }
</script>
</x-app-layout>
