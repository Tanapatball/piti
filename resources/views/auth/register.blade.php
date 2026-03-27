<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">สมัครสมาชิก</h2>
        <p class="text-gray-500 mt-1">สร้างบัญชีเพื่อเข้าใช้งานระบบ</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- ชื่อ-นามสกุล -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="fname" value="ชื่อ" class="text-gray-700 font-medium" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <x-text-input id="fname" class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl" type="text" name="fname" :value="old('fname')" required autofocus placeholder="ชื่อ" />
                </div>
                <x-input-error :messages="$errors->get('fname')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="lname" value="นามสกุล" class="text-gray-700 font-medium" />
                <x-text-input id="lname" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl" type="text" name="lname" :value="old('lname')" required placeholder="นามสกุล" />
                <x-input-error :messages="$errors->get('lname')" class="mt-2" />
            </div>
        </div>

        <!-- อีเมล -->
        <div class="mt-4">
            <x-input-label for="email" value="อีเมล" class="text-gray-700 font-medium" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl" type="email" name="email" :value="old('email')" required placeholder="your@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- เบอร์โทรศัพท์ -->
        <div class="mt-4">
            <x-input-label for="phone" value="เบอร์โทรศัพท์" class="text-gray-700 font-medium" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                    </svg>
                </div>
                <x-text-input id="phone" class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl" type="text" name="phone" :value="old('phone')" required placeholder="0812345678" />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- รหัสผ่าน -->
        <div class="mt-4">
            <x-input-label for="password" value="รหัสผ่าน" class="text-gray-700 font-medium" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                </div>
                <x-text-input id="password" class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl" type="password" name="password" required placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- ยืนยันรหัสผ่าน -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="ยืนยันรหัสผ่าน" class="text-gray-700 font-medium" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <x-text-input id="password_confirmation" class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl" type="password" name="password_confirmation" required placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-lg shadow-indigo-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                </svg>
                สมัครสมาชิก
            </button>
        </div>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
                มีบัญชีอยู่แล้ว?
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">เข้าสู่ระบบ</a>
            </p>
        </div>
    </form>
</x-guest-layout>
