# Railway Deployment Guide for HRMS Backend

## Overview
This guide provides step-by-step instructions for deploying the HRMS Laravel application to Railway.

## Prerequisites
- Railway account
- Git repository connected to Railway
- Database service (MySQL/PostgreSQL) on Railway

## Deployment Steps

### 1. Environment Variables Setup
Set the following environment variables in your Railway project:

```bash
APP_NAME=HRMS Backend
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=your-db-host.railway.app
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Database Setup
1. Create a MySQL database service in Railway
2. Get the connection details from Railway dashboard
3. Update the environment variables with the database credentials

### 3. Application Key Generation
The application will automatically generate an APP_KEY if not provided, but you can set it manually:
```bash
php artisan key:generate
```

### 4. Deployment Configuration Files
The following files are already configured:
- `railway.json` - Railway deployment settings
- `nixpacks.toml` - Build configuration for PHP 8.2
- `Procfile` - Process definition
- `railway-start.sh` - Startup script
- `.railwayignore` - Files to exclude from deployment

### 5. Build Process
The deployment will automatically:
1. Install PHP 8.2 with required extensions
2. Install Composer dependencies
3. Clear and cache configuration
4. Clear and cache routes
5. Clear and cache views
6. Run database migrations
7. Start the application server

## Troubleshooting

### Common Issues

#### 1. Application Key Error
**Error**: `No application encryption key has been specified`
**Solution**: Ensure `APP_KEY` is set in environment variables

#### 2. Database Connection Error
**Error**: `SQLSTATE[HY000] [2002] Connection refused`
**Solution**: 
- Check database credentials in environment variables
- Ensure database service is running
- Verify database host and port

#### 3. Permission Errors
**Error**: `Permission denied` for storage/logs
**Solution**: The startup script handles this automatically

#### 4. Route Cache Error
**Error**: `Route cache not found`
**Solution**: The startup script clears and rebuilds route cache

#### 5. View Cache Error
**Error**: `View cache not found`
**Solution**: The startup script clears and rebuilds view cache

### Debugging Steps

1. **Check Railway Logs**
   - Go to Railway dashboard
   - Click on your service
   - Check the "Logs" tab for error messages

2. **Verify Environment Variables**
   - Ensure all required variables are set
   - Check for typos in variable names

3. **Test Database Connection**
   - Use Railway's database connection details
   - Test connection from Railway's database service

4. **Check Application Status**
   - Visit `/health` endpoint
   - Check `/api/test-db` endpoint

### Health Check Endpoints

- `GET /health` - Application health check
- `GET /api/test-db` - Database connection test
- `GET /` - Main application

### Performance Optimization

1. **Enable Caching**
   - Configuration cache is enabled by default
   - Route cache is enabled by default
   - View cache is enabled by default

2. **Database Optimization**
   - Use database indexes for frequently queried columns
   - Optimize database queries

3. **File System**
   - Use local file system for caching
   - Ensure proper permissions for storage directory

## Monitoring

### Railway Dashboard
- Monitor application logs
- Check resource usage
- View deployment status

### Application Logs
- Check `storage/logs/laravel.log` for application errors
- Monitor database connection issues
- Track user authentication errors

## Security Considerations

1. **Environment Variables**
   - Never commit sensitive data to Git
   - Use Railway's environment variable management

2. **Database Security**
   - Use strong passwords
   - Restrict database access

3. **Application Security**
   - Set `APP_DEBUG=false` in production
   - Use HTTPS in production
   - Implement proper authentication

## Support

If you encounter issues:
1. Check Railway logs first
2. Verify environment variables
3. Test database connectivity
4. Review this troubleshooting guide
5. Contact Railway support if needed

## Deployment Checklist

- [ ] Environment variables configured
- [ ] Database service created and connected
- [ ] Application key generated
- [ ] Database migrations ready
- [ ] All configuration files committed
- [ ] Railway service connected to Git repository
- [ ] Health check endpoints working
- [ ] Database connection test passing 
