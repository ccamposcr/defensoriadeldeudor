<template>
    <div>

        <!-- PAYMENT DATES -->
        <div v-if="$store.getters.paymentDates(userID)">
            
            <ul class="legal-cases__payment-dates">
            <li class="payment-dates__date" v-bind:key="paymentDate.id" v-for="paymentDate in $store.getters.paymentDates(userID)">
                <p v-if="paymentDate.date"><strong>Fecha de pago:</strong> {{ paymentDate.date }}</p>
                <b-button v-if="checkAccessList('editar caso')" @click.prevent="removePaymentDate(paymentDate.id, userID)" variant="danger">
                Eliminar
                </b-button>
            </li>
            </ul>
            <span class="label-danger" v-if="$store.getters.paymentDates(userID) && !$store.getters.paymentDates(userID).length">No hay fechas de pago</span>
        </div>
        <!-- END PAYMENT DATES -->

  </div>
</template>

<script>
import repositories from '../repositories';
import global from '../global';

export default {
  name: 'UserPaymentDates',
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
    removePaymentDate: async function(paymentDateID, userID){
      this.$store.commit('setShowLoader', true);

      const data = {};
      data.id = paymentDateID;
      //OK
      await repositories.deletePaymentDate(data);
      await this.renderPaymentDates(userID);

      this.$store.commit('setShowLoader', false);
    },
    renderPaymentDates: async function(userID){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getPaymentDatesBy', {searchBy: 'userID', userID: userID});

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
