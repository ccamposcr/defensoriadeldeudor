<template>
    <div>
        <b-modal id="bv-modal-legal-case-form" hide-footer novalidate="true" @hide="onCloseLegalForm">
            <template #modal-title>
            Caso Legal
            </template>
            <div class="d-block">
              <div v-if="errors.length">
                  <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
                  <ul class="errors-list">
                      <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
                  </ul>
              </div>
              <b-form class="legal__case-form">
                  <input type="hidden" v-model="legalCaseForm.id">
                  <b-form-group label-for="internalCode" label="Número de expediente">
                    <b-form-input v-model="legalCaseForm.internalCode" type="text" class="form-control" id="internalCode" placeholder="Número de expediente"></b-form-input>
                  </b-form-group>
                  <b-form-group label-for="code" label="Código Interno">
                    <b-form-input v-model="legalCaseForm.code" type="text" class="form-control" id="code" placeholder="Código interno"></b-form-input>
                  </b-form-group>
                  <b-form-group label-for="subject" label="Naturaleza de expediente">
                    <b-form-select id="subject" v-model="legalCaseForm.subjectID" :options="staticData.subjectList" value-field="id" text-field="subject"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="judicialStatus" label="Estado Judicial">
                    <b-form-select id="judicialStatus" v-model="legalCaseForm.judicialStatusID" :options="staticData.judicialStatusList" value-field="id" text-field="judicialStatus"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="administrativeStatus" label="Estado Administrativo">
                    <b-form-select id="administrativeStatus" v-model="legalCaseForm.administrativeStatusID" :options="staticData.administrativeStatusList" value-field="id" text-field="administrativeStatus"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="location" label="Ubicación del expediente">
                    <b-form-select id="location" v-model="legalCaseForm.locationID" :options="staticData.locationList" value-field="id" text-field="location"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="note" label="Nueva nota (opcional)">
                    <b-form-textarea id="note" v-model="legalCaseForm.note" placeholder="Agregue una nota" rows="3" max-rows="6"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-for="totalAmount" label="Monto del caso">
                    <b-form-input v-model="legalCaseForm.totalAmount" type="text" class="form-control" id="totalAmount" placeholder="Monto del caso"></b-form-input>
                  </b-form-group>

                  <div class="case-form__payments-group">
                    <b-form-group label-for="nextPaymentDay" label="Agregar múltiples fechas de pago (opcional)">
                      <b-form-datepicker :min="today" id="nextPaymentDay" v-model="nextPaymentDay" locale="es"></b-form-datepicker>
                    </b-form-group>

                    <b-form-group>
                      <b-button :disabled="!nextPaymentDay" @click.prevent="addNewPaymentDay" variant="primary">
                        Agregar fecha
                      </b-button>
                    </b-form-group>
                  </div>

                  <b-form-group label="Listado fechas de pago" v-if="paymentDates.dates.length">
                      <ul class="case-form__list">
                          <li class="list__date" :key="index" v-for="(item, index) in paymentDates.dates">
                            <span><strong>Fecha de pago:</strong> {{ item.date }}</span>
                            <b-button v-if="!editingLegalCase" @click.prevent="removePaymentDate(index)" variant="danger">
                              Eliminar
                            </b-button>
                          </li>
                      </ul>
                  </b-form-group>
                  
                  <b-button :disabled="showLoader" v-if="!editingLegalCase" @click.prevent="checkForm(function(){setNewLegalCase()})" type="submit" variant="primary">
                    Crear
                  </b-button>
                  <b-button :disabled="showLoader" v-if="editingLegalCase" @click.prevent="checkForm(function(){setEditedLegalCase()})" type="submit" variant="primary">
                    Guardar
                  </b-button>
                  <b-button @click.prevent="closeLegalForm" variant="danger">Cancelar</b-button>
              </b-form>

              <div v-if="errors.length">
                <p class="label label-danger">Por favor, corrija el(los) error(es) del formulario</p>
              </div>

            </div>
        </b-modal>
    </div>
</template>
 

<script>
import repositories from '../repositories';

