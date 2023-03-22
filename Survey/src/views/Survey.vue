<script setup>
import { useSurveyStore } from '../store/SurveyStore';
import { onMounted } from 'vue';
import router from '../router';
import PrimaryButton from '../components/Buttons/PrimaryButton.vue';
import HeaderBar from '../components/HeaderBar/HeaderBar.vue';
import SurveyCard from '../components/SurveyCard.vue';
import Loading from '../components/Loading/Loading.vue';
const store = useSurveyStore();
onMounted(() => {
  console.log(store.surveys.data);
  if (store.surveys.data.length != 0) {
    return
  }
  store.getSurveys();
})

</script>
<template>
  <div>
    <HeaderBar title="Survey">
      <PrimaryButton @click="router.push('/survey/create')">Create Survey</PrimaryButton>
    </HeaderBar>
    <div class="mx-auto w-32 mt-10" v-if="store.loading">
      <Loading :size=12 />
    </div>
    <div v-else>
      <div v-if="store.surveys.data.length == 0" class="mx-auto w-64 mt-10">
        <span>You do not have any survey yet</span>
      </div>
      <div class="w-11/12 mx-auto flex flex-wrap gap-3 mt-3 justify-center">
        <SurveyCard v-for="survey in store.getAllSurveys" :survey="survey"></SurveyCard>
      </div>
    </div>
  </div>
</template>