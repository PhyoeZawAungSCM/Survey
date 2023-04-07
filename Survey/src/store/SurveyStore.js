import { defineStore } from "pinia";
import { computed, reactive, ref, watch } from "vue";
import router from "../router";
import { httpAuth } from "../services/HTTPService";
import { useNotification } from "@kyvg/vue3-notification";

export const useSurveyStore = defineStore("survey", () => {
    const surveys = reactive({ data: [] });
    const loading = ref(false);
    const isCreating = ref(false);
    const { notify } = useNotification();

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
        const formData = new FormData();
        formData.append("title", survey.title);
        formData.append("description", survey.description);
        formData.append("status", survey.status);
        formData.append("expire_date", survey.expire_date);
        formData.append("image", survey.image);
        for (let i in survey.questions) {
            const keys = Object.keys(survey.questions[i]);
            keys.forEach((key) => {
                if (key == "data" && Array.isArray(survey.questions[i].data)) {
                    let data = survey.questions[i].data;
                    if (data.length != 0) {
                        for (let index in data) {
                            formData.append(
                                `questions[${i}][${key}][${index}]`,
                                survey.questions[i][key][index]
                            );
                        }
                    } else {
                        console.log("Else condition");
                        formData.append(`questions[${i}][${key}][0]`, null);
                    }
                } else {
                    formData.append(
                        `questions[${i}][${key}]`,
                        survey.questions[i][key]
                    );
                }
            });
        }
        httpAuth()
            .post("/survey/create", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then((response) => {
                isCreating.value = false;
                surveys.data.push(response.data.survey);
                router.push("/survey");
                notify({
                    title: "Survey create success",
                    type: "success",
                });
            })
            .catch((error) => {
                isCreating.value = false;
                notify({
                    title: "Error occur",
                    type: "error",
                    text: error.response.data.message,
                });
            });
    }
    /**
     * to delete a survey
     */
    function deleteSurvey(id) {
        httpAuth()
            .delete(`/survey/survey/${id}`)
            .then((res) => {
                surveys.data = surveys.data.filter((survey) => {
                    return survey.id != res.data.survey.id;
                });
                notify({
                    title: "Survey delete success",
                    type: "success",
                });
            })
            .catch((error) => {
                notify({
                    title: "Error occur",
                    type: "error",
                    text: error.response.data.message,
                });
            });
    }

    const latestSurvey = computed(()=> {
        let latest_time = new Date("2023-01-01Z00:00:00:000");
        let latest_index = 0;
        surveys.data.forEach((survey,index) => {
           let current_survey_create_time = new Date(survey.created_at);
           if(latest_time <= current_survey_create_time){
                latest_time = current_survey_create_time;
                latest_index = index;
           }
        });
        return surveys.data[latest_index];
    })
    return {
        surveys,
        getSurveys,
        getAllSurveys,
        loading,
        submitSurvey,
        isCreating,
        deleteSurvey,
        latestSurvey
    };
});
