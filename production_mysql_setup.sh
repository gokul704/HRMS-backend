#!/bin/bash

# Production MySQL Setup Script for HRMS Backend
# Usage: ./production_mysql_setup.sh

set -e

echo "🚀 Setting up MySQL for Production"
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

# Function to get user input
get_input() {
    local prompt="$1"
    local default="$2"
    local input

    if [ -n "$default" ]; then
        read -p "$prompt [$default]: " input
        echo "${input:-$default}"
    else
        read -p "$prompt: " input
        echo "$input"
    fi
}

echo "📋 Production MySQL Configuration"
echo "-------------------------------"
echo ""

# Get production MySQL details
DB_HOST=$(get_input "Enter MySQL host" "localhost")
DB_PORT=$(get_input "Enter MySQL port" "3306")
DB_DATABASE=$(get_input "Enter database name" "hrms_backend")
DB_USERNAME=$(get_input "Enter MySQL username" "hrms_user")
DB_PASSWORD=$(get_input "Enter MySQL password" "")
DB_ROOT_PASSWORD=$(get_input "Enter MySQL root password (for setup)" "")

echo ""
echo "🔧 Setting up MySQL for production..."
echo ""

# Check if MySQL is installed
if ! command -v mysql &> /dev/null; then
    print_error "MySQL client is not installed"
    echo "Install MySQL first:"
    echo "  Ubuntu/Debian: sudo apt install mysql-server mysql-client"
    echo "  CentOS/RHEL: sudo yum install mysql-server mysql"
    echo "  macOS: brew install mysql"
    exit 1
fi

print_status "MySQL client is installed"

# Test root connection
if mysql -u root -p"$DB_ROOT_PASSWORD" -e "SELECT 1;" 2>/dev/null; then
    print_status "MySQL root connection successful"
else
    print_error "Cannot connect to MySQL as root"
    print_warning "Make sure MySQL is running and root password is correct"
    exit 1
fi

# Create database if it doesn't exist
print_info "Creating database '$DB_DATABASE'..."
mysql -u root -p"$DB_ROOT_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
print_status "Database created/verified"

# Create user if it doesn't exist
print_info "Creating user '$DB_USERNAME'..."
mysql -u root -p"$DB_ROOT_PASSWORD" -e "CREATE USER IF NOT EXISTS '$DB_USERNAME'@'%' IDENTIFIED BY '$DB_PASSWORD';" 2>/dev/null
print_status "User created/verified"

# Grant permissions
print_info "Granting permissions to '$DB_USERNAME' on '$DB_DATABASE'..."
mysql -u root -p"$DB_ROOT_PASSWORD" -e "GRANT ALL PRIVILEGES ON \`$DB_DATABASE\`.* TO '$DB_USERNAME'@'%';" 2>/dev/null
mysql -u root -p"$DB_ROOT_PASSWORD" -e "FLUSH PRIVILEGES;" 2>/dev/null
print_status "Permissions granted"

# Test new user connection
if mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "USE \`$DB_DATABASE\`; SELECT 1;" 2>/dev/null; then
    print_status "New user connection test successful"
else
    print_error "New user connection test failed"
    exit 1
fi

echo ""
echo "📝 Updating .env file..."
echo ""

# Backup current .env
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
print_status "Backup created: .env.backup.$(date +%Y%m%d_%H%M%S)"

# Update .env with production settings
sed -i '' "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
sed -i '' "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
sed -i '' "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
sed -i '' "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
sed -i '' "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env

print_status ".env file updated with production settings"

echo ""
echo "🔧 Testing Laravel database connection..."
echo ""

# Test Laravel connection
if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch (Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }" 2>/dev/null | grep -q "SUCCESS"; then
    print_status "Laravel database connection successful"
else
    ERROR_MSG=$(php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch (Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }" 2>/dev/null | grep "FAILED")
    print_error "Laravel database connection failed: $ERROR_MSG"
    exit 1
fi

echo ""
echo "📊 Running database migrations..."
echo ""

# Run migrations
if php artisan migrate --force; then
    print_status "Database migrations completed successfully"
else
    print_error "Database migrations failed"
    exit 1
fi

echo ""
echo "🔒 Security Recommendations"
echo "-------------------------"
echo ""

echo "1. MySQL Security:"
echo "   - Change default root password"
echo "   - Remove anonymous users: DELETE FROM mysql.user WHERE User='';"
echo "   - Remove test database: DROP DATABASE IF EXISTS test;"
echo "   - Restrict root access: UPDATE mysql.user SET Host='localhost' WHERE User='root';"
echo ""

echo "2. Firewall Configuration:"
echo "   - Allow MySQL port (3306) only from your application server"
echo "   - Use VPN or SSH tunnel for remote connections"
echo ""

echo "3. MySQL Configuration (/etc/mysql/mysql.conf.d/mysqld.cnf):"
echo "   [mysqld]"
echo "   bind-address = 0.0.0.0"
echo "   max_connections = 200"
echo "   innodb_buffer_pool_size = 256M"
echo "   query_cache_size = 32M"
echo "   slow_query_log = 1"
echo "   long_query_time = 2"
echo ""

echo "4. Backup Strategy:"
echo "   - Set up automated daily backups"
echo "   - Test backup restoration regularly"
echo "   - Store backups in multiple locations"
echo ""

echo "📋 Production .env Configuration"
echo "-------------------------------"
echo ""
echo "Your production .env should now contain:"
echo "DB_CONNECTION=mysql"
echo "DB_HOST=$DB_HOST"
echo "DB_PORT=$DB_PORT"
echo "DB_DATABASE=$DB_DATABASE"
echo "DB_USERNAME=$DB_USERNAME"
echo "DB_PASSWORD=********"
echo ""

echo "🎯 Next Steps"
echo "-------------"
echo "1. Test your application: php artisan serve"
echo "2. Monitor MySQL logs: sudo tail -f /var/log/mysql/error.log"
echo "3. Set up automated backups"
echo "4. Configure monitoring and alerting"
echo "5. Set up SSL/TLS for secure connections"
echo ""

print_status "MySQL production setup completed successfully! 🎉"

echo ""
echo "🔍 To troubleshoot issues, run: ./mysql_troubleshoot.sh"
echo "📖 For deployment help, see: DEPLOYMENT_CHECKLIST.md"
