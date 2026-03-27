<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\ReceiveTypeController;
use App\Http\Controllers\IssueTypeController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\StockAlertSettingController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\NotificationController;

// หน้า Welcome
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (ต้อง login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// ระบบจัดการ (ต้อง login)
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users (เฉพาะ admin)
    Route::resource('users', UserController::class)->middleware('role:admin');

    // Products (ดูได้ทุก role, แก้ไขเฉพาะ admin/staff)
    Route::resource('products', ProductController::class)->except(['index', 'show'])->middleware('role:admin,staff');
    Route::resource('products', ProductController::class)->only(['index', 'show']);

    // Categories (เฉพาะ admin/staff)
    // ใช้ explicit routes แทน resource เพราะ category_id อาจมี "/" อยู่
    Route::controller(CategoryController::class)->middleware('role:admin,staff')->group(function () {
        Route::get('categories', 'index')->name('categories.index');
        Route::get('categories/create', 'create')->name('categories.create');
        Route::post('categories', 'store')->name('categories.store');
        Route::get('categories/{category}/edit', 'edit')->name('categories.edit')->where('category', '.*');
        Route::put('categories/{category}', 'update')->name('categories.update')->where('category', '.*');
        Route::delete('categories/{category}', 'destroy')->name('categories.destroy')->where('category', '.*');
    });

    // Warehouses (เฉพาะ admin/staff)
    Route::resource('warehouses', WarehouseController::class)->middleware('role:admin,staff');

    // Transactions (รับสินค้า — เฉพาะ admin/staff)
    Route::resource('transactions', TransactionController::class)->middleware('role:admin,staff');

    // Stock Outs (เบิกสินค้า — เฉพาะ admin/staff)
    Route::resource('stock-outs', StockOutController::class)->parameters([
        'stock-outs' => 'stockOut',
    ])->middleware('role:admin,staff');

    // API ตรวจสอบ code สำหรับเบิกสินค้า
    Route::post('/stock-outs/check-code', [StockOutController::class, 'checkCode'])->middleware('role:admin,staff')->name('stock-outs.check-code');

    // Receive Types (ประเภทการรับเข้า — เฉพาะ admin/staff)
    Route::resource('receive-types', ReceiveTypeController::class)->middleware('role:admin,staff');

    // Issue Types (ประเภทการเบิก — เฉพาะ admin/staff)
    Route::resource('issue-types', IssueTypeController::class)->middleware('role:admin,staff');

    // Reports (รายงาน)
    Route::prefix('reports')->group(function () {
        // Legacy routes
        Route::get('/received', [ReportController::class, 'receivedReport'])->name('reports.received');
        Route::get('/issued', [ReportController::class, 'issuedReport'])->name('reports.issued');
        Route::get('/transactions', [ReportController::class, 'transactionsForm'])->name('reports.transactions.form');
        Route::post('/transactions/pdf', [ReportController::class, 'transactionsPdf'])->name('reports.transactions.pdf');

        // 1. เมนูหลัก
        Route::get('/main/receive-types', [ReportController::class, 'mainReceiveTypes'])->name('reports.main.receive-types');
        Route::get('/main/categories', [ReportController::class, 'mainCategories'])->name('reports.main.categories');
        Route::get('/main/products', [ReportController::class, 'mainProducts'])->name('reports.main.products');

        // 2. รายละเอียดสินค้า
        Route::get('/products/by-category', [ReportController::class, 'productsByCategory'])->name('reports.products.by-category');
        Route::get('/products/by-date', [ReportController::class, 'productsByDate'])->name('reports.products.by-date');
        Route::get('/products/all', [ReportController::class, 'productsAll'])->name('reports.products.all');
        Route::get('/products/by-size', [ReportController::class, 'productsBySize'])->name('reports.products.by-size');
        Route::get('/products/by-pack', [ReportController::class, 'productsByPack'])->name('reports.products.by-pack');

        // 3. รายงานรับสินค้าเข้าคลัง
        Route::get('/received/check', [ReportController::class, 'receivedCheck'])->name('reports.received.check');
        Route::get('/received/by-product', [ReportController::class, 'receivedByProduct'])->name('reports.received.by-product');
        Route::get('/received/by-type', [ReportController::class, 'receivedByType'])->name('reports.received.by-type');

        // 4. รายงานเบิกสินค้าจากคลัง
        Route::get('/issued/check', [ReportController::class, 'issuedCheck'])->name('reports.issued.check');
        Route::get('/issued/by-product', [ReportController::class, 'issuedByProduct'])->name('reports.issued.by-product');
        Route::get('/issued/by-type', [ReportController::class, 'issuedByType'])->name('reports.issued.by-type');

        // 5-6. สินค้าคงเหลือ ณ ปัจจุบัน
        Route::get('/stock-remaining-size', [ReportController::class, 'stockRemainingSize'])->name('reports.stock-remaining-size');
        Route::get('/stock-remaining-pack', [ReportController::class, 'stockRemainingPack'])->name('reports.stock-remaining-pack');

        // 7-8. สต็อกการ์ด
        Route::get('/stock-card-by-id', [ReportController::class, 'stockCardById'])->name('reports.stock-card-by-id');
        Route::get('/stock-card-by-code', [ReportController::class, 'stockCardByCode'])->name('reports.stock-card-by-code');

        // 9-11. สินค้าคงเหลือ
        Route::get('/stock-by-product', [ReportController::class, 'stockByProduct'])->name('reports.stock-by-product');
        Route::get('/stock-quantity', [ReportController::class, 'stockQuantity'])->name('reports.stock-quantity');
        Route::get('/stock-by-product-no-code', [ReportController::class, 'stockByProductNoCode'])->name('reports.stock-by-product-no-code');

        // 12. รายงานสรุปสินค้า (รับ/เบิก)
        Route::get('/summary/product', [ReportController::class, 'productSummary'])->name('reports.summary.product');
    });

    // Stock Alert Settings (เฉพาะ admin)
    Route::get('/stock-alert-settings', [StockAlertSettingController::class, 'index'])->middleware('role:admin')->name('stock-alert-settings.index');
    Route::put('/stock-alert-settings', [StockAlertSettingController::class, 'update'])->middleware('role:admin')->name('stock-alert-settings.update');

    // Stock Alert Email (เฉพาะ admin)
    Route::post('/stock-alert/send', function (\Illuminate\Http\Request $request) {
        $request->validate(['email' => 'required|email']);
        \Illuminate\Support\Facades\Artisan::call('stock:alert', ['--to' => $request->email]);
        return back()->with('success', \Illuminate\Support\Facades\Artisan::output());
    })->middleware('role:admin')->name('stock-alert.send');

    // Sync current_stock (เฉพาะ admin)
    Route::post('/stock-sync', function () {
        \Illuminate\Support\Facades\Artisan::call('stock:sync');
        return back()->with('success', \Illuminate\Support\Facades\Artisan::output());
    })->middleware('role:admin')->name('stock.sync');

    // Backup Management (เฉพาะ admin)
    Route::middleware('role:admin')->prefix('backups')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/create', [BackupController::class, 'create'])->name('backups.create');
        Route::post('/create-db', [BackupController::class, 'createDbOnly'])->name('backups.create-db');
        Route::post('/create-excel', [BackupController::class, 'createExcel'])->name('backups.create-excel');
        Route::post('/create-db-excel', [BackupController::class, 'createDbWithExcel'])->name('backups.create-db-excel');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
        Route::post('/clean', [BackupController::class, 'clean'])->name('backups.clean');
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/dropdown', [NotificationController::class, 'dropdown'])->name('notifications.dropdown');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/destroy-read', [NotificationController::class, 'destroyRead'])->name('notifications.destroy-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // Font test
    Route::get('/font_test', function () {
        return view('font_test');
    });

    Route::get('/report/received', [ReportController::class, 'receivedReport'])->name('report.received');
});

// Authentication routes
require __DIR__.'/auth.php';
