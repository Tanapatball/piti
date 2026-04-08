# Coding Standards & Best Practices

## PHP / Laravel Conventions

### PSR Standards

โปรเจ็คนี้ใช้ **PSR-12** เป็นมาตรฐานการเขียนโค้ด

```php
// ถูกต้อง
namespace App\Http\Controllers;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }
}

// ไม่ถูกต้อง
namespace App\Http\Controllers;
class ProductController extends Controller{
    public function index(){
        $products=Product::all();
        return view('products.index',compact('products'));
    }
}
```

### Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| Class | PascalCase | `ProductController`, `StockOutService` |
| Method/Function | camelCase | `getProducts()`, `calculateStock()` |
| Variable | camelCase | `$totalStock`, `$productName` |
| Constant | UPPER_SNAKE_CASE | `MAX_UPLOAD_SIZE`, `DEFAULT_ROLE` |
| Database Table | snake_case (plural) | `products`, `transaction_items` |
| Database Column | snake_case | `product_id`, `created_at` |
| Route | kebab-case | `/stock-outs`, `/receive-types` |
| View File | kebab-case | `stock-card.blade.php` |
| Config Key | snake_case | `app.timezone` |

### Models

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    // 1. Properties
    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'name',
        'category_id',
        'stock_min',
        'stock_max',
    ];

    protected $casts = [
        'stock_min' => 'integer',
        'stock_max' => 'integer',
        'current_stock' => 'integer',
    ];

    // 2. Relationships (เรียงตาม belongsTo -> hasMany -> belongsToMany)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'product_id', 'product_id');
    }

    // 3. Accessors & Mutators
    public function getFullNameAttribute(): string
    {
        return "{$this->product_id} - {$this->name}";
    }

    // 4. Scopes
    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<', 'stock_min');
    }

    // 5. Custom Methods
    public function isLowStock(): bool
    {
        return $this->current_stock < $this->stock_min;
    }
}
```

### Controllers

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    // ใช้ Type hints เสมอ
    public function index(): View
    {
        $products = Product::with('category')
            ->orderBy('name')
            ->paginate(20);

        return view('products.index', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate ก่อนเสมอ
        $validated = $request->validate([
            'product_id' => 'required|string|max:20|unique:products',
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|exists:categories,category_id',
            'stock_min' => 'nullable|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'เพิ่มสินค้าเรียบร้อยแล้ว');
    }

    // ใช้ Route Model Binding
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }
}
```

### Blade Templates

```blade
{{-- ใช้ component-based approach --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">รายการสินค้า</h2>
    </x-slot>

    {{-- ใช้ @if/@foreach แทน <?php ?> --}}
    @if($products->isNotEmpty())
        <div class="grid grid-cols-3 gap-4">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    @else
        <p class="text-gray-500">ไม่พบสินค้า</p>
    @endif

    {{-- Escape output เสมอ --}}
    <p>{{ $product->name }}</p>

    {{-- ใช้ {!! !!} เฉพาะเมื่อแน่ใจว่าปลอดภัย --}}
    {!! $trustedHtml !!}
</x-app-layout>
```

---

## JavaScript Conventions

### ES6+ Syntax

```javascript
// ใช้ const/let แทน var
const baseUrl = '/api';
let currentPage = 1;

// ใช้ Arrow functions
const fetchProducts = async () => {
    const response = await fetch(`${baseUrl}/products`);
    return response.json();
};

// ใช้ Template literals
const message = `สินค้า ${product.name} มีจำนวน ${product.stock} ชิ้น`;

// ใช้ Destructuring
const { name, price, stock } = product;
```

### Alpine.js Pattern

```html
<div x-data="{
    open: false,
    items: [],
    async fetchItems() {
        const response = await fetch('/api/items');
        this.items = await response.json();
    }
}" x-init="fetchItems()">

    <button @click="open = !open">
        Toggle
    </button>

    <ul x-show="open" x-transition>
        <template x-for="item in items" :key="item.id">
            <li x-text="item.name"></li>
        </template>
    </ul>
</div>
```

---

## CSS / Tailwind Conventions

### Utility-First Approach

```html
<!-- ถูกต้อง: ใช้ Tailwind utilities -->
<button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
    บันทึก
</button>

<!-- หลีกเลี่ยง: เขียน custom CSS เกินจำเป็น -->
<button class="custom-button">บันทึก</button>
```

### Component Classes

เมื่อ pattern ซ้ำกันหลายที่ ให้สร้างเป็น component:

```css
/* resources/css/app.css */
@layer components {
    .btn-primary {
        @apply px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600;
    }

    .form-input {
        @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500;
    }
}
```

### Responsive Design

```html
<!-- Mobile-first approach -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- content -->
</div>
```

---

## Database Conventions

### Migrations

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // Primary key ก่อน
            $table->string('product_id', 20)->primary();

            // ข้อมูลหลัก
            $table->string('name', 150);
            $table->text('description')->nullable();

            // Foreign keys
            $table->string('category_id', 50)->nullable();

            // Numeric fields
            $table->integer('stock_min')->default(0);
            $table->decimal('price', 10, 2)->default(0);

            // Timestamps ท้ายสุด
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')
                ->references('category_id')
                ->on('categories')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### Query Best Practices

