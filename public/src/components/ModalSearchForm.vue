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
                        <li :key="error" v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>
                <b-form class="client__search-form">
                    <b-form-group label-for="personalID2" label="Cédula">
                    <b-form-input v-model="searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cédula"></b-form-input>
                    </b-form-group>
                    <b-button @click.prevent="checkForm(function(){showSearchResults(searchClientForm.personalID)})" type="submit" variant="primary">Buscar</b-button>
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
        if(!this.searchClientForm.personalID){
            this.errors.push("Ingrese una identificación válida");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearSearchForm: function(){
        for(const item in this.searchClientForm){
            this.searchClientForm[item] = '';
        }
        this.errors = [];
    },
    cancelSearchForm: function(){
        this.clearSearchForm();
        this.$bvModal.hide('bv-modal-search-form');
    },
    showSearchResults: async function(personalID){        
        const data = await this.$parent.getClientByPersonalID(personalID);
        this.$emit('update:users', data.response);

        this.$bvModal.hide('bv-modal-search-form');
        this.clearSearchForm();
      }
  }
}
</script>

<style lang="scss" scoped>
</style>