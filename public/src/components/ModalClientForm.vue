<template>
    <div>
        <b-modal id="bv-modal-client-form" hide-footer novalidate="true" @hide="onCloseClientForm">
        <template #modal-title>
          Cliente
        </template>
        <div class="d-block">
          <div v-if="errors.length">
              <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
              <ul class="errors-list">
                  <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
              </ul>
          </div>
          <b-form class="client__new-form">
              <input type="hidden" v-model="$store.getters.clientForm.id">
              <b-form-group label-for="personalID" label="Cédula">
                <b-form-input @blur="checkIfClientAlreadyExists"  v-model="$store.getters.clientForm.personalID" type="text" class="form-control" id="personalID" placeholder="Cédula" :disabled="editingUser"></b-form-input>
              </b-form-group>
              <b-form-group label-for="name" label="Nombre">
                <b-form-input v-model="$store.getters.clientForm.name" type="text" class="form-control" id="name" placeholder="Nombre"></b-form-input>
              </b-form-group>
              <b-form-group label-for="lastName1" label="Primer Apellido">
                <b-form-input v-model="$store.getters.clientForm.lastName1" type="text" class="form-control" id="lastName1" placeholder="Primer Apellido"></b-form-input>
              </b-form-group>
              <b-form-group label-for="lastName2" label="Segundo Apellido">
                <b-form-input v-model="$store.getters.clientForm.lastName2" type="text" class="form-control" id="lastName1" placeholder="Segundo Apellido"></b-form-input>
              </b-form-group>
              <b-form-group label-for="phone" label="Teléfono principal" v-mask="'####-####'">
                <b-form-input v-model="$store.getters.clientForm.phone" type="text" class="form-control" id="phone" placeholder="Teléfono"></b-form-input>
              </b-form-group>
              <b-form-group label-for="phone2" label="Teléfono 2 (opcional)" v-mask="'####-####'">
                <b-form-input v-model="$store.getters.clientForm.phone2" type="text" class="form-control" id="phone2" placeholder="Teléfono 2"></b-form-input>
              </b-form-group>
              <b-form-group label-for="phone3" label="Teléfono 3 (opcional)" v-mask="'####-####'">
                <b-form-input v-model="$store.getters.clientForm.phone3" type="text" class="form-control" id="phone3" placeholder="Teléfono 3"></b-form-input>
              </b-form-group>
              <b-form-group label-for="email" label="Email principal">
                <b-form-input v-model="$store.getters.clientForm.email" type="email" class="form-control" id="email" placeholder="Email"></b-form-input>
              </b-form-group>
              <b-form-group label-for="email2" label="Email 2 (opcional)">
                <b-form-input v-model="$store.getters.clientForm.email2" type="email" class="form-control" id="email2" placeholder="Email 2"></b-form-input>
              </b-form-group>
              <b-form-group label-for="email3" label="Email 3 (opcional)">
                <b-form-input v-model="$store.getters.clientForm.email3" type="email" class="form-control" id="email3" placeholder="Email 3"></b-form-input>
              </b-form-group>
              <b-form-group label-for="job" label="Ocupación (opcional)">
                <b-form-input v-model="$store.getters.clientForm.job" type="text" class="form-control" id="job" placeholder="Ocupación"></b-form-input>
              </b-form-group>
              <b-form-group label-for="address" label="Dirección">
                <b-form-input v-model="$store.getters.clientForm.address" type="text" class="form-control" id="address" placeholder="Dirección"></b-form-input>
              </b-form-group>

              <b-button :disabled="showLoader" v-if="!editingUser" @click.prevent="checkForm(function(){setNewClient()})" type="submit" variant="primary">
                Crear
              </b-button>
              <b-button :disabled="showLoader" v-if="editingUser" @click.prevent="checkForm(function(){setEditedClient()})" type="submit" variant="primary">
                Guardar
              </b-button>
              <b-button @click.prevent="closeClientForm" variant="danger">Cancelar</b-button>
          </b-form>

          <div v-if="errors.length">
            <p class="label label-danger">Por favor, corrija el(los) error(es) del formulario</p>
          </div>
          
        </div>
      </b-modal>
    </div>
