<template>
    <div>
        <b-modal id="bv-modal-update-password-form" hide-footer novalidate="true" @hide="onCloseUpdatePasswordForm">
            <template #modal-title>
            Cambiar Contraseña
            </template>
            <div class="d-block">
                <div v-if="errors.length">
                    <p>Por favor, corrija el(los) siguiente(s) error(es):</p>
                    <ul class="errors-list">
                        <li class="label label-danger" :key="error" v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>
                <b-form class="client__update-password-form">
                    <b-form-group label-for="password" label="Contraseña">
                        <b-form-input v-model="$store.getters.updatePasswordForm.password" type="password" class="form-control" id="password" placeholder="Contraseña"></b-form-input>
                    </b-form-group>
                    <b-form-group label-for="confirmPassword" label="Confirmar contraseña">
                        <b-form-input v-model="$store.getters.updatePasswordForm.confirmPassword" type="password" class="form-control" id="confirmPassword" placeholder="Contraseña"></b-form-input>
                    </b-form-group>
                    <b-button :disabled="$store.getters.showLoader" @click.prevent="checkForm(function(){updatePassword()})" type="submit" variant="primary">
                        Actualizar
                    </b-button>
                    <b-button @click.prevent="closeUpdatePasswordForm" variant="danger">Cancelar</b-button>
                </b-form>

                <div v-if="errors.length">
                    <p class="label label-danger">Por favor, corrija el(los) error(es) del formulario</p>
                </div>

            </div>
        </b-modal>
    </div>
</template>
 

<script>
import repositories from '../../repositories';

export default {
  name: 'ModalUpdatePasswordForm',
  props: [],
  data () {
    return {
        errors:[]
    }
  },
  methods: {
    checkForm: function(callback){
        this.errors = [];
        if(!this.$store.getters.updatePasswordForm.password){
            this.errors.push("Ingrese una contraseña");
        }
        if(this.$store.getters.updatePasswordForm.password != this.$store.getters.updatePasswordForm.confirmPassword){
            this.errors.push("Ambas contraseñas deben ser iguales");
        }
        if(!this.errors.length){
            callback();
        }
    },
    clearUpdatePasswordForm: function(){
        const data = {
            password: '',
            confirmPassword: ''
        }
        this.$store.commit('setUpdatePasswordForm', data);
        this.errors = [];
    },
    closeUpdatePasswordForm: function(){
        this.$bvModal.hide('bv-modal-update-password-form');
    },
    onCloseUpdatePasswordForm: function(){
        this.clearUpdatePasswordForm();
    },
    updatePassword: async function(){
        this.$store.commit('setShowLoader', true);
        //OK
        await repositories.updatePassword(this.$store.getters.currentUserIdUpdatePassword, this.$store.getters.updatePasswordForm);
        this.closeUpdatePasswordForm();
        this.$store.commit('setShowLoader', false);
      }
  }
}
</script>

<style lang="scss">
</style>