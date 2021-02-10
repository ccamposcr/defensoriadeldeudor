import Vue    from 'vue'
import Router from 'vue-router'

import Client    from './components/Client.vue'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/clientes',
            component: Client
        }
    ],
    mode: "history",
    base: '/defensoriadeldeudor'
})