</template>
 

<script>
import repositories from '../repositories';

export default {
  name: 'ModalClientForm',
  props: ["showLoader", "editingUser"],
  data () {
    return {
      errors:[],
      URLparams: null
    }
  },
  mounted() {
    this.URLparams = this.$route.query;
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.$store.getters.clientForm.personalID){
            this.errors.push("Ingrese una identificación válida");
        }
        if(!this.$store.getters.clientForm.name){
            this.errors.push("Ingrese un nombre válido");
        }
        if(!this.$store.getters.clientForm.lastName1){
            this.errors.push("Ingrese un primer apellido válido");
        }
        if(!this.$store.getters.clientForm.lastName2){
            this.errors.push("Ingrese un segundo apellido válido");
        }
        if(!this.$store.getters.clientForm.phone){
            this.errors.push("Ingrese un teléfono válido");
        }
        if(!this.$store.getters.clientForm.email || !this.validEmail(this.$store.getters.clientForm.email)){
            this.errors.push("Ingrese un email válido");
        }
        if(this.$store.getters.clientForm.email2 && !this.validEmail(this.$store.getters.clientForm.email2)){
            this.errors.push("Ingrese un email 2 válido");
        }
        if(this.$store.getters.clientForm.email3 && !this.validEmail(this.$store.getters.clientForm.email3)){
            this.errors.push("Ingrese un email 3 válido");
        }
        if(!this.$store.getters.clientForm.address){
            this.errors.push("Ingrese una dirección válida");
        }
        if(!this.errors.length){
            callback();
        }
    },
    validEmail: function (email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
    renderClientByPersonalID: async function(personalID){
      //service, searchBy, value, callback
      await this.$emit('renderClientBy', {service:'getClientBy', searchBy:'PersonalID', value:personalID});

    },
    setNewClient: async function(){
        this.$emit('update:showLoader', true);
        const data = await repositories.addNewClient(this.$store.getters.clientForm);

        this.renderClientByPersonalID(this.$store.getters.clientForm.personalID);
        this.closeClientForm();

        this.$emit('update:showLoader', false);
        if( this.URLparams.appointmentDate ){
          this.$router.push('/inicio?appointmentDate='+this.URLparams.appointmentDate+'&clientID='+data.clientID);
        }
    },
    setEditedClient: async function(){
        this.$emit('update:showLoader', true);
        await repositories.editClient(this.$store.getters.clientForm);

        await repositories.updateClientIsInUse({'id': this.$store.getters.clientForm.id, 'inUse': 0});

        this.renderClientByPersonalID(this.$store.getters.clientForm.personalID);
        this.closeClientForm();
        this.$emit('update:showLoader', false);
    },
    clearClientForm: function(){
        const data = {
          id: '',
          personalID: '',
          name: '',
          lastName1: '',
          lastName2: '',
          phone: '',
          phone2: '',
          phone3: '',
          email: '',
          email2:'',
          email3: '',
          job: '',
          address: '',
          roleID: '0',
          status: '1',
          inUse: '0'
        };
        this.$store.commit('setClientForm', data);
        this.errors = [];
    },
    onCloseClientForm: async function(){
      if( this.$store.getters.clientForm.id ){
        this.$emit('update:showLoader', true);
        await repositories.updateClientIsInUse({'id': this.$store.getters.clientForm.id, 'inUse': 0});
        this.$emit('update:showLoader', false);
      }
      this.clearClientForm();
      if( this.URLparams.appointmentDate ){
        this.$router.push('/inicio?appointmentDate='+this.URLparams.appointmentDate);
      }
    },
    closeClientForm: function(){
      this.$bvModal.hide('bv-modal-client-form');
    },
    checkIfClientAlreadyExists: async function(){
      this.$emit('update:showLoader', true);
      const data = await repositories.getClientBy('personalID', this.$store.getters.clientForm.personalID);
      const response = data.response;
      if( response.length ){
        this.renderClientByPersonalID(this.$store.getters.clientForm.personalID);
        this.closeClientForm();
      }
      this.$emit('update:showLoader', false);
    }
  }
}
</script>

<style lang="scss" scoped>
</style>