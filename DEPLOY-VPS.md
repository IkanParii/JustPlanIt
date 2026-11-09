# 🚀 PANDUAN LENGKAP DEPLOY TASKFLOW DI VPS UBUNTU

Dokumentasi *step-by-step* untuk mendeploy aplikasi TaskFlow di VPS Ubuntu menggunakan Docker, terhubung ke `central-mariadb` via network `shared-net`, dan reverse proxy Nginx host (seperti `blog.ikanparii.my.id`).

---

## 📌 Rangkuman Informasi
* **Domain / Subdomain**: `taskflow.ikanparii.my.id`
* **Port Internal Web**: `127.0.0.1:8088` (hanya listen di localhost VPS)
* **Database Engine**: Container `central-mariadb` (Docker network: `shared-net`)
* **Database Name**: `taskflow_demo`
* **Database User**: `taskflow_user`
* **Database Password**: `PasswordAmanDatabase123!` (atau ganti sesuai selera)
* **Demo Mode**: `DEMO_MODE=true`

---

## 🛠️ LANGKAH 1: Setup DNS di Cloudflare

1. Login ke dashboard **Cloudflare** $\rightarrow$ pilih domain `ikanparii.my.id`.
2. Masuk ke tab **DNS** $\rightarrow$ klik **Add record**.
3. Isi parameter:
   * **Type**: `A`
   * **Name**: `taskflow`
   * **IPv4 address**: `[IP_VPS_KAMU]`
   * **Proxy status**: **Proxied (Awan Oranye)** atau **DNS Only (Awan Abu-abu)**.
   * **TTL**: `Auto`
4. Klik **Save**.
5. *(Jika pakai Awan Oranye)*: Masuk menu **SSL/TLS** di Cloudflare $\rightarrow$ pastikan mode diset ke **Full** atau **Full (Strict)**.

---

## 🗄️ LANGKAH 2: Bikin Database di `central-mariadb`

Jalankan perintah ini di terminal VPS untuk membuat database & user khusus TaskFlow:

```bash
docker exec -it central-mariadb mariadb -u root -p -e "
CREATE DATABASE IF NOT EXISTS taskflow_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'taskflow_user'@'%' IDENTIFIED BY 'PasswordAmanDatabase123!';
GRANT ALL PRIVILEGES ON taskflow_demo.* TO 'taskflow_user'@'%';
FLUSH PRIVILEGES;
"
```

---

## 📥 LANGKAH 3: Clone Project & Setup Environment

Jalankan di terminal VPS:

```bash
# 1. Clone langsung branch demo ke folder /home/ubuntu/taskflow
git clone -b feat/demo-mode https://github.com/IkanParii/JustPlanIt.git /home/ubuntu/taskflow
cd /home/ubuntu/taskflow

# 2. Buat file .env production
cp .env.example .env
chmod 600 .env

# 3. Sesuaikan konfigurasi di .env
sed -i 's/DEMO_MODE=false/DEMO_MODE=true/' .env
sed -i 's/DB_PASSWORD=ChangeThisToYourDatabasePassword/DB_PASSWORD=PasswordAmanDatabase123!/' .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
sed -i 's|APP_URL=https://demo.domainku.com|APP_URL=https://taskflow.ikanparii.my.id|' .env
```

---

## 🐳 LANGKAH 4: Build Container & Seeding Data Demo

Jalankan di dalam folder `/home/ubuntu/taskflow`:

```bash
# 1. Build image dan nyalakan container (App PHP & Web Nginx)
docker compose up -d --build

# 2. Generate Application Key Laravel
docker compose exec -T app php artisan key:generate --force

# 3. Migrasi tabel & isi data dummy realistis (Konteks Indonesia)
docker compose exec -T app php artisan migrate:fresh --seed --class=DemoSeeder --force

# 4. Hubungkan symlink storage publik (untuk unduh lampiran)
docker compose exec -T app php artisan storage:link --force

# 5. Optimasi cache performa production
docker compose exec -T app php artisan optimize

# 6. Beri izin eksekusi script reset demo
chmod +x reset-demo.sh
```

Cek status container:
```bash
docker compose ps
```
*Pastikan kedua container (`justplanit_app` dan `justplanit_web`) berstatus `Up (healthy)`.*

---

## 🌐 LANGKAH 5: Konfigurasi Nginx di Host VPS

Sama persis seperti konfigurasi `blog.ikanparii.my.id` Anda:

```bash
# 1. Buat file config Nginx
sudo nano /etc/nginx/sites-available/taskflow
```

Isi dengan konfigurasi berikut:
```nginx
server {
    listen 80;
    server_name taskflow.ikanparii.my.id;

    client_max_body_size 15M;

    location / {
        proxy_pass http://127.0.0.1:8088;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Aktifkan config dan pasang SSL otomatis dari Certbot:
```bash
# 2. Buat symlink ke sites-enabled
sudo ln -s /etc/nginx/sites-available/taskflow /etc/nginx/sites-enabled/

# 3. Tes sintaks Nginx
sudo nginx -t

# 4. Request SSL Let's Encrypt (HTTPS otomatis)
sudo certbot --nginx -d taskflow.ikanparii.my.id

# 5. Reload Nginx host
sudo systemctl reload nginx
```

---

## ⏰ LANGKAH 6: Pasang Cron Job Reset Otomatis (Jam 03:00 WIB)

Agar data demo di-reset bersih setiap subuh:

```bash
# 1. Tes script reset manual terlebih dahulu
./reset-demo.sh

# 2. Pasang cron job harian
(crontab -l 2>/dev/null; echo "0 3 * * * /home/ubuntu/taskflow/reset-demo.sh >> /var/log/taskflow-reset.log 2>&1") | crontab -
```

---

## 🔑 Akun Demo Siap Pakai

Website sudah bisa diakses di: **`https://taskflow.ikanparii.my.id`**

* 👑 **Administrator**: `admin@demo.com` | `DemoPassword123!`
* 👤 **User Reguler**: `user@demo.com` | `DemoPassword123!`

---

## 🛑 Troubleshooting Ringkas

1. **Error `network shared-net not found` saat `docker compose up`**:
   Pastikan network ada dengan perintah:
   `docker network create shared-net` (hanya jika belum dibuat sebelumnya).
2. **Lihat log error Laravel jika ada kendala**:
   `docker compose exec -T app tail -n 50 storage/logs/laravel.log`
3. **Cek pemakaian RAM aktual**:
   `docker stats justplanit_app justplanit_web --no-stream`
   *(Total penggunaan RAM hanya berkisar ~45-55 MB).*
