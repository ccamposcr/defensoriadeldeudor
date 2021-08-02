<template>
  <div class="client">
    <button v-show="!systemUsersInterface" class="show-instructions" @click.prevent="$store.commit('setInstructions', !$store.getters.instructions.show)">{{$store.getters.instructions.show ? $store.getters.instructions.hideShow : $store.getters.instructions.textShow}}</button>
    <div class="instructions" v-show="$store.getters.instructions.show">
      <h1>Instrucciones</h1>
      <p><strong>Agregar cliente:</strong> Presione el botón agregar cliente nuevo</p>
      <p><strong>Editar cliente:</strong> Busque el cliente por identificación o nombre y luego presione el botón editar cliente</p>
      <p><strong>Agregar caso:</strong> Busque el cliente por identificación o nombre, luego presione el botón agregar caso</p>
      <p><strong>Editar caso:</strong> Busque el caso por código interno ó número de expediente, luego presione el botón editar caso</p>
    </div>

    <b-button variant="info" v-if="!systemUsersInterface" @click="showSearchClientModal">Buscar Cliente - Caso</b-button>
    <b-button variant="success" v-if="!systemUsersInterface && checkAccessList('agregar cliente')"  @click="showClientFormModal">Agregar Cliente Nuevo</b-button>
    <b-button variant="primary" :disabled="$store.getters.showLoader" v-if="!systemUsersInterface" @click="renderAllClients">
      Ver todos los Clientes
    </b-button>

    <div v-show="$store.getters.users">
      <ul class="client__list">
        <li class="list__user" v-bind:key="user.id" v-for="user in $store.getters.users">
          
          <client-detail :user="user"></client-detail>

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

          <legal-cases @fillEditLegalCaseForm="fillEditLegalCaseForm" @checkAccessList="checkAccessList" @unblockLegalCase="unblockLegalCase" @renderLegalCaseNotes="renderLegalCaseNotes" :user="user"></legal-cases>

        </li>
      </ul>
    </div>

    <modal-client-form @renderClientBy="renderClientBy"></modal-client-form>
    <modal-search-form @renderLegalCases="renderLegalCases" @renderClientBy="renderClientBy"></modal-search-form>
    <modal-update-password-form></modal-update-password-form>
    <modal-legal-case-form @renderLegalCaseNotes="renderLegalCaseNotes" @renderLegalCases="renderLegalCases"></modal-legal-case-form>

  </div>
</template>

<script>
import ClientDetail from './ClientDetail.vue';
import ModalClientForm from './Modals/ModalClientForm.vue';
import ModalSearchForm from './Modals/ModalSearchForm.vue';
import ModalUpdatePasswordForm from './Modals/ModalUpdatePasswordForm.vue';
import ModalLegalCaseForm from './Modals/ModalLegalCaseForm.vue';
import repositories from '../repositories';
import global from '../global';
import LegalCases from './LegalCases.vue';

export default {
  name: 'Client',
  components: {ClientDetail, ModalClientForm, ModalSearchForm, ModalUpdatePasswordForm, ModalLegalCaseForm, LegalCases},
  data () {
    return {
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
    getStaticDataFromDB: async function(){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getJudicialStatusList');
      await this.$store.dispatch('getSubjectList');
      await this.$store.dispatch('getLocationListData');
      
      this.$store.commit('setShowLoader', false);
    },
    checkAccessList: function(action){
      return global.checkAccessList(action);
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
        this.$store.commit('setEditingUser', false);
        this.$bvModal.show('bv-modal-client-form');
      }
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
          this.$store.commit('setEditingUser', true);
          this.$bvModal.show('bv-modal-client-form');

        }
      }
    },
    showLegalCaseForm: async function(userID){
      if( this.checkAccessList('agregar caso') ){
        this.$store.commit('setEditingLegalCase', false);
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
    unblockUser: async function(userID){

      await this.$store.dispatch('updateClientIsInUse', {id: userID, inUse: 0});
      //service, searchBy, value, callback
      await this.renderClientBy({service:'getClientBy', searchBy:'id', value:userID});

    },
    renderLegalCases: async function({searchBy, value, userID, callback}){      
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getLegalCasesBy', {searchBy, value, userID, callback});

      this.$store.commit('setShowLoader', false);
    },
    renderLegalCaseNotes: async function(legalCaseID){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('getLegalCaseNotesBy', {searchBy: 'legalCaseID', legalCaseID: legalCaseID});

      this.$store.commit('setShowLoader', false);
    },
    resetClientVars: function(){
      this.$store.commit('setLegalCaseNotes', []);
      this.$store.commit('setLegalCases', []);
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
    fillEditLegalCaseForm: async function(legalCaseID, userID){
      if( this.checkAccessList('editar caso') ){
  
        await this.isLegalCaseInUse(legalCaseID);

        if(this.$store.getters.isLegalCaseInUse === '1'){
          alert('Este registro está siendo editado por otro usuario. Por favor intente más tarde.');
        }else{
          
          await this.$store.dispatch('updateLegalCaseIsInUse', {id: legalCaseID, inUse: 1});

          await this.fillLegalCaseForm(legalCaseID, userID);

          this.$store.commit('setEditingLegalCase', true);
          this.$bvModal.show('bv-modal-legal-case-form');
          
        }
      }
    },
    unblockLegalCase: async function(legalCaseID, userID){

      await this.$store.dispatch('updateLegalCaseIsInUse', {id: legalCaseID, inUse: 0});
      //searchBy, value, userID, callback
      await this.renderLegalCases({searchBy:'userID', value:userID, userID:userID});

    }
  }
}
</script>

<style lang="scss">
.v-application{
  .client{
    .btn{
      margin-right: 10px;
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
      }
    }
    .user{
      &__options{
        margin-top: 15px;
      }
    }
  }
}
</style>
