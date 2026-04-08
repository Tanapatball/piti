# Architecture Document

## ภาพรวมสถาปัตยกรรม

ระบบพัฒนาตาม MVC (Model-View-Controller) pattern โดยใช้ Laravel Framework เป็นหลัก

```
┌─────────────────────────────────────────────────────────────────┐
│                         Client (Browser)                        │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Web Server (Apache/Nginx)                   │
│                         public/index.php                        │
└─────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Laravel Application                      │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                      Middleware                          │   │
│  │  • Authentication (auth)                                 │   │
│  │  • Role-based Access (RoleMiddleware)                    │   │
│  └──────────────────────────────────────────────────────────┘   │
│                               │                                 │
│                               ▼                                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                      Routes (web.php)                    │   │
│  │  • Resource routes (products, transactions, etc.)        │   │
│  │  • Custom routes (reports, backups, etc.)                │   │
│  └──────────────────────────────────────────────────────────┘   │
│                               │                                 │
│                               ▼                                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                     Controllers                          │   │
│  │  ProductController, TransactionController,               │   │
│  │  StockOutController, ReportController, etc.              │   │
│  └──────────────────────────────────────────────────────────┘   │
│                               │                                 │
│                    ┌──────────┴──────────┐                      │
│                    ▼                     ▼                      │
│  ┌─────────────────────────┐  ┌──────────────────────────────┐  │
│  │        Models           │  │          Views               │  │
│  │  (Eloquent ORM)         │  │     (Blade Templates)        │  │
│  │                         │  │                              │  │
│  │  • User, Role           │  │  • layouts/                  │  │
│  │  • Product, Category    │  │  • products/                 │  │
│  │  • Transaction          │  │  • transactions/             │  │
│  │  • StockOut             │  │  • reports/                  │  │
│  │  • Notification         │  │  • components/               │  │
│  └─────────────────────────┘  └──────────────────────────────┘  │
│              │                                                  │
└──────────────│──────────────────────────────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Database (SQLite)                        │
│                       database/database.sqlite                  │
└─────────────────────────────────────────────────────────────────┘
```

## Technology Stack และเหตุผลที่เลือก

### Backend: Laravel 12

| เหตุผล | รายละเอียด |
|--------|------------|
| Mature Framework | เป็น PHP Framework ที่มีความเสถียรและมี community ขนาดใหญ่ |
| Eloquent ORM | ทำให้การทำงานกับ database ง่ายและรวดเร็ว |
| Built-in Features | มี Authentication, Queue, Mail, Cache พร้อมใช้ |
| MVC Pattern | โครงสร้างชัดเจน ง่ายต่อการ maintain |

### Database: SQLite (Default)

| เหตุผล | รายละเอียด |
|--------|------------|
| Zero Configuration | ไม่ต้องติดตั้ง database server แยก |
| Portable | เก็บเป็นไฟล์เดียว ย้ายง่าย backup ง่าย |
| Lightweight | เหมาะกับระบบขนาดเล็ก-กลาง |
| Scalable | สามารถเปลี่ยนเป็น MySQL ได้ง่าย |

### Frontend: Blade + Tailwind CSS

| เหตุผล | รายละเอียด |
|--------|------------|
| Server-side Rendering | เร็ว, SEO friendly |
| Tailwind CSS | Utility-first, flexible, modern design |
| Laravel Breeze | Starter kit พร้อม authentication UI |

### Build Tool: Vite

| เหตุผล | รายละเอียด |
|--------|------------|
| Fast HMR | Hot Module Replacement ที่รวดเร็ว |
| Modern | รองรับ ES Modules |
| Laravel Integration | มี plugin สำหรับ Laravel โดยเฉพาะ |

## โครงสร้าง Folder/Module

```
app/
├── Console/
│   └── Commands/
│       ├── BackupDatabase.php      # สำรองข้อมูลอัตโนมัติ
│       ├── ImportDbf.php           # นำเข้าข้อมูลจากไฟล์ DBF
│       ├── RecalculateStock.php    # คำนวณสต๊อกใหม่
│       ├── SendStockAlert.php      # ส่งแจ้งเตือนสต๊อก
│       └── SyncCurrentStock.php    # sync สต๊อกปัจจุบัน
│
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                   # Authentication controllers
│   │   ├── BackupController.php    # จัดการ backup
│   │   ├── CategoryController.php  # จัดการหมวดหมู่
│   │   ├── IssueTypeController.php # ประเภทการเบิก
│   │   ├── NotificationController.php # แจ้งเตือน
│   │   ├── ProductController.php   # จัดการสินค้า
│   │   ├── ProfileController.php   # โปรไฟล์ผู้ใช้
│   │   ├── ReceiveTypeController.php # ประเภทการรับ
│   │   ├── ReportController.php    # รายงานทั้งหมด
│   │   ├── StockAlertSettingController.php # ตั้งค่าแจ้งเตือน
│   │   ├── StockOutController.php  # การเบิกสินค้า
│   │   ├── TransactionController.php # การรับสินค้า
│   │   ├── UserController.php      # จัดการผู้ใช้
│   │   └── WarehouseController.php # จัดการคลัง
│   │
│   ├── Middleware/
│   │   └── RoleMiddleware.php      # ตรวจสอบ role
│   │
│   └── Requests/
│       ├── Auth/LoginRequest.php   # Validate login
│       └── ProfileUpdateRequest.php # Validate profile
│
├── Mail/
│   └── StockAlertMail.php          # Email แจ้งเตือนสต๊อก
│
├── Models/
│   ├── Category.php                # หมวดหมู่สินค้า
│   ├── IssueType.php               # ประเภทการเบิก
│   ├── Notification.php            # การแจ้งเตือน
│   ├── Product.php                 # สินค้า
│   ├── ReceiveType.php             # ประเภทการรับ
│   ├── Role.php                    # บทบาทผู้ใช้
│   ├── StockOut.php                # การเบิกสินค้า
│   ├── Transaction.php             # ธุรกรรมรับสินค้า
│   ├── TransactionItem.php         # รายการในธุรกรรม
│   ├── User.php                    # ผู้ใช้
│   └── Warehouse.php               # คลังสินค้า
│
├── Observers/
│   └── ProductObserver.php         # ติดตามการเปลี่ยนแปลงสินค้า
│
├── Providers/
│   ├── AppServiceProvider.php      # App bindings
│   └── RouteServiceProvider.php    # Route configuration
│
└── View/
    └── Components/
        ├── AppLayout.php           # Layout หลัก
        └── GuestLayout.php         # Layout สำหรับ guest
```

