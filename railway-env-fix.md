# Railway Environment Variables Fix

## Current Issue
Your application is trying to connect to `hrms-backend.railway.internal` but this is not the correct database host. Railway uses template syntax that needs to be resolved.

## Solution

### Step 1: Remove Manual DB Variables
In your Railway dashboard, **REMOVE** these variables:
```
DB_HOST=hrms-backend.railway.internal
DB_DATABASE=railway
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=nbsjRswegfzZnAkULqciGwQYEheyKpOy
```

### Step 2: Let Railway Handle Database Variables
Railway will automatically provide these variables from your MySQL service:
```
MYSQL_DATABASE="railway"
MYSQL_URL="mysql://${{MYSQLUSER}}:${{MYSQL_ROOT_PASSWORD}}@${{RAILWAY_PRIVATE_DOMAIN}}:3306/${{MYSQL_DATABASE}}"
MYSQLHOST="${{RAILWAY_PRIVATE_DOMAIN}}"
MYSQLPORT="3306"
MYSQLDATABASE="${{MYSQL_DATABASE}}"
MYSQLUSER="root"
MYSQLPASSWORD="${{MYSQL_ROOT_PASSWORD}}"
```

### Step 3: Keep Only Essential Variables
Keep only these variables in your Railway dashboard:
```
APP_NAME="HRMS"
APP_ENV="production"
APP_DEBUG="false"
DB_CONNECTION="mysql"
```

### Step 4: Ensure MySQL Service is Connected
1. In Railway dashboard, check if your MySQL service is connected to your application
2. The MySQL service should be in the same project as your HRMS-backend
3. Railway will automatically resolve the template variables

## Expected Result

After removing manual DB variables, the startup script should show:
```
Railway Environment Detected: YES
MYSQLHOST: [actual-railway-domain].railway.internal
MYSQLDATABASE: railway
MYSQLUSER: root
Database connection successful!
```

## Test the Fix

After updating variables, redeploy and check:
1. Railway logs for "Database connection successful!"
2. Visit `/health` endpoint
3. Visit `/test-db` endpoint

## Troubleshooting

If you still see "Connection refused":
1. Check that MySQL service is connected to your app in Railway
2. Verify the MySQL service is running
3. Check Railway logs for any template resolution errors 
