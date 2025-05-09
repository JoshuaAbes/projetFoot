import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('../views/HomeView.vue'),
    meta: { title: 'Accueil - Fiction Interactive' }
  },
  {
    path: '/stories/:id',
    name: 'story',
    component: () => import('../views/StoryView.vue'),
    meta: { title: 'Histoire - Fiction Interactive' },
    props: true
  },
  {
    path: '/stories/:storyId/chapters/:chapterId',
    name: 'chapter',
    component: () => import('../views/ChapterView.vue'),
    meta: { title: 'Lecture - Fiction Interactive' },
    props: true
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Mettre à jour le titre de la page
router.beforeEach((to, from, next) => {
  document.title = to.meta.title || 'Fiction Interactive';
  next();
});

export default router;