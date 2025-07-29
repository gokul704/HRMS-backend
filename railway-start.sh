#!/bin/bash

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi

# Debug environment variables
echo "=== Environment Debug ==="
echo "DATABASE_URL: ${DATABASE_URL:0:50}..."
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "DB_PASSWORD: ${DB_PASSWORD:0:10}..."
echo "========================="

# Parse DATABASE_URL if provided and set individual variables
if [ ! -z "$DATABASE_URL" ]; then
    echo "Parsing DATABASE_URL..."
    php parse_railway_db.php
fi

# Test database connection
echo "=== Testing Database Connection ==="
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful!'; } catch (Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }"

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start the application
php artisan serve --host=0.0.0.0 --port=$PORT
