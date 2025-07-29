#!/bin/bash

# HRMS Production Setup Script
# This script helps configure the application for production deployment

set -e

echo "🔧 Setting up HRMS for production deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${BLUE}[SETUP]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_header "Production Environment Configuration"

# Create production .env if it doesn't exist
if [ ! -f ".env.production" ]; then
    print_status "Creating production environment file..."
    cp .env .env.production

    # Update production settings
    sed -i '' 's/APP_ENV=local/APP_ENV=production/' .env.production
    sed -i '' 's/APP_DEBUG=true/APP_DEBUG=false/' .env.production
    sed -i '' 's/APP_URL=http:\/\/localhost/APP_URL=https:\/\/your-domain.com/' .env.production

    print_status "Production .env file created: .env.production"
    print_warning "Please update .env.production with your actual domain and database credentials"
else
    print_status "Production environment file already exists"
fi

print_header "Web Server Configuration"

# Create Apache configuration
cat > apache.conf << 'EOF'
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /path/to/your/hrms-backend/public

    <Directory /path/to/your/hrms-backend/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/hrms_error.log
    CustomLog ${APACHE_LOG_DIR}/hrms_access.log combined
</VirtualHost>
EOF

# Create Nginx configuration
cat > nginx.conf << 'EOF'
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /path/to/your/hrms-backend/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

print_status "Web server configurations created:"
echo "  - apache.conf (for Apache)"
echo "  - nginx.conf (for Nginx)"

print_header "SSL Certificate Setup"

cat > ssl_setup.md << 'EOF'
# SSL Certificate Setup

## Using Let's Encrypt (Recommended)

1. Install Certbot:
   ```bash
   # Ubuntu/Debian
   sudo apt install certbot python3-certbot-apache

   # CentOS/RHEL
   sudo yum install certbot python3-certbot-nginx
   ```

2. Obtain SSL certificate:
   ```bash
   # For Apache
   sudo certbot --apache -d your-domain.com -d www.your-domain.com

   # For Nginx
   sudo certbot --nginx -d your-domain.com -d www.your-domain.com
   ```

3. Auto-renewal:
   ```bash
   sudo crontab -e
   # Add this line:
   0 12 * * * /usr/bin/certbot renew --quiet
   ```

## Using Cloudflare (Alternative)

1. Sign up for Cloudflare
2. Add your domain
3. Update nameservers
4. Enable SSL/TLS encryption mode: Full (strict)
5. Enable "Always Use HTTPS" rule
EOF

print_status "SSL setup guide created: ssl_setup.md"

print_header "Monitoring Setup"

cat > monitoring_setup.md << 'EOF'
# Monitoring Setup

## Application Monitoring

1. **Log Monitoring**:
   ```bash
   # Monitor Laravel logs
   tail -f storage/logs/laravel.log

   # Monitor web server logs
   tail -f /var/log/apache2/hrms_error.log
   tail -f /var/log/nginx/hrms_error.log
   ```

2. **Health Checks**:
   - http://your-domain.com/health
   - http://your-domain.com/up
   - http://your-domain.com/api/test-db

3. **Database Monitoring**:
   ```bash
   # Check database connection
   php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';"
   ```

## Backup Strategy

1. **Database Backup**:
   ```bash
   # Create backup script
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
   ```

2. **Application Backup**:
   ```bash
   # Backup application files
   tar -czf hrms_backup_$(date +%Y%m%d_%H%M%S).tar.gz --exclude=node_modules --exclude=vendor .
   ```

3. **Automated Backups**:
   ```bash
   # Add to crontab
   0 2 * * * /path/to/backup_script.sh
   ```
EOF

print_status "Monitoring guide created: monitoring_setup.md"

print_header "Security Checklist"

cat > security_checklist.md << 'EOF'
# Security Checklist

## ✅ Completed
- [x] APP_DEBUG=false in production
- [x] Strong APP_KEY generated
- [x] File permissions set correctly
- [x] Application optimized for production

## 🔧 To Complete
- [ ] Configure HTTPS/SSL certificate
- [ ] Set up firewall rules
- [ ] Configure rate limiting
- [ ] Set up CORS properly
- [ ] Enable security headers
- [ ] Configure backup strategy
- [ ] Set up monitoring and alerting
- [ ] Regular security updates

## 🔒 Security Headers (Add to web server config)

### Apache (.htaccess in public/):
```apache
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

### Nginx:
```nginx
add_header X-Content-Type-Options nosniff;
add_header X-Frame-Options DENY;
add_header X-XSS-Protection "1; mode=block";
add_header Referrer-Policy "strict-origin-when-cross-origin";
```
EOF

print_status "Security checklist created: security_checklist.md"

print_header "Deployment Summary"

echo ""
echo "🎉 Production setup completed!"
echo ""
echo "📁 Generated files:"
echo "  - .env.production (update with your settings)"
echo "  - apache.conf (Apache configuration)"
echo "  - nginx.conf (Nginx configuration)"
echo "  - ssl_setup.md (SSL certificate guide)"
echo "  - monitoring_setup.md (Monitoring guide)"
echo "  - security_checklist.md (Security checklist)"
echo ""
echo "🚀 Next steps:"
echo "1. Update .env.production with your domain and database credentials"
echo "2. Choose and configure your web server (Apache or Nginx)"
echo "3. Set up SSL certificate (see ssl_setup.md)"
echo "4. Configure monitoring and backups (see monitoring_setup.md)"
echo "5. Complete security checklist (see security_checklist.md)"
echo ""
echo "🔗 Test your deployment:"
echo "  - Health check: http://your-domain.com/health"
echo "  - Application: http://your-domain.com"
echo ""
echo "📖 For detailed instructions, see DEPLOYMENT_CHECKLIST.md"
