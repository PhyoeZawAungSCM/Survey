<script setup>
import PrimaryButton from './Buttons/PrimaryButton.vue';
import DeleteButton from './Buttons/DeleteButton.vue';
import router from '../router';
const props = defineProps({
  survey:Object
})
const emit = defineEmits(['deleteSurvey','viewLink']);
</script>
<template>
  <div class="max-w-sm w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full h-64 items-center flex">
      <img class="rounded-t-lg block mx-auto" :src="`http://127.0.0.1:8000/${survey.image}`"/>
    </div>
    <div class="p-5">
    <div class="w-full h-8 overflow-hidden mt-2">
      <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-ellipsis">{{ survey.title }}</h5>
    </div>
    <div class="w-full h-8 overflow-hidden my-2">
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ survey.description }}</p>
    </div>
      <div class="flex justify-between">
        <div>
          <PrimaryButton @click="router.push(`/survey/edit/${survey.id}`)">Edit</PrimaryButton>
        </div>
        <div>
          <PrimaryButton class="mr-2" @click="emit('viewLink',survey.slug)">View Survey</PrimaryButton>
          <DeleteButton @click="emit('deleteSurvey',survey.id)">Delete</DeleteButton>
        </div>
      </div>
    </div>
  </div>
</template>