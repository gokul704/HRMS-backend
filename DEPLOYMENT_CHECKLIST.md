# HRMS Backend Deployment Checklist

## ✅ Current Status
- Application is running successfully on port 8000
- Database connection is working
- All migrations are applied
- Routes are properly registered
- Configuration is optimized

## 🔧 Common Deployment Issues & Solutions

### 1. Environment Configuration
```bash
# Ensure .env file is properly configured
cp .env.example .env
php artisan key:generate
```

### 2. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed database (if needed)
php artisan db:seed
```

### 3. Storage Permissions
```bash
# Create storage directories
mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### 4. Cache Optimization
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Dependencies
```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies (if using frontend assets)
npm install
npm run build
```

### 6. Application Key
```bash
# Generate application key
php artisan key:generate
```

### 7. Queue Workers (if using queues)
```bash
# Start queue workers
php artisan queue:work
```

### 8. Web Server Configuration

#### For Apache (.htaccess should be in public/):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### For Nginx:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/app/public;

    add_index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🚀 Production Deployment Steps

1. **Set Environment to Production**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize Application**
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan optimize
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Set Proper Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

4. **Configure Web Server**
   - Point document root to `public/` directory
   - Ensure `.htaccess` is in place for Apache
   - Configure Nginx as shown above

5. **Start Application**
   ```bash
   # For development
   php artisan serve --host=0.0.0.0 --port=8000

   # For production (use proper web server)
   # Apache/Nginx + PHP-FPM
   ```

## 🔍 Troubleshooting

### Check Application Status
```bash
# Test database connection
php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';"

# Check routes
php artisan route:list

# Check configuration
php artisan config:show
```

### Common Error Solutions

1. **500 Internal Server Error**
   - Check storage permissions
   - Clear all caches
   - Check .env configuration

2. **Database Connection Error**
   - Verify database credentials in .env
   - Ensure database server is running
   - Check database exists

3. **Route Not Found**
   - Clear route cache: `php artisan route:clear`
   - Check route definitions
   - Ensure web server is configured correctly

4. **Permission Denied**
   - Set proper file permissions
   - Check storage directory permissions
   - Ensure web server can write to storage

## 📊 Health Check Endpoints

Your application includes these health check endpoints:
- `GET /health` - Application health check
- `GET /up` - Laravel's built-in health check
- `GET /api/test-db` - Database connection test

## 🔐 Security Checklist

- [ ] APP_DEBUG=false in production
- [ ] Strong APP_KEY generated
- [ ] Database credentials secured
- [ ] File permissions set correctly
- [ ] HTTPS enabled (if applicable)
- [ ] CORS configured properly
- [ ] Rate limiting enabled

## 📝 Logs

Monitor application logs:
```bash
tail -f storage/logs/laravel.log
```

## 🆘 Emergency Commands

```bash
# Emergency cache clear
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear

# Emergency restart
php artisan optimize:clear
php artisan optimize
``` 
