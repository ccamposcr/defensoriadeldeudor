<template>
  <div class="administracion">
        <b-form class="rol-form">
            <b-form-group>
                <h5>Permisos Roles</h5>
            </b-form-group>
            <b-form-group>
                <b-alert v-if="showSuccessMsg" variant="success">Permisos guardados!</b-alert>
            </b-form-group>

            <div v-for="item in staticData.roleList" :key="item.id">
                
                <b-form-group>
                    <h6 class="role" :id="item.privilege">Rol {{item.role}}</h6>
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
            showSuccessMsg : false
        }
    },
    created () {
        this.getStaticDataFromDB();
    },
    methods: {
        saveRoleAccessList: async function(){
            console.log(this.administracionForm);
            this.administracionForm.forEach((roleValue, i) =>  {
                roleValue.forEach((accessValue, j) => {
                    this.roleprivilegeaccess.data.push({
                        'roleID': i,
                        'accessID': accessValue
                    });
                });
            });
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

            const administrationFormArray = [];
          
            response.forEach(obj => {
                const { roleID, accessID } = obj;
                if(administrationFormArray[roleID]){
                    administrationFormArray[roleID].push(accessID);
                }else{
                    administrationFormArray[roleID] = [accessID]
                }
            });

            if( administrationFormArray.length ){
                this.administracionForm = administrationFormArray;
            }
           
        }
    }
  }
</script>

<style lang="scss">

</style>