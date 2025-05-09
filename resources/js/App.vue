<script setup>
  import { ref } from 'vue';
  import TheHeader from '@/components/TheHeader.vue';
  import PageExample from '@/pages/PageExample.vue';
  const n = ref(1);

  import { createPinia } from 'pinia';
  import { setDefaultHeaders, setDefaultBaseUrl } from '@/utils/fetchJson.js';
  import { RouterView } from 'vue-router';
  import TheFooter from '@/components/TheFooter.vue';

  // Configuration de l'API
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
  setDefaultHeaders({'X-CSRF-TOKEN': csrfToken});

  const apiBaseUrl = document.querySelector('meta[name="api-base-url"]')?.getAttribute('content') ?? '';
  setDefaultBaseUrl(apiBaseUrl);

  // Initialisation de Pinia
  const pinia = createPinia();
</script>

<template>
  <div class="app-container">
    <TheHeader />
    
    <main class="main-content">
      <RouterView />
    </main>
    
    <TheFooter />
  </div>
</template>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
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
}
</style>