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
  }
}
</script>

<style lang="scss" scoped>

</style>
