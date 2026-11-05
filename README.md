# 📋 TaskFlow (JustPlanIt) - Secure Task & Planner Management

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)

**TaskFlow** adalah aplikasi manajemen tugas (*task planner*) modern berbasis Laravel 12 dan Tailwind CSS yang dirancang dengan standar keamanan tinggi (*enterprise-grade security*). Aplikasi ini dilengkapi dengan proteksi IDOR (*Insecure Direct Object References*), penyimpanan lampiran dokumen terisolasi, audit activity logging otomatis, serta pemisahan peran (*role-based access*) antara User dan Administrator.

Aplikasi ini telah dilengkapi dengan **Demo Mode**, arsitektur **Docker ultra-hemat RAM (~45-50 MB)**, dan siap di-deploy ke VPS Linux (Ubuntu).

---

## 🚀 Fitur Utama

1. **Dashboard Statistik Real-time**:
   - Rekap total tugas, tugas selesai, tugas *in-progress*, dan jumlah berkas yang di-host.
   - Indikator tenggat waktu mendesak (*upcoming deadlines*).
   - Riwayat 5 aktivitas terbaru pengguna.
2. **Task & Planner Management**:
   - Manajemen tugas lengkap (*Create, Read, Update Status, Delete*).
   - Fitur unggah lampiran dokumen (PDF, DOC, DOCX maks. 10MB) dengan otorisasi unduh anti-IDOR.
   - Transisi status tugas cepat (`pending` $\leftrightarrow$ `completed`).
3. **Audit Log & Activity Trail**:
   - Pencatatan otomatis setiap aksi penting (`login`, pembuatan tugas, unggah dokumen, perubahan status, dsb.) lengkap dengan timestamp dan IP address pengguna.
4. **Admin Control Center**:
   - Monitoring metrik sistem global: total pengguna terdaftar, total tugas, utilisasi penyimpanan berkas, dan log audit real-time seluruh sistem.
   - Manajemen pengguna dengan sistem isolasi penghapusan data terkait (*cascading cleanup*).
5. **Mode Demo Portofolio Publik (`DEMO_MODE`)**:
   - Registrasi publik dialihkan ke mode "Tampilan Saja" (backend menolak submit dengan status HTTP 403).
   - Banner kredensial demo otomatis di halaman login dan register.
   - Proteksi akun demo utama dari penghapusan di panel admin.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS, FontAwesome 6, Vite
- **Autentikasi**: Laravel Breeze (Session/Cookie Authentication)
- **Database**: MariaDB / MySQL (Didukung juga SQLite)
- **Containerization**: Multi-stage Docker (Node 20 Alpine + PHP 8.2-FPM Alpine + Nginx Alpine)
- **Reverse Proxy**: Caddy / Nginx

---

## 🧪 Akun Demo

Saat `DEMO_MODE=true` aktif, silakan login menggunakan kredensial demo berikut:

| Peran (*Role*) | Email | Password | Akses & Wewenang |
|---|---|---|---|
| 👑 **Administrator** | `admin@demo.com` | `DemoPassword123!` | Akses penuh Admin Panel, pemantauan seluruh audit log, dan statistik server |
| 👤 **User Reguler** | `user@demo.com` | `DemoPassword123!` | Akses Workspace pribadi, pembuatan tugas, unggah berkas, dan log mandiri |

---

## ⚙️ Variabel Lingkungan: `DEMO_MODE`

Pengaturan demo dikendalikan sepenuhnya melalui file `.env`:

```env
# Mode Demo Portofolio (true = aktif, false = non-aktif/produksi normal)
DEMO_MODE=true
```

### Efek saat `DEMO_MODE=true`:
1. **Registrasi Tampilan Saja**: Pengunjung tetap bisa membuka halaman register, namun saat disubmit backend menolak dengan **HTTP 403 Forbidden** dan pesan: *"Registrasi dinonaktifkan di mode demo. Silakan login dengan akun demo yang tersedia."*
2. **Banner Akun Demo**: Menampilkan kartu kredensial demo di halaman login & register.
3. **Proteksi Akun Demo**: Melarang penghapusan akun `admin@demo.com` dan `user@demo.com` di admin panel.
4. **Demo Seeder**: Mengizinkan eksekusi `php artisan db:seed --class=DemoSeeder` dan script `reset-demo.sh`.

---

## 🐳 Panduan Deployment Docker di VPS (Ubuntu 22.04)

### Prasyarat
- Docker & Docker Compose v2 terpasang di VPS.
- External Docker network `shared-net` sudah dibuat (`docker network create shared-net`).

