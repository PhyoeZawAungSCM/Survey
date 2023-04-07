<script setup>
import { ref, reactive, watch,onMounted } from 'vue';
import { http } from '../services/HTTPService';
import SideBar from '../components/SideBar.vue'
import { useRoute,useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const answerId = ref(route.params.id);
function getData()  {
  if (answerId.value) {
    getQuestionAndAnswer(answerId.value);
    isGettingAnswer.value = true;
    answerId.value = null;
    router.replace('/answer')
  }
};

/**
 * get survey id by filtering answer id
 * @param {survey} survey 
 */
function getSurveyId(survey){
  let surveyId = undefined ; 
  survey.answers.forEach(answer => {
    if(answer.id == answerId.value){
      surveyId = survey.id;
    };
  })
  return surveyId;
}
/**
 * filter the surveys and do the process of assigning
 * @param {surveys} surveys 
 */
function doProcess(surveys){
  const filteredSurvey = surveys.surveys.filter(survey => {
    const survey_id = getSurveyId(survey);
    if(survey_id){
      return true;
    }
 });
  answers.value = filteredSurvey[0].answers;
  survey.data.title = filteredSurvey[0].title;
  survey.data.description = filteredSurvey[0].description;
  getData();
} 
const isLoading = ref(false);
const showAnswerDetail = ref(false);
const data = ref({});
const answers = ref([]);
const active = ref(1);
const isGettingAnswer = ref(false);
const showPreviousButton = ref(false);
const answerButtonList = ref([]);
let previousButtons = [];
let nextButtons = [];
const activeAnswer = ref(0);
const survey = reactive({
  data: {
    title: "",
    description: '',
  }
});
/**
 * get answer list from side bar
 */
function getAnswerList({ survey_id, answer, title, description }) {
  survey.data.title = title;
  survey.data.description = description;
  answers.value = answer;
  active.value = survey_id;
  data.value = {};
  previousButtons = [];
  nextButtons = [];
  isGettingAnswer.value = true;
  getQuestionAnswer.value = false;
}

/**
 * get question and ansewers data from answer id get from side bar 
 */
const getQuestionAnswer = ref(false);
function getQuestionAndAnswer(answer_id) {
  activeAnswer.value = answer_id;
  http().get(`questionAndAnswer/${answer_id}`)
    .then(response => {
      showAnswerDetail.value = true;
      data.value = response.data;
      isLoading.value = false;
      getQuestionAnswer.value = true;
    })
    .catch(error => {
      console.log(error);
    })
}

/**
 * Next button click function 
 */
function addNewButton() {
  if (nextButtons.length != 0) {
    let button = nextButtons.shift();
    let removeButton = answerButtonList.value.shift();
    previousButtons.unshift(removeButton);
    answerButtonList.value.push(button);
  }
}
/**
 * previous button click function
 */
function removeNewButton() {
  if (previousButtons.length != 0) {
    let button = previousButtons.shift();
    let removeButton = answerButtonList.value.pop();
    nextButtons.push(removeButton);
    answerButtonList.value.unshift(button);
  }
}
/**
 * watch the answer and create buttons based on this
 */
watch(answers, () => {
  console.log("Enter watch")
  let idList = [];
  // getting all the keys from answers
  let keys = Object.keys(answers.value);
  // if the key length greater than 5 add to next button list
  if (keys.length > 5) {
    for (let i = 5; i < keys.length; i++) {
      nextButtons.push(answers.value[keys[i]].id)
    }
  }
  if (keys.length > 5) {
    for (let i = 0; i < 5; i++) {
      idList.push(answers.value[keys[i]].id);
    }
  } else {
    keys.forEach(key => {
      idList.push(answers.value[key].id)
    })
  }
  answerButtonList.value = idList;
  console.log(idList)
})
</script> 
<template>
  <div class="flex w-11/12 mx-auto max-h-full">
    <div :class="isGettingAnswer ? 'transition-width duration-300 ease-linear' : 'mx-auto w-2/4'" class="w-1/4">
      <Transition name="detail">
        <div v-if="showAnswerDetail"
          class="bg-white rounded-md shadow-sm justify-center mt-4 border-r-2 px-4 sticky top-0">
          <h1 class="text-2xl font-semibold text-gray-900 pl-8 py-2">Answer detail</h1>
          <div class="w-full p-3 grid grid-cols-2 gap-2">
            <h4 class="text-gray-900 font-medium">Name </h4>
            <h4 class="text-gray-900 font-medium">{{ data.answer_detail.name }}</h4>
            <h4 class="text-gray-900 font-medium">Answer at </h4>
            <h4 class="text-gray-900 font-medium">{{ data.answer_detail.created_at }}</h4>
            <h4 class="text-gray-900 font-medium">Total questions</h4>
            <h4 class="text-gray-900 font-medium">{{ data.questions.length }}</h4>
            <h4 class="text-gray-900 font-medium">Total answers</h4>
            <h4 class="text-gray-900 font-medium">{{ data.answers.length }}</h4>
            <h4 class="text-gray-900 font-medium">Survey Id </h4>
            <h4 class="text-gray-900 font-medium">{{ data.answer_detail.survey_id }}</h4>
          </div>
          <div class="flex  justify-end w-full pb-4">
            <button class="bg-blue-600 rounded-lg  text-white px-4 py-2 font-semibold hover:bg-blue-700"
              @click="showAnswerDetail = false">show survey list</button>
          </div>
        </div>
      </Transition>
      <Transition name="list">
        <SideBar v-if="!showAnswerDetail" @getAnswerList="getAnswerList" :activeId=active :doProcess="doProcess" :canProcess="answerId"/>
      </Transition>
    </div>
    <Transition name="answer">
      <div v-if="isGettingAnswer" class=" w-3/4 gap-2">
        <div
          class="w-full bg-white rounded p-2 mt-4 flex items-center justify-between h-16 sticky top-0 z-10 border-b-[1px]">
          <div>
            <h1></h1>
            <button class="w-[30px]  border-2 mx-2 rounded-lg hover:bg-gray-600 hover:text-white" @click="removeNewButton"
              v-if="answers.length > 5">&Lt;</button>
            <button @click="getQuestionAndAnswer(answer)"
              class="w-[30px]  border-2 mx-2 rounded-lg hover:bg-gray-600 hover:text-white"
              :class="answer == activeAnswer ? ' bg-gray-600 text-white' : ''" v-for="answer in answerButtonList">
              {{ answer }}</button>
            <button class="w-[30px]  border-2 mx-2 rounded-lg hover:bg-gray-600 hover:text-white" @click="addNewButton"
              v-if="answers.length > 5">&Gt;</button>
          </div>
          <h3 class="mr-4 text-gray-900 font-semibold">Total survey's answers {{ answers.length }}</h3>
        </div>
        <form class="bg-white rounded-b-lg  shadow-md p-8">
          <!-- Title -->
          <div class="col-span-3 sm:col-span-2 mb-4">
            <h3 class="text-3xl font-bold">{{ survey.data.title }}</h3>
          </div>
          <!--title-->
          <!-- Description -->
          <div class="mb-4 ">
            <div class="mt-2">
              <p>{{ survey.data.description == 'null' ? '' : survey.data.description }}</p>
            </div>
          </div>
          <!-- Description -->
          <hr>
          <!--Questions-->
          <Transition name="question-and-answer">
            <div v-if="getQuestionAnswer">
              <div class="">
                <div v-for="(question, i) in data.questions">
                  <div class="py-4 border-b-[1px] border-gray-50">
                    <h1 class="block text-xl font-medium leading-6 text-gray-900"> {{ i + 1 }}.{{ question.title }}</h1>
                    <p class="font-gray-900 mt-2 text-sm">{{ question.description }}</p>
                    <div v-if="question.type == 'select'" class="mt-2">
                      <select id="country" name="country" disabled :value="data.answers[i] ? data.answers[i].data : ''"
                        class="mt-2 block w-full rounded-md border-0 font-medium bg-white py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option v-for="data in question.data" class="font-medium text-gray-800">{{ data }}</option>
                      </select>
                    </div>
                    <div v-if="question.type == 'text'" class="mt-2">
                      <input type="text" disabled :value="data.answers[i] ? data.answers[i].data : ''"
                        class="font-medium mt-2 block w-full rounded-md border-0 py-1.5 text-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    <div v-if="question.type == 'checkbox'" class="mt-2">
                      <div class="flex items-start" v-for="item in question.data">
                        <div class="flex h-6 items-center">
                          <input type="checkbox" :value="item" disabled
                            :checked="data.answers[i] ? data.answers[i].data.includes(item) : ''"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        </div>
                        <div class="ml-3 text-sm leading-6">
                          <label for="candidates" class="font-medium text-gray-900">{{ item }}</label>
                        </div>
                      </div>
                    </div>
                    <div class="mt-2" v-if="question.type == 'textarea'">
                      <textarea rows="3" disabled
                        class=" text-gray-800 font-medium mt-1 block w-full rounded-md border-0 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6"
                        placeholder="">{{ data.answers[i] ? data.answers[i].data : "" }}</textarea>
                    </div>
                    <div class="mt-2" v-if="question.type == 'radio'">

                      <div class="flex items-center" v-for="item in question.data">
                        <input id="push-everything" disabled :value="item"
                          :checked="data.answers[i] ? data.answers[i].data == item : ''" name="push-notifications"
                          type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="push-everything" class="ml-3 block  font-medium leading-6 text-gray-800">{{ item
                        }}</label>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </Transition>
          <!--Questions-->
        </form>
      </div>
    </Transition>
  </div>
</template>
<style scoped>
.answer-enter-active {
  transition: width 0.5s ease;
}

.answer-enter-from {
  width: 0px;
}

.detail-enter-from,
.detail-leave-to {
  opacity: 0;
}

.detail-leave-active {
  display: none;
}

.detail-enter-active {
  transition: all 0.5s ease-in-out;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
}

.list-enter-active {
  transition: all 0.5s ease-in-out;
}

.list-leave-active {
  display: none;
}

.question-and-answer-enter-from,
.question-and-answer-leave-to {
  opacity: 0;
}

.question-and-answer-enter-active,
.question-and-answer-leave-active {
  transition: opacity 0.3s ease-in-out;
}
</style>
