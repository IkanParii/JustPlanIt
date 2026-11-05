# 📋 TaskFlow (JustPlanIt) - Secure Task & Planner Management

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**TaskFlow** adalah platform manajemen tugas (*task planner*) modern berbasis Laravel 12 dan Tailwind CSS yang dirancang dengan arsitektur aman dan performa tinggi. Aplikasi ini dilengkapi dengan mitigasi keamanan IDOR (*Insecure Direct Object References*), manajemen lampiran dokumen terisolasi, audit activity logging otomatis, serta pembagian peran User dan Administrator.

---

## ✨ Fitur Utama

- **User Workspace**: Dashboard statistik progres tugas (total tasks, completed, pending/in-progress, files hosted, dan deadline terdekat).
- **Task & Document Management**: Pembuatan tugas dengan tenggat waktu, prioritas, dan lampiran berkas dokumen (PDF, DOC, DOCX maks. 10MB) dengan otorisasi unduh ketat anti-IDOR.
- **Audit Trail & Activity Logs**: Pencatatan aktivitas otomatis (`login`, create task, upload berkas, update status) lengkap dengan timestamp dan IP address.
- **Admin Control Center**: Monitoring metrik sistem global (total users, total tasks, penggunaan storage disk) serta manajemen akun pengguna.
- **Role-Based Access Control**: Pemisahan otorisasi yang jelas antara akun `user` dan `admin`.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS, FontAwesome 6, Vite
- **Autentikasi**: Laravel Breeze
- **Database**: SQLite / MySQL / MariaDB

---

## 🚀 Panduan Instalasi Lokal

```bash
# 1. Clone repository
git clone https://github.com/IkanParii/JustPlanIt.git
cd JustPlanIt

# 2. Install dependensi PHP & Node.js
composer install
npm install

# 3. Setup environment file
cp .env.example .env
php artisan key:generate

# 4. Jalankan database migration
touch database/database.sqlite
php artisan migrate

# 5. Hubungkan symlink storage berkas
php artisan storage:link

# 6. Jalankan dev server & Vite
npm run dev
php artisan serve
```

---

## 🐳 Demo Mode & Docker Deployment

Untuk deployment publik dengan **Docker**, **Demo Mode** (registrasi tampilan saja + penolakan backend HTTP 403), **DemoSeeder** (data dummy realistis konteks Indonesia), dan script **reset otomatis harian**, silakan gunakan branch:

👉 **[`feat/demo-mode`](https://github.com/IkanParii/JustPlanIt/tree/feat/demo-mode)**

---

## 📄 Lisensi

Project ini dilisensikan di bawah lisensi [MIT](LICENSE).
