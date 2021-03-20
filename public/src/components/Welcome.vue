<template>
   <div>Bienvenido: {{loggedINUser.name}} {{loggedINUser.lastName1}} {{loggedINUser.lastName2}}</div>
</template>
 

<script>
import repositories from '../repositories';

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
      const data = await repositories.getClientBy('personalID', loggedINUser);
      this.loggedINUser = data.response[0];
      this.$root.$emit('loggedINUserData', this.loggedINUser);
    }
  }
}
</script>

<style lang="scss" scoped>
</style>