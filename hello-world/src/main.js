import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import ElementUI from 'element-ui';
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min'
import $ from 'jquery';
import 'element-ui/lib/theme-chalk/index.css';

import Vant from 'vant';
import 'vant/lib/index.css';

// Vue.use(axios); // 使用此方法报错但不影响
Vue.prototype.$ajax = axios
Vue.use(ElementUI);
Vue.use(Vant);
Vue.config.productionTip = false

router.beforeEach((to,from,next)=>{
  if(to.meta.title){
    document.title = to.meta.title
  }
  next()
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')