<template>
  <div class="client">
    <b-button variant="info" @click="showSearchClientModal">Buscar Cliente</b-button>
    <b-button variant="info" @click="showClientFormModal">Agregar Cliente Nuevo</b-button>
    <b-button variant="info" @click="showAllClients">Ver todos los Clientes</b-button>

    <div v-show="users.length">
      <ul class="client__list">
        <li class="list__user" v-bind:key="user.id" v-for="user in users">
          <div>C&eacute;dula: {{ user.personalID }}</div>
          <div>Nombre: {{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</div>
          <div>Tel&eacute;fono: {{ user.phone }}</div>
          <div>Email: {{ user.email }}</div>
          <div>Direcci&oacute;n: {{ user.address }}</div>
          <div>
            <b-button @click="fillEditClientForm(user.id)" variant="info">Editar Cliente</b-button>
            <b-button @click="showLegalCaseForm(user.id)" variant="info">Agregar Caso</b-button>
            <b-button @click="showLegalCases(user.id)" variant="info">Ver Casos</b-button>
          </div>

          <div v-if="legalCases[user.id]">
            <ul class="user__legal-cases">
              <li class="legal-cases__case" v-bind:key="legalCase.id" v-for="legalCase in legalCases[user.id]">
                <div>Caso: {{ legalCase.subject }}</div>
                <div>Estado: {{ legalCase.status }}</div>
                <div>Detalle: {{ legalCase.detail }}</div>
                <div>Fecha a notificar: {{legalCase.nextNotification}}</div>
                <b-button @click="fillLegalCaseForm(legalCase.id, user.id)" variant="info">Editar Caso</b-button>
              </li>
            </ul>
          </div>

        </li>
      </ul>
    </div>
    <div v-show="!users.length">
      <p>No hay resultados</p>
    </div>

    <div>
      <modal-client-form :client-form="clientForm" :editing-user="editingUser" :users.sync="users"></modal-client-form>
      <modal-search-form :search-client-form="searchClientForm" :users.sync="users"></modal-search-form>
      <modal-legal-case-form :legal-case-form="legalCaseForm" :editing-legal-case="editingLegalCase" :static-data="staticData" :legal-case-user-id="legalCaseUserId"></modal-legal-case-form>
    </div>

  </div>
</template>

<script>
import ModalClientForm from './ModalClientForm.vue';
import ModalSearchForm from './ModalSearchForm.vue';
import ModalLegalCaseForm from './ModalLegalCaseForm.vue';
export default {
  name: 'Client',
  components: {ModalClientForm, ModalSearchForm, ModalLegalCaseForm},
  data () {
    return {
      staticData:{
        roleList: [],
        statusList: [],
        subjectList: []
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
        role:'99',
        status: '1'
      },
      legalCaseForm:{
        id: null,
        subject: null,
        userID: null,
        status: null,
        detail: null,
        nextNotification: null
      },
      searchClientForm:{
        personalID: null,
        name: null,
        lastName1: null,
        lastName2: null,
        searchBy: null
      },
      legalCases: [],
      editingLegalCase: false,
      legalCaseUserId: null,
      dateToday: null,
      editingUser: false
    }
  },
  created: function(){
      this.getStaticDataFromDB();
      this.dateToday = this.getTodayDate();
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
              body: new URLSearchParams(params),
              headers:{
              'Content-Type': 'application/x-www-form-urlencoded',
              "X-Requested-With": "XMLHttpRequest"
              }
          });

          const data = await response.json();
          csrf_name = data.csrf_name;
          csrf_hash = data.csrf_hash;
          return data;
      },
      getTodayDate: function(){
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        today.setMonth(today.getMonth() - 2)
        today.setDate(15)
        return new Date(today);
      },
      getStaticDataFromDB: async function(){

        const roleListData = await this.getRoleList();
        this.staticData.roleList = roleListData.response;

        const statusListData = await this.getStatusList();
        this.staticData.statusList = statusListData.response;

        const subjectListData = await this.getSubjectList();
        this.staticData.subjectList = subjectListData.response;

        console.log(this.staticData);
      },
      getRoleList: async function(){
        const url = 'clientes/getRoleList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      getStatusList: async function(){
        const url = 'clientes/getStatusList';
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
          body: new URLSearchParams(params),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      },
      showLegalCases: async function(id){        
        const data = await this.getLegalCasesBy('userID', id);

        this.$set(this.legalCases, id, data.response);
      },
      fillEditClientForm: async function(id){
        const data = await this.getClientBy('id', id);
        const response = data.response;
        if( response.length ){
          this.clientForm = data.response[0];
          this.editingUser = true;
          this.$bvModal.show('bv-modal-client-form');
        }
      },
      fillLegalCaseForm: async function(id, userID){
        this.legalCaseUserId = userID;
        const data = await this.getLegalCasesBy('id', id);
        const response = data.response;
        if( response.length ){
          this.legalCaseForm = data.response[0];
          this.editingLegalCase = true;
          this.$bvModal.show('bv-modal-legal-case-form');
        }
      },
      resetClientVars: function(){
        this.legalCases = [];
      },
      showLegalCaseForm: async function(userID){
        this.clearForm('legalCaseForm');
        this.editingLegalCase = false;
        this.legalCaseUserId = userID;
        this.$bvModal.show('bv-modal-legal-case-form');
      },
      clearForm: function(form){
        for(const item in this[form]){
            this[form][item] = '';
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
      }
    }
    .legal-cases{
      &__case{
        padding: 15px;
        border-bottom: 1px solid gray;
        &:last-child{
          border-bottom: none;
        }
      }
    }
  }
</style>
