#!/usr/bin/env bash
# exit on error
set -o errexit

# Instala dependências do PHP
composer install --no-dev --optimize-autoloader

# Instala dependências do Node e compila o Vue (Vite)
npm install
npm run build

# Limpa e gera caches do Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache