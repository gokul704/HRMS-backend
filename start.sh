#!/bin/bash

# Start Laravel application without database migrations
echo "Starting Laravel application..."

# Create storage directories if they don't exist
mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache

# Set permissions
chmod -R a+rw storage

# Start the application
php artisan serve --host=0.0.0.0 --port=8000
