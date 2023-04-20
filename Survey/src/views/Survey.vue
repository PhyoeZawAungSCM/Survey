<script setup>
import { useSurveyStore } from '../store/SurveyStore';
import { onMounted , watchEffect , ref } from 'vue';
import { useRouter } from 'vue-router';
import PrimaryButton from '../components/Buttons/PrimaryButton.vue';
import HeaderBar from '../components/HeaderBar/HeaderBar.vue';
import SurveyCard from '../components/SurveyCard.vue';
import Loading from '../components/Loading/Loading.vue';
import gsap from 'gsap';
const store = useSurveyStore();
const router = useRouter();
const surveys = ref(null);
watchEffect(()=>{
  surveys.value = store.getAllSurveys;
})
function deleteSurvey(id){
  store.deleteSurvey(id);
}
function viewLink(slug){
  router.push('/survey/'+slug);
}

/**
 * Before enter to make animation
 * @param {DOM el} el 
 */
function onBeforeEnter(el,done){
  el.style.opacity = 0;
  el.style.transform = 'translateY(20px)'
}

/**
 * Enter state of animation
 * @param {DOM el} el 
 */
function onEnter(el,done){
  gsap.to(el,{
    opacity:1,
    translateY:0,
    delay:el.dataset.index * 0.15,
    onComplete:done
  })
}

function onLeave(el,done){
  gsap.to(el,{
    opacity:0,
    translateY:20,
    delay:0.15,
    onComplete:done,
  });
}
/**
 * Get the surveys
 */
onMounted(() => {
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
        <TransitionGroup 
        @before-enter="onBeforeEnter"
        @enter="onEnter"
        @leave="onLeave"
        :css="false"
        appear
        >
        <SurveyCard v-for="(survey,index) in surveys" 
        @delete-survey="deleteSurvey"
        @viewLink="viewLink"
        :survey="survey"
        :key="index"
        :data-index="index"
         ></SurveyCard>
        </TransitionGroup>
      </div>
    </div>
  </div>
</template>