export default {
  name: 'ModalLegalCaseForm',
  props: ["showLoader", "paymentDates", "legalCaseForm", "editingLegalCase", "staticData", "legalCaseUserId", "today"],
  data () {
    return {
      errors:[],
      nextPaymentDay: null
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.legalCaseForm.internalCode){
            this.errors.push("Ingrese el número del expediente");
        }
        if(!this.legalCaseForm.code){
            this.errors.push("Ingrese el código interno");
        }
        if(!this.legalCaseForm.subjectID){
            this.errors.push("Seleccione la naturaleza del expediente");
        }
        if(!this.legalCaseForm.judicialStatusID){
            this.errors.push("Seleccione el estado judicial");
        }
        if(!this.legalCaseForm.administrativeStatusID){
            this.errors.push("Seleccione el estado administrativo");
        }
        if(!this.legalCaseForm.locationID){
            this.errors.push("Seleccione la ubicación del expediente");
        }
        if(!this.legalCaseForm.totalAmount){
            this.errors.push("Ingrese un monto del caso");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearLegalCaseForm: function(){
        for(const item in this.legalCaseForm){
            this.legalCaseForm[item] = null;
        }
        this.paymentDates.dates = [];
        this.paymentDates.legalCaseID = null;
        this.legalCaseForm.totalAmount = 0;
        this.legalCaseForm.inUse = '0';
        this.errors = [];
    },
    onCloseLegalForm: async function(){
      if( this.legalCaseForm.id ){
        this.$emit('update:showLoader', true);
        await repositories.updateLegalCaseIsInUse({'id': this.legalCaseForm.id, 'inUse': 0});
        this.$emit('update:showLoader', false);
      }
      this.clearLegalCaseForm();
    },
    closeLegalForm: async function(){
      this.$bvModal.hide('bv-modal-legal-case-form');
    },
    setNewLegalCase: async function(){
        this.$emit('update:showLoader', true);
        const userID = this.legalCaseUserId;
        const data = await repositories.addNewLegalCase(userID, this.legalCaseForm);

        const legalCaseNote = {};
        legalCaseNote.legalCaseID = data.legalCaseID;
        legalCaseNote.userID = loggedINUserID;
        legalCaseNote.note = this.legalCaseForm.note;

        if( legalCaseNote.note ){
          await repositories.addLegalCaseNote(legalCaseNote);
        }

        if( this.paymentDates.dates.length ){
          this.paymentDates.legalCaseID = data.legalCaseID;
          const paymentDatesStr = {
            'legalCaseID': this.paymentDates.legalCaseID,
            'dates': JSON.stringify(this.paymentDates.dates)
          }
          await repositories.addPaymentDates(paymentDatesStr);
        }

        this.$parent.renderLegalCases('userID', userID, userID); 
        this.closeLegalForm();
        this.$emit('update:showLoader', false);
    },
    setEditedLegalCase: async function(){
        this.$emit('update:showLoader', true);
        const userID = this.legalCaseUserId;

        await repositories.editLegalCase(this.legalCaseForm);

        await repositories.updateLegalCaseIsInUse({'id': this.legalCaseForm.id, 'inUse': 0});
        
        this.$parent.renderLegalCases('userID', userID, userID);

        const legalCaseNote = {};
        legalCaseNote.legalCaseID = this.legalCaseForm.legalCaseID;
        legalCaseNote.userID = loggedINUserID;
        legalCaseNote.note = this.legalCaseForm.note;

        if( legalCaseNote.note ){
          await repositories.addLegalCaseNote(legalCaseNote);
          this.$parent.renderLegalCaseNotes(legalCaseNote.legalCaseID);
        }

        if( this.paymentDates.dates.length ){
          this.paymentDates.legalCaseID = this.legalCaseForm.legalCaseID;
          const validArrayDates = this.paymentDates.dates.filter(elm => !elm.id );

          const paymentDatesStr = {
            'legalCaseID': this.paymentDates.legalCaseID,
            'dates': JSON.stringify(validArrayDates)
          }
          await repositories.addPaymentDates(paymentDatesStr);
          this.$parent.renderLegalPaymentDates(this.paymentDates.legalCaseID);
        }

        this.closeLegalForm();
        this.$emit('update:showLoader', false);
    },
    addNewPaymentDay: function(){
      if (this.nextPaymentDay){
        this.paymentDates.dates.push({'date': this.nextPaymentDay});
        this.nextPaymentDay = null;
      }
    },
    removePaymentDate: function(index){
      this.paymentDates.dates.splice(index, 1);
    }
  }
}
</script>

<style lang="scss" scoped>
.case-form{
  &__list{
    list-style-type: none;
  }
  &__payments-group{
    display: flex;
    justify-content: space-between;
    align-items: center;
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