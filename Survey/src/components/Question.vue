
<script setup>
import { useQuestionStore } from '../store/QuestionStore.js';
import { ref,reactive } from 'vue';
import Select from './InputType/Select.vue';
import DeleteButton from './Buttons/DeleteButton.vue';
const props = defineProps({
  question:Object,
  index:Number,
})
const emit = defineEmits('deleteQuestion');

const QUESTION_TYPE = ['text', 'select', 'radio', 'checkbox', 'textarea'];

const optionType = ['radio','checkbox','select'];

function addOption(){
  props.question.data.push("");
}
function removeOption(index){
  props.question.data.splice(index,1);
}
</script>

<template>
  <div class="border-b-2 p-6 mb-2">
    <div>
      <div class="flex justify-between">
           <h2 class="font-bold text-2xl text-gray-900">{{index + 1}}.{{ question.title }}</h2>
           <DeleteButton
           @click="emit('deleteQuestion',question.id)">Delete Question</DeleteButton>
      </div>
   
      <div class="flex justify-between">
        <!-- Title -->
        <div class="col-span-3 sm:col-span-2 mb-4 w-3/4 mr-2">
          <label for="company-website" class="block text-sm font-medium leading-6 text-gray-900">Question text</label>
          <div class="mt-2 flex rounded-md shadow-sm">
            <input type="text" name="company-website" id="company-website"
            v-model="question.title"
              class="block w-full flex-1 rounded rounded-r-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="www.example.com" />
          </div>
        </div>
        <div class="w-1/4">
          <div class="col-span-6 sm:col-span-3">
            <label for="type" class="block text-sm font-medium leading-6 text-gray-900">Question type</label>
            <select id="type" name="type" v-model="question.type"
              class="mt-2 block w-full rounded-md border-0 bg-white py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              <option v-for="(type) in QUESTION_TYPE" :key="type">{{ type }}</option>
            </select>
          </div>
        </div>
        <!--title-->
      </div>
      <div class="mb-4">
      <label for="about" class="block text-sm font-medium leading-6 text-gray-900">Question description</label>
      <div class="mt-2">
        <textarea id="about" name="about" rows="3"
        v-model="question.description"
          class="mt-1 block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6"
          placeholder="Question Description" >{{ question.description }}</textarea>
      </div>
    </div>
      <!--select-->
      <Select 
      v-if= "optionType.includes(props.question.type)" 
      :data="props.question.data"
      @addOption = "addOption"
      @removeOption = "removeOption"
      />
      <!--select-->
    </div>
  </div>
</template>