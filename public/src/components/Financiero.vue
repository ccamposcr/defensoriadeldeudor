<template>
  <div class="financiero">
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
                  :href="selectedEvent.href"
                  v-if="selectedEvent.type=='notification'"
                >
                  Ir al cobro
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-menu>

        </v-sheet>
      </v-col>
    </v-row>

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
              <b-button v-if="checkAccessList('agregar info financiera')" @click="showFinancialInfoForm(user.id)" variant="success">Agregar Información Financiera</b-button>
              <b-button :disabled="$store.getters.showLoader" @click="renderFinancialInfo({searchBy:'userID', value:user.id, userID:user.id})" variant="primary">Ver Información Financiera</b-button>
            </div>

            <!-- FINANCIAL INFO -->
            <div v-if="$store.getters.financialInfo(user.id)">
            <ul class="user__financial">
                <li class="financial__detail" v-bind:key="financial.id" v-for="financial in $store.getters.financialInfo(user.id)">

                    <p v-if="financial.totalAmount"><strong>Monto total:</strong> {{ financial.totalAmount }}</p>
                    <p v-if="financial.administrativeStatus"><strong>Estado:</strong> {{ financial.administrativeStatus }}</p>
                
                </li>
            </ul>
            </div>
            <span class="label-danger" v-if="$store.getters.financialInfo(user.id) && !$store.getters.financialInfo(user.id).length">No hay Información financiera</span>
            <!-- FINANCIAL INFO -->

          </li>
        </ul>
      </div>
    </div>

    <modal-search-form @renderClientBy="renderClientBy"></modal-search-form>
    <modal-financial-info-form @renderFinancialInfo="renderFinancialInfo"></modal-financial-info-form>

  </div>
</template>

<script>
import ClientDetailMin from './ClientDetailMin.vue';
import repositories from '../repositories';
import global from '../global';
import ModalSearchForm from './Modals/ModalSearchForm.vue';
import ModalFinancialInfoForm from './Modals/ModalFinancialInfoForm.vue';

export default {
    name: 'Financiero',
    components: {ModalSearchForm, ClientDetailMin, ModalFinancialInfoForm},
    data () {
      return{
        value: '',
        today: '',
        selectedOpen: false,
        selectedElement: null,
        selectedEvent: {},
        type: 'week',
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

        if( this.$store.getters.paymentDates.length ){
          this.$store.getters.paymentDates.forEach(item => {
            item.name = 'Cobro -> Cliente: ' + item.userName + ' ' + item.lastName1;
            item.details = (item.start ? '<strong>Cobrar el:</strong> '+ item.start : '') + '<br/><strong>Cliente:</strong> ' + item.userName + ' ' + item.lastName1 + ' ' + item.lastName2;
            //item.href = base_url + 'financiero?userID=' + item.userID + '&financialContractID=' + item.financialContractID;
            item.color = 'red';
            item.type = 'notification';
          });
        }

        this.$store.commit('setEvents', this.$store.getters.paymentDates);

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

        this.$store.commit('setShowLoader', false);
      },
      renderPaymentDates: async function(financialContractID){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('getPaymentDatesBy', {searchBy: 'financialContractID', financialContractID: financialContractID});

        this.$store.commit('setShowLoader', false);
      },
      fillPaymentDatesOnForm: async function(id){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('fillPaymentDatesOnForm', {id});

        this.$store.commit('setShowLoader', false);
      }
    }
  }
</script>

<style lang="scss" scoped>
  .v-current-time {
    height: 2px;
    background-color: #ea4335;
    position: absolute;
    left: -1px;
    right: 0;
    pointer-events: none;
    &.first::before {
      content: '';
      position: absolute;
      background-color: #ea4335;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin-top: -5px;
      margin-left: -6.5px;
    }
  }
  .v-calendar-daily__day-interval{
    cursor: pointer;
  }

  .financiero{
    &__content{
      margin-top: 50px;
    }
    .btn{
      margin-right: 10px;
      margin-bottom: 10px;
      &:last-child{
        margin-right: 0;
      }
    }
  }

  .client{
    &__list{
      list-style-type: none;
      padding: 0;
      margin-top: 30px;
    }
    .list{
      &__user{
        background-color: #e6e5e5;
        margin-bottom: 15px;
        padding: 15px 15px 5px 15px;
      }
    }
    .user{
      &__options{
        margin-top: 15px;
      }
    }
  }
</style>