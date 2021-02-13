<template>
  <div class="client">
    <button class="btn btn-info" @click="showSearchClientPanel">Buscar Cliente</button>
    <button class="btn btn-info" @click="showAddNewClientPanel">Agregar Cliente Nuevo</button>

    <div v-show="panels.showAddNewClientPanel">
      <form class="client__new-form">
        <div class="form-group">
          <label for="personalID">C&eacute;dula</label>
          <input v-model="newClientForm.personalID" type="text" class="form-control" id="personalID" placeholder="Cedula">
        </div>
        <div class="form-group">
          <label for="name">Nombre</label>
          <input v-model="newClientForm.name" type="text" class="form-control" id="name" placeholder="Nombre">
        </div>
        <div class="form-group">
          <label for="lastName1">Primer Apellido</label>
          <input v-model="newClientForm.lastName1" type="text" class="form-control" id="lastName1" placeholder="Primer Apellido">
        </div>
        <div class="form-group">
          <label for="lastName2">Segundo Apellido</label>
          <input v-model="newClientForm.lastName2" type="text" class="form-control" id="lastName1" placeholder="Segundo Apellido">
        </div>
        <div class="form-group">
          <label for="phone">Tel&eacute;fono</label>
          <input v-model="newClientForm.phone" type="text" class="form-control" id="phone" placeholder="Telefono">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input v-model="newClientForm.email" type="email" class="form-control" id="email" placeholder="Email">
        </div>
        <div class="form-group">
          <label for="address">Direcci&oacute;n</label>
          <input v-model="newClientForm.address" type="text" class="form-control" id="address" placeholder="Direccion">
        </div>
        <button @click.prevent="addNewClient" type="submit" class="btn btn-primary">Agregar</button>
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

    <div>
      <ul class="client__list">
        <li class="list__user"  v-bind:key="user.id" v-for="user in users">
          <div>C&eacute;dula: {{ user.personalID }}</div>
          <div>Nombre: {{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</div>
          <div>Tel&eacute;fono: {{ user.phone }}</div>
          <div>Email: {{ user.email }}</div>
          <div>
            <button @click="editClient(user.id)" class="btn btn-info">Editar Cliente</button>
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
      newClientForm:{
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
        showAddNewClientPanel: false,
        showAddLegalCasePanel: false,
        showLegalCasesPanel: false
      },
      legalCases: []
    }
  },
  created: function(){
      this.getAllUsers();
  },
  methods: {
      getAllUsers: async function(){
        this.resetClientVars();
        const url = 'clientes/getAllClients';
        const response = await fetch(url);
        const data = await response.json();
        this.users = data.response;
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
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
      showSearchResults: async function(id){
        this.resetClientVars();
        
        const data = await this.getClientByPersonalID(id);

        this.users = data.response;
      },
      addNewClient: async function(){
        
        const url = 'clientes/addClient';
        this.newClientForm[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(this.newClientForm),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        this.getAllUsers();
      },
      showSearchClientPanel: function(){
        this.hideAllPanels();
        this.panels.showSearchClientPanel = true;
      },
      showAddNewClientPanel: function(){
        this.hideAllPanels();
        this.panels.showAddNewClientPanel = true;
      },
      hideAllPanels: function(){
        for(const panel in this.panels){
          this.panels[panel] = false;
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
        this.panels.showLegalCasesPanel = true;
        
        const data = await this.getLegalCasesByID(id);

        this.$set(this.legalCases, id, data.response);
      },
      editClient: function(id){
        this.getClientByPersonalID(id);
        console.log(id);
        this.panels.showAddNewClientPanel;
      },
      resetClientVars: function(){
        this.legalCases = [];
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
      }
    }
  }
</style>
