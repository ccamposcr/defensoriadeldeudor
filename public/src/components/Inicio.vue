<template>
  <div class="inicio">
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
        <v-sheet height="800">
          <v-calendar
            ref="calendar"
            :events="$store.getters.events"
            color="primary"
            :type="type"
            v-model="value"
            @change="fetchEvents"
            @click:event="showEvent"
            @click:time="showAppointmentModal"
            interval-count="14"
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
                  Ir al caso
                </v-btn>
                <v-btn
                  depressed
                  color="error"
                  @click="cancelAppointment(selectedEvent.appointmentID)"
                  v-if="checkAccessList('eliminar cita') && selectedEvent.type=='appointment'"
                >
                  Eliminar Cita
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-menu>

        </v-sheet>
      </v-col>
    </v-row>

    <modal-appointment-form @fetchEvents="fetchEvents" :editing-appointment="editingAppointment" :date="date"></modal-appointment-form>

  </div>
</template>

<script>
  import ModalAppointmentForm from './ModalAppointmentForm.vue';
  import repositories from '../repositories';
  import global from '../global';

  export default {
    name: 'Inicio',
    components: {ModalAppointmentForm},
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
        editingAppointment: false,
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
      const params = this.$route.query;
      this.loadDataFromURLParams(params);
    },
    methods: {
      checkAccessList: function(action){
        return global.checkAccessList(action);
      },
      getStaticDataFromDB: async function(){
        this.$store.commit('setShowLoader', true);

        await this.$store.dispatch('getAppointmentTypeList');

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

        if( this.$store.getters.legalCasePaymentDates.length ){
          this.$store.getters.legalCasePaymentDates.forEach(item => {
            item.name = 'Cobro -> N.: ' + item.internalCode + ' -> ' + item.userName + ' ' + item.lastName1;
            item.details = (item.start ? '<strong>Cobrar el:</strong> '+ item.start : '') +'<br/><strong>Número de expediente:</strong> ' + item.internalCode + '<br/><strong>Cliente:</strong> ' + item.userName + ' ' + item.lastName1 + ' ' + item.lastName2;
            item.href = base_url + 'clientes?userID=' + item.userID + '&legalCaseID=' + item.legalCaseID;
            item.color = 'orange';
            item.type = 'notification';
          });
        }

        await this.$store.dispatch('getAppointmentsByDateRange', {searchBy: 'date', startDate, endDate});

        if( this.$store.getters.appointmentsDates.length ){
          this.$store.getters.appointmentsDates.forEach(item => {
            item.name = 'Cita -> ' + (item.type ? item.type + ' -> ' : '') + item.clientUserName + ' ' + item.clientLastName1;
            item.details = '<strong>Cita:</strong> '+ item.date + '<br/><strong>Cliente:</strong> ' + item.clientUserName + ' ' + item.clientLastName1 + ' ' + item.clientLastName2
            + (item.internalUserUserName ? '<br/><strong>Funcionario asignado:</strong> ' + item.internalUserUserName + ' ' + item.internalUserLastName1 + ' ' + item.internalUserLastName2 : '')
            + '<br/><strong>Hecha por:</strong> ' + item.madeByUserUserName + ' ' + item.madeByUserLastName1 + ' ' + item.madeByUserLastName2
            + (item.type ? '<br/><strong>Tipo de Cita:</strong> ' + item.type : '');
            item.start = item.date;
            item.color = item.alertColor;
            item.type = 'appointment';
          });
        }

        const paymentsArray = this.$store.getters.legalCasePaymentDates;
        const appointmentsArray = this.$store.getters.appointmentsDates;

        const events = paymentsArray.concat(appointmentsArray);
        this.$store.commit('setEvents', events);

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
      showAppointmentModal: function({ date, hour }){
        if( this.checkAccessList('agendar cita') ){
          const data = date + ' ' + hour +':00';
          this.$store.commit('setAppointmentFormBy', {data:data, by:'date'});
          this.$bvModal.show('bv-modal-appointment-form');
        }
      },
      cancelAppointment: async function(appointmentID){
        this.$store.commit('setShowLoader', true);
        //OK
        await repositories.cancelAppointment({id:appointmentID});
        const start = this.date.start;
        const end = this.date.end;
        this.fetchEvents({start, end});
        this.selectedOpen = false;
        this.$store.commit('setShowLoader', false);
      },
      loadDataFromURLParams: async function(params){
        if(this.checkAccessList('agendar cita') && params.appointmentDate){
          this.$store.commit('setAppointmentFormBy', {data:params.appointmentDate, by:'date'});
          this.$bvModal.show('bv-modal-appointment-form');
        }
        if(this.checkAccessList('agendar cita') && params.clientID){
          this.$store.commit('setAppointmentFormBy', {data:params.clientID, by:'userID'});
        }
      },
      sync: function(){
        const start = this.date.start;
        const end = this.date.end;
        this.fetchEvents({start, end});
      }
    }
  }
</script>

<style lang="scss">
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
</style>