<script setup>
import { onMounted, reactive, ref , watch } from 'vue';
import { useRouter } from 'vue-router';
import HeaderBar from '../components/HeaderBar/HeaderBar.vue';
import { httpAuth } from '../services/HTTPService';
import SurveyCard from '../components/SurveyCard.vue';
import Loading from '../components/Loading/Loading.vue';
import { useNavigationStore } from '../store/NavigationStore';
import { useSurveyStore } from '../store/SurveyStore';

const router = useRouter();
const store = useNavigationStore();
const surveyStore = useSurveyStore();
const data = reactive({ data: {} })
const loading = ref(false)
const load_card = ref(false);
const loadSurveyCount = ref(false);
const loadAnswerCount = ref(false);
const loadLatestSurvey = ref(false);

function goToAnswer(answer){
  router.push(`/answer/${answer.id}`);
  store.isActive = 'Answer';
}
function deleteSurvey(id){
  surveyStore.deleteSurvey(id);
  data.data.latest_survey = {...surveyStore.latestSurvey};
}
function viewLink(slug){
  router.push('/survey/'+slug);
}
/**
 * Fetching the survey data
 */
onMounted(() => {
  loading.value = true;
  httpAuth().get('/survey/dashboard-data')
    .then(response => {
      data.data = response.data;
      console.log(response);
      loading.value = false
      setTimeout(()=>{
        load_card.value = true;
      },100)
    });
})
/**
 * for animating the card
 */
watch(load_card,()=>{
  setTimeout(()=> {
    loadSurveyCount.value = true;
  },100)
})
watch(loadSurveyCount , () => {
  setTimeout(()=>{
    loadAnswerCount.value = true;
  },100)
})
watch(loadAnswerCount , () => {
  setTimeout(()=>{
    loadLatestSurvey.value = true;
  },100)
})
</script>
<template>
  <div v-if="loading" class="w-fit mx-auto mt-20">
    <Loading :size=12 />
  </div>
  <div v-else>
    <HeaderBar title="Dashboard"></HeaderBar>
    <div class="grid grid-cols-3 gap-4 mx-auto w-11/12 mt-4">
      <Transition>
      <div v-if="load_card">
      <SurveyCard v-if="data.data.latest_survey" 
        @delete-survey="deleteSurvey"
        @viewLink="viewLink"
        :survey="data.data.latest_survey" />
      <div v-else class="w-full bg-white shadow-md rounded-lg flex items-center justify-center">
        <span>You do not have any survey yet . </span>
        <button class="text-blue-500" @click="router.push('survey/create')">create one</button>
      </div>
     </div>
     </Transition>
     
      <div class="w-full h-full grid grid-rows-2 gap-4">
        <Transition>
        <div v-if="loadSurveyCount" class=" bg-white shadow-md  justify-center rounded-lg">
          <p class="font-bold pt-4 text-gray-900 text-xl w-fit mx-auto">Total surveys</p>
          <p class="font-bold text-gray-900 text-9xl pb-4 w-fit mx-auto">{{ data.data.survey_count }}</p>
        </div>
      </Transition>
      <Transition>
        <div v-if="loadAnswerCount" class=" bg-white shadow-md  justify-center rounded-lg">
          <p class="font-bold pt-4 text-gray-900 text-xl w-fit mx-auto">Total answers</p>
          <p class="font-bold text-gray-900 text-9xl pb-4 w-fit mx-auto">{{ data.data.answer_count }}</p>
        </div>
      </Transition>
      </div>
      <Transition>
      <div v-if="loadLatestSurvey" class=" w-full bg-white shadow-md rounded-lg px-6 ">
        <h1 class="font-bold text-2xl text-gray-900 pt-4 mb-4">Latest Surveys</h1>
        <div v-for="answer in data.data.latest_answers" 
        @click="goToAnswer(answer)"
        class="pl-2 cursor-pointer pb-2 border-b-[1px] hover:bg-gray-200">
          <h1 class="font-medium text-xl text-gray-900">{{ answer.title }}</h1>
          <p class="text-gray-500 text-sm ">{{ answer.created_at }}</p>
          
        </div>
       
      </div>
    </Transition>
    </div>
  </div>
</template>
<style scoped>
  .v-enter-from,
  .v-leave-to {
    opacity: 0;
    transform: translateY(20px);
  }
  .v-enter-active,
  .v-leave-active {
    transition: all 0.5s ease-in-out;
  }
</style>