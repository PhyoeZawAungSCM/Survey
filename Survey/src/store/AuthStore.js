import { defineStore } from "pinia";
import { reactive } from "vue";
import { http, httpAuth } from "../services/HTTPService";
import router from '../router';

export const useUserStore = defineStore("user", () => {
    // authenticate user
    const AUTH_USER = reactive({ user: {},token : localStorage.getItem('TOKEN'), isLogin: false });
    // Error Handler
    const errorData = reactive({ error: "", hasError: false });
    // Register User
    const userData = reactive({
        name: "",
        email: "",
        password: "",
        passwordConfirmation: "",
        remember_me: false,
    });
    /**
     * registering the user
     */
    function register() {
        http()
            .post("/register", {
                name: userData.name,
                email: userData.email,
                password: userData.password,
                password_confirmation: userData.passwordConfirmation,
            })
            .then((response) => {
                console.log(response);
                AUTH_USER.user = response.data;
                router.push("login");
            })
            .catch((error) => {
                errorData.error = Object.values(
                    error.response.data.errors
                )[0][0];
                errorData.hasError = true;
            });
    }
    /**
     * login the authenticated user
     */
    function login() {
        http()
            .post("/login", {
                email: userData.email,
                password: userData.password,
                remember_me: userData.remember_me,
            })
            .then((response) => {
                AUTH_USER.user = response.data.user;
                AUTH_USER.token = response.data.token;
                AUTH_USER.isLogin = true;
                if (AUTH_USER.user != {} && AUTH_USER.isLogin && AUTH_USER.token) {
                    localStorage.setItem("TOKEN",AUTH_USER.token)
                    router.push("dashboard");
                }
            })
            .catch((error) => {
                console.log(error);
            });
    }
    /**
     * logout the user
     */
    function logout(){
        httpAuth().post('/logout')
        .then(response => {
            AUTH_USER.token = null;
            userData = {
                name: "",
                email: "",
                password: "",
                passwordConfirmation: "",
                remember_me: false,
            };
            localStorage.removeItem('TOKEN');
            router.push('login');
        })
        .catch(error => {
            console.log(error);
        })
    }
    /**
     * get the user with the store token in localstorage
     */
    function getUser(){
        if(AUTH_USER.token){
            httpAuth().get('/user')
            .then(response => AUTH_USER.user = response.data.user)
            .catch(error => console.log(error));
        }
    }
    /**
     * Return the pinia User Store data and function to use another places
     */
    return { userData, register , errorData, AUTH_USER, login , getUser ,logout};
});
