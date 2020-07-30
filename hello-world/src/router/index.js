import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)
const routes = [{
  path: '/',
  name: 'Home',
  component: Home,
  meta: {
    title: '登录页',
    keepAlive: true, // 需要被缓存
  }
},
{
  path: '/login', // 返回登录页面
  name: 'login',
  component: Home
},
{
  path: '/about',
  name: 'About',
  component: () => import('../views/About.vue')
},
{
  path: '/index',
  name: 'index',
  component: () => import('../views/index.vue')
}
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router