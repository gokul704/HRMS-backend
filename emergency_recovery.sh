#!/bin/bash

# Emergency Recovery Script for MySQL + StaffIQ Application Failures
# Usage: ./emergency_recovery.sh

set -e

echo "🚨 EMERGENCY RECOVERY - MySQL + StaffIQ Application"
echo "================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}✅${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠️${NC} $1"
}

print_error() {
    echo -e "${RED}❌${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ️${NC} $1"
}

print_emergency() {
    echo -e "${RED}🚨 EMERGENCY:${NC} $1"
}

echo "🔍 Phase 1: System Diagnostics"
echo "=============================="
echo ""

# Check if we're on a server
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    print_info "Detected Linux server environment"
elif [[ "$OSTYPE" == "darwin"* ]]; then
    print_warning "Detected macOS - this script is for production servers"
else
    print_warning "Unknown OS - proceed with caution"
fi

echo ""
echo "🗄️ MySQL Emergency Recovery"
echo "=========================="
echo ""

# Check MySQL service status
print_info "Checking MySQL service status..."
if command -v systemctl &> /dev/null; then
    if sudo systemctl is-active --quiet mysql; then
        print_status "MySQL service is running"
    else
        print_emergency "MySQL service is NOT running"
        echo "Attempting to start MySQL..."
        sudo systemctl start mysql
        sleep 3
        if sudo systemctl is-active --quiet mysql; then
            print_status "MySQL service started successfully"
        else
            print_error "Failed to start MySQL service"
            echo "Manual intervention required:"
            echo "  sudo systemctl status mysql"
            echo "  sudo journalctl -u mysql"
        fi
    fi
else
    print_warning "systemctl not available - checking MySQL process"
    if pgrep mysql > /dev/null; then
        print_status "MySQL process is running"
    else
        print_emergency "MySQL process not found"
    fi
fi

# Check MySQL connection
print_info "Testing MySQL connection..."
if command -v mysql &> /dev/null; then
    if mysql -u root -p -e "SELECT 1;" 2>/dev/null; then
        print_status "MySQL root connection successful"
    else
        print_emergency "MySQL root connection failed"
        echo "Trying to reset MySQL root password..."
        echo "Run: sudo mysql_secure_installation"
    fi
else
    print_error "MySQL client not installed"
fi

echo ""
echo "🌐 StaffIQ Application Emergency Recovery"
echo "===================================="
echo ""

# Check PHP
print_info "Checking PHP installation..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php --version | head -n1)
    print_status "PHP installed: $PHP_VERSION"
else
    print_emergency "PHP not installed"
    echo "Install PHP: sudo apt install php8.2 php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl"
fi

# Check Composer
print_info "Checking Composer..."
if command -v composer &> /dev/null; then
    print_status "Composer is installed"
else
    print_emergency "Composer not installed"
    echo "Install Composer: curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer"
fi

# Check Laravel application
print_info "Checking Laravel application..."
if [ -f "artisan" ]; then
    print_status "Laravel application found"
else
    print_emergency "Laravel application not found"
    echo "Check if you're in the correct directory"
    exit 1
fi

# Check .env file
print_info "Checking environment configuration..."
if [ -f ".env" ]; then
    print_status ".env file exists"

    # Check critical environment variables
    if grep -q "APP_KEY=base64:" .env; then
        print_status "Application key is set"
    else
        print_emergency "Application key not set"
        echo "Generating application key..."
        php artisan key:generate
    fi

    if grep -q "APP_DEBUG=false" .env; then
        print_status "Debug mode is disabled (production ready)"
    else
        print_warning "Debug mode is enabled - set APP_DEBUG=false for production"
    fi
else
    print_emergency ".env file missing"
    if [ -f ".env.example" ]; then
        echo "Copying .env.example to .env..."
        cp .env.example .env
        php artisan key:generate
    else
        print_error "No .env.example found - manual intervention required"
    fi
fi

echo ""
echo "🔧 Phase 2: Emergency Fixes"
echo "==========================="
echo ""

