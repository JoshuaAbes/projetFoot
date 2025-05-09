<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  percentage: {
    type: Number,
    default: 0
  }
});
</script>

<template>
  <div class="progress-container">
    <div class="progress-label">Progression: {{ percentage }}%</div>
    <div class="progress-bar">
      <div class="progress-fill" :style="{ width: `${percentage}%` }"></div>
    </div>
  </div>
</template>

<style scoped>
.progress-container {
  margin: 1rem 0;
}

.progress-label {
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
  color: #666;
}

.progress-bar {
  height: 10px;
  width: 100%;
  background: #eee;
  border-radius: 5px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: #4caf50;
  transition: width 0.3s ease;
}
</style>