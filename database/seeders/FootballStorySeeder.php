<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Choice;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * Seeder pour créer une histoire interactive sur le football
 * 
 * Ce seeder charge les données d'histoire depuis un fichier JSON et crée
 * l'histoire complète avec tous ses chapitres et choix dans la base de données.
 * Il crée également un utilisateur administrateur si aucun n'existe.
 */
class FootballStorySeeder extends Seeder
{
    /**
     * Exécute le seeder pour créer l'histoire interactive
     * 
     * Étapes du processus:
     * 1. Vérifie/crée un utilisateur administrateur
     * 2. Charge les données d'histoire depuis un fichier JSON
     * 3. Crée l'entrée principale de l'histoire
     * 4. Crée les chapitres associés
     * 5. Crée les choix entre les chapitres
     * 
     * @return void
     */
    public function run(): void
    {
        // Vérifier si l'utilisateur existe
        $user = User::first();
        if (!$user) {
            // Créer un utilisateur administrateur si aucun n'existe
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        
        // Charger le contenu du fichier JSON contenant les données de l'histoire
        $jsonPath = database_path('data/story.json');
        if (!File::exists($jsonPath)) {
            $this->command->error('Le fichier story.json n\'existe pas!');
            return;
        }
        
        $storyData = json_decode(File::get($jsonPath), true);
        
        // Vérifier que le format JSON est correct et contient les chapitres
        if (!isset($storyData['chapters']) || !is_array($storyData['chapters'])) {
            $this->command->error('Format JSON invalide. La clé "chapters" est requise.');
            return;
        }
        
        // Créer l'entrée principale de l'histoire dans la base de données
        $story = Story::create([
            'title' => 'Devenir une légende du football',
            'summary' => 'Une aventure interactive qui te permet de vivre le parcours d\'un jeune talent qui rêve de devenir une star du football.',
            'author' => 'Auteur',
            'is_published' => true,
            'user_id' => $user->id,
        ]);
        
        // Première passe: créer tous les chapitres pour pouvoir référencer leurs IDs plus tard
        $chapters = [];
        foreach ($storyData['chapters'] as $index => $chapterData) {
            $chapter = Chapter::create([
                'story_id' => $story->id,
                'title' => $chapterData['title'],
                'content' => $chapterData['content'],
                'chapter_number' => $index + 1,
                // Un chapitre sans choix est considéré comme une fin d'histoire
                'is_ending' => !isset($chapterData['choices']) || empty($chapterData['choices']),
            ]);
            
            $chapters[$index] = $chapter;
        }
        
        // Deuxième passe: créer les choix entre les chapitres
        foreach ($storyData['chapters'] as $index => $chapterData) {
            if (isset($chapterData['choices']) && is_array($chapterData['choices'])) {
                foreach ($chapterData['choices'] as $choiceData) {
                    // L'indice du chapitre de destination dans le JSON (base 1)
                    $nextChapterIndex = $choiceData['next_chapter'] - 1;
                    
                    // Vérifier si l'indice du chapitre de destination est valide
                    if (!isset($chapters[$nextChapterIndex])) {
                        $this->command->warning("Index de chapitre invalide: {$choiceData['next_chapter']}");
                        continue;
                    }
                    
                    // Créer le choix avec la référence au chapitre de destination
                    Choice::create([
                        'chapter_id' => $chapters[$index]->id,
                        'text' => $choiceData['text'],
                        'next_chapter_id' => $chapters[$nextChapterIndex]->id,
                    ]);
                }
            }
        }
        
        // Afficher un message de confirmation
        $this->command->info('Histoire créée avec succès avec ' . count($chapters) . ' chapitres.');
    }
}