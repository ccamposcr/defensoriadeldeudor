<template>
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
    </div>
</template>
 

<script>
export default {
  name: 'ModalClientForm',
  props: ["clientForm", "editingUser"],
  data () {
    return {
    }
  },
  methods: {
    showClientByPersonalID: async function(personalID){
        const data = await this.$parent.getClientByPersonalID(personalID);
        this.$emit('update:users', data.response);
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
    clearClientForm: function(){
        for(const item in this.clientForm){
            this.clientForm[item] = '';
        }
        this.clientForm.role = '99';
        this.clientForm.status = '1'
    },
    cancelClientForm: function(){
        this.clearClientForm();
        this.$bvModal.hide('bv-modal-client-form');
    }
  }
}
</script>

<style lang="scss" scoped>
</style>