## Data Flow

### การรับสินค้าเข้าคลัง

```
┌──────────┐    ┌─────────────────────┐    ┌─────────────┐
│  User    │───▶│ TransactionController│───▶│ Transaction │
│ (Staff)  │    │       store()       │    │   Model     │
└──────────┘    └─────────────────────┘    └──────┬──────┘
                                                  │
                                                  ▼
                                           ┌─────────────┐
                                           │Transaction  │
                                           │   Items     │
                                           └──────┬──────┘
                                                  │
                                                  ▼
                                           ┌─────────────┐
                                           │  Product    │
                                           │current_stock│
                                           │  (+qty)     │
                                           └─────────────┘
```

### การเบิกสินค้าออก

```
┌──────────┐    ┌─────────────────────┐    ┌─────────────┐
│  User    │───▶│  StockOutController │───▶│  StockOut   │
│ (Staff)  │    │       store()       │    │   Model     │
└──────────┘    └─────────────────────┘    └──────┬──────┘
                                                  │
                                                  ▼
                                           ┌─────────────┐
                                           │  Product    │
                                           │current_stock│
                                           │   (-qty)    │
                                           └──────┬──────┘
                                                  │
                                                  ▼
                                           ┌─────────────┐
                                           │ Notification│
                                           │  (if low)   │
                                           └─────────────┘
```

## Security Architecture

### Authentication Flow

```
┌─────────┐    ┌─────────────┐    ┌──────────────┐    ┌──────────┐
│  Login  │───▶│ LoginRequest│───▶│ Authenticate │───▶│  Session │
│  Form   │    │ (validate)  │    │   (verify)   │    │  Created │
└─────────┘    └─────────────┘    └──────────────┘    └──────────┘
```

### Authorization (Role-based)

```
┌─────────┐    ┌─────────────┐    ┌──────────────┐    ┌──────────┐
│ Request │───▶│    auth     │───▶│    role:     │───▶│Controller│
│         │    │ middleware  │    │ admin,staff  │    │  Action  │
└─────────┘    └─────────────┘    └──────────────┘    └──────────┘
                     │                   │
                     ▼                   ▼
              ┌─────────────┐    ┌──────────────┐
              │  Redirect   │    │    403       │
              │  to Login   │    │  Forbidden   │
              └─────────────┘    └──────────────┘
```

## Queue & Background Jobs

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ stock:alert     │───▶│   Job Queue     │───▶│  Send Email     │
│ command         │    │   (database)    │    │  Notification   │
└─────────────────┘    └─────────────────┘    └─────────────────┘

┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ backup:run      │───▶│ Spatie Backup   │───▶│  Create ZIP     │
│ command         │    │   Package       │    │   Archive       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## Deployment Architecture

### Development

```
┌─────────────────────────────────────────┐
│             Local Machine               │
│  ┌─────────────┐    ┌─────────────┐     │
│  │ PHP Server  │    │ Vite Dev    │     │
│  │  :8000      │    │  :5173      │     │
│  └─────────────┘    └─────────────┘     │
│         │                 │              │
│         └────────┬────────┘              │
│                  ▼                       │
│         ┌─────────────┐                  │
│         │   SQLite    │                  │
│         └─────────────┘                  │
└─────────────────────────────────────────┘
```

### Production (XAMPP)

```
┌─────────────────────────────────────────┐
│             XAMPP Server                │
│  ┌─────────────────────────────────┐    │
│  │           Apache                │    │
│  │  DocumentRoot: htdocs/pj/public │    │
│  └─────────────────────────────────┘    │
│                  │                       │
│                  ▼                       │
│         ┌─────────────┐                  │
│         │   SQLite    │                  │
│         │   / MySQL   │                  │
│         └─────────────┘                  │
└─────────────────────────────────────────┘
```
