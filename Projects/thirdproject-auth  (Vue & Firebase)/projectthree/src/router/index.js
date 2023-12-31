import Vue from 'vue'
import Router from 'vue-router'
import GMap from '@/components/home/GMap'
import Signup from '@/components/auth/Signup'
import Login from '@/components/auth/Login'
import firebase, { remoteConfig } from 'firebase'
import ViewProfile from '@/components/profile/ViewProfile'


Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'GMap',
      component: GMap,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/signup',
      name: 'Signup',
      component: Signup
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
    },
    {
      path: '/profile/:id',
      name: 'ViewProfile',
      component: ViewProfile,
      meta: {
        requiresAuth: true
      }
    }
  ]
})

router.beforeEach((to, from, next) => {
  //check to see if route reqiures authentication firest
  if(to.matched.some(rec => rec.meta.requiresAuth)) {
    //check auth state of user
    let user = firebase.auth().currentUser
    if(user) {
      next()
    }else {
      next({name: 'Login'})
    }
  } else {
    next()
  }
})


export default router
