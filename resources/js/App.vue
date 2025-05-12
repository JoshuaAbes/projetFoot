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

<style>
html {
  box-sizing: border-box;
}

*, *:before, *:after {
  box-sizing: inherit;
}

/* Classes utilitaires pour les différents styles */
body, html {
  font-family: "Libre Baskerville", serif;
  background-color: #38003D;
  color: #03F1FE;
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  -webkit-text-size-adjust: 100%;
  -webkit-font-smoothing: antialiased;
}

*, *:before, *:after {
  box-sizing: inherit;
}

/* Classes utilitaires pour les différents styles */
.libre-regular {
  font-family: "Libre Baskerville", serif;
  font-weight: 400;
  font-style: normal;
}

.libre-bold {
  font-family: "Libre Baskerville", serif;
  font-weight: 700;
  font-style: normal;
}

.libre-italic {
  font-family: "Libre Baskerville", serif;
  font-weight: 400;
  font-style: italic;
}

.mona-regular {
  font-family: "Mona Sans", sans-serif;
  font-weight: 400;
  font-style: normal;
  font-optical-sizing: auto;
  font-variation-settings: "wdth" 100;
}

.mona-semibold {
  font-family: "Mona Sans", sans-serif;
  font-weight: 600;
  font-style: normal;
  font-optical-sizing: auto;
  font-variation-settings: "wdth" 100;
}

.mona-bold {
  font-family: "Mona Sans", sans-serif;
  font-weight: 700;
  font-style: normal;
  font-optical-sizing: auto;
  font-variation-settings: "wdth" 100;
}

.mona-italic {
  font-family: "Mona Sans", sans-serif;
  font-weight: 400;
  font-style: italic;
  font-optical-sizing: auto;
  font-variation-settings: "wdth" 100;
}
</style>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: #38003D; /* Fond violet foncé */
  color: #03F1FE; /* Texte cyan */
  font-family: "Mona Sans", sans-serif;
  width: 100%;
}

/* Style spécial pour les vues de chapitre */
.chapter-view .main-content {
  padding: 0;
  max-width: 100%;
  background-color: #38003D; /* Même couleur de fond pour les chapitres */
  width: 100%;
}

.full-height {
  padding: 0;
  max-width: 100%;
  margin: 0;
  width: 100%;
}

.main-content {
  flex: 1;
  padding: 0.5rem;
  max-width: 100%;
  margin: 0 auto;
  width: 100%;
  color: #03F1FE; /* S'assurer que le texte est cyan partout */
}

@media (min-width: 768px) {
  .main-content {
    padding: 1rem;
    max-width: 90%;
  }
}

@media (min-width: 1024px) {
  .main-content {
    padding: 1.5rem;
    max-width: 1200px;
  }
}
</style>