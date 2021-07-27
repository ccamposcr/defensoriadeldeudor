<template>
    <div>
        <p v-if="user.personalID"><strong>Identificaci&oacute;n</strong> {{ user.personalID }}</p>
        <p v-if="user.name"><strong>Nombre:</strong> <span class="user__name">{{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</span></p>
  </div>
</template>

<script>

export default {
  name: 'ClientDetailMin',
  props: ["user"],
  components: {},
  data () {
    return {}
  },
  methods: {
  }
}
</script>

<style lang="scss" scoped>
  .list{
    &__user{
      p{
        margin-bottom: 0;
      }
    }
  }
</style>
