<template>
  <v-app>
    <v-main>
      <router-view></router-view>
    </v-main>
    <div v-if="$store.getters.showLoader" class="loader">
      <b-spinner large></b-spinner>
    </div>
  </v-app>
</template>

<script>

export default {
  name: 'app',
  components: { },
  data () {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="scss">
  @import "./scss/global.scss";
  @import "./scss/navigation.scss";
  @import "./scss/welcome.scss";
  @import "./scss/login.scss";
</style>
