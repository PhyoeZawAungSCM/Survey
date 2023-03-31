<script setup>
  import { onMounted , reactive,ref } from 'vue';
  import { httpAuth } from '../services/HTTPService';
  
    const props = defineProps({
      activeId:Number
    });
    const surveys = reactive({data:{}});
    const emit = defineEmits();
   
    onMounted(()=>{
       httpAuth().get('survey/question-answer')
       .then(response => {
          console.log(response);
          surveys.data = response.data;
      })
    })
    function onEnter(el,done){
      console.log(el.dataset.index);
      let duration = el.dataset.index * 150;
      console.log(duration);
      el.classList.add('transition-all',`duration-${duration}`,'ease-in-out')
    }
</script>
<template>
<div class="bg-white border-r-2   w-full h-fit mt-4 rounded sticky top-0">
  <h3 class="text-2xl font-semibold text-gray-900 pl-8 py-2">Survey List</h3>
  <TransitionGroup @enter="onEnter">
  <div v-for="(survey,index) in surveys.data.surveys" 
    :key="index"
    :data-index = "index"
    class="w-full pl-8 pr-4 hover:bg-gray-200 hover:transition duration-150 border-b-2 items-center"
   :class="survey.id == activeId ? `shadow-sm transition bg-gray-300 duration-300 ease-in-out` : ''" 
  >
    <div @click="emit('getAnswerList',{survey_id:survey.id, answer:survey.answers,title:survey.title,description:survey.description})" class="flex justify-between py-2 cursor-pointer">
      <h4 class="text-gray-900 font-medium text-base">{{ survey.title }}</h4>
      <span class="text-gray-700 font-normal text-sm">{{ survey.answers.length }}</span>
    </div>
  </div>
</TransitionGroup>
</div>

</template>
<style>

</style>
