<script setup>
const props = defineProps({
  buttonType:String,
  disabled:Boolean,
});
const emit = defineEmits(['click']);
</script>
<template>
  <button :disabled="disabled" @click="emit('click')" :type=buttonType class="bg-gray-900 rounded-lg text-gray-300 px-4 py-1.5 shadow-md ">
    <slot></slot>
  </button>
</template>