# Cloudflare Tunnel Setup Guide

Panduan lengkap untuk setup Cloudflare Tunnel agar aplikasi dapat diakses melalui domain `sig.kopiflores.my.id` tanpa perlu expose port atau IP publik.

## Keuntungan Cloudflare Tunnel

✅ **Tidak perlu port forwarding** - Port 80/443 tidak perlu dibuka  
✅ **Tidak perlu IP publik** - Bisa digunakan di belakang NAT/firewall  
✅ **SSL/TLS otomatis** - HTTPS gratis dari Cloudflare  
✅ **Lebih aman** - Tidak ada inbound connections ke server  
✅ **Gratis** - Tersedia di semua plan Cloudflare termasuk free tier  

## Prerequisites

- ✅ Cloudflare account (daftar gratis di [cloudflare.com](https://dash.cloudflare.com/sign-up))
- ✅ Domain `kopiflores.my.id` sudah terdaftar di Cloudflare
- ✅ Docker dan Docker Compose sudah terinstall
- ✅ Aplikasi sudah running di Docker

## Step 1: Create Cloudflare Tunnel

### 1.1 Login ke Cloudflare Zero Trust

1. Buka browser dan kunjungi: **https://one.dash.cloudflare.com/**
2. Login dengan akun Cloudflare Anda
3. Jika pertama kali, Anda akan diminta setup team name (bisa pakai nama apapun)

### 1.2 Create New Tunnel

1. Di sidebar kiri, klik **Networks** → **Tunnels**
2. Klik tombol **Create a tunnel**
3. Pilih **Cloudflared** (bukan Quick Tunnels)
4. Klik **Next**

### 1.3 Name Your Tunnel

1. Masukkan nama tunnel: `sig-wisata-tunnel` (atau nama lain yang Anda inginkan)
2. Klik **Save tunnel**

### 1.4 Copy Tunnel Token

Setelah tunnel dibuat, Anda akan melihat halaman "Install and run a connector":

1. **PENTING:** Copy token yang ditampilkan
   - Token berbentuk string panjang yang dimulai dengan `eyJ...`
   - Contoh: `eyJhIjoiYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXoxMjM0NTY3ODkwIiwidCI6IjEyMzQ1Njc4LTEyMzQtMTIzNC0xMjM0LTEyMzQ1Njc4OTBhYiIsInMiOiJhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ejEyMzQ1Njc4OTAifQ==`
2. Simpan token ini dengan aman (Anda akan memasukkannya ke `.env` nanti)
3. **Jangan klik Next dulu**, kita akan konfigurasi public hostname dulu

### 1.5 Configure Public Hostname

Masih di halaman yang sama:

1. Scroll ke bawah ke section **Public Hostnames**
2. Klik **Add a public hostname**
3. Isi form:
   - **Subdomain:** `sig`
   - **Domain:** `kopiflores.my.id` (pilih dari dropdown)
   - **Path:** (kosongkan)
   - **Service Type:** `HTTP`
   - **URL:** `nginx:80`
4. Klik **Save hostname**

> [!IMPORTANT]
> Service URL harus `nginx:80` (bukan `localhost:8080`). Ini adalah nama service Docker yang akan diakses oleh cloudflared container.

### 1.6 Finish Setup

1. Klik **Next** atau **Finish** untuk menyelesaikan wizard
2. Tunnel sekarang sudah dibuat, tapi statusnya akan "DOWN" sampai kita start cloudflared container

## Step 2: Configure Docker

### 2.1 Add Tunnel Token to .env

1. Buka file `.env` di project directory:
   ```bash
   nano /mnt/Data/Coding/project/sig-wisata/.env
   ```

2. Cari baris `CLOUDFLARE_TUNNEL_TOKEN=`

3. Paste token yang Anda copy tadi:
   ```bash
   CLOUDFLARE_TUNNEL_TOKEN=eyJhIjoiYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXoxMjM0NTY3ODkwIiwidCI6IjEyMzQ1Njc4LTEyMzQtMTIzNC0xMjM0LTEyMzQ1Njc4OTBhYiIsInMiOiJhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ejEyMzQ1Njc4OTAifQ==
   ```

4. Save file (Ctrl+O, Enter, Ctrl+X)

### 2.2 Verify Configuration

Pastikan konfigurasi sudah benar:

```bash
# Check .env file
grep CLOUDFLARE_TUNNEL_TOKEN .env

# Should show your token (not empty)
```

## Step 3: Deploy Cloudflared Container

### 3.1 Clear Laravel Cache

```bash
# Clear configuration cache
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
```

### 3.2 Restart Containers

```bash
# Stop all containers
docker compose down

# Start with cloudflared
docker compose up -d

# Check status
docker compose ps
```

**Expected output:**
```
NAME                    STATUS
sig_wisata_app          Up
sig_wisata_cloudflared  Up
sig_wisata_db           Up (healthy)
sig_wisata_nginx        Up
sig_wisata_redis        Up
```

### 3.3 Check Cloudflared Logs

```bash
# View cloudflared logs
docker compose logs -f cloudflared
```

**Expected output (successful connection):**
```
INF Starting tunnel tunnelID=...
INF Connection registered connIndex=0 ...
INF Connection established ...
```

**If you see errors:**
- `Invalid token` → Check token di `.env`, pastikan tidak ada spasi atau karakter tambahan
- `Cannot connect to nginx` → Pastikan nginx container running: `docker compose ps nginx`

## Step 4: Verify Tunnel Status

### 4.1 Check in Cloudflare Dashboard

1. Kembali ke Cloudflare Zero Trust dashboard
2. Go to **Networks** → **Tunnels**
3. Cari tunnel `sig-wisata-tunnel`
4. Status harus **HEALTHY** (hijau)
5. Di kolom **Connectors**, harus ada **1 connector** active

### 4.2 Test DNS Resolution

```bash
# Check if domain resolves
nslookup sig.kopiflores.my.id

# Should return Cloudflare IPs (not your server IP)
```

### 4.3 Test Public Access

```bash
# Test from command line
curl -I https://sig.kopiflores.my.id

# Expected: HTTP/2 200 OK
```

**Or open in browser:**
```
https://sig.kopiflores.my.id
```

## Step 5: Application Testing

Verify semua fitur aplikasi bekerja dengan baik:

### 5.1 Basic Functionality

- [ ] Homepage loads correctly
- [ ] CSS dan JavaScript loaded (no 404 errors)
- [ ] Images displayed properly
- [ ] No mixed content warnings in browser console

### 5.2 Authentication

- [ ] Login page accessible
- [ ] Login works correctly
- [ ] Session persists after login
- [ ] Logout works

### 5.3 Admin Features

- [ ] Admin dashboard accessible
- [ ] CRUD operations work (create, read, update, delete)
- [ ] File uploads work
- [ ] Map features functional

### 5.4 Performance

- [ ] Page load time acceptable (< 3 seconds)
- [ ] No timeout errors
- [ ] API calls respond quickly

## Troubleshooting

### Tunnel Status: DOWN

**Possible causes:**
1. Token salah atau tidak valid
2. Cloudflared container tidak running
3. Network issue

**Solutions:**
```bash
# Check if cloudflared is running
docker compose ps cloudflared

# Check logs for errors
docker compose logs cloudflared

# Restart cloudflared
docker compose restart cloudflared

# If still down, recreate with new token
docker compose down
docker compose up -d
```

### 502 Bad Gateway

**Possible causes:**
1. Nginx container tidak running
2. Service URL salah di tunnel config
3. Network issue antara cloudflared dan nginx

**Solutions:**
```bash
# Check nginx status
docker compose ps nginx

# Test nginx from cloudflared container
docker compose exec cloudflared wget -O- http://nginx:80

# Check nginx logs
docker compose logs nginx

# Verify tunnel public hostname config:
# Should be: nginx:80 (NOT localhost:8080)
```

### SSL Certificate Errors

Cloudflare Tunnel automatically handles SSL. Jika ada masalah:

1. Check Cloudflare SSL/TLS mode:
   - Go to **SSL/TLS** → **Overview**
   - Set to **Full** or **Flexible**
2. Clear browser cache and cookies
3. Try incognito/private mode

### Application Not Loading Assets

**Possible causes:**
1. APP_URL tidak sesuai
2. Asset path salah
3. Cache issue

**Solutions:**
```bash
# Verify APP_URL in .env
grep APP_URL .env
# Should be: https://sig.kopiflores.my.id

# Clear Laravel cache
docker compose exec app php artisan config:cache
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear

# Rebuild assets if needed
npm run build
```

### Cannot Access from External Network

**Possible causes:**
1. Tunnel not healthy
2. DNS not propagated yet
3. Cloudflare proxy not enabled

**Solutions:**
```bash
# Check tunnel status in dashboard
# Status must be HEALTHY

# Check DNS propagation
# https://dnschecker.org/#A/sig.kopiflores.my.id

# Verify proxy is enabled (orange cloud) in DNS settings
```

## Advanced Configuration

### Multiple Public Hostnames

Anda bisa menambahkan multiple hostnames untuk satu tunnel:

1. Di Cloudflare dashboard, go to tunnel detail
2. Klik **Public Hostname** tab
3. Klik **Add a public hostname**
4. Contoh:
   - `api.kopiflores.my.id` → `http://nginx:80/api`
   - `admin.kopiflores.my.id` → `http://nginx:80/admin`

### Custom Path Routing

Anda bisa route different paths ke different services:

```
sig.kopiflores.my.id/app → http://nginx:80
sig.kopiflores.my.id/api → http://api-service:3000
```

### Access Control

Cloudflare Zero Trust menyediakan access control:

1. Go to **Access** → **Applications**
2. Create new application
3. Set policies (email domain, IP range, etc.)
4. Protect sensitive endpoints

### Monitoring

Monitor tunnel health dan traffic:

1. **Tunnel Analytics:**
   - Networks → Tunnels → [Your Tunnel] → Analytics
   - View requests, errors, bandwidth

2. **Logs:**
   - Logs → Zero Trust
   - Filter by tunnel name

3. **Alerts:**
   - Notifications → Configure alerts
   - Get notified if tunnel goes down

## Security Best Practices

### 1. Restrict Access by IP (Optional)

Di Cloudflare Firewall:
1. Go to **Security** → **WAF**
2. Create firewall rule:
   ```
   If IP is not in [your office IP]
   Then Block
   ```

### 2. Rate Limiting

Protect against DDoS:
1. Go to **Security** → **Rate Limiting Rules**
2. Create rule:
   ```
   If requests > 100 per minute
   Then Challenge or Block
   ```

### 3. Enable Bot Protection

1. Go to **Security** → **Bots**
2. Enable **Bot Fight Mode** (free)
3. Or configure **Super Bot Fight Mode** (paid)

### 4. Use Access Policies

Untuk admin area:
1. Create Access Application untuk `/admin/*`
2. Require email authentication
3. Or require specific email domain

## Maintenance

### Updating Cloudflared

Cloudflared image akan auto-update saat container restart:

```bash
# Pull latest image
docker compose pull cloudflared

# Restart with new image
docker compose up -d cloudflared
```

### Rotating Tunnel Token

Jika token compromised:

1. Di Cloudflare dashboard, delete old tunnel
2. Create new tunnel dengan token baru
3. Update `.env` dengan token baru
4. Restart containers

### Backup Configuration

Backup important files:
```bash
# Backup .env
cp .env .env.backup

# Backup docker-compose.yml
cp docker-compose.yml docker-compose.yml.backup
```

## Cost

Cloudflare Tunnel is **100% FREE** on all Cloudflare plans, including the free tier.

**What's included:**
- ✅ Unlimited bandwidth
- ✅ Unlimited tunnels
- ✅ Unlimited public hostnames
- ✅ DDoS protection
- ✅ SSL/TLS certificates
- ✅ Basic analytics

## Support

**Cloudflare Documentation:**
- https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/

**Community:**
- Cloudflare Community: https://community.cloudflare.com/
- Discord: https://discord.cloudflare.com/

**Issues:**
- Check logs: `docker compose logs cloudflared`
- Check Cloudflare status: https://www.cloudflarestatus.com/
