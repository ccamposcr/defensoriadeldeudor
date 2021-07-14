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
            <b-button :disabled="showLoader" v-if="!systemUsersInterface" @click="renderLegalCases('userID', user.id, user.id)" variant="primary">Ver Casos</b-button>
            <b-button v-if="user.role != 'Administrador' && systemUsersInterface && checkAccessList('eliminar usuarios')" @click="deleteUser(user.id)" variant="danger">Eliminar Usuario</b-button>
            <b-button v-if="systemUsersInterface" @click="updatePassword(user.id)" variant="success">Cambiar Contraseña</b-button>

            <b-form-group v-if="user.inUse == '1' && checkAccessList('administrar')" label="Cliente bloqueado -> *Precaución puede estar siendo editado por algún usuario">
              <b-button @click.prevent="unblockUser(user.id)" variant="danger">Desbloquear</b-button>
            </b-form-group>
          </div>


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
                <p v-if="legalCase.totalAmount && legalCase.totalAmount != null"><strong>Monto Total:</strong> {{legalCase.totalAmount}}</p>
                <div class="case__options">
                  <b-button v-if="checkAccessList('editar caso')" @click="fillEditLegalCaseForm(legalCase.legalCaseID, user.id)" variant="info">Editar caso</b-button>
                  <b-button :disabled="showLoader" @click="renderLegalCaseNotes(legalCase.legalCaseID)" variant="primary">Ver notas</b-button>
                  <b-button :disabled="showLoader" @click="renderLegalPaymentDates(legalCase.legalCaseID)" variant="primary">Ver fechas de pago</b-button>

                  <b-form-group v-if="legalCase.inUse == '1' && checkAccessList('administrar')" label="Caso bloqueado -> *Precaución puede estar siendo editado por algún usuario">
                    <b-button @click.prevent="unblockLegalCase(legalCase.legalCaseID, user.id)" variant="danger">Desbloquear</b-button>
                  </b-form-group>
                </div>

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

              </li>
            </ul>
          </div>
          <span class="label-danger" v-if="legalCases[user.id] && !legalCases[user.id].length">No hay casos</span>
          <!-- END LEGAL CASES -->


        </li>
      </ul>
    </div>

    <modal-client-form :show-loader.sync="showLoader" :client-form="clientForm" :editing-user="editingUser" :users.sync="users"></modal-client-form>
    <modal-search-form :show-loader.sync="showLoader" :search-client-form="searchClientForm" :users.sync="users" :location-static-data="locationStaticData" :legal-cases.sync="legalCases"></modal-search-form>
    <modal-legal-case-form :show-loader.sync="showLoader" :payment-dates="paymentDates" :legal-case-form="legalCaseForm" :editing-legal-case="editingLegalCase" :static-data="staticData" :legal-case-user-id="legalCaseUserId" :today="today"></modal-legal-case-form>
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
import repositories from '../repositories';
import global from '../global';

export default {
  name: 'Client',
  components: {ModalClientForm, ModalSearchForm, ModalLegalCaseForm, ModalUpdatePasswordForm},
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
        phone2: null,
        phone3: null,
        email2: null,
        email3: null,
        job: null,
        inUse: '0'
      },
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
      legalCases: [],
      editingLegalCase: false,
      legalCaseUserId: null,
      today: '',
      editingUser: false,
      legalCaseNotes: [],
      legalPaymentDates: [],
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
    renderClientBy: async function(service, searchBy, value, returnProp){
      this.showLoader = true;

      const data = await repositories[service](searchBy, value);
      const response = data.response;
      this.users = response;
      
      if(returnProp && response.length){
        return response[0][returnProp];
      }

      this.showLoader = false;
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
    renderLegalCases: async function(searchBy, value, userID){      
      this.showLoader = true;

      const data = await repositories.getLegalCasesBy(searchBy, value);
      const response = data.response;

      const dataFormatted = this.buildLocation(response);
      this.$set(this.legalCases, userID, dataFormatted);

      this.showLoader = false;
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
    buildLocation: function(data){
      data.forEach(item => {
        item.location = item.locationID != '999' ? (item.name ? item.name : '') + ' ' + (item.lastName1 ? item.lastName1 : '') + ' ' + (item.lastName2 ? item.lastName2 : '') : this.locationStaticData['999'];
      });
      return data;
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
    isLegalCaseInUse: async function(id){
      this.showLoader = true;

      const data = await repositories.isLegalCaseInUse({'id': id});
      const response = data.response;
      let isInUse = 0;
      
      if( response.length ){
        isInUse = response[0].inUse;
      }

      this.showLoader = false;

      return isInUse;
    },
    fillLegalCaseForm: async function(id, userID){
      this.showLoader = true;

      this.legalCaseUserId = userID;
      
      const data = await repositories.getLegalCasesBy('id', id);
      const response = data.response;

      if( response.length ){
        this.legalCaseForm = response[0];
        this.legalCaseForm.id = id;
      }

      this.showLoader = false;
    },
    fillPaymentDatesOnForm: async function(id){
      this.showLoader = true;

      const data = await repositories.getLegalPaymentDatesBy('legalCaseID', id);
      const response = data.response;
      if( response.length ){
        this.paymentDates.legalCaseID = id;
        this.paymentDates.dates = response;
      }

      this.showLoader = false;
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
    renderLegalCaseNotes: async function(legalCaseID){
      this.showLoader = true;

      const data = await repositories.getLegalCaseNotesBy('legalCaseID', legalCaseID);
      this.$set(this.legalCaseNotes, legalCaseID, data.response);

      this.showLoader = false;
    },
    renderLegalPaymentDates: async function(legalCaseID){
      this.showLoader = true;

      const data = await repositories.getLegalPaymentDatesBy('legalCaseID', legalCaseID);
      this.$set(this.legalPaymentDates, legalCaseID, data.response);

      this.showLoader = false;
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

        await this.renderClientBy('getClientBy', 'id', params.userID);

      }
      if(params.legalCaseID){

        await this.renderLegalCases('id', params.legalCaseID, params.userID);

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
    removePaymentDate: async function(legalPaymentDateID, legalCaseID){
      this.showLoader = true;

      const data = {};
      data.id = legalPaymentDateID;
      await repositories.deletePaymentDate(data);
      await this.renderLegalPaymentDates(legalCaseID);

      this.showLoader = false;
    },
    unblockUser: async function(userID){

      await repositories.updateClientIsInUse({'id': userID, 'inUse': 0});
      await this.renderClientBy('getClientBy', 'id', userID);

    },
    unblockLegalCase: async function(legalCaseID, userID){

      await repositories.updateLegalCaseIsInUse({'id': legalCaseID, 'inUse': 0});
      await this.renderLegalCases('id', legalCaseID, userID);

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
        background-color: #e4e4e4;
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
        background-color: #fafafa;
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
