#!/bin/bash

echo "🚀 Starting Railway deployment with database setup..."

# Run migrations
echo "📊 Running migrations..."
php artisan migrate --force

# Run seeders
echo "👤 Running seeders..."
php artisan db:seed --force

# Test the setup
echo "🧪 Testing setup..."
php artisan tinker --execute="echo 'User count: ' . App\Models\User::count(); echo 'Department count: ' . App\Models\Department::count();"

echo "✅ Deployment completed!"
