<script setup>
  import { ref } from 'vue';
  import TheHeader from '@/components/TheHeader.vue';
  import PageExample from '@/pages/PageExample.vue';
  const n = ref(1);
</script>

<template>
  <div>
    <TheHeader />
    <main>
      <PageExample />
    </main>
  </div>
</template>

<style scoped>

</style>