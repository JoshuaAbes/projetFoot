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

const loadChapter = async (id) => {
  loading.value = true;
  error.value = null;
  
  try {
    const data = await storyStore.fetchChapter(id);
    if (data) {
      chapter.value = data;
      
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

const selectChoice = (choiceId, nextChapterId) => {
  // Animation de transition
  fadeOutAnimation.value = true;
  
  // Sauvegarder le prochain chapitre à charger
  nextChapterId.value = nextChapterId;
  
  // Attendre la fin de l'animation avant de naviguer
  setTimeout(() => {
    router.push(`/stories/${storyId}/chapters/${nextChapterId}`);
  }, 500);
};

const goToStory = () => {
  router.push(`/stories/${storyId}`);
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
  <div class="chapter-view">
    <div v-if="loading" class="loading-indicator">
      <p>Chargement du chapitre...</p>
    </div>
    
    <div v-else-if="error" class="error-message">
      <p>{{ error }}</p>
      <button @click="goToStory" class="back-button">Retour à l'histoire</button>
    </div>
    
    <div v-else-if="chapter" 
      :class="['chapter-content', { 'fade-out': fadeOutAnimation }]">
      
      <h1 class="chapter-title" v-if="chapter.title">{{ chapter.title }}</h1>
      
      <div class="chapter-text">
        <!-- Utiliser v-html avec précaution, cela suppose que le contenu du serveur est sécurisé -->
        <div v-html="chapter.content"></div>
      </div>
      
      <div v-if="chapter.image" class="chapter-image">
        <img :src="chapter.image" alt="Illustration du chapitre">
      </div>
      
      <div v-if="chapter.is_ending" class="chapter-ending">
        <p class="ending-message">Fin de l'histoire</p>
        <button @click="goToStory" class="return-button">Retour à l'histoire</button>
      </div>
      
      <div v-else-if="chapter.choices && chapter.choices.length > 0" 
        class="chapter-choices">
        <h2 class="choices-title">Que voulez-vous faire ?</h2>
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
        <p>Aucun choix disponible.</p>
        <button @click="goToStory" class="return-button">Retour à l'histoire</button>
      </div>
    </div>
    
    <div v-else class="no-chapter">
      <p>Chapitre introuvable.</p>
      <button @click="goToStory" class="back-button">Retour à l'histoire</button>
    </div>
  </div>
</template>

<style scoped>
.chapter-view {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem 0;
}

.chapter-content {
  opacity: 1;
  transition: opacity 0.5s ease;
}

.fade-out {
  opacity: 0;
}

.chapter-title {
  font-size: 2rem;
  margin-bottom: 1.5rem;
  text-align: center;
}

.chapter-text {
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 2rem;
}

.chapter-image {
  margin: 2rem 0;
  text-align: center;
}

.chapter-image img {
  max-width: 100%;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.chapter-choices {
  margin-top: 3rem;
}

.choices-title {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  text-align: center;
}

.choices-list {
  list-style: none;
  padding: 0;
}

.choice-item {
  margin-bottom: 1rem;
}

.choice-button {
  width: 100%;
  padding: 1rem;
  background: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 4px;
  text-align: left;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
}

.choice-button:hover {
  background: #eee;
  transform: translateY(-2px);
}

.choice-button.visited {
  background: #f0f7ff;
  border-color: #b3d4ff;
}

.visited-indicator {
  font-size: 0.8rem;
  color: #666;
  margin-left: 0.5rem;
}

.chapter-ending {
  margin-top: 3rem;
  text-align: center;
}

.ending-message {
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: #333;
}

.return-button,
.back-button {
  padding: 0.75rem 1.5rem;
  background: #333;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.return-button:hover,
.back-button:hover {
  background: #555;
}

.loading-indicator,
.error-message,
.no-chapter {
  text-align: center;
  padding: 2rem;
  background: #f5f5f5;
  border-radius: 8px;
}

.error-message {
  color: #e53935;
}

.no-choices {
  margin-top: 3rem;
  text-align: center;
}

@media (max-width: 768px) {
  .chapter-view {
    padding: 1rem 0;
  }
  
  .chapter-title {
    font-size: 1.5rem;
  }
  
  .chapter-text {
    font-size: 1rem;
  }
  
  .choices-title {
    font-size: 1.2rem;
  }
}
</style>