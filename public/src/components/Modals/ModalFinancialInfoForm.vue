<template>
    <div>
        <b-modal id="bv-modal-financial-info-form" hide-footer novalidate="true" @hide="onCloseFinancialForm">
            <template #modal-title>
            Información financiera
            </template>
            <div class="d-block">
              <div v-if="errors.length">
                  <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
                  <ul class="errors-list">
                      <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
                  </ul>
              </div>
              <b-form class="financial-form">
                  <input type="hidden" v-model="$store.getters.financialForm.id">
                  
                  <b-form-group label-for="totalAmount" label="Monto del contrato">
                    <b-form-input v-model="$store.getters.financialForm.totalAmount" type="text" class="form-control" id="totalAmount" placeholder="Monto del contrato"></b-form-input>
                  </b-form-group>
                  <b-form-group label-for="administrativeStatus" label="Estado Administrativo">
                    <b-form-select id="administrativeStatus" v-model="$store.getters.financialForm.administrativeStatusID" :options="$store.getters.staticData.administrativeStatusList" value-field="id" text-field="administrativeStatus"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="propertyNumber" label="Número del bien (opcional)">
                    <b-form-input v-model="$store.getters.financialForm.propertyNumber" type="text" class="form-control" id="propertyNumber" placeholder="Número del bien"></b-form-input>
                  </b-form-group>

                  <div class="financial-form__payments-group">
                    <h5>Fechas de pagos manuales</h5>
                    <p>Seleccione una fecha y presione el botón de "Agregar fecha", haga lo mismo con la cantidad de fechas que necesite ingresar. </p>
                    <b-form-group label-for="paymentDaySelected" label="Seleccione la fecha">
                      <b-form-datepicker id="paymentDaySelected" v-model="paymentDaySelected" locale="es"></b-form-datepicker>
                    </b-form-group>

                    <b-form-group>
                      <b-button :disabled="!paymentDaySelected" @click.prevent="addNewPaymentDay" variant="primary">
                        Agregar fecha
                      </b-button>
                    </b-form-group>
                  </div>

                  <div class="financial-form__payments-group">
                    <h5>Generar fechas de pagos recurrentes</h5>
                    <p>Seleccione solamente una fecha, luego ingrese la cantidad de meses recurrentes y presion el botón "Generar fechas" para generar pagos recurrentes el mismo día durante X cantidad de meses.</p>
                    <b-form-group label-for="paymentDaySelectedForRecurring" label="Seleccione la fecha">
                      <b-form-datepicker id="paymentDaySelectedForRecurring" v-model="paymentDaySelectedForRecurring" locale="es"></b-form-datepicker>
                    </b-form-group>
                    <b-form-group label="Cantidad de meses recurrentes">
                      <b-form-input v-model="numberMonths" min="1" max="48" type="number" class="form-control" id="numberMonths" placeholder="Ingrese la cantidad de meses a generar"></b-form-input>
                    </b-form-group>
                    <b-form-group>
                      <b-button :disabled="!paymentDaySelectedForRecurring || numberMonths <= 0" @click.prevent="generateRecurringPayments" variant="primary">
                        Generar fechas
                      </b-button>
                    </b-form-group>
                  </div>

                  <b-form-group label="Listado fechas de pago" v-if="$store.getters.paymentDatesForm.dates.length">
                      <b-button v-if="!$store.getters.editingFinancialInfo && $store.getters.paymentDatesForm.dates.length" @click.prevent="removeAllPayments" variant="danger">
                        Quitar todas
                      </b-button>
                      <ul class="financial__list">
                          <li class="list__date" :key="index" v-for="(item, index) in $store.getters.paymentDatesForm.dates">
                            <span><strong>Fecha de pago:</strong> {{ item.paymentDateAlert }}</span>
                            <b-button v-if="!$store.getters.editingFinancialInfo" @click.prevent="removePaymentDate(index)" variant="danger">
                              Quitar
                            </b-button>
                          </li>
                      </ul>
                  </b-form-group>
                  
                  
                  <b-button :disabled="$store.getters.showLoader" v-if="!$store.getters.editingFinancialInfo" @click.prevent="checkForm(function(){setNewFinancialInfo()})" type="submit" variant="primary">
                    Crear
                  </b-button>
                  <b-button :disabled="$store.getters.showLoader" v-if="$store.getters.editingFinancialInfo" @click.prevent="checkForm(function(){setEditedFinancialInfo()})" type="submit" variant="primary">
                    Guardar
                  </b-button>
                  <b-button @click.prevent="closeFinancialForm" variant="danger">Cancelar</b-button>
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
  name: 'ModalFinancialInfoForm',
  data () {
    return {
      errors:[],
      paymentDaySelected: '',
      paymentDaySelectedForRecurring: '',
      numberMonths: 0,
      paymentsArray: []
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.$store.getters.financialForm.administrativeStatusID){
            this.errors.push("Seleccione el estado administrativo");
        }
        if(!this.$store.getters.financialForm.totalAmount){
            this.errors.push("Ingrese el monto de contrato");
        }
        if(!this.$store.getters.paymentDatesForm.dates.length){
            this.errors.push("Ingrese la o las fechas de pago");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearFinancialForm: function(){
        const data = {
          id: '',
          totalAmount: '',
          administrativeStatusID: '',
          propertyNumber: ''
        };
        this.$store.commit('setFinancialForm', data);
        this.errors = [];
        this.numberMonths = 0;
        this.paymentDaySelected = '';
        this.paymentDaySelectedForRecurring = '';
        this.paymentsArray = [];
        const tmpData = {
            financialContractID: '',
            dates: []
        }
        this.$store.commit('setPaymentDatesForm', tmpData);
    },
    onCloseFinancialForm: async function(){
      if( this.$store.getters.financialForm.id ){
        this.$store.commit('setShowLoader', true);
        
        await this.$store.dispatch('updateFinancialInfoIsInUse', {id: this.$store.getters.financialForm.id, inUse: 0});

        this.$store.commit('setShowLoader', false);
      }
      this.clearFinancialForm();
    },
    closeFinancialForm: async function(){
      this.$bvModal.hide('bv-modal-financial-info-form');
    },
    setNewFinancialInfo: async function(){
        this.$store.commit('setShowLoader', true);

        const userID = this.$store.getters.currentFinancialInfoUserId;
        //OK
        const data = await repositories.addFinancialContract(userID, this.$store.getters.financialForm);


        if( this.$store.getters.paymentDatesForm.dates.length ){
     
          const tmpData = {
            financialContractID: data.financialContractID,
            dates: this.$store.getters.paymentDatesForm.dates
          }
          this.$store.commit('setPaymentDatesForm', tmpData);

          const paymentDatesStr = {
            'financialContractID': this.$store.getters.paymentDatesForm.financialContractID,
            'dates': JSON.stringify(this.$store.getters.paymentDatesForm.dates)
          }
          //OK
          await repositories.addPaymentDates(paymentDatesStr);
        }

        //searchBy, userID, callback
        await this.$emit('renderFinancialInfo', {searchBy:'userID', value:userID, userID:userID});


        this.closeFinancialForm();
        this.$store.commit('setShowLoader', false);
    },
    setEditedFinancialInfo: async function(){
        this.$store.commit('setShowLoader', true);

        const userID = this.$store.getters.currentFinancialInfoUserId;

        //OK
        await repositories.editFinancialContract(this.$store.getters.financialForm);

        await this.$store.dispatch('updateFinancialInfoIsInUse', {id: this.$store.getters.financialForm.id, inUse: 0});

        //searchBy, userID, callback
        await this.$emit('renderFinancialInfo', {searchBy:'userID', value:userID, userID:userID});
        

        if( this.$store.getters.paymentDatesForm.dates.length ){

          const tmpData = {
            financialContractID: this.$store.getters.financialForm.financialContractID,
            dates: this.$store.getters.paymentDatesForm.dates
          }
          this.$store.commit('setPaymentDatesForm', tmpData);

          const dates = this.$store.getters.paymentDatesForm.dates;
          const validArrayDates = dates.filter(elm => !elm.id );

          const paymentDatesStr = {
            'financialContractID': this.$store.getters.paymentDatesForm.financialContractID,
            'dates': JSON.stringify(validArrayDates)
          }

          //OK
          await repositories.addPaymentDates(paymentDatesStr);
          await this.$emit('renderPaymentDates', this.$store.getters.paymentDatesForm.financialContractID);
          await this.$emit('sync');
        }

        this.closeFinancialForm();
        this.$store.commit('setShowLoader', false);
    },
    addNewPaymentDay: function(){
      this.paymentsArray = this.$store.getters.paymentDatesForm.dates;
      if (this.paymentDaySelected && !this.paymentsArray.some(e => e.paymentDateAlert === this.paymentDaySelected)){
        this.paymentsArray.push({'paymentDateAlert': this.paymentDaySelected});
        const tmpData = {
          financialContractID: this.$store.getters.paymentDatesForm.financialContractID,
          dates: this.paymentsArray
        }
        this.$store.commit('setPaymentDatesForm', tmpData);
      }
      this.paymentDaySelected = '';
    },
    removePaymentDate: function(index){
      this.paymentsArray.splice(index, 1);
      const tmpData = {
        financialContractID: this.$store.getters.paymentDatesForm.financialContractID,
        dates: this.paymentsArray
      }
      this.$store.commit('setPaymentDatesForm', tmpData);
    },
    removeAllPayments: function(){
      this.paymentsArray = [];
      const tmpData = {
        financialContractID: this.$store.getters.paymentDatesForm.financialContractID,
        dates: this.paymentsArray
      }
      this.$store.commit('setPaymentDatesForm', tmpData);
    },
    generateRecurringPayments: function(){
      this.paymentsArray = this.$store.getters.paymentDatesForm.dates;
      if(this.paymentDaySelectedForRecurring && this.numberMonths > 0){
        let datePointer = this.paymentDaySelectedForRecurring;
        for( let i = 0; i < this.numberMonths; i++ ){
          if( !this.paymentsArray.some(e => e.paymentDateAlert === datePointer) ){
            this.paymentsArray.push({'paymentDateAlert': datePointer});
          }
          datePointer = moment(datePointer + 'T00:00:00').add(1, 'month').format("YYYY-MM-DD");
        }
        const tmpData = {
          financialContractID: this.$store.getters.paymentDatesForm.financialContractID,
          dates: this.paymentsArray
        }
        this.$store.commit('setPaymentDatesForm', tmpData);
        this.paymentDaySelectedForRecurring = '';
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.financial-form{
  &__list{
    list-style-type: none;
  }
}

.list{
  &__date{
    padding: 10px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
}
</style>