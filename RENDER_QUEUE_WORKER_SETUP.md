# Render Queue Worker Setup Guide

## Problem
Notifications are not working in live because queue worker is not running. Laravel needs a background worker to process queued notifications.

## Solution: Setup Background Worker on Render

### Step 1: Create Background Worker Service

1. **Go to Render Dashboard**
   - Login to https://dashboard.render.com
   - Select your project (DMCRS)

2. **Create New Background Worker**
   - Click **"New +"** button
   - Select **"Background Worker"**

3. **Configure Background Worker**

   **Basic Settings:**
   - **Name**: `dmcrs-queue-worker` (or any name you prefer)
   - **Environment**: `Docker` (if using Docker) or `Web Service`
   - **Region**: Same as your web service
   - **Branch**: `main` (or your main branch)

   **Build & Deploy:**
   - **Build Command**: 
     ```
     composer install --no-dev --optimize-autoloader
     ```
   - **Start Command**: 
     ```
     php artisan queue:work --sleep=3 --tries=3 --max-time=3600
     ```

   **Environment Variables:**
   - Copy ALL environment variables from your web service:
     - `APP_ENV=production`
     - `APP_DEBUG=false`
     - `APP_KEY=your-app-key`
     - `DB_CONNECTION=...`
     - `DB_HOST=...`
     - `DB_DATABASE=...`
     - `DB_USERNAME=...`
     - `DB_PASSWORD=...`
     - `QUEUE_CONNECTION=database` (IMPORTANT!)
     - `BREVO_API_KEY=...`
     - All other variables from web service

   **Advanced Settings:**
   - **Auto-Deploy**: `Yes` (deploy automatically when you push to GitHub)
   - **Plan**: Free tier is fine for testing

4. **Click "Create Background Worker"**

---

## Step 2: Verify Queue Connection

Make sure your `.env` file has:

```env
QUEUE_CONNECTION=database
```

NOT `sync` or `redis` (unless you have Redis setup).

---

## Step 3: Verify Queue Table Exists

The queue table should be created automatically, but verify:

1. Check if `jobs` table exists in your database
2. If not, run migration:
   ```bash
   php artisan queue:table
   php artisan migrate
   ```

---

## Step 4: Test Queue Worker

1. **Check Worker Status**
   - Go to Background Worker service in Render
   - Check "Logs" tab
   - You should see: `Processing: App\Notifications\MakeupClassStatusNotification`

2. **Test Notification**
   - Create a makeup class request as Faculty
   - Check if notification appears (should work now!)

3. **Monitor Logs**
   - Watch the Background Worker logs
   - Should see queue jobs being processed

---

## Alternative: Use Cron Job (If Background Worker Not Available)

If Render doesn't support Background Workers on your plan, use a Cron Job:

1. **Create Cron Job Service**
   - New → Cron Job
   - **Schedule**: `* * * * *` (every minute)
   - **Command**: 
     ```bash
     cd /opt/render/project/src && php artisan schedule:run
     ```

2. **Update Laravel Scheduler**
   Add to `app/Console/Kernel.php`:
   ```php
   protected function schedule(Schedule $schedule)
   {
       $schedule->command('queue:work --stop-when-empty')
                ->everyMinute()
                ->withoutOverlapping();
   }
   ```

---

## Troubleshooting

### Queue Worker Not Processing Jobs

1. **Check Queue Connection**
   ```bash
   php artisan queue:work --verbose
   ```

2. **Check Database Connection**
   - Verify database credentials in environment variables

3. **Check Jobs Table**
   ```sql
   SELECT * FROM jobs LIMIT 10;
   ```

4. **Clear Queue**
   ```bash
   php artisan queue:clear
   ```

### Notifications Still Not Appearing

1. **Check Environment**
   - Make sure `APP_ENV=production` in Background Worker

2. **Check Notification Class**
   - Verify using `MakeupClassStatusNotification` (queued) in production

3. **Check Logs**
   - Web service logs
   - Background worker logs
   - Look for errors

---

## Quick Verification Commands

Run these in Render Shell (if available) or via SSH:

```bash
# Check queue connection
php artisan tinker
>>> config('queue.default')
# Should return: "database"

# Check if jobs table exists
php artisan migrate:status

# Process queue manually (for testing)
php artisan queue:work --once
```

---

## Expected Result

After setup:
- ✅ Background Worker running
- ✅ Queue jobs being processed
- ✅ Notifications appearing in notification bell
- ✅ All roles (Faculty, Academic Head, Department Chair) receiving notifications

---

## Cost Note

- **Free Tier**: Background Workers are available on free tier
- **Usage**: Minimal resource usage for queue processing
- **Scaling**: Can scale up if needed

---

## Summary

1. Create Background Worker service in Render
2. Set start command: `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`
3. Copy all environment variables from web service
4. Set `QUEUE_CONNECTION=database`
5. Deploy and monitor logs

**Your notifications should work after this setup!** 🎉

