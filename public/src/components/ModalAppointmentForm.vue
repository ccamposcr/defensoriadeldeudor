<template>
  <div>
    <b-modal id="bv-modal-appointment-form" hide-footer novalidate="true" @hide="cancelAppointmentForm" @show="getAllClients">
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
            <div><strong>Fecha y Hora de la Cita:</strong> {{appointmentForm.date}}</div>
          </b-form-group>

          <b-form-group label-for="client" label="Seleccione el cliente">
            <b-form-select id="client" v-model="appointmentForm.userID" :options="appointmentForm.clientList" value-field="id" text-field="client"></b-form-select>
            <strong>Filtre la lista de clientes para una búsqueda más rápida</strong>
          </b-form-group>

          <b-form-group label-for="filter" label="Filtrar lista clientes">
            <b-form-input @keyup="filter" v-model="appointmentForm.filterBy" type="text" class="form-control" id="filter" placeholder="Ingrese la Cédula, o el Nombre, o el Apellido"></b-form-input>
          </b-form-group>
          

          <b-form-group label="Ó agregue un Cliente Nuevo">
            <b-button @click="$router.push('/clientes?showNewClientForm=true&appointmentDate='+appointmentForm.date)" variant="success">Agregar Cliente Nuevo</b-button>
          </b-form-group>

          <b-form-group label-for="internalUser" label="Asigne el funcionario que atiende la cita">
            <b-form-select id="internalUser" v-model="appointmentForm.internalUserID" :options="appointmentForm.usersList" value-field="id" text-field="client"></b-form-select>
          </b-form-group>

          <b-form-group label-for="appointmentType" label="Tipo de Cita">
            <b-form-select id="appointmentType" v-model="appointmentForm.appointmentTypeID" :options="staticData.appointmentTypeList" value-field="id" text-field="type"></b-form-select>
          </b-form-group>

          <b-form-group label-for="filter" label="Seleccione el color de la alerta">
            <b-form-input v-model="appointmentForm.alertColor" type="color" class="form-control" id="filter" placeholder="Color de la alerta"></b-form-input>
          </b-form-group>

          <b-button :disabled="showLoader" @click.prevent="checkForm(function(){setNewAppointment()})" type="submit" variant="primary">
            Agendar
          </b-button>
          <b-button @click.prevent="cancelAppointmentForm" variant="danger">Cancelar</b-button>

          
          <div v-if="errors.length">
            <p class="label label-danger">Por favor, corrija el(los) error(es) del formulario</p>
          </div>

      </b-form>
      
    </div>
  </b-modal>
</div>
</template>

<script>
import repositories from '../repositories';

export default {
  name: 'ModalAppointmentForm',
  props: ["showLoader", "appointmentForm", "editingAppointment", "date", "staticData"],
  data () {
    return {
      errors:[],
      clientList: []
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.appointmentForm.userID){
            this.errors.push("Seleccione un cliente");
        }
        if(!this.appointmentForm.internalUserID){
            this.errors.push("Seleccione un funcionario");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearAppointmentForm: function(){
      for(const item in this.appointmentForm){
          this.appointmentForm[item] = null;
      }
      this.errors = [];
    },
    cancelAppointmentForm: function(){
      this.$bvModal.hide('bv-modal-appointment-form');
      this.clearAppointmentForm();
    },
    getAllClients: async function(){
      this.$emit('update:showLoader', true);
      const dataClients = await repositories.getAllClients();
      this.clientList = dataClients.response;
      this.clientList.forEach(item => {
        item['client'] = item.personalID + ' -> ' + item.name + ' ' + item.lastName1 + ' ' + item.lastName2;
        item['userID'] = item.id;
      });
      this.$set(this.appointmentForm, 'clientList', this.clientList);

      const dataUsers = await repositories.getAllUsers();
      this.usersList = dataUsers.response;
      this.usersList.forEach(item => {
        item['client'] = item.personalID + ' -> ' + item.name + ' ' + item.lastName1 + ' ' + item.lastName2;
        item['userID'] = item.id;
      });
      this.$set(this.appointmentForm, 'usersList', this.usersList);
    
      this.$emit('update:showLoader', false);
    },
    filter: function(){
      this.appointmentForm['clientList'] =  this.clientList.filter((client) => {
          return client.personalID.toLowerCase().includes(this.appointmentForm.filterBy.toLowerCase()) ||
                client.name.toLowerCase().includes(this.appointmentForm.filterBy.toLowerCase()) ||
                client.lastName1.toLowerCase().includes(this.appointmentForm.filterBy.toLowerCase()) ||
                client.lastName2.toLowerCase().includes(this.appointmentForm.filterBy.toLowerCase());
      });
    },
    setNewAppointment: async function(){
      this.$emit('update:showLoader', true);
      this.appointmentForm['madeByUserID'] = loggedINUserID;
      await repositories.addNewAppointment(this.appointmentForm);
      this.cancelAppointmentForm();
      const start = this.date.start;
      const end = this.date.end;
      this.$parent.fetchEvents({start, end});
      this.$emit('update:showLoader', false);
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
