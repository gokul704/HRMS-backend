#!/bin/bash

# StaffIQ Backend Diagnostic Script
# Usage: ./diagnose.sh

echo "🔍 StaffIQ Backend Diagnostic Report"
echo "=================================="
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

echo "📋 System Information"
echo "---------------------"
print_info "PHP Version: $(php --version | head -n1)"
print_info "Laravel Version: $(php artisan --version)"
print_info "Composer Version: $(composer --version | head -n1)"
echo ""

echo "🔧 Application Status"
echo "-------------------"
if [ -f ".env" ]; then
    print_status "Environment file exists"
else
    print_error "Environment file missing"
fi

if [ -f "artisan" ]; then
    print_status "Laravel artisan file exists"
else
    print_error "Laravel artisan file missing"
fi

# Check APP_KEY
if grep -q "APP_KEY=base64:" .env; then
    print_status "Application key is set"
else
    print_warning "Application key may not be set"
fi

# Check APP_DEBUG
if grep -q "APP_DEBUG=false" .env; then
    print_status "Debug mode is disabled (production ready)"
elif grep -q "APP_DEBUG=true" .env; then
    print_warning "Debug mode is enabled (not recommended for production)"
else
    print_warning "APP_DEBUG not found in .env"
fi
echo ""

echo "🗄️ Database Status"
echo "-----------------"
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'SUCCESS';" 2>/dev/null | grep -q "SUCCESS"; then
    print_status "Database connection successful"
else
    print_error "Database connection failed"
fi

# Check migrations
MIGRATION_STATUS=$(php artisan migrate:status 2>/dev/null | grep -c "Ran" || echo "0")
if [ "$MIGRATION_STATUS" -gt 0 ]; then
    print_status "Migrations are applied ($MIGRATION_STATUS migrations ran)"
else
    print_error "No migrations have been run"
fi
echo ""

echo "📁 Storage & Permissions"
echo "----------------------"
if [ -d "storage" ]; then
    print_status "Storage directory exists"

    # Check storage subdirectories
    for dir in "framework/sessions" "framework/views" "framework/cache" "logs"; do
        if [ -d "storage/$dir" ]; then
            print_status "storage/$dir exists"
        else
            print_warning "storage/$dir missing"
        fi
    done

    # Check permissions
    if [ -w "storage" ]; then
        print_status "Storage directory is writable"
    else
        print_error "Storage directory is not writable"
    fi
else
    print_error "Storage directory missing"
fi

if [ -d "bootstrap/cache" ]; then
    print_status "Bootstrap cache directory exists"
    if [ -w "bootstrap/cache" ]; then
        print_status "Bootstrap cache directory is writable"
    else
        print_error "Bootstrap cache directory is not writable"
    fi
else
    print_error "Bootstrap cache directory missing"
fi
echo ""

echo "🛣️ Routes & Configuration"
echo "------------------------"
ROUTE_COUNT=$(php artisan route:list 2>/dev/null | grep -c "GET\|POST\|PUT\|DELETE\|PATCH" || echo "0")
if [ "$ROUTE_COUNT" -gt 0 ]; then
    print_status "Routes are registered ($ROUTE_COUNT routes found)"
else
    print_error "No routes found"
fi

# Check if application is responding
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200"; then
    print_status "Application is responding on localhost:8000"
else
    print_warning "Application not responding on localhost:8000"
fi
echo ""

echo "🔍 Health Checks"
echo "---------------"
# Test health endpoint
if curl -s http://localhost:8000/health > /dev/null 2>&1; then
    print_status "Health endpoint is working"
else
    print_warning "Health endpoint not responding"
fi

# Test database endpoint
if curl -s http://localhost:8000/api/test-db > /dev/null 2>&1; then
    print_status "Database test endpoint is working"
else
    print_warning "Database test endpoint not responding"
fi
echo ""

echo "📦 Dependencies"
echo "--------------"
if [ -f "composer.json" ]; then
    print_status "Composer.json exists"
    if [ -d "vendor" ]; then
        print_status "Vendor directory exists"
    else
        print_error "Vendor directory missing - run 'composer install'"
    fi
else
    print_error "Composer.json missing"
fi

if [ -f "package.json" ]; then
    print_status "Package.json exists"
    if [ -d "node_modules" ]; then
        print_status "Node modules exist"
    else
        print_warning "Node modules missing - run 'npm install'"
    fi
else
    print_info "No package.json found (no frontend assets)"
fi
echo ""

echo "📊 Cache Status"
echo "--------------"
if php artisan config:show app.name > /dev/null 2>&1; then
    print_status "Configuration cache is working"
else
    print_warning "Configuration cache may need clearing"
fi

if php artisan route:list > /dev/null 2>&1; then
    print_status "Route cache is working"
else
    print_warning "Route cache may need clearing"
fi
echo ""

echo "🎯 Recommendations"
echo "-----------------"
echo ""

if grep -q "APP_DEBUG=true" .env; then
    print_warning "Set APP_DEBUG=false for production"
fi

if [ ! -w "storage" ]; then
    print_warning "Run: chmod -R 775 storage bootstrap/cache"
fi

if [ ! -d "vendor" ]; then
    print_warning "Run: composer install"
fi

if [ "$MIGRATION_STATUS" -eq 0 ]; then
    print_warning "Run: php artisan migrate"
fi

echo ""
echo "🚀 Quick Fix Commands:"
echo "----------------------"
echo "composer install"
echo "php artisan key:generate"
echo "php artisan migrate"
echo "php artisan config:clear"
echo "php artisan cache:clear"
echo "php artisan route:clear"
echo "php artisan view:clear"
echo "php artisan optimize"
echo "chmod -R 775 storage bootstrap/cache"
echo ""
echo "📖 For detailed deployment instructions, see DEPLOYMENT_CHECKLIST.md"
