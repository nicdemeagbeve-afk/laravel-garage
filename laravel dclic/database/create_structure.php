<?php
/**
 * Script pour créer manuellement la structure SQLite
 * Utilise PDO pour créer une base SQLite fonctionnelle
 */

$dbPath = __DIR__ . '/database.sqlite';

// Créer la base de données
$pdo = new PDO("sqlite:$dbPath");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Active les contraintes de clés étrangères
$pdo->exec('PRAGMA foreign_keys = ON');

echo "=== Création de la structure de base de données ===\n\n";

try {
    // Table users (déjà existante, on va la créer)
    echo "Création table: users\n";
    $pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        email_verified_at TIMESTAMP NULL,
        password VARCHAR(255) NOT NULL,
        remember_token VARCHAR(100) NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    );
    SQL);

    // Table vehicules
    echo "Création table: vehicules\n";
    $pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS vehicules (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        marque VARCHAR(255) NOT NULL,
        modele VARCHAR(255) NOT NULL,
        annee INT NOT NULL,
        immatriculation VARCHAR(20) UNIQUE NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    );
    SQL);

    // Table techniciens
    echo "Création table: techniciens\n";
    $pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS techniciens (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL,
        email VARCHAR(255),
        telephone VARCHAR(20),
        photo_url VARCHAR(255) NULL,
        age INTEGER NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    );
    SQL);

    // Table reparations
    echo "Création table: reparations\n";
    $pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS reparations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        vehicule_id INTEGER NOT NULL,
        technicien_id INTEGER,
        description TEXT NOT NULL,
        date_debut DATE,
        date_fin DATE,
        cout_estime DECIMAL(10,2),
        cout_reel DECIMAL(10,2),
        statut VARCHAR(50),
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
        FOREIGN KEY (technicien_id) REFERENCES techniciens(id) ON DELETE SET NULL
    );
    SQL);

    // Table services
    echo "Création table: services\n";
    $pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS services (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(255) NOT NULL,
        description TEXT,
        prix DECIMAL(10,2),
        duree_estimee INT,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    );
    SQL);

    // Table breakdowns (NOUVELLE FONCTIONNALITÉ)
    echo "Création table: breakdowns\n";
    $pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS breakdowns (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        vehicule_id INTEGER NOT NULL,
        technicien_id INTEGER NULL,
        description TEXT NOT NULL,
        onsite_assistance BOOLEAN NOT NULL DEFAULT 0,
        latitude DECIMAL(10,8) NULL,
        longitude DECIMAL(11,8) NULL,
        status VARCHAR(50) NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
        FOREIGN KEY (technicien_id) REFERENCES techniciens(id) ON DELETE SET NULL
    );
    SQL);

    // Table migrations
    echo "Création table: migrations\n";
    $pdo->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS migrations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        migration VARCHAR(255) NOT NULL UNIQUE,
        batch INTEGER NOT NULL
    );
    SQL);

    // Insérer les migrations existantes
    $migrations = [
        '0001_01_01_000000_create_users_table',
        '0001_01_01_000001_create_cache_table',
        '0001_01_01_000002_create_jobs_table',
        '2026_01_20_161435_create_vehicules_table',
        '2026_01_20_172952_create_techniciens_table',
        '2026_01_21_141545_create_reparations_table',
        '2026_01_22_194822_create_services_table',
        '2026_01_23_000000_create_breakdowns_table',
        '2026_01_23_000001_add_user_id_to_vehicules_table',
        '2026_01_23_000002_add_photo_and_age_to_techniciens_table',
    ];

    $batch = 1;
    foreach ($migrations as $migration) {
        try {
            $pdo->exec("INSERT INTO migrations (migration, batch) VALUES ('$migration', $batch)");
        } catch (Exception $e) {
            // Migration déjà existante, on continue
        }
    }

    // Créer des index
    echo "Création des index\n";
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_breakdowns_user_id ON breakdowns(user_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_breakdowns_vehicule_id ON breakdowns(vehicule_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_breakdowns_status ON breakdowns(status)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_vehicules_user_id ON vehicules(user_id)');

    echo "\n✅ Structure de base de données créée avec succès!\n";
    echo "Base de données: $dbPath\n\n";

} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

// Insérer quelques données de démonstration
echo "=== Insertion de données de démonstration ===\n\n";

try {
    // Utilisateurs
    echo "Création d'utilisateurs de test\n";
    $pdo->exec(<<<SQL
    INSERT OR IGNORE INTO users (name, email, password, created_at, updated_at) VALUES
    ('Admin User', 'admin@example.com', '\$2y\$12\$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5EelJrdtm2S4m', datetime('now'), datetime('now')),
    ('Jean Dupont', 'jean@example.com', '\$2y\$12\$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5EelJrdtm2S4m', datetime('now'), datetime('now')),
    ('Marie Martin', 'marie@example.com', '\$2y\$12\$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5EelJrdtm2S4m', datetime('now'), datetime('now'));
    SQL);

    // Techniciens
    echo "Création de techniciens\n";
    $pdo->exec(<<<SQL
    INSERT OR IGNORE INTO techniciens (nom, prenom, email, telephone, photo_url, age, created_at, updated_at) VALUES
    ('Bernard', 'Pierre', 'pierre.bernard@garage.com', '06 12 34 56 78', 'https://via.placeholder.com/150?text=Pierre', 45, datetime('now'), datetime('now')),
    ('Moreau', 'Jean', 'jean.moreau@garage.com', '06 23 45 67 89', 'https://via.placeholder.com/150?text=Jean', 38, datetime('now'), datetime('now')),
    ('Leclerc', 'Claude', 'claude.leclerc@garage.com', '06 34 56 78 90', 'https://via.placeholder.com/150?text=Claude', 52, datetime('now'), datetime('now'));
    SQL);

    // Véhicules
    echo "Création de véhicules\n";
    $pdo->exec(<<<SQL
    INSERT OR IGNORE INTO vehicules (user_id, marque, modele, annee, immatriculation, created_at, updated_at) VALUES
    (2, 'Peugeot', '308', 2020, 'AB-123-CD', datetime('now'), datetime('now')),
    (2, 'Renault', 'Clio', 2019, 'EF-456-GH', datetime('now'), datetime('now')),
    (3, 'Citroën', 'C4', 2021, 'IJ-789-KL', datetime('now'), datetime('now'));
    SQL);

    // Services
    echo "Création de services\n";
    $pdo->exec(<<<SQL
    INSERT OR IGNORE INTO services (nom, description, prix, duree_estimee, created_at, updated_at) VALUES
    ('Révision', 'Révision complète du véhicule', 150.00, 120, datetime('now'), datetime('now')),
    ('Changement d''huile', 'Changement d''huile moteur', 50.00, 30, datetime('now'), datetime('now')),
    ('Remplacement plaquettes', 'Remplacement des plaquettes de frein', 200.00, 60, datetime('now'), datetime('now'));
    SQL);

    echo "\n✅ Données de démonstration insérées avec succès!\n";
    echo "\n📝 Identifiants de test:\n";
    echo "   Email: jean@example.com\n";
    echo "   Email: marie@example.com\n";
    echo "   Mot de passe: password\n\n";

} catch (PDOException $e) {
    echo "❌ Erreur lors de l'insertion des données: " . $e->getMessage() . "\n";
    exit(1);
}

echo "✅ Tout est prêt! Vous pouvez maintenant lancer l'application.\n";
?>
