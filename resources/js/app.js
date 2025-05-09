import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import { setDefaultHeaders, setDefaultBaseUrl } from '@/utils/fetchJson.js';
import App from './App.vue';

// Configuration de l'API
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
setDefaultHeaders({'X-CSRF-TOKEN': csrfToken});

const apiBaseUrl = document.querySelector('meta[name="api-base-url"]')?.getAttribute('content') ?? '';
setDefaultBaseUrl(apiBaseUrl);

// Créer l'application Vue
const app = createApp(App);

// Utiliser Pinia et Vue Router
app.use(createPinia());
app.use(router);

// Monter l'application
app.mount('#app');