# Clear all caches
print_info "Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
print_status "All caches cleared"

# Fix storage permissions
print_info "Fixing storage permissions..."
mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache
print_status "Storage permissions fixed"

# Check database connection
print_info "Testing database connection..."
if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch (Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }" 2>/dev/null | grep -q "SUCCESS"; then
    print_status "Database connection successful"

    # Run migrations
    print_info "Running database migrations..."
    if php artisan migrate --force; then
        print_status "Database migrations completed"
    else
        print_error "Database migrations failed"
    fi
else
    ERROR_MSG=$(php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch (Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }" 2>/dev/null | grep "FAILED")
    print_emergency "Database connection failed: $ERROR_MSG"

    echo ""
    echo "🔧 Database Emergency Fixes:"
    echo "1. Check MySQL service: sudo systemctl status mysql"
    echo "2. Check MySQL logs: sudo tail -f /var/log/mysql/error.log"
    echo "3. Test MySQL connection: mysql -u root -p"
    echo "4. Create database: mysql -u root -p -e \"CREATE DATABASE IF NOT EXISTS hrms_backend;\""
    echo "5. Update .env with correct database credentials"
fi

# Optimize application
print_info "Optimizing application..."
php artisan optimize
print_status "Application optimized"

echo ""
echo "🌐 Phase 3: Web Server Recovery"
echo "==============================="
echo ""

# Check web server
print_info "Checking web server..."
if command -v nginx &> /dev/null; then
    if sudo systemctl is-active --quiet nginx; then
        print_status "Nginx is running"
    else
        print_emergency "Nginx is not running"
        sudo systemctl start nginx
    fi
elif command -v apache2 &> /dev/null; then
    if sudo systemctl is-active --quiet apache2; then
        print_status "Apache is running"
    else
        print_emergency "Apache is not running"
        sudo systemctl start apache2
    fi
else
    print_warning "No web server detected - using PHP built-in server"
    echo "Start with: php artisan serve --host=0.0.0.0 --port=8000"
fi

echo ""
echo "🔍 Phase 4: Health Checks"
echo "========================="
echo ""

# Test application endpoints
print_info "Testing application health..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200"; then
    print_status "Application is responding on localhost:8000"
else
    print_emergency "Application not responding on localhost:8000"
fi

if curl -s http://localhost:8000/health > /dev/null 2>&1; then
    print_status "Health endpoint is working"
else
    print_warning "Health endpoint not responding"
fi

echo ""
echo "📋 Emergency Recovery Summary"
echo "============================"
echo ""

echo "✅ Completed:"
echo "  - System diagnostics"
echo "  - MySQL service check"
echo "  - PHP/Composer verification"
echo "  - Laravel application check"
echo "  - Environment configuration"
echo "  - Cache clearing"
echo "  - Storage permissions"
echo "  - Database connection test"
echo "  - Application optimization"
echo "  - Web server check"
echo "  - Health endpoint test"
echo ""

echo "🚨 If issues persist:"
echo "1. Check logs: tail -f storage/logs/laravel.log"
echo "2. Check MySQL logs: sudo tail -f /var/log/mysql/error.log"
echo "3. Check web server logs: sudo tail -f /var/log/nginx/error.log"
echo "4. Restart services: sudo systemctl restart mysql nginx"
echo "5. Check disk space: df -h"
echo "6. Check memory usage: free -h"
echo ""

echo "📞 Emergency Commands:"
echo "====================="
echo "sudo systemctl restart mysql"
echo "sudo systemctl restart nginx"
echo "php artisan config:clear"
echo "php artisan cache:clear"
echo "php artisan migrate --force"
echo "chmod -R 775 storage bootstrap/cache"
echo ""

print_status "Emergency recovery completed! 🚀"

echo ""
echo "🔍 For detailed diagnostics, run: ./diagnose.sh"
echo "📖 For deployment help, see: DEPLOYMENT_CHECKLIST.md"
