<template>
    <div>
        <!-- LEGAL CASES -->
        <div v-if="legalCases[user.id]">
        <ul class="user__legal-cases">
            <li class="legal-cases__case" v-bind:key="legalCase.id" v-for="legalCase in legalCases[user.id]">
            <p v-if="legalCase.internalCode && legalCase.internalCode != null"><strong>Número de expediente:</strong> {{ legalCase.internalCode }}</p>
            <p v-if="legalCase.code && legalCase.code != null"><strong>Código interno:</strong> {{ legalCase.code }}</p>
            <p v-if="legalCase.subject && legalCase.subject != null"><strong>Naturaleza de expediente:</strong> {{ legalCase.subject }}</p>
            <p v-if="legalCase.judicialStatus && legalCase.judicialStatus != null"><strong>Estado judicial:</strong> {{ legalCase.judicialStatus }}</p>
            <p v-if="legalCase.administrativeStatus && legalCase.administrativeStatus != null"><strong>Estado administrativo:</strong> {{ legalCase.administrativeStatus }}</p>
            <p v-if="legalCase.location && legalCase.location != null"><strong>Ubicación del expediente:</strong> {{ legalCase.location }}</p>
            <p v-if="legalCase.totalAmount && legalCase.totalAmount != null"><strong>Monto del caso:</strong> {{legalCase.totalAmount}}</p>
            <div class="case__options">
                <b-button v-if="checkAccessList('editar caso')" @click="fillEditLegalCaseForm(legalCase.legalCaseID, user.id)" variant="info">Editar caso</b-button>
                <b-button :disabled="showLoader" @click="renderLegalCaseNotes(legalCase.legalCaseID)" variant="primary">Ver notas</b-button>
                <b-button :disabled="showLoader" @click="renderLegalPaymentDates(legalCase.legalCaseID)" variant="primary">Ver fechas de pago</b-button>

                <b-form-group v-if="legalCase.inUse == '1' && checkAccessList('administrar')" label="Caso bloqueado -> *Precaución puede estar siendo editado por algún usuario">
                <b-button @click.prevent="unblockLegalCase(legalCase.legalCaseID, user.id)" variant="danger">Desbloquear</b-button>
                </b-form-group>
            </div>

            <!-- LEGAL NOTES -->
            <div v-if="legalCaseNotes[legalCase.legalCaseID]">
                
                <ul class="legal-cases__notes">
                <li class="notes__note" v-bind:key="legalCaseNote.id" v-for="legalCaseNote in legalCaseNotes[legalCase.legalCaseID]">
                    <p v-if="legalCaseNote.note"><strong>Nota:</strong> {{ legalCaseNote.note }}</p>
                    <p v-if="legalCaseNote.name"><strong>Hecha por:</strong> {{ legalCaseNote.name }} {{ legalCaseNote.lastName1 }} {{ legalCaseNote.lastName2 }}</p>
                    <p v-if="legalCaseNote.date"><strong>Fecha:</strong> {{ legalCaseNote.date }}</p>
                </li>
                </ul>
                <span class="label-danger" v-if="legalCaseNotes[legalCase.legalCaseID] && !legalCaseNotes[legalCase.legalCaseID].length">No hay notas</span>
            </div>
            <!-- LEGAL NOTES END -->

            <!-- LEGAL PAYMENT DATES -->
            <div v-if="legalPaymentDates[legalCase.legalCaseID]">
                
                <ul class="legal-cases__payment-dates">
                <li class="payment-dates__date" v-bind:key="legalPaymentDate.id" v-for="legalPaymentDate in legalPaymentDates[legalCase.legalCaseID]">
                    <p v-if="legalPaymentDate.date"><strong>Fecha de pago:</strong> {{ legalPaymentDate.date }}</p>
                    <b-button v-if="checkAccessList('editar caso')" @click.prevent="removePaymentDate(legalPaymentDate.id, legalCase.legalCaseID)" variant="danger">
                    Eliminar
                    </b-button>
                </li>
                </ul>
                <span class="label-danger" v-if="legalPaymentDates[legalCase.legalCaseID] && !legalPaymentDates[legalCase.legalCaseID].length">No hay fechas de pago</span>
            </div>
            <!-- END LEGAL PAYMENT DATES -->

            </li>
        </ul>
        </div>
        <span class="label-danger" v-if="legalCases[user.id] && !legalCases[user.id].length">No hay casos</span>
        <!-- END LEGAL CASES -->
    </div>
</template>
 

<script>
import repositories from '../repositories';
import global from '../global';

