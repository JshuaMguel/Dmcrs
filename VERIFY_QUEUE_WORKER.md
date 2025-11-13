# How to Verify Queue Worker is Running on Render

## Check 1: Render Dashboard

1. **Go to Render Dashboard**: https://dashboard.render.com
2. **Select your project** (DMCRS)
3. **Look for services**:
   - ✅ Should see: `dmcrs` (Web Service) - **This is running**
   - ❓ Should see: `dmcrs-queue-worker` (Background Worker) - **Check if this exists**

### If `dmcrs-queue-worker` EXISTS:
- ✅ Queue worker service was created
- Check its **Status**: Should be "Live" or "Running"
- Check its **Logs** tab: Should see `Processing: App\Notifications\MakeupClassStatusNotification`

### If `dmcrs-queue-worker` DOES NOT EXIST:
- ❌ Render didn't auto-detect the worker from `render.yaml`
- **Solution**: Create it manually (see below)

---

## Check 2: Manual Worker Creation (If Not Auto-Created)

If the worker service doesn't exist, create it manually:

1. **In Render Dashboard**:
   - Click **"New +"** button
   - Select **"Background Worker"**

2. **Configure**:
   - **Name**: `dmcrs-queue-worker`
   - **Environment**: `Docker` (or same as web service)
   - **Region**: Same as web service
   - **Branch**: `main`
   - **Build Command**: `chmod +x render-build.sh && ./render-build.sh`
   - **Start Command**: `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`

3. **Environment Variables**:
   - Copy ALL from web service (`dmcrs`)
   - **Important**: Make sure `QUEUE_CONNECTION=database`

4. **Click "Create Background Worker"**

---

## Check 3: Verify Queue Worker is Processing

### In Worker Logs, you should see:
```
Processing: App\Notifications\MakeupClassStatusNotification
Processed: App\Notifications\MakeupClassStatusNotification
```

### If you see errors:
- Check database connection
- Check if `jobs` table exists
- Check if `QUEUE_CONNECTION=database` is set

---

## Check 4: Test Notification Bell

1. **Create a makeup class request** as Faculty
2. **Check notification bell** - should show notification
3. **Check worker logs** - should see job being processed

---

## Troubleshooting

### Worker Not Processing Jobs

1. **Check jobs table**:
   ```sql
   SELECT * FROM jobs ORDER BY created_at DESC LIMIT 10;
   ```

2. **Check failed_jobs table**:
   ```sql
   SELECT * FROM failed_jobs ORDER BY failed_at DESC LIMIT 10;
   ```

3. **Manually process queue** (for testing):
   ```bash
   php artisan queue:work --once
   ```

### Worker Service Not Created

- Render might not auto-detect workers from `render.yaml` on free tier
- **Solution**: Create manually as shown above

---

## Expected Result

✅ **Web Service**: Running (dmcrs)  
✅ **Background Worker**: Running (dmcrs-queue-worker)  
✅ **Queue Processing**: Jobs being processed  
✅ **Notification Bell**: Showing notifications  

---

## Quick Status Check

After deployment, check Render Dashboard:
- **Services count**: Should be 2 (web + worker)
- **Worker status**: Should be "Live"
- **Worker logs**: Should show queue processing

If worker is missing, create it manually using the steps above.

