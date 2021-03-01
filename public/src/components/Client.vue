<template>
  <div class="client">
    <b-button variant="info" @click="showSearchClientModal">Buscar Cliente</b-button>
    <b-button variant="info" @click="showClientFormModal">Agregar Cliente Nuevo</b-button>
    <b-button variant="info" @click="showAllClients">Ver todos los Clientes</b-button>

    <div v-show="users">
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
    <div v-show="!users">
      <p>No hay resultados</p>
    </div>

    <div>
      <b-modal id="bv-modal-client-form" hide-footer>
        <template #modal-title>
          Cliente
        </template>
        <div class="d-block">
          
          <b-form class="client__new-form">
            <input type="hidden" v-model="clientForm.id">
            <b-form-group label-for="personalID" label="Cédula">
              <b-form-input v-model="clientForm.personalID" type="text" class="form-control" id="personalID" placeholder="Cédula" :disabled="editingUser"></b-form-input>
            </b-form-group>
            <b-form-group label-for="name" label="Nombre">
              <b-form-input v-model="clientForm.name" type="text" class="form-control" id="name" placeholder="Nombre"></b-form-input>
            </b-form-group>
            <b-form-group label-for="lastName1" label="Primer Apellido">
              <b-form-input v-model="clientForm.lastName1" type="text" class="form-control" id="lastName1" placeholder="Primer Apellido"></b-form-input>
            </b-form-group>
            <b-form-group label-for="lastName2" label="Segundo Apellido">
              <b-form-input v-model="clientForm.lastName2" type="text" class="form-control" id="lastName1" placeholder="Segundo Apellido"></b-form-input>
            </b-form-group>
            <b-form-group label-for="phone" label="Teléfono">
              <b-form-input v-model="clientForm.phone" type="text" class="form-control" id="phone" placeholder="Teléfono"></b-form-input>
            </b-form-group>
            <b-form-group label-for="email" label="Email">
              <b-form-input v-model="clientForm.email" type="email" class="form-control" id="email" placeholder="Email"></b-form-input>
            </b-form-group>
            <b-form-group label-for="address" label="Dirección">
              <b-form-input v-model="clientForm.address" type="text" class="form-control" id="address" placeholder="Dirección"></b-form-input>
            </b-form-group>

            <b-button v-if="!editingUser" @click.prevent="setNewClient" type="submit" variant="primary">Agregar</b-button>
            <b-button v-if="editingUser" @click.prevent="setEditedClient" type="submit" variant="primary">Guardar</b-button>
            <b-button @click.prevent="cancelClientForm" variant="danger">Cancelar</b-button>
          </b-form>
          
        </div>
      </b-modal>

      <b-modal id="bv-modal-search-form" hide-footer>
        <template #modal-title>
          Buscar
        </template>
        <div class="d-block">

          <b-form class="client__search-form">
            <b-form-group label-for="personalID2" label="Cédula">
              <b-form-input v-model="searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cédula"></b-form-input>
            </b-form-group>
            <b-button @click.prevent="showSearchResults(searchClientForm.personalID)" type="submit" variant="primary">Buscar</b-button>
            <b-button @click.prevent="cancelSearchForm" variant="danger">Cancelar</b-button>
          </b-form>

        </div>
      </b-modal>

      <b-modal id="bv-modal-legal-case-form" hide-footer>
        <template #modal-title>
          Caso Legal
        </template>
        <div class="d-block">

          <b-form class="user__case-form">
            <b-form-group label-for="subject" label="Caso Legal">
              <input type="hidden" v-model="legalCaseForm.id">
              <b-form-select id="subject" v-model="legalCaseForm.subject" :options="staticData.subjectList" value-field="subject" text-field="subject"></b-form-select>
            </b-form-group>
            <b-form-group label-for="status" label="Estado">
              <b-form-select id="status" v-model="legalCaseForm.status" :options="staticData.statusList" value-field="status" text-field="status"></b-form-select>
            </b-form-group>
            <b-form-group label-for="detail" label="Detalle">
              <b-form-textarea id="detail" v-model="legalCaseForm.detail" placeholder="Detalle del caso" rows="3" max-rows="6"></b-form-textarea>
            </b-form-group>
            <b-form-group label-for="nextNotification" label="Fecha de Notificación">
              <b-form-datepicker :min="dateToday" id="nextNotification" v-model="legalCaseForm.nextNotification" locale="es"></b-form-datepicker>
            </b-form-group>
            <b-button v-if="!editingLegalCase" @click.prevent="setNewLegalCase" type="submit" variant="primary">Agregar</b-button>
            <b-button v-if="editingLegalCase" @click.prevent="setEditedLegalCase" type="submit" variant="primary">Guardar</b-button>
            <b-button @click.prevent="cancelLegalForm" variant="danger">Cancelar</b-button>
          </b-form>

        </div>
      </b-modal>
    </div>

  </div>
