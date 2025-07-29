# Railway Environment Variables Setup

## Required Environment Variables

You need to set these environment variables in your Railway dashboard:

### Option 1: Using DATABASE_URL (Recommended)
```
DATABASE_URL=mysql://username:password@host:port/database_name
```

### Option 2: Using Individual Variables
```
DB_CONNECTION=mysql
DB_HOST=your-database-host.railway.app
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## How to Set Environment Variables in Railway

1. Go to your Railway dashboard
2. Click on your application
3. Go to the "Variables" tab
4. Add the environment variables listed above

## Database Service Setup

If you don't have a database service:

1. In Railway dashboard, click "New Service"
2. Select "Database" → "MySQL"
3. Railway will automatically provide the `DATABASE_URL`
4. Connect the database service to your application

## Testing the Connection

After deployment, test these endpoints:

- `/health` - Application health check
- `/test-db` - Database connection test

## Common Issues

### Issue: "Database connection failed"
**Solution:** Check that `DATABASE_URL` is set correctly in Railway variables

### Issue: "Access denied for user"
**Solution:** Verify username and password in the connection string

### Issue: "Unknown database"
**Solution:** Check that the database name is correct

### Issue: "Connection refused"
**Solution:** Ensure the database service is running and connected

## Example DATABASE_URL Format

```
mysql://root:password123@mysql.railway.internal:3306/railway
```

Where:
- `root` = username
- `password123` = password
- `mysql.railway.internal` = host
- `3306` = port
- `railway` = database name 
