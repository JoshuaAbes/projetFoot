<script setup>
import { ref } from 'vue';

const isMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};
</script>

<template>
  <header class="header">
    <div class="container">
      <div class="logo">
        <router-link to="/">
          Fiction Interactive
        </router-link>
      </div>
      
      <button class="menu-toggle" @click="toggleMenu" aria-label="Menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
      

      <nav class="main-nav" :class="{ 'is-open': isMenuOpen }">

      </nav>
    </div>
  </header>
</template>

<style scoped>
.header {
  background-color: #222;
  color: white;
  padding: 1rem 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo a {
  color: white;
  font-size: 1.5rem;
  font-weight: bold;
  text-decoration: none;
}

.main-nav ul {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
}

.main-nav li {
  margin-left: 1.5rem;
}

.main-nav a {
  color: white;
  text-decoration: none;
}

.main-nav a:hover {
  text-decoration: underline;
}

.menu-toggle {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
}

.menu-toggle span {
  display: block;
  width: 25px;
  height: 3px;
  margin: 5px 0;
  background: white;
}

@media (max-width: 768px) {
  .menu-toggle {
    display: block;
  }
  
  .main-nav {
    position: fixed;
    top: 0;
    right: -250px;
    width: 250px;
    height: 100vh;
    background: #333;
    padding: 2rem;
    transition: right 0.3s ease;
    z-index: 10;
  }
  
  .main-nav.is-open {
    right: 0;
  }
  
  .main-nav ul {
    flex-direction: column;
  }
  
  .main-nav li {
    margin: 1rem 0;
  }
}
</style>