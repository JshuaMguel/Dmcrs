# Local Testing Guide - Makeup Class System Updates

## 🧪 LOCAL TESTING STEPS

### Step 1: Run Migrations
```bash
cd DMCRS
php artisan migrate
```

This will add the `proof_of_conduct` field to your database.

---

### Step 2: Clear Cache (Optional but Recommended)
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

### Step 3: Test the New Features

#### Test 1: Student Confirmation Email Flow
1. **Login as Faculty**
2. **Create a new makeup class request**
   - Fill in all required fields
   - Upload student list CSV (with email column)
   - Submit
3. **Check**: 
   - ✅ Student confirmation emails should be sent IMMEDIATELY
   - ✅ Request status should be `pending`
   - ✅ Department Chair should NOT be notified yet

#### Test 2: Student Confirmation
1. **Check email inbox** (or check logs)
2. **Click confirm/decline link** from email
3. **Verify**: Student confirmation is saved

#### Test 3: Submit Official Request
1. **Go to**: `/faculty/student-confirmations`
2. **Check**: Pending request should appear
3. **Verify**: At least 1 student has confirmed
4. **Click**: "Submit Official Request" button
5. **Check**:
   - ✅ Department Chair should be notified
   - ✅ Request should appear in Department Chair dashboard

#### Test 4: Approval Flow
1. **Login as Department Chair**
2. **Approve the request**
3. **Login as Academic Head**
4. **Approve the request**
5. **Check**:
   - ✅ Final approval email should be sent to students
   - ✅ Email should show "approved and scheduled" (no buttons)
   - ✅ Request status should be `APPROVED`

#### Test 5: Proof of Conduct Upload
1. **Login as Faculty**
2. **View approved request** (`/faculty/makeup-requests/{id}`)
3. **Check**: Proof upload section should appear
4. **Click**: "Print Student List" button
5. **Verify**: Student list prints correctly (from CSV)
6. **Upload**: Multiple proof images
7. **Check**:
   - ✅ Images should upload successfully
   - ✅ Images should display in gallery
   - ✅ Can delete individual images

#### Test 6: Email Template (Approved Status)
1. **Check final approval email**
2. **Verify**: 
   - ✅ NO confirm/decline buttons
   - ✅ Shows "approved and scheduled" message
   - ✅ Status is `APPROVED`

---

### Step 4: Check Logs

Check Laravel logs for any errors:
```bash
tail -f storage/logs/laravel.log
```

Or check specific log entries:
```bash
grep "Student confirmation emails" storage/logs/laravel.log
grep "Proof of conduct uploaded" storage/logs/laravel.log
```

---

### Step 5: Test Edge Cases

1. **No students in CSV**: Should handle gracefully
2. **Invalid CSV format**: Should show error
3. **Submit without confirmations**: Should show error
4. **Upload large image**: Should validate file size
5. **Upload non-image file**: Should reject

---

## 🐛 COMMON ISSUES & FIXES

### Issue 1: Migration Fails
**Error**: Column already exists
**Fix**: 
```bash
php artisan migrate:rollback --step=1
php artisan migrate
```

### Issue 2: Email Not Sending
**Check**:
- Brevo API key is set in `.env`
- Check `storage/logs/laravel.log` for email errors
- Verify email service is working

### Issue 3: Images Not Uploading
**Check**:
- `storage/app/public/proof_of_conduct` directory exists
- File permissions are correct
- PHP `upload_max_filesize` is set correctly

### Issue 4: Route Not Found
**Fix**:
```bash
php artisan route:clear
php artisan route:list | grep proof
```

---

## ✅ TESTING CHECKLIST

- [ ] Migrations run successfully
- [ ] Student confirmation emails sent immediately
- [ ] Student can confirm/decline via email link
- [ ] Faculty can see student confirmations
- [ ] "Submit Official Request" button appears when ≥1 confirmed
- [ ] Department Chair receives notification after submit
- [ ] Approval flow works (Chair → Head)
- [ ] Final approval email sent to students
- [ ] Final email shows "approved" message (no buttons)
- [ ] Proof upload section appears for approved requests
- [ ] Print student list works (from CSV)
- [ ] Multiple images can be uploaded
- [ ] Images display in gallery
- [ ] Individual images can be deleted
- [ ] No errors in logs

---

## 📝 NOTES

- **Email Testing**: Use real email addresses or check Brevo dashboard
- **CSV Format**: Must have `email` column (and optionally `name`, `student_id`)
- **Image Size**: Max 15MB per image
- **Multiple Images**: Use Ctrl/Cmd to select multiple files

---

## 🚀 READY FOR PRODUCTION?

Once all tests pass locally:
1. ✅ Backup production database
2. ✅ Deploy code to production
3. ✅ Run migrations on production
4. ✅ Test one request in production
5. ✅ Monitor logs for first few requests

