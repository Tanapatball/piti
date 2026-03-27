<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขประเภทการเบิก</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <form action="{{ route('issue-types.update', $issueType) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700">รหัสประเภท</label>
                    <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ $issueType->issue_type_id }}" disabled>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">ชื่อประเภท</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2"
                        value="{{ old('name', $issueType->name) }}" required>
                    @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                        บันทึกการแก้ไข
                    </button>
                    <a href="{{ route('issue-types.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded shadow">
                        ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
