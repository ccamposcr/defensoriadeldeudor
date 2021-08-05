<template>
    <div>
        <div v-if="$store.getters.paymentDatesBy(financial.financialContractID)">
            
            <ul class="detail__list">
              <li class="list__option" v-bind:key="paymentDate.id" v-for="paymentDate in $store.getters.paymentDatesBy(financial.financialContractID)">
                  <p v-if="paymentDate.paymentDateAlert"><strong>Fecha de pago:</strong> {{ paymentDate.paymentDateAlert }}</p>
                  <p v-if="paymentDate.paymentDateMade"><strong>Pagado:</strong> {{ paymentDate.paymentDateMade }}</p>
                  <p v-if="paymentDate.referenceNumber"><strong>Número de refencia:</strong> {{ paymentDate.referenceNumber }}</p>
                  <p v-if="paymentDate.amountPaid"><strong>Monto pagado:</strong> {{ paymentDate.amountPaid }}</p>
                  <p v-if="$store.getters.COUNTPaymentDatesBy(financial.financialContractID)"><strong>Monto a pagar:</strong> {{ (financial.totalAmount / $store.getters.COUNTPaymentDatesBy(financial.financialContractID)).toFixed(2) }}</p>
                  <div class="detail__options">
                    <b-button v-if="$emit('checkAccessList', 'editar info financiera')" @click.prevent="$emit('removePaymentDate', {paymentDateID:paymentDate.id, financialContractID:financial.financialContractID})" variant="danger">
                      Eliminar
                    </b-button>
                    <b-button v-if="$emit('checkAccessList', 'agregar info financiera')" @click="$emit('showAddInvoiceFom', {paymentDateID:paymentDate.id})" variant="success">Ingresar pago del cliente</b-button>
                  </div>
              </li>
            </ul>
            <span class="label-danger" v-if="$store.getters.paymentDatesBy(financial.financialContractID) && !$store.getters.paymentDatesBy(financial.financialContractID).length">No hay fechas de pago</span>
        </div>
  </div>
</template>

<script>

export default {
  name: 'PaymentDates',
  props: ["financial"],
  components: {},
  data () {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="scss">
</style>
