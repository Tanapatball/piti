<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">สินค้า</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-2 flex-1">
                    <div class="relative flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหารหัส, ชื่อสินค้า..."
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <select name="category" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- ทุกหมวดหมู่ --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}" {{ request('category') == $cat->category_id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">ค้นหา</button>
                    @if(request('search') || request('category'))
                    <a href="{{ route('products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium text-center">ล้าง</a>
                    @endif
                </form>
                @if(!Auth::user()->isUser())
                <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow text-sm text-center">
                    เพิ่มสินค้า
                </a>
                @endif
            </div>

            @if(request('search') || request('category'))
            <div class="mb-3 text-sm text-gray-500">
                พบ {{ $products->count() }} รายการ
                @if(request('search')) | ค้นหา: "{{ request('search') }}" @endif
                @if(request('category')) | หมวด: {{ $categories->firstWhere('category_id', request('category'))->category_name ?? '' }} @endif
            </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ชื่อสินค้า</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">หมวดหมู่</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock Min</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock Max</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CTN คงเหลือ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inner คงเหลือ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ขนาด</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pack</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">น้ำหนักต่อกิโล</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">น้ำหนักรวม</th>
                            @if(!Auth::user()->isUser())
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $p)
                        @php
                            $isLow = $p->stock_min > 0 && $p->ctn_remaining < $p->stock_min;
                            $isOver = $p->stock_max > 0 && $p->ctn_remaining > $p->stock_max;
                        @endphp
                        <tr class="{{ $isLow ? 'bg-red-50 hover:bg-red-100' : ($isOver ? 'bg-orange-50 hover:bg-orange-100' : 'hover:bg-gray-50') }}">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->product_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $p->name }}
                                @if($isLow)
                                    <span class="ml-1 inline-flex items-center bg-red-100 text-red-700 text-xs font-bold px-1.5 py-0.5 rounded-full">ต่ำ</span>
                                @elseif($isOver)
                                    <span class="ml-1 inline-flex items-center bg-orange-100 text-orange-700 text-xs font-bold px-1.5 py-0.5 rounded-full">เกิน</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->category->category_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->stock_min }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->stock_max }}</td>
                            <td class="px-6 py-4 text-sm font-bold {{ $isLow ? 'text-red-600' : ($isOver ? 'text-orange-600' : 'text-gray-900') }}">{{ $p->ctn_remaining }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->inner_remaining }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->size ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->pack ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->weight_per_kg ?? 0 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $p->weight_total ?? 0 }}</td>
                            @if(!Auth::user()->isUser())
                            <td class="px-6 py-4 text-sm font-medium flex gap-2">
                                <a href="{{ route('products.edit', $p) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('products.destroy', $p) }}" method="POST" onsubmit="return confirm('ลบสินค้านี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>

                @if($products->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        @if(request('search') || request('category'))
                            ไม่พบสินค้าที่ตรงกับเงื่อนไข
                        @else
                            ไม่มีข้อมูลสินค้า
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
