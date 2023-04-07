<script setup>
import { ref, reactive ,watchEffect } from "vue";
import { v4 } from 'uuid';
import { useSurveyStore } from "../store/SurveyStore";
import { formatDate } from '../services/DateFormatService.js';
import { httpAuth } from '../services/HTTPService';
import Question from './Question.vue';
import PrimaryButton from "./Buttons/PrimaryButton.vue";
import Loading from "./Loading/Loading.vue";
import HeaderBar from "./HeaderBar/HeaderBar.vue";

const store = useSurveyStore();
const survey = reactive({
  data: {
    title: '',
    description: '',
    status: 0,
    expire_date: '',
    image:null,
    questions: []
  }
});
const today = new Date();
const formatedToday = formatDate(today);

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

const image = ref(null);
const previewImage = ref(null);
/**
 * Upload an image and preview it
 */
function ImageUpload() {
  survey.data.image = image.value.files[0];
  previewImage.value = URL.createObjectURL(survey.data.image);
}

const status = ref(true);
watchEffect( () => {
  survey.data.status = status.value ?  1 : 0 ;
});
</script>
<template>
  <HeaderBar title="Create Survey"></HeaderBar>
  <div class="bg-red mx-auto w-full">
    <form class="bg-white my-4 w-3/4 rounded-lg mx-auto shadow-md p-8" @submit.prevent="submitSurvey">
      <!--Image-->
      <div class="col-span-full">
        <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Survey photo</label>
        <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
          <div class="text-center items-center">
            <img v-if="previewImage" :src="previewImage" />
            <div v-else>
              <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                  clip-rule="evenodd" />
              </svg>
            </div>
              <div class="mt-4 flex text-sm leading-6 text-gray-600">
                <label for="file-upload"
                  class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                  <span>{{previewImage ? "Change photo" : "Upload a photo" }}</span>
                  <input id="file-upload" name="file-upload" type="file" class="sr-only" ref="image"
                    @change="ImageUpload">
                </label>
              </div>
          </div>
        </div>
      </div>

      <!--Image-->
      <!-- Title -->
      <div class="col-span-3 sm:col-span-2 mb-4 mt-2">
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
          <input type="checkbox" v-model="status" class="sr-only peer">
          <div
            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
          </div>
        </label>
      </div>
      <div>
        <label for="expire_date" class="block text-sm font-medium leading-6 text-gray-900 mb-2">Expire date</label>
        <input type="date" id="expire_date"
          :min="formatedToday"
          class="rounded px-6  text-gray-900 font-medium border-gray-300 shadow-sm focus:ring-blue-800"
          v-model="survey.data.expire_date" />
      </div>


      <!--Questions-->
      <div class="mt-4">
        <div class="flex justify-between items-center mb-2">
          <h3 class="block text-3xl font-medium leading-6 text-gray-900">Questions</h3>
        </div>
        <div v-if="survey.data.questions.length == 0" class="text-gray-900 font-medium items-center"><span
            class="text-gray-900 font-normal text-sm">you do not
            have any question created</span>
        </div>
        <Question v-for="(question, index) in survey.data.questions" :key="question" :question="question" :index="index"
          @deleteQuestion="deleteQuestion" />
      </div>
      <!--Questions-->
      <div class="flex justify-end gap-5">
        <PrimaryButton @click="addQuestion" buttonType="button">Create New question</PrimaryButton>
        <PrimaryButton buttonType="submit" :disabled="store.isCreating">
          <Loading v-if="store.isCreating" />{{ store.isCreating ? "Creating" : "Create" }}
        </PrimaryButton>
      </div>
    </form>
</div></template>
