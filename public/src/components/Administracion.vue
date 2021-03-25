<template>
  <div class="administracion">
      <b-form class="rol-form">
        <div v-for="item in staticData.roleList" :key="item.id">

            <h4 class="role" :id="item.privilege">{{item.role}}</h4>

            <b-form-group label="Accesos">
                <b-form-checkbox-group
                    id="accessList"
                    name="accessList"
                    label="Accesos del Rol"
                    value-field="id"
                    text-field="action"
                    v-model="administracionForm[item.id]"
                    :data-index="item.id"
                >
                    <b-form-checkbox v-for="access in staticData.accessList" :key="access.id" :value="access.id">{{access.action}}</b-form-checkbox>
                </b-form-checkbox-group>
            </b-form-group>

        </div>

        <b-button @click.prevent="saveRoleAccessList" type="submit" variant="primary">Guardar</b-button>
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
            administracionForm: [],
            roleprivilegeaccess: {
                data : []
            },
            errors: []
        }
    },
    created () {
        this.getStaticDataFromDB();
    },
    methods: {
        saveRoleAccessList: async function(){
            this.administracionForm.forEach((roleValue, i) =>  {
                roleValue.forEach((accessValue, j) => {
                    this.roleprivilegeaccess.data.push({
                        'roleID': i,
                        'accessID': accessValue
                    });
                });
            });
            await repositories.setRolePrivilegeAccess(this.roleprivilegeaccess);
            console.log(this.roleprivilegeaccess.data);
        },
        getStaticDataFromDB: async function(){

            const roleListData = await repositories.getRoleList();
            this.staticData.roleList = roleListData.response;

            const accessListData = await repositories.getAccessList();
            this.staticData.accessList = accessListData.response;
        },
        clearRolForm: function(){
            for(const item in this.administracionForm){
                this.administracionForm[item] = null;
            }
            this.errors = [];
        }
    }
  }
</script>

<style lang="scss">

</style>