# Paano Makita ang Queue Worker sa Render Dashboard

## Step-by-Step Guide

### Step 1: Login sa Render
1. **Open browser** → Go to: https://dashboard.render.com
2. **Login** sa Render account mo

### Step 2: Pumunta sa Project
1. **Click "Dashboard"** sa top navigation
2. **Hanapin ang project** na "DMCRS" (o kung ano man ang name ng project mo)
3. **Click sa project name** para buksan

### Step 3: Check Services
Sa project page, makikita mo ang **"Services"** section:

**Dapat makita mo:**
- ✅ **dmcrs** (Web Service) - Status: "Live" ✅
- ❓ **dmcrs-queue-worker** (Background Worker) - Check kung may ganito

### Kung WALANG "dmcrs-queue-worker":
- ❌ Hindi na-create ang worker service
- Kailangan i-create manually

### Kung MAY "dmcrs-queue-worker":
- ✅ Na-create na ang worker service
- Check ang **Status**: Dapat "Live" o "Running"
- Click sa service name para makita ang logs

---

## Visual Guide

```
Render Dashboard
├── Dashboard (top menu)
│   └── Your Projects
│       └── DMCRS Project ← Click dito
│           └── Services Section
│               ├── dmcrs (Web Service) ← Dapat may ganito
│               └── dmcrs-queue-worker (Background Worker) ← Check kung may ganito
```

---

## Kung Wala ang Worker Service

### Option A: Check kung nasa ibang tab
1. Sa project page, check ang **tabs**:
   - "Services" tab
   - "Settings" tab
   - "Logs" tab

### Option B: Create Manually
1. Sa project page, click **"New +"** button (usually sa top right)
2. Select **"Background Worker"**
3. Follow the configuration steps

---

## Quick Check

**Tanong sa sarili:**
1. ✅ May "dmcrs" service ba? → **YES** (web service)
2. ❓ May "dmcrs-queue-worker" service ba? → **Check mo**

**Kung WALA ang worker:**
- Create manually (see steps above)
- O baka nasa ibang project/service

**Kung MAY worker:**
- Click sa service name
- Check ang "Logs" tab
- Dapat may: `Processing: App\Notifications\MakeupClassStatusNotification`

---

## Alternative: Check via URL

Direct URL format:
```
https://dashboard.render.com/web/[your-project-id]/services
```

Pero mas madali kung:
1. Dashboard → Projects → DMCRS → Services

---

## Summary

**Location**: Render Dashboard → Your Project → Services Section

**Dapat makita**:
- 1 Web Service (dmcrs) ✅
- 1 Background Worker (dmcrs-queue-worker) ❓

**Kung wala ang worker**: Create manually using "New +" → Background Worker

