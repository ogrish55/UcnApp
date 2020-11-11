<script>
import {Line} from 'vue-chartjs/es/BaseCharts.js'
import axios from 'axios'

export default {
  extends: Line,
  name: 'LineChart',
  props: {
    someBoolean: Boolean
  },
  data() {
    return {
      readerHot: null,
      readerCold: null,
      labelsHot: [],
      dataHot: [],
      labelsCold: [],
      dataCold: [],
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
    if(this.someBoolean === true)
    {
      this.fillWithHot()
    } else if (this.someBoolean === false){
      this.fillWithCold()
    }
  },
  created() {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('access_token')
  },
  methods: {
    fillWithHot() {
      this.chartData = {
        labels: this.labelsHot, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [{
          label: 'Varmt vand',
          backgroundColor: '#f87979',
          fill: false,
          borderWidth: 1,
          borderColor: '#f87979',
          data: this.dataHot
        }]
      }
      this.renderChart(this.chartData, this.options)
    },
    fillWithCold() {
      this.chartData = {
        labels: this.labelsCold, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [{
          label: 'Koldt vand',
          backgroundColor: '#0a5493',
          fill: false,
          borderWidth: 1,
          borderColor: '#0a5493',
          data: this.dataCold
        }]
      }
      this.renderChart(this.chartData, this.options)
    },
    getHotDataFromReader() {
      let keys = Object.keys(this.readerHot)
      keys.forEach(key => {
        this.dataHot.push(this.readerHot[key].value)
        // this.timeOfReader.push(this.reader[key].date.date)
        let dateString = this.readerHot[key].date.date
        let dateTime = new Date(dateString)
        let year = dateTime.getFullYear() // "2019"
        year = year.toString().slice(-2) // "19"
        this.labelsHot.push(this.monthNames[dateTime.getMonth()] + ' \'' + year)
      })
      this.labelsHot.shift(); // fjerner første element af array da data altid vil være 0
      this.dataHot.shift(); // fjerner tilsvarende label
    },
    getColdDataFromReader() {
      let keys = Object.keys(this.readerCold)
      keys.forEach(key => {
        this.dataCold.push(this.readerCold[key].value)
        // this.timeOfReader.push(this.reader[key].date.date)
        let dateString = this.readerCold[key].date.date
        let dateTime = new Date(dateString)
        let year = dateTime.getFullYear() // "2019"
        year = year.toString().slice(-2) // "19"
        this.labelsCold.push(this.monthNames[dateTime.getMonth()] + ' \'' + year)
      })
      this.labelsCold.shift(); // fjerner første element af array da data altid vil være 0
      this.dataCold.shift(); // fjerner tilsvarende label
    },
    apiCalls() {
      let urlForCold = 'http://backend.test/api/data/consumption/cold/json'
      let urlForHot = 'http://backend.test/api/data/consumption/hot/json'

      axios
        .get(urlForCold)
        .then(response => (this.readerCold = response.data[0]))
        .then(response => (this.getColdDataFromReader()))
        .then(this.fillWithCold),
        axios
          .get(urlForHot)
          .then(response => (this.readerHot = response.data[0]))
          .then(response => (this.getHotDataFromReader()))
          .then(this.fillWithHot)
    }
  }
}
</script>

<style scoped>

</style>
