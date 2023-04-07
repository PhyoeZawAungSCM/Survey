<script setup>
import { RouterView } from 'vue-router';
</script>
<template>
  <notifications position="top right" class="mr-4 mt-10" width="25%"/>
  <div class="bg-gray-100 w-full md:mx-auto h-full">
    <RouterView></RouterView>
  </div>
</template>

<style scoped>

</style>