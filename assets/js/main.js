Vue.component('app-client-list', {
    props: {
        item: String
    },
    template: '<li>{{ item.name }}</li>',
    data: function(){
        return{
            users: []
        }
    },
    created(){
        this.getUsers();
    },
    methods: {
        async getUsers(){
          const res = await fetch('clientes/getAllClients');
          const data = await res.json();
          this.users = data;
        }
    }
})

const app = new Vue({
    el: '#app'
})