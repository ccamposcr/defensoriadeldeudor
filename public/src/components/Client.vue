<template>
  <div class="client">
    <b-button variant="info" v-if="!systemUsersInterface" @click="showSearchClientModal">Buscar Cliente</b-button>
    <b-button variant="success" v-if="!systemUsersInterface && checkAccessList('agregar cliente')"  @click="showClientFormModal">Agregar Cliente Nuevo</b-button>
    <b-button variant="primary" :disabled="$store.getters.showLoader" v-if="!systemUsersInterface" @click="renderAllClients">
      Ver todos los Clientes
    </b-button>

    <div v-show="$store.getters.users">
      <ul class="client__list">
        <li class="list__user" v-bind:key="user.id" v-for="user in $store.getters.users">
          <p v-if="user.personalID && user.personalID != null"><strong>C&eacute;dula:</strong> {{ user.personalID }}</p>
          <p v-if="user.name && user.name != null"><strong>Nombre:</strong> <span class="user__name">{{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</span></p>
          <p v-if="user.phone && user.phone != null" ><strong>Tel&eacute;fono:</strong> {{ user.phone }}</p>
          <p v-if="user.phone2 && user.phone2 != null" ><strong>Tel&eacute;fono 2:</strong> {{ user.phone2 }}</p>
          <p v-if="user.phone3 && user.phone3 != null" ><strong>Tel&eacute;fono 3:</strong> {{ user.phone3 }}</p>
          <p v-if="user.email && user.email != null"><strong>Email:</strong> {{ user.email }}</p>
          <p v-if="user.email2 && user.email2 != null"><strong>Email 2:</strong> {{ user.email2 }}</p>
          <p v-if="user.email3 && user.email3 != null"><strong>Email 3:</strong> {{ user.email3 }}</p>
          <p v-if="user.job && user.job != null"><strong>Ocupación:</strong> {{ user.job }}</p>
          <p v-if="user.address && user.address != null"><strong>Direcci&oacute;n:</strong> {{ user.address }}</p>
          <p v-if="user.role && user.role != null"><strong>Rol:</strong> {{ user.role }}</p>
          <div class="user__options">
            <b-button v-if="!systemUsersInterface && checkAccessList('editar cliente')" @click="fillEditClientForm(user.id)" variant="info">Editar Cliente</b-button>
            <b-button v-if="!systemUsersInterface && checkAccessList('agregar caso')" @click="showLegalCaseForm(user.id)" variant="success">Agregar Caso</b-button>
            <b-button :disabled="$store.getters.showLoader" v-if="!systemUsersInterface" @click="renderLegalCases({searchBy:'userID', value:user.id, userID:user.id})" variant="primary">Ver Casos</b-button>
            <b-button v-if="user.role != 'Administrador' && systemUsersInterface && checkAccessList('eliminar usuarios')" @click="deleteUser(user.id)" variant="danger">Eliminar Usuario</b-button>
            <b-button v-if="systemUsersInterface" @click="updatePassword(user.id)" variant="success">Cambiar Contraseña</b-button>

            <b-form-group v-if="user.inUse == '1' && checkAccessList('administrar')" label="Cliente bloqueado -> *Precaución puede estar siendo editado por algún usuario">
              <b-button @click.prevent="unblockUser(user.id)" variant="danger">Desbloquear</b-button>
            </b-form-group>
          </div>


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

              <!-- LEGAL NOTES -->
                <div v-if="$store.getters.legalCaseNotes(legalCase.legalCaseID)">
                  
                  <ul class="legal-cases__notes">
                    <li class="notes__note" v-bind:key="legalCaseNote.id" v-for="legalCaseNote in $store.getters.legalCaseNotes(legalCase.legalCaseID)">
                      <p v-if="legalCaseNote.note"><strong>Nota:</strong> {{ legalCaseNote.note }}</p>
                      <p v-if="legalCaseNote.name"><strong>Hecha por:</strong> {{ legalCaseNote.name }} {{ legalCaseNote.lastName1 }} {{ legalCaseNote.lastName2 }}</p>
                      <p v-if="legalCaseNote.date"><strong>Fecha:</strong> {{ legalCaseNote.date }}</p>
                    </li>
                  </ul>
                  <span class="label-danger" v-if="$store.getters.legalCaseNotes(legalCase.legalCaseID) && !$store.getters.legalCaseNotes(legalCase.legalCaseID).length">No hay notas</span>
                </div>
                <!-- LEGAL NOTES END -->

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

              </li>
            </ul>
          </div>
          <span class="label-danger" v-if="$store.getters.legalCases(user.id) && !$store.getters.legalCases(user.id).length">No hay casos</span>
          <!-- END LEGAL CASES -->


        </li>
      </ul>
    </div>

    <modal-client-form @renderClientBy="renderClientBy" :editing-user="editingUser"></modal-client-form>
    <modal-search-form @renderLegalCases="renderLegalCases" @renderClientBy="renderClientBy"></modal-search-form>
    <modal-legal-case-form @renderLegalPaymentDates="renderLegalPaymentDates" @renderLegalCaseNotes="renderLegalCaseNotes" @renderLegalCases="renderLegalCases" :editing-legal-case="editingLegalCase" :today="today"></modal-legal-case-form>
    <modal-update-password-form :update-password-form="updatePasswordForm"></modal-update-password-form>

  </div>
</template>

<script>
import ModalClientForm from './ModalClientForm.vue';
import ModalSearchForm from './ModalSearchForm.vue';
import ModalLegalCaseForm from './ModalLegalCaseForm.vue';
import ModalUpdatePasswordForm from './ModalUpdatePasswordForm.vue';
import repositories from '../repositories';
import global from '../global';

export default {
  name: 'Client',
  components: {ModalClientForm, ModalSearchForm, ModalLegalCaseForm, ModalUpdatePasswordForm},
  data () {
    return {
      updatePasswordForm:{
        password: null,
        confirmPassword: null
      },
      editingLegalCase: false,
      today: '',
      editingUser: false,
      systemUsersInterface: false
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
    renderAllClients: async function(){
      this.$store.commit('setShowLoader', true);

      this.resetClientVars();

      await this.$store.dispatch('getAllClients');

      this.$store.commit('setShowLoader', false);
    },
    renderAllUsers: async function(){
      this.$store.commit('setShowLoader', true);

      this.resetClientVars();

      await this.$store.dispatch('getAllUsers');

      this.$store.commit('setShowLoader', false);
    },
    renderClientBy: async function({service, searchBy, value, callback}){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getClientBy', {service, searchBy, value, callback});

      this.$store.commit('setShowLoader', false);
      
    },
    showSearchClientModal: function(){
      this.$bvModal.show('bv-modal-search-form');
    },
    showClientFormModal: function(){
      if( this.checkAccessList('agregar cliente') ){
        this.editingUser = false;
        this.$bvModal.show('bv-modal-client-form');
      }
    },
    renderLegalCases: async function({searchBy, value, userID, callback}){      
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getLegalCasesBy', {searchBy, value, userID, callback});

      this.$store.commit('setShowLoader', false);
    },
    isClientInUse: async function(id){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getIsClientInUse', {id});

      this.$store.commit('setShowLoader', false);

    },
    fillClientForm: async function(id){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('fillClientForm', {id});

      this.$store.commit('setShowLoader', false);
    },
    fillEditClientForm: async function(id){
      if( this.checkAccessList('editar cliente') ){
        await this.isClientInUse(id);
        if(this.$store.getters.isClientInUse === '1'){
          alert('Este registro está siendo editado por otro usuario. Por favor intente más tarde.');
        }else{
          await this.$store.dispatch('updateClientIsInUse', {id: id, inUse: 1});

          await this.fillClientForm(id);
        
          this.editingUser = true;
          this.$bvModal.show('bv-modal-client-form');

        }
      }
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

          this.editingLegalCase = true;
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
    resetClientVars: function(){
      this.$store.commit('setLegalPaymentDates', []);
      this.$store.commit('setLegalCaseNotes', []);
      this.$store.commit('setLegalCases', []);
    },
    showLegalCaseForm: async function(userID){
      if( this.checkAccessList('agregar caso') ){
        this.editingLegalCase = false;
        this.$store.commit('setCurrentLegalCaseUserId', userID);
        this.$bvModal.show('bv-modal-legal-case-form');
      }
    },
    loadDataFromURLParams: async function(params){
      if(params.userID){
        //service, searchBy, value, callback
        await this.renderClientBy({service:'getClientBy', searchBy:'id', value:params.userID});

      }
      if(params.legalCaseID){
        //searchBy, value, userID, callback
        await this.renderLegalCases({searchBy:'id', value:params.legalCaseID, userID:params.userID});

      }
      if(params.showNewClientForm){
        if( this.checkAccessList('agregar cliente') ){
          this.showClientFormModal();
        }else{
          window.location.href = base_url + 'denied';
        }
      }

      if(params.showSystemUsers){
        if( this.checkAccessList('administrar') ){
          this.systemUsersInterface = true;
          await this.renderAllUsers();
        }else{
          window.location.href = base_url + 'denied';
        }
      }
    },
    deleteUser: async function(userID){
      this.$store.commit('setShowLoader', true);
      //OK
      await repositories.deleteUser({id:userID});
      await this.renderAllUsers();

      this.$store.commit('setShowLoader', false);
    },
    updatePassword: async function(userID){
      this.$store.commit('setCurrentUserIdUpdatePassword', userID);
      this.$bvModal.show('bv-modal-update-password-form');
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
    unblockUser: async function(userID){

      await this.$store.dispatch('updateClientIsInUse', {id: userID, inUse: 0});
      //service, searchBy, value, callback
      await this.renderClientBy({service:'getClientBy', searchBy:'id', value:userID});

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
  .client{
    .btn{
      margin-right: 10px;
      margin-bottom: 10px;
      &:last-child{
        margin-right: 0;
      }
    }
    &__list{
      list-style-type: none;
      padding: 0;
      margin-top: 30px;
    }
    .list{
      &__user{
        background-color: #e6e5e5;
        margin-bottom: 15px;
        padding: 15px;
        p{
          margin-bottom: 0;
        }
      }
    }
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
      }
      &__notes,
      &__payment-dates{
        list-style-type: none;
        padding: 0;
        margin-top: 30px;
        background-color: #e6e5e5;
      }
    }
    .case{
      &__options{
        margin-top: 30px;
      }
    }
    .notes__note,
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
  }
</style>
