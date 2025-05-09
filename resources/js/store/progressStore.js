import { defineStore } from 'pinia';
import { fetchJson } from '../utils/fetchJson';

export const useProgressStore = defineStore('progress', {
  state: () => ({
    currentProgress: {},
    visitedChapters: {},  // Structure: { storyId: [chapterId1, chapterId2, ...] }
    loading: false,
    error: null
  }),
  
  getters: {
    isChapterVisited: (state) => (storyId, chapterId) => {
      if (!state.visitedChapters[storyId]) return false;
      return state.visitedChapters[storyId].includes(parseInt(chapterId));
    },
    
    progressPercentage: (state) => (storyId, totalChapters) => {
      if (!state.visitedChapters[storyId]) return 0;
      return Math.round((state.visitedChapters[storyId].length / totalChapters) * 100);
    }
  },
  
  actions: {
    // Sauvegarder la progression
    async saveProgress(storyId, chapterId) {
      this.loading = true;
      this.error = null;
      
      // Mettre à jour la liste des chapitres visités localement
      if (!this.visitedChapters[storyId]) {
        this.visitedChapters[storyId] = [];
      }
      
      if (!this.visitedChapters[storyId].includes(parseInt(chapterId))) {
        this.visitedChapters[storyId].push(parseInt(chapterId));
      }
      
      // Sauvegarder dans le localStorage
      localStorage.setItem('visitedChapters', JSON.stringify(this.visitedChapters));
      
      // Si l'utilisateur est authentifié, sauvegarder sur le serveur
      if (document.cookie.includes('laravel_session')) {
        try {
          const { request } = fetchJson({
            url: 'progress',
            method: 'POST',
            data: {
              story_id: storyId,
              current_chapter_id: chapterId,
              visited_chapters: this.visitedChapters[storyId]
            }
          });
          
          const response = await request;
          this.currentProgress = response.progress;
        } catch (error) {
          this.error = `Erreur lors de la sauvegarde de la progression: ${error.statusText}`;
          console.error(error);
        } finally {
          this.loading = false;
        }
      } else {
        this.loading = false;
      }
    },
    
    // Charger la progression sauvegardée
    async loadProgress(storyId) {
      // D'abord, charger depuis localStorage
      const savedData = localStorage.getItem('visitedChapters');
      if (savedData) {
        this.visitedChapters = JSON.parse(savedData);
      }
      
      // Si l'utilisateur est authentifié, récupérer la progression du serveur
      if (document.cookie.includes('laravel_session')) {
        this.loading = true;
        this.error = null;
        
        try {
          const { request } = fetchJson(`progress/${storyId}`);
          const response = await request;
          
          if (response && response.progress) {
            this.currentProgress = response.progress;
            
            // Mettre à jour la liste des chapitres visités
            if (response.progress.visited_chapters) {
              this.visitedChapters[storyId] = response.progress.visited_chapters;
              localStorage.setItem('visitedChapters', JSON.stringify(this.visitedChapters));
            }
          }
          
          return response;
        } catch (error) {
          // Si l'utilisateur n'a pas encore de progression, ce n'est pas une erreur
          if (error.status !== 404) {
            this.error = `Erreur lors du chargement de la progression: ${error.statusText}`;
            console.error(error);
          }
          return null;
        } finally {
          this.loading = false;
        }
      }
    }
  }
});