<template>
  <div class="inicio">
    <v-row>
      <v-col>
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
                <v-list-item @click="type = 'day'">
                  <v-list-item-title>Día</v-list-item-title>
                </v-list-item>
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
        <v-sheet height="60%">
          <v-calendar
            ref="calendar"
            :events="events"
            color="primary"
            :type="type"
            v-model="value"
            @change="fetchEvents"
            @click:event="showEvent"
            @click:more="viewDay"
            @click:date="viewDay"
            @click:time="showAppointmentModal"
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
                >
                  Ir al caso
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-menu>

        </v-sheet>
      </v-col>
    </v-row>

    <modal-appointment-form :editing-appointment="editingAppointment"  :appointment-form="appointmentForm"></modal-appointment-form>
  </div>
</template>

<script>
  import ModalAppointmentForm from './ModalAppointmentForm.vue';
  import repositories from '../repositories';

  export default {
    name: 'Inicio',
    components: {ModalAppointmentForm},
    data: () => ({
      value: '',
      today: '',
      events: [],
      selectedOpen: false,
      selectedElement: null,
      selectedEvent: {},
      type: 'week',
      typeToLabel: {
        month: 'Mes',
        week: 'Semana',
        day: 'Día',
      },
      ready: false,
      appointmentForm: {
        data: null,
        userID: null
      },
      editingAppointment: false
    }),
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
      viewDay: function ({ date }) {
      this.value = date
      this.type = 'day'
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
        const startDate = start.date;
        const endDate = end.date;

        const data = await repositories.getLegalCasesByDateRange('nextNotification', startDate, endDate);
        const response = data.response;
        if( response.length ){
          response.forEach(item => {
            item['name'] = 'Cobro -> N.Exp: ' + item.internalCode + ' -> ' + item.userName + ' ' + item.lastName1;
            item['details'] = 'Siguiente pago: '+ item.start +'<br/>Número de expediente: ' + item.internalCode + '<br/>Cliente: ' + item.userName + ' ' + item.lastName1 + ' ' + item.lastName2;
            item['href'] = base_url + 'clientes?userID=' + item.userID + '&legalCaseID=' + item.legalCaseID;
            item['color'] = 'orange';
          });
        }

        this.events = response;
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
        this.$set(this.appointmentForm, 'date', date + ' ' + hour +':00:00');
        this.$bvModal.show('bv-modal-appointment-form');
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