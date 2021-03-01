<template>
    <div>
        <b-modal id="bv-modal-search-form" hide-footer>
            <template #modal-title>
            Buscar
            </template>
            <div class="d-block">

            <b-form class="client__search-form">
                <b-form-group label-for="personalID2" label="Cédula">
                <b-form-input v-model="searchClientForm.personalID" type="text" class="form-control" id="personalID2" placeholder="Cédula"></b-form-input>
                </b-form-group>
                <b-button @click.prevent="showSearchResults(searchClientForm.personalID)" type="submit" variant="primary">Buscar</b-button>
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
    }
  },
  methods: {
    clearSearchForm: function(){
        for(const item in this.searchClientForm){
            this.searchClientForm[item] = '';
        }
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