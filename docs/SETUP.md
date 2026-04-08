# Setup & Configuration Guide

## System Requirements

### Required
| Component | Minimum Version |
|-----------|-----------------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| npm | 9+ |
| Git | 2.x |

### PHP Extensions
```
BCMath
Ctype
cURL
DOM
Fileinfo
JSON
Mbstring
OpenSSL
PCRE
PDO
PDO_SQLite (หรือ PDO_MySQL)
Tokenizer
XML
GD (สำหรับ PDF generation)
```

ตรวจสอบ extensions:
```bash
php -m
```

---

## Installation Steps

### 1. Clone Repository

```bash
git clone <repository-url>
cd pj
```

### 2. Install PHP Dependencies

```bash
composer install
```

ถ้าต้องการติดตั้งสำหรับ production:
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
# สร้างไฟล์ .env จาก template
cp .env.example .env

# สร้าง Application Key
php artisan key:generate
```

### 5. Database Setup

**SQLite (Default):**
```bash
# สร้างไฟล์ database
touch database/database.sqlite

# รัน migrations
php artisan migrate
```

**MySQL (Alternative):**
```bash
# แก้ไข .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=your_password

# รัน migrations
php artisan migrate
```

### 6. Build Frontend Assets

**Development:**
```bash
npm run dev
```

**Production:**
```bash
npm run build
```

### 7. Create Admin User

```bash
php artisan tinker
```

```php
use App\Models\User;
use App\Models\Role;

$admin = Role::where('role_name', 'admin')->first();

User::create([
    'fname' => 'Admin',
    'lname' => 'User',
    'email' => 'admin@example.com',
    'phone' => '0812345678',
    'role_id' => $admin->role_id,
    'password' => bcrypt('password123'),
]);
```

### 8. Start Development Server

```bash
# วิธีที่ 1: แยก terminal
php artisan serve         # Terminal 1
npm run dev               # Terminal 2

# วิธีที่ 2: รวม (ใช้ concurrently)
composer dev
```

เข้าใช้งาน: http://localhost:8000

---

## Environment Variables

### Application Settings
```env
APP_NAME="Inventory System"   # ชื่อแอพ
APP_ENV=local                 # local, staging, production
APP_KEY=                      # Auto-generated
APP_DEBUG=true                # true สำหรับ dev, false สำหรับ prod
APP_URL=http://localhost      # URL หลัก
```

### Database Settings
```env
# SQLite
DB_CONNECTION=sqlite

# MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=secret
```

### Session & Cache
```env
SESSION_DRIVER=database       # file, database, redis
SESSION_LIFETIME=120          # minutes

CACHE_STORE=database          # file, database, redis
QUEUE_CONNECTION=database     # sync, database, redis
```

### Email Settings
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

สำหรับทดสอบ (ไม่ส่งจริง):
```env
MAIL_MAILER=log
```

### Backup Settings (Optional)
```env
BACKUP_DISK=local             # disk สำหรับเก็บ backup
BACKUP_NOTIFICATION_EMAIL=admin@example.com
```

---

## Dependencies

### Composer Packages (Production)

| Package | Purpose |
|---------|---------|
| laravel/framework | Laravel core |
| laravel/tinker | REPL สำหรับ debug |
| barryvdh/laravel-dompdf | สร้าง PDF |
| mpdf/mpdf | สร้าง PDF (alternative) |
| phpoffice/phpspreadsheet | Excel import/export |
| spatie/laravel-backup | Backup system |
| hisamu/php-xbase | อ่านไฟล์ DBF |

### Composer Packages (Development)

| Package | Purpose |
|---------|---------|
| laravel/breeze | Authentication UI |
| laravel/pail | Real-time log viewer |
| laravel/pint | Code formatting |
| laravel/sail | Docker development |
| fakerphp/faker | Fake data for testing |
| phpunit/phpunit | Testing framework |
| mockery/mockery | Mocking library |

### NPM Packages

| Package | Purpose |
|---------|---------|
| tailwindcss | CSS framework |
| @tailwindcss/forms | Form styling |
| alpinejs | JavaScript framework |
| vite | Build tool |
| laravel-vite-plugin | Laravel integration |
| autoprefixer | CSS vendor prefixes |
| postcss | CSS processing |

---

## Deployment Guide

### XAMPP (Windows)

1. **Copy project to htdocs:**
   ```
   C:\xampp\htdocs\pj\
   ```

2. **Configure Apache Virtual Host:**

   Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName inventory.local
       DocumentRoot "C:/xampp/htdocs/pj/public"
       <Directory "C:/xampp/htdocs/pj/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. **Edit hosts file:**

   `C:\Windows\System32\drivers\etc\hosts`:
   ```
   127.0.0.1 inventory.local
   ```

4. **Set permissions:**

   ต้องให้ Apache เขียนได้ใน:
   - `storage/`
   - `bootstrap/cache/`

5. **Build assets:**
   ```bash
   npm run build
   ```

6. **Optimize for production:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Linux Server

1. **Install requirements:**
   ```bash
   sudo apt update
   sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-gd
   sudo apt install nginx
   sudo apt install composer nodejs npm
   ```

2. **Clone and setup:**
   ```bash
   cd /var/www
   git clone <repo> inventory
   cd inventory
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --force
   ```

3. **Set permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/inventory
   sudo chmod -R 755 /var/www/inventory
   sudo chmod -R 775 /var/www/inventory/storage
   sudo chmod -R 775 /var/www/inventory/bootstrap/cache
   ```

4. **Nginx configuration:**
   ```nginx
   server {
       listen 80;
       server_name inventory.example.com;
       root /var/www/inventory/public;

       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

---

## Scheduled Tasks

### Setup Cron (Linux)

```bash
crontab -e
```

เพิ่มบรรทัด:
```cron
* * * * * cd /var/www/inventory && php artisan schedule:run >> /dev/null 2>&1
```

### Setup Task Scheduler (Windows)

สร้าง Task ที่รันทุกนาที:
```
Program: C:\php\php.exe
Arguments: C:\xampp\htdocs\pj\artisan schedule:run
Start in: C:\xampp\htdocs\pj
```

### Available Commands

| Command | Description |
|---------|-------------|
| `php artisan stock:alert` | ส่งแจ้งเตือนสต๊อกต่ำ |
| `php artisan stock:sync` | Sync current_stock |
| `php artisan backup:run` | สร้าง backup |
| `php artisan backup:run --only-db` | Backup เฉพาะ database |
| `php artisan backup:clean` | ลบ backup เก่า |

---

## Troubleshooting

### Common Issues

**1. Permission Denied on Storage**
```bash
chmod -R 775 storage bootstrap/cache
```

**2. Class not found**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

**3. Vite manifest not found**
```bash
npm run build
```

**4. CSRF token mismatch**
```bash
php artisan cache:clear
php artisan config:clear
# เปิด browser ใหม่
```

**5. Database locked (SQLite)**
```bash
# ตรวจสอบว่าไม่มี process อื่นใช้งาน database
# หรือเปลี่ยนไปใช้ MySQL
```

### Logs

```bash
# ดู logs
tail -f storage/logs/laravel.log

# real-time logs (dev)
php artisan pail
```

### Debug Mode

```env
APP_DEBUG=true
```

จะแสดง error ละเอียดบนหน้าเว็บ (ใช้เฉพาะ development)

---

## Health Check

```bash
# ตรวจสอบสถานะ
php artisan about

# ตรวจสอบ routes
php artisan route:list

# ตรวจสอบ migrations
php artisan migrate:status
```
