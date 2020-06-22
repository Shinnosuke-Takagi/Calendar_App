<template>
  <v-row class="fill-height">
    <v-col>
      <v-sheet height="64">
        <v-toolbar flat color="white">
          <v-dialog v-model="dialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn color="amber" dark v-on="on">Add Event</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">イベントの追加</span>
              </v-card-title>
              <v-card-text  pb="1">
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="newEvent.name" label="ラベル名" required clearable></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field v-model="newEvent.details" label="内容" required clearable></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                      <v-select
                        v-model="newEvent.color"
                        :items="['pink', 'purple', 'teal', 'lime']"
                        label="カラーラベル"
                        prepend-icon="mdi-palette"
                        required
                      ></v-select>
                    </v-col>
                    <v-col cols="12" lg="6">
                      <v-menu
                        v-model="menu1"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                      >
                        <template v-slot:activator="{ on }">
                          <v-datetime-picker
                          label="開始時間"
                          readonly
                          v-model="AddNewEventStart"
                          @input="menu1 = false"
                          v-on="on"
                          ></v-datetime-picker>
                        </template>
                      </v-menu>
                    </v-col>

                    <v-col cols="12" lg="6">
                      <v-menu
                        v-model="menu2"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                      >
                        <template v-slot:activator="{ on }">
                          <v-datetime-picker
                          v-model="AddNewEventEnd"
                          label="終了時間"
                          readonly
                          @input="menu2 = false"
                          v-on="on"
                          ></v-datetime-picker>
                        </template>
                      </v-menu>
                    </v-col>
                  </v-row>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click.prevent="dialog = false">Close</v-btn>
                <v-btn color="blue darken-1" text @click.prevent="clickAdd">Save</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
            Today
          </v-btn>
          <v-btn fab text small color="grey darken-2" @click="prev">
            <v-icon small>mdi-chevron-left</v-icon>
          </v-btn>
          <v-btn fab text small color="grey darken-2" @click="next">
            <v-icon small>mdi-chevron-right</v-icon>
          </v-btn>
          <v-toolbar-title class="font-weight-regular">{{ title }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu bottom right>
            <template v-slot:activator="{ on }">
              <v-btn
                outlined
                color="grey darken-2"
                v-on="on"
              >
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>mdi-menu-down</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>Day</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Week</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Month</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 days</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="600">
        <v-calendar
          ref="calendar"
          v-model="focus"
          class="font-weight-medium"
          color="primary"
          locale="ja-jp"
          :events="events"
          :event-color="getEventColor"
          :now="today"
          :type="type"
          @click:event="showEvent"
          @click:more="viewDay"
          @click:date="viewDay"
          @change="updateRange"
        ></v-calendar>
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
              <v-toolbar-title
               v-html="selectedEvent.name"
               class="font-weight-medium"
              ></v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon>
                <v-icon @click="clickDelete">mdi-trash-can-outline</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text>
              <span
                v-html="selectedEvent.details"
                v-show="editEvent !== selectedEvent.id"
              ></span>
              <v-textarea
                v-model="selectedEvent.details"
                v-show="editEvent === selectedEvent.id"
                outlined
                placeholder="selectedEvent.details"
                name="newSelectedDetails"
              ></v-textarea>
            </v-card-text>
            <v-card-actions>
              <v-btn
                text
                color="secondary"
                @click.prevent="selectedEventClose"
              >
                Close
              </v-btn>
              <v-btn
                v-show="editEvent !== selectedEvent.id"
                text
                color="accent"
                @click.prevent="editEvent = selectedEvent.id"
              >
                Edit
              </v-btn>
              <v-btn
                v-show="editEvent === selectedEvent.id"
                text
                color="accent"
                @click.prevent="updateEvent"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
      </v-sheet>
    </v-col>
  </v-row>
</template>

<script>
  import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
  import Vue from 'vue'
  import DatetimePicker from 'vuetify-datetime-picker'

  Vue.use(DatetimePicker)

  export default {
    data: () => ({
      focus: '',
      type: 'month',
      typeToLabel: {
        month: 'Month',
        week: 'Week',
        day: 'Day',
        '4day': '4 Days',
      },
      dialog: false,
      menu1: false,
      menu2: false,
      start: null,
      end: null,
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      editEvent: null,
      newEvent: {
        id: '',
        name: '',
        color: '',
        details: '',
        start: '',
        end: '',
      },
      events: [],
      colors: [],
      names: [],
    }),
    computed: {
      isLogin () {
        return this.$store.getters['auth/check']
      },
      today () {
        return this.formatDate(new Date())
      },
      AddNewEventStart: {
        get () {
          return this.newEvent.start
        },
        set (value) {
          this.newEvent.start = `${value.getFullYear()}-${value.getMonth() + 1}-${value.getDate()} ${value.getHours()}:${value.getMinutes()}`
        }
      },
      AddNewEventEnd: {
        get () {
          return this.newEvent.end
        },
        set (value) {
          this.newEvent.end = `${value.getFullYear()}-${value.getMonth() + 1}-${value.getDate()} ${value.getHours()}:${value.getMinutes()}`
        }
      },
      title () {
        const { start, end } = this
        if (!start || !end) {
          return ''
        }

        const startMonth = this.monthFormatter(start)
        const endMonth = this.monthFormatter(end)
        const suffixMonth = startMonth === endMonth ? '' : endMonth

        const startYear = start.year + '年'
        const endYear = end.year + '年'
        const suffixYear = startYear === endYear ? '' : endYear

        const startDay = start.day + '日'
        const endDay = end.day + '日'

        switch (this.type) {
          case 'month':
            return `${startYear} ${startMonth}`
          case 'week':
          case '4day':
            return `${startYear} ${startMonth} ${startDay} - ${suffixMonth} ${endDay} ${suffixYear}`
          case 'day':
            return `${startYear} ${startMonth} ${startDay}`
        }
        return ''
      },
      monthFormatter () {
        return this.$refs.calendar.getFormatter({
          timeZone: 'UTC', month: 'long',
        })
      },
    },
    mounted () {
      this.$refs.calendar.checkChange()
    },
    methods: {
      viewDay ({ date }) {
        this.focus = date
        this.type = 'day'
      },
      getEventColor (event) {
        return event.color
      },
      setToday () {
        this.focus = this.today
      },
      prev () {
        this.$refs.calendar.prev()
      },
      next () {
        this.$refs.calendar.next()
      },
      showEvent ({ nativeEvent, event }) {
        const open = () => {
          this.selectedEvent = event
          this.selectedElement = nativeEvent.target
          setTimeout(() => this.selectedOpen = true, 10)
        }

        if (this.selectedOpen) {
          this.selectedOpen = false
          setTimeout(open, 10)
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
      selectedEventClose () {
        this.editEvent = null
        this.selectedOpen = false
        const {start, end} = this
        this.fetchEvents()
        this.updateRange({ start, end })
      },
      async updateDetails () {
        if(! this.isLogin) {
          alert('ログインしてください')
          this.$router.push('/login')
          return false
        }

        const response = await axios.patch(`/api/events/${this.selectedEvent.id}`, this.selectedEvent)

      },
      updateEvent () {
        const {start, end} = this
        this.updateDetails ()
        this.editEvent = null
        this.updateRange ({start, end})
      },
      async addEvent () {
        if(! this.isLogin) {
          this.$router.push('/login')
          alert('ログインしてください')
          return false
        }

        const response = await axios.post('/api/events', this.newEvent)

        if(response.status === CREATED) {
            this.dialog = false
            return false
        }

        this.dialog = true
      },
      clickAdd () {
        const {start, end} = this
        this.addEvent ()
        this.updateRange ({start, end})
      },
      async fetchEvents () {
        const response = await axios.get('/api/events')

        if(response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }

        const items = response.data

        const events = []

        items.forEach( function(item) {
          events.push({
            id: item.id,
            name: item.name,
            start: item.start,
            end: item.end,
            color: item.color,
            details: item.details
          })
        })

        this.events = events
      },
      async deleteEvent () {
        if(! this.isLogin) {
          this.$router.push('/login')
          alert('ログインしてください')
          return false
        }

        const response = await axios.delete(`/api/events/${this.selectedEvent.id}`)

        if(response.status !== OK) {
          this.selectedOpen = true
          return false
        }

        this.selectedOpen = false
      },
      clickDelete () {
        const {start, end} = this
        this.deleteEvent()
        this.updateRange({ start, end })
      },
      updateRange ({ start, end }) {
          this.fetchEvents()
          this.start = start
          this.end = end
      },
      formatDate (a, withTime) {
        return withTime
          ? `${a.getFullYear()}-${a.getMonth() + 1}-${a.getDate()} ${a.getHours()}:${a.getMinutes()}`
          : `${a.getFullYear()}-${a.getMonth() + 1}-${a.getDate()}`
      },
    },
  }
</script>
