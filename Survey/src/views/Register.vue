<script setup>
  import { RouterLink } from 'vue-router';
  import {ref,watch} from 'vue';
  import {useUserStore} from '../store/AuthStore';

  const store = useUserStore();
  watch(()=>[store.userData.name,store.userData.email,store.userData.password,store.userData.passwordConfirmation],()=>{
    store.errorData.hasError = false;
  })

</script>
<template>

  <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
      <div>
        <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
          alt="Your Company" />
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Register an account</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or
          {{ ' ' }}
          <router-link to="login" href="#" class="font-medium text-indigo-600 hover:text-indigo-500">sign in to your account</router-link>
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="store.register" >
        <input type="hidden" name="remember" value="true" />
       
        <div class="-space-y-px rounded-md shadow-sm">
          <Transition>
          <div v-show="store.errorData.hasError" class="bg-red-500 w-full mb-3 rounded-md justify-center px-3 items-center">
            <p class="text-gray-50 py-2">{{ store.errorData.error }}</p>
          </div>
          </Transition>
          <div>
            <label for="name" class="sr-only">Name</label>
            <input id="name" name="name" type="text" v-model="store.userData.name"
              class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="Name" />
          </div>
          <div>
            <label for="email-address" class="sr-only">Email address</label>
            <input id="email-address" name="email" type="email" autocomplete="email" required="" v-model="store.userData.email"
              class="relative block w-full border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="Email address" />
          </div>
          <div>
            <label for="new-password" class="sr-only">new-password</label>
            <input id="new-password" name="new-password" type="password" autocomplete="current-password" required="" v-model="store.userData.password"
              class="relative block w-full border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="New password" />
          </div>
          <div>
            <label for="confirm-password" class="sr-only">Password</label>
            <input id="confirm-password" name="confirm-password" type="password" autocomplete="current-password"
              required=""
              v-model="store.userData.passwordConfirmation"
              class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
              placeholder="Confirm password" />
          </div>
        </div>
        <div>
          <button type="submit"
            class="group relative flex w-full justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            </span>
            Register
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

