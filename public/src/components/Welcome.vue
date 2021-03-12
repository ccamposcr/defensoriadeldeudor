<template>
   <div>Bienvenido: {{loggedINUser.name}} {{loggedINUser.lastName1}} {{loggedINUser.lastName2}}</div>
</template>
 

<script>
export default {
  name: 'Welcome',
  props: [],
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
      const data = await this.$parent.getClientBy('personalID', loggedINUser);
      this.loggedINUser = data.response[0];
      this.$root.$emit('loggedINUserData', this.loggedINUser);
    }
  }
}
</script>

<style lang="scss" scoped>
</style>