<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table des choix (choices)
 * 
 * Cette migration crée la table qui stocke les choix disponibles dans chaque chapitre.
 * Chaque choix appartient à un chapitre et pointe vers un autre chapitre,
 * créant ainsi le graphe de navigation de l'histoire interactive.
 */
return new class extends Migration
{
    /**
     * Exécute la migration pour créer la table choices
     * 
     * Crée une table avec les champs suivants:
     * - id: identifiant unique auto-incrémenté
     * - chapter_id: référence au chapitre parent (clé étrangère)
     * - text: texte du choix affiché à l'utilisateur
     * - next_chapter_id: référence au chapitre de destination (clé étrangère, facultatif)
     */
    public function up(): void
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chapter_id');
            $table->string('text');
            $table->unsignedBigInteger('next_chapter_id')->nullable();
            $table->timestamps();

            // Le choix est supprimé si le chapitre parent est supprimé (cascade)
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
            
            // Si un chapitre cible est supprimé, la référence devient nulle (set null)
            $table->foreign('next_chapter_id')->references('id')->on('chapters')->onDelete('set null');
        });
    }

    /**
     * Annule la migration en supprimant la table choices
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
