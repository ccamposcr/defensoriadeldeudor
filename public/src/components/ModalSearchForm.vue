<template>
    <div>
        <b-modal id="bv-modal-search-form" hide-footer novalidate="true" @hide="cancelSearchForm">
            <template #modal-title>
            Buscar
            </template>
            <div class="d-block">
                <div v-if="errors.length">
                    <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
                    <ul class="errors-list">
                        <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>
                <b-form class="client__search-form">
                    <b-form-group label="Buscar por">
                        <b-form-radio-group
                            id="search-by"
                            v-model="searchClientForm.searchBy"
                            name="search-by"
                            label="Seleccione la opción por la que desea buscar"
                        >
                        <b-form-radio value="personalID">Cédula</b-form-radio>
                        <b-form-radio value="name">Nombre</b-form-radio>
                        <b-form-radio value="code" >Código interno</b-form-radio>
                        <b-form-radio value="internalCode">Número de expediente</b-form-radio>
                        </b-form-radio-group>
                    </b-form-group>

                    <b-form-group v-show="searchClientForm.searchBy == 'personalID'" label-for="personalID2" label="Buscar por cédula">
                        <b-form-input v-model="searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cédula"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="searchClientForm.searchBy == 'name'" label-for="name2" label="Buscar por nombre">
                        <b-form-input v-model="searchClientForm.name" type="text" class="form-control" id="name2" placeholder="Nombre"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="searchClientForm.searchBy == 'code'" label-for="code" label="Buscar por Código">
                        <b-form-input v-model="searchClientForm.code" type="text" class="form-control" id="code" placeholder="Código"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="searchClientForm.searchBy == 'internalCode'" label-for="internalCode" label="Buscar por número de expediente">
                        <b-form-input v-model="searchClientForm.internalCode" type="text" class="form-control" id="internalCode" placeholder="Número de Expediente"></b-form-input>
                    </b-form-group>
                    <b-button :disabled="showLoader" v-show="searchClientForm.searchBy" @click.prevent="checkForm(function(){showSearchResults()})" type="submit" variant="primary">
                        Buscar
                    </b-button>
                    <b-button @click.prevent="cancelSearchForm" variant="danger">Cancelar</b-button>
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
  name: 'ModalSearchForm',
  props: ["showLoader", "searchClientForm", "users", "locationStaticData", "legalCases"],
  data () {
    return {
        errors:[]
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(this.searchClientForm.searchBy == 'personalID' && !this.searchClientForm.personalID){
            this.errors.push("Ingrese una identificación válida");
        }
        if(this.searchClientForm.searchBy == 'name' && !this.searchClientForm.name){
            this.errors.push("Ingrese un nombre válido");
        }
        if(this.searchClientForm.searchBy == 'code' && !this.searchClientForm.code){
            this.errors.push("Ingrese un código válido");
        }
        if(this.searchClientForm.searchBy == 'internalCode' && !this.searchClientForm.internalCode){
            this.errors.push("Ingrese un número de expediente válido");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearSearchForm: function(){
      for(const item in this.searchClientForm){
          this.searchClientForm[item] = null;
      }
      this.searchClientForm.searchBy = 'personalID';
      this.errors = [];
    },
    cancelSearchForm: function(){
        this.$bvModal.hide('bv-modal-search-form');
        this.clearSearchForm();
    },
    showSearchResults: async function(){
        this.$emit('update:showLoader', true);
        let data = null;
        if( this.searchClientForm.searchBy == 'code' || this.searchClientForm.searchBy == 'internalCode' ){
            data = await repositories.getClientByLegalCase(this.searchClientForm.searchBy, this.searchClientForm[this.searchClientForm.searchBy]);
            const clientResponse = data.response;
            if(clientResponse.length){
                this.$emit('update:users', clientResponse);

                const legalCaseID = clientResponse[0].legalCaseID;
                const userID = clientResponse[0].id;
                const legalCasedata = await repositories.getLegalCasesBy('id', legalCaseID);
                const legalCaseResponse = legalCasedata.response;

                if( legalCaseResponse.length ){
                    legalCaseResponse.forEach(item => {
                        item.location = item.locationID != '999' ? item.location = item.name + ' ' + item.lastName1 + ' ' + item.lastName2 : this.locationStaticData['999'];
                    });
                    this.legalCases[userID] = legalCaseResponse;
                    this.$emit('update:legalCases', this.legalCases);
                }
            }
        }else{
            data = await repositories.getClientBy(this.searchClientForm.searchBy, this.searchClientForm[this.searchClientForm.searchBy]);
            this.$emit('update:users', data.response);
        }
        
        this.cancelSearchForm();
        this.$emit('update:showLoader', false);
      }
  }
}
</script>

<style lang="scss" scoped>
</style>