<script>
import {Line} from 'vue-chartjs/es/BaseCharts.js'
import axios from 'axios'

export default {
  extends: Line,
  name: 'LineChart',
  data() {
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
      },
    }
  },
  mounted() {
    this.apiCalls()
  },
  created() {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('access_token')
  },
  methods: {
    fillWithHot() {
      this.chartData = {
        labels: this.labels, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [{
          label: 'Varmt vand',
          backgroundColor: '#f87979',
          fill: false,
          borderWidth: 1,
          borderColor: '#f87979',
          data: this.data
        }]
      }
      this.renderChart(this.chartData, this.options)
    },
    fillWithCold() {
      this.chartData = {
        labels: this.labels, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [{
          label: 'Koldt vand',
          backgroundColor: '#0a5493',
          fill: false,
          borderWidth: 1,
          borderColor: '#0a5493',
          data: this.data
        }]
      }
      this.renderChart(this.chartData, this.options)
    },
    getDataFromReader() {
      let keys = Object.keys(this.reader)
      keys.forEach(key => {
        this.data.push(this.reader[key].value)
        // this.timeOfReader.push(this.reader[key].date.date)
        let dateString = this.reader[key].date.date
        let dateTime = new Date(dateString)
        let year = dateTime.getFullYear() // "2019"
        year = year.toString().slice(-2) // "19"
        this.labels.push(this.monthNames[dateTime.getMonth()] + ' \'' + year)
      })
      this.labels.shift(); // fjerner første element af array da data altid vil være 0
      this.data.shift(); // fjerner tilsvarende label
    },
    apiCalls() {
      let urlForCold = 'http://backend.test/api/data/consumption/cold/json'
      let urlForHot = 'http://backend.test/api/data/consumption/hot/json'

      if (this.$store.state.someboolean === false) {
        axios
          .get(urlForCold)
          .then(response => (this.reader = response.data[0]))
          .then(response => (this.getDataFromReader()))
          .then(this.fillWithCold)
      } else if (this.$store.state.someboolean === true) {
        axios
          .get(urlForHot)
          .then(response => (this.reader = response.data[0]))
          .then(response => (this.getDataFromReader()))
          .then(this.fillWithHot)
      }

    }
  }
}
</script>

<style scoped>

</style>
