#!/bin/bash
# Render Start Script with Queue Processing
# This runs queue worker in background + web server

# Start queue worker in background (processes notifications)
php artisan queue:work --daemon --sleep=3 --tries=3 --max-time=3600 > /dev/null 2>&1 &

# Start PHP built-in server for Render
php artisan serve --host=0.0.0.0 --port=$PORT

