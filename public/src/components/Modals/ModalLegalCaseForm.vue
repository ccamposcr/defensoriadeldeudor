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
                  <input type="hidden" v-model="$store.getters.legalCaseForm.id">
                  <b-form-group label-for="internalCode" label="Número de expediente">
                    <b-form-input v-model="$store.getters.legalCaseForm.internalCode" type="text" class="form-control" id="internalCode" placeholder="Número de expediente"></b-form-input>
                  </b-form-group>
                  <b-form-group label-for="code" label="Código Interno">
                    <b-form-input v-model="$store.getters.legalCaseForm.code" type="text" class="form-control" id="code" placeholder="Código interno"></b-form-input>
                  </b-form-group>
                  <b-form-group label-for="subject" label="Naturaleza de expediente">
                    <b-form-select id="subject" v-model="$store.getters.legalCaseForm.subjectID" :options="$store.getters.staticData.subjectList" value-field="id" text-field="subject"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="judicialStatus" label="Estado Judicial">
                    <b-form-select id="judicialStatus" v-model="$store.getters.legalCaseForm.judicialStatusID" :options="$store.getters.staticData.judicialStatusList" value-field="id" text-field="judicialStatus"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="location" label="Ubicación del expediente">
                    <b-form-select id="location" v-model="$store.getters.legalCaseForm.locationID" :options="$store.getters.staticData.locationList" value-field="id" text-field="location"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="note" label="Nueva nota (opcional)">
                    <b-form-textarea id="note" v-model="$store.getters.legalCaseForm.note" placeholder="Agregue una nota" rows="3" max-rows="6"></b-form-textarea>
                  </b-form-group>
                  
                  <b-button :disabled="$store.getters.showLoader" v-if="!$store.getters.editingLegalCase" @click.prevent="checkForm(function(){setNewLegalCase()})" type="submit" variant="primary">
                    Crear
                  </b-button>
                  <b-button :disabled="$store.getters.showLoader" v-if="$store.getters.editingLegalCase" @click.prevent="checkForm(function(){setEditedLegalCase()})" type="submit" variant="primary">
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
import repositories from '../../repositories';

export default {
  name: 'ModalLegalCaseForm',
  data () {
    return {
      errors:[]
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.$store.getters.legalCaseForm.internalCode){
            this.errors.push("Ingrese el número del expediente");
        }
        if(!this.$store.getters.legalCaseForm.code){
            this.errors.push("Ingrese el código interno");
        }
        if(!this.$store.getters.legalCaseForm.subjectID){
            this.errors.push("Seleccione la naturaleza del expediente");
        }
        if(!this.$store.getters.legalCaseForm.judicialStatusID){
            this.errors.push("Seleccione el estado judicial");
        }
        if(!this.$store.getters.legalCaseForm.locationID){
            this.errors.push("Seleccione la ubicación del expediente");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearLegalCaseForm: function(){
        const data = {
          id: '',
          internalCode: '',
          code: '',
          subjectID: '',
          judicialStatusID: '',
          locationID: '',
          note: ''
        };
        this.$store.commit('setLegalCaseForm', data);
        this.errors = [];
    },
    onCloseLegalForm: async function(){
      if( this.$store.getters.legalCaseForm.id ){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('updateLegalCaseIsInUse', {id: this.$store.getters.legalCaseForm.id, inUse: 0});

        this.$store.commit('setShowLoader', false);
      }
      this.clearLegalCaseForm();
    },
    closeLegalForm: async function(){
      this.$bvModal.hide('bv-modal-legal-case-form');
    },
    setNewLegalCase: async function(){
        this.$store.commit('setShowLoader', true);
        const userID = this.$store.getters.currentLegalCaseUserId;
        //OK
        const data = await repositories.addNewLegalCase(userID, this.$store.getters.legalCaseForm);

        const legalCaseNote = {};
        legalCaseNote.legalCaseID = data.legalCaseID;
        legalCaseNote.userID = loggedINUserID;
        legalCaseNote.note = this.$store.getters.legalCaseForm.note;

        if( legalCaseNote.note ){
          //Ok
          await repositories.addLegalCaseNote(legalCaseNote);
        }

        //searchBy, value, userID, callback
        await this.$emit('renderLegalCases', {searchBy:'userID', value:userID, userID:userID});

        this.closeLegalForm();
        this.$store.commit('setShowLoader', false);
    },
    setEditedLegalCase: async function(){
        this.$store.commit('setShowLoader', true);
        const userID = this.$store.getters.currentLegalCaseUserId;

        //OK
        await repositories.editLegalCase(this.$store.getters.legalCaseForm);

        await this.$store.dispatch('updateLegalCaseIsInUse', {id: this.$store.getters.legalCaseForm.id, inUse: 0});

        //searchBy, value, userID, callback
        await this.$emit('renderLegalCases', {searchBy:'userID', value:userID, userID:userID});
        
        const legalCaseNote = {};
        legalCaseNote.legalCaseID = this.$store.getters.legalCaseForm.legalCaseID;
        legalCaseNote.userID = loggedINUserID;
        legalCaseNote.note = this.$store.getters.legalCaseForm.note;

        if( legalCaseNote.note ){
          //OK
          await repositories.addLegalCaseNote(legalCaseNote);
          await this.$emit('renderLegalCaseNotes', legalCaseNote.legalCaseID);
        }

        this.closeLegalForm();
        this.$store.commit('setShowLoader', false);
    }
  }
}
</script>

<style lang="scss" scoped>
.case-form{
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