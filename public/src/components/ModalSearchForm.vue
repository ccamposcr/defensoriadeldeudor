<template>
    <div>
        <b-modal id="bv-modal-search-form" hide-footer novalidate="true">
            <template #modal-title>
            Buscar
            </template>
            <div class="d-block">
                <div v-if="errors.length">
                    <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
                    <ul>
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
                        <b-form-radio value="lastName1" >Primer apellido</b-form-radio>
                        <b-form-radio value="lastName2">Segundo apellido</b-form-radio>
                        </b-form-radio-group>
                    </b-form-group>

                    <b-form-group v-show="searchClientForm.searchBy == 'personalID'" label-for="personalID2" label="Buscar por cédula">
                        <b-form-input v-model="searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cédula"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="searchClientForm.searchBy == 'name'" label-for="name2" label="Buscar por nombre">
                        <b-form-input v-model="searchClientForm.name" type="text" class="form-control" id="name2" placeholder="Nombre"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="searchClientForm.searchBy == 'lastName1'" label-for="lastName1_2" label="Buscar por primer apellido">
                        <b-form-input v-model="searchClientForm.lastName1" type="text" class="form-control" id="lastName1_2" placeholder="Primer Apellido"></b-form-input>
                    </b-form-group>
                    <b-form-group v-show="searchClientForm.searchBy == 'lastName2'" label-for="lastName2_2" label="Buscar por segundo apellido">
                        <b-form-input v-model="searchClientForm.lastName2" type="text" class="form-control" id="lastName2_2" placeholder="Segundo Apellido"></b-form-input>
                    </b-form-group>
                    <b-button v-show="searchClientForm.searchBy" @click.prevent="checkForm(function(){showSearchResults()})" type="submit" variant="primary">Buscar</b-button>
                    <b-button @click.prevent="cancelSearchForm" variant="danger">Cancelar</b-button>
                </b-form>

            </div>
        </b-modal>
    </div>
</template>
 

<script>
export default {
  name: 'ModalSearchForm',
  props: ["searchClientForm"],
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
        if(this.searchClientForm.searchBy == 'lastName1' && !this.searchClientForm.lastName1){
            this.errors.push("Ingrese el primer apellido válido");
        }
        if(this.searchClientForm.searchBy == 'lastName2' && !this.searchClientForm.lastName2){
            this.errors.push("Ingrese el segundo apellido válido");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearSearchForm: function(except){
        for(const item in this.searchClientForm){
            if( !except.includes(item) ){
                this.searchClientForm[item] = null;
            }
        }
        this.errors = [];
    },
    cancelSearchForm: function(){
        this.clearSearchForm(['searchBy']);
        this.$bvModal.hide('bv-modal-search-form');
    },
    showSearchResults: async function(){   
        let data = null;
        switch(this.searchClientForm.searchBy){
            case 'personalID':
                data = await this.$parent.getClientBy('personalID', this.searchClientForm.personalID);
                this.$emit('update:users', data.response);
                break;
            case 'name':
                data = await this.$parent.getClientBy('name', this.searchClientForm.name);
                this.$emit('update:users', data.response);
                break;
            case 'lastName1':
                data = await this.$parent.getClientBy('lastName1', this.searchClientForm.lastName1);
                this.$emit('update:users', data.response);
                break;
            case 'lastName2':
                data = await this.$parent.getClientBy('lastName2', this.searchClientForm.lastName2);
                this.$emit('update:users', data.response);
                break;
        }    
        
        this.$bvModal.hide('bv-modal-search-form');
        this.clearSearchForm(['searchBy']);
      }
  }
}
</script>

<style lang="scss" scoped>
</style>