<script setup>
import { ref, reactive ,onMounted , watch,computed } from "vue";
import { v4 } from 'uuid';
import { useSurveyStore } from "../store/SurveyStore";
import { useRoute,useRouter } from "vue-router";
import { httpAuth } from '../services/HTTPService'
import Question from './Question.vue';
import PrimaryButton from "./Buttons/PrimaryButton.vue";
import HeaderBar from "./HeaderBar/HeaderBar.vue";
import Loading from "./Loading/Loading.vue";

const route = useRoute();
const router = useRouter();
const store = useSurveyStore();
const loading = ref(false);
const survey = reactive({
  data: {
    title: '',
    description: '',
    status: true,
    expire_date: '',
    questions: []
  }
})
/**
 * Adding a question to survey
 */
function addQuestion() {
  const question = reactive({
    id: v4(),
    type: "text",
    title: '',
    description: '',
    survey_id:route.params.id,
    data: [],
  })
  survey.data.questions.push(question);
}

/**
 * delete a question
 * @param {Integer} id 
 */
function deleteQuestion(id) {
  survey.data.questions = survey.data.questions.filter(question => question.id != id);
}

/**
 * submitting a question form
 */
function updateSurvey() { 
  httpAuth().put('/survey/survey/' + survey.data.id , survey.data)
  .then(response => {
    console.log(response)
    let surveyIndex = store.surveys.data.findIndex(survey => survey.id == response.data.survey.id);
    store.surveys.data[surveyIndex]= response.data.survey;
    router.push('/survey');
  })
  .catch(error => {
    console.log(error)
  })
}

/**
 * fetching a survey by id
 */
onMounted(()=>{
  loading.value = true;
  httpAuth().get('/survey/survey/' + route.params.id)
  .then(response => {
    survey.data = response.data.data
    loading.value=false;
  })
  .catch(error => {
    loading.value = false;
    alert(error);
  })
})
</script>
<template>
  <div v-if="loading" class="w-fit mx-auto mt-16">
    <Loading :size=12 />
  </div>
  <div v-else>
    <HeaderBar :title="survey.data.title"></HeaderBar>
    <div class="bg-red mx-auto w-full">
    <form class="bg-white my-4 w-3/4 rounded-lg mx-auto shadow-md p-8" @submit.prevent="updateSurvey">
      <!-- Title -->
      <div class="col-span-3 sm:col-span-2 mb-4">
        <label for="company-website" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
        <div class="mt-2 flex rounded-md shadow-sm">
          <input type="text" name="company-website" id="company-website"
            class="block w-full flex-1 rounded rounded-r-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            v-model="survey.data.title" />
        </div>
      </div>
      <!--title-->
      <!-- Description -->
      <div class="mb-4">
        <label for="about" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
        <div class="mt-2">
          <textarea id="about" rows="3"
            class="mt-1 block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6"
            v-model="survey.data.description">{{ survey.data.description }}</textarea>
        </div>
      </div>
      <!-- Description -->
      <div class="mb-4">
        <label class="relative inline-flex items-center cursor-pointer">
          <span class=" font-medium text-gray-900 dark:text-gray-300 mr-4">Status</span>
          <input type="checkbox" v-model="survey.data.status" class="sr-only peer">
          <div
            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
          </div>

        </label>
      </div>
      <div>
        <label for="expire_date" class="block text-sm font-medium leading-6 text-gray-900 mb-2">Expire date</label>
        <input type="date" id="expire_date"
          class="rounded px-6  text-gray-900 font-medium border-gray-300 shadow-sm focus:ring-blue-800"
          v-model="survey.data.expire_date" />
      </div>


      <!--Questions-->
      <div class="mt-4">
        <div class="flex justify-between items-center mb-2">
          <h3 class="block text-3xl font-medium leading-6 text-gray-900">Questions</h3>
        </div>
        <div class="text-gray-900 font-medium items-center"><span class="text-gray-900 font-normal text-sm">you do not
            have any question created</span>
        </div>
        <Question v-for="(question, index) in survey.data.questions" :key="question" :question="question" :index="index"
          @deleteQuestion="deleteQuestion" />
      </div>
      <!--Questions-->
      <div class="flex justify-end mt-4 gap-5">
      <PrimaryButton @click="addQuestion" buttonType="button">Create New question</PrimaryButton>
      <PrimaryButton buttonType="submit" :disabled="store.isCreating"><Loading v-if="store.isCreating"/>{{store.isCreating ? "Updating" : "Update"}}</PrimaryButton> 
      </div>
    </form>
  </div>
  </div>
</template>
