#!/bin/sh
set -e

# Pastikan struktur direktori storage lengkap
mkdir -p /var/www/html/storage/framework/cache \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/app/public/attachments \
         /var/www/html/bootstrap/cache

# Jalankan perintah utama container
exec "$@"
