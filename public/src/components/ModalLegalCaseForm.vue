<template>
    <div>
        <b-modal id="bv-modal-legal-case-form" hide-footer novalidate="true" @hide="cancelLegalForm">
            <template #modal-title>
            Caso Legal
            </template>
            <div class="d-block">
              <div v-if="errors.length">
                  <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
                  <ul>
                      <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
                  </ul>
              </div>
              <b-form class="user__case-form">
                  <input type="hidden" v-model="legalCaseForm.id">
                  <b-form-group label-for="internalCode" label="Número de expediente">
                    <b-form-input v-model="legalCaseForm.internalCode" type="text" class="form-control" id="internalCode" placeholder="Número de expediente" :disabled="editingLegalCase"></b-form-input>
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
                  <b-form-group label-for="note" label="Nueva nota">
                    <b-form-textarea id="note" v-model="legalCaseForm.note" placeholder="Agregue una nota" rows="3" max-rows="6"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-for="nextNotification" label="Fecha de siguiete pago">
                    <b-form-datepicker :min="today" id="nextNotification" v-model="legalCaseForm.nextNotification" locale="es"></b-form-datepicker>
                  </b-form-group>
                  <b-button v-if="!editingLegalCase" @click.prevent="checkForm(function(){setNewLegalCase()})" type="submit" variant="primary">Agregar</b-button>
                  <b-button v-if="editingLegalCase" @click.prevent="checkForm(function(){setEditedLegalCase()})" type="submit" variant="primary">Guardar</b-button>
                  <b-button @click.prevent="cancelLegalForm" variant="danger">Cancelar</b-button>
              </b-form>

            </div>
        </b-modal>
    </div>
</template>
 

<script>
import repositories from '../repositories';

export default {
  name: 'ModalLegalCaseForm',
  props: ["legalCaseForm", "editingLegalCase", "staticData", "legalCaseUserId", "today"],
  data () {
    return {
      errors:[],
      loggedINUserData: null
    }
  },
  mounted(){
    this.$root.$on('loggedINUserData', data => {
        this.loggedINUserData = data;
    });
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.legalCaseForm.internalCode){
            this.errors.push("Ingrese el número del expediente");
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
        if(!this.legalCaseForm.nextNotification){
            this.errors.push("Ingrese una fecha de alerta válida");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearLegalCaseForm: function(){
        for(const item in this.legalCaseForm){
            this.legalCaseForm[item] = null;
        }
        this.errors = [];
    },
    cancelLegalForm: function(){
        this.clearLegalCaseForm();
        this.$bvModal.hide('bv-modal-legal-case-form');
    },
    setNewLegalCase: async function(){
        const userID = this.legalCaseUserId;
        const data = await repositories.addNewLegalCase(userID, this.legalCaseForm);

        const legalCaseNote = {};
        
        legalCaseNote['legalCaseID'] = data.legalCaseID;
        legalCaseNote['userID'] = this.loggedINUserData['id'];
        legalCaseNote['note'] = this.legalCaseForm['note'];

        if( legalCaseNote['note'] ){
          await repositories.addLegalCaseNote(legalCaseNote);
        }

        this.$parent.showLegalCases(userID);
        this.clearLegalCaseForm();
        this.$bvModal.hide('bv-modal-legal-case-form');
    },
    setEditedLegalCase: async function(){
        const userID = this.legalCaseUserId;

        await repositories.editLegalCase(this.legalCaseForm);
        this.$parent.showLegalCases(userID);

        const legalCaseNote = {};
        legalCaseNote['legalCaseID'] = this.legalCaseForm['legalCaseID'];
        legalCaseNote['userID'] = this.loggedINUserData['id'];
        legalCaseNote['note'] = this.legalCaseForm['note'];

        if( legalCaseNote['note'] ){
          await repositories.addLegalCaseNote(legalCaseNote);
          this.$parent.showLegalCaseNotes(legalCaseNote['legalCaseID']);
        }

        this.cancelLegalForm();
    }
  }
}
</script>

<style lang="scss" scoped>
</style>