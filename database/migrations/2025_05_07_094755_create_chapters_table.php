<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table des chapitres (chapters)
 * 
 * Cette migration crée la table qui stocke les chapitres des histoires interactives.
 * Chaque chapitre appartient à une histoire et peut contenir plusieurs choix
 * permettant aux utilisateurs de naviguer vers d'autres chapitres.
 */
return new class extends Migration
{
    /**
     * Exécute la migration pour créer la table chapters
     * 
     * Crée une table avec les champs suivants:
     * - id: identifiant unique auto-incrémenté
     * - story_id: référence à l'histoire parente (clé étrangère)
     * - title: titre du chapitre
     * - content: contenu textuel du chapitre
     * - chapter_number: numéro de séquence du chapitre
     * - is_ending: indique si le chapitre est une fin d'histoire (par défaut: false)
     * - is_starting: indique si le chapitre est le début de l'histoire (par défaut: false)
     */
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('story_id');
            $table->string('title');
            $table->text('content');
            $table->integer('chapter_number');
            $table->boolean('is_ending')->default(false); 
            $table->boolean('is_starting')->default(false);  
            $table->timestamps();

            // Le chapitre est supprimé si l'histoire est supprimée (cascade)
            $table->foreign('story_id')->references('id')->on('stories')->onDelete('cascade');
        });
    }

    /**
     * Annule la migration en supprimant la table chapters
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
