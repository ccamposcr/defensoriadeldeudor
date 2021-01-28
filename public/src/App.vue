<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
import Clientlist from "./components/Clientlist.vue";

export default {
  name: 'app',
  components: { Clientlist },
  data () {
    return {
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
