import { createRouter, createWebHistory } from "vue-router";
import { useUserStore } from "../store/AuthStore";
import DefaultLayout from "../views/DefaultLayout.vue";
import AuthLayout from "../views/AuthLayout.vue";
import Register from "../views/Register.vue";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import Survey from "../views/Survey.vue";
import CreateSurvey from '../components/CreateSurvey.vue';
import EditSurvey from '../components/EditSurvey.vue';
import SurveyAnswer from '../views/SurveyAnswer.vue';
import AnswerList from '../views/AnswerList.vue';
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            redirect: "/dashboard",
            meta: { requireAuth: true },
            component: DefaultLayout,
            children: [
                {
                    path: "/dashboard",
                    component: Dashboard,
                    name: "dashboard",
                },
                {
                    path: "/survey",
                    component: Survey,
                    name: "survey",
                },
                {
                  path:"/survey/create",
                  component:CreateSurvey,
                  name:'create-survey'
                },
                {
                  path:'/survey/edit/:id',
                  component:EditSurvey,
                  name:'edit-survey'
                },
                {
                  path:'/answer',
                  component:AnswerList,
                  name:'answer-list'
                }
              
            ],
        },

        {
            path: "/auth",
            redirect: "/login",
            component: AuthLayout,
            meta: { isGuest: true },
            children: [
                {
                    path: "/register",
                    component: Register,
                    name: "register",
                },
                {
                    path: "/login",
                    component: Login,
                    name: "login",
                },
            ],
        },
        {
          path:'/survey/:slug',
          component:SurveyAnswer,
          name:'answer-servey',
        }
    ],
    
});
router.beforeEach((to,from,next)=>{
  const store = useUserStore();
  if(to.meta.requireAuth && !store.AUTH_USER.token){
    next('/login');
  }else if(to.meta.isGuest && store.AUTH_USER.token){
    next('/dashboard');
  }else{
    next()
  }
})
export default router;
