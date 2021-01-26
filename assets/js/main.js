Vue.component('app-client-list', {
    props: [],
    template: '<ul><li v-bind:key="item.id" v-for="item in users">{{ item.name }}</li></ul>',
    data: function(){
        return{
            users: []
        }
    },
    created: function(){ // Perfect step to retrieve async data
        this.getUsers();
    },
    methods: {
        getUsers: async function(){
            const res = await fetch('clientes/getAllClients');
            const data = await res.json();
            this.users = data;
        }
    }
})

const app = new Vue({
    el: '#app'
})