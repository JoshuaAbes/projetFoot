<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useStoryStore } from '../store/storyStore';
import { useProgressStore } from '../store/progressStore';
import ProgressIndicator from '../components/ProgressIndicator.vue';

const router = useRouter();
const route = useRoute();
const storyStore = useStoryStore();
const progressStore = useProgressStore();

const storyId = route.params.id;
const story = ref(null);
const firstChapter = ref(null);
const totalChapters = ref(0);

onMounted(async () => {
  try {
    // Charger l'histoire
    const response = await storyStore.fetchStory(storyId);
    console.log("Réponse API:", response); // Débogage
    
    if (!response) {
      storyStore.error = "Histoire introuvable.";
      return;
    }
    
    story.value = response;
    
    // Déterminer le nombre total de chapitres pour le calcul de pourcentage
    if (story.value && story.value.chapters) {
      totalChapters.value = story.value.chapters.length;
    }
    
    // Charger la progression
    await progressStore.loadProgress(storyId);
    
    // Charger le premier chapitre ou celui en cours
    try {
      if (progressStore.currentProgress && progressStore.currentProgress.current_chapter_id) {
        // Reprendre où l'utilisateur s'était arrêté
        router.push(`/stories/${storyId}/chapters/${progressStore.currentProgress.current_chapter_id}`);
      } else {
        // Charger le premier chapitre
        firstChapter.value = await storyStore.fetchFirstChapter(storyId);
      }
    } catch (error) {
      console.error("Erreur lors du chargement du premier chapitre:", error);
    }
  } catch (error) {
    console.error("Erreur lors du chargement de l'histoire:", error);
    storyStore.error = "Erreur lors du chargement de l'histoire.";
  }
});

const startReading = () => {
  if (progressStore.currentProgress && progressStore.currentProgress.current_chapter_id) {
    // Reprendre où l'utilisateur s'était arrêté
    router.push(`/stories/${storyId}/chapters/${progressStore.currentProgress.current_chapter_id}`);
  } else if (firstChapter.value) {
    // Commencer par le premier chapitre - CORRECTION ICI
    const chapterId = firstChapter.value.chapter ? firstChapter.value.chapter.id : firstChapter.value.id;
    router.push(`/stories/${storyId}/chapters/${chapterId}`);
  }
};

const resetProgress = async () => {
  // Réinitialiser la progression locale
  if (progressStore.visitedChapters[storyId]) {
    progressStore.visitedChapters[storyId] = [];
    localStorage.setItem('visitedChapters', JSON.stringify(progressStore.visitedChapters));
  }
  
  // Réinitialiser la progression sur le serveur si authentifié
  if (document.cookie.includes('laravel_session')) {
    try {
      const { request } = fetchJson({
        url: `progress/${storyId}`,
        method: 'DELETE'
      });
      await request;
    } catch (error) {
      console.error("Erreur lors de la réinitialisation de la progression:", error);
    }
  }
  
  // Recharger la page pour actualiser l'interface
  window.location.reload();
};
</script>

<template>
  <div class="story-view">
    <div v-if="storyStore.loading" class="loading-indicator">
      <p>Chargement de l'histoire...</p>
    </div>
    
    <div v-else-if="storyStore.error" class="error-message">
      <p>{{ storyStore.error }}</p>
      <button @click="$router.push('/')" class="back-button">Retour à l'accueil</button>
    </div>
    
    <!-- Ajoutez cette vérification pour s'assurer que story existe -->
    <div v-else-if="story && story.title" class="story-detail">
      <div class="story-header">
        <div class="story-cover">
          <img v-if="story.cover_image" :src="story.cover_image" :alt="story.title" class="cover-image">
          <!-- Correction de la ligne problématique -->
          <div v-else class="cover-placeholder">{{ story.title && story.title.charAt(0) || '?' }}</div>
        </div>
        
        <div class="story-info">
          <h1 class="story-title">{{ story.title }}</h1>
          <p class="story-author" v-if="story.author">Par {{ story.author }}</p>
          
          <div class="story-progress" v-if="progressStore.visitedChapters[storyId]">
            <ProgressIndicator 
              :percentage="progressStore.progressPercentage(storyId, totalChapters)" 
            />
            <button @click="resetProgress" class="reset-progress-button">
              Réinitialiser la progression
            </button>
          </div>
        </div>
      </div>
      
      <div class="story-description">
        <h2>Description</h2>
        <p>{{ story.summary || 'Aucune description disponible.' }}</p>
      </div>
      
      <div class="story-action">
        <button @click="startReading" class="start-button">
          {{ progressStore.visitedChapters[storyId] && progressStore.visitedChapters[storyId].length > 0 
            ? 'Reprendre la lecture' 
            : 'Commencer la lecture' }}
        </button>
      </div>
    </div>
    
    <div v-else class="no-story">
      <p>Histoire introuvable.</p>
      <button @click="$router.push('/')" class="back-button">Retour à l'accueil</button>
    </div>
  </div>
</template>

<style scoped>
.story-view {
  padding: 1rem 0;
}

.story-header {
  display: flex;
  margin-bottom: 2rem;
}

.story-cover {
  width: 300px;
  height: 400px;
  margin-right: 2rem;
  overflow: hidden;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.cover-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.cover-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f2f2f2;
  font-size: 5rem;
  color: #ccc;
}

.story-info {
  flex: 1;
}

.story-title {
  font-size: 2.5rem;
  margin: 0 0 0.5rem 0;
}

.story-author {
  font-size: 1.2rem;
  color: #666;
  margin-bottom: 1rem;
}

.story-progress {
  margin: 1.5rem 0;
}

.story-description {
  margin-bottom: 2rem;
}

.story-description h2 {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.story-action {
  margin-top: 2rem;
}

.start-button {
  padding: 1rem 2rem;
  background: #333;
  color: white;
  font-size: 1.2rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s ease;
}

.start-button:hover {
  background: #555;
}

.reset-progress-button {
  margin-left: 1rem;
  padding: 0.5rem 1rem;
  background: #e53935;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.reset-progress-button:hover {
  background: #c62828;
}

.loading-indicator,
.error-message,
.no-story {
  text-align: center;
  padding: 2rem;
  background: #f5f5f5;
  border-radius: 8px;
  margin: 2rem 0;
}

.error-message {
  color: #e53935;
}

.back-button {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: #333;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

@media (max-width: 768px) {
  .story-header {
    flex-direction: column;
  }
  
  .story-cover {
    width: 100%;
    height: 300px;
    margin-right: 0;
    margin-bottom: 1.5rem;
  }
  
  .story-title {
    font-size: 2rem;
  }
}
</style>