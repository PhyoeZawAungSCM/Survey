import axios from 'axios'
import { useUserStore } from '../store/AuthStore';
/**
 * making ajax request only for http not for File input 
 */
export const http = () => {
  return axios.create({
    baseURL:'http://127.0.0.1:8000/api',
  })
}

/**
 * to make the request with authorization header for sanctum API token
 * @returns function
 */
export const httpAuth = () => {
  const store = useUserStore();
  console.log(store.AUTH_USER.token)
  return axios.create({
    baseURL:'http://127.0.0.1:8000/api/auth',
    headers:{
      'Authorization':`Bearer ${store.AUTH_USER.token}`
    }
  })
}
