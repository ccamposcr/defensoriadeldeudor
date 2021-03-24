<template>
  <div class="administracion">
      <b-form class="user__case-form">
       
        <b-form-group label-for="role" label="Rol">
            <b-form-select id="role" v-model="administracionForm.role" :options="staticData.roleList" value-field="privilege" text-field="role"></b-form-select>
        </b-form-group>
        <b-form-group label-for="accessList" label="Privilegio">
            <b-form-select id="accessList" v-model="administracionForm.access" :options="staticData.accessList" value-field="id" text-field="action"></b-form-select>
        </b-form-group>
        <b-button v-if="!editingLegalCase" @click.prevent="checkForm(function(){setNewLegalCase()})" type="submit" variant="primary">Agregar</b-button>
        <b-button v-if="editingLegalCase" @click.prevent="checkForm(function(){setEditedLegalCase()})" type="submit" variant="primary">Guardar</b-button>
        <b-button @click.prevent="cancelLegalForm" variant="danger">Cancelar</b-button>
    </b-form>
  </div>
</template>

<script>

import repositories from '../repositories';

  export default {
    name: 'Administracion',
    components: {},
    data () {
        return{
            staticData:{
                roleList: [],
                accessList: []
            },
            administracionForm: {
                role: null,
                access: null
            }
        }
    },
    created () {
        this.getStaticDataFromDB();
    },
    mounted () {
    },
    methods: {
        getStaticDataFromDB: async function(){

            const roleListData = await repositories.getRoleList();
            this.staticData.roleList = roleListData.response;

            const accessListData = await repositories.getAccessList();
            this.staticData.accessList = accessListData.response;
        }
    }
  }
</script>

<style lang="scss">

</style>