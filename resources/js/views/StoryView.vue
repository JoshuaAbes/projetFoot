<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useStoryStore } from '../store/storyStore';
import { useProgressStore } from '../store/progressStore';

const router = useRouter();
const route = useRoute();
const storyStore = useStoryStore();
const progressStore = useProgressStore();

const storyId = route.params.id;
const story = ref(null);
const firstChapter = ref(null);
const totalChapters = ref(0);
const lastVisitedChapterIsEnding = ref(false);

// Calculer le texte du bouton en fonction de l'état
const buttonText = computed(() => {
  if (lastVisitedChapterIsEnding.value) {
    return 'Recommencer l\'histoire';
  }
  
  if (progressStore.visitedChapters[storyId] && progressStore.visitedChapters[storyId].length > 0) {
    return 'Reprendre la lecture';
  }
  
  return 'Commencer la lecture';
});

onMounted(async () => {
  try {
    // Charger l'histoire
    const response = await storyStore.fetchStory(storyId);
    
    if (!response) {
      storyStore.error = "Histoire introuvable.";
      return;
    }
    
    story.value = response;
    
    // Déterminer le nombre total de chapitres
    if (story.value && story.value.chapters) {
      totalChapters.value = story.value.chapters.length;
    }
    
    // Charger la progression
    await progressStore.loadProgress(storyId);
    
    // Vérifier si le dernier chapitre visité est un chapitre de fin
    if (progressStore.currentProgress && progressStore.currentProgress.current_chapter_id) {
      try {
        const lastChapter = await storyStore.fetchChapter(progressStore.currentProgress.current_chapter_id);
        lastVisitedChapterIsEnding.value = lastChapter && lastChapter.is_ending === true;
      } catch (error) {
        console.error("Erreur lors de la vérification du dernier chapitre:", error);
      }
    }
    
    // Charger le premier chapitre pour être prêt à commencer/recommencer
    firstChapter.value = await storyStore.fetchFirstChapter(storyId);
    
  } catch (error) {
    console.error("Erreur lors du chargement de l'histoire:", error);
    storyStore.error = "Erreur lors du chargement de l'histoire.";
  }
});

const startReading = () => {
  if (lastVisitedChapterIsEnding.value) {
    // Si le dernier chapitre visité est une fin, on réinitialise et on recommence
    resetProgress();
    
    // Rediriger vers le premier chapitre
    const chapterId = firstChapter.value.chapter ? firstChapter.value.chapter.id : firstChapter.value.id;
    router.push(`/stories/${storyId}/chapters/${chapterId}`);
  }
  else if (progressStore.currentProgress && progressStore.currentProgress.current_chapter_id) {
    // Sinon, reprendre où l'utilisateur s'était arrêté
    router.push(`/stories/${storyId}/chapters/${progressStore.currentProgress.current_chapter_id}`);
  } else if (firstChapter.value) {
    // Commencer par le premier chapitre
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
  
  // Indiquer que nous ne sommes plus sur un chapitre de fin
  lastVisitedChapterIsEnding.value = false;
};
</script>

<template>
  <div class="story-container">
    <!-- Loading et erreur -->
    <div v-if="storyStore.loading" class="loading-indicator">
      <p>Chargement de l'histoire...</p>
    </div>
    
    <div v-else-if="storyStore.error" class="error-message">
      <p>{{ storyStore.error }}</p>
      <button @click="$router.push('/')" class="back-button">Retour à l'accueil</button>
    </div>
    
    <!-- Vue de présentation de l'histoire (style moderne) -->
    <div v-else-if="story && story.title" class="story-card">
      <div class="story-header">
        <h1 class="story-title">{{ story.title }}</h1>
      </div>
      
      <div class="story-content">
        <p class="story-summary">{{ story.summary || 'Aucune description disponible.' }}</p>
        <p class="story-author" v-if="story.author">Par {{ story.author }}</p>
      </div>
      
      <div class="story-action">
        <button @click="startReading" class="start-button">
          {{ buttonText }}
        </button>
      </div>
    </div>
    
    <div v-else class="error-card">
      <p>Histoire introuvable.</p>
      <button @click="$router.push('/')" class="back-button">Retour à l'accueil</button>
    </div>
  </div>
</template>

<style scoped>
.story-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 60px);
  padding: 1rem;
  box-sizing: border-box;
  width: 100%;
}

.story-card {
  width: 100%;
  max-width: 100%;
  text-align: center;
  background-color: #24292e;
  color: var(--color-text);
  padding: 1.5rem 1rem;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  animation: fadeIn 0.8s ease;
}

.story-header {
  margin-bottom: 1.5rem;
}

.story-title {
  font-size: 1.8rem;
  margin: 0;
  font-weight: 700;
  word-break: break-word;
}

.story-content {
  margin-bottom: 2rem;
}

.story-summary {
  font-size: 1rem;
  line-height: 1.5;
  margin-bottom: 1rem;
}

.story-author {
  font-style: italic;
  color: #ccc;
  font-size: 0.9rem;
}

.story-action {
  margin-top: 1.5rem;
}

.start-button {
  padding: 0.8rem 1.5rem;
  font-size: 1rem;
  background-color: #4d9aff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s ease;
  width: 100%;
  max-width: 250px;
}

.loading-indicator,
.error-message,
.error-card {
  text-align: center;
  padding: 1.5rem;
  border-radius: 8px;
  width: 100%;
  max-width: 100%;
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

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Media queries pour tablettes */
@media (min-width: 768px) {
  .story-container {
    padding: 1.5rem;
    min-height: calc(100vh - 80px);
  }

  .story-card {
    max-width: 600px;
    padding: 2rem 1.5rem;
  }

  .story-title {
    font-size: 2rem;
  }

  .story-summary {
    font-size: 1.1rem;
  }
}

/* Media queries pour desktop */
@media (min-width: 1024px) {
  .story-container {
    padding: 2rem;
    min-height: calc(100vh - 120px);
  }

  .story-card {
    max-width: 800px;
    padding: 3rem 2rem;
  }

  .story-title {
    font-size: 2.5rem;
  }

  .story-summary {
    font-size: 1.2rem;
    line-height: 1.6;
  }

  .start-button {
    padding: 1rem 2rem;
    font-size: 1.2rem;
  }
}
</style>