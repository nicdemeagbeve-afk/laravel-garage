<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Technicien;
use App\Models\Breakdown;

class BreakdownCreationTest extends TestCase
{
    use RefreshDatabase;

    protected $client;
    protected $gestionClient;
    protected $admin;
    protected $vehicule;
    protected $technicien;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les utilisateurs de test
        $this->client = User::factory()->create([
            'name' => 'Client Test',
            'email' => 'client@test.com',
            'role' => 'client',
            'email_verified_at' => now()
        ]);

        $this->gestionClient = User::factory()->create([
            'name' => 'Gestionnaire Client',
            'email' => 'gestionclient@test.com',
            'role' => 'gestion_client',
            'email_verified_at' => now()
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // Créer un véhicule pour le client
        $this->vehicule = Vehicule::create([
            'user_id' => $this->client->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'immatriculation' => 'ABC-123',
            'annee_fabrication' => 2020,
            'boite' => 'Automatique',
            'nbr_portes' => 4
        ]);

        // Créer un technicien de test
        $this->technicien = Technicien::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'dupont@test.com',
            'specialite' => 'Moteur'
        ]);
    }

    /**
     * Test que seul un client peut accéder au formulaire de création de panne
     */
    public function test_only_client_can_access_breakdown_create_form()
    {
        // Le client peut accéder
        $response = $this->actingAs($this->client)
            ->get(route('breakdowns.create'));
        $this->assertTrue($response->status() === 200, "Client should access breakdown form");

        // Le gestionnaire client ne peut pas
        $response = $this->actingAs($this->gestionClient)
            ->get(route('breakdowns.create'));
        $this->assertEquals(403, $response->status(), "Gestion client should not access breakdown form");

        // L'admin ne peut pas
        $response = $this->actingAs($this->admin)
            ->get(route('breakdowns.create'));
        $this->assertEquals(403, $response->status(), "Admin should not access breakdown form");
    }

    /**
     * Test la création d'une panne par un client avec tous les champs
     */
    public function test_client_can_create_breakdown_with_technician()
    {
        $breakdownData = [
            'vehicule_id' => $this->vehicule->id,
            'description' => 'Le moteur ne démarre pas correctement',
            'phone' => '+33 6 12 34 56 78',
            'location' => 'Avenue de la République, 75000 Paris',
            'needs_technician' => 1,
            'technicien_id' => $this->technicien->id,
            'onsite_assistance' => 1
        ];

        $response = $this->actingAs($this->client)
            ->post(route('breakdowns.store'), $breakdownData);

        // Should redirect
        $this->assertTrue($response->status() >= 200 && $response->status() < 400, 'Response should be successful');

        // Vérifier que la panne a été créée en base de données
        $this->assertDatabaseHas('breakdowns', [
            'user_id' => $this->client->id,
            'vehicule_id' => $this->vehicule->id,
            'phone' => '+33 6 12 34 56 78',
            'location' => 'Avenue de la République, 75000 Paris',
            'status' => 'pending',
            'is_approved' => false
        ]);
    }

    /**
     * Test la création d'une panne sans dépannage sur place
     */
    public function test_client_can_create_breakdown_without_technician()
    {
        $breakdownData = [
            'vehicule_id' => $this->vehicule->id,
            'description' => 'Je veux juste des conseils',
            'phone' => '06 11 22 33 44',
            'location' => 'Rue des Oliviers, Lyon',
            'needs_technician' => 0,
            'onsite_assistance' => 0
        ];

        $response = $this->actingAs($this->client)
            ->post(route('breakdowns.store'), $breakdownData);

        // Should succeed
        $this->assertTrue($response->status() >= 200 && $response->status() < 400, 'Response should be successful');

        // Vérifier que la panne a été créée
        $this->assertDatabaseHas('breakdowns', [
            'user_id' => $this->client->id,
            'phone' => '06 11 22 33 44',
            'location' => 'Rue des Oliviers, Lyon'
        ]);
    }

    /**
     * Test que les autres rôles ne peuvent pas créer de pannes
     */
    public function test_non_client_users_cannot_create_breakdown()
    {
        $breakdownData = [
            'vehicule_id' => $this->vehicule->id,
            'description' => 'Test',
            'phone' => '06 12 34 56 78',
            'location' => 'Paris',
            'needs_technician' => 0
        ];

        // Gestionnaire client ne peut pas
        $response = $this->actingAs($this->gestionClient)
            ->post(route('breakdowns.store'), $breakdownData);
        $this->assertEquals(403, $response->status(), "Gestion client should not create breakdown");

        // Admin ne peut pas
        $response = $this->actingAs($this->admin)
            ->post(route('breakdowns.store'), $breakdownData);
        $this->assertEquals(403, $response->status(), "Admin should not create breakdown");

        // Vérifier qu'aucune panne n'a été créée
        $this->assertEquals(0, Breakdown::count());
    }

    /**
     * Test la validation du format du téléphone
     */
    public function test_phone_number_validation()
    {
        // Numéro invalide (caractères invalides)
        $response = $this->actingAs($this->client)
            ->post(route('breakdowns.store'), [
                'vehicule_id' => $this->vehicule->id,
                'description' => 'Test',
                'phone' => 'ABC-123',
                'location' => 'Paris',
                'needs_technician' => 0
            ]);

        // Should NOT succeed
        $this->assertEquals(0, Breakdown::count());

        // Numéro trop court
        $response = $this->actingAs($this->client)
            ->post(route('breakdowns.store'), [
                'vehicule_id' => $this->vehicule->id,
                'description' => 'Test',
                'phone' => '123',
                'location' => 'Paris',
                'needs_technician' => 0
            ]);

        // Should fail
        $this->assertEquals(0, Breakdown::count());

        // Numéro valide
        $response = $this->actingAs($this->client)
            ->post(route('breakdowns.store'), [
                'vehicule_id' => $this->vehicule->id,
                'description' => 'Test valide',
                'phone' => '06 12 34 56 78',
                'location' => 'Paris',
                'needs_technician' => 0
            ]);

        // Should succeed
        $this->assertTrue($response->status() >= 200 && $response->status() < 400, 'Valid phone should be accepted');
        $this->assertEquals(1, Breakdown::count());
    }

    /**
     * Test that client can see their own breakdowns
     */
    public function test_client_can_see_their_breakdowns()
    {
        // Create a breakdown
        $breakdown = Breakdown::create([
            'user_id' => $this->client->id,
            'vehicule_id' => $this->vehicule->id,
            'description' => 'Test breakdown',
            'phone' => '06 12 34 56 78',
            'location' => 'Paris',
            'status' => 'pending',
            'is_approved' => false
        ]);

        // Client should see it
        $response = $this->actingAs($this->client)
            ->get(route('breakdowns.index'));
        
        $this->assertTrue($response->status() === 200, 'Client should access breakdowns list');
    }
}
