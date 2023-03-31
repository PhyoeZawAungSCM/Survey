<script setup>
import { ref, reactive, onMounted, watch } from "vue";
import { useSurveyStore } from "../store/SurveyStore";
import { useRoute, useRouter } from "vue-router";
import { http } from '../services/HTTPService'
import PrimaryButton from "../components/Buttons/PrimaryButton.vue";
import Loading from "../components/Loading/Loading.vue";

const route = useRoute();
const router = useRouter();
const store = useSurveyStore();

// loading 
const loading = ref(false);

// to submit survey data
const survey = reactive({
  data: {
    title: '',
    description: '',
    status: true,
    expire_date: '',
    questions: []
  }
})

// store temporary answer data as id and key
const answerModel = reactive({data:{}});

// check for is submmitted data
const isSubmitted = ref(false);

const name = ref('');
/**
 * Submitting the answer 
 */
function submitAnswer(){
  const answers = [];
  const keys = Object.keys(answerModel.data);
  console.log(keys)
  keys.forEach((key) => {
    answers.push({id:key,data:answerModel.data[key]});
  })
  console.log(answers)
  http().post(`/survey/${survey.data.id}/answer`,{survey_id:survey.data.id,name:name.value,answers:answers})
  .then(response => {
    console.log(response);
    isSubmitted.value = true;
  })
  .catch(error => console.log(error))
}


/**
 * fetching a survey by id
 */
onMounted(() => {
  loading.value = true;
  http().get('/survey/' + route.params.slug)
    .then(response => {
      survey.data = response.data.data;
      // store answerModel as question id and left data as empty
      survey.data.questions.forEach(question => {
        if(question.type == 'checkbox'){
          answerModel.data[question.id] = [];
        }
        else{
          answerModel.data[question.id]='';
        }
      });
      loading.value = false;
    })
    .catch(error => {
      loading.value = false;
      router.push({name:'pagenotfound'});
    })
})
</script>
<template>
  <div v-if="loading" class="w-fit mx-auto mt-16">
    <Loading :size=12 />
  </div>
  <div v-else-if="isSubmitted">
    <h1>Thanks for answering the question</h1>
  </div>
  <div v-else>
    <div class="bg-red mx-auto w-full">
      <form class="bg-white my-4 w-3/4 rounded-lg mx-auto shadow-md p-8" @submit.prevent="submitAnswer">
        <!-- Title -->
        <div class="col-span-3 sm:col-span-2 mb-4">
          <h3 class="text-3xl font-bold">{{ survey.data.title }}</h3>
        </div>
        <!--title-->
        <!-- Description -->
        <div class="mb-4 ">
          <div class="mt-2">
            <p>{{ survey.data.description }}</p>
          </div>
        </div>
        <!-- Description -->
        <hr>
        <p class="font-gray-900 mt-2 text-sm">Your name</p>
        <div class="mt-2">
            <input type="text"
              v-model="name"
              class="font-medium mt-2 block w-full rounded-md border-0 py-1.5 text-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
        <!--Questions-->
        <div class="">
          <div v-for="(question, i) in survey.data.questions">
            <div class="py-4 border-b-[1px] border-gray-50">
              <h1 class="block text-xl font-medium leading-6 text-gray-900"> {{ i + 1 }}.{{ question.title }}</h1>
              <p class="font-gray-900 mt-2 text-sm">{{ question.description }}</p>
              <div v-if="question.type == 'select'" class="mt-2">
                <select id="country" name="country"
                  v-model="answerModel.data[question.id]"
                  class="mt-2 block w-full rounded-md border-0 font-medium bg-white py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                  <option v-for="data in question.data" class="font-medium text-gray-800">{{ data }}</option>
                </select>
              </div>
              <div v-if="question.type == 'text'" class="mt-2">
                <input type="text"
                  v-model="answerModel.data[question.id]"
                  class="font-medium mt-2 block w-full rounded-md border-0 py-1.5 text-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
              <div v-if="question.type == 'checkbox'" class="mt-2">
                <div class="flex items-start" v-for="data in question.data">
                  <div class="flex h-6 items-center">
                    <input type="checkbox" v-model="answerModel.data[question.id]" :value="data" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                  </div>
                  <div class="ml-3 text-sm leading-6">
                    <label for="candidates" class="font-medium text-gray-900">{{ data }}</label>
                  </div>
                </div>
              </div>
              <div class="mt-2" v-if="question.type == 'textarea'">
                <textarea rows="3"
                  v-model="answerModel.data[question.id]"
                  class=" text-gray-800 font-medium mt-1 block w-full rounded-md border-0 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6"
                  placeholder="">{{ answerModel.data[question.id] }}</textarea>
              </div>
              <div class="mt-2" v-if="question.type == 'radio'">
                <div class="flex items-center" v-for="data in question.data">
                  <input id="push-everything" 
                  v-model="answerModel.data[question.id]"  
                  :value="data"
                  name="push-notifications" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                  <label for="push-everything" class="ml-3 block  font-medium leading-6 text-gray-800">{{data}}</label>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!--Questions-->
        <div class="flex justify-end mt-4">
          <PrimaryButton buttonType="submit" :disabled="store.isCreating">
          <Loading v-if="store.isCreating" />{{ store.isCreating ? "submitting" : "Submit" }}
        </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
