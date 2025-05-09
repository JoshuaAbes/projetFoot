import { createRouter, createWebHistory } from 'vue-router';
import StoryView from '../views/StoryView.vue';
import ChapterView from '../views/ChapterView.vue';

const routes = [
  {
    path: '/',
    redirect: '/stories/1' 
  },
  {
    path: '/stories/:id',
    name: 'story',
    component: StoryView,
    props: true
  },
  {
    path: '/stories/:storyId/chapters/:chapterId',
    name: 'chapter',
    component: ChapterView,
    props: true
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;