```php
// ถูกต้อง: ใช้ Eager loading ป้องกัน N+1
$products = Product::with(['category', 'transactions'])->get();

// ไม่ดี: N+1 query problem
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->name; // Query ทุก loop
}

// ถูกต้อง: ใช้ Query Builder สำหรับ complex queries
$report = DB::table('products')
    ->join('categories', 'products.category_id', '=', 'categories.category_id')
    ->select('categories.category_name', DB::raw('SUM(current_stock) as total'))
    ->groupBy('categories.category_id', 'categories.category_name')
    ->get();
```

---

## Security Best Practices

### Input Validation

```php
// ใช้ Form Request สำหรับ validation ที่ซับซ้อน
class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => 'required|string|max:20|unique:products',
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'กรุณากรอกรหัสสินค้า',
            'product_id.unique' => 'รหัสสินค้านี้มีในระบบแล้ว',
        ];
    }
}
```

### Authorization

```php
// ใช้ Middleware สำหรับ role-based access
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::resource('products', ProductController::class);
});

// ตรวจสอบภายใน Controller
public function update(Request $request, Product $product)
{
    $this->authorize('update', $product);
    // ...
}
```

### SQL Injection Prevention

```php
// ถูกต้อง: ใช้ Eloquent หรือ Query Builder
$products = Product::where('name', 'like', "%{$search}%")->get();

// ถูกต้อง: ใช้ parameter binding
$products = DB::select('SELECT * FROM products WHERE name = ?', [$name]);

// อันตราย: String concatenation
$products = DB::select("SELECT * FROM products WHERE name = '{$name}'"); // ห้าม!
```

### XSS Prevention

```blade
{{-- ถูกต้อง: Auto-escaped --}}
<p>{{ $user->name }}</p>

{{-- ระวัง: ไม่ escape --}}
{!! $user->bio !!}  {{-- ใช้เฉพาะ trusted content --}}
```

---

## Error Handling

### Try-Catch Pattern

```php
public function importProducts(Request $request)
{
    try {
        DB::beginTransaction();

        // Import logic...

        DB::commit();

        return redirect()->back()->with('success', 'นำเข้าข้อมูลเรียบร้อย');

    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Product import failed', [
            'error' => $e->getMessage(),
            'file' => $request->file('import_file')?->getClientOriginalName(),
        ]);

        return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
    }
}
```

### Custom Exceptions

```php
// app/Exceptions/InsufficientStockException.php
class InsufficientStockException extends Exception
{
    public function __construct(Product $product, int $requested)
    {
        parent::__construct(
            "สินค้า {$product->name} มีไม่เพียงพอ (ต้องการ: {$requested}, คงเหลือ: {$product->current_stock})"
        );
    }
}
```

---

## Testing Standards

### Test Structure

```php
class ProductTest extends TestCase
{
    use RefreshDatabase;

    // ตั้งชื่อ method ให้อธิบายสิ่งที่จะ test
    public function test_user_can_view_products_list(): void
    {
        // Arrange
        $user = User::factory()->create();
        Product::factory()->count(5)->create();

        // Act
        $response = $this->actingAs($user)->get('/products');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/products', [
            'product_id' => 'P001',
            'name' => 'Test Product',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['product_id' => 'P001']);
    }
}
```

---

## Code Formatting

### Laravel Pint

โปรเจ็คใช้ Laravel Pint สำหรับ auto-format:

```bash
# Format all files
./vendor/bin/pint

# Format specific file
./vendor/bin/pint app/Models/Product.php

# Check without fixing
./vendor/bin/pint --test
```

### EditorConfig

ไฟล์ `.editorconfig` กำหนดรูปแบบพื้นฐาน:

```ini
root = true

[*]
charset = utf-8
end_of_line = lf
indent_size = 4
indent_style = space
insert_final_newline = true
trim_trailing_whitespace = true

[*.md]
trim_trailing_whitespace = false

[*.{yml,yaml}]
indent_size = 2

[*.blade.php]
indent_size = 4
```

---

## Git Commit Messages

### Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

| Type | Description |
|------|-------------|
| feat | เพิ่ม feature ใหม่ |
| fix | แก้ bug |
| docs | แก้ไขเอกสาร |
| style | แก้ไข format (ไม่กระทบ logic) |
| refactor | Refactor code |
| test | เพิ่ม/แก้ไข tests |
| chore | งาน maintenance |

### Examples

```
feat(products): เพิ่มฟังก์ชันค้นหาสินค้าตามหมวดหมู่

- เพิ่ม scope search ใน Product model
- เพิ่ม filter dropdown ในหน้ารายการสินค้า
- เพิ่ม test cases สำหรับการค้นหา

Closes #123
```

```
fix(stock): แก้ปัญหาสต๊อกติดลบเมื่อเบิกสินค้า

เพิ่มการตรวจสอบจำนวนสินค้าก่อนบันทึกการเบิก
```
