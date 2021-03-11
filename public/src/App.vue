<template>
  <div id="app">
    <div>Bienvenido: {{loggedINUser.name}} {{loggedINUser.lastName1}} {{loggedINUser.lastName2}}</div>
    <router-view></router-view>
  </div>
</template>

<script>

export default {
  name: 'app',
  components: { },
  data () {
    return {
      loggedINUser: []
    }
  },
  created: function(){
    this.setLoggedInUser();
  },
  methods: {
    setLoggedInUser: async function(){
      const data = await this.getLoggedINUser();
      this.loggedINUser = data.response[0];
    },
    getLoggedINUser: async function(){
      const url = 'clientes/getClientBy';
      
      const params = {
          'searchBy':'personalID',
          'value' :loggedINUser
      };
      params[csrf_name] = csrf_hash;
      const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(params),
          headers:{
          'Content-Type': 'application/x-www-form-urlencoded',
          "X-Requested-With": "XMLHttpRequest"
          }
      });

      const data = await response.json();
      csrf_name = data.csrf_name;
      csrf_hash = data.csrf_hash;
      return data;
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
