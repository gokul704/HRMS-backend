#!/bin/bash

# StaffIQ Backend Production Deployment Script
# Usage: ./deploy.sh

set -e  # Exit on any error

echo "🚀 Starting StaffIQ Backend Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_status "Checking PHP version..."
php --version

print_status "Checking Composer..."
composer --version

print_status "Installing/updating Composer dependencies..."
composer install --no-dev --optimize-autoloader

print_status "Creating storage directories..."
mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache

print_status "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache

print_status "Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

print_status "Running database migrations..."
php artisan migrate --force

print_status "Optimizing application for production..."
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_status "Checking database connection..."
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'SUCCESS';" 2>/dev/null | grep -q "SUCCESS"; then
    print_status "Database connection successful"
else
    print_error "Database connection failed"
    exit 1
fi

print_status "Checking application key..."
if [ -z "$(grep '^APP_KEY=' .env | cut -d'=' -f2)" ]; then
    print_warning "Application key not set, generating..."
    php artisan key:generate
else
    print_status "Application key already set"
fi

print_status "Testing application..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200"; then
    print_status "Application is responding correctly"
else
    print_warning "Application might not be running or accessible"
fi

print_status "Deployment completed successfully! 🎉"

echo ""
echo "📋 Next steps:"
echo "1. Configure your web server (Apache/Nginx)"
echo "2. Set up SSL certificate (recommended)"
echo "3. Configure environment variables for production"
echo "4. Set up monitoring and logging"
echo "5. Configure backup strategy"
echo ""
echo "🔗 Health check endpoints:"
echo "- http://your-domain.com/health"
echo "- http://your-domain.com/up"
echo "- http://your-domain.com/api/test-db"
echo ""
echo "📖 See DEPLOYMENT_CHECKLIST.md for detailed instructions"
