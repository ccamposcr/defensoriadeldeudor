import Vue    from 'vue'
import Router from 'vue-router'

import Client    from './components/Client.vue'
import Appointment    from './components/Appointment.vue'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/clientes',
            component: Client
        },
        {
            path: '/citas',
            component: Appointment
        }
    ],
    mode: "history",
    base: '/defensoriadeldeudor'
})