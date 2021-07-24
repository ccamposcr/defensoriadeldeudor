<template>
  <div>
    <b-modal id="bv-modal-appointment-form" hide-footer novalidate="true" @hide="onCloseAppointmentForm" @show="fillAppointmentForm">
    <template #modal-title>
      Citas
    </template>
    <div class="d-block">
      <div v-if="errors.length">
          <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
          <ul class="errors-list">
              <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
          </ul>
      </div>
      <b-form class="appointment__new-form">
          <b-form-group>
            <div><strong>Fecha y Hora de la Cita:</strong> {{$store.getters.appointmentForm.date}}</div>
          </b-form-group>

          <b-form-group label-for="client" label="Seleccione el cliente">
            <b-form-select id="client" v-model="$store.getters.appointmentForm.userID" :options="$store.getters.appointmentForm.clientList" value-field="id" text-field="client"></b-form-select>
            <strong>Filtre la lista de clientes para una búsqueda más rápida</strong>
          </b-form-group>

          <b-form-group label-for="filter" label="Filtrar lista clientes">
            <b-form-input @keyup="filter" v-model="$store.getters.appointmentForm.filterBy" type="text" class="form-control" id="filter" placeholder="Ingrese la Cédula, o el Nombre, o el Apellido"></b-form-input>
          </b-form-group>
          

          <b-form-group label="Ó agregue un Cliente Nuevo">
            <b-button @click="$router.push('/clientes?showNewClientForm=true&appointmentDate='+$store.getters.appointmentForm.date)" variant="success">Agregar Cliente Nuevo</b-button>
          </b-form-group>

          <b-form-group label-for="internalUser" label="Asigne el funcionario que atiende la cita">
            <b-form-select id="internalUser" v-model="$store.getters.appointmentForm.internalUserID" :options="$store.getters.appointmentForm.usersList" value-field="id" text-field="client"></b-form-select>
          </b-form-group>

          <b-form-group v-if="checkAccessList('agendar tipo cita')" label-for="appointmentType" label="Tipo de Cita">
            <b-form-select id="appointmentType" v-model="$store.getters.appointmentForm.appointmentTypeID" :options="$store.getters.staticData.appointmentTypeList" value-field="id" text-field="type"></b-form-select>
          </b-form-group>

          <b-form-group label-for="filter" label="Seleccione el color de la alerta">
            <b-form-input v-model="$store.getters.appointmentForm.alertColor" type="color" class="form-control" id="filter" placeholder="Color de la alerta"></b-form-input>
          </b-form-group>

          <b-button :disabled="$store.getters.showLoader" @click.prevent="checkForm(function(){setNewAppointment()})" type="submit" variant="primary">
            Agendar
          </b-button>
          <b-button @click.prevent="closeAppointmentForm" variant="danger">Cancelar</b-button>

          
          <div v-if="errors.length">
            <p class="label label-danger">Por favor, corrija el(los) error(es) del formulario</p>
          </div>

      </b-form>
      
    </div>
  </b-modal>
</div>
</template>

<script>
import repositories from '../../repositories';
 import global from '../../global';

export default {
  name: 'ModalAppointmentForm',
  props: ["editingAppointment", "date"],
  data () {
    return {
      errors:[]
    }
  },
  methods: {
    checkAccessList: function(action){
        return global.checkAccessList(action);
    },
    checkForm: function(callback){
        this.errors = [];
        if(!this.$store.getters.appointmentForm.userID){
            this.errors.push("Seleccione un cliente");
        }
        if(!this.$store.getters.appointmentForm.internalUserID){
            this.errors.push("Seleccione un funcionario");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearAppointmentForm: function(){
      const data = {
          date: '',
          userID: '',
          internalUserID: '',
          madeByUserID: '',
          filterBy: '',
          clientList: [],
          usersList: [],
          alertColor: '#28a745',
          appointmentTypeID: ''
      };
      this.$store.commit('setAppointmentForm', data);
      this.errors = [];
    },
    closeAppointmentForm: function(){
      this.$bvModal.hide('bv-modal-appointment-form');
    },
    onCloseAppointmentForm: function(){
      this.clearAppointmentForm();
    },
    fillAppointmentForm: async function(){
      this.$store.commit('setShowLoader', true);

      await this.$store.dispatch('fillAppointmentForm');
    
      this.$store.commit('setShowLoader', false);
    },
    filter: function(){
      this.$store.getters.appointmentForm.clientList =  this.$store.getters.appointmentOriginalClientList.filter((client) => {
          return client.personalID.toLowerCase().includes(this.$store.getters.appointmentForm.filterBy.toLowerCase()) ||
                client.name.toLowerCase().includes(this.$store.getters.appointmentForm.filterBy.toLowerCase()) ||
                client.lastName1.toLowerCase().includes(this.$store.getters.appointmentForm.filterBy.toLowerCase()) ||
                client.lastName2.toLowerCase().includes(this.$store.getters.appointmentForm.filterBy.toLowerCase());
      });
    },
    setNewAppointment: async function(){
      this.$store.commit('setShowLoader', true);
      this.$store.commit('setAppointmentFormBy', {data:loggedINUserID, by:'madeByUserID'});
      //OK
      await repositories.addNewAppointment(this.$store.getters.appointmentForm);
      this.closeAppointmentForm();
      const start = this.date.start;
      const end = this.date.end;
      await this.$emit('fetchEvents', {start, end});
      this.$store.commit('setShowLoader', false);
    }
  }
}
</script>

<style lang="scss">
</style>
