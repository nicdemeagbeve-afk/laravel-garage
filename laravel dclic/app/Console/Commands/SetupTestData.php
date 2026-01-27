<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\vehicule;
use App\Models\Technicien;
use App\Models\Service;
use Illuminate\Console\Command;

class SetupTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-test-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer les utilisateurs de test et données initiales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Création des données de test...');

        // 1. Créer Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@garage.fr'],
            [
                'name' => 'Administrateur',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'is_active' => true
            ]
        );
        $this->line("✅ Admin créé: admin@garage.fr (admin123)");

        // 2. Créer Responsable Services
        $responsable = User::updateOrCreate(
            ['email' => 'responsable@garage.fr'],
            [
                'name' => 'David Lemoine',
                'password' => bcrypt('responsable123'),
                'role' => 'responsable_services',
                'is_active' => true
            ]
        );
        $this->line("✅ Responsable créé: responsable@garage.fr (responsable123)");

        // 3. Créer Gestion Client
        $gestion = User::updateOrCreate(
            ['email' => 'gestion@garage.fr'],
            [
                'name' => 'Marie Martin',
                'password' => bcrypt('gestion123'),
                'role' => 'gestion_client',
                'is_active' => true
            ]
        );
        $this->line("✅ Gestion Client créé: gestion@garage.fr (gestion123)");

        // 4. Créer Clients
        $client1 = User::updateOrCreate(
            ['email' => 'jean@example.com'],
            [
                'name' => 'Jean Dupont',
                'password' => bcrypt('client123'),
                'role' => 'client',
                'is_active' => true
            ]
        );
        $this->line("✅ Client 1 créé: jean@example.com (client123)");

        $client2 = User::updateOrCreate(
            ['email' => 'sophie@example.com'],
            [
                'name' => 'Sophie Bernard',
                'password' => bcrypt('client123'),
                'role' => 'client',
                'is_active' => true
            ]
        );
        $this->line("✅ Client 2 créé: sophie@example.com (client123)");

        // 5. Créer Véhicules
        Vehicule::updateOrCreate(
            ['immatriculation' => 'AB-123-CD'],
            [
                'user_id' => $client1->id,
                'marque' => 'Peugeot',
                'modele' => '308',
                'couleur' => 'Noir',
                'annee' => 2021,
                'kilometrage' => 80000,
                'carrosserie' => 'Berline',
                'energie' => 'Essence',
                'boite' => 'Manuelle'
            ]
        );
        $this->line("✅ Véhicule 1 créé");

        Vehicule::updateOrCreate(
            ['immatriculation' => 'EF-456-GH'],
            [
                'user_id' => $client1->id,
                'marque' => 'Renault',
                'modele' => 'Clio',
                'couleur' => 'Blanc',
                'annee' => 2023,
                'kilometrage' => 15000,
                'carrosserie' => 'Berline',
                'energie' => 'Essence',
                'boite' => 'Automatique'
            ]
        );
        $this->line("✅ Véhicule 2 créé");

        // 6. Créer Techniciens
        Technicien::updateOrCreate(
            ['nom' => 'Martin', 'prenom' => 'Marc'],
            [
                'specialite' => 'Moteur',
                'photo_url' => null,
                'age' => 35
            ]
        );
        $this->line("✅ Technicien 1 créé (Marc Martin)");

        Technicien::updateOrCreate(
            ['nom' => 'Durand', 'prenom' => 'Paul'],
            [
                'specialite' => 'Électricité',
                'photo_url' => null,
                'age' => 42
            ]
        );
        $this->line("✅ Technicien 2 créé (Paul Durand)");

        Technicien::updateOrCreate(
            ['nom' => 'Fournier', 'prenom' => 'Sophie'],
            [
                'specialite' => 'Freinage',
                'photo_url' => null,
                'age' => 38
            ]
        );
        $this->line("✅ Technicien 3 créé (Sophie Fournier)");

        // 7. Créer Services
        Service::updateOrCreate(
            ['name' => 'Diagnostic Électrique'],
            [
                'description' => 'Diagnostic complet système électrique',
                'price' => 50.00,
                'images' => null
            ]
        );
        $this->line("✅ Service 1 créé");

        Service::updateOrCreate(
            ['name' => 'Nettoyage Batterie'],
            [
                'description' => 'Nettoyage et remplacement batterie',
                'price' => 25.00,
                'images' => null
            ]
        );
        $this->line("✅ Service 2 créé");

        Service::updateOrCreate(
            ['name' => 'Remplacement Démarreur'],
            [
                'description' => 'Changement démarreur moteur',
                'price' => 180.00,
                'images' => null
            ]
        );
        $this->line("✅ Service 3 créé");

        Service::updateOrCreate(
            ['name' => 'Vidange'],
            [
                'description' => 'Vidange complète moteur',
                'price' => 45.00,
                'images' => null
            ]
        );
        $this->line("✅ Service 4 créé");

        $this->info("\n✨ Toutes les données de test ont été créées!");
        $this->line("\n📝 Identifiants de connexion:");
        $this->line("  Admin:              admin@garage.fr / admin123");
        $this->line("  Responsable:        responsable@garage.fr / responsable123");
        $this->line("  Gestion Client:     gestion@garage.fr / gestion123");
        $this->line("  Client 1:           jean@example.com / client123");
        $this->line("  Client 2:           sophie@example.com / client123");
    }
}
