import { defineStore } from 'pinia';
import { fetchJson } from '../utils/fetchJson';

export const useStoryStore = defineStore('story', {
  state: () => ({
    stories: [],
    currentStory: null,
    loading: false,
    error: null
  }),
  
  getters: {
    publishedStories: (state) => state.stories.filter(s => s.is_published),
    getStoryById: (state) => (id) => state.stories.find(s => s.id === parseInt(id))
  },
  
  actions: {
    // Récupérer toutes les histoires
    async fetchStories() {
      this.loading = true;
      this.error = null;
      
      try {
        const { request } = fetchJson('stories');
        const data = await request;
        this.stories = data;
      } catch (error) {
        this.error = `Erreur lors du chargement des histoires: ${error.statusText}`;
        console.error(error);
      } finally {
        this.loading = false;
      }
    },
    
    // Récupérer une histoire par son ID
    async fetchStory(id) {
      this.loading = true;
      this.error = null;
      
      try {
        const { request } = fetchJson(`stories/${id}`);
        const data = await request;
        this.currentStory = data;
        return data;
      } catch (error) {
        this.error = `Erreur lors du chargement de l'histoire: ${error.statusText}`;
        console.error(error);
        return null;
      } finally {
        this.loading = false;
      }
    },
    
    // Récupérer un chapitre spécifique
    async fetchChapter(id) {
      try {
        const { request } = fetchJson(`chapters/${id}`);
        return await request;
      } catch (error) {
        this.error = `Erreur lors du chargement du chapitre: ${error.statusText}`;
        console.error(error);
        return null;
      }
    },
    
    // Récupérer le premier chapitre d'une histoire
    async fetchFirstChapter(storyId) {
      try {
        const { request } = fetchJson(`stories/${storyId}/first-chapter`);
        return await request;
      } catch (error) {
        this.error = `Erreur lors du chargement du premier chapitre: ${error.statusText}`;
        console.error(error);
        return null;
      }
    }
  }
});