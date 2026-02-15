# SIG Wisata Manggarai Barat

Sistem Informasi Geografis untuk pengelolaan objek wisata di Kabupaten Manggarai Barat.

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue.js 3 + Inertia.js
- **Database**: MySQL (MariaDB)
- **Cache**: Redis
- **Styling**: Tailwind CSS
- **Map**: Leaflet.js

## 📚 Documentation

- **[Development Setup](README.Development.md)** - Setup development environment (localhost)
- **[Production Setup with Cloudflare Tunnel](README.Cloudflare-Tunnel.md)** - Deploy to production
- **[Docker Setup Guide](README.Docker.md)** - General Docker information

## Docker Setup (Production)

> **For Development**: See [README.Development.md](README.Development.md) for development environment setup.

### Prerequisites

- Docker
- Docker Compose

### Installation

1. Clone repository:
```bash
git clone <repository-url>
cd sig-wisata
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Build and start containers:
```bash
docker-compose up -d --build
```

4. Run migrations:
```bash
docker-compose exec app php artisan migrate --seed
```

5. Access application:
- **Web**: http://localhost
- **Vite Dev Server**: http://localhost:5173

### Available Commands

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f app
docker-compose logs -f nginx

# Run artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan tinker

# Run npm commands
docker-compose exec node npm run dev
docker-compose exec node npm run build

# Access container shell
docker-compose exec app sh
docker-compose exec mysql mysql -u sig_wisata_user -p

# Rebuild containers
docker-compose up -d --build

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### Services

- **app**: PHP 8.2 FPM (Laravel application)
- **nginx**: Web server (port 80)
- **mysql**: MariaDB database (port 3307)
- **redis**: Cache & session storage (port 6379)
- **node**: Vite development server (port 5173)

### Troubleshooting

**Permission issues:**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

**Database connection error:**
```bash
# Check if MySQL is ready
docker-compose exec mysql mysqladmin ping -h localhost

# Restart services
docker-compose restart app
```

**Clear all data and restart:**
```bash
docker-compose down -v
docker-compose up -d --build
```

## License

MIT License

