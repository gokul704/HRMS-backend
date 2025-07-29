# SSL Certificate Setup

## Using Let's Encrypt (Recommended)

1. Install Certbot:
   ```bash
   # Ubuntu/Debian
   sudo apt install certbot python3-certbot-apache

   # CentOS/RHEL
   sudo yum install certbot python3-certbot-nginx
   ```

2. Obtain SSL certificate:
   ```bash
   # For Apache
   sudo certbot --apache -d your-domain.com -d www.your-domain.com

   # For Nginx
   sudo certbot --nginx -d your-domain.com -d www.your-domain.com
   ```

3. Auto-renewal:
   ```bash
   sudo crontab -e
   # Add this line:
   0 12 * * * /usr/bin/certbot renew --quiet
   ```

## Using Cloudflare (Alternative)

1. Sign up for Cloudflare
2. Add your domain
3. Update nameservers
4. Enable SSL/TLS encryption mode: Full (strict)
5. Enable "Always Use HTTPS" rule
