<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขการเบิกสินค้า</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
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
                <label class="block text-gray-700">เลขที่อ้างอิง</label>
                <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100 cursor-not-allowed" value="{{ $stockOut->trans_id }}" readonly>
                <select id="transSelect" class="hidden">
                    @foreach($transactions as $t)
                        <option value="{{ $t->trans_id }}"
                                data-items='@json($t->items)'
                                {{ $stockOut->trans_id == $t->trans_id ? 'selected' : '' }}>
                            {{ $t->trans_id }} ({{ $t->trans_date }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ฟอร์มแก้ไขการเบิก --}}
            <form action="{{ route('stock-outs.update', $stockOut->id) }}" method="POST" id="stockOutForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="trans_id" id="trans_id" value="{{ $stockOut->trans_id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700">ประเภทการเบิก</label>
                        <select name="issue_type_id" class="w-full border rounded px-3 py-2">
                            <option value="">-- เลือกประเภท --</option>
                            @foreach($issueTypes as $type)
                                <option value="{{ $type->issue_type_id }}"
                                    {{ old('issue_type_id', $stockOut->issue_type_id) == $type->issue_type_id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700">เอกสารอ้างอิง</label>
                        <input type="text" name="reference_doc" class="w-full border rounded px-3 py-2"
                            value="{{ old('reference_doc', $stockOut->reference_doc) }}">
                    </div>

                    <div>
                        <label class="block text-gray-700">เลขที่อ้างอิง</label>
                        <input type="text" name="reference_no" class="w-full border rounded px-3 py-2"
                            value="{{ old('reference_no', $stockOut->reference_no) }}">
                    </div>

                    <div>
                        <label class="block text-gray-700">วันที่เบิก</label>
                        <input type="date" name="issued_date" class="w-full border rounded px-3 py-2"
                               value="{{ old('issued_date', $stockOut->issued_date) }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700">หมายเหตุ</label>
                        <textarea name="note" class="w-full border rounded px-3 py-2">{{ old('note', $stockOut->note) }}</textarea>
                    </div>
                </div>

                <h3 class="font-semibold text-lg mb-2">รายการสินค้า</h3>
                <div class="overflow-x-auto"><table class="w-full border mb-4" id="itemsTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1">สินค้า</th>
                            <th class="border px-2 py-1">Code</th>
                            <th class="border px-2 py-1">Kg/ctn (คงเหลือ)</th>
                            <th class="border px-2 py-1">Kg/Inner (คงเหลือ)</th>
                            <th class="border px-2 py-1">เบิกเต็ม</th>
                            <th class="border px-2 py-1">เบิกเศษ</th>
                            <th class="border px-2 py-1">Size</th>
                            <th class="border px-2 py-1">Pack</th>
                            <th class="border px-2 py-1">น้ำหนักรวม (kg)</th>
                            <th class="border px-2 py-1">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- JS will populate this --}}
                    </tbody>
                </table></div>

                <button type="button" onclick="addNewRow()" class="mb-4 bg-blue-600 text-white px-3 py-1 rounded">+ เพิ่มสินค้า</button>

                <div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
                        บันทึกการแก้ไข
                    </button>
                    <a href="{{ route('stock-outs.index') }}" class="ml-2 text-gray-600 hover:underline">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>

<script>
    // Prepare existing data
    const existingSiblings = @json($siblings);
    const originalTransId = '{{ $stockOut->trans_id }}';

    // Create a map of TransactionItems for selected trans_id
    let transactionItems = [];
    let transMap = {};

    const transSelect = document.getElementById('transSelect');
    const tbody = document.querySelector('#itemsTable tbody');
    let rowIdx = 0;
    let isInitialLoad = true;

    // Initialize: Load items from selected transaction
    function initTransactionItems() {
        const selectedOption = transSelect.selectedOptions[0];
        if (selectedOption && selectedOption.value) {
            const items = selectedOption.dataset.items;
            if (items) {
                transactionItems = JSON.parse(items);
                transMap = {};
                transactionItems.forEach(item => {
                    const key = item.product_id + '_' + (item.code || '');
                    transMap[key] = {
                        full_qty: item.full_qty,
                        fraction_qty: item.fraction_qty,
                        product_name: item.product?.name,
                        weight: item.product?.weight_per_kg || 0,
                        size: item.product?.size,
                        pack: item.product?.pack
                    };
                });
            }
        }
    }

    // Render existing rows (for initial load when editing same transaction)
    function renderExistingRows() {
        tbody.innerHTML = '';
        rowIdx = 0;

        existingSiblings.forEach(sibling => {
            const key = sibling.product_id + '_' + (sibling.code || '');
            const baseInfo = transMap[key] || {};

            // "Available" for this row = Current Unused + Quantity currently held by this row
            const availFull = (baseInfo.full_qty || 0) + sibling.quantity;
            const availFraction = (baseInfo.fraction_qty || 0) + (sibling.fraction_qty || 0);

            const tr = document.createElement('tr');
            tr.innerHTML = getExistingRowHtml(rowIdx, sibling, baseInfo, availFull, availFraction);
            tbody.appendChild(tr);

            calcWeight(tr.querySelector('input[name*="[quantity]"]'));
            rowIdx++;
        });
    }

    // Render rows from new transaction selection
    function renderNewTransactionRows() {
        tbody.innerHTML = '';
        rowIdx = 0;

        transactionItems.forEach((item, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="border px-2 py-1">
                    <input type="text" name="items[${idx}][product_name]" class="w-full border rounded px-2 py-1" readonly value="${item.product?.name || ''}">
                    <input type="hidden" name="items[${idx}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${idx}][weight_per_unit]" value="${item.product?.weight_per_kg || 0}">
                </td>
                <td class="border px-2 py-1">
                    <input type="text" name="items[${idx}][code]" class="w-full border rounded px-2 py-1 code-input" value="${item.code || ''}" data-product-id="${item.product_id}" onblur="checkCodeExists(this)">
                    <div class="code-status text-xs mt-1"></div>
                </td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][full_stock]" class="w-full border rounded px-2 py-1" readonly value="${item.full_qty}"></td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][fraction_stock]" class="w-full border rounded px-2 py-1" readonly value="${item.fraction_qty}"></td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][quantity]" value="0" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][fraction_qty]" value="0" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
                <td class="border px-2 py-1"><input type="text" name="items[${idx}][size]" class="w-full border rounded px-2 py-1" readonly value="${item.product?.size || ''}"></td>
                <td class="border px-2 py-1"><input type="text" name="items[${idx}][pack]" class="w-full border rounded px-2 py-1" readonly value="${item.product?.pack || ''}"></td>
                <td class="border px-2 py-1"><input type="number" name="items[${idx}][net_weight]" class="w-full border rounded px-2 py-1" readonly value="0"></td>
                <td class="border px-2 py-1 text-center">
                    <button type="button" class="text-red-600" onclick="removeRow(this)">ลบ</button>
                </td>
            `;
            tbody.appendChild(tr);
            rowIdx++;
        });
    }

    function getExistingRowHtml(idx, data, baseInfo, availFull, availFraction) {
        const prodName = data.product?.name || baseInfo.product_name || '-';
        const weight = data.product?.weight_per_kg || baseInfo.weight || 0;
        const size = data.product?.size || baseInfo.size || '';
        const pack = data.product?.pack || baseInfo.pack || '';
        const code = data.code || '';
        const prodId = data.product_id || '';
        const qty = data.quantity || 0;
        const frac = data.fraction_qty || 0;
        const id = data.id || '';

        return `
            <td class="border px-2 py-1">
                <input type="text" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${prodName}">
                <input type="hidden" name="items[${idx}][product_id]" value="${prodId}">
                <input type="hidden" name="items[${idx}][weight_per_unit]" value="${weight}">
                <input type="hidden" name="items[${idx}][id]" value="${id}">
            </td>
            <td class="border px-2 py-1">
                <input type="text" name="items[${idx}][code]" class="w-full border rounded px-2 py-1 code-input" value="${code}" data-product-id="${prodId}" onblur="checkCodeExists(this)">
                <div class="code-status text-xs mt-1"></div>
            </td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][full_stock]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${availFull}"></td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][fraction_stock]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${availFraction}"></td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][quantity]" value="${qty}" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][fraction_qty]" value="${frac}" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
            <td class="border px-2 py-1"><input type="text" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${size}"></td>
            <td class="border px-2 py-1"><input type="text" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="${pack}"></td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][net_weight]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly value="0"></td>
            <td class="border px-2 py-1 text-center">
                <button type="button" class="text-red-600" onclick="removeRow(this)">ลบ</button>
            </td>
        `;
    }

    // Transaction select is now disabled - no change event needed
    // Keep the original transaction items loaded for reference

    // Add new row from available items
    function addNewRow() {
        if (!transactionItems || transactionItems.length === 0) {
            alert('กรุณาเลือกเลขที่อ้างอิงก่อนเพิ่มสินค้า');
            return;
        }

        const idx = rowIdx++;

        let options = '<option value="">-- เลือกสินค้า --</option>';
        transactionItems.forEach(item => {
            if (item.full_qty > 0 || item.fraction_qty > 0) {
                const val = item.product_id + '||' + (item.code || '');
                const label = (item.product?.name || item.product_id) + (item.code ? ' (' + item.code + ')' : '');
                options += `<option value="${val}"
                    data-name="${item.product?.name || ''}"
                    data-weight="${item.product?.weight_per_kg || 0}"
                    data-full="${item.full_qty}"
                    data-frac="${item.fraction_qty}"
                    data-size="${item.product?.size || ''}"
                    data-pack="${item.product?.pack || ''}"
                >${label}</option>`;
            }
        });

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="border px-2 py-1" colspan="2">
                <select class="w-full border rounded px-2 py-1" onchange="selectProduct(this, ${idx})">
                    ${options}
                </select>
                <input type="hidden" name="items[${idx}][product_id]">
                <input type="hidden" name="items[${idx}][code]">
                <input type="hidden" name="items[${idx}][weight_per_unit]">
            </td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][full_stock]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][fraction_stock]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][quantity]" value="0" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][fraction_qty]" value="0" class="w-full border rounded px-2 py-1" min="0" oninput="calcWeight(this)"></td>
            <td class="border px-2 py-1"><input type="text" name="items[${idx}][size]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
            <td class="border px-2 py-1"><input type="text" name="items[${idx}][pack]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
            <td class="border px-2 py-1"><input type="number" name="items[${idx}][net_weight]" class="w-full border rounded px-2 py-1 bg-gray-50" readonly></td>
            <td class="border px-2 py-1 text-center">
                <button type="button" class="text-red-600" onclick="removeRow(this)">ลบ</button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function selectProduct(select, idx) {
        const option = select.selectedOptions[0];
        if (!option.value) return;

        const parts = option.value.split('||');
        const prodId = parts[0];
        const code = parts[1] || '';

        const row = select.closest('tr');
        row.querySelector(`input[name="items[${idx}][product_id]"]`).value = prodId;
        row.querySelector(`input[name="items[${idx}][code]"]`).value = code;
        row.querySelector(`input[name="items[${idx}][weight_per_unit]"]`).value = option.dataset.weight;

        row.querySelector(`input[name="items[${idx}][full_stock]"]`).value = option.dataset.full;
        row.querySelector(`input[name="items[${idx}][fraction_stock]"]`).value = option.dataset.frac;

        row.querySelector(`input[name="items[${idx}][size]"]`).value = option.dataset.size;
        row.querySelector(`input[name="items[${idx}][pack]"]`).value = option.dataset.pack;
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        if (tbody.rows.length > 1) {
            row.remove();
        } else {
            alert('ต้องมีอย่างน้อย 1 รายการ');
        }
    }

    function calcWeight(input) {
        const row = input.closest('tr');
        if (!row) return;

        const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const fraction = parseFloat(row.querySelector('input[name*="[fraction_qty]"]').value) || 0;
        const weight = parseFloat(row.querySelector('input[name*="[weight_per_unit]"]').value) || 0;
        const fullStock = parseFloat(row.querySelector('input[name*="[full_stock]"]').value) || 0;
        const fractionStock = parseFloat(row.querySelector('input[name*="[fraction_stock]"]').value) || 0;

        // Limit to available stock
        if (quantity > fullStock) {
            row.querySelector('input[name*="[quantity]"]').value = fullStock;
        }
        if (fraction > fractionStock) {
            row.querySelector('input[name*="[fraction_qty]"]').value = fractionStock;
        }

        const finalQty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const finalFrac = parseFloat(row.querySelector('input[name*="[fraction_qty]"]').value) || 0;
        row.querySelector('input[name*="[net_weight]"]').value = ((finalQty + finalFrac) * weight).toFixed(2);
    }

    // Check code exists
    async function checkCodeExists(input) {
        const code = input.value.trim();
        const productId = input.dataset.productId;
        const statusDiv = input.parentElement.querySelector('.code-status');

        if (!code || !statusDiv) {
            if (statusDiv) statusDiv.innerHTML = '';
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

    // Form validation before submit
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

    // Initialize on page load
    initTransactionItems();
    renderExistingRows();
</script>
</x-app-layout>
