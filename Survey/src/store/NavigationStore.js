import {defineStore} from 'pinia';
import {ref} from 'vue';
import { useUserStore } from './AuthStore';
import { useRouter } from 'vue-router';
export const useNavigationStore = defineStore('navigation',()=>{
  const store = useUserStore();
  const router = useRouter();
  const NAV_BUTTONS = {
    Dashboard: {
      routerLink: '/dashboard'
    },
    Survey: {
      routerLink: '/survey'
    },
    Answer: {
      routerLink: '/answer'
    }
  };
  const USER_ACTION_BUTTONS = {
    Logout: {
      action: store.logout
    },
  };
  const isActive = ref('');

  // Change the active button
  function changeNavActive(button) {
    router.push(NAV_BUTTONS[button].routerLink);
    isActive.value = button;
  }
  return {NAV_BUTTONS , USER_ACTION_BUTTONS, isActive , changeNavActive };
})