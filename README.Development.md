# Development Environment Setup

Panduan untuk menjalankan project dalam mode development yang terpisah dari production.

## 📋 Overview

Development environment ini dirancang untuk:
- ✅ Berjalan bersamaan dengan production tanpa konflik
- ✅ Menggunakan database terpisah (`sig_wisata_dev`)
- ✅ Menggunakan port berbeda
- ✅ Tidak menggunakan Cloudflare Tunnel (akses via localhost)

## 🚀 Quick Start

### 1. Start Development Environment

```bash
# Start semua container development
docker-compose -f docker-compose.dev.yml --env-file .env.dev up -d

# Lihat logs
docker-compose -f docker-compose.dev.yml --env-file .env.dev logs -f

# Lihat status container
docker-compose -f docker-compose.dev.yml --env-file .env.dev ps
```

### 2. Setup Database (First Time Only)

```bash
# Jalankan migration
docker exec sig_wisata_dev_app php artisan migrate

# (Optional) Seed database dengan data dummy
docker exec sig_wisata_dev_app php artisan db:seed
```

### 3. Akses Aplikasi

- **Web Application**: http://localhost:8081
- **Vite Dev Server (HMR)**: http://localhost:5175
- **MySQL**: `localhost:3308`
- **Redis**: `localhost:6381`

### 4. Stop Development Environment

```bash
# Stop semua container
docker-compose -f docker-compose.dev.yml --env-file .env.dev down

# Stop dan hapus volumes (HATI-HATI: akan menghapus database development)
docker-compose -f docker-compose.dev.yml --env-file .env.dev down -v
```

## 🔧 Configuration

### Port Mapping

| Service | Development | Production |
|---------|-------------|------------|
| Nginx   | 8081        | 8080       |
| MySQL   | 3308        | 3307       |
| Redis   | 6381        | 6380       |
| Vite    | 5175        | 5174       |

### Environment Files

- **Development**: `.env.dev` (REDIS_PORT=6379, FORWARD_REDIS_PORT=6381)
- **Production**: `.env` (REDIS_PORT=6380)

### Docker Compose Files

- **Development**: `docker-compose.dev.yml`
- **Production**: `docker-compose.yml`

### Project Names

- **Development**: `sig_wisata_dev`
- **Production**: `sig_wisata`

## 📝 Common Commands

### Artisan Commands

```bash
# Jalankan artisan command
docker exec sig_wisata_dev_app php artisan <command>

# Contoh: Clear cache
docker exec sig_wisata_dev_app php artisan cache:clear

# Contoh: Generate key
docker exec sig_wisata_dev_app php artisan key:generate
```

### Composer Commands

```bash
# Install dependencies
docker exec sig_wisata_dev_app composer install

# Update dependencies
docker exec sig_wisata_dev_app composer update
```

### NPM Commands

```bash
# Install dependencies
docker exec sig_wisata_dev_node npm install

# Build assets
docker exec sig_wisata_dev_node npm run build
```

### Database Commands

```bash
# Akses MySQL CLI
docker exec -it sig_wisata_dev_db mysql -u sig_wisata_user -p sig_wisata_dev
# Password: sig_wisata_pass

# Backup database
docker exec sig_wisata_dev_db mysqldump -u sig_wisata_user -psig_wisata_pass sig_wisata_dev > backup.sql

# Restore database
docker exec -i sig_wisata_dev_db mysql -u sig_wisata_user -psig_wisata_pass sig_wisata_dev < backup.sql
```

## 🔄 Development Workflow

### 1. Pull Latest Code

```bash
git pull origin main
```

### 2. Start Development Environment

```bash
docker-compose -f docker-compose.dev.yml --env-file .env.dev up -d
```

### 3. Update Dependencies (if needed)

```bash
docker exec sig_wisata_dev_app composer install
docker exec sig_wisata_dev_node npm install
```

### 4. Run Migrations (if needed)

```bash
docker exec sig_wisata_dev_app php artisan migrate
```

### 5. Develop & Test

- Buat perubahan code
- Test di http://localhost:8081
- Vite akan auto-reload saat ada perubahan

### 6. Commit & Push

```bash
git add .
git commit -m "Your commit message"
git push origin main
```

> **Note**: Push code ke repository **TIDAK** akan mempengaruhi production karena production dan development menggunakan environment terpisah.

## 🆚 Development vs Production

| Aspect | Development | Production |
|--------|-------------|------------|
| **Access** | http://localhost:8081 | https://sig.kopiflores.my.id |
| **Database** | sig_wisata_dev | sig_wisata |
| **Debug Mode** | Enabled | Disabled |
| **Cloudflare Tunnel** | ❌ Disabled | ✅ Enabled |
| **Hot Module Reload** | ✅ Enabled | ❌ Disabled |
| **Environment File** | .env.dev | .env |
| **Docker Compose** | docker-compose.dev.yml | docker-compose.yml |

## 🐛 Troubleshooting

### Port Already in Use

Jika port sudah digunakan, ubah port di `.env.dev`:

```env
NGINX_PORT=8082
MYSQL_PORT=3309
REDIS_PORT=6382
VITE_PORT=5176
```

### Container Name Conflict

Jika ada konflik nama container, pastikan `COMPOSE_PROJECT_NAME` berbeda:

```env
COMPOSE_PROJECT_NAME=sig_wisata_dev_2
```

### Database Connection Error

Pastikan MySQL container sudah healthy:

```bash
docker-compose -f docker-compose.dev.yml --env-file .env.dev ps
```

Tunggu hingga status MySQL menjadi `healthy`.

### Vite Not Hot Reloading

Restart node container:

```bash
docker-compose -f docker-compose.dev.yml --env-file .env.dev restart node
```

## 📚 Related Documentation

- [Production Setup (Cloudflare Tunnel)](README.Cloudflare-Tunnel.md)
- [Docker Setup](README.Docker.md)
- [Main README](README.md)

## ⚠️ Important Notes

1. **Jangan commit `.env.dev`** - File ini sudah ada di `.gitignore`
2. **Database terpisah** - Development dan production menggunakan database berbeda
3. **Production tetap aman** - Perubahan di development tidak mempengaruhi production
4. **Cloudflare Tunnel** - Hanya aktif di production, tidak di development
