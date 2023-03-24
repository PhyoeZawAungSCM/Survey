<script setup>
  import { onMounted ,reactive,ref} from 'vue';
  import HeaderBar from '../components/HeaderBar/HeaderBar.vue';
  import { httpAuth } from '../services/HTTPService';
  import SurveyCard from '../components/SurveyCard.vue';
  import Loading from '../components/Loading/Loading.vue';
  const data = reactive({data:{}})
  const loading = ref(false)
  onMounted(()=>{
    loading.value = true;
    httpAuth().get('/survey/dashboard-data')
    .then(response => {
      data.data = response.data;
      console.log(response);
      loading.value = false
    });
  })
</script>
<template>
  <div v-if="loading" class="w-fit mx-auto mt-20">
    <Loading :size=12 />
  </div>
  <div v-else>
  <HeaderBar title="Dashboard"></HeaderBar>
  <div class="grid grid-cols-3 gap-4 mx-auto w-11/12 mt-4">
      <SurveyCard v-if="data.data.latest_survey" :survey="data.data.latest_survey"/>

    <div class="w-full">
      <div class=" h-1/2 bg-white shadow-md mb-3 justify-center rounded-lg">
        <p class="font-bold pt-4 text-gray-900 text-xl w-fit mx-auto">Total surveys</p>
        <p class="font-bold text-gray-900 text-9xl pb-4 w-fit mx-auto">{{ data.data.survey_count }}</p>
      </div>
      <div class=" h-1/2 bg-white shadow-md  justify-center rounded-lg">
        <p class="font-bold pt-4 text-gray-900 text-xl w-fit mx-auto">Total answers</p>
        <p class="font-bold text-gray-900 text-9xl pb-4 w-fit mx-auto">{{ data.data.answer_count }}</p>
      </div>
    </div>
    <div class=" w-full bg-white shadow-md rounded-lg pl-6 ">
      <h1  class="font-bold text-2xl text-gray-900 pt-4">Latest Surveys</h1>
      <div v-for="answer in data.data.latest_answers" class="my-3 pl-2 cursor-pointer">
        <h1 class="font-medium text-xl text-gray-900">{{ answer.title }}</h1>
        <p class="text-gray-500 text-sm ">{{ answer.created_at }}</p>
      </div>
    </div>
  </div>
</div>
</template>