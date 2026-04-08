# API Documentation

ระบบใช้ Web Routes (non-API) โดย endpoints ทั้งหมดรองรับ HTML response สำหรับ browser

## Authentication

### Login
```
POST /login
```

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123",
    "_token": "csrf_token"
}
```

**Response:** Redirect to `/dashboard`

### Logout
```
POST /logout
```

**Response:** Redirect to `/`

### Register
```
POST /register
```

**Request Body:**
```json
{
    "fname": "John",
    "lname": "Doe",
    "email": "john@example.com",
    "phone": "0812345678",
    "password": "password123",
    "password_confirmation": "password123",
    "_token": "csrf_token"
}
```

---

## Products (สินค้า)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/products` | index | auth |
| GET | `/products/create` | create | auth, role:admin,staff |
| POST | `/products` | store | auth, role:admin,staff |
| GET | `/products/{product}` | show | auth |
| GET | `/products/{product}/edit` | edit | auth, role:admin,staff |
| PUT | `/products/{product}` | update | auth, role:admin,staff |
| DELETE | `/products/{product}` | destroy | auth, role:admin,staff |

### Create Product

**Request:**
```
POST /products
Content-Type: application/x-www-form-urlencoded
```

**Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| product_id | string(20) | Yes | รหัสสินค้า |
| name | string(150) | Yes | ชื่อสินค้า |
| category_id | string | No | รหัสหมวดหมู่ |
| stock_min | integer | No | สต๊อกขั้นต่ำ |
| stock_max | integer | No | สต๊อกสูงสุด |
| size | string(50) | No | ขนาด |
| pack | string(50) | No | แพ็ค |
| weight_per_kg | decimal | No | น้ำหนักต่อกิโล |

---

## Categories (หมวดหมู่)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/categories` | index | auth, role:admin,staff |
| GET | `/categories/create` | create | auth, role:admin,staff |
| POST | `/categories` | store | auth, role:admin,staff |
| GET | `/categories/{category}/edit` | edit | auth, role:admin,staff |
| PUT | `/categories/{category}` | update | auth, role:admin,staff |
| DELETE | `/categories/{category}` | destroy | auth, role:admin,staff |

**Note:** `category` parameter รองรับ "/" ในค่า (เช่น `A/B`)

### Create Category

**Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| category_id | string | Yes | รหัสหมวดหมู่ |
| category_name | string | Yes | ชื่อหมวดหมู่ |

---

## Transactions (รับสินค้าเข้าคลัง)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/transactions` | index | auth, role:admin,staff |
| GET | `/transactions/create` | create | auth, role:admin,staff |
| POST | `/transactions` | store | auth, role:admin,staff |
| GET | `/transactions/{transaction}` | show | auth, role:admin,staff |
| GET | `/transactions/{transaction}/edit` | edit | auth, role:admin,staff |
| PUT | `/transactions/{transaction}` | update | auth, role:admin,staff |
| DELETE | `/transactions/{transaction}` | destroy | auth, role:admin,staff |

### Create Transaction

**Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| trans_id | string(50) | Yes | เลขที่ธุรกรรม |
| trans_date | date | Yes | วันที่รับ |
| reference_doc | string(100) | No | เอกสารอ้างอิง |
| reference_no | string(100) | No | เลขที่เอกสาร |
| receive_type_id | integer | No | ประเภทการรับ |
| note | text | No | หมายเหตุ |
| items | array | Yes | รายการสินค้า |

**Items Array:**
```json
{
    "items": [
        {
            "product_id": "P001",
            "item_code": "ITEM001",
            "full_qty": 100,
            "fraction_qty": 0,
            "net_weight": 50.00
        }
    ]
}
```

---

## Stock Outs (เบิกสินค้า)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/stock-outs` | index | auth, role:admin,staff |
| GET | `/stock-outs/create` | create | auth, role:admin,staff |
| POST | `/stock-outs` | store | auth, role:admin,staff |
| GET | `/stock-outs/{stockOut}` | show | auth, role:admin,staff |
| GET | `/stock-outs/{stockOut}/edit` | edit | auth, role:admin,staff |
| PUT | `/stock-outs/{stockOut}` | update | auth, role:admin,staff |
| DELETE | `/stock-outs/{stockOut}` | destroy | auth, role:admin,staff |
| POST | `/stock-outs/check-code` | checkCode | auth, role:admin,staff |

### Create Stock Out

**Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| product_id | string(20) | Yes | รหัสสินค้า |
| issue_type_id | integer | No | ประเภทการเบิก |
| code | string | No | รหัส |
| trans_id | string(50) | No | อ้างอิงธุรกรรม |
| reference_doc | string | No | เอกสารอ้างอิง |
| reference_no | string | No | เลขที่เอกสาร |
| quantity | integer | Yes | จำนวน |
| fraction_qty | integer | No | จำนวนเศษ |
| user_id | integer | No | ผู้เบิก |
| issued_date | date | Yes | วันที่เบิก |
| note | text | No | หมายเหตุ |

### Check Code
```
POST /stock-outs/check-code
```

**Request:**
```json
{
    "code": "ITEM001"
}
```

**Response:**
```json
{
    "exists": true,
    "product_id": "P001",
    "product_name": "สินค้า A"
}
```

---

## Reports (รายงาน)

### รายงานหลัก

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/reports/main/receive-types` | รายงานตามประเภทการรับ |
| GET | `/reports/main/categories` | รายงานตามหมวดหมู่ |
| GET | `/reports/main/products` | รายงานสินค้าทั้งหมด |

### รายละเอียดสินค้า

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/reports/products/by-category` | สินค้าตามหมวดหมู่ |
| GET | `/reports/products/by-date` | สินค้าตามวันที่ |
| GET | `/reports/products/all` | สินค้าทั้งหมด |
| GET | `/reports/products/by-size` | สินค้าตามขนาด |
| GET | `/reports/products/by-pack` | สินค้าตามแพ็ค |

