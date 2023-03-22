import { defineStore } from "pinia";
import { computed, reactive, ref } from "vue";
import router from "../router";
import { httpAuth } from "../services/HTTPService";
export const useSurveyStore = defineStore("survey", () => {
    const surveys = reactive({ data: [] });
    const loading = ref(false);
    const isCreating = ref(false);
    function getSurveys() {
        loading.value = true;
        httpAuth()
            .get("/survey/surveys")
            .then((response) => {
                surveys.data = response.data.surveys;
                loading.value = false;
            })
            .catch((error) => {
                loading.value = false;
                alert(error);
            });
    }
    const getAllSurveys = computed(() => {
        return surveys.data;
    });
    /**
     * submitting a question form
     */
    function submitSurvey(survey) {
        isCreating.value = true;
        httpAuth()
            .post("/survey/create", survey)
            .then((response) => {
                isCreating.value = false;
                surveys.data.push(response.data.survey)
                router.push('/survey');
            })
            .catch((error) => {
                isCreating.value = false;
                alert(error);
            });
    }
    return { surveys, getSurveys, getAllSurveys, loading ,submitSurvey,isCreating};
});
