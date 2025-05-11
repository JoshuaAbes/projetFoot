<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStoryStore } from '../store/storyStore';
import { useProgressStore } from '../store/progressStore';

const route = useRoute();
const router = useRouter();
const storyStore = useStoryStore();
const progressStore = useProgressStore();

const storyId = route.params.storyId;
const chapterId = route.params.chapterId;
const chapter = ref(null);
const loading = ref(true);
const error = ref(null);
const fadeOutAnimation = ref(false);
const nextChapterId = ref(null);
const totalChapters = ref(0);
const currentChapterNumber = ref(0);

const loadChapter = async (id) => {
  loading.value = true;
  error.value = null;
  
  try {
    const data = await storyStore.fetchChapter(id);
    if (data) {
      chapter.value = data;
      currentChapterNumber.value = data.chapter_number;
      
      // Charger le nombre total de chapitres si nécessaire
      if (!totalChapters.value) {
        const story = await storyStore.fetchStory(storyId);
        if (story && story.chapters) {
          totalChapters.value = story.chapters.length;
        }
      }
      
      // Sauvegarder la progression
      await progressStore.saveProgress(storyId, id);
    } else {
      error.value = "Chapitre introuvable.";
    }
  } catch (err) {
    error.value = "Erreur lors du chargement du chapitre.";
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const selectChoice = (choiceId, nextChapterIdParam) => {
  // Animation de transition
  fadeOutAnimation.value = true;
  
  // Stockez simplement la valeur
  nextChapterId.value = nextChapterIdParam;
  
  // Attendre la fin de l'animation avant de naviguer
  setTimeout(() => {
    router.push(`/stories/${storyId}/chapters/${nextChapterIdParam}`);
  }, 500);
};

const goToStory = () => {
  router.push(`/stories/${storyId}`);
};

const restartStory = async () => {
  // Réinitialiser la progression
  if (progressStore.visitedChapters[storyId]) {
    progressStore.visitedChapters[storyId] = [];
    localStorage.setItem('visitedChapters', JSON.stringify(progressStore.visitedChapters));
  }
  
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
  
  // Charger le premier chapitre
  try {
    const firstChapter = await storyStore.fetchFirstChapter(storyId);
    if (firstChapter) {
      const firstChapterId = firstChapter.chapter ? firstChapter.chapter.id : firstChapter.id;
      router.push(`/stories/${storyId}/chapters/${firstChapterId}`);
    } else {
      router.push(`/stories/${storyId}`);
    }
  } catch (error) {
    console.error("Erreur lors du chargement du premier chapitre:", error);
    router.push(`/stories/${storyId}`);
  }
};

// Charger le chapitre initial
onMounted(() => {
  loadChapter(chapterId);
});

// Réagir aux changements de route pour les nouveaux chapitres
watch(() => route.params.chapterId, (newId, oldId) => {
  if (newId !== oldId) {
    fadeOutAnimation.value = false;
    loadChapter(newId);
  }
});
</script>

<template>
  <div class="chapter-container">
    <div v-if="loading" class="loading-indicator">
      <p>Chargement du chapitre...</p>
    </div>
    
    <div v-else-if="error" class="error-message">
      <p>{{ error }}</p>
      <button @click="goToStory" class="back-button">Retour à l'histoire</button>
    </div>
    
    <div v-else-if="chapter" 
      :class="['chapter-content', { 'fade-out': fadeOutAnimation }]">
      
      <div class="chapter-text-container">
        <h1 class="chapter-title" v-if="chapter.title">{{ chapter.title }}</h1>
        
        <div class="chapter-text">
          <p>{{ chapter.content }}</p>
        </div>
      </div>
      
      <!-- Choix ou fin -->
      <div v-if="chapter.is_ending" class="chapter-ending">
        <p class="ending-message">Fin de l'histoire</p>
        <button @click="goToStory" class="return-button">Retour à l'histoire</button>
      </div>
      
      <div v-else-if="chapter.choices && chapter.choices.length > 0" 
        class="chapter-choices">
        <ul class="choices-list">
          <li v-for="choice in chapter.choices" :key="choice.id" class="choice-item">
            <button 
              @click="selectChoice(choice.id, choice.next_chapter_id)" 
              class="choice-button"
              :class="{ 'visited': progressStore.isChapterVisited(storyId, choice.next_chapter_id) }"
            >
              {{ choice.text }}
              <span v-if="progressStore.isChapterVisited(storyId, choice.next_chapter_id)" 
                class="visited-indicator">(déjà visité)</span>
            </button>
          </li>
        </ul>
      </div>
      
      <div v-else class="no-choices">
        <button @click="goToStory" class="return-button">Retour à l'histoire</button>
      </div>
      
      <!-- Footer avec informations -->
      <div class="chapter-footer">
        <button @click="restartStory" class="restart-button">Recommencer</button>
        <div class="chapter-counter">{{ currentChapterNumber }}/{{ totalChapters }}</div>
      </div>
    </div>
    
    <div v-else class="no-chapter">
      <p>Chapitre introuvable.</p>
      <button @click="goToStory" class="back-button">Retour à l'histoire</button>
    </div>
  </div>
</template>

<style scoped>
.chapter-container {
  display: flex;
  justify-content: center;
  min-height: 100vh;
  position: relative;
  background-color: #ce9059;
}

.chapter-content {
  width: 100%;
  max-width: 800px;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 100vh;
  opacity: 1;
  transition: opacity 0.5s ease;
}

.fade-out {
  opacity: 0;
}

.chapter-text-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  flex-grow: 1;
  padding: 2rem 0;
}

.chapter-title {
  font-size: 2rem;
  margin-bottom: 2rem;
  text-align: center;
  color: white;
}

.chapter-text {
  font-size: 1.2rem;
  line-height: 1.7;
  color: white;
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

.chapter-choices {
  margin-top: 3rem;
  margin-bottom: 5rem;
}

.choices-list {
  list-style-type: none;
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.choice-item {
  margin-bottom: 1rem;
  width: 100%;
  max-width: 400px;
}

.choice-button {
  width: 100%;
  padding: 0.8rem 1.5rem;
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: 2px solid white;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: center;
  font-size: 1rem;
}

.choice-button:hover {
  background-color: rgba(255, 255, 255, 0.2);
}

.choice-button.visited {
  border-color: rgba(255, 255, 255, 0.5);
  color: rgba(255, 255, 255, 0.8);
}

.visited-indicator {
  font-size: 0.8rem;
  margin-left: 0.5rem;
  opacity: 0.8;
}

.chapter-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
  position: absolute;
  bottom: 1rem;
  left: 1rem;
  right: 1rem;
}

.restart-button {
  padding: 0.5rem 1rem;
  background-color: transparent;
  color: white;
  border: 1px solid white;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
}

.restart-button:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.chapter-counter {
  color: white;
  font-size: 1rem;
}

.chapter-ending {
  margin-top: 2rem;
  text-align: center;
}

.ending-message {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: white;
}

.return-button {
  padding: 0.8rem 1.5rem;
  background-color: transparent;
  color: white;
  border: 2px solid white;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 1rem;
  font-size: 1rem;
}

.loading-indicator,
.error-message,
.no-chapter {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 2rem;
  text-align: center;
  color: white;
}

.back-button {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: transparent;
  color: white;
  border: 1px solid white;
  border-radius: 4px;
  cursor: pointer;
}

@media (max-width: 768px) {
  .chapter-title {
    font-size: 1.5rem;
  }
  
  .chapter-text {
    font-size: 1rem;
    padding: 0 1rem;
  }
  
  .chapter-footer {
    flex-direction: row;
    padding: 0.5rem;
  }
}
</style>