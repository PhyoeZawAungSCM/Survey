<script setup>
  const emit = defineEmits(['click']);
</script>
<template>
  <button 
  class="bg-red-500 text-gray-100 px-4 py-1.5 rounded font-"
  @click="emit('click')">
    <slot></slot>
  </button>
</template>