<template>
    <div>
        <p v-if="user.personalID && user.personalID != null"><strong>Identificaci&oacute;n</strong> {{ user.personalID }}</p>
        <p v-if="user.name && user.name != null"><strong>Nombre:</strong> <span class="user__name">{{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</span></p>
        <p v-if="user.phone && user.phone != null" ><strong>Tel&eacute;fono:</strong> {{ user.phone }}</p>
        <p v-if="user.phone2 && user.phone2 != null" ><strong>Tel&eacute;fono 2:</strong> {{ user.phone2 }}</p>
        <p v-if="user.phone3 && user.phone3 != null" ><strong>Tel&eacute;fono 3:</strong> {{ user.phone3 }}</p>
        <p v-if="user.email && user.email != null"><strong>Email:</strong> {{ user.email }}</p>
        <p v-if="user.email2 && user.email2 != null"><strong>Email 2:</strong> {{ user.email2 }}</p>
        <p v-if="user.email3 && user.email3 != null"><strong>Email 3:</strong> {{ user.email3 }}</p>
        <p v-if="user.job && user.job != null"><strong>Ocupación:</strong> {{ user.job }}</p>
        <p v-if="user.address && user.address != null"><strong>Direcci&oacute;n:</strong> {{ user.address }}</p>
        <p v-if="user.role && user.role != null"><strong>Rol:</strong> {{ user.role }}</p>
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

<style lang="scss" scoped>
  .list{
    &__user{
      p{
        margin-bottom: 0;
      }
    }
  }
</style>
