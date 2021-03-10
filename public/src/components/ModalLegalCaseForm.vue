<template>
    <div>
        <b-modal id="bv-modal-legal-case-form" hide-footer novalidate="true">
            <template #modal-title>
            Caso Legal
            </template>
            <div class="d-block">
              <div v-if="errors.length">
                  <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
                  <ul>
                      <li :key="error" v-for="error in errors">{{ error }}</li>
                  </ul>
              </div>
              <b-form class="user__case-form">
                  <b-form-group label-for="subject" label="Naturaleza de expediente">
                    <input type="hidden" v-model="legalCaseForm.id">
                  <b-form-select id="subject" v-model="legalCaseForm.subjectID" :options="staticData.subjectList" value-field="id" text-field="subject"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="judicialStatus" label="Estado Judicial">
                    <b-form-select id="judicialStatus" v-model="legalCaseForm.judicialStatusID" :options="staticData.judicialStatusList" value-field="id" text-field="judicialStatus"></b-form-select>
                  </b-form-group>
                  <b-form-group label-for="detail" label="Detalle">
                    <b-form-textarea id="detail" v-model="legalCaseForm.detail" placeholder="Detalle del caso" rows="3" max-rows="6"></b-form-textarea>
                  </b-form-group>
                  <b-form-group label-for="nextNotification" label="Fecha de siguiete pago">
                    <b-form-datepicker :min="dateToday" id="nextNotification" v-model="legalCaseForm.nextNotification" locale="es"></b-form-datepicker>
                  </b-form-group>
                  <b-button v-if="!editingLegalCase" @click.prevent="checkForm(function(){setNewLegalCase()})" type="submit" variant="primary">Agregar</b-button>
                  <b-button v-if="editingLegalCase" @click.prevent="checkForm(function(){setEditedLegalCase()})" type="submit" variant="primary">Guardar</b-button>
                  <b-button @click.prevent="cancelLegalForm" variant="danger">Cancelar</b-button>
              </b-form>

            </div>
        </b-modal>
    </div>
</template>
 

<script>
export default {
  name: 'ModalLegalCaseForm',
  props: ["legalCaseForm", "editingLegalCase", "staticData", "legalCaseUserId"],
  data () {
    return {
      errors:[]
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.legalCaseForm.subjectID){
            this.errors.push("Seleccione un caso");
        }
        if(!this.legalCaseForm.judicialStatusID){
            this.errors.push("Seleccione el estado judicial del caso");
        }
        if(!this.legalCaseForm.detail){
            this.errors.push("Ingrese una nota del caso");
        }
        if(!this.legalCaseForm.nextNotification){
            this.errors.push("Ingrese una fecha de alerta válida");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearLegalCaseForm: function(){
        for(const item in this.legalCaseForm){
            this.legalCaseForm[item] = '';
        }
        this.errors = [];
    },
    cancelLegalForm: function(){
        this.clearLegalCaseForm();
        this.$bvModal.hide('bv-modal-legal-case-form');
    },
    setNewLegalCase: async function(){
        const userID = this.legalCaseUserId;
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

        this.$parent.showLegalCases(userID);
        this.clearLegalCaseForm();
        this.$bvModal.hide('bv-modal-legal-case-form');
    },
    setEditedLegalCase: async function(){
        const userID = this.legalCaseUserId;
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

        this.$parent.showLegalCases(userID);
        this.clearLegalCaseForm();
        this.$bvModal.hide('bv-modal-legal-case-form');
    }
  }
}
</script>

<style lang="scss" scoped>
</style>


<!--
2 EStados

Estado Judicial
-> Revision de expediente
-> Auduencia
-> Señalamiento de remate
-> Prevenci0n
-> Revocatoria
-> Apelacipon
-> Apelacion por inadmision
-> Expresion de agravios
-> Aclaracion y Adicion
-> Oposicion a intereses
-> Actividad procesal defectuosa
-> Incidente de anulidad de remate
-> Recurso de revision (Sala de casacion)
-> Incidente de cobro de honorarios
-> Varios
-> Informacion al cliente
-> Archivo
-> Archivo definitivo


Estado Administrativo
-> Moroso
-> Cancelado
-> Al Dia
-> Al Dia pendiente

Ubicacion del expediente
-> Archivo
-> Lista de funcionarios ->  Asignado X Usuario -> el que tiene el expediente


Notas o detalle por usuario con fecha -> historial


Codigo interno de expediente


Fecha de cobro
_______________________________________________-
Citas por cliente
Fecha de la cita


-->