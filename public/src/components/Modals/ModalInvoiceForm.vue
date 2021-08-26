<template>
    <div>
        <b-modal id="bv-modal-invoice-form" hide-footer novalidate="true" @hide="onCloseInvoiceForm">
            <template #modal-title>
            Establecer como pagado
            </template>
            <div class="d-block">
              <div v-if="errors.length">
                  <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
                  <ul class="errors-list">
                      <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
                  </ul>
              </div>
              <b-form class="financial-form">
                  <input type="hidden" v-model="$store.getters.invoiceForm.id">
                  
                  <b-form-group label-for="referenceNumber" label="Número de referencia">
                    <b-form-input v-model="$store.getters.invoiceForm.referenceNumber" type="text" class="form-control" id="referenceNumber" placeholder="Número de referencia"></b-form-input>
                  </b-form-group>
                  <b-form-group label-for="amountPaid" label="Monto pagado">
                    <b-form-input v-model="$store.getters.invoiceForm.amountPaid" type="text" class="form-control" id="amountPaid" placeholder="Monto pagado"></b-form-input>
                  </b-form-group>
                  
                  <b-button :disabled="$store.getters.showLoader" @click.prevent="checkForm(function(){setNewInvoiceInfo()})" type="submit" variant="primary">
                    Agregar
                  </b-button>
                  <b-button @click.prevent="closeInvoiceForm" variant="danger">Cancelar</b-button>
              </b-form>

              <div v-if="errors.length">
                <p class="label label-danger">Por favor, corrija el(los) error(es) del formulario</p>
              </div>

            </div>
        </b-modal>
    </div>
</template>
 

<script>
import repositories from '../../repositories';
import moment from 'moment';

export default {
  name: 'ModalInvoiceForm',
  data () {
    return {
      errors:[]
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.$store.getters.invoiceForm.referenceNumber){
            this.errors.push("Ingrese un número de referencia del pago");
        }
        if(!this.$store.getters.invoiceForm.amountPaid){
            this.errors.push("Ingrese el monto pagado");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearInvoiceForm: function(){
        const data = {
            id: '',
            paymentDateMade: '',
            referenceNumber: '',
            amountPaid: ''
        };
        this.$store.commit('setInvoiceForm', data);

    },
    onCloseInvoiceForm: async function(){
      this.clearInvoiceForm();
    },
    closeInvoiceForm: async function(){
      this.$bvModal.hide('bv-modal-invoice-form');
    },
    setNewInvoiceInfo: async function(){
        this.$store.commit('setShowLoader', true);

        const paymentDatesID = this.$store.getters.currentPaymentId;
        const financialContractID = this.$store.getters.currentFinancialInfoUserId;
        //OK
        await repositories.addInvoice(paymentDatesID, this.$store.getters.invoiceForm);

        await this.$emit('renderPaymentDates', financialContractID);


        this.closeInvoiceForm();
        this.$store.commit('setShowLoader', false);
    }
  }
}
</script>

<style lang="scss">
</style>