#!/bin/bash

# MySQL Production Troubleshooting Script
# Usage: ./mysql_troubleshoot.sh

echo "🔍 MySQL Production Troubleshooting"
echo "==================================="
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

echo "📋 Current Database Configuration"
echo "--------------------------------"
print_info "DB_CONNECTION: $(grep DB_CONNECTION .env | cut -d'=' -f2)"
print_info "DB_HOST: $(grep DB_HOST .env | cut -d'=' -f2)"
print_info "DB_PORT: $(grep DB_PORT .env | cut -d'=' -f2)"
print_info "DB_DATABASE: $(grep DB_DATABASE .env | cut -d'=' -f2)"
print_info "DB_USERNAME: $(grep DB_USERNAME .env | cut -d'=' -f2)"
print_info "DB_PASSWORD: $(grep DB_PASSWORD .env | cut -d'=' -f2 | sed 's/./*/g')"
echo ""

echo "🔧 Database Connection Test"
echo "-------------------------"
if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch (Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }" 2>/dev/null | grep -q "SUCCESS"; then
    print_status "Database connection successful"
else
    ERROR_MSG=$(php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch (Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }" 2>/dev/null | grep "FAILED")
    print_error "Database connection failed: $ERROR_MSG"
fi
echo ""

echo "🗄️ MySQL Service Status"
echo "----------------------"
# Check if MySQL is running
if command -v mysql &> /dev/null; then
    print_status "MySQL client is installed"

    # Try to connect with current credentials
    DB_HOST=$(grep DB_HOST .env | cut -d'=' -f2)
    DB_PORT=$(grep DB_PORT .env | cut -d'=' -f2)
    DB_USERNAME=$(grep DB_USERNAME .env | cut -d'=' -f2)
    DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d'=' -f2)
    DB_DATABASE=$(grep DB_DATABASE .env | cut -d'=' -f2)

    if mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1;" 2>/dev/null; then
        print_status "MySQL server is accessible"

        # Check if database exists
        if mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "USE $DB_DATABASE;" 2>/dev/null; then
            print_status "Database '$DB_DATABASE' exists"

            # Check table count
            TABLE_COUNT=$(mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "USE $DB_DATABASE; SHOW TABLES;" 2>/dev/null | wc -l)
            print_info "Found $TABLE_COUNT tables in database"
        else
            print_error "Database '$DB_DATABASE' does not exist"
        fi
    else
        print_error "Cannot connect to MySQL server"
        print_warning "Check if MySQL service is running"
        print_warning "Verify host, port, username, and password"
    fi
else
    print_error "MySQL client is not installed"
fi
echo ""

echo "📊 Migration Status"
echo "------------------"
MIGRATION_STATUS=$(php artisan migrate:status 2>/dev/null | grep -c "Ran" || echo "0")
if [ "$MIGRATION_STATUS" -gt 0 ]; then
    print_status "Migrations are applied ($MIGRATION_STATUS migrations ran)"
else
    print_error "No migrations have been run"
fi
echo ""

echo "🔍 Common Production MySQL Issues"
echo "--------------------------------"
echo ""

echo "1. Connection Issues:"
echo "   - MySQL service not running"
echo "   - Wrong host/port configuration"
echo "   - Firewall blocking connection"
echo "   - Wrong credentials"
echo ""

echo "2. Database Issues:"
echo "   - Database doesn't exist"
echo "   - Insufficient permissions"
echo "   - Character set issues"
echo "   - Connection limit reached"
echo ""

echo "3. Performance Issues:"
echo "   - Slow queries"
echo "   - Memory constraints"
echo "   - Disk space issues"
echo "   - Connection pooling"
echo ""

echo "🚀 Quick Fixes for Production"
echo "----------------------------"
echo ""

echo "1. Check MySQL Service:"
echo "   sudo systemctl status mysql"
echo "   sudo systemctl start mysql"
echo ""

echo "2. Create Database (if missing):"
echo "   mysql -u root -p -e \"CREATE DATABASE IF NOT EXISTS hrms_backend;\""
echo ""

echo "3. Grant Permissions:"
echo "   mysql -u root -p -e \"GRANT ALL PRIVILEGES ON hrms_backend.* TO 'root'@'%';\""
echo "   mysql -u root -p -e \"FLUSH PRIVILEGES;\""
echo ""

echo "4. Update .env for Production:"
echo "   DB_HOST=your-production-mysql-host"
echo "   DB_PORT=3306"
echo "   DB_DATABASE=hrms_backend"
echo "   DB_USERNAME=your-production-user"
echo "   DB_PASSWORD=your-production-password"
echo ""

echo "5. Test Connection:"
echo "   php artisan tinker --execute=\"DB::connection()->getPdo(); echo 'SUCCESS';\""
echo ""

echo "6. Run Migrations:"
echo "   php artisan migrate --force"
echo ""

echo "🔧 Production MySQL Configuration"
echo "-------------------------------"
echo ""

echo "Recommended .env settings for production:"
echo "DB_CONNECTION=mysql"
echo "DB_HOST=your-mysql-server-ip"
echo "DB_PORT=3306"
echo "DB_DATABASE=hrms_backend"
echo "DB_USERNAME=hrms_user"
echo "DB_PASSWORD=strong_password_here"
echo ""

echo "MySQL Server Configuration (/etc/mysql/mysql.conf.d/mysqld.cnf):"
echo "[mysqld]"
echo "bind-address = 0.0.0.0"
echo "max_connections = 200"
echo "innodb_buffer_pool_size = 256M"
echo "query_cache_size = 32M"
echo ""

echo "📝 Troubleshooting Commands"
echo "--------------------------"
echo ""

echo "# Check MySQL status"
echo "sudo systemctl status mysql"
echo ""

echo "# Check MySQL logs"
echo "sudo tail -f /var/log/mysql/error.log"
echo ""

echo "# Check MySQL processes"
echo "sudo netstat -tlnp | grep mysql"
echo ""

echo "# Test MySQL connection"
echo "mysql -h [HOST] -P [PORT] -u [USER] -p [DATABASE]"
echo ""

echo "# Check database size"
echo "mysql -u root -p -e \"SELECT table_schema AS 'Database', ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)' FROM information_schema.tables WHERE table_schema = 'hrms_backend';\""
echo ""

echo "# Check slow queries"
echo "mysql -u root -p -e \"SHOW VARIABLES LIKE 'slow_query_log';\""
echo ""

echo "🎯 Next Steps"
echo "-------------"
echo "1. Update your production .env with correct MySQL credentials"
echo "2. Ensure MySQL service is running on your production server"
echo "3. Create database and user with proper permissions"
echo "4. Test connection with: php artisan tinker"
echo "5. Run migrations: php artisan migrate --force"
echo "6. Monitor MySQL logs for any errors"
echo ""
echo "📖 For detailed MySQL setup, see DEPLOYMENT_CHECKLIST.md"
