# Database Schema

## ER Diagram

```
┌─────────────────┐         ┌─────────────────┐
│      roles      │         │   warehouses    │
├─────────────────┤         ├─────────────────┤
│ *role_id (PK)   │         │ *warehouse_id   │
│  role_name      │         │  warehouse_name │
│  description    │         │  location       │
└────────┬────────┘         └────────┬────────┘
         │                           │
         │ 1:N                       │ 1:N
         ▼                           │
┌─────────────────┐                  │
│     users       │                  │
├─────────────────┤                  │
│ *user_id (PK)   │                  │
│  fname          │                  │
│  lname          │                  │
│  email          │                  │
│  phone          │                  │
│  user_code      │                  │
│  role_id (FK)───┤                  │
│  password       │                  │
│  receive_alert  │                  │
└────────┬────────┘                  │
         │                           │
         │ 1:N                       │
         │                           │
         ▼                           │
┌─────────────────┐         ┌────────┴────────┐
│   stock_outs    │         │   categories    │
├─────────────────┤         ├─────────────────┤
│ *id (PK)        │         │ *category_id    │
│  product_id(FK)─┼────┐    │  category_name  │
│  issue_type_id  │    │    └────────┬────────┘
│  code           │    │             │
│  trans_id (FK)  │    │             │ 1:N
│  reference_doc  │    │             │
│  reference_no   │    │             ▼
│  quantity       │    │    ┌─────────────────┐
│  fraction_qty   │    │    │    products     │
│  user_id (FK)───┘    │    ├─────────────────┤
│  issued_date    │    └───▶│ *product_id(PK) │
│  note           │         │  name           │
└─────────────────┘         │  category_id(FK)│
                            │  warehouse_id   │──┘
┌─────────────────┐         │  stock_min      │
│  issue_types    │         │  stock_max      │
├─────────────────┤         │  current_stock  │
│ *issue_type_id  │◀────────│  size           │
│  code           │         │  pack           │
│  name           │         │  weight_per_kg  │
└─────────────────┘         │  weight_total   │
                            └────────┬────────┘
                                     │
                    ┌────────────────┼────────────────┐
                    │                │                │
                    ▼                ▼                ▼
         ┌─────────────────┐  ┌─────────────┐  ┌─────────────────┐
         │  transactions   │  │notifications│  │transaction_items│
         ├─────────────────┤  ├─────────────┤  ├─────────────────┤
         │ *trans_id (PK)  │  │ *id (PK)    │  │ *id (PK)        │
         │  trans_date     │  │  type       │  │  trans_id (FK)──┤
         │  reference_doc  │  │  title      │  │  product_id(FK) │
         │  reference_no   │  │  message    │  │  code           │
         │  receive_type_id│  │  icon       │  │  item_code      │
         │  note           │  │  color      │  │  full_qty       │
         └────────┬────────┘  │  link       │  │  fraction_qty   │
                  │           │  product_id │  │  net_weight     │
                  │           │  user_id    │  └─────────────────┘
                  │           │  read_at    │
                  │           └─────────────┘
                  │
                  ▼
         ┌─────────────────┐
         │  receive_types  │
         ├─────────────────┤
         │*receive_type_id │
         │  code           │
         │  name           │
         └─────────────────┘
```

## รายละเอียดตาราง

### 1. roles (บทบาทผู้ใช้)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| role_id | bigint unsigned | No | PK | รหัสบทบาท (Auto increment) |
| role_name | varchar(50) | No | Unique | ชื่อบทบาท |
| description | varchar(255) | Yes | - | คำอธิบาย |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

**Default Data:**
- admin - ผู้ดูแลระบบ
- staff - พนักงาน
- user - ผู้ใช้งานทั่วไป

---

### 2. users (ผู้ใช้)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| user_id | bigint unsigned | No | PK | รหัสผู้ใช้ |
| fname | varchar(100) | No | - | ชื่อ |
| lname | varchar(100) | No | - | นามสกุล |
| email | varchar(255) | No | Unique | อีเมล |
| phone | varchar(20) | Yes | - | เบอร์โทร |
| user_code | varchar(20) | Yes | - | รหัสพนักงาน |
| role_id | bigint unsigned | No | FK | บทบาท |
| password | varchar(255) | No | - | รหัสผ่าน (hashed) |
| receive_stock_alert | boolean | No | - | รับแจ้งเตือนสต๊อก |
| remember_token | varchar(100) | Yes | - | Token จำ login |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