### รายงานรับสินค้า

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/reports/received/check` | ตรวจสอบการรับ |
| GET | `/reports/received/by-product` | รับตามสินค้า |
| GET | `/reports/received/by-type` | รับตามประเภท |

### รายงานเบิกสินค้า

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/reports/issued/check` | ตรวจสอบการเบิก |
| GET | `/reports/issued/by-product` | เบิกตามสินค้า |
| GET | `/reports/issued/by-type` | เบิกตามประเภท |

### สินค้าคงเหลือ

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/reports/stock-remaining-size` | คงเหลือตามขนาด |
| GET | `/reports/stock-remaining-pack` | คงเหลือตามแพ็ค |

### Stock Card

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/reports/stock-card-by-id` | Stock Card ตาม ID |
| GET | `/reports/stock-card-by-code` | Stock Card ตาม Code |

### รายงานสต๊อก

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/reports/stock-by-product` | สต๊อกตามสินค้า |
| GET | `/reports/stock-quantity` | จำนวนสต๊อก |
| GET | `/reports/stock-by-product-no-code` | สต๊อกไม่มี Code |
| GET | `/reports/summary/product` | สรุปสินค้า (รับ/เบิก) |

### Report Query Parameters

รายงานส่วนใหญ่รองรับ query parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| start_date | date | วันที่เริ่มต้น |
| end_date | date | วันที่สิ้นสุด |
| category_id | string | กรองตามหมวดหมู่ |
| product_id | string | กรองตามสินค้า |
| format | string | `pdf` หรือ `excel` |

---

## Users (ผู้ใช้)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/users` | index | auth, role:admin |
| GET | `/users/create` | create | auth, role:admin |
| POST | `/users` | store | auth, role:admin |
| GET | `/users/{user}` | show | auth, role:admin |
| GET | `/users/{user}/edit` | edit | auth, role:admin |
| PUT | `/users/{user}` | update | auth, role:admin |
| DELETE | `/users/{user}` | destroy | auth, role:admin |

---

## Notifications (แจ้งเตือน)

| Method | URI | Action | Description |
|--------|-----|--------|-------------|
| GET | `/notifications` | index | รายการแจ้งเตือนทั้งหมด |
| GET | `/notifications/dropdown` | dropdown | แจ้งเตือนสำหรับ dropdown |
| GET | `/notifications/unread-count` | unreadCount | จำนวนยังไม่อ่าน |
| POST | `/notifications/{id}/read` | markAsRead | ทำเครื่องหมายอ่านแล้ว |
| POST | `/notifications/mark-all-read` | markAllAsRead | อ่านทั้งหมด |
| DELETE | `/notifications/destroy-read` | destroyRead | ลบที่อ่านแล้ว |
| DELETE | `/notifications/{id}` | destroy | ลบแจ้งเตือน |

### Get Unread Count Response

```json
{
    "count": 5
}
```

---

## Backups (สำรองข้อมูล)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/backups` | index | auth, role:admin |
| POST | `/backups/create` | create | auth, role:admin |
| POST | `/backups/create-db` | createDbOnly | auth, role:admin |
| POST | `/backups/create-excel` | createExcel | auth, role:admin |
| POST | `/backups/create-db-excel` | createDbWithExcel | auth, role:admin |
| GET | `/backups/download/{filename}` | download | auth, role:admin |
| DELETE | `/backups/{filename}` | destroy | auth, role:admin |
| POST | `/backups/clean` | clean | auth, role:admin |

---

## Master Data

### Warehouses (คลังสินค้า)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/warehouses` | index | auth, role:admin,staff |
| POST | `/warehouses` | store | auth, role:admin,staff |
| PUT | `/warehouses/{warehouse}` | update | auth, role:admin,staff |
| DELETE | `/warehouses/{warehouse}` | destroy | auth, role:admin,staff |

### Receive Types (ประเภทการรับ)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/receive-types` | index | auth, role:admin,staff |
| POST | `/receive-types` | store | auth, role:admin,staff |
| PUT | `/receive-types/{receiveType}` | update | auth, role:admin,staff |
| DELETE | `/receive-types/{receiveType}` | destroy | auth, role:admin,staff |

### Issue Types (ประเภทการเบิก)

| Method | URI | Action | Middleware |
|--------|-----|--------|------------|
| GET | `/issue-types` | index | auth, role:admin,staff |
| POST | `/issue-types` | store | auth, role:admin,staff |
| PUT | `/issue-types/{issueType}` | update | auth, role:admin,staff |
| DELETE | `/issue-types/{issueType}` | destroy | auth, role:admin,staff |

---

## System Actions

### Stock Sync
```
POST /stock-sync
Middleware: auth, role:admin
```
คำนวณ current_stock ใหม่จาก transaction_items และ stock_outs

### Stock Alert
```
POST /stock-alert/send
Middleware: auth, role:admin
```

**Request:**
```json
{
    "email": "admin@example.com"
}
```

ส่งอีเมลแจ้งเตือนสินค้าที่สต๊อกต่ำกว่า stock_min

---

## HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created |
| 302 | Found - Redirect after form submission |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Not logged in |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error |

## CSRF Protection

ทุก POST, PUT, DELETE request ต้องมี CSRF token:

```html
<form method="POST">
    @csrf
    <!-- form fields -->
</form>
```

หรือ Header:
```
X-CSRF-TOKEN: {token}
```
