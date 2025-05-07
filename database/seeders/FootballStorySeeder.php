<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Choice;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FootballStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si l'utilisateur existe
        $user = User::first();
        if (!$user) {
            // Créer un utilisateur si aucun n'existe
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        
        // Charger le contenu du fichier JSON
        $jsonPath = database_path('data/story.json');
        if (!File::exists($jsonPath)) {
            $this->command->error('Le fichier story.json n\'existe pas!');
            return;
        }
        
        $storyData = json_decode(File::get($jsonPath), true);
        
        if (!isset($storyData['chapters']) || !is_array($storyData['chapters'])) {
            $this->command->error('Format JSON invalide. La clé "chapters" est requise.');
            return;
        }
        
        // Créer l'histoire
        $story = Story::create([
            'title' => 'Devenir une légende du football',
            'summary' => 'Une aventure interactive qui te permet de vivre le parcours d\'un jeune talent qui rêve de devenir une star du football.',
            'author' => 'Auteur',
            'is_published' => true,
            'user_id' => $user->id,
        ]);
        
        // Créer tous les chapitres
        $chapters = [];
        foreach ($storyData['chapters'] as $index => $chapterData) {
            $chapter = Chapter::create([
                'story_id' => $story->id,
                'title' => $chapterData['title'],
                'content' => $chapterData['content'],
                'chapter_number' => $index + 1,
                'is_ending' => !isset($chapterData['choices']) || empty($chapterData['choices']),
            ]);
            
            $chapters[$index] = $chapter;
        }
        
        // Créer les choix après avoir créé tous les chapitres
        foreach ($storyData['chapters'] as $index => $chapterData) {
            if (isset($chapterData['choices']) && is_array($chapterData['choices'])) {
                foreach ($chapterData['choices'] as $choiceData) {
                    // next_chapter est l'index dans le tableau JSON (basé sur 1)
                    $nextChapterIndex = $choiceData['next_chapter'] - 1;
                    
                    // Vérifier si l'index du chapitre de destination est valide
                    if (!isset($chapters[$nextChapterIndex])) {
                        $this->command->warning("Index de chapitre invalide: {$choiceData['next_chapter']}");
                        continue;
                    }
                    
                    Choice::create([
                        'chapter_id' => $chapters[$index]->id,
                        'text' => $choiceData['text'],
                        'next_chapter_id' => $chapters[$nextChapterIndex]->id,
                    ]);
                }
            }
        }
        
        $this->command->info('Histoire créée avec succès avec ' . count($chapters) . ' chapitres.');
    }
}