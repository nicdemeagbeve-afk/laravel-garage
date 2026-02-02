#!/usr/bin/env php
<?php
/**
 * Script d'installation - Crée la structure de base et les données de test
 * sans dépendre de PDO SQLite
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Technicien;
use App\Models\Service;

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "\n🔧 Installation du projet GESTION_CLIENTS_APP\n";
    echo "============================================\n\n";

    // Step 1: Enregistrer les migrations
    echo "📋 Enregistrement des migrations...\n";
    DB::table('migrations')->insert([
        ['migration' => '0001_01_01_000000_create_users_table', 'batch' => 1],
        ['migration' => '0001_01_01_000001_create_cache_table', 'batch' => 1],
        ['migration' => '0001_01_01_000002_create_jobs_table', 'batch' => 1],
        ['migration' => '2026_01_20_161435_create_vehicules_table', 'batch' => 1],
        ['migration' => '2026_01_20_172952_create_techniciens_table', 'batch' => 1],
        ['migration' => '2026_01_21_141545_create_reparations_table', 'batch' => 1],
        ['migration' => '2026_01_22_194822_create_services_table', 'batch' => 1],
        ['migration' => '2026_01_23_000000_create_breakdowns_table', 'batch' => 1],
        ['migration' => '2026_01_23_000001_add_user_id_to_vehicules_table', 'batch' => 1],
        ['migration' => '2026_01_23_000002_add_photo_and_age_to_techniciens_table', 'batch' => 1],
    ]);
    echo "✅ Migrations enregistrées\n\n";

    // Step 2: Créer les utilisateurs
    echo "👥 Création des utilisateurs de test...\n";
    $users = [
        [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ],
        [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => bcrypt('password'),
        ],
        [
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
            'password' => bcrypt('password'),
        ],
    ];

    foreach ($users as $userData) {
        User::firstOrCreate(['email' => $userData['email']], $userData);
    }
    echo "✅ 3 utilisateurs créés\n\n";

    // Step 3: Créer les techniciens
    echo "🔧 Création des techniciens...\n";
    $technicians = [
        [
            'nom' => 'Bernard',
            'prenom' => 'Pierre',
            'email' => 'pierre.bernard@garage.com',
            'telephone' => '06 12 34 56 78',
            'photo_url' => 'https://via.placeholder.com/150?text=Pierre',
            'age' => 45,
        ],
        [
            'nom' => 'Moreau',
            'prenom' => 'Jean',
            'email' => 'jean.moreau@garage.com',
            'telephone' => '06 23 45 67 89',
            'photo_url' => 'https://via.placeholder.com/150?text=Jean',
            'age' => 38,
        ],
        [
            'nom' => 'Leclerc',
            'prenom' => 'Claude',
            'email' => 'claude.leclerc@garage.com',
            'telephone' => '06 34 56 78 90',
            'photo_url' => 'https://via.placeholder.com/150?text=Claude',
            'age' => 52,
        ],
    ];

    foreach ($technicians as $techData) {
        Technicien::firstOrCreate(['email' => $techData['email']], $techData);
    }
    echo "✅ 3 techniciens créés\n\n";

    // Step 4: Créer les véhicules
    echo "🚗 Création des véhicules...\n";
    $jean = User::where('email', 'jean@example.com')->first();
    $marie = User::where('email', 'marie@example.com')->first();

    $vehicles = [
        ['user_id' => $jean->id, 'marque' => 'Peugeot', 'modele' => '308', 'annee' => 2020, 'immatriculation' => 'AB-123-CD'],
        ['user_id' => $jean->id, 'marque' => 'Renault', 'modele' => 'Clio', 'annee' => 2019, 'immatriculation' => 'EF-456-GH'],
        ['user_id' => $marie->id, 'marque' => 'Citroën', 'modele' => 'C4', 'annee' => 2021, 'immatriculation' => 'IJ-789-KL'],
    ];

    foreach ($vehicles as $vData) {
        Vehicule::firstOrCreate(['immatriculation' => $vData['immatriculation']], $vData);
    }
    echo "✅ 3 véhicules créés\n\n";

    // Step 5: Créer les services
    echo "🛠️  Création des services...\n";
    $services = [
        ['nom' => 'Révision', 'description' => 'Révision complète du véhicule', 'prix' => 150.00, 'duree_estimee' => 120],
        ['nom' => "Changement d'huile", 'description' => "Changement d'huile moteur", 'prix' => 50.00, 'duree_estimee' => 30],
        ['nom' => 'Remplacement plaquettes', 'description' => 'Remplacement des plaquettes de frein', 'prix' => 200.00, 'duree_estimee' => 60],
    ];

    foreach ($services as $sData) {
        Service::firstOrCreate(['nom' => $sData['nom']], $sData);
    }
    echo "✅ 3 services créés\n\n";

    echo "════════════════════════════════════════════\n";
    echo "✨ Installation terminée avec succès!\n";
    echo "════════════════════════════════════════════\n\n";

    echo "📝 Identifiants de test:\n";
    echo "   Email: jean@example.com\n";
    echo "   Email: marie@example.com\n";
    echo "   Email: admin@example.com\n";
    echo "   Mot de passe: password\n\n";

    echo "🚀 Commandes suivantes:\n";
    echo "   php artisan serve\n";
    echo "   Accédez à http://localhost:8000\n\n";

} catch (Exception $e) {
    echo "\n❌ Erreur: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
?>
