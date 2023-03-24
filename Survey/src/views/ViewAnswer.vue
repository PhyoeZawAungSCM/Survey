<script setup>
import { onMounted ,reactive} from 'vue';
import { useRoute } from 'vue-router';
import { httpAuth } from '../services/HTTPService';

const route = useRoute();
const answer = reactive({data:{}});
  onMounted(()=>{
    httpAuth().get('/survey/answer/'+route.params.answer_id)
    .then(response => {
      answer.data = response.data.data;
    });
  })
</script>
<template>
<div>
  <pre>
    {{ answer.data }}
  </pre>
</div>
</template>