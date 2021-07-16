<template>
  <div class="client">
    <b-button variant="info" v-if="!systemUsersInterface" @click="showSearchClientModal">Buscar Cliente</b-button>
    <b-button variant="success" v-if="!systemUsersInterface && checkAccessList('agregar cliente')"  @click="showClientFormModal">Agregar Cliente Nuevo</b-button>
    <b-button variant="primary" :disabled="showLoader" v-if="!systemUsersInterface" @click="renderAllClients">
      Ver todos los Clientes
    </b-button>

    <div v-show="users.length">
      <ul class="client__list">
        <li class="list__user" v-bind:key="user.id" v-for="user in users">
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
            <b-button :disabled="showLoader" v-if="!systemUsersInterface" @click="renderLegalCases({searchBy:'userID', value:user.id, userID:user.id})" variant="primary">Ver Casos</b-button>
            <b-button v-if="user.role != 'Administrador' && systemUsersInterface && checkAccessList('eliminar usuarios')" @click="deleteUser(user.id)" variant="danger">Eliminar Usuario</b-button>
            <b-button v-if="systemUsersInterface" @click="updatePassword(user.id)" variant="success">Cambiar Contraseña</b-button>

            <b-form-group v-if="user.inUse == '1' && checkAccessList('administrar')" label="Cliente bloqueado -> *Precaución puede estar siendo editado por algún usuario">
              <b-button @click.prevent="unblockUser(user.id)" variant="danger">Desbloquear</b-button>
            </b-form-group>
          </div>

          <legal-cases :user="user" :legal-cases="legalCases" :show-loader.sync="showLoader"></legal-cases>

        </li>
      </ul>
    </div>

    <modal-client-form @renderClientBy="renderClientBy" :show-loader.sync="showLoader" :client-form="clientForm" :editing-user="editingUser" :users.sync="users"></modal-client-form>
    <modal-search-form @renderLegalCases="renderLegalCases" @renderClientBy="renderClientBy" :show-loader.sync="showLoader" :search-client-form="searchClientForm" :users.sync="users" :location-static-data="locationStaticData" :legal-cases.sync="legalCases"></modal-search-form>
    <modal-legal-case-form @renderLegalPaymentDates="renderLegalPaymentDates" @renderLegalCaseNotes="renderLegalCaseNotes" @renderLegalCases="renderLegalCases" :show-loader.sync="showLoader" :payment-dates="paymentDates" :legal-case-form="legalCaseForm" :editing-legal-case="editingLegalCase" :static-data="staticData" :legal-case-user-id="legalCaseUserId" :today="today"></modal-legal-case-form>
    <modal-update-password-form :show-loader.sync="showLoader" :update-password-form="updatePasswordForm" :update-password-user-id="updatePasswordUserId"></modal-update-password-form>
    <div v-if="showLoader" class="loader">
      <b-spinner large></b-spinner>
    </div>
  </div>
</template>

<script>
import ModalClientForm from './ModalClientForm.vue';
import ModalSearchForm from './ModalSearchForm.vue';
import ModalLegalCaseForm from './ModalLegalCaseForm.vue';
import ModalUpdatePasswordForm from './ModalUpdatePasswordForm.vue';
import LegalCases from './LegalCases.vue';
import repositories from '../repositories';
import global from '../global';

export default {
  name: 'Client',
  components: {ModalClientForm, ModalSearchForm, ModalLegalCaseForm, ModalUpdatePasswordForm, LegalCases},
  data () {
    return {
      staticData:{
        judicialStatusList: [],
        subjectList: [],
        administrativeStatusList: [],
        locationList: []
      },
      users: [],
      clientForm:{
        id:null,
        personalID:null,
        name: null,
        lastName1: null,
        lastName2: null,
        phone: null,
        email: null,
        address: null,
        roleID:'0',
        status: '1',
        phone2: '',
        phone3: '',
        email2: '',
        email3: '',
        job: '',
        inUse: '0'
      },
      searchClientForm:{
        personalID: null,
        name: null,
        code: null,
        internalCode: null,
        searchBy: 'personalID'
      },
      updatePasswordForm:{
        password: null,
        confirmPassword: null
      },
      today: '',
      editingUser: false,
      locationStaticData: {'999': 'Archivo'},
      systemUsersInterface: false,
      updatePasswordUserId: null,
      showLoader: false
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
      this.showLoader = true;

      const judicialStatusListData = await repositories.getJudicialStatusList();
      this.staticData.judicialStatusList = judicialStatusListData.response;

      const subjectListData = await repositories.getSubjectList();
      this.staticData.subjectList = subjectListData.response;
      
      const administrativeStatusListData = await repositories.getAdministrativeStatusList();
      this.staticData.administrativeStatusList = administrativeStatusListData.response;

      const locationListData = await repositories.getClientBy('roleID !=', '0');
      this.staticData.locationList = locationListData.response;

      this.staticData.locationList.forEach(item => {
        item.location = item.name + ' ' + item.lastName1 + ' ' + item.lastName2;  
      });
      this.staticData.locationList.push({'location': this.locationStaticData['999'], 'id': '999'});
      
      this.showLoader = false;
    },
    renderAllClients: async function(){
      this.showLoader = true;

      this.resetClientVars();

      const data = await repositories.getAllClients();
      this.users = data.response;

      this.showLoader = false;
    },
    renderAllUsers: async function(){
      this.showLoader = true;

      this.resetClientVars();
      const data = await repositories.getAllUsers();
      this.users = data.response;

      this.showLoader = false;
    },
    renderClientBy: async function({service, searchBy, value, callback}){
      this.showLoader = true;

      const data = await repositories[service](searchBy, value);
      const response = data.response;
      this.users = response;

      this.showLoader = false;
      
      if(callback && response.length){
        callback(response);
      }
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
    isClientInUse: async function(id){
      this.showLoader = true;

      const data = await repositories.isClientInUse({'id': id});
      const response = data.response;
      let isInUse = 0;
      
      if( response.length ){
        isInUse = response[0].inUse;
      }

      this.showLoader = false;

      return isInUse;
    },
    fillClientForm: async function(id){
      this.showLoader = true;

      const data = await repositories.getClientBy('id', id);
      const response = data.response;
      if( response.length ){
        this.clientForm = response[0];
      }

      this.showLoader = false;
    },
    fillEditClientForm: async function(id){
      if( this.checkAccessList('editar cliente') ){

        let isInUse = await this.isClientInUse(id);

        if(isInUse === '1'){
          alert('Este registro está siendo editado por otro usuario. Por favor intente más tarde.');
        }else{

          await repositories.updateClientIsInUse({'id': id, 'inUse': 1});

          await this.fillClientForm(id);
        
          this.editingUser = true;
          this.$bvModal.show('bv-modal-client-form');

        }
      }
    },
    resetClientVars: function(){
      this.legalCases = [];
      this.legalCaseNotes = [];
    },
    showLegalCaseForm: async function(userID){
      if( this.checkAccessList('agregar caso') ){
        this.editingLegalCase = false;
        this.legalCaseUserId = userID;
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
      this.showLoader = true;

      const data = {};
      data.id = userID;
      await repositories.deleteUser(data);
      await this.renderAllUsers();

      this.showLoader = false;
    },
    updatePassword: async function(userID){
      this.updatePasswordUserId = userID;
      this.$bvModal.show('bv-modal-update-password-form');
    },
    unblockUser: async function(userID){

      await repositories.updateClientIsInUse({'id': userID, 'inUse': 0});
      //service, searchBy, value, callback
      await this.renderClientBy({service:'getClientBy', searchBy:'id', value:userID});

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
