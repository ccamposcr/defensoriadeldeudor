import Vue from 'vue';
import BootstrapVue from "bootstrap-vue";
import App from './App.vue';
import router from './router';
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import vuetify from './plugins/vuetify';
import VueTheMask from 'vue-the-mask';
import money from 'v-money'
import store from './store.js';

Vue.use(BootstrapVue);
Vue.use(VueTheMask);
Vue.use(money, {precision: 2})

const moment = require('moment');
require('moment/locale/es');
 
Vue.use(require('vue-moment'), {
    moment
})

new Vue({
  store,
  vuetify,
  router,
  render: h => h(App)
}).$mount('#app');