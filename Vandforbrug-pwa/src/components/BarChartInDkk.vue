<script>
import {Bar} from 'vue-chartjs/es/BaseCharts.js'
import axios from 'axios'
export default {
  extends: Bar,
  name: 'BarChart',
  data () {
    return {
      reader: null,
      labels: [],
      data: [],
      monthNames: [
        'Januar',
        'Februar',
        'Marts',
        'April',
        'Maj',
        'Juni',
        'Juli',
        'August',
        'September',
        'Oktober',
        'November',
        'December'
      ],
      chartData: null,
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: true
            }
          }],
          xAxes: [{
            gridLines: {
              display: false
            }
          }]
        },
        legend: {
          display: true
        },
        responsive: true,
        maintainAspectRatio: false
      }
    }
  },
  mounted () {
    this.apiCalls()
  },
  methods: {
    fillChart () {
      this.chartData = {
        labels: this.labels,
        datasets: [{
          label: 'Forbrug',
          borderWidth: 1,
          backgroundColor: 'rgba(75, 192, 192, 0.2)'
          ,
          borderColor: 'rgba(75, 192, 192, 0.2)',
          pointBorderColor: '#2554FF',
          data: this.data
        }]
      }
      this.renderChart(this.chartData, this.options)
    },
    getDataFromReader () {
      let keys = Object.keys(this.reader)
      keys.forEach(key => {
        let dateString = this.reader[key].date.date
        let dateTime = new Date(dateString) // konverter til date objekt
        let year = dateTime.getFullYear() // "2019"
        year = year.toString().slice(-2) // "19"
        this.labels.push(this.monthNames[dateTime.getMonth()] + ' \'' + year)
        // this.coldTimeOfReader.push(this.monthNames[dateTime.getMonth()])
        this.data.push(parseFloat(this.reader[key].price))
      })
      this.labels.shift()
      this.data.shift()
    },
    apiCalls () {
      axios
        .get('http://backend.test/api/data/monthlyUsageInDkk')
        .then(response => (this.reader = response.data[0]))
        .then(response => (this.getDataFromReader()))
        .then(this.fillChart)
    }
  },
}
</script>

<style scoped>
</style>
