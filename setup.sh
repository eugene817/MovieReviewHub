#!/usr/bin/env bash
set -e

echo "🛠  Setting up database…"

php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:create --if-not-exists

php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

php bin/console doctrine:fixtures:load --no-interaction --append

php bin/console cache:clear

echo "✅  Setup complete!"
