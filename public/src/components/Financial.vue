<template>
  <div class="financiero">
    <div class="financiero__wrapper">
      <div class="financiero__calendar">
        <v-row>
          <v-col>
            <v-btn
              outlined
              class=""
              color="green darken-2"
              @click="sync"
            >
              Sincronizar
            </v-btn>
            <v-sheet height="64">
              <v-toolbar
                flat
              >
                <v-btn
                  outlined
                  class="mr-4"
                  color="grey darken-2"
                  @click="setToday"
                >
                  Hoy
                </v-btn>
                <v-btn
                  fab
                  text
                  small
                  color="grey darken-2"
                  @click="prev"
                >
                  <v-icon small>
                    mdi-chevron-left
                  </v-icon>
                </v-btn>
                <v-btn
                  fab
                  text
                  small
                  color="grey darken-2"
                  @click="next"
                >
                  <v-icon small>
                    mdi-chevron-right
                  </v-icon>
                </v-btn>
                <v-toolbar-title v-if="$refs.calendar">
                  {{ $refs.calendar.title }}
                </v-toolbar-title>
                <v-spacer></v-spacer>

                <v-menu
                  bottom
                  right
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      outlined
                      color="grey darken-2"
                      v-bind="attrs"
                      v-on="on"
                    >
                      <span>{{ typeToLabel[type] }}</span>
                      <v-icon right>
                        mdi-menu-down
                      </v-icon>
                    </v-btn>
                  </template>
                  <v-list>
                    <v-list-item @click="type = 'week'">
                      <v-list-item-title>Semana</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="type = 'month'">
                      <v-list-item-title>Mes</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>

              </v-toolbar>
            </v-sheet>
            <v-sheet>
              <v-calendar
                ref="calendar"
                :events="$store.getters.events"
                color="primary"
                :type="type"
                v-model="value"
                @change="fetchEvents"
                @click:event="showEvent"
                interval-count="0"
                first-time="06:00"
              >
                <template v-slot:day-body="{ date, week }">
                  <div
                    class="v-current-time"
                    :class="{ first: date === week[0].date }"
                    :style="{ top: nowY }"
                  ></div>
                </template>
              </v-calendar>

              <v-menu
                v-model="selectedOpen"
                :close-on-content-click="false"
                :activator="selectedElement"
                offset-x
              >
                <v-card
                  color="grey lighten-4"
                  min-width="350px"
                  flat
                >
                  <v-toolbar
                    :color="selectedEvent.color"
                    dark
                  >
                    <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                    <v-spacer></v-spacer>
                  </v-toolbar>
                  <v-card-text>
                    <span v-html="selectedEvent.details"></span>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      text
                      color="secondary"
                      @click="selectedOpen = false"
                    >
                      Cerrar
                    </v-btn>
                    <v-btn
                      depressed
                      color="primary"
                      @click="showPaymentDateDetail(selectedEvent.href)"
                      v-if="selectedEvent.type=='notification'"
                    >
                      Ver el detalle
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-menu>

            </v-sheet>
          </v-col>
        </v-row>
      </div>
      <div class="financiero__aside">
        <h6>Cobros en mora</h6>
        <ul class="detail__list" v-if="$store.getters.overDuePaymentDates.length">
          <li class="list__option" v-bind:key="overDuePayment.id" v-for="overDuePayment in $store.getters.overDuePaymentDates">
            <p><strong>F.Pago: </strong>{{overDuePayment.start}}</p>
            <p><strong>Nombre: </strong>{{overDuePayment.userName}} {{overDuePayment.lastName1}} {{overDuePayment.lastName2}}</p>
            <button class="btn btn-danger" @click="showPaymentDateDetail({userID: overDuePayment.userID, financialContractID: overDuePayment.financialContractID, paymentDateID: overDuePayment.id})">Ver el detalle</button>
          </li>
        </ul>
        <p v-show="!$store.getters.overDuePaymentDates.length">No hay pagos en mora</p>
      </div>
    </div>

    <div class="financiero__content">
      <b-button variant="info" @click="showSearchClientModal">Buscar Cliente</b-button>
      <b-button variant="primary" :disabled="$store.getters.showLoader" @click="renderAllClients">
        Ver todos los Clientes
      </b-button>

      <div class="client" v-show="$store.getters.users">
        <ul class="client__list">
          <li class="list__user" v-bind:key="user.id" v-for="user in $store.getters.users">
            
            <client-detail-min :user="user"></client-detail-min>

            <div class="user__options">
              <b-button :disabled="$store.getters.showLoader" @click="renderFinancialInfo({searchBy:'userID', value:user.id, userID:user.id})" variant="primary">Ver Información Financiera</b-button>
              <b-button v-if="checkAccessList('agregar info financiera')" @click="showFinancialInfoForm(user.id)" variant="success">Agregar Información Financiera</b-button>
            </div>
            
            <div v-if="$store.getters.financialInfo(user.id)">
              <ul class="box">
                  <li class="box__detail" v-bind:key="financial.id" v-for="financial in $store.getters.financialInfo(user.id)">
                      <financial-detail :financial="financial" @checkAccessList="checkAccessList" @fillEditFinancialForm="fillEditFinancialForm" :user="user" @unblockFinancialInfo="unblockFinancialInfo" @renderPaymentDates="renderPaymentDates"></financial-detail>
                      <payment-dates :financial="financial" @checkAccessList="checkAccessList" @removePaymentDate="removePaymentDate" @showAddInvoiceForm="showAddInvoiceForm"  @renderPaymentDates="renderPaymentDates"></payment-dates>
                  </li>
              </ul>
            </div>
            <span class="label-danger" v-if="$store.getters.financialInfo(user.id) && !$store.getters.financialInfo(user.id).length">No hay Información financiera</span>

          </li>
        </ul>
      </div>
    </div>

    <modal-search-form @renderClientBy="renderClientBy"></modal-search-form>
    <modal-financial-info-form @renderFinancialInfo="renderFinancialInfo" @renderPaymentDates="renderPaymentDates" @sync="sync"></modal-financial-info-form>
    <modal-invoice-form @renderPaymentDates="renderPaymentDates"></modal-invoice-form>

  </div>
