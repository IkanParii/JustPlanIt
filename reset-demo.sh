#!/bin/bash
# ==============================================================================
# SCRIPT RESET OTOMATIS MODE DEMO (TaskFlow / JustPlanIt)
# ==============================================================================
# Script ini digunakan untuk me-reset database dan men-seed ulang data dummy demo.
# WAJIB memiliki pengaman: Script HANYA akan berjalan jika DEMO_MODE=true.
# ==============================================================================

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

ENV_FILE="$SCRIPT_DIR/.env"
TIMESTAMP=$(date +"%Y-%m-%d %H:%M:%S")

echo "[$TIMESTAMP] ======================================================"
echo "[$TIMESTAMP] [TASKFLOW] Memeriksa verifikasi keamanan DEMO_MODE..."

# 1. PENGAMAN 1: Cek keberadaan file .env
if [ ! -f "$ENV_FILE" ]; then
    echo "[$TIMESTAMP] [ERROR] File .env tidak ditemukan di $SCRIPT_DIR!"
    echo "[$TIMESTAMP] [ABORT] Reset database dibatalkan."
    exit 1
fi

# 2. PENGAMAN 2: Baca environment variable DEMO_MODE dari .env
DEMO_MODE_ENV=$(grep -E '^DEMO_MODE=' "$ENV_FILE" | cut -d '=' -f2 | tr -d ' "\r\n')

if [ "$DEMO_MODE_ENV" != "true" ]; then
    echo "[$TIMESTAMP] [FATAL SECURITY ALERT]"
    echo "[$TIMESTAMP] Variabel DEMO_MODE terdeteksi bernilai: '$DEMO_MODE_ENV' (BUKAN 'true')."
    echo "[$TIMESTAMP] Pengaman aktif: Reset database DIBATALKAN untuk mencegah kehilangan data non-demo!"
    exit 1
fi

# 3. PENGAMAN 3: Verifikasi langsung ke dalam container aplikasi
IS_CONTAINER_DEMO=$(docker compose exec -T app php -r "echo config('app.demo_mode') ? 'true' : 'false';" 2>/dev/null || echo "failed")

if [ "$IS_CONTAINER_DEMO" != "true" ]; then
    echo "[$TIMESTAMP] [FATAL SECURITY ALERT]"
    echo "[$TIMESTAMP] Container melaporkan config('app.demo_mode') bernilai: '$IS_CONTAINER_DEMO'."
    echo "[$TIMESTAMP] Reset database DIBATALKAN."
    exit 1
fi

echo "[$TIMESTAMP] [VERIFIED] DEMO_MODE=true valid di host dan container."
echo "[$TIMESTAMP] [ACTION] Menjalankan migrate:fresh dan DemoSeeder..."

# 4. EKSEKUSI RESET & SEED
docker compose exec -T app php artisan migrate:fresh --seed --class=DemoSeeder --force

# 5. REFRESH SYMLINK STORAGE & CACHE
docker compose exec -T app php artisan storage:link --force || true
docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

echo "[$TIMESTAMP] [SUCCESS] Reset database dan seed data demo selesai dengan sukses!"
echo "[$TIMESTAMP] ======================================================"
