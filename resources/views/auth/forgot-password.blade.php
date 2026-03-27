<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        ลืมรหัสผ่าน? ไม่มีปัญหา กรุณากรอกอีเมลของคุณ แล้วเราจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ให้ทางอีเมล
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="อีเมล" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                ส่งลิงก์รีเซ็ตรหัสผ่าน
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
