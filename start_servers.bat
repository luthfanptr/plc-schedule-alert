@echo off
echo Starting MINA-PLC on port 8000...
start cmd /k "cd MINA-PLC && php artisan serve --port=8000"

echo Starting Notification Service on port 8001...
start cmd /k "cd notification-service && php artisan serve --port=8001"

echo Servers started in new windows!
