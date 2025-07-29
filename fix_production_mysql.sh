#!/bin/bash

# Quick Fix for Production MySQL Issues
# Usage: ./fix_production_mysql.sh

echo "🔧 Quick Fix for Production MySQL Issues"
echo "======================================="
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

echo "🎯 Common Production MySQL Issues & Quick Fixes"
echo "=============================================="
echo ""

echo "1. 🔥 MySQL Service Not Running"
echo "   Fix: sudo systemctl start mysql"
echo "   Check: sudo systemctl status mysql"
echo ""

echo "2. 🔐 Wrong Credentials"
echo "   Fix: Update .env with correct production credentials"
echo "   Example:"
echo "   DB_HOST=your-production-server.com"
echo "   DB_USERNAME=hrms_user"
echo "   DB_PASSWORD=strong_password"
echo ""

echo "3. 🌐 Connection Issues"
echo "   Fix: Check firewall and MySQL bind-address"
echo "   - Allow port 3306 in firewall"
echo "   - Set bind-address = 0.0.0.0 in MySQL config"
echo ""

echo "4. 🗄️ Database Doesn't Exist"
echo "   Fix: Create database manually"
echo "   mysql -u root -p -e \"CREATE DATABASE hrms_backend;\""
echo ""

echo "5. 🔑 Permission Issues"
echo "   Fix: Grant proper permissions"
echo "   mysql -u root -p -e \"GRANT ALL PRIVILEGES ON hrms_backend.* TO 'user'@'%';\""
echo ""

echo "🚀 Quick Fix Commands"
echo "===================="
echo ""

echo "# 1. Check MySQL status"
echo "sudo systemctl status mysql"
echo "sudo systemctl start mysql"
echo ""

echo "# 2. Check MySQL logs for errors"
echo "sudo tail -f /var/log/mysql/error.log"
echo ""

echo "# 3. Test MySQL connection"
echo "mysql -u root -p -e \"SELECT 1;\""
echo ""

echo "# 4. Create database and user"
echo "mysql -u root -p -e \"CREATE DATABASE IF NOT EXISTS hrms_backend;\""
echo "mysql -u root -p -e \"CREATE USER IF NOT EXISTS 'hrms_user'@'%' IDENTIFIED BY 'password';\""
echo "mysql -u root -p -e \"GRANT ALL PRIVILEGES ON hrms_backend.* TO 'hrms_user'@'%';\""
echo "mysql -u root -p -e \"FLUSH PRIVILEGES;\""
echo ""

echo "# 5. Test Laravel connection"
echo "php artisan tinker --execute=\"DB::connection()->getPdo(); echo 'SUCCESS';\""
echo ""

echo "# 6. Run migrations"
echo "php artisan migrate --force"
echo ""

echo "📋 Production .env Template"
echo "=========================="
echo ""

cat << 'EOF'
# Production MySQL Configuration
DB_CONNECTION=mysql
DB_HOST=your-production-mysql-host.com
DB_PORT=3306
DB_DATABASE=hrms_backend
DB_USERNAME=hrms_user
DB_PASSWORD=your_strong_password_here

# For cloud databases (AWS RDS, Google Cloud SQL, etc.)
# DB_HOST=your-instance.region.rds.amazonaws.com
# DB_PORT=3306
# DB_DATABASE=hrms_backend
# DB_USERNAME=admin
# DB_PASSWORD=your_cloud_password
EOF

echo ""
echo "🔍 Troubleshooting Steps"
echo "======================="
echo ""

echo "1. Check if MySQL is running:"
echo "   sudo systemctl status mysql"
echo ""

echo "2. Check MySQL logs:"
echo "   sudo tail -f /var/log/mysql/error.log"
echo ""

echo "3. Test direct MySQL connection:"
echo "   mysql -h [HOST] -P [PORT] -u [USER] -p"
echo ""

echo "4. Test Laravel connection:"
echo "   php artisan tinker"
echo "   DB::connection()->getPdo();"
echo ""

echo "5. Check network connectivity:"
echo "   telnet [HOST] [PORT]"
echo "   nc -zv [HOST] [PORT]"
echo ""

echo "6. Check firewall:"
echo "   sudo ufw status"
echo "   sudo iptables -L"
echo ""

echo "🎯 Cloud-Specific Fixes"
echo "======================"
echo ""

echo "AWS RDS:"
echo "- Check Security Groups (allow port 3306)"
echo "- Use correct endpoint URL"
echo "- Ensure SSL is configured properly"
echo ""

echo "Google Cloud SQL:"
echo "- Check Cloud SQL Proxy"
echo "- Verify connection string"
echo "- Check IAM permissions"
echo ""

echo "DigitalOcean:"
echo "- Check firewall rules"
echo "- Use private network if available"
echo "- Verify database cluster status"
echo ""

echo "Heroku:"
echo "- Use DATABASE_URL environment variable"
echo "- Check addon status: heroku addons"
echo "- View logs: heroku logs --tail"
echo ""

echo "🔧 Emergency Fixes"
echo "================="
echo ""

echo "If MySQL is completely broken:"
echo "1. Restart MySQL: sudo systemctl restart mysql"
echo "2. Reset root password: sudo mysql_secure_installation"
echo "3. Recreate database: mysql -u root -p < backup.sql"
echo "4. Update .env with correct credentials"
echo "5. Run migrations: php artisan migrate --force"
echo ""

echo "📞 Need Help?"
echo "============="
echo "1. Run diagnostics: ./mysql_troubleshoot.sh"
echo "2. Check deployment guide: DEPLOYMENT_CHECKLIST.md"
echo "3. Monitor logs: tail -f storage/logs/laravel.log"
echo "4. Test endpoints: curl http://your-domain.com/health"
echo ""

print_status "Quick fix guide ready! Follow the steps above to resolve MySQL issues."
