# DMCRS Production Deployment Checklist

## Recent Changes Made (Need to Deploy):

### 1. Database Changes:
- ✅ Added `student_email` column to make_up_class_confirmations 
- ✅ Made `student_id` nullable in make_up_class_confirmations
- ✅ Added `student_id_number` and `student_name` columns to make_up_class_confirmations

### 2. Code Changes:
- ✅ Fixed email system (confirm/decline working for non-registered students)
- ✅ Enhanced CSV parsing to extract Student ID, Name, Email, Course
- ✅ Updated MakeupClassController to handle student info from CSV
- ✅ Enhanced faculty view to show full name and student ID
- ✅ Fixed academic head approval SQL error (removed 'date' column)

### 3. Email Configuration:
- ✅ Gmail SMTP with App Password: gmjauouwuleegday
- ✅ From: ustpbalubal.dmcrs@gmail.com

## Production Deployment Steps:

### 1. Push Code to Repository:
```bash
git add .
git commit -m "Add student ID/name display, fix email confirmations, enhance CSV parsing"
git push origin main
```

### 2. Update Render Environment Variables:
- MAIL_MAILER=smtp
- MAIL_HOST=smtp.gmail.com  
- MAIL_PORT=587
- MAIL_USERNAME=ustpbalubal.dmcrs@gmail.com
- MAIL_PASSWORD=gmjauouwuleegday
- MAIL_ENCRYPTION=tls
- MAIL_FROM_ADDRESS=ustpbalubal.dmcrs@gmail.com
- MAIL_FROM_NAME=USTP Balubal Campus - DMCRS

### 3. Run Migrations on Production:
The deployment should automatically run:
```bash
php artisan migrate --force
```

### 4. Clear Caches:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Testing Checklist for Production:

### ✅ Email System:
1. Faculty creates make-up class request
2. Academic head approves (should send emails to students)
3. Check if emails are received with proper student info

### ✅ Student Confirmations:
1. Students click email links (confirm/decline)
2. Should work for non-registered students from CSV
3. Faculty should see full name and student ID

### ✅ Academic Head Approval:
1. Should not get SQL error about 'date' column
2. Should successfully create schedule records

### ✅ Faculty Dashboard:
1. Student confirmations page should show proper names/IDs
2. No "Attempt to read property 'name' on null" errors

## URLs to Test:
- Main site: https://dmcrs.onrender.com
- Faculty login and dashboard
- Academic head approval workflow
- Student email confirmation links