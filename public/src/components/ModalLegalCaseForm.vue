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
                  <b-form-group label-for="nextNotification" label="Fecha de Alerta">
                  <b-form-datepicker :min="dateToday" id="nextNotification" v-model="legalCaseForm.nextNotification" locale="es"></b-form-datepicker>
                  </b-form-group>
                  <b-button v-if="!editingLegalCase" @click.prevent="checkForm(function(){setNewLegalCase})" type="submit" variant="primary">Agregar</b-button>
                  <b-button v-if="editingLegalCase" @click.prevent="checkForm(function(){setEditedLegalCase})" type="submit" variant="primary">Guardar</b-button>
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
        if(!this.legalCaseForm.subject){
            this.errors.push("Seleccione un caso");
        }
        if(!this.legalCaseForm.statusList){
            this.errors.push("Seleccione el estado del caso");
        }
        if(!this.legalCaseForm.detail){
            this.errors.push("Ingrese el detalle del caso");
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