# Monitoring Setup

## Application Monitoring

1. **Log Monitoring**:
   ```bash
   # Monitor Laravel logs
   tail -f storage/logs/laravel.log

   # Monitor web server logs
   tail -f /var/log/apache2/hrms_error.log
   tail -f /var/log/nginx/hrms_error.log
   ```

2. **Health Checks**:
   - http://your-domain.com/health
   - http://your-domain.com/up
   - http://your-domain.com/api/test-db

3. **Database Monitoring**:
   ```bash
   # Check database connection
   php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';"
   ```

## Backup Strategy

1. **Database Backup**:
   ```bash
   # Create backup script
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
   ```

2. **Application Backup**:
   ```bash
   # Backup application files
   tar -czf hrms_backup_$(date +%Y%m%d_%H%M%S).tar.gz --exclude=node_modules --exclude=vendor .
   ```

3. **Automated Backups**:
   ```bash
   # Add to crontab
   0 2 * * * /path/to/backup_script.sh
   ```
