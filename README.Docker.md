# Docker Setup untuk SIG Wisata Manggarai Barat

## Konfigurasi Port

Docker Compose telah dikonfigurasi untuk menggunakan port non-default agar tidak bertabrakan dengan aplikasi lain di server. Port dapat dikustomisasi melalui file `.env`.

### Port Default

| Service | Port Internal | Port Eksternal Default | Environment Variable |
|---------|---------------|------------------------|---------------------|
| Nginx   | 80            | 8080                   | `NGINX_PORT`        |
| MySQL   | 3306          | 3307                   | `MYSQL_PORT`        |
| Redis   | 6379          | 6380                   | `REDIS_PORT`        |
| Vite    | 5173          | 5174                   | `VITE_PORT`         |

### Mengubah Port

Untuk mengubah port di server yang berbeda, edit file `.env`:

```bash
# Contoh: Menggunakan port yang berbeda
NGINX_PORT=9080
MYSQL_PORT=3308
REDIS_PORT=6381
VITE_PORT=5175
```

### Menjalankan Multiple Instance

Jika Anda ingin menjalankan beberapa instance aplikasi di server yang sama, ubah `COMPOSE_PROJECT_NAME` dan semua port:

```bash
# Instance 1 (.env)
COMPOSE_PROJECT_NAME=sig_wisata_prod
NGINX_PORT=8080
MYSQL_PORT=3307
REDIS_PORT=6380
VITE_PORT=5174

# Instance 2 (.env.staging)
COMPOSE_PROJECT_NAME=sig_wisata_staging
NGINX_PORT=8081
MYSQL_PORT=3308
REDIS_PORT=6381
VITE_PORT=5175
```

Jalankan dengan file .env yang berbeda:
```bash
docker-compose --env-file .env.staging up -d
```

## Instalasi dan Menjalankan

### 1. Setup Awal

```bash
# Copy file .env.example
cp .env.example .env

# Edit .env dan sesuaikan port jika diperlukan
nano .env

# Build dan jalankan containers
docker-compose up -d --build
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
docker-compose exec app composer install

# Install Node dependencies (jika belum otomatis)
docker-compose exec node npm install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate

# Seed database (opsional)
docker-compose exec app php artisan db:seed
```

### 3. Setup Storage Permissions

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

## Akses Aplikasi

Setelah semua container berjalan:

- **Web Application**: `http://localhost:8080` (atau port yang Anda set di `NGINX_PORT`)
- **Vite Dev Server**: `http://localhost:5174` (untuk hot module replacement)
- **MySQL**: `localhost:3307` (dari host machine)
- **Redis**: `localhost:6380` (dari host machine)

## Perintah Berguna

```bash
# Melihat status containers
docker-compose ps

# Melihat logs
docker-compose logs -f

# Melihat logs service tertentu
docker-compose logs -f app
docker-compose logs -f nginx

# Restart service tertentu
docker-compose restart app

# Stop semua containers
docker-compose down

# Stop dan hapus volumes (HATI-HATI: akan menghapus data database)
docker-compose down -v

# Masuk ke container
docker-compose exec app sh
docker-compose exec mysql mysql -u sig_wisata_user -p

# Run artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear

# Run npm commands
docker-compose exec node npm run build
docker-compose exec node npm run dev
```

## Troubleshooting

### Port sudah digunakan

Jika mendapat error "port already in use":

1. Cek port yang sedang digunakan:
   ```bash
   sudo netstat -tulpn | grep :8080
   ```

2. Ubah port di file `.env`:
   ```bash
   NGINX_PORT=9080  # Gunakan port yang tersedia
   ```

3. Restart containers:
   ```bash
   docker-compose down
   docker-compose up -d
   ```

### Permission Issues

Jika ada masalah permission di folder storage:

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Issues

Pastikan environment variables di `.env` sesuai dengan konfigurasi di `docker-compose.yml`:

```bash
DB_HOST=mysql
DB_PORT=3306  # Port internal, bukan eksternal
DB_DATABASE=sig_wisata
DB_USERNAME=sig_wisata_user
DB_PASSWORD=sig_wisata_pass
```

### Redis Connection Issues

Pastikan konfigurasi Redis di `.env`:

```bash
REDIS_HOST=redis
REDIS_PORT=6379  # Port internal, bukan eksternal
```

## Production Deployment

Untuk deployment production:

1. Ubah `.env`:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. Build assets:
   ```bash
   docker-compose exec node npm run build
   ```

3. Optimize Laravel:
   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```

4. Gunakan reverse proxy (Nginx/Apache) di host untuk SSL termination

## Backup Database

```bash
# Backup
docker-compose exec mysql mysqldump -u sig_wisata_user -psig_wisata_pass sig_wisata > backup.sql

# Restore
docker-compose exec -T mysql mysql -u sig_wisata_user -psig_wisata_pass sig_wisata < backup.sql
```
