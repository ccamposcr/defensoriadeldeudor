<template>
  <div class="client">
    <button class="btn btn-info" @click="showSearchClientPanel">Buscar Cliente</button>
    <button class="btn btn-info" @click="showClientFormPanel">Agregar Cliente Nuevo</button>
    <button class="btn btn-info" @click="showAllClients">Ver todos los Clientes</button>

    <div v-show="panels.showClientFormPanel">
      <form class="client__new-form">
        <input v-model="clientForm.id" type="hidden">
        <div class="form-group">
          <label for="personalID">C&eacute;dula</label>
          <input v-model="clientForm.personalID" type="text" class="form-control" id="personalID" placeholder="Cedula" :disabled="editingUser">
        </div>
        <div class="form-group">
          <label for="name">Nombre</label>
          <input v-model="clientForm.name" type="text" class="form-control" id="name" placeholder="Nombre">
        </div>
        <div class="form-group">
          <label for="lastName1">Primer Apellido</label>
          <input v-model="clientForm.lastName1" type="text" class="form-control" id="lastName1" placeholder="Primer Apellido">
        </div>
        <div class="form-group">
          <label for="lastName2">Segundo Apellido</label>
          <input v-model="clientForm.lastName2" type="text" class="form-control" id="lastName1" placeholder="Segundo Apellido">
        </div>
        <div class="form-group">
          <label for="phone">Tel&eacute;fono</label>
          <input v-model="clientForm.phone" type="text" class="form-control" id="phone" placeholder="Telefono">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input v-model="clientForm.email" type="email" class="form-control" id="email" placeholder="Email">
        </div>
        <div class="form-group">
          <label for="address">Direcci&oacute;n</label>
          <input v-model="clientForm.address" type="text" class="form-control" id="address" placeholder="Direccion">
        </div>
        <button v-if="!editingUser" @click.prevent="setNewClient" type="submit" class="btn btn-primary">Agregar</button>
        <button v-if="editingUser" @click.prevent="setEditedClient" type="submit" class="btn btn-primary">Guardar</button>
        <button @click.prevent="cancelClientForm" class="btn btn-danger">Cancelar</button>
      </form>
    </div>

    <div v-show="panels.showSearchClientPanel">
      <form class="client__search-form">
        <div class="form-group">
          <label for="personalID2">C&eacute;dula</label>
          <input v-model="searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cedula">
        </div>
        <button @click.prevent="showSearchResults(searchClientForm.personalID)" type="submit" class="btn btn-primary">Buscar</button>
      </form>
    </div>

    <div v-show="users && panels.showClientListPanel">
      <ul class="client__list">
        <li class="list__user" v-bind:key="user.id" v-for="user in users">
          <div>C&eacute;dula: {{ user.personalID }}</div>
          <div>Nombre: {{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</div>
          <div>Tel&eacute;fono: {{ user.phone }}</div>
          <div>Email: {{ user.email }}</div>
          <div>Direcci&oacute;n: {{ user.address }}</div>
          <div>
            <button @click="fillEditClientForm(user.id)" class="btn btn-info">Editar Cliente</button>
            <button @click="addLegalCase(user.id)" class="btn btn-info">Agregar Caso</button>
            <button @click="showLegalCases(user.id)" class="btn btn-info">Ver Casos</button>

            <div v-show="panels.showAddLegalCasePanel">
              <form class="user__case-form">
                <div class="form-group">
                  <label for="personalID2">C&eacute;dula</label>
                  <input v-model="searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cedula">
                </div>
                <button @click.prevent="" type="submit" class="btn btn-primary">Agregar</button>
              </form>
            </div>
          </div>

          <div v-if="legalCases[user.id] && panels.showLegalCasesPanel">
            <ul class="user__legal-cases">
              <li class="legal-cases__case" v-bind:key="legalCase.id" v-for="legalCase in legalCases[user.id]">
                <div>Caso: {{ legalCase.subject }}</div>
                <div>Estado: {{ legalCase.status }}</div>
                <button @click="editCase(legalCase.id)" class="btn btn-info">Editar Caso</button>
              </li>
            </ul>
          </div>

        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Client',
  data () {
    return {
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
        status: 1
      },
      searchClientForm:{
        personalID: ''
      },
      panels:{
        showSearchClientPanel: false,
        showClientFormPanel: false,
        showAddLegalCasePanel: false,
        showLegalCasesPanel: false,
        showClientListPanel: false
      },
      legalCases: [],
      editingUser: false
    }
  },
  created: function(){
      //this.getAllUsers();
  },
  methods: {
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

        this.hideAllPanels();
        this.panels.showClientListPanel = true;
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

        this.hideAllPanels();
        this.panels.showClientListPanel = true;
        this.panels.showSearchClientPanel = true;
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
        //this.getAllUsers();
        this.showClientByPersonalID(this.clientForm.personalID);
        this.clearClientForm();
      },
      showSearchClientPanel: function(){
        this.hideAllPanels();
        this.panels.showSearchClientPanel = true;
      },
      showClientFormPanel: function(){
        this.hideAllPanels();
        this.editingUser = false;
        this.panels.showClientFormPanel = true;
      },
      hideAllPanels: function(){
        for(const panel in this.panels){
          this.panels[panel] = false;
        }
      },
      clearClientForm: function(){
        for(const item in this.clientForm){
          this.clientForm[item] = '';
        }
      },
      getLegalCasesByID: async function(id){
        const url = 'clientes/getLegalCasesByID';

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
        this.hideAllPanels();
        this.panels.showClientListPanel = true;
        this.panels.showLegalCasesPanel = true;
        
        const data = await this.getLegalCasesByID(id);

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
          this.hideAllPanels();
          this.panels.showClientFormPanel = true;
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
        this.panels.showClientFormPanel = false;
        //this.getAllUsers();
        this.showClientByPersonalID(this.clientForm.personalID);
        this.clearClientForm();
      },
      cancelClientForm: function(){
        this.panels.showClientFormPanel = false;
      },
      showClientByPersonalID: async function(personalID){
        const data = await this.getClientByPersonalID(personalID);

        this.users = data.response;

        this.hideAllPanels();
        this.panels.showClientListPanel = true;
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
