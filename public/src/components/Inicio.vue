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
          </v-toolbar>
        </v-sheet>
        <v-sheet height="50%">
          <v-calendar
            ref="calendar"
            :events="events"
            color="primary"
            type="week"
            v-model="value"
            @change="fetchEvents"
          ></v-calendar>
        </v-sheet>
      </v-col>
    </v-row>
  </div>
</template>

<script>
  export default {
    data: () => ({
      value: '',
      today: '',
      events: [
        /*{
          name: 'Weekly Meeting',
          start: '2019-01-07 09:00',
          end: '2019-01-07 10:00',
        },
        {
          name: `Thomas' Birthday`,
          start: '2019-01-10',
        },
        {
          name: 'Mash Potatoes',
          start: '2019-01-09 12:30',
          end: '2019-01-09 15:30',
        },*/
      ],
    }),
    mounted () {
      this.$refs.calendar.scrollToTime('07:00');
      this.$refs.calendar.checkChange();
    },
    methods: {
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

        //console.log(startDate);
        //console.log(endDate);

        const data = await this.getLegalCasesByDateRange('nextNotification', startDate, endDate);
        const response = data.response;
        if( response.length ){
          response.forEach(item => {
            item['name'] = 'S.Pago -> N.Exp: ' + item.internalCode + ' -> ' + item.userName + ' ' + item.lastName1 + ' ' + item.lastName2;
            item['timed'] = true;
            item['color'] = 'orange';
          });
        }
        console.log(response);
        this.events = response;
      },
      getLegalCasesByDateRange: async function(searchBy, start, end){
        const url = 'inicio/getLegalCasesByDateRange';

        const params = {
          'searchBy':searchBy,
          'start': start,
          'end': end
        };
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(params),
          headers:{
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-Requested-With": "XMLHttpRequest"
          }
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
      }
    }
  }
</script>

<style lang="scss">
.inicio{
  .v-event {
    background-color: #1867c0;
    color: #ffffff;
    border: 1px solid #1867c0;
  }

  
}
</style>