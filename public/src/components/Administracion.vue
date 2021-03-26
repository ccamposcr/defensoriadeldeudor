<template>
  <div class="administracion">
        <b-form class="rol-form">
            <b-form-group>
                <h5>Permisos Roles</h5>
            </b-form-group>
            <b-form-group>
                <b-alert v-show="showSuccessMsg" variant="success" show>Permisos guardados!</b-alert>
            </b-form-group>

            <div v-for="item in staticData.roleList" :key="item.id">
                
                <b-form-group>
                    <h6 class="role" :id="item.id">Rol {{item.role}}</h6>
                </b-form-group>

                <b-form-group>
                    <b-form-checkbox-group
                        id="accessList"
                        name="accessList"
                        label="Accesos del Rol"
                        value-field="id"
                        text-field="action"
                        v-model="administracionForm[item.id]"
                    >
                        <b-form-checkbox :data-role="item.id" :data-access="access.id" v-for="access in staticData.accessList" :key="access.id" :value="access.id">{{access.action}}</b-form-checkbox>
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
            showSuccessMsg: false
        }
    },
    created () {
        this.getStaticDataFromDB();
    },
    methods: {
        saveRoleAccessList: async function(){
            this.roleprivilegeaccess.data = [];

            for (const roleValue in this.administracionForm) {
                this.administracionForm[roleValue].forEach((accessValue) => {
                    this.roleprivilegeaccess.data.push({
                        'roleID': roleValue,
                        'accessID': accessValue
                    });
                });
            }
            await repositories.setRolePrivilegeAccess(this.roleprivilegeaccess);
            this.showSuccessMsg = true;

        },
        getStaticDataFromDB: async function(){

            const roleListData = await repositories.getRoleList();
            this.staticData.roleList = roleListData.response;

            const accessListData = await repositories.getAccessList();
            this.staticData.accessList = accessListData.response;

            const roleprivilegeaccessData = await repositories.getRolePrivilegeAccess();
            const response = roleprivilegeaccessData.response;

            this.administracionForm = response;

        }
    }
  }
</script>

<style lang="scss">

</style>