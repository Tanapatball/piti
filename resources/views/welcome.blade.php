<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ระบบจัดการข้อมูลสินค้าคงคลังออนไลน์</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 to-indigo-50 min-h-screen flex flex-col">

    <!-- Header / Navbar -->
    <header class="w-full bg-gradient-to-r from-indigo-700 to-indigo-900 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xl hidden sm:block">WMS</span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-white text-indigo-700 font-medium rounded-lg hover:bg-indigo-50 transition shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-white font-medium hover:bg-white/10 rounded-lg transition">
                            เข้าสู่ระบบ
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-white text-indigo-700 font-medium rounded-lg hover:bg-indigo-50 transition shadow-sm">
                                สมัครสมาชิก
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1 flex items-center">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-24">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <!-- Text Section -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ระบบจัดการคลังสินค้าครบวงจร
                    </div>

                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        ระบบสารสนเทศ<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-800">จัดการสินค้าคงคลัง</span>
                    </h1>

                    <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0">
                        สำหรับไลน์ผลิต ติดตามการรับ-เบิกสินค้า ตรวจสอบคลังและรายงานสถานะสินค้าคงคลังแบบเรียลไทม์ รองรับผู้ใช้งานหลายระดับ
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition shadow-lg shadow-indigo-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                            </svg>
                            เริ่มใช้งาน
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-indigo-700 font-semibold rounded-xl border-2 border-indigo-200 hover:border-indigo-300 hover:bg-indigo-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                                </svg>
                                สมัครสมาชิก
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Features Section -->
            <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">จัดการสินค้า</h3>
                    <p class="text-gray-600 text-sm">บันทึกข้อมูลสินค้า หมวดหมู่ และติดตามสต็อกแบบเรียลไทม์</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">รับ-เบิกสินค้า</h3>
                    <p class="text-gray-600 text-sm">บันทึกการรับสินค้าเข้าคลังและเบิกสินค้าออกได้สะดวก</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">รายงานครบถ้วน</h3>
                    <p class="text-gray-600 text-sm">รายงานสต็อก สต็อกการ์ด และส่งออก PDF ได้ทันที</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} ระบบจัดการข้อมูลสินค้าคงคลังออนไลน์ สงวนลิขสิทธิ์
        </div>
    </footer>
</body>
</html>
