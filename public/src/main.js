import Vue from 'vue'
import BootstrapVue from "bootstrap-vue"
import App from './App.vue'
import router from './router';
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-vue/dist/bootstrap-vue.css"
import vuetify from './plugins/vuetify'
import VueTheMask from 'vue-the-mask'
import Vuex from 'vuex'

Vue.use(BootstrapVue);
Vue.use(VueTheMask);
Vue.use(Vuex);

const moment = require('moment')
require('moment/locale/es')
 
Vue.use(require('vue-moment'), {
    moment
})

new Vue({
  vuetify,
  router,
  render: h => h(App)
}).$mount('#app');