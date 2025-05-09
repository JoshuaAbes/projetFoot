<script setup>
import { onMounted } from 'vue';
import { useStoryStore } from '../store/storyStore';
import StoryCard from '../components/StoryCard.vue';

const storyStore = useStoryStore();

onMounted(async () => {
  await storyStore.fetchStories();
});
</script>

<template>
  <div class="home-view">
    <h1 class="page-title">Découvrez nos histoires interactives</h1>
    
    <div v-if="storyStore.loading" class="loading-indicator">
      <p>Chargement des histoires...</p>
    </div>
    
    <div v-else-if="storyStore.error" class="error-message">
      <p>{{ storyStore.error }}</p>
    </div>
    
    <div v-else-if="storyStore.stories.length === 0" class="empty-state">
      <p>Aucune histoire disponible pour le moment.</p>
    </div>
    
    <div v-else class="story-grid">
      <StoryCard 
        v-for="story in storyStore.publishedStories" 
        :key="story.id" 
        :story="story" 
      />
    </div>
  </div>
</template>

<style scoped>
.home-view {
  padding: 1rem 0;
}

.page-title {
  font-size: 2rem;
  margin-bottom: 2rem;
  text-align: center;
}

.story-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
}

.loading-indicator,
.error-message,
.empty-state {
  text-align: center;
  padding: 2rem;
  background: #f5f5f5;
  border-radius: 8px;
  margin: 2rem 0;
}

.error-message {
  color: #e53935;
}

@media (max-width: 768px) {
  .story-grid {
    grid-template-columns: 1fr;
  }
}
</style>