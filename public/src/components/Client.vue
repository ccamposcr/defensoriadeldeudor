<template>
  <div class="client">
    <welcome></welcome>
    <b-button variant="info" @click="showSearchClientModal">Buscar Cliente</b-button>
    <b-button variant="info" @click="showClientFormModal">Agregar Cliente Nuevo</b-button>
    <b-button variant="info" @click="showAllClients">Ver todos los Clientes</b-button>

    <div v-show="users.length">
      <ul class="client__list">
        <li class="list__user" v-bind:key="user.id" v-for="user in users">
          <div><strong>C&eacute;dula:</strong> {{ user.personalID }}</div>
          <div><strong>Nombre:</strong> {{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</div>
          <div><strong>Tel&eacute;fono:</strong> {{ user.phone }}</div>
          <div><strong>Email:</strong> {{ user.email }}</div>
          <div><strong>Direcci&oacute;n:</strong> {{ user.address }}</div>
          <div>
            <b-button @click="fillEditClientForm(user.id)" variant="info">Editar Cliente</b-button>
            <b-button @click="showLegalCaseForm(user.id)" variant="info">Agregar Caso</b-button>
            <b-button @click="showLegalCases(user.id)" variant="info">Ver Casos</b-button>
          </div>

          <div v-if="legalCases[user.id]">
            <ul class="user__legal-cases">
              <li class="legal-cases__case" v-bind:key="legalCase.id" v-for="legalCase in legalCases[user.id]">
                <div><strong>Número de expediente:</strong> {{ legalCase.internalCode }}</div>
                <div><strong>Naturaleza de expediente:</strong> {{ legalCase.subject }}</div>
                <div><strong>Estado judicial:</strong> {{ legalCase.judicialStatus }}</div>
                <div><strong>Estado administrativo:</strong> {{ legalCase.administrativeStatus }}</div>
                <div><strong>Ubicación del expediente:</strong> {{ legalCase.location }}</div>
                <div><strong>Fecha de siguiente pago:</strong> {{legalCase.nextNotification}}</div>
                <b-button @click="fillLegalCaseForm(legalCase.legalCaseID, user.id)" variant="info">Editar Caso</b-button>
                <b-button @click="showLegalCaseNotes(legalCase.legalCaseID)" variant="info">Ver notas</b-button>

                <div v-if="legalCaseNotes[legalCase.legalCaseID]">
                  
                  <ul class="legal-cases__notes">
                    <li class="notes__note" v-bind:key="legalCaseNote.id" v-for="legalCaseNote in legalCaseNotes[legalCase.legalCaseID]">
                      <div><strong>Nota:</strong> {{ legalCaseNote.note }}</div>
                      <div><strong>Hecha por:</strong> {{ legalCaseNote.name }} {{ legalCaseNote.lastName1 }} {{ legalCaseNote.lastName2 }}</div>
                      <div><strong>Fecha:</strong> {{ legalCaseNote.date }}</div>
                    </li>
                  </ul>
                  <span v-if="legalCaseNotes[legalCase.legalCaseID] && !legalCaseNotes[legalCase.legalCaseID].length">No hay notas</span>
                </div>

              </li>
            </ul>
          </div>
          <span v-if="legalCases[user.id] && !legalCases[user.id].length">No hay casos</span>

        </li>
      </ul>
    </div>

    <div>
      <modal-client-form :client-form="clientForm" :editing-user="editingUser" :users.sync="users"></modal-client-form>
      <modal-search-form :search-client-form="searchClientForm" :users.sync="users"></modal-search-form>
      <modal-legal-case-form :legal-case-form="legalCaseForm" :editing-legal-case="editingLegalCase" :static-data="staticData" :legal-case-user-id="legalCaseUserId" :today="today"></modal-legal-case-form>
    </div>

  </div>
</template>

<script>
import ModalClientForm from './ModalClientForm.vue';
import ModalSearchForm from './ModalSearchForm.vue';
import ModalLegalCaseForm from './ModalLegalCaseForm.vue';
import Welcome from './Welcome.vue';

export default {
  name: 'Client',
  components: {ModalClientForm, ModalSearchForm, ModalLegalCaseForm, Welcome},
  data () {
    return {
      staticData:{
        roleList: [],
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
        roleID:'99',
        status: '1'
      },
      legalCaseForm:{
        id: null,
        internalCode: null,
        subjectID: null,
        userID: null,
        judicialStatusID: null,
        administrativeStatusID: null,
        note: null,
        nextNotification: null,
        legalCaseID: null,
        locationID: null
      },
      searchClientForm:{
        personalID: null,
        name: null,
        lastName1: null,
        lastName2: null,
        searchBy: 'personalID'
      },
      legalCases: [],
      editingLegalCase: false,
      legalCaseUserId: null,
      today: '',
      editingUser: false,
      legalCaseNotes: [],
      locationStaticData: {'999': 'Archivo'}
    }
  },
  computed:{
    getTodayDate: function(){
      return this.$parent.getTodayDate();
    }
  },
  created(){
    this.getStaticDataFromDB();
    this.today = this.getTodayDate;
  },
  mounted() {
    /*this.$root.$on('bv::modal::hide', (bvEvent, modalId) => {
      switch(modalId){
        case 'bv-modal-legal-case-form':
        break;
        case 'bv-modal-search-form':
        break;
        case 'bv-modal-client-form':
        break;
      }
    })*/
  },
  methods: {
      getClientBy: async function(searchBy, value){
          const url = 'clientes/getClientBy';
          
          const params = {
              'searchBy':searchBy,
              'value' :value
          };
          params[csrf_name] = csrf_hash;
          const response = await fetch(url, {
              credentials: 'include',
              method: 'POST',
              body: new URLSearchParams(params)
          });

          const data = await response.json();
          csrf_name = data.csrf_name;
          csrf_hash = data.csrf_hash;
          return data;
      },
      getStaticDataFromDB: async function(){

        const roleListData = await this.getRoleList();
        this.staticData.roleList = roleListData.response;

        const judicialStatusListData = await this.getJudicialStatusList();
        this.staticData.judicialStatusList = judicialStatusListData.response;

        const subjectListData = await this.getSubjectList();
        this.staticData.subjectList = subjectListData.response;
        
        const administrativeStatusListData = await this.getAdministrativeStatusList();
        this.staticData.administrativeStatusList = administrativeStatusListData.response;

        const locationListData = await this.getClientBy('roleID !=', '99');
        this.staticData.locationList = locationListData.response;

        this.staticData.locationList.forEach(item => {
          item['location'] = item.name + ' ' + item.lastName1 + ' ' + item.lastName2;  
        });
        this.staticData.locationList.push({'location': this.locationStaticData['999'], 'id': '999'});
      },
      getRoleList: async function(){
        const url = 'clientes/getRoleList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      getJudicialStatusList: async function(){
        const url = 'clientes/getJudicialStatusList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      getSubjectList: async function(){
        const url = 'clientes/getSubjectList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      getAllUsers: async function(){
        const url = 'clientes/getAllClients';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      getAdministrativeStatusList: async function(){
        const url = 'clientes/getAdministrativeStatusList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      showAllClients: async function(){
        this.resetClientVars();

        const data = await this.getAllUsers();
        this.users = data.response;
      },
      showSearchClientModal: function(){
        this.$bvModal.show('bv-modal-search-form');
      },
      showClientFormModal: function(){
        this.clearForm('clientForm');
        this.editingUser = false;
        this.$bvModal.show('bv-modal-client-form');
      },
      getLegalCasesBy: async function(searchBy, value){
        const url = 'clientes/getLegalCasesBy';

        const params = {
          'searchBy':searchBy,
          'value': value
        };
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      getLegalCaseNotesBy: async function(searchBy, value){
        const url = 'clientes/getLegalCaseNotesBy';

        const params = {
          'searchBy':searchBy,
          'value': value
        };
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      showLegalCases: async function(userID){        
        const data = await this.getLegalCasesBy('userID', userID);
        data.response.forEach(item => {
          item['location'] = item.locationID != '999' ? item.location = item.name + ' ' + item.lastName1 + ' ' + item.lastName2 : this.locationStaticData['999'];
        });
        this.$set(this.legalCases, userID, data.response);
      },
      fillEditClientForm: async function(id){
        const data = await this.getClientBy('id', id);
        const response = data.response;
        if( response.length ){
          this.clientForm = response[0];
          this.editingUser = true;
          this.$bvModal.show('bv-modal-client-form');
        }
      },
      fillLegalCaseForm: async function(legalCaseID, userID){
        this.legalCaseUserId = userID;
        const data = await this.getLegalCasesBy('id', legalCaseID);
        const response = data.response;
        if( response.length ){
          this.legalCaseForm = response[0];
          this.legalCaseForm['id'] = legalCaseID;
          this.editingLegalCase = true;
          this.$bvModal.show('bv-modal-legal-case-form');
        }
      },
      showLegalCaseNotes: async function(legalCaseID){
        const data = await this.getLegalCaseNotesBy('legalCaseID', legalCaseID);
        this.$set(this.legalCaseNotes, legalCaseID, data.response);
      },
      resetClientVars: function(){
        this.legalCases = [];
        this.legalCaseNotes = [];
      },
      showLegalCaseForm: async function(userID){
        this.clearForm('legalCaseForm');
        this.editingLegalCase = false;
        this.legalCaseUserId = userID;
        this.$bvModal.show('bv-modal-legal-case-form');
      },
      clearForm: function(form){
        for(const item in this[form]){
            this[form][item] = null;
        }
    }
  }
}
</script>

<style lang="scss" scoped>
  .client{
    &__list{
      list-style-type: none;
      padding: 0;
    }
    .list{
      &__user{
        background-color: lightgray;
        margin-bottom: 15px;
        padding: 15px;
      }
    }
    .user{
      &__legal-cases{
        list-style-type: none;
        padding: 0;
        display: flex;
      }
    }
    .legal-cases{
      &__case{
        padding: 15px;
        border-right: 1px solid gray;
        flex: 1 1 100%;
        &:last-child{
          border-right: none;
        }
      }
      &__notes{
        list-style-type: none;
        padding: 0;
      }
    }
    .notes{
      &__note{
        padding: 15px;
        border-bottom: 1px solid gray;
        &:last-child{
          border-bottom: none;
        }
      }
    }
  }
</style>
