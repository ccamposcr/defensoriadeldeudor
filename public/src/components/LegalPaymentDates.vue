<template>
    <div>

        <!-- LEGAL PAYMENT DATES -->
        <div v-if="$store.getters.legalPaymentDates(legalCase.legalCaseID)">
            
            <ul class="legal-cases__payment-dates">
            <li class="payment-dates__date" v-bind:key="legalPaymentDate.id" v-for="legalPaymentDate in $store.getters.legalPaymentDates(legalCase.legalCaseID)">
                <p v-if="legalPaymentDate.date"><strong>Fecha de pago:</strong> {{ legalPaymentDate.date }}</p>
                <b-button v-if="checkAccessList('editar caso')" @click.prevent="removePaymentDate(legalPaymentDate.id, legalCase.legalCaseID)" variant="danger">
                Eliminar
                </b-button>
            </li>
            </ul>
            <span class="label-danger" v-if="$store.getters.legalPaymentDates(legalCase.legalCaseID) && !$store.getters.legalPaymentDates(legalCase.legalCaseID).length">No hay fechas de pago</span>
        </div>
        <!-- END LEGAL PAYMENT DATES -->

  </div>
</template>

<script>

import global from '../global';

export default {
  name: 'LegalPaymentDates',
  props: ["legalCase"],
  components: {},
  data () {
    return {
    }
  },
  methods: {
    checkAccessList: function(action){
      return global.checkAccessList(action);
    },
    removePaymentDate: async function(legalPaymentDateID, legalCaseID){
      this.$store.commit('setShowLoader', true);

      const data = {};
      data.id = legalPaymentDateID;
      //OK
      await repositories.deletePaymentDate(data);
      await this.renderLegalPaymentDates(legalCaseID);

      this.$store.commit('setShowLoader', false);
    },
    renderLegalPaymentDates: async function(legalCaseID){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getLegalPaymentDatesBy', {searchBy: 'legalCaseID', legalCaseID: legalCaseID});

      this.$store.commit('setShowLoader', false);
    }
  }
}
</script>

<style lang="scss" scoped>
    .legal-cases{
        &__payment-dates{
            list-style-type: none;
            padding: 0;
            margin-top: 30px;
            background-color: #e6e5e5;
        }
    }
    .payment-dates__date{
      padding: 15px;
      border-bottom: 1px solid gray;
      &:last-child{
        border-bottom: none;
      }
    }
    .payment-dates__date{
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
</style>
