<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">เบิกสินค้า</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc pl-6">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- เลือกเลขที่ (Transaction) --}}
            <div class="mb-4">
                <label class="block text-gray-700">เลือกเลขที่อ้างอิง</label>
                <select id="transSelect" class="w-full border rounded px-3 py-2">
                    <option value="">-- เลือกเลขที่ --</option>
                    @foreach($transactions as $t)
                        <option value="{{ $t->trans_id }}"
                                data-items='@json($t->items)'>
                            {{ $t->trans_id }} ({{ $t->trans_date }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ฟอร์มเบิกสินค้า --}}
            <form action="{{ route('stock-outs.store') }}" method="POST" id="stockOutForm">
                @csrf
                <input type="hidden" name="trans_id" id="trans_id">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700">ประเภทการเบิก</label>
                        <select name="issue_type_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- เลือกประเภท --</option>
                            @foreach($issueTypes as $type)
                                <option value="{{ $type->issue_type_id }}" {{ old('issue_type_id') == $type->issue_type_id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700">เอกสารอ้างอิง</label>
                        <input type="text" name="reference_doc" class="w-full border rounded px-3 py-2"
                            value="{{ old('reference_doc') }}">
                    </div>

                    <div>
                        <label class="block text-gray-700">เลขที่อ้างอิง</label>
                        <input type="text" name="reference_no" class="w-full border rounded px-3 py-2"
                            value="{{ old('reference_no') }}">
                    </div>

                    <div>
                        <label class="block text-gray-700">วันที่เบิก</label>
                        <input type="date" name="issued_date" class="w-full border rounded px-3 py-2"
                               value="{{ old('issued_date', now()->toDateString()) }}">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-gray-700">หมายเหตุ</label>
                        <textarea name="note" class="w-full border rounded px-3 py-2">{{ old('note') }}</textarea>
                    </div>
                </div>

                <h3 class="font-semibold text-lg mb-2">รายการสินค้า</h3>
                <div class="overflow-x-auto"><table class="w-full border mb-4" id="itemsTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1">รหัส - ชื่อสินค้า</th>
                            <th class="border px-2 py-1">Size</th>
                            <th class="border px-2 py-1">Pack</th>
                            <th class="border px-2 py-1">น้ำหนักรวม (kg)</th>
                            <th class="border px-2 py-1">Code</th>
                            <th class="border px-2 py-1">Kg/ctn (คงเหลือ)</th>
                            <th class="border px-2 py-1">Kg/Inner (คงเหลือ)</th>
                            <th class="border px-2 py-1">เบิกเต็ม</th>
                            <th class="border px-2 py-1">เบิกเศษ</th>
                            <th class="border px-2 py-1">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- แถวตัวอย่าง จะถูก clone --}}
                        <tr>
                            <td class="border px-2 py-1">
                                <input type="text" name="items[0][product_name]" class="w-full border rounded px-2 py-1" readonly>
                                <input type="hidden" name="items[0][product_id]">
                                <input type="hidden" name="items[0][weight_per_unit]">
                            </td>
                            <td class="border px-2 py-1"><input type="text" name="items[0][size]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
                            <td class="border px-2 py-1"><input type="text" name="items[0][pack]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
                            <td class="border px-2 py-1"><input type="number" name="items[0][net_weight]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
                            <td class="border px-2 py-1">
                                <input type="text" name="items[0][code]" class="w-full border rounded px-2 py-1">
                            </td>
                            <td class="border px-2 py-1"><input type="number" name="items[0][full_stock]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
                            <td class="border px-2 py-1"><input type="number" name="items[0][fraction_stock]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
                            <td class="border px-2 py-1"><input type="number" name="items[0][quantity]" value="0" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
                            <td class="border px-2 py-1"><input type="number" name="items[0][fraction_qty]" value="0" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
                            <td class="border px-2 py-1 text-center">
                                <button type="button" class="text-red-600" onclick="removeRow(this)">ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table></div>

                <button type="button" onclick="addRow()" class="mb-4 bg-blue-600 text-white px-3 py-1 rounded">+ เพิ่มสินค้า</button>

                <div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                        บันทึกการเบิก
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    let rowIdx = 1;

    const transSelect = document.getElementById('transSelect');

    transSelect.addEventListener('change', function(){
        const selectedTrans = this.value;
        console.log('Selected trans:', selectedTrans);
        document.getElementById('trans_id').value = selectedTrans;
        const items = this.selectedOptions[0]?.dataset.items;
        const tbody = document.querySelector('#itemsTable tbody');
        tbody.innerHTML = ''; // ลบแถวเดิม

        if(!items) return;

        const parsedItems = JSON.parse(items);

        parsedItems.forEach((item, idx) => {
            const productCode = item.product?.product_id || '';
            const productName = item.product?.name || '';
            const displayName = productCode + ' - ' + productName;

            const newRow = tbody.insertRow();
            newRow.innerHTML = `
                <td class="border px-2 py-1">
                    <input type="text" name="items[${idx}][product_name]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${displayName}">
                    <input type="hidden" name="items[${idx}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${idx}][weight_per_unit]" value="${item.product?.weight_per_kg || 0}">
                </td>
                <td class="border px-2 py-1"><input type="text" name="items[${idx}][size]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${item.product?.size || ''}"></td>
                <td class="border px-2 py-1"><input type="text" name="items[${idx}][pack]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${item.product?.pack || ''}"></td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][net_weight]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="0"></td>
                <td class="border px-2 py-1">
                    <input type="text" name="items[${idx}][code]" class="w-full border rounded px-2 py-1 code-input" value="${item.code || ''}" data-product-id="${item.product_id}" onblur="checkCodeExists(this)">
                    <div class="code-status text-xs mt-1"></div>
                </td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][full_stock]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${item.full_qty}"></td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][fraction_stock]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${item.fraction_qty}"></td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][quantity]" value="0" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][fraction_qty]" value="0" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
                <td class="border px-2 py-1 text-center">
                    <button type="button" class="text-red-600" onclick="removeRow(this)">ลบ</button>
                </td>
            `;
        });
    });

    function addRow() {
        alert('กรุณาเลือกเลขที่ก่อนเพิ่มสินค้า');
    }

    function removeRow(btn) {
        const tbody = document.querySelector('#itemsTable tbody');
        if(tbody.rows.length > 1) btn.closest('tr').remove();
    }

    function calcWeight(input){
        const row = input.closest('tr');
        const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const fraction = parseFloat(row.querySelector('input[name*="[fraction_qty]"]').value) || 0;
        const weight = parseFloat(row.querySelector('input[name*="[weight_per_unit]"]').value) || 0;
        const fullStock = parseFloat(row.querySelector('input[name*="[full_stock]"]').value) || 0;
        const fractionStock = parseFloat(row.querySelector('input[name*="[fraction_stock]"]').value) || 0;

        // ถ้าเบิกเกินคงเหลือ ให้ปรับเป็นคงเหลือสูงสุด
        if(quantity > fullStock) row.querySelector('input[name*="[quantity]"]').value = fullStock;
        if(fraction > fractionStock) row.querySelector('input[name*="[fraction_qty]"]').value = fractionStock;

        row.querySelector('input[name*="[net_weight]"]').value = ((quantity + fraction) * weight).toFixed(2);
    }

    // ตรวจสอบ code ว่ามีอยู่จริงหรือไม่
    async function checkCodeExists(input) {
        const code = input.value.trim();
        const productId = input.dataset.productId;
        const statusDiv = input.parentElement.querySelector('.code-status');

        if (!code) {
            statusDiv.innerHTML = '';
            input.classList.remove('border-red-500', 'border-green-500');
            return;
        }

        try {
            const response = await fetch('{{ route("stock-outs.check-code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code, product_id: productId })
            });

            const data = await response.json();

            if (data.exists) {
                statusDiv.innerHTML = `<span class="text-green-600">✓ ${data.message}</span>`;
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
            } else {
                statusDiv.innerHTML = `<span class="text-red-600">✗ ${data.message}</span>`;
                input.classList.remove('border-green-500');
                input.classList.add('border-red-500');
            }
        } catch (error) {
            console.error('Error checking code:', error);
            statusDiv.innerHTML = '<span class="text-yellow-600">⚠ ไม่สามารถตรวจสอบได้</span>';
        }
    }

    // ตรวจสอบก่อน submit
    document.getElementById('stockOutForm').addEventListener('submit', function(e) {
        const codeInputs = document.querySelectorAll('.code-input');
        let hasInvalidCode = false;

        codeInputs.forEach(input => {
            if (input.classList.contains('border-red-500')) {
                hasInvalidCode = true;
            }
        });

        if (hasInvalidCode) {
            e.preventDefault();
            alert('กรุณาตรวจสอบ Code ที่ไม่ถูกต้องก่อนบันทึก');
            return false;
        }
    });
</script>
</x-app-layout>
