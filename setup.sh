#!/bin/bash

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until docker exec omabonk-db mysqladmin ping -h localhost --silent 2>/dev/null; do
    sleep 2
done
echo "MySQL is ready!"

# Enter app container
echo "Installing Composer dependencies..."
docker exec omabonk-app composer install --no-interaction

# Run migrations
echo "Running migrations..."
docker exec omabonk-app php spark migrate --all

# Run seeder
echo "Running seeder..."
docker exec omabonk-app php spark db:seed DatabaseSeeder

echo ""
echo "========================================="
echo "  Setup selesai!"
echo "  Aplikasi: http://localhost:8000"
echo "  phpMyAdmin: http://localhost:8080"
echo ""
echo "  Admin Login:"
echo "  Email: admin@omabonk.com"
echo "  Password: password"
echo ""
echo "  Member Login:"
echo "  Email: member@omabonk.com"
echo "  Password: password"
echo "========================================="
