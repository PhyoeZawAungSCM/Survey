<script setup>
const props = defineProps({
  buttonType:String
});
const emit = defineEmits(['click']);
</script>
<template>
  <button @click="emit('click')" :type=buttonType class="bg-gray-900 rounded-lg text-gray-300 px-4 py-2 shadow-md mr-3 mt-3">
    <slot></slot>
  </button>
</template>