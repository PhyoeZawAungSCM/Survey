<script setup>
  import Navigation from '../components/Navigation.vue';
  import { RouterView } from 'vue-router';
  import { useUserStore } from '../store/AuthStore';
  const store = useUserStore();

  store.getUser();
</script>
<template>
  <div>
    <Navigation/>
    <RouterView></RouterView>
  </div>
</template>
