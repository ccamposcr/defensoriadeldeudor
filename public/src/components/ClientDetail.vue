<template>
    <div>
        <p v-if="user.personalID"><strong>Identificaci&oacute;n</strong> {{ user.personalID }}</p>
        <p v-if="user.name"><strong>Nombre:</strong> <span class="user__name">{{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</span></p>
        <p v-if="user.phone" ><strong>Tel&eacute;fono:</strong> {{ user.phone }}</p>
        <p v-if="user.phone2" ><strong>Tel&eacute;fono 2:</strong> {{ user.phone2 }}</p>
        <p v-if="user.phone3" ><strong>Tel&eacute;fono 3:</strong> {{ user.phone3 }}</p>
        <p v-if="user.email"><strong>Email:</strong> {{ user.email }}</p>
        <p v-if="user.email2"><strong>Email 2:</strong> {{ user.email2 }}</p>
        <p v-if="user.email3"><strong>Email 3:</strong> {{ user.email3 }}</p>
        <p v-if="user.job"><strong>Ocupación:</strong> {{ user.job }}</p>
        <p v-if="user.address"><strong>Direcci&oacute;n:</strong> {{ user.address }}</p>
        <p v-if="user.role"><strong>Rol:</strong> {{ user.role }}</p>
  </div>
</template>

<script>

export default {
  name: 'ClientDetail',
  props: ["user"],
  components: {},
  data () {
    return {}
  },
  methods: {
  }
}
</script>

<style lang="scss">
.v-application{
  .list{
    &__user{
      p{
        margin-bottom: 0;
      }
    }
  }
}
</style>
