# Railway Environment Variables Fix

## Current Issue
Your application is trying to connect to `hrms-backend.railway.internal` but this is not the correct database host.

## Solution

### Step 1: Update Environment Variables in Railway Dashboard

Go to your Railway dashboard and update these variables:

**Remove these variables:**
```
DB_HOST=hrms-backend.railway.internal
```

**Add/Update these variables:**
```
DB_HOST=mysql.railway.internal
DB_CONNECTION=mysql
DB_DATABASE=railway
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=nbsjRswegfzZnAkULqciGwQYEheyKpOy
```

### Step 2: Ensure MySQL Service is Connected

1. In Railway dashboard, check if your MySQL service is connected to your application
2. The MySQL service should be in the same project as your HRMS-backend
3. Railway should automatically provide the correct internal host

### Step 3: Alternative - Use Railway's Auto Variables

If Railway provides these automatically, you can remove the manual DB_* variables and let Railway handle it:

**Remove all DB_* variables and let Railway provide:**
- `MYSQL_URL`
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`

## Expected Result

After updating the environment variables, the startup script should show:
```
DB_HOST: mysql.railway.internal
Database connection successful!
```

## Test the Fix

After updating variables, redeploy and check:
1. Railway logs for "Database connection successful!"
2. Visit `/health` endpoint
3. Visit `/test-db` endpoint 
