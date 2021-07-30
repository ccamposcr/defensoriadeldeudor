<template>
    <div>
        <p v-if="financial.totalAmount"><strong>Monto total:</strong> {{ financial.totalAmount }}</p>
        <p v-if="financial.administrativeStatus"><strong>Estado:</strong> {{ financial.administrativeStatus }}</p>
        <p v-if="financial.dateCreated"><strong>Fecha creación:</strong> {{ financial.dateCreated }}</p>
        <p v-if="financial.propertyNumber"><strong>Número de propiedad:</strong> {{ financial.propertyNumber }}</p>
        <div class="detail__options">
            <b-button v-if="$emit('checkAccessList', 'editar info financiera')" @click="$emit('fillEditFinancialForm', financial.financialContractID, user.id)" variant="info">Editar Información Financiera</b-button>
            <b-form-group v-if="financial.inUse == '1' && $emit('checkAccessList','administrar')" label="Finaciero bloqueado -> *Precaución puede estar siendo editado por algún usuario">
                <b-button @click.prevent="$emit('unblockFinancialInfo', financial.financialContractID, user.id)" variant="danger">Desbloquear</b-button>
            </b-form-group>
        </div>
    </div>
</template>

<script>

export default {
  name: 'FinancialDetail',
  props: ["financial", "user"],
  components: {},
  data () {
    return {}
  },
  methods: {
  }
}
</script>

<style lang="scss">
</style>
