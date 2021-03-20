<template>
  <div>
    <b-modal id="bv-modal-appointment-form" hide-footer novalidate="true">
    <template #modal-title>
      Citas
    </template>
    <div class="d-block">
      <div v-if="errors.length">
          <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
          <ul>
              <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
          </ul>
      </div>
      <b-form class="appointment__new-form">
         
          <b-form-group label-for="client" label="Seleccione el cliente">
            <b-form-select id="client" v-model="appointmentForm.client" :options="appointmentForm.clientList" value-field="id" text-field="client"></b-form-select>
          </b-form-group>

          <b-button v-if="!editingAppointment" @click.prevent="checkForm(function(){setNewAppointment()})" type="submit" variant="primary">Agregar</b-button>
          <b-button v-if="editingAppointment" @click.prevent="checkForm(function(){setEditedAppointment()})" type="submit" variant="primary">Guardar</b-button>
          <b-button @click.prevent="cancelAppointmentForm" variant="danger">Cancelar</b-button>
      </b-form>
      
    </div>
  </b-modal>
</div>
</template>

<script>

export default {
  name: 'ModalAppointmentForm',
  props: ["appointmentForm", "editingAppointment"],
  data () {
    return {
      errors:[]
    }
  },
  methods: {
    clearAppointmentForm: function(){
      for(const item in this.appointmentForm){
          this.appointmentForm[item] = null;
      }
      this.errors = [];
    },
    cancelAppointmentForm: function(){
      this.clearAppointmentForm();
      this.$bvModal.hide('bv-modal-appointment-form');
    }

  }
}
</script>

<style lang="scss" scoped>

</style>
