import Vue    from 'vue'
import Router from 'vue-router'

import Client    from './components/Client.vue'
import Inicio    from './components/Inicio.vue'
import Administracion    from './components/Administracion.vue'
import Financial    from './components/Financial.vue'

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
        },
        {
            path: '/financiero',
            component: Financial
        },
        {
            path: '/administracion',
            component: Administracion
        }
    ],
    mode: "history",
    //base: '/'
    base: '/defensoriadeldeudor'
})