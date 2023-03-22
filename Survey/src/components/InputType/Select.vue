<script setup>
const props = defineProps({
  data: Array
});
const emit = defineEmits(['addOption', 'removeOption']);
</script>
<template>
  <h3 class="text-gray-900 font-bold text-lg">Options</h3>
  <div class="col-span-6 sm:col-span-3 w-full">
    <TransitionGroup name="options">
      <div class="flex gap-4 items-center m-2" v-for="(option, index) in props.data" :key="index">
        <div class="flex items-center gap-4 w-full">
          <label for="company-website" class="block leading-6 text-gray-900">{{ index + 1 }}</label>
          <input type="text" name="first-name" id="first-name" autocomplete="given-name" v-model="props.data[index]"
            class=" block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
        <div class="items-center">
          <button class="bg-red-500 text-gray-200 px-4 py-1.5 rounded"
            @click="emit('removeOption', index)">remove</button>
        </div>
      </div>
    </TransitionGroup>
    <div class="ml-8">
      <button class="bg-gray-900 text-gray-200 px-4 py-2 rounded-md my-2" @click="emit('addOption')">Add Option</button>
    </div>

  </div>
</template>
<style scoped>
.options-enter-from,
.options-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

.options-enter-active,
.options-leave-active {
  transition: all 0.3s ease-in-out;
}
</style>