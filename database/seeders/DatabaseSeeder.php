<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Site;
use App\Models\Local;
use App\Models\Chambre;
use App\Models\Equipement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Créer des utilisateurs
        $this->createUsers();
        
        // 2. Créer des sites
        $sites = $this->createSites();
        
        // 3. Créer des locaux (pavillons)
        $pavillons = $this->createPavillons($sites);
        
        // 4. Créer des équipements
        $equipements = $this->createEquipements($pavillons);
        
        // 5. Créer des chambres
        $chambres = $this->createChambres($pavillons);
        
        // 6. Assigner des équipements aux chambres
        $this->assignEquipementsToChambres($chambres, $equipements);
        
        // 7. Assigner des étudiants aux chambres
        $this->assignUsersToChambres($chambres);
    }
    
    private function createUsers()
    {
        // Admin
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'System',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'sexe' => 'h',
            'age' => 30,
            'telephone' => '123456789',
            'statut' => 'actif',
            'role' => 'admin'
        ]);
        
        // Gestionnaire
        User::create([
            'firstname' => 'Gestionnaire',
            'lastname' => 'Principal',
            'email' => 'gestionnaire@test.com',
            'password' => Hash::make('password'),
            'sexe' => 'f',
            'age' => 35,
            'telephone' => '987654321',
            'statut' => 'actif',
            'role' => 'gestionnaire'
        ]);
        
        // Étudiants
        $etudiants = [
            ['Fatou', 'Diop', 'kalsoum1509@gmail.com'],
            ['Moussa', 'Ba', 'kalsom1509@gmail.com'],
            ['Awa', 'Seck', 'kanycisse967@gmail.com'],
            ['Ibrahima', 'Fall', 'kany.cisse1@univ-thies.sn'],
            ['Aminata', 'Ndiaye', 'aminata.ndiaye@etudiant.com'],
            ['Omar', 'Sy', 'omar.sy@etudiant.com'],
            ['Khady', 'Thiam', 'khady.thiam@etudiant.com'],
            ['Mamadou', 'Diallo', 'mamadou.diallo@etudiant.com'],
            ['Aicha', 'Kane', 'aicha.kane@etudiant.com'],
            ['Ousmane', 'Sarr', 'ousmane.sarr@etudiant.com'],
            ['Binta', 'Gueye', 'binta.gueye@etudiant.com'],
            ['Cheikh', 'Diouf', 'cheikh.diouf@etudiant.com'],
            ['Mariama', 'Cisse', 'mariama.cisse@etudiant.com'],
            ['Alioune', 'Mbaye', 'alioune.mbaye@etudiant.com'],
            ['Ndeye', 'Faye', 'ndeye.faye@etudiant.com']
        ];
        
        $niveaux = ['Licence 1', 'Licence 2', 'Licence 3'];
        $filieres = ['Informatique', 'Gestion', 'Droit', 'Lettres', 'Sciences'];
        $domaines = ['Technologie', 'Commerce', 'Juridique', 'Littéraire', 'Scientifique'];
        
        foreach ($etudiants as $index => $etudiant) {
            User::create([
                'firstname' => $etudiant[0],
                'lastname' => $etudiant[1],
                'email' => $etudiant[2],
                'password' => Hash::make('password'),
                'sexe' => rand(0, 1) ? 'f' : 'h',
                'age' => rand(18, 25),
                'adresse' => 'Adresse ' . ($index + 1) . ', Dakar',
                'telephone' => '77' . str_pad($index + 1, 7, '0', STR_PAD_LEFT),
                'niveauEtude' => $niveaux[array_rand($niveaux)],
                'numDossier' => 'DOS' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'filiere' => $filieres[array_rand($filieres)],
                'domaine' => $domaines[array_rand($domaines)],
                'numCIN' => '1' . str_pad($index + 1, 12, '0', STR_PAD_LEFT),
                'statut' => 'actif',
                'role' => 'etudiant'
            ]);
        }
    }
    
    private function createSites()
    {
        $sites = [
            [
                'nom' => 'Campus Principal Dakar',
                'superficie' => 50000.00,
                'dateConstruction' => '2010-01-15',
                'localisation_lat' => 14.6937,
                'localisation_lng' => -17.4441
            ],
            [
                'nom' => 'Campus Annexe Pikine',
                'superficie' => 25000.00,
                'dateConstruction' => '2015-09-10',
                'localisation_lat' => 14.7547,
                'localisation_lng' => -17.3906
            ]
        ];
        
        $siteModels = [];
        foreach ($sites as $site) {
            $siteModels[] = Site::create($site);
        }
        
        return $siteModels;
    }
    
    private function createPavillons($sites)
    {
        $pavillons = [];
        
        // Pavillons pour le campus principal
        $pavillonsPrincipal = [
            ['Pavillon A - Garçons', 2500.00, 80],
            ['Pavillon B - Filles', 2200.00, 70],
            ['Pavillon C - Mixte', 2800.00, 90]
        ];
        
        foreach ($pavillonsPrincipal as $pavillon) {
            $pavillons[] = Local::create([
                'id_site' => $sites[0]->id,
                'nom' => $pavillon[0],
                'superficie' => $pavillon[1],
                'capacite' => $pavillon[2],
                'disponible' => true,
                'statutConforme' => 'conforme',
                'type' => 'pavillon'
            ]);
        }
        
        // Pavillons pour le campus annexe
        $pavillonsAnnexe = [
            ['Pavillon D - Garçons', 1800.00, 60],
            ['Pavillon E - Filles', 1600.00, 50]
        ];
        
        foreach ($pavillonsAnnexe as $pavillon) {
            $pavillons[] = Local::create([
                'id_site' => $sites[1]->id,
                'nom' => $pavillon[0],
                'superficie' => $pavillon[1],
                'capacite' => $pavillon[2],
                'disponible' => true,
                'statutConforme' => 'conforme',
                'type' => 'pavillon'
            ]);
        }
        
        return $pavillons;
    }
    
    private function createEquipements($pavillons)
    {
        $equipements = [];
        $typesEquipements = [
            ['Lit', 'mobilier', 'bon'],
            ['Bureau', 'mobilier', 'bon'],
            ['Chaise', 'mobilier', 'bon'],
            ['Armoire', 'mobilier', 'neuf'],
            ['Ventilateur', 'electromenager', 'bon'],
            ['Réfrigérateur', 'electromenager', 'neuf'],
            ['Ordinateur', 'informatique', 'bon'],
            ['Lampe de bureau', 'electricite', 'bon'],
            ['Radiateur', 'chauffage', 'use']
        ];
        
        foreach ($pavillons as $pavillon) {
            // Créer quelques équipements pour chaque pavillon
            for ($i = 0; $i < 15; $i++) {
                $equipement = $typesEquipements[array_rand($typesEquipements)];
                $equipements[] = Equipement::create([
                    'id_local' => $pavillon->id,
                    'nom' => $equipement[0] . ' - ' . ($i + 1),
                    'type' => $equipement[1],
                    'etat' => $equipement[2],
                    'dateDebut' => now()->subMonths(rand(1, 12)),
                    'dateFin' => now()->addYears(rand(1, 5))
                ]);
            }
        }
        
        return $equipements;
    }
    
    private function createChambres($pavillons)
    {
        $chambres = [];
        $statuts = ['libre', 'occupe', 'en maintenance'];
        
        foreach ($pavillons as $pavillon) {
            // Créer 15-20 chambres par pavillon
            $nombreChambres = rand(15, 20);
            
            for ($i = 1; $i <= $nombreChambres; $i++) {
                $numero = str_pad($i, 3, '0', STR_PAD_LEFT);
                $chambres[] = Chambre::create([
                    'id_pavillon' => $pavillon->id,
                    'nom' => 'Chambre ' . $numero . ' - ' . $pavillon->nom,
                    'numero' => $numero,
                    'superficie' => rand(15, 25) + (rand(0, 99) / 100), // entre 15.00 et 25.99
                    'capacite' => rand(2, 6), // entre 2 et 6 places
                    'statut' => $statuts[array_rand($statuts)]
                ]);
            }
        }
        
        return $chambres;
    }
    
    private function assignEquipementsToChambres($chambres, $equipements)
    {
        foreach ($chambres as $chambre) {
            // Assigner 3-8 équipements par chambre
            $nombreEquipements = rand(3, 8);
            $equipementsPavillon = collect($equipements)->where('id_local', $chambre->id_pavillon);
            
            $equipementsAssignes = $equipementsPavillon->random(min($nombreEquipements, $equipementsPavillon->count()));
            
            foreach ($equipementsAssignes as $equipement) {
                $chambre->equipements()->attach($equipement->id, [
                    'quantite' => rand(1, 3),
                    'date_installation' => now()->subDays(rand(1, 365))
                ]);
            }
        }
    }
    
    private function assignUsersToChambres($chambres)
    {
        $etudiants = User::where('role', 'etudiant')->get();
        $etudiantIndex = 0;
        
        foreach ($chambres as $chambre) {
            // Assigner des étudiants selon la capacité et le statut
            if ($chambre->statut === 'libre') {
                $nombreEtudiants = rand(0, $chambre->capacite - 1); // Chambre libre = pas pleine
            } elseif ($chambre->statut === 'occupe') {
                $nombreEtudiants = rand(1, $chambre->capacite); // Chambre occupée = au moins 1 étudiant
            } else {
                $nombreEtudiants = 0; // Chambre en maintenance = vide
            }
            
            for ($i = 0; $i < $nombreEtudiants && $etudiantIndex < $etudiants->count(); $i++) {
                $chambre->users()->attach($etudiants[$etudiantIndex]->id);
                $etudiantIndex++;
            }
        }
    }
}