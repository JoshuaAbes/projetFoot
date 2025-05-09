<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  story: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <router-link :to="`/stories/${story.id}`" class="story-card">
    <div class="story-image-container">
      <img v-if="story.cover_image" :src="story.cover_image" :alt="story.title" class="story-image">
      <div v-else class="story-image-placeholder">{{ story.title.charAt(0) }}</div>
    </div>
    
    <div class="story-content">
      <h2 class="story-title">{{ story.title }}</h2>
      <p class="story-author" v-if="story.author">Par {{ story.author }}</p>
      <p class="story-description">{{ story.summary || 'Aucune description disponible' }}</p>
    </div>
    
    <div class="story-action">
      <button class="read-button">Commencer la lecture</button>
    </div>
  </router-link>
</template>

<style scoped>
.story-card {
  display: flex;
  flex-direction: column;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  text-decoration: none;
  color: inherit;
}

.story-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.story-image-container {
  width: 100%;
  height: 200px;
  overflow: hidden;
}

.story-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.story-image-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f2f2f2;
  font-size: 3rem;
  color: #ccc;
}

.story-content {
  padding: 1rem;
  flex-grow: 1;
}

.story-title {
  margin: 0;
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.story-author {
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 0.5rem;
}

.story-description {
  margin: 0;
  font-size: 0.9rem;
  color: #333;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
}

.story-action {
  padding: 1rem;
  border-top: 1px solid #eee;
}

.read-button {
  width: 100%;
  padding: 0.75rem;
  background: #333;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s ease;
}

.read-button:hover {
  background: #555;
}
</style>