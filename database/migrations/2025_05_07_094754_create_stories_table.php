<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer la table des histoires (stories)
 * 
 * Cette migration crée la table principale qui stocke les histoires interactives.
 * Chaque histoire contient plusieurs chapitres et est optionnellement liée à un utilisateur.
 */
return new class extends Migration
{
    /**
     * Exécute la migration pour créer la table stories
     * 
     * Crée une table avec les champs suivants:
     * - id: identifiant unique auto-incrémenté
     * - title: titre de l'histoire (obligatoire)
     * - summary: résumé de l'histoire (facultatif)
     * - author: nom de l'auteur (facultatif)
     * - is_published: indique si l'histoire est publiée (par défaut: false)
     * - user_id: référence à l'utilisateur créateur (facultatif, clé étrangère)
     */
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->string('author')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Annule la migration en supprimant la table stories
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