**Foreign Keys:**
- `role_id` → `roles(role_id)` ON DELETE CASCADE

---

### 3. warehouses (คลังสินค้า)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| warehouse_id | bigint unsigned | No | PK | รหัสคลัง |
| warehouse_name | varchar(100) | No | Unique | ชื่อคลัง |
| location | varchar(255) | Yes | - | ที่ตั้ง |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

---

### 4. categories (หมวดหมู่สินค้า)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| category_id | varchar(50) | No | PK | รหัสหมวดหมู่ |
| category_name | varchar(100) | No | - | ชื่อหมวดหมู่ |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

**Note:** `category_id` เป็น string เพราะอาจมีรูปแบบ เช่น "A/B", "01-02"

---

### 5. products (สินค้า)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| product_id | varchar(20) | No | PK | รหัสสินค้า |
| name | varchar(150) | No | - | ชื่อสินค้า |
| category_id | varchar(50) | Yes | FK | หมวดหมู่ |
| warehouse_id | bigint unsigned | Yes | FK | คลังสินค้า |
| stock_min | int | Yes | - | สต๊อกขั้นต่ำ |
| stock_max | int | Yes | - | สต๊อกสูงสุด |
| current_stock | int | No | - | สต๊อกปัจจุบัน (default: 0) |
| size | varchar(50) | Yes | - | ขนาด |
| pack | varchar(50) | Yes | - | แพ็ค |
| weight_per_kg | decimal(10,2) | Yes | - | น้ำหนักต่อกก. |
| weight_total | decimal(10,2) | Yes | - | น้ำหนักรวม |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

**Foreign Keys:**
- `category_id` → `categories(category_id)` ON DELETE SET NULL
- `warehouse_id` → `warehouses(warehouse_id)` ON DELETE SET NULL

**Indexes:**
- Primary key on `product_id`

---

### 6. receive_types (ประเภทการรับเข้า)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| receive_type_id | bigint unsigned | No | PK | รหัสประเภท |
| code | varchar(10) | Yes | - | รหัสย่อ |
| name | varchar(100) | No | - | ชื่อประเภท |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

---

### 7. issue_types (ประเภทการเบิก)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| issue_type_id | bigint unsigned | No | PK | รหัสประเภท |
| code | varchar(10) | Yes | - | รหัสย่อ |
| name | varchar(100) | No | - | ชื่อประเภท |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

---

### 8. transactions (ธุรกรรมรับสินค้า)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| trans_id | varchar(50) | No | PK | เลขที่ธุรกรรม |
| trans_date | date | No | - | วันที่รับ |
| reference_doc | varchar(100) | Yes | - | เอกสารอ้างอิง |
| reference_no | varchar(100) | Yes | - | เลขที่เอกสาร |
| receive_type_id | bigint unsigned | Yes | FK | ประเภทการรับ |
| note | text | Yes | - | หมายเหตุ |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

**Foreign Keys:**
- `receive_type_id` → `receive_types(receive_type_id)` ON DELETE SET NULL

---

### 9. transaction_items (รายการในธุรกรรม)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| id | bigint unsigned | No | PK | รหัสรายการ |
| trans_id | varchar(50) | No | FK | เลขที่ธุรกรรม |
| product_id | varchar(20) | No | FK | รหัสสินค้า |
| code | varchar(50) | Yes | - | รหัส |
| item_code | varchar(50) | Yes | - | รหัสรายการ |
| full_qty | int | No | - | จำนวนเต็ม (default: 0) |
| fraction_qty | int | No | - | จำนวนเศษ (default: 0) |
| net_weight | decimal(10,2) | No | - | น้ำหนักสุทธิ (default: 0) |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

**Foreign Keys:**
- `trans_id` → `transactions(trans_id)` ON DELETE CASCADE
- `product_id` → `products(product_id)` ON DELETE CASCADE

