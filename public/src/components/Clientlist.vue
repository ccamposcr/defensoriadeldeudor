<template>
  <ul><li v-bind:key="item.id" v-for="item in users">{{ item.name }}</li></ul>
</template>

<script>
export default {
  name: 'Clientlist',
  data () {
    return {
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
}
</script>

<style lang="scss" scoped>

</style>