</template>

<script>
import ClientDetailMin from './ClientDetailMin.vue';
import FinancialDetail from './FinancialDetail.vue';
import PaymentDates from './PaymentDates.vue';
import repositories from '../repositories';
import global from '../global';
import ModalSearchForm from './Modals/ModalSearchForm.vue';
import ModalFinancialInfoForm from './Modals/ModalFinancialInfoForm.vue';
import ModalInvoiceForm from './Modals/ModalInvoiceForm.vue';

export default {
    name: 'Financial',
    components: {ModalSearchForm, ClientDetailMin, ModalFinancialInfoForm, FinancialDetail, PaymentDates, ModalInvoiceForm},
    data () {
      return{
        value: '',
        today: '',
        selectedOpen: false,
        selectedElement: null,
        selectedEvent: {},
        type: 'month',
        typeToLabel: {
          month: 'Mes',
          week: 'Semana'
        },
        ready: false,
        date:{
          start: null,
          end: null
        }
      }
    },
    created(){
      this.getStaticDataFromDB();
    },
    computed: {
      cal () {
        return this.ready ? this.$refs.calendar : null
      },
      nowY () {
        return this.cal ? this.cal.timeToY(this.cal.times.now) + 'px' : '-10px'
      },
    },
    mounted () {
      this.$refs.calendar.checkChange();
      this.ready = true;
      this.scrollToTime();
      this.updateTime();
      this.$refs.calendar.scrollToTime('08:00');
    },
    methods: {
      checkAccessList: function(action){
        return global.checkAccessList(action);
      },
      getStaticDataFromDB: async function(){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('getAdministrativeStatusList');
        
        this.$store.commit('setShowLoader', false);
      },
      setToday: function() {
        this.value = '';
      },
      prev: function() {
        this.$refs.calendar.prev();
      },
      next: function() {
        this.$refs.calendar.next();
      },
      fetchEvents: async function({ start, end }) {
        this.$store.commit('setShowLoader', true);
        this.date.start = start;
        this.date.end = end;

        const startDate = this.date.start.date;
        const endDate = this.date.end.date;

        await this.$store.dispatch('getPaymentDatesByDateRange', {startDate, endDate});

        const events = this.$store.getters.paymentDatesEvents;
        if( events.length ){
          events.forEach(item => {
            item.name = 'Cobro -> Cliente: ' + item.userName + ' ' + item.lastName1;
            item.details = (item.start ? '<strong>Cobrar el:</strong> '+ item.start : '') + '<br/><strong>Cliente:</strong> ' + item.userName + ' ' + item.lastName1 + ' ' + item.lastName2;
            item.href = {userID: item.userID, financialContractID: item.financialContractID, paymentDateID: item.id};
            item.color = 'red';
            item.type = 'notification';
          });
        }

        this.$store.commit('setEvents', events);

        await this.$store.dispatch('getOverDuePaymentDatesByDateRange', {endDate});

        this.$store.commit('setShowLoader', false);
        
      },
      showEvent: function ({ nativeEvent, event }) {
        const open = () => {
          this.selectedEvent = event
          this.selectedElement = nativeEvent.target
          setTimeout(() => {
            this.selectedOpen = true
          }, 10)
        }

        if (this.selectedOpen) {
          this.selectedOpen = false
          setTimeout(open, 10)
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
      getCurrentTime: function() {
        return this.cal ? this.cal.times.now.hour * 60 + this.cal.times.now.minute : 0
      },
      scrollToTime: function() {
        const time = this.getCurrentTime()
        const first = Math.max(0, time - (time % 30) - 30)
        this.cal.scrollToTime(first)
      },
      updateTime: function () {
        setInterval(() => this.cal.updateTimes(), 60 * 1000)
      },
      sync: function(){
        const start = this.date.start;
        const end = this.date.end;
        this.fetchEvents({start, end});
      },
      renderClientBy: async function({service, searchBy, value, callback}){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('getClientBy', {service, searchBy, value, callback});

        this.$store.commit('setShowLoader', false);
      },
      showSearchClientModal: function(){
        this.$bvModal.show('bv-modal-search-form');
      },
      renderAllClients: async function(){
        this.$store.commit('setShowLoader', true);

        this.resetFinancialVars();

        await this.$store.dispatch('getAllClients');

        this.$store.commit('setShowLoader', false);
      },
      showFinancialInfoForm: function(userID){
        if( this.checkAccessList('agregar info financiera') ){
          this.$store.commit('setEditingFinancialInfo', false);
          this.$store.commit('setCurrentFinancialInfoUserId', userID);
          this.$bvModal.show('bv-modal-financial-info-form');
        }
      },
      renderFinancialInfo: async function({searchBy, value, userID, callback}){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('getFinancialInfoBy', {searchBy, value, userID, callback});
        //const financialInfo = this.$store.getters.financialInfo(userID);
        //console.log(financialInfo);
        //await this.$store.dispatch('getSUMPaymentDatesBy', {searchBy: 'financialContractID', financialContractID: financialContractID});

        this.$store.commit('setShowLoader', false);
      },
      renderPaymentDates: async function(financialContractID){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('getPaymentDatesBy', {searchBy: 'financialContractID', financialContractID: financialContractID});
        await this.$store.dispatch('getCOUNTPaymentDatesBy', {searchBy: 'financialContractID', financialContractID: financialContractID});

        //console.log(this.$store.getters.COUNTPaymentDates);
        //console.log(this.$store.getters.SUMPaymentDates);

        this.$store.commit('setShowLoader', false);
      },
      fillPaymentDatesOnForm: async function(id){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('fillPaymentDatesOnForm', {id});

        this.$store.commit('setShowLoader', false);
      },
      fillEditFinancialForm: async function(financialContractID, userID){
        if( this.checkAccessList('editar info financiera') ){
  
          await this.isFinancialInfoInUse(financialContractID);

          if(this.$store.getters.isFinancialInfoInUse === '1'){
            alert('Este registro está siendo editado por otro usuario. Por favor intente más tarde.');
          }else{
            
            await this.$store.dispatch('updateFinancialInfoIsInUse', {id: financialContractID, inUse: 1});

            await this.fillFinancialForm(financialContractID, userID);
            
            await this.fillPaymentDatesOnForm(financialContractID);

            this.$store.commit('setEditingFinancialInfo', true);
            this.$bvModal.show('bv-modal-financial-info-form');
            
          }
        }
      },
      fillFinancialForm: async function(id, userID){
        this.$store.commit('setShowLoader', true);

        this.$store.commit('setCurrentFinancialInfoUserId', userID);

        await this.$store.dispatch('fillFinancialForm', {id});

        this.$store.commit('setShowLoader', false);
      },
      isFinancialInfoInUse: async function(id){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('getIsFinancialInfoInUse', {id});

        this.$store.commit('setShowLoader', false);
      },
      unblockFinancialInfo: async function(financialContractID, userID){

        await this.$store.dispatch('updateFinancialInfoIsInUse', {id: financialContractID, inUse: 0});
        //searchBy, value, userID, callback
        await this.renderFinancialInfo({searchBy:'userID', value:userID, userID:userID});

      },
      removePaymentDate: async function({paymentDateID, financialContractID}){
        this.$store.commit('setShowLoader', true);

        const data = {};
        data.id = paymentDateID;
        //OK
        await repositories.deletePaymentDate(data);
        await this.renderPaymentDates(financialContractID);

        this.sync();

        this.$store.commit('setShowLoader', false);
      },
      showPaymentDateDetail: async function(data){
        this.resetFinancialVars();
        //{userID: "", financialContractID: "", paymentDateID: ""}
        await this.renderClientBy({service: 'getClientBy', searchBy: 'id', value: data.userID});
        await this.renderFinancialInfo({searchBy: 'userID', value: data.userID, userID: data.userID});
        await this.renderPaymentDates(data.financialContractID);
      },
      resetFinancialVars: function(){
        this.$store.commit('setFinancialInfo', []);
        this.$store.commit('setPaymentDates', []);
      },
      showAddInvoiceForm: function({paymentDateID, financialContractID, amountToPay}){
        if( this.checkAccessList('agregar info financiera') ){
          this.$store.commit('setCurrentPaymentId', paymentDateID);
          this.$store.commit('setInvoiceForm', {paymentDateMade: '', referenceNumber: '', amountPaid: amountToPay});
          this.$store.commit('setCurrentFinancialInfoUserId', financialContractID);
          this.$bvModal.show('bv-modal-invoice-form');
        }
        
      }
      
    }
  }
</script>

<style lang="scss">
.v-application{
  .financiero{
    &__content{
      margin-top: 50px;
    }
    &__wrapper{
      display: flex;
      flex-direction: row;
    }
    &__calendar{
      width: 80%;
    }
    &__aside{
      width: 18%;
      margin-left: 2%;
      border: #e0e0e0 1px solid;
      padding: 10px;

      .detail__list{
        overflow: auto;
        max-height: 300px;
      }
    }
  }
}
</style>