<script setup>
import { ref } from 'vue';
import { gsap } from "gsap";
import { useUserStore } from '../store/AuthStore';
import router from '../router/index';

const store = useUserStore();

const NAV_BUTTONS = {
  Dashboard: {
    routerLink: '/dashboard'
  },
  Survey: {
    routerLink: '/survey'
  }
};
const USER_ACTION_BUTTONS = {
  Logout: {
    action: store.logout
  },
  Profile: {
    action: "profile"
  }
};
const currentRoute = router.currentRoute.value.name;
const currentRouteNav = currentRoute[0].toUpperCase() +currentRoute.substring(1,currentRoute.length);
// isActive
const isActive = ref(currentRouteNav);
function changeNavActive(button) {
  console.log(button)
  router.push(NAV_BUTTONS[button].routerLink);
  isActive.value = button;
}

// isUserActionActive
const isUserActionActive = ref(false);
const userActionButtons = ref(null);
function toggleUserActionActive(event) {
  isUserActionActive.value = !isUserActionActive.value;
  console.log(event.target)
  gsap.to(userActionButtons, {
    duration: 0.5,
    opacity: 1,
  });
}


</script>
<template>
  <div class="w-full bg-gray-800 align-middle py-2 z-50">
    <div class="w-11/12 mx-auto flex justify-between z-20">
      <div>
        <button v-for="button in Object.keys(NAV_BUTTONS)" :key="button" @click="changeNavActive(button)"
          :class="button == isActive ? 'bg-gray-900 text-gray-200 font-medium' : ''" class="text-gray-300
                            px-4 py-2 
                          hover:bg-gray-700
                            rounded-md
                            mx-2
                            transition
                            ease-in-out
                            duration-300
                          ">{{ button }}</button>
      </div>
      <div>
        <button class="text-gray-300 py-2 " @click="toggleUserActionActive">{{ store.AUTH_USER.user.name }}</button>
        <Transition>
          <div v-if="isUserActionActive" ref="userActionButtons" class="bg-gray-50 absolute
                      rounded shadow-md  
                      w-48
                      right-0 top-12
                      ">
            <button v-for="button in Object.keys(USER_ACTION_BUTTONS)" class="hover:bg-gray-300 
                                text-gray-700
                                w-full
                                p-2
                                rounded" @click="USER_ACTION_BUTTONS[button].action()">
              {{ button }}
            </button>
          </div>
        </Transition>
      </div>

    </div>

  </div>
</template>
<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
  transform: matrix(0.5, 0, 0, 0.2, 50, -50);
}
</style>