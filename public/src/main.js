import Vue from 'vue'
import BootstrapVue from "bootstrap-vue"
import App from './App.vue'
import router from './router';
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-vue/dist/bootstrap-vue.css"
import vuetify from './plugins/vuetify'

Vue.use(BootstrapVue);

new Vue({
  vuetify,
  router,
  render: h => h(App)
}).$mount('#app');