#!/bin/bash
cd /home/vensisjohn/Bureau/laravel/garage-laravel/GESTION_CLIENTS_APP

# Configurer pour MySQL en local avec les infos par défaut
echo "Configuration de la base de données..."

# Essayer MySQL d'abord
mysql -u root -e "CREATE DATABASE IF NOT EXISTS garage_laravel;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Base MySQL créée"
    exit 0
fi

# Essayer MariaDB
sudo -u mysql mysql -e "CREATE DATABASE IF NOT EXISTS garage_laravel;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Base MariaDB créée"
    exit 0
fi

# Sinon utiliser une base en mémoire avec in-memory SQLite
echo "MySQL/MariaDB non disponible, utilisation mode in-memory"
echo "DB_CONNECTION=sqlite" >> .env
echo "DB_DATABASE=:memory:" >> .env
echo "⚠️  Les données ne seront pas persistantes"
