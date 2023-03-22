<script setup>
const porps = defineProps({
  title:String
})
</script>
<template>
    <div class="bg-white w-full shadow-md py-2">
    <div class="h-16 items-center w-11/12 mx-auto flex justify-between">
      <h3 class="text-4xl text-gray-900 font-bold ml-4">{{ title }}</h3>  
      <slot></slot>
    </div>
  </div>
</template>