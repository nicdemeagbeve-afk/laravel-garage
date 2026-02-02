<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Diagnostic Complet',
                'description' => 'Utilisation d\'outils de diagnostic avancés pour identifier rapidement les problèmes mécaniques et électroniques.',
                'price' => 150.00,
                'images' => null,
            ],
            [
                'name' => 'Entretien Régulier',
                'description' => 'Services d\'entretien préventif, y compris les vidanges, les remplacements de filtres et les inspections de sécurité.',
                'price' => 200.00,
                'images' => null,
            ],
            [
                'name' => 'Réparations Mécaniques',
                'description' => 'Réparations de moteurs, de transmissions, de freins et d\'autres composants essentiels.',
                'price' => 300.00,
                'images' => null,
            ],
            [
                'name' => 'Électricité Automobile',
                'description' => 'Diagnostic et réparation des systèmes électriques, y compris les batteries, les alternateurs et les systèmes d\'éclairage.',
                'price' => 250.00,
                'images' => null,
            ],
            [
                'name' => 'Climatisation et Chauffage',
                'description' => 'Entretien et réparation des systèmes de climatisation et de chauffage pour un confort optimal.',
                'price' => 280.00,
                'images' => null,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
