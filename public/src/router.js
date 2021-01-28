import Vue    from 'vue'
import Router from 'vue-router'

import Clientlist    from './components/Clientlist.vue'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/clientes',
            component: Clientlist
        }
    ],
    linkActiveClass: "active",
    mode: "history"
})