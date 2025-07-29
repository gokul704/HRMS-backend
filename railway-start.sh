#!/bin/bash

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi

# Debug environment variables
echo "=== Environment Debug ==="
echo "DATABASE_URL: ${DATABASE_URL:0:50}..."
echo "DB_URL: ${DB_URL:0:50}..."
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

    # Extract components from DATABASE_URL
    DB_HOST=$(echo $DATABASE_URL | sed -n 's/.*@\([^:]*\):.*/\1/p')
    DB_PORT=$(echo $DATABASE_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    DB_DATABASE=$(echo $DATABASE_URL | sed -n 's/.*\/\([^?]*\).*/\1/p')
    DB_USERNAME=$(echo $DATABASE_URL | sed -n 's/.*:\/\/\([^:]*\):.*/\1/p')
    DB_PASSWORD=$(echo $DATABASE_URL | sed -n 's/.*:\/\/[^:]*:\([^@]*\)@.*/\1/p')

    echo "Extracted from DATABASE_URL:"
    echo "DB_HOST: $DB_HOST"
    echo "DB_PORT: $DB_PORT"
    echo "DB_DATABASE: $DB_DATABASE"
    echo "DB_USERNAME: $DB_USERNAME"
    echo "DB_PASSWORD: ${DB_PASSWORD:0:10}..."

    # Set environment variables for Laravel
    export DB_HOST=$DB_HOST
    export DB_PORT=$DB_PORT
    export DB_DATABASE=$DB_DATABASE
    export DB_USERNAME=$DB_USERNAME
    export DB_PASSWORD=$DB_PASSWORD
fi

# Handle Railway's new variable syntax
if [ ! -z "$DB_URL" ]; then
    echo "Using DB_URL from Railway..."
    # Extract components from DB_URL
    DB_HOST=$(echo $DB_URL | sed -n 's/.*@\([^:]*\):.*/\1/p')
    DB_PORT=$(echo $DB_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    DB_DATABASE=$(echo $DB_URL | sed -n 's/.*\/\([^?]*\).*/\1/p')
    DB_USERNAME=$(echo $DB_URL | sed -n 's/.*:\/\/\([^:]*\):.*/\1/p')
    DB_PASSWORD=$(echo $DB_URL | sed -n 's/.*:\/\/[^:]*:\([^@]*\)@.*/\1/p')

    echo "Extracted from DB_URL:"
    echo "DB_HOST: $DB_HOST"
    echo "DB_PORT: $DB_PORT"
    echo "DB_DATABASE: $DB_DATABASE"
    echo "DB_USERNAME: $DB_USERNAME"
    echo "DB_PASSWORD: ${DB_PASSWORD:0:10}..."

    # Set environment variables for Laravel
    export DB_HOST=$DB_HOST
    export DB_PORT=$DB_PORT
    export DB_DATABASE=$DB_DATABASE
    export DB_USERNAME=$DB_USERNAME
    export DB_PASSWORD=$DB_PASSWORD
fi

# Set fallback values if not provided
if [ -z "$DB_CONNECTION" ]; then
    export DB_CONNECTION=mysql
fi

if [ -z "$DB_HOST" ]; then
    export DB_HOST=127.0.0.1
fi

if [ -z "$DB_PORT" ]; then
    export DB_PORT=3306
fi

if [ -z "$DB_DATABASE" ]; then
    export DB_DATABASE=laravel
fi

if [ -z "$DB_USERNAME" ]; then
    export DB_USERNAME=root
fi

if [ -z "$DB_PASSWORD" ]; then
    export DB_PASSWORD=
fi

echo "=== Final Database Configuration ==="
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "DB_PASSWORD: ${DB_PASSWORD:0:10}..."
echo "==================================="

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
