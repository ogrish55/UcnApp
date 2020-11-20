<template>
  <div class="chart-container" id="chart" v-if="reader">
    <line-chart v-if="ready" :chart-data="datacollection" :options="chartOptions"></line-chart>
  </div>
</template>

<script>
import LineChart from './LineChart'
import axios from 'axios'

export default {
  name: 'Consumption',
  components: {LineChart},

  data () {
    return {
      ready: false,
      datacollection: null,
      measurements: [],
      timeOfReader: [],
      reader: null,
      chartOptions: null
    }
  },
  mounted () {
    this.apiCalls()
  },

  methods: {
    fillData () {
      this.datacollection = {
        labels: this.timeOfReader, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [
          {
            label: 'Hot water',
            backgroundColor: '#f87979',
            data: this.measurements
          }
        ]
      }
      this.ready = true
    },
    getDataFromReader () {
      let keys = Object.keys(this.reader)
      keys.forEach(key => {
        this.measurements.push(this.reader[key].value)
        this.timeOfReader.push(this.reader[key].date.date)
      })
      this.timeOfReader = this.timeOfReader.map(x => x.substr(0, 10))
    },
    apiCalls () {
      axios
        .get('http://backend.test/api/data/consumption/hot')
        .then(response => (this.reader = response.data[0]))
        .then(response => (this.getDataFromReader()))
        .then(this.fillData)
    }
  }
}

</script>

<style scoped>

</style>
