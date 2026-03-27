<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขประเภทการรับเข้า</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <form action="{{ route('receive-types.update', $receiveType) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700">ชื่อประเภท</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2"
                        value="{{ old('name', $receiveType->name) }}">
                    @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                    บันทึกการแก้ไข
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
