<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">แก้ไขผู้ใช้</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">ชื่อ</label>
                        <input type="text" name="fname" class="border rounded w-full p-2 mt-1"
                            value="{{ old('fname', $user->fname) }}" required>
                        @error('fname')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">นามสกุล</label>
                        <input type="text" name="lname" class="border rounded w-full p-2 mt-1"
                            value="{{ old('lname', $user->lname) }}" required>
                        @error('lname')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Email</label>
                        <input type="email" name="email" class="border rounded w-full p-2 mt-1"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">เบอร์โทร</label>
                        <input type="text" name="phone" class="border rounded w-full p-2 mt-1"
                            value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Role</label>
                        <select name="role_id" class="border rounded w-full p-2 mt-1" required>
                            <option value="">-- เลือก Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->role_id }}" {{ old('role_id', $user->role_id) == $role->role_id ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                        บันทึกการแก้ไข
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
