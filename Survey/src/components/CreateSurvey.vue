<script setup>
import { ref, reactive } from "vue";
import { v4 } from 'uuid';
import { httpAuth } from '../services/HTTPService'
import Question from './Question.vue';
import PrimaryButton from "./Buttons/PrimaryButton.vue";
import Loading from "./Loading/Loading.vue";
import HeaderBar from "./HeaderBar/HeaderBar.vue";
import { useSurveyStore } from "../store/SurveyStore";

const store = useSurveyStore();

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
function submitSurvey() { 
  store.submitSurvey(survey.data);
}
</script>
<template>
  <HeaderBar title="Create Survey"></HeaderBar>
  <div class="bg-red mx-auto w-full">
    <form class="bg-white my-4 w-3/4 rounded-lg mx-auto shadow-md p-8" @submit.prevent="submitSurvey">
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
        <div v-if="survey.data.questions.length == 0" class="text-gray-900 font-medium items-center"><span class="text-gray-900 font-normal text-sm">you do not
            have any question created</span>
        </div>
        <Question v-for="(question, index) in survey.data.questions" :key="question" :question="question" :index="index"
          @deleteQuestion="deleteQuestion" />
      </div>
      <!--Questions-->
      <div class="flex justify-end gap-5">
      <PrimaryButton @click="addQuestion" buttonType="button">Create New question</PrimaryButton>
      <PrimaryButton buttonType="submit" :disabled="store.isCreating"><Loading v-if="store.isCreating"/>{{store.isCreating ? "Creating" : "Create"}}</PrimaryButton>  
    </div>
    </form>
  </div>
</template>
