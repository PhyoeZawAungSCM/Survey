<script setup>
import { onMounted, reactive, computed, ref } from 'vue';
import { httpAuth } from '../services/HTTPService';
import Loading from './Loading/Loading.vue';
const props = defineProps({
  activeId: Number,
  doProcess: Function,
  canProcess:{
    type:Boolean,
    default:false,
  },
});
const surveys = reactive({ data: {} });
const emit = defineEmits();
const loading = ref(true);
onMounted(async () => {
  loading.value = true;
  await httpAuth().get('survey/question-answer')
    .then(response => {
      surveys.data = response.data;
      loading.value = false;
      if(props.canProcess){
        props.doProcess(response.data);
      } 
    });
})

function onEnter(el, done) {
  let duration = el.dataset.index * 150;
  el.classList.add('transition-all', `duration-${duration}`, 'ease-in-out')
}
const hasNotSurvey = computed(() => {
  let keys = Object.keys(surveys.data);
  return keys.length == 0 ? true : false;
})
</script>
<template>
  <div v-if="loading" class="w-fit mx-auto mt-20">
    <Loading :size=12 />
  </div>
  <div v-else-if="hasNotSurvey" class="mx-auto w-64 mt-10">
    <span>You do not have any survey yet</span>
  </div>
  <div v-else class="bg-white border-r-2   w-full h-fit mt-4 rounded sticky top-0">
    <h3 class="text-2xl font-semibold text-gray-900 pl-8 py-2">Survey List</h3>
    <TransitionGroup @enter="onEnter">
      <div v-for="(survey, index) in surveys.data.surveys" :key="index" :data-index="index"
        class="w-full pl-8 pr-4 hover:bg-gray-200 hover:transition duration-150 border-b-2 items-center"
        :class="survey.id == activeId ? `shadow-sm transition bg-gray-300 duration-300 ease-in-out` : ''">
        <div
          @click="emit('getAnswerList', { survey_id: survey.id, answer: survey.answers, title: survey.title, description: survey.description })"
          class="flex justify-between py-2 cursor-pointer">
          <h4 class="text-gray-900 font-medium text-base">{{ survey.title }}</h4>
          <span class="text-gray-700 font-normal text-sm">{{ survey.answers.length }}</span>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>
<style></style>
