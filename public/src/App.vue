<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>

import Client from './components/Client.vue';
import Appointment from './components/Appointment.vue';

export default {
  name: 'app',
  components: {Client, Appointment },
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

<style lang="scss" scoped>

</style>
