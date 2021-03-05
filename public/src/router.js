import Vue    from 'vue'
import Router from 'vue-router'

import Client    from './components/Client.vue'
import Inicio    from './components/Inicio.vue'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/clientes',
            component: Client
        },
        {
            path: '/inicio',
            component: Inicio
        }
    ],
    mode: "history",
    base: '/defensoriadeldeudor'
})