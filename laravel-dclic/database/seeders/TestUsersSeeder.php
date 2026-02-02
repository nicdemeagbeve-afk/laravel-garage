<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@garage.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'age' => 35,
            'sexe' => 'M',
            'residence' => 'Montréal, QC',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un gestionnaire de clients
        User::create([
            'name' => 'Gestion Client User',
            'email' => 'gestion@garage.test',
            'password' => Hash::make('password123'),
            'role' => 'gestion_client',
            'age' => 28,
            'sexe' => 'F',
            'residence' => 'Québec, QC',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un responsable de services
        User::create([
            'name' => 'Responsable Services',
            'email' => 'responsable@garage.test',
            'password' => Hash::make('password123'),
            'role' => 'responsable_services',
            'age' => 40,
            'sexe' => 'M',
            'residence' => 'Laval, QC',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un client actif
        User::create([
            'name' => 'Client Actif',
            'email' => 'client@garage.test',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'age' => 32,
            'sexe' => 'M',
            'residence' => 'Gatineau, QC',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Créer un client en attente d'approbation
        User::create([
            'name' => 'Client En Attente',
            'email' => 'client-waiting@garage.test',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'age' => 25,
            'sexe' => 'F',
            'residence' => 'Longueuil, QC',
            'is_active' => false,
            'verification_code' => 'ABC123DEF456',
            'created_by' => 1, // Créé par l'admin
        ]);

        // Créer un gestionnaire de clients créé par un autre utilisateur (en attente)
        User::create([
            'name' => 'Gestion Client En Attente',
            'email' => 'gestion-waiting@garage.test',
            'password' => Hash::make('password123'),
            'role' => 'gestion_client',
            'age' => 30,
            'sexe' => 'M',
            'residence' => 'Sherbrooke, QC',
            'is_active' => false,
            'verification_code' => 'XYZ789UVW012',
            'created_by' => 2, // Créé par le gestionnaire de clients
        ]);

        $this->command->info('Les utilisateurs de test ont été créés avec succès!');
        $this->command->info('Admin: admin@garage.test / password123');
        $this->command->info('Gestion Client: gestion@garage.test / password123');
        $this->command->info('Responsable Services: responsable@garage.test / password123');
        $this->command->info('Client: client@garage.test / password123');
    }
}
