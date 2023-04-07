<script setup>
import { RouterLink } from 'vue-router';
import { useUserStore } from '../store/AuthStore';
import {watch} from 'vue';

const store = useUserStore();
watch(()=>[store.userData.email,store.userData.password],()=>{
    store.errorData.hasError = false;
  })
</script>
<template>
  <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
      <div>
        <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
          alt="Your Company" />
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Sign in to your account</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or
          {{ ' ' }}
          <router-link to="register" href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Sing up an account</router-link>
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="store.login">
        <input type="hidden" name="remember" value="true" />
        <div class="-space-y-px rounded-md shadow-sm">
          <Transition>
          <div v-show="store.errorData.hasError" class="bg-red-500 w-full mb-3 rounded-md justify-center px-3 items-center">
            <p class="text-gray-50 py-2">{{ store.errorData.error }}</p>
          </div>
          </Transition>
          <div>
            <label for="email-address" class="sr-only">Email address</label>
            <input id="email-address" name="email" type="email" autocomplete="email" required=""
            v-model="store.userData.email"
              class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="Email address" />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required=""
            v-model="store.userData.password"  class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="Password" />
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember-me" name="remember-me" type="checkbox"
              v-model="store.userData.remember_me"
              class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
          </div>
          <div class="text-sm">
            <!-- <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a> -->
          </div>
        </div>

        <div>
          <button type="submit"
            class="group relative flex w-full justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            </span>
            Sign in
          </button>
        </div>
      </form>
  </div>
</div>
</template>
<style scoped>
 .v-enter-from,
 .v-leave-to{
    transform: ScaleY(0);
 }
 .v-enter-active,
 .v-leave-active{
   transition: transform 0.3s ease-in-out;
 }
</style>

