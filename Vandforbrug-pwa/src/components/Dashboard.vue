<template>
  <div>
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column"> <!-- bg-gradient-primary-to-secondary */ -->

        <!-- Main Content -->
        <div id="content">

          <!-- Begin Page Content -->
          <div class="container-fluid">
            <!-- Content Row -->
            <forbrugsoversigt></forbrugsoversigt>
            <!-- Content Row -->

            <div class="row">

              <!-- Area Chart -->
              <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                  <!-- Card Header - Dropdown -->
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Vandforbrug</h6>
                    <div class="dropdown no-arrow">
                      <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false"> Vandtype
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                           aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" v-on:click="fillWithHot"><strong>Varmt vand</strong></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" v-on:click="fillWithCold"><strong>Koldt vand</strong></a>
                      </div>
                    </div>
                    <div class="dropdown">
                      <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Vælg måned
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" v-on:click="fillWithHot">Alle</a>
                        <a class="dropdown-item" v-for="month in coldTimeOfReader" :key="month" v-on:click="fillTest(month)">
                          {{month}}
                        </a>
                      </div>
                    </div>
                  </div>
                  <!-- Card Body -->
                  <div class="card-body">
                    <div class="chart-area">
                      <line-chart :width="300" :height="70" v-if="ready" :chart-data="datacollection"
                                  :options="chartOptions"></line-chart>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pie Chart -->
              <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                  <!-- Card Header - Dropdown -->
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Dyk ned i dit forbrug</h6>
                    <div class="dropdown no-arrow">
                      <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                           aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                      </div>
                    </div>
                  </div>
                  <!-- Card Body -->
                  <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                      <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Direct
                    </span>
                      <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Social
                    </span>
                      <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Referral
                    </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <hr>

            <!-- Content Row -->
            <div class="row">

              <!-- Content Column -->
              <div class="col-lg-6 mb-4">

                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Forbrug i DKK (månedligt)</h6>
                  </div>
                  <div class="card-body">
                    <bar-chart-in-dkk></bar-chart-in-dkk>
                  </div>
                </div>
              </div>

              <!-- <div class="" -->
              <div class="col-lg-6 mb-4"> 
                <!-- <h1>Dyk ned i dit forbrug</h1> -->
                <daily-grid></daily-grid>
              </div>


            </div>

            <!-- <h1>Dyk ned i dit forbrug</h1>
            <daily-grid></daily-grid> -->

          </div>
          <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; UCN gruppe 1 2020 Let's Gooo</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->

      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


  </div>
</template>

