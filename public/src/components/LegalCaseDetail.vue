<template>
    <div>
        <p v-if="legalCase.internalCode"><strong>Número de expediente:</strong> {{ legalCase.internalCode }}</p>
        <p v-if="legalCase.code"><strong>Código interno:</strong> {{ legalCase.code }}</p>
        <p v-if="legalCase.subject"><strong>Naturaleza de expediente:</strong> {{ legalCase.subject }}</p>
        <p v-if="legalCase.judicialStatus"><strong>Estado judicial:</strong> {{ legalCase.judicialStatus }}</p>
        <p v-if="legalCase.location"><strong>Ubicación del expediente:</strong> {{ legalCase.location }}</p>
  </div>
</template>

<script>

export default {
  name: 'LegalCaseDetail',
  props: ["legalCase"],
  components: {},
  data () {
    return {}
  },
  methods: {
  }
}
</script>

<style lang="scss" scoped>
  .legal-cases{
    &__case{
      p{
          margin-bottom: 0;
      }
    }
  }
</style>