---

### 10. stock_outs (การเบิกสินค้า)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| id | bigint unsigned | No | PK | รหัสการเบิก |
| product_id | varchar(20) | No | FK | รหัสสินค้า |
| issue_type_id | bigint unsigned | Yes | FK | ประเภทการเบิก |
| code | varchar(50) | Yes | - | รหัส |
| trans_id | varchar(50) | Yes | FK | อ้างอิงธุรกรรม |
| reference_doc | varchar(100) | Yes | - | เอกสารอ้างอิง |
| reference_no | varchar(100) | Yes | - | เลขที่เอกสาร |
| quantity | int | No | - | จำนวน (default: 0) |
| fraction_qty | int | No | - | จำนวนเศษ (default: 0) |
| user_id | bigint unsigned | Yes | FK | ผู้เบิก |
| issued_date | date | No | - | วันที่เบิก |
| note | text | Yes | - | หมายเหตุ |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

**Foreign Keys:**
- `product_id` → `products(product_id)` ON DELETE CASCADE
- `trans_id` → `transactions(trans_id)` ON DELETE SET NULL
- `user_id` → `users(user_id)` ON DELETE SET NULL
- `issue_type_id` → `issue_types(issue_type_id)` ON DELETE SET NULL

---

### 11. notifications (การแจ้งเตือน)

| Column | Type | Nullable | Key | Description |
|--------|------|----------|-----|-------------|
| id | bigint unsigned | No | PK | รหัสแจ้งเตือน |
| type | varchar(255) | No | - | ประเภท (stock_low, stock_over, system) |
| title | varchar(255) | No | - | หัวข้อ |
| message | text | No | - | ข้อความ |
| icon | varchar(255) | Yes | - | ไอคอน |
| color | varchar(255) | No | - | สี (default: blue) |
| link | varchar(255) | Yes | - | ลิงก์ |
| product_id | varchar(20) | Yes | FK | สินค้าที่เกี่ยวข้อง |
| user_id | bigint unsigned | Yes | FK | ผู้ใช้เฉพาะ (null = ทุกคน) |
| read_at | timestamp | Yes | - | วันที่อ่าน |
| created_at | timestamp | Yes | - | วันที่สร้าง |
| updated_at | timestamp | Yes | - | วันที่แก้ไข |

**Foreign Keys:**
- `product_id` → `products(product_id)` ON DELETE SET NULL
- `user_id` → `users(user_id)` ON DELETE SET NULL

**Indexes:**
- Composite index on `(user_id, read_at)`
- Index on `created_at`

---

## System Tables

### sessions
สำหรับเก็บ session ของผู้ใช้ (session driver: database)

### cache
สำหรับเก็บ cache (cache driver: database)

### jobs / job_batches / failed_jobs
สำหรับ Laravel Queue system

### password_reset_tokens
สำหรับระบบ reset password

---

## Indexes Summary

| Table | Index | Columns | Type |
|-------|-------|---------|------|
| users | PRIMARY | user_id | Primary |
| users | users_email_unique | email | Unique |
| products | PRIMARY | product_id | Primary |
| transactions | PRIMARY | trans_id | Primary |
| transaction_items | PRIMARY | id | Primary |
| stock_outs | PRIMARY | id | Primary |
| notifications | notifications_user_id_read_at_index | user_id, read_at | Index |
| notifications | notifications_created_at_index | created_at | Index |

---

## การคำนวณ current_stock

current_stock ถูกคำนวณจาก:

```sql
current_stock = SUM(รับเข้า) - SUM(เบิกออก)

-- รับเข้า
SELECT SUM(full_qty)
FROM transaction_items
WHERE product_id = ?

-- เบิกออก
SELECT SUM(quantity)
FROM stock_outs
WHERE product_id = ?
```

สามารถ sync ได้ด้วยคำสั่ง:
```bash
php artisan stock:sync
```

---

## Backup Strategy

1. **Database backup**: SQLite file copy หรือ `mysqldump`
2. **Storage backup**: รวม uploaded files
3. **Spatie Backup**: Full application backup

```bash
# Full backup
php artisan backup:run

# Database only
php artisan backup:run --only-db
```
