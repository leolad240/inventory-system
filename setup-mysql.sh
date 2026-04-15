#!/bin/bash

MYSQL=/Applications/XAMPP/xamppfiles/bin/mysql

echo "→ Checking MySQL connection..."
$MYSQL -u root -h 127.0.0.1 -P 3306 -e "SELECT 1;" 2>/dev/null
if [ $? -ne 0 ]; then
  echo ""
  echo "✗ Cannot connect to MySQL. Please start MySQL in XAMPP first, then re-run this script."
  echo "  Open XAMPP → click Start next to MySQL → come back here."
  exit 1
fi

echo "→ Creating database 'inventory_pro'..."
$MYSQL -u root -h 127.0.0.1 -P 3306 -e "CREATE DATABASE IF NOT EXISTS inventory_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo "→ Running migrations..."
php artisan migrate:fresh

echo "→ Seeding demo data..."
php artisan db:seed

echo ""
echo "✓ Done! Database is ready."
echo "  Tables:   $(${MYSQL} -u root -h 127.0.0.1 -P 3306 inventory_pro -e 'SHOW TABLES;' 2>/dev/null | tail -n +2 | wc -l | tr -d ' ') tables created"
echo "  Products: $(${MYSQL} -u root -h 127.0.0.1 -P 3306 inventory_pro -e 'SELECT COUNT(*) FROM products;' 2>/dev/null | tail -1)"
echo "  Sales:    $(${MYSQL} -u root -h 127.0.0.1 -P 3306 inventory_pro -e 'SELECT COUNT(*) FROM sales;' 2>/dev/null | tail -1)"
echo ""
echo "  Login at: http://localhost:8000"
echo "  Admin:  admin@demo.com / password"
echo "  Staff:  sarah@demo.com / password"
echo "  Staff:  mike@demo.com  / password"
