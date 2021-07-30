<template>
<div class="administration">
    <button class="show-instructions" @click.prevent="$store.commit('setInstructions', !$store.getters.instructions.show)">{{$store.getters.instructions.show ? $store.getters.instructions.hideShow : $store.getters.instructions.textShow}}</button>
    <div class="instructions" v-show="$store.getters.instructions.show">
      <h1>Instrucciones</h1>
      <p><strong>Agregar/Quitar permisos:</strong> Marque o desmarque el permiso  y luego presione el botón Guardar permisos</p>
      <p><strong>Agregar un nuevo valor:</strong> Ingrese el valor deseado y luego presione el botón Agregar</p>
    </div>
    <div class="administration__box">
        <b-form class="rol-form">
            <b-form-group>
                <h5 class="box__title">Permisos Roles</h5>
            </b-form-group>
            <b-form-group>
                <b-alert v-show="showSuccessMsg" variant="success" show>Permisos guardados!</b-alert>
            </b-form-group>

            <div class="administration__group" v-for="item in $store.getters.staticData.roleList" :key="item.id">
                
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
                        v-model="$store.getters.administrationForm[item.id]"
                    >
                        <b-form-checkbox class="group__role" :data-role="item.id" :data-access="access.id" v-for="access in $store.getters.staticData.accessList" :key="access.id" :value="access.id">{{access.action}}</b-form-checkbox>
                    </b-form-checkbox-group>
                </b-form-group>

            </div>

            <b-button :disabled="$store.getters.showLoader" @click.prevent="saveRoleAccessList" type="submit" variant="primary">
                Guardar permisos
            </b-button>
        </b-form>
    </div>

    <div class="administration__box">
        <h5 class="box__title">Estado Administrativo</h5>
        <ul v-for="item in $store.getters.staticData.administrativeStatusList" :key="item.id">
            <li>{{item.administrativeStatus}}</li>
        </ul>
        <b-form class="form">
            <b-form-group label-for="administrativeStatus" label="">
                <b-form-input  v-model="administration.administrativeStatus" type="text" class="form-control" id="administrativeStatus" placeholder="Ingrese un valor"></b-form-input>
            </b-form-group>
        
            <b-button @click.prevent="addAdministrativeStatus" type="submit" variant="primary">Agregar Estado Administrativo</b-button>
        </b-form>
    </div>

    <div class="administration__box">
        <h5 class="box__title">Tipos de Citas</h5>
        <ul v-for="item in $store.getters.staticData.appointmentTypeList" :key="item.id">
            <li>{{item.type}}</li>
        </ul>
        <b-form class="form">
            <b-form-group label-for="appointmentType" label="">
                <b-form-input  v-model="administration.type" type="text" class="form-control" id="appointmentType" placeholder="Ingrese un valor"></b-form-input>
            </b-form-group>
        
            <b-button @click.prevent="addAppointmentType" type="submit" variant="primary">Agregar Tipo de Cita</b-button>
        </b-form>
    </div>

    <div class="administration__box">
        <h5 class="box__title">Estado Judicial</h5>
        <ul v-for="item in $store.getters.staticData.judicialStatusList" :key="item.id">
            <li>{{item.judicialStatus}}</li>
        </ul>
        <b-form class="form">
            <b-form-group label-for="judicialStatus" label="">
                <b-form-input  v-model="administration.judicialStatus" type="text" class="form-control" id="judicialStatus" placeholder="Ingrese un valor"></b-form-input>
            </b-form-group>
        
            <b-button @click.prevent="addJudicialStatus" type="submit" variant="primary">Agregar Estado Judicial</b-button>
        </b-form>
    </div>

    <div class="administration__box">
        <h5 class="box__title">Naturaleza de expediente</h5>
        <ul v-for="item in $store.getters.staticData.subjectList" :key="item.id">
            <li>{{item.subject}}</li>
        </ul>
        <b-form class="form">
            <b-form-group label-for="subject" label="">
                <b-form-input  v-model="administration.subject" type="text" class="form-control" id="subject" placeholder="Ingrese un valor"></b-form-input>
            </b-form-group>
        
            <b-button @click.prevent="addSubject" type="submit" variant="primary">Agregar Naturaleza de expediente</b-button>
        </b-form>
    </div>
    
  </div>
