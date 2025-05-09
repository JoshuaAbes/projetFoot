<template>
  <footer class="footer">
    <div class="container">
      <p>&copy; {{ currentYear }} Fiction Interactive</p>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue';

const currentYear = computed(() => new Date().getFullYear());
</script>

<style scoped>
.footer {
  background-color: #222;
  color: white;
  padding: 1.5rem 0;
  margin-top: auto;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
  text-align: center;
}
</style>