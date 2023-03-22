<script setup>
import { ref, reactive } from "vue";
import { v4 } from 'uuid';
import Question from './Question.vue';
import PrimaryButton from "./Buttons/PrimaryButton.vue";
import {httpAuth} from '../services/HTTPService'

// const imageFile = ref(null);
// function previewImage(event) {
//   console.log(event.target.files[0]);
// }


const survey = reactive({
  data: {
    title: '',
    description: '',
    status: '',
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
function deleteQuestion(id){
  survey.data.questions = survey.data.questions.filter(question => question.id != id);
}

/**
 * submitting a question form
 */
function submitSurvey(){
  httpAuth().post('/survey/create',survey)
  .then(response => {
    console.log(response.data);
  })
  .catch(error => {
    console.log(error);
  })
}
</script>
<template>
  <h1>Create surcey</h1>
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
      <!--Questions-->
      <div>
        <div class="flex justify-between items-center mb-2">
          <h3 class="block text-3xl font-medium leading-6 text-gray-900">Questions</h3>

        </div>
        <div class="text-gray-900 font-medium items-center"><span class="text-gray-900 font-normal text-sm">you do not
            have any question created</span></div>

        <Question v-for="(question,index) in survey.data.questions" 
        :key="question" 
        :question="question"
        :index="index"
        @deleteQuestion="deleteQuestion" />


      </div>
      <!--Questions-->
    
      <PrimaryButton buttonType="submit">Create</PrimaryButton>
      <PrimaryButton @click="addQuestion" buttonType="button">Create New question</PrimaryButton>
    </form>
  </div>
</template>
