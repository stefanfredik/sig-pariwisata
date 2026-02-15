# Development Workflow Guide

## Quick Start Commands

### Initial Setup (Phase 1)
```bash
# Create Laravel project
composer create-project laravel/laravel sig-wisata
cd sig-wisata

# Install dependencies
composer require laravel/sanctum intervention/image
npm install @inertiajs/vue3 @vitejs/plugin-vue
npm install tailwindcss postcss autoprefixer
npm install leaflet leaflet-routing-machine leaflet.markercluster
npm install chart.js

# Initialize Tailwind
npx tailwindcss init -p

# Setup database
cp .env.example .env
# Edit .env with your database credentials
php artisan key:generate
php artisan migrate

# Start development servers
php artisan serve
# In another terminal:
npm run dev
```

### Daily Development Workflow
```bash
# Pull latest changes
git pull origin main

# Install any new dependencies
composer install
npm install

# Run migrations
php artisan migrate

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Start servers
php artisan serve
npm run dev
```

### Testing Workflow
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ObjekWisataTest.php

# Run tests with coverage
php artisan test --coverage

# Run tests in parallel (faster)
php artisan test --parallel
```

### Database Management
```bash
# Create new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all and re-run
php artisan migrate:fresh

# Run migrations with seeders
php artisan migrate:fresh --seed

# Create seeder
php artisan make:seeder TableNameSeeder
```

### Code Generation
```bash
# Create model with migration, factory, seeder
php artisan make:model ModelName -mfs

# Create controller
php artisan make:controller ControllerName

# Create resource controller
php artisan make:controller ControllerName --resource

# Create request validation
php artisan make:request RequestName

# Create middleware
php artisan make:middleware MiddlewareName

# Create service class
mkdir -p app/Services
touch app/Services/ServiceName.php
```

### Asset Management
```bash
# Compile assets for development
npm run dev

# Watch for changes
npm run dev -- --watch

# Build for production
npm run build

# Optimize images (after implementing ImageService)
php artisan images:optimize
```

### Cache Management
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear all caches
php artisan optimize:clear
```

## Git Workflow

### Branch Strategy
```bash
# Main branches
main          # Production-ready code
develop       # Integration branch

# Feature branches
feature/phase-1-setup
feature/auth-system
feature/objek-wisata-crud
feature/map-integration
feature/review-system

# Bugfix branches
bugfix/fix-image-upload
bugfix/fix-routing
```

### Commit Convention
```bash
# Format: <type>(<scope>): <subject>

# Types:
feat:     # New feature
fix:      # Bug fix
docs:     # Documentation
style:    # Formatting, missing semicolons, etc.
refactor: # Code restructuring
test:     # Adding tests
chore:    # Maintenance

# Examples:
git commit -m "feat(auth): implement admin login"
git commit -m "fix(upload): resolve image resize issue"
git commit -m "docs(readme): add installation instructions"
```

### Standard Workflow
```bash
# Start new feature
git checkout develop
git pull origin develop
git checkout -b feature/feature-name

# Make changes and commit
git add .
git commit -m "feat(scope): description"

# Push to remote
git push origin feature/feature-name

# Create pull request (via GitHub/GitLab)
# After review and approval, merge to develop

# Delete local branch after merge
git checkout develop
git pull origin develop
git branch -d feature/feature-name
```

## Code Quality Standards

### PHP (PSR-12)
```bash
# Install PHP CS Fixer
composer require --dev friendsofphp/php-cs-fixer

# Create config file .php-cs-fixer.php
# Run fixer
vendor/bin/php-cs-fixer fix
```

### ESLint for JavaScript
```bash
# Install ESLint
npm install --save-dev eslint eslint-plugin-vue

# Initialize ESLint
npx eslint --init

# Run linter
npm run lint

# Fix auto-fixable issues
npm run lint -- --fix
```

## Debugging Tools

### Laravel Debugbar
```bash
# Install
composer require barryvdh/laravel-debugbar --dev

# Publish config
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

### Laravel Telescope (Advanced)
```bash
# Install
composer require laravel/telescope --dev

# Install Telescope
php artisan telescope:install

# Migrate
php artisan migrate

# Access at: /telescope
```

### Vue DevTools
- Install browser extension: Vue.js devtools
- Access in browser developer tools

## Performance Monitoring

### Query Logging
```php
// In routes/web.php or AppServiceProvider
DB::listen(function ($query) {
    Log::info($query->sql, $query->bindings, $query->time);
});
```

### Slow Query Detection
```bash
# Enable MySQL slow query log
# In my.cnf or my.ini:
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log
long_query_time = 1
```

## Deployment Checklist

### Pre-Deployment
- [ ] All tests passing
- [ ] Code reviewed and approved
- [ ] Database migrations tested
- [ ] Environment variables configured
- [ ] Assets compiled for production
- [ ] Cache cleared
- [ ] Documentation updated

### Deployment Steps
```bash
# On production server

# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install --production

# 3. Run migrations
php artisan migrate --force

# 4. Build assets
npm run build

# 5. Clear and cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart services
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx

# 7. Verify deployment
curl -I https://yourdomain.com
```

### Post-Deployment
- [ ] Verify homepage loads
- [ ] Test critical user flows
- [ ] Check error logs
- [ ] Monitor performance
- [ ] Verify SSL certificate
- [ ] Test mobile responsiveness

## Troubleshooting

### Common Issues

**Issue: "Class not found"**
```bash
composer dump-autoload
php artisan clear-compiled
```

**Issue: "Mix manifest not found"**
```bash
npm run build
```

**Issue: "Permission denied" on storage**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**Issue: Database connection failed**
```bash
# Check .env database credentials
# Test connection:
php artisan tinker
>>> DB::connection()->getPdo();
```

**Issue: Assets not loading**
```bash
php artisan storage:link
npm run build
```

## Useful Artisan Commands

```bash
# List all routes
php artisan route:list

# List all registered commands
php artisan list

# Enter tinker (REPL)
php artisan tinker

# Generate application key
php artisan key:generate

# Create symbolic link for storage
php artisan storage:link

# Optimize application
php artisan optimize

# Clear all caches
php artisan optimize:clear

# Run queue worker
php artisan queue:work

# Run scheduler (in cron)
php artisan schedule:run
```

## Phase-Specific Notes

### Phase 1: Setup
- Focus on getting environment running
- Don't skip dependency installation
- Test both `php artisan serve` and `npm run dev`

### Phase 2-3: Backend Development
- Write migrations before models
- Use factories for test data
- Implement repositories for data access
- Validate all inputs

### Phase 4: Frontend Development
- Start with layout and components
- Use Tailwind utility classes
- Make components reusable
- Test responsive design early

### Phase 5: Map Integration
- Test with real coordinates
- Handle geolocation permissions
- Optimize marker rendering
- Test on mobile devices

### Phase 6-7: Features
- Test review workflow end-to-end
- Verify rating calculations
- Test dashboard charts with real data

### Phase 8: Optimization
- Profile before optimizing
- Measure improvements
- Don't over-optimize early

### Phase 9: Security
- Test rate limiting
- Verify file upload restrictions
- Check all forms for CSRF
- Test with malicious inputs

### Phase 10: Final Testing
- Test on different browsers
- Test on real mobile devices
- Get user feedback
- Fix critical bugs before deployment
