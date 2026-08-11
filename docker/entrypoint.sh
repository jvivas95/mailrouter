#!/bin/bash
set -e

echo "🚀 Starting MailRouter..."

# ── Wait for MySQL ──────────────────────────────────
echo "⏳ Waiting for the database..."
until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
        echo 'OK';
    } catch (Exception \$e) {
        exit(1);
    }
"; do
    echo "   Database not available, retrying in 3s..."
    sleep 3
done
echo "✅ Database is ready."

# ── Execute migrations ────────────────────────────────────────────
echo "📦 Executing migrations..."
php artisan migrate --force --seed

# ── Optimize Laravel for production ──────────────────────────────
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── Final permissions ────────────────────────────────────────────────
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "✅ MailRouter ready at http://localhost"

# ── Start Supervisor (manages Nginx + PHP-FPM + Worker + Scheduler)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
