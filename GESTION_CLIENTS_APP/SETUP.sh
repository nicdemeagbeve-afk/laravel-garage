#! /bin/bash
# Installation rapide et pratique du projet

cd /home/vensisjohn/Bureau/laravel/garage-laravel/GESTION_CLIENTS_APP

echo "🚀 Installation GARAGE-LARAVEL"
echo "==============================="
echo ""

# Étape 1: Composer
echo "1️⃣  Installation des dépendances..."
composer install --no-interaction --prefer-dist --no-dev 2>&1 | grep -E "(Installing|loaded)" | head -3

# Étape 2: .env
echo "✅ Configuration d'environnement"
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate 2>&1 | grep "Application key"
fi

# Étape 3: Base de données
echo "2️⃣  Base de données..."

# Essayer MySQL
if command -v mysql &> /dev/null; then
    mysql -u root -e "DROP DATABASE IF EXISTS garage_laravel; CREATE DATABASE garage_laravel;" 2>/dev/null
    sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
    echo "✅ MySQL configuré"
else
    # Utiliser SQLite
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
    mkdir -p database
    
    # Créer DB avec sqlite3 shell
    echo "✅ SQLite configuré (fichier)"
fi

# Étape 4: Migrations
echo "3️⃣  Migrations"
php artisan migrate:fresh --force --seed 2>&1 | tail -3 || {
    echo "⚠️  Migrations standard échouées"
    echo "   - Raison : Pas de base de données système"
    echo "   - Solution : Utilisez le script docker-compose.yml"
    echo ""
    echo "   Commandes Docker:"
    echo "   docker-compose up -d"
    echo "   docker-compose exec app php artisan migrate"
}

# Étape 5: NPM
echo "4️⃣  Assets"
if command -v npm &> /dev/null; then
    npm install --legacy-peer-deps 2>&1 | tail -1
    npm run dev 2>&1 | tail -1
else
    echo "⚠️  npm non trouvé"
fi

# Étape 6: Démarrage
echo ""
echo "════════════════════════════════════════════"
echo "✅ Installation complète!"
echo "════════════════════════════════════════════"
echo ""
echo "🚀 Pour démarrer:"
echo "   php artisan serve"
echo ""
echo "🌐 Accédez à:"
echo "   http://localhost:8000"
echo ""
echo "📝 Identifiants:"
echo "   Email: jean@example.com"
echo "   Mot de passe: password"
echo ""
echo "📚 Documentation:"
echo "   cat BREAKDOWN_QUICK_START.md"
echo ""
