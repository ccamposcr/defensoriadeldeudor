<template>
    <div>
        <b-modal id="bv-modal-search-form" hide-footer novalidate="true" @hide="onCloseSearchForm">
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
                            v-model="$store.getters.searchClientForm.searchBy"
                            name="search-by"
                            label="Seleccione la opción por la que desea buscar"
                        >
                        <b-form-radio value="personalID">Cédula</b-form-radio>
                        <b-form-radio value="name">Nombre</b-form-radio>
                        <b-form-radio value="code" >Código interno</b-form-radio>
                        <b-form-radio value="internalCode">Número de expediente</b-form-radio>
                        </b-form-radio-group>
                    </b-form-group>

                    <b-form-group v-show="$store.getters.searchClientForm.searchBy == 'personalID'" label-for="personalID2" label="Buscar por cédula">
                        <b-form-input v-model="$store.getters.searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cédula"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="$store.getters.searchClientForm.searchBy == 'name'" label-for="name2" label="Buscar por nombre">
                        <b-form-input v-model="$store.getters.searchClientForm.name" type="text" class="form-control" id="name2" placeholder="Nombre"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="$store.getters.searchClientForm.searchBy == 'code'" label-for="code" label="Buscar por Código">
                        <b-form-input v-model="$store.getters.searchClientForm.code" type="text" class="form-control" id="code" placeholder="Código"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="$store.getters.searchClientForm.searchBy == 'internalCode'" label-for="internalCode" label="Buscar por número de expediente">
                        <b-form-input v-model="$store.getters.searchClientForm.internalCode" type="text" class="form-control" id="internalCode" placeholder="Número de Expediente"></b-form-input>
                    </b-form-group>
                    <b-button :disabled="$store.getters.showLoader" v-show="$store.getters.searchClientForm.searchBy" @click.prevent="checkForm(function(){showSearchResults()})" type="submit" variant="primary">
                        Buscar
                    </b-button>
                    <b-button @click.prevent="closeSearchForm" variant="danger">Cancelar</b-button>
                </b-form>

                <div v-if="errors.length">
                    <p class="label label-danger">Por favor, corrija el(los) error(es) del formulario</p>
                </div>

            </div>
        </b-modal>
    </div>
</template>
 

<script>

export default {
  name: 'ModalSearchForm',
  props: [],
  data () {
    return {
        errors:[]
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(this.$store.getters.searchClientForm.searchBy == 'personalID' && !this.$store.getters.searchClientForm.personalID){
            this.errors.push("Ingrese una identificación válida");
        }
        if(this.$store.getters.searchClientForm.searchBy == 'name' && !this.$store.getters.searchClientForm.name){
            this.errors.push("Ingrese un nombre válido");
        }
        if(this.$store.getters.searchClientForm.searchBy == 'code' && !this.$store.getters.searchClientForm.code){
            this.errors.push("Ingrese un código válido");
        }
        if(this.$store.getters.searchClientForm.searchBy == 'internalCode' && !this.$store.getters.searchClientForm.internalCode){
            this.errors.push("Ingrese un número de expediente válido");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearSearchForm: function(){
        const data = {
            personalID: '',
            name: '',
            code: '',
            internalCode: '',
            searchBy: 'personalID'
        }
        this.$store.commit('setSearchClientForm', data);
        this.errors = [];
    },
    closeSearchForm: function(){
        this.$bvModal.hide('bv-modal-search-form');
    },
    onCloseSearchForm: function(){
        this.clearSearchForm();
    },
    showSearchResults: async function(){
        const callback = async (response) => {
       
            if( response.length ){
                const dataResponse = response[0];
                const legalCaseID = dataResponse.legalCaseID;
                const userID = dataResponse.id;

                //searchBy, value, userID, callback
                await this.$emit('renderLegalCases', {searchBy:'id', value:legalCaseID, userID:userID});
            }
        }

        if( this.$store.getters.searchClientForm.searchBy == 'code' || this.$store.getters.searchClientForm.searchBy == 'internalCode' ){
            //service, searchBy, value, callback
            await this.$emit('renderClientBy', {service:'getClientByLegalCase', searchBy:this.$store.getters.searchClientForm.searchBy, value:this.$store.getters.searchClientForm[this.$store.getters.searchClientForm.searchBy], callback: callback});
            
        }else{
            //service, searchBy, value, callback
            await this.$emit('renderClientBy', {service:'getClientBy', searchBy:this.$store.getters.searchClientForm.searchBy, value:this.$store.getters.searchClientForm[this.$store.getters.searchClientForm.searchBy]});
        }
        
        this.closeSearchForm();
    }
  }
}
</script>

<style lang="scss" scoped>
</style>