### Langkah 1: Siapkan Database di Central MariaDB
Masuk ke container MariaDB terpusat Anda di VPS:
```bash
docker exec -it central-mariadb mariadb -u root -p
```
Jalankan perintah SQL berikut:
```sql
CREATE DATABASE taskflow_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'taskflow_user'@'%' IDENTIFIED BY 'PasswordAmanDatabase123!';
GRANT ALL PRIVILEGES ON taskflow_demo.* TO 'taskflow_user'@'%';
FLUSH PRIVILEGES;
EXIT;
```

### Langkah 2: Clone Repository & Pindah ke Branch Demo
```bash
git clone https://github.com/IkanParii/JustPlanIt.git /home/ubuntu/taskflow
cd /home/ubuntu/taskflow
git checkout feat/demo-mode
```

### Langkah 3: Konfigurasi Environment `.env`
Salin template konfigurasi:
```bash
cp .env.example .env
chmod 600 .env
nano .env
```
Sesuaikan nilai-nilai berikut di `.env`:
```env
APP_NAME=TaskFlow
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://taskflow.domainku.com

DEMO_MODE=true
APP_PORT=8088

DB_CONNECTION=mysql
DB_HOST=central-mariadb
DB_PORT=3306
DB_DATABASE=taskflow_demo
DB_USERNAME=taskflow_user
DB_PASSWORD=PasswordAmanDatabase123!
```

### Langkah 4: Build & Jalankan Container
```bash
# Build image dan jalankan container di background
docker compose up -d --build

# Generate App Key
docker compose exec -T app php artisan key:generate --force

# Jalankan migrasi dan isi data dummy demo realistis
docker compose exec -T app php artisan migrate:fresh --seed --class=DemoSeeder --force

# Hubungkan symlink storage publik
docker compose exec -T app php artisan storage:link --force

# Optimasi cache Laravel
docker compose exec -T app php artisan optimize
```

### Langkah 5: Sambungkan ke Reverse Proxy (Caddy / Nginx)

#### Opsi A: Menggunakan Caddy di VPS
Tambahkan blok berikut pada `/etc/caddy/Caddyfile`:
```caddy
taskflow.domainku.com {
    reverse_proxy 127.0.0.1:8088
}
```
Lalu reload Caddy:
```bash
sudo systemctl reload caddy
```

#### Opsi B: Menggunakan Nginx Host di VPS
Tambahkan server block di `/etc/nginx/sites-available/taskflow`:
```nginx
server {
    server_name taskflow.domainku.com;

    location / {
        proxy_pass http://127.0.0.1:8088;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```
Aktifkan dan pasang SSL Let's Encrypt:
```bash
sudo ln -s /etc/nginx/sites-available/taskflow /etc/nginx/sites-enabled/
sudo certbot --nginx -d taskflow.domainku.com
```

---

## 🔄 Otomatisasi Reset Database Harian (`reset-demo.sh`)

Script `reset-demo.sh` sudah dilengkapi dengan **pengaman tingkat tinggi**: script akan **langsung berhenti (abort)** jika `DEMO_MODE` bukan `"true"`.

### 1. Berikan Izin Eksekusi
```bash
chmod +x reset-demo.sh
```

### 2. Tes Jalankan Manual
```bash
./reset-demo.sh
```

### 3. Pasang Jadwal Cron di Host VPS (Setiap Jam 03:00 WIB)
Buka crontab:
```bash
crontab -e
```
Tambahkan baris berikut:
```cron
0 3 * * * /home/ubuntu/taskflow/reset-demo.sh >> /var/log/taskflow-reset.log 2>&1
```

---

## 🔒 Praktik Keamanan yang Diterapkan

- **Zero DB Exposure**: Port 3306 MariaDB tidak dibuka ke publik/host; komunikasi antar container berjalan di dalam Docker network privat `shared-net`.
- **Non-Root Execution**: Container PHP berjalan menggunakan user non-root `www-data`.
- **Resource Hard-Cap**: Limit RAM ketat (App: 96MB, Web: 24MB), bebas dari risiko OOM (*Out Of Memory*).
- **IDOR Protection**: Validasi ketat kepemilikan data sebelum user dapat mengakses, mengunduh, atau memodifikasi tugas.
- **Rate Limiting**: Endpoint login dibatasi maksimal 5 percobaan gagal per menit.
- **Hardened Nginx**: Header `X-Frame-Options`, `X-Content-Type-Options`, `nosniff`, serta pemblokiran akses ke file sensitif (`.env`, `.git`).

---

## 📄 Lisensi
Open-source under the [MIT License](LICENSE).
