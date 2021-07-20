<template>
    <div>
        <!-- LEGAL CASES -->
        <div v-if="$store.getters.legalCases(user.id)">
        <ul class="user__legal-cases">
            <li class="legal-cases__case" v-bind:key="legalCase.id" v-for="legalCase in $store.getters.legalCases(user.id)">
                <p v-if="legalCase.internalCode && legalCase.internalCode != null"><strong>Número de expediente:</strong> {{ legalCase.internalCode }}</p>
                <p v-if="legalCase.code && legalCase.code != null"><strong>Código interno:</strong> {{ legalCase.code }}</p>
                <p v-if="legalCase.subject && legalCase.subject != null"><strong>Naturaleza de expediente:</strong> {{ legalCase.subject }}</p>
                <p v-if="legalCase.judicialStatus && legalCase.judicialStatus != null"><strong>Estado judicial:</strong> {{ legalCase.judicialStatus }}</p>
                <p v-if="legalCase.administrativeStatus && legalCase.administrativeStatus != null"><strong>Estado administrativo:</strong> {{ legalCase.administrativeStatus }}</p>
                <p v-if="legalCase.location && legalCase.location != null"><strong>Ubicación del expediente:</strong> {{ legalCase.location }}</p>
                <p v-if="legalCase.totalAmount && legalCase.totalAmount != null"><strong>Monto del caso:</strong> {{legalCase.totalAmount}}</p>
                <div class="case__options">
                    <b-button v-if="checkAccessList('editar caso')" @click="fillEditLegalCaseForm(legalCase.legalCaseID, user.id)" variant="info">Editar caso</b-button>
                    <b-button :disabled="$store.getters.showLoader" @click="renderLegalCaseNotes(legalCase.legalCaseID)" variant="primary">Ver notas</b-button>
                    <b-button :disabled="$store.getters.showLoader" @click="renderLegalPaymentDates(legalCase.legalCaseID)" variant="primary">Ver fechas de pago</b-button>

                    <b-form-group v-if="legalCase.inUse == '1' && checkAccessList('administrar')" label="Caso bloqueado -> *Precaución puede estar siendo editado por algún usuario">
                    <b-button @click.prevent="unblockLegalCase(legalCase.legalCaseID, user.id)" variant="danger">Desbloquear</b-button>
                    </b-form-group>
                </div>

                <legalNotes :legal-case="legalCase"></legalNotes>

                <legal-payment-dates :legal-case="legalCase"></legal-payment-dates>
            
            </li>
        </ul>
        </div>
        <span class="label-danger" v-if="$store.getters.legalCases(user.id) && !$store.getters.legalCases(user.id).length">No hay casos</span>
        <!-- END LEGAL CASES -->

  </div>
</template>

<script>

import global from '../global';
import legalNotes from './LegalNotes.vue';
import legalPaymentDates from './LegalPaymentDates.vue';

export default {
  name: 'LegalCases',
  props: ["user"],
  components: {legalNotes, legalPaymentDates},
  data () {
    return {
    }
  },
  created(){
    this.getStaticDataFromDB();
  },
  mounted() {
    const params = this.$route.query;
    this.loadDataFromURLParams(params);
  },
  methods: {
    checkAccessList: function(action){
      return global.checkAccessList(action);
    },
    getStaticDataFromDB: async function(){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getJudicialStatusList');
      await this.$store.dispatch('getSubjectList');
      await this.$store.dispatch('getAdministrativeStatusList');
      await this.$store.dispatch('getLocationListData');
      
      this.$store.commit('setShowLoader', false);
    },
    renderLegalCases: async function({searchBy, value, userID, callback}){      
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getLegalCasesBy', {searchBy, value, userID, callback});

      this.$store.commit('setShowLoader', false);
    },
    isLegalCaseInUse: async function(id){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getIsLegalCaseInUse', {id});

      this.$store.commit('setShowLoader', false);
    },
    fillLegalCaseForm: async function(id, userID){
      this.$store.commit('setShowLoader', true);

      this.$store.commit('setCurrentLegalCaseUserId', userID);

      await this.$store.dispatch('fillLegalCaseForm', {id});

      this.$store.commit('setShowLoader', false);
    },
    fillPaymentDatesOnForm: async function(id){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('fillPaymentDatesOnForm', {id});

      this.$store.commit('setShowLoader', false);
    },
    fillEditLegalCaseForm: async function(legalCaseID, userID){
      if( this.checkAccessList('editar caso') ){
  
        await this.isLegalCaseInUse(legalCaseID);

        if(this.$store.getters.isLegalCaseInUse === '1'){
          alert('Este registro está siendo editado por otro usuario. Por favor intente más tarde.');
        }else{
          
          await this.$store.dispatch('updateLegalCaseIsInUse', {id: legalCaseID, inUse: 1});

          await this.fillLegalCaseForm(legalCaseID, userID);
          
          await this.fillPaymentDatesOnForm(legalCaseID);

          this.$store.commit('setEditingLegalCase', true);
          this.$bvModal.show('bv-modal-legal-case-form');
          
        }
      }
    },
    renderLegalCaseNotes: async function(legalCaseID){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getLegalCaseNotesBy', {searchBy: 'legalCaseID', legalCaseID: legalCaseID});

      this.$store.commit('setShowLoader', false);
    },
    renderLegalPaymentDates: async function(legalCaseID){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getLegalPaymentDatesBy', {searchBy: 'legalCaseID', legalCaseID: legalCaseID});

      this.$store.commit('setShowLoader', false);
    },
    loadDataFromURLParams: async function(params){
      if(params.legalCaseID){
        //searchBy, value, userID, callback
        await this.renderLegalCases({searchBy:'id', value:params.legalCaseID, userID:params.userID});

      }
    },
    unblockLegalCase: async function(legalCaseID, userID){

      await this.$store.dispatch('updateLegalCaseIsInUse', {id: legalCaseID, inUse: 0});
      //searchBy, value, userID, callback
      await this.renderLegalCases({searchBy:'id', value:legalCaseID, userID:userID});

    }
  }
}
</script>

<style lang="scss" scoped>
    .user{
      &__legal-cases{
        list-style-type: none;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        background-color: #fbfbfb;
        margin-top: 30px;
      }
      &__name{
        font-size: 20px;
        font-weight: bold;
      }
      &__options{
        margin-top: 30px;
      }
    }
    .legal-cases{
      &__case{
        padding: 15px;
        flex: 1 1 50%;
        border-bottom: 1px solid gray;

        &:nth-child(odd){
          border-right: 1px solid gray;
        }

        &:last-child{
          border-right: none;
        }
        p{
            margin-bottom: 0;
        }
      }
    }
    .case{
      &__options{
        margin-top: 30px;
      }
    }
</style>