</template>

<script>

import repositories from '../repositories';

  export default {
    name: 'Administracion',
    components: {},
    data () {
        return{
            roleprivilegeaccess: {
                data : []
            },
            showSuccessMsg: false,
            administration: {
                administrativeStatus: null,
                type: null,
                judicialStatus: null,
                subject: null
            }
        }
    },
    created () {
        this.getStaticDataFromDB();
    },
    methods: {
        saveRoleAccessList: async function(){
            this.$store.commit('setShowLoader', true);
            this.roleprivilegeaccess.data = [];

            for (const roleValue in this.$store.getters.administrationForm) {
                this.$store.getters.administrationForm[roleValue].forEach((accessValue) => {
                    this.roleprivilegeaccess.data.push({
                        'roleID': roleValue,
                        'accessID': accessValue
                    });
                });
            }
            //OK
            await repositories.setRolePrivilegeAccess(this.roleprivilegeaccess);
            this.showSuccessMsg = true;
            this.$store.commit('setShowLoader', false);

        },
        getStaticDataFromDB: async function(){
            this.$store.commit('setShowLoader', true);

            await this.$store.dispatch('getRoleList');
            await this.$store.dispatch('getAccessList');
            await this.$store.dispatch('getRolePrivilegeAccess');
            await this.$store.dispatch('getJudicialStatusList');
            await this.$store.dispatch('getSubjectList');
            await this.$store.dispatch('getAdministrativeStatusList');
            await this.$store.dispatch('getLocationListData');
            await this.$store.dispatch('getAppointmentTypeList');      

            this.$store.commit('setShowLoader', false);

        },
        addAdministrativeStatus: async function(){
            this.$store.commit('setShowLoader', true);

            if(this.administration.administrativeStatus){
                //OK
                await repositories.addAdministrativeStatus({'administrativeStatus': this.administration.administrativeStatus});
                await this.$store.dispatch('getAdministrativeStatusList');
                this.administration.administrativeStatus = null;
            }
            
            this.$store.commit('setShowLoader', false);
        },
        addAppointmentType: async function(){
            this.$store.commit('setShowLoader', true);

            if(this.administration.type){
                //OK
                await repositories.addAppointmentType({'type': this.administration.type});
                await this.$store.dispatch('getAppointmentTypeList');
                this.administration.type = null;
            }

            this.$store.commit('setShowLoader', false);
        },
        addJudicialStatus: async function(){
            this.$store.commit('setShowLoader', true);

            if(this.administration.judicialStatus){
                //OK
                await repositories.addJudicialStatus({'judicialStatus': this.administration.judicialStatus});
                await this.$store.dispatch('getJudicialStatusList');
                this.administration.judicialStatus = null;
            }

            this.$store.commit('setShowLoader', false);
        },
        addSubject: async function(){
            this.$store.commit('setShowLoader', true);

            if(this.administration.subject){
                //OK
                await repositories.addSubject({'subject': this.administration.subject});
                await this.$store.dispatch('getSubjectList');
                this.administration.subject = null;
            }
            
            this.$store.commit('setShowLoader', false);
        }
    }
  }
</script>

<style lang="scss">
.v-application{
    .administration{
        .group{
            &__role{
                margin-bottom: 10px;
            }
        }

        &__box{
            padding-bottom: 50px;
            border-bottom: 1px solid;
            margin-bottom: 50px;

            &:last-child{
                border-bottom: none;
            }

            .form{
                margin-top: 30px;
            }
        }
        .box{
            &__title{
                margin-bottom: 30px;
            }
        }
    }
}
</style>