export default {
  name: 'LegalCases',
  props: ["user", "legalCases", "showLoader", ""],
  data () {
    return {
        legalCaseForm:{
        id: null,
        internalCode: null,
        subjectID: null,
        userID: null,
        judicialStatusID: null,
        administrativeStatusID: null,
        note: null,
        totalAmount: 0,
        legalCaseID: null,
        locationID: null,
        code: null,
        inUse: '0'
      },
      paymentDates:{
        legalCaseID: null,
        dates: []
      },
      legalCases: [],
      editingLegalCase: false,
      legalCaseUserId: null,
      legalCaseNotes: [],
      legalPaymentDates: [],
    }
  },
  methods: {
    checkAccessList: function(action){
      return global.checkAccessList(action);
    },
    buildLocation: function(data){
      data.forEach(item => {
        item.location = item.locationID != '999' ? (item.name ? item.name : '') + ' ' + (item.lastName1 ? item.lastName1 : '') + ' ' + (item.lastName2 ? item.lastName2 : '') : this.locationStaticData['999'];
      });
      return data;
    },
    fillEditLegalCaseForm: async function(legalCaseID, userID){
      if( this.checkAccessList('editar caso') ){
  
        let isInUse = await this.isLegalCaseInUse(legalCaseID);

        if(isInUse === '1'){
          alert('Este registro está siendo editado por otro usuario. Por favor intente más tarde.');
        }else{
          
          await repositories.updateLegalCaseIsInUse({'id': legalCaseID, 'inUse': 1});

          await this.fillLegalCaseForm(legalCaseID, userID);
          
          await this.fillPaymentDatesOnForm(legalCaseID);

          this.editingLegalCase = true;
          this.$bvModal.show('bv-modal-legal-case-form');
          
        }
      }
    },
    unblockLegalCase: async function(legalCaseID, userID){

      await repositories.updateLegalCaseIsInUse({'id': legalCaseID, 'inUse': 0});
      //searchBy, value, userID, callback
      await this.renderLegalCases({searchBy:'id', value:legalCaseID, userID:userID});

    },
    isLegalCaseInUse: async function(id){
      this.$emit('update:showLoader', true);

      const data = await repositories.isLegalCaseInUse({'id': id});
      const response = data.response;
      let isInUse = 0;
      
      if( response.length ){
        isInUse = response[0].inUse;
      }

      this.$emit('update:showLoader', false);

      return isInUse;
    },
    fillLegalCaseForm: async function(id, userID){
      this.$emit('update:showLoader', true);

      this.legalCaseUserId = userID;
      
      const data = await repositories.getLegalCasesBy('id', id);
      const response = data.response;

      if( response.length ){
        this.legalCaseForm = response[0];
        this.legalCaseForm.id = id;
      }

      this.$emit('update:showLoader', false);
    },
    fillPaymentDatesOnForm: async function(id){
      this.$emit('update:showLoader', true);

      const data = await repositories.getLegalPaymentDatesBy('legalCaseID', id);
      const response = data.response;
      if( response.length ){
        this.paymentDates.legalCaseID = id;
        this.paymentDates.dates = response;
      }

      this.$emit('update:showLoader', false);
    },
    renderLegalCaseNotes: async function(legalCaseID){
      this.$emit('update:showLoader', true);

      const data = await repositories.getLegalCaseNotesBy('legalCaseID', legalCaseID);
      this.$set(this.legalCaseNotes, legalCaseID, data.response);

      this.$emit('update:showLoader', false);
    },
    renderLegalPaymentDates: async function(legalCaseID){
      this.$emit('update:showLoader', true);

      const data = await repositories.getLegalPaymentDatesBy('legalCaseID', legalCaseID);
      this.$set(this.legalPaymentDates, legalCaseID, data.response);

      this.$emit('update:showLoader', false);
    },
    removePaymentDate: async function(legalPaymentDateID, legalCaseID){
      this.$emit('update:showLoader', true);

      const data = {};
      data.id = legalPaymentDateID;
      await repositories.deletePaymentDate(data);
      await this.renderLegalPaymentDates(legalCaseID);

      this.$emit('update:showLoader', false);
    },
    renderLegalCases: async function({searchBy, value, userID, callback}){      
      this.$emit('update:showLoader', true);

      const data = await repositories.getLegalCasesBy(searchBy, value);
      const response = data.response;

      const dataFormatted = this.buildLocation(response);
      this.$set(this.legalCases, userID, dataFormatted);

      this.$emit('update:showLoader', false);

      if(callback && response.length){
        callback(response);
      }
    }
  }
}
</script>

<style lang="scss" scoped>
</style>