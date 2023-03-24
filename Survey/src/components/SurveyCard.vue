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
  <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <a href="#">
      <img class="rounded-t-lg" src="https://flowbite.com/docs/images/blog/image-1.jpg" alt="" />
    </a>
    <div class="p-5">
      <a href="#">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ survey.title }}</h5>
      </a>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ survey.description }}</p>
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