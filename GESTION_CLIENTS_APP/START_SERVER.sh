#!/bin/bash
# 🚀 DÉMARRAGE RAPIDE - PROJET GARAGE

echo "╔════════════════════════════════════════════════════════╗"
echo "║     🚗 PROJET GARAGE - DÉMARRAGE RAPIDE (30 sec)      ║"
echo "╚════════════════════════════════════════════════════════╝"
echo ""

# Vérifier que nous sommes dans le bon dossier
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Veuillez être dans le dossier GESTION_CLIENTS_APP"
    exit 1
fi

echo "1️⃣  Démarrage du serveur Laravel..."
echo ""
echo "   🌐 Accédez à: http://localhost:8000"
echo ""
echo "   📝 Identifiants de test:"
echo "      Admin:              admin@garage.fr / admin123"
echo "      Responsable:        responsable@garage.fr / responsable123"
echo "      Gestion Client:     gestion@garage.fr / gestion123"
echo "      Client 1:           jean@example.com / client123"
echo "      Client 2:           sophie@example.com / client123"
echo ""
echo "   📊 Accès Admin: http://localhost:8000/admin/dashboard"
echo ""
echo "   🎯 Workflow à tester:"
echo "      1. Login client → Déclarer une panne"
echo "      2. Login gestion_client → Enrichir la panne"
echo "      3. Login responsable → Valider"
echo "      4. Login admin → Dashboard"
echo ""
echo "   💡 Commandes utiles:"
echo "      php artisan tinker           (Console PHP)"
echo "      php artisan route:list       (Voir routes)"
echo "      php artisan migrate:status   (État migrations)"
echo ""

# Démarrer le serveur
php artisan serve
