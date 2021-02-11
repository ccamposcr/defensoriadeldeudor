<template>
  <div>
    <button class="btn btn-info" @click="showSearchClientPanel">Buscar Cliente</button>
    <button class="btn btn-info" @click="showAddNewClientPanel">Agregar Cliente Nuevo</button>

    <div v-show="panels.showAddNewClientPanel">
      <form>
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
      <form>
        <div class="form-group">
          <label for="personalID2">C&eacute;dula</label>
          <input v-model="searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cedula">
        </div>
        <button @click.prevent="getClientByID" type="submit" class="btn btn-primary">Buscar</button>
      </form>
    </div>

    <div class="clientList">
      <ul>
        <li v-bind:key="user.id" v-for="user in users">
          <div>C&eacute;dula: {{ user.personalID }}</div>
          <div>Nombre: {{ user.name }} {{ user.lastName1 }} {{ user.lastName2 }}</div>
          <div>Tel&eacute;fono: {{ user.phone }}</div>
          <div>Email: {{ user.email }}</div>
          <div>
            <button @click="editClient(user.id)" class="btn btn-info">Editar Cliente</button>
            <button @click="showLegalCases(user.id)" class="btn btn-info">Ver Casos</button>
          </div>
          <ul v-if="legalCases[user.id]">
             <li v-bind:key="legalCase.id" v-for="legalCase in legalCases[user.id]">
              <div>Caso: {{ legalCase.subject }}</div>
              <div>Estado: {{ legalCase.status }}</div>
             </li>
          </ul>
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
        role:'9',
        status: 1
      },
      searchClientForm:{
        personalID: ''
      },
      panels:{
        showSearchClientPanel: false,
        showAddNewClientPanel: false
      },
      legalCases: []
    }
  },
  created: function(){ // Perfect step to retrieve async data
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
      getClientByID: async function(){
        this.resetClientVars();
        const url = 'clientes/getClientByID';
        this.searchClientForm[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(this.searchClientForm),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        });

        const data = await response.json();
        this.users = data.response;
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
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
      showLegalCases: async function(id){
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
        this.legalCases[id] = data.response;
        console.log(this.legalCases);
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
      },
      editClient: function(id){
        console.log(id);
      },
      resetClientVars: function(){
        this.legalCases = [];
      }
  }
}
</script>

<style lang="scss" scoped>

</style>
