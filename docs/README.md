# Inventory Management System (ระบบจัดการคลังสินค้า)

ระบบจัดการคลังสินค้าสำหรับองค์กร พัฒนาด้วย Laravel 12 รองรับการรับเข้า-เบิกออกสินค้า พร้อมระบบรายงานและแจ้งเตือนอัตโนมัติ

## Features หลัก

- **การจัดการสินค้า** - เพิ่ม แก้ไข ลบ ค้นหาสินค้าในคลัง
- **การรับสินค้าเข้าคลัง** - บันทึกการรับสินค้าพร้อมเอกสารอ้างอิง
- **การเบิกสินค้าออก** - บันทึกการเบิกสินค้าพร้อมระบุผู้เบิกและวัตถุประสงค์
- **รายงานหลากหลาย** - รายงานสต๊อก, Stock Card, รายงานรับ-เบิก
- **แจ้งเตือนสินค้าใกล้หมด** - ระบบแจ้งเตือนอัตโนมัติเมื่อสต๊อกต่ำกว่าที่กำหนด
- **ระบบ Backup** - สำรองข้อมูลอัตโนมัติและ Export เป็น Excel
- **การจัดการผู้ใช้** - ระบบ Role-based access control (Admin/Staff/User)

## Technology Stack

| Component | Technology |
|-----------|------------|
| Backend | Laravel 12 (PHP 8.2+) |
| Database | SQLite (default) / MySQL |
| Frontend | Blade Templates + Tailwind CSS |
| Build Tool | Vite |
| Authentication | Laravel Breeze |
| PDF Export | DomPDF / mPDF |
| Excel Export | PhpSpreadsheet |
| Backup | Spatie Laravel Backup |

## Quick Start

### ความต้องการของระบบ

- PHP 8.2 หรือสูงกว่า
- Composer
- Node.js 18+ และ npm
- Git

### การติดตั้ง

```bash
# 1. Clone repository
git clone <repository-url>
cd pj

# 2. ติดตั้ง dependencies
composer install
npm install

# 3. สร้างไฟล์ .env
cp .env.example .env

# 4. สร้าง Application Key
php artisan key:generate

# 5. รัน migration
php artisan migrate

# 6. Build frontend assets
npm run build

# 7. รัน development server
php artisan serve
```

### การรัน Development Mode

```bash
# วิธีที่ 1: รันแยกแต่ละ service
php artisan serve        # Backend server
npm run dev              # Vite dev server

# วิธีที่ 2: รันพร้อมกันทั้งหมด
composer dev
```

## โครงสร้างโปรเจ็ค

```
pj/
├── app/
│   ├── Console/Commands/     # Artisan commands
│   ├── Http/Controllers/     # Controllers
│   ├── Mail/                 # Mail classes
│   ├── Models/               # Eloquent models
│   ├── Observers/            # Model observers
│   └── Providers/            # Service providers
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── docs/                     # Documentation
├── public/                   # Public assets
├── resources/
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Web routes
│   └── auth.php              # Authentication routes
├── storage/                  # Storage files
└── tests/                    # Test files
```

## User Roles

| Role | สิทธิ์การใช้งาน |
|------|----------------|
| **Admin** | เข้าถึงทุกฟังก์ชัน, จัดการผู้ใช้, ตั้งค่าระบบ, Backup |
| **Staff** | จัดการสินค้า, รับ-เบิกสินค้า, ดูรายงาน |
| **User** | ดูสินค้า, ดูรายงานพื้นฐาน |

## เอกสารเพิ่มเติม

- [Architecture Document](./ARCHITECTURE.md) - สถาปัตยกรรมระบบ
- [API Documentation](./API.md) - รายละเอียด API
- [Database Schema](./DATABASE.md) - โครงสร้างฐานข้อมูล
- [Setup Guide](./SETUP.md) - คู่มือการติดตั้ง
- [Coding Standards](./CODING_STANDARDS.md) - มาตรฐานการเขียนโค้ด
- [Roadmap](./ROADMAP.md) - แผนพัฒนาในอนาคต

## การทดสอบ

```bash
# รัน tests ทั้งหมด
php artisan test

# รัน specific test
php artisan test --filter=AuthenticationTest
```

## License

MIT License
