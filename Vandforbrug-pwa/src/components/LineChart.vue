<script>
import {Line} from 'vue-chartjs/es/BaseCharts.js'
import axios from 'axios'

export default {
  extends: Line,
  name: 'LineChart',
  props: {
    action: null,
  },
  data() {
    return {
      monthReader: null,
      someBoolean: false,
      monthMeasurements: [],
      monthTimeOfReader: [],
      readerHot: null,
      averageHot: null,
      averageCold: null,
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
  watch: {
    action: function () {
      switch (this.action) {
        case 'all':
          this.fillWithAll();
          break;
        case 'hot':
          this.fillWithHot();
          break;
        case 'cold':
          this.fillWithCold();
          break;
        default:
          this.fillPrMonth(this.action)
          break;
      }
    },
  },
  mounted() {
    this.apiCallHot()
    this.apiCallCold()
    this.apiCallAverage();

  },
  created() {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('access_token')
  },
  methods: {
    fillAverageLines() {
      this.averageHotArr = []
      this.averageColdArr = []

      let length = this.dataCold.length
      for (var i = 0; i < length; i++) {
        this.averageHotArr[i] = this.averageHot;
        this.averageColdArr[i] = this.averageCold;
      }
    },
    fillWithHot() {
      this.chartData = {
        labels: this.labelsHot, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [{
          label: 'Varmt vand',
          backgroundColor: '#f87979',
          fill: true,
          borderWidth: 1,
          borderColor: '#f87979',
          data: this.dataHot
        },
          { // linje med gennemsnit
            label: 'Gennemsnit',
            data: this.averageHotArr,
            type: 'line',
            fill: false,
            borderColor: '#40c940',
            borderWidth: 3,
            pointRadius: 0, // fjerner punkterne
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
          fill: true,
          borderWidth: 1,
          borderColor: '#0a5493',
          data: this.dataCold
        },
          { // linje med gennemsnit
            label: 'Gennemsnit',
            data: this.averageColdArr,
            type: 'line',
            fill: false,
            borderColor: '#40c940',
            borderWidth: 3,
            pointRadius: 0, // fjerner punkterne
          }
        ]
      }
      this.renderChart(this.chartData, this.options)
    },
    fillWithAll() {
      this.chartData = {
        labels: this.labelsCold, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [{
          label: 'Koldt vand',
          backgroundColor: '#0a5493',
          fill: false,
          borderWidth: 1,
          borderColor: '#0a5493',
          data: this.dataCold
        },
          {
            label: 'Varmt vand',
            backgroundColor: '#f87979',
            fill: false,
            borderWidth: 1,
            borderColor: '#f87979',
            data: this.dataHot
          }
        ]
      }
      this.renderChart(this.chartData, this.options)
    },
    fillPrMonth(chosenMonth) {
      var monthName = chosenMonth.substring(0, chosenMonth.length - 4);
      var year = "20" + chosenMonth.substring(chosenMonth.length - 2, chosenMonth.length);

      this.apiCall(year, monthName); // call for data

      // fill the graph
      this.chartData = {
        labels: this.monthTimeOfReader, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [{
          label: 'Varmt vand',
          backgroundColor: '#f87979',
          borderColor: '#f87979',
          fill: true,
          borderWidth: 1,
          data: this.monthMeasurements
        }
        ]
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
    getMonthDataFromReader() {
      // Clear out the arrays
      this.monthTimeOfReader = []
      this.monthMeasurements = []
      let keys = Object.keys(this.monthReader)
      keys.forEach(key => {
        this.monthMeasurements.push(this.monthReader[key].value)
        // this.monthTimeOfReader.push(this.monthReader[key].date.date)
        let dateString = this.monthReader[key].date.date
        let dateTime = new Date(dateString) // konverter til date objekt
        // let year = dateTime.getFullYear() // "2019"
        // year = year.toString().slice(-2) // "19"
        this.monthTimeOfReader.push("d. " + dateTime.getDate())
        // this.monthTimeOfReader.push(dateString)
      })
    },
    apiCallHot() {
      let urlForHot = 'http://backend.test/api/data/consumption/hot/json'
      axios
        .get(urlForHot)
        .then(response => (this.readerHot = response.data[0]))
        .then(response => (this.getHotDataFromReader()))
        .then(this.fillWithAll)
    },
    apiCallCold() {
      let urlForCold = 'http://backend.test/api/data/consumption/cold/json'
      axios
        .get(urlForCold)
        .then(response => (this.readerCold = response.data[0]))
        .then(response => (this.getColdDataFromReader()))
        .then(this.fillWithAll)
    },
    apiCall(year, monthName) {
      axios
        .get('http://backend.test/api/data/dailyMeasurements/' + year + "/" + monthName)
        .then(response => (this.monthReader = response.data))
        .then(this.getMonthDataFromReader)
    },
    apiCallAverage() {
      axios
        .get('http://backend.test/api/data/average/hot')
        .then(response => (this.averageHot = response.data)),
        axios
          .get('http://backend.test/api/data/average/cold')
          .then(response => (this.averageCold = response.data))
          .then(this.fillAverageLines)
    }
  }
}
</script>

<style scoped>
</style>
