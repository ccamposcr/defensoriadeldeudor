<template>
  <v-app>
    <router-view></router-view>
  </v-app>
</template>

<script>

import Client from './components/Client.vue';
import Inicio from './components/Inicio.vue';

export default {
  name: 'app',
  components: {Client, Inicio },
  data () {
    return {
    }
  },
  methods: {
    getTodayDate: function(){
      const now = new Date();
      const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      today.setMonth(today.getMonth() - 2)
      today.setDate(15)
      return new Date(today);
    }
  }
}
</script>

<style lang="scss">
  @import "./scss/navigation.scss";
</style>
