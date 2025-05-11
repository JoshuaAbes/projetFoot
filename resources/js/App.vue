<script setup>
  import { ref, computed } from 'vue';  // Ajout de 'computed' ici
  import TheHeader from '@/components/TheHeader.vue';
  import PageExample from '@/pages/PageExample.vue';
  const n = ref(1);

  import { createPinia } from 'pinia';
  import { setDefaultHeaders, setDefaultBaseUrl } from '@/utils/fetchJson.js';
  import { RouterView, useRoute } from 'vue-router';
  // Supprimé l'import du footer
  // import TheFooter from '@/components/TheFooter.vue';

  // Configuration de l'API
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
  setDefaultHeaders({'X-CSRF-TOKEN': csrfToken});

  const apiBaseUrl = document.querySelector('meta[name="api-base-url"]')?.getAttribute('content') ?? '';
  setDefaultBaseUrl(apiBaseUrl);

  // Initialisation de Pinia
  const pinia = createPinia();

  // Détecter si on est dans un chapitre pour ajuster le style
  const route = useRoute();
  const isChapterView = computed(() => {
    return route.path.includes('/stories/') && route.path.includes('/chapters/');
  });
</script>

<template>
  <div class="app-container" :class="{ 'chapter-view': isChapterView }">
    <!-- Garder le header sur toutes les pages -->
    <TheHeader />
    
    <main class="main-content" :class="{ 'full-height': isChapterView }">
      <RouterView />
    </main>
    
    <!-- Supprimer le footer -->
    <!-- <TheFooter /> -->
  </div>
</template>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Style spécial pour les vues de chapitre */
.chapter-view .main-content {
  padding: 0;
  max-width: 100%;
}

.full-height {
  padding: 0;
  max-width: 100%;
  margin: 0;
}

.main-content {
  flex: 1;
  padding: 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
}

@media (max-width: 768px) {
  .main-content {
    padding: 1rem;
  }
  
  .full-height {
    padding: 0;
  }
}
</style>