<script>
import BarChart from './BarChart'
import BarChartInDkk from './BarChartInDkk'
import Forbrugsoversigt from "@/components/Forbrugsoversigt";
import '../assets/vendor/jquery/jquery.min'
// import '../assets/vendor/bootstrap/js/bootstrap.bundle.min.js';
import '../assets/vendor/jquery-easing/jquery.easing.min.js'
// import '../assets/js/sb-admin-2.min.js'
import '../assets/vendor/chart.js/Chart.min.js'
import '../assets/vendor/chart.js/Chart.min.js'
import LineChart from './LineChart'
import axios from 'axios'
import DailyGrid from './DailyGrid'
// import '../assets/js/demo/chart-pie-demo.js';
export default {
  name: 'Dashboard',
  components: {
    LineChart,
    BarChart,
    BarChartInDkk,
    DailyGrid,
    Forbrugsoversigt
  },
  data () {
    return {
      ready: false,
      coldReady: false,
      datacollection: null,
      coldDatacollection: null,
      measurements: [],
      coldMeasurements: [],
      monthMeasurements: [],
      monthTimeOfReader: [],
      timeOfReader: [],
      coldTimeOfReader: [],
      reader: null,
      coldReader: null,
      monthReader: null,
      averageHot: null,
      averageCold: null,
      averageHotArr: [],
      averageColdArr: [],
      chartOptions: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: true
            }
          }],
          xAxes: [ {
            gridLines: {
              display: true
            }
          }]
        },
        legend: {
          display: true
        },
        responsive: true,
        maintainAspectRatio: true
      },
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
      ]
    }
  },
  computed: {
    calculateUsageInDkk () {
      this.usageInDkk = (this.usageInM3 * 54.84).toFixed(2)
    },
    calculateAconto () {
      this.aconto = (this.monthNumber * 400).toFixed(2)
    },
    calculateDiff () {
      this.difference = (this.aconto - this.usageInDkk).toFixed(2)
    }
  },
  mounted () {
    this.apiCalls()
  },
  created () {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('access_token')
  },
  methods: {
    fillTest(chosenMonth){
      var monthName = chosenMonth.substring(0, chosenMonth.length - 4);
      var year = "20" + chosenMonth.substring(chosenMonth.length - 2, chosenMonth.length);

      this.apiCall(year, monthName); // call for data

      // fill the graph
      this.datacollection = {
        labels: this.monthTimeOfReader,
        datasets: [
          {
            label: monthName + " " + year,
            backgroundColor: '#c94040',
            data: this.monthMeasurements,
            fill: true,
            borderWidth: 1,
            borderColor: '#f87979',
          }
        ]
      }
    },
    fillWithHot () {
      this.apiCallAverage();

      this.datacollection = {
        labels: this.timeOfReader, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [
          {
            label: 'Varmt vand',
            backgroundColor: '#c94040',
            data: this.measurements,
            fill: true,
            borderWidth: 1,
            borderColor: '#f87979',
          },
          { // linje med gennemsnit
            label: 'Gennemsnit',
            data: this.averageHotArr,
            type: 'line',
            fill: false,
            borderColor: '#40c940',
            borderWidth: 3,
            pointRadius: 0, // fjerner punkterne
          }
        ]
      }

      this.ready = true
    },
    fillWithCold () {
      this.apiCallAverage();

      this.datacollection = {
        labels: this.coldTimeOfReader, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [
          {
            label: 'Koldt vand',
            backgroundColor: '#0a5493',
            data: this.coldMeasurements,
            fill: true,
            borderWidth: 1,
            borderColor: '#0a5493',
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
      this.coldReady = true
    },
    fillAverageLines () {
      this.averageHotArr = []
      this.averageColdArr = []

      let length = this.measurements.length
      for(var i = 0; i < length; i++){
        this.averageHotArr[i] = this.averageHot;
        this.averageColdArr[i] = this.averageCold;
      }
    },
    getDataFromReader () {
      let keys = Object.keys(this.reader)
      keys.forEach(key => {
        this.measurements.push(this.reader[key].value)
        // this.timeOfReader.push(this.reader[key].date.date)
        let dateString = this.reader[key].date.date
        let dateTime = new Date(dateString)
        let year = dateTime.getFullYear() // "2019"
        year = year.toString().slice(-2) // "19"
        this.timeOfReader.push(this.monthNames[dateTime.getMonth()] + ' \'' + year)
      })
      this.measurements.shift(); // fjerner første element af array da data altid vil være 0s
      this.timeOfReader.shift(); // fjerner tilsvarende label
    },
    getColdDataFromReader () {
      let keys = Object.keys(this.coldReader)
      keys.forEach(key => {
        this.coldMeasurements.push(this.coldReader[key].value)
        let dateString = this.coldReader[key].date.date
        let dateTime = new Date(dateString) // konverter til date objekt
        let year = dateTime.getFullYear() // "2019"
        year = year.toString().slice(-2) // "19"
        this.coldTimeOfReader.push(this.monthNames[dateTime.getMonth()] + " '" + year)
      })
      this.coldMeasurements.shift(); // fjerner første element af array da data altid vil være 0
      this.coldTimeOfReader.shift(); // fjerner tilsvarende label
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
    apiCall(year, monthName){
       axios
        .get('http://backend.test/api/data/dailyMeasurements/' + year + "/" + monthName)
        .then(response => (this.monthReader = response.data))
        .then(response => (this.getMonthDataFromReader()))
    },
    apiCallAverage(){
      axios
        .get('http://backend.test/api/data/average/hot')
        .then(response => (this.averageHot = response.data)),
      axios
        .get('http://backend.test/api/data/average/cold')
        .then(response => (this.averageCold = response.data))
        .then(this.fillAverageLines)
    },
    apiCalls () {
      axios
        .get('http://backend.test/api/data/consumption/cold/json')
        .then(response => (this.coldReader = response.data[0]))
        .then(response => (this.getColdDataFromReader()))
        .then(this.fillWithCold),
        axios
          .get('http://backend.test/api/data/consumption/hot/json')
          .then(response => (this.reader = response.data[0]))
          .then(response => (this.getDataFromReader()))
          .then(this.fillWithHot)
    }
  }
}
</script>

<style scoped>
@import '../assets/styles/sb-admin-2.min.css';
@import '../assets/styles/all.min.css';

#wrapper {
  position: relative;
  bottom: 34px;
}
#cardrow {
  margin-top: 15px;
}
/* #content{
  background: rgb(34,193,195);
  background: linear-gradient(193deg, rgba(34,193,195,0.3841911764705882) 0%, rgba(253,187,45,0.3253676470588235) 100%);
} */
.sticky-footer{
  /* gradient baggrund */
  background: rgb(2,0,36);
  background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(73,87,255,0.4150035014005602) 0%, rgba(255,0,0,0.2553396358543417) 100%);
}
</style>