</template>

<script>
export default {
  name: 'Client',
  data () {
    return {
      staticData:{
        roleList: [],
        statusList: [],
        subjectList: []
      },
      users: [],
      clientForm:{
        id:'',
        personalID:'',
        name: '',
        lastName1: '',
        lastName2: '',
        phone: '',
        email: '',
        address: '',
        role:'99',
        status: '1'
      },
      legalCaseForm:{
        id: '',
        subject: '',
        userID: '',
        status: '',
        detail: '',
        nextNotification: ''
      },
      searchClientForm:{
        personalID: ''
      },
      legalCases: [],
      editingUser: false,
      editingLegalCase: false,
      legalCaseUserID: '',
      dateToday: null
    }
  },
  created: function(){
      this.getStaticDataFromDB();
      this.dateToday = this.getTodayDate();
  },
  methods: {
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
      getClientByPersonalID: async function(id){
        const url = 'clientes/getClientByPersonalID';
        
        const params = {
          personalID:id
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
      showSearchResults: async function(personalID){
        this.resetClientVars();
        
        const data = await this.getClientByPersonalID(personalID);

        this.users = data.response;

        this.$bvModal.hide('bv-modal-search-form');
        this.clearSearchForm();
      },
      setNewClient: async function(){
        const url = 'clientes/addClient';
        this.clientForm[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(this.clientForm),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        this.showClientByPersonalID(this.clientForm.personalID);
        this.clearClientForm();
        this.$bvModal.hide('bv-modal-client-form');
      },
      showSearchClientModal: function(){
        this.$bvModal.show('bv-modal-search-form');
      },
      showClientFormModal: function(){
        this.editingUser = false;
        this.$bvModal.show('bv-modal-client-form');
      },
      clearClientForm: function(){
        for(const item in this.clientForm){
          this.clientForm[item] = '';
        }
        this.clientForm.role = '99';
        this.clientForm.status = '1'
      },
      clearLegalCaseForm: function(){
        for(const item in this.legalCaseForm){
          this.legalCaseForm[item] = '';
        }
      },
      clearSearchForm: function(){
        for(const item in this.searchClientForm){
          this.searchClientForm[item] = '';
        }
      },
      getLegalCasesByUserID: async function(id){
        const url = 'clientes/getLegalCasesByUserID';

        const params = {
          userID:id
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
        const data = await this.getLegalCasesByUserID(id);

        this.$set(this.legalCases, id, data.response);
      },
      getClientByID: async function(id){
        const url = 'clientes/getClientByID';
        
        const params = {
          id:id
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
      fillEditClientForm: async function(id){
        const data = await this.getClientByID(id);
        const response = data.response;
        if( response.length ){
          this.clientForm = data.response[0];
          this.editingUser = true;
          this.$bvModal.show('bv-modal-client-form');
        }
      },
      getLegalCaseByID: async function(id){
        const url = 'clientes/getLegalCaseByID';

        const params = {
          id:id
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
      fillLegalCaseForm: async function(id, userID){
        this.legalCaseUserID = userID;
        const data = await this.getLegalCaseByID(id);
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
      setEditedClient: async function(){
        const url = 'clientes/editClient';
        this.clientForm[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(this.clientForm),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        this.showClientByPersonalID(this.clientForm.personalID);
        this.clearClientForm();
        this.$bvModal.hide('bv-modal-client-form');
      },
      setEditedLegalCase: async function(){
        const userID = this.legalCaseUserID;
        const url = 'clientes/editLegalCase';
        this.legalCaseForm[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(this.legalCaseForm),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        this.showLegalCases(userID);
        this.clearLegalCaseForm();
        this.$bvModal.hide('bv-modal-legal-case-form');
      },
      cancelClientForm: function(){
        this.clearClientForm();
        this.$bvModal.hide('bv-modal-client-form');
      },
      cancelSearchForm: function(){
        this.clearSearchForm();
        this.$bvModal.hide('bv-modal-search-form');
      },
      cancelLegalForm: function(){
        this.clearLegalCaseForm();
        this.$bvModal.hide('bv-modal-legal-case-form');
      },
      showClientByPersonalID: async function(personalID){
        const data = await this.getClientByPersonalID(personalID);

        this.users = data.response;

      },
      showLegalCaseForm: async function(userID){
        this.legalCaseUserID = userID;
        this.$bvModal.show('bv-modal-legal-case-form');
      },
      setNewLegalCase: async function(){
        const userID = this.legalCaseUserID;
        const url = 'clientes/addLegalCase';
        this.legalCaseForm[csrf_name] = csrf_hash;
        this.legalCaseForm['userID'] = userID;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(this.legalCaseForm),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        this.showLegalCases(userID);
        this.clearLegalCaseForm();
        this.$bvModal.hide('bv-modal-legal-case-form');
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
