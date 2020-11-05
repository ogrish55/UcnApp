<template>
  <div>
    <!-- Page Wrapper -->
    <div id="wrapper">

      <!-- Sidebar -->
      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
          <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Kontrolpanel</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
          Bruger
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
          <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-cog"></i>
            <span>Oplysninger</span></a>
        </li>

        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Components:</h6>
            <a class="collapse-item" href="buttons.html">Buttons</a>
            <a class="collapse-item" href="cards.html">Cards</a>
          </div>
        </div>

        <!-- Divider -->
        <hr class="sidebar-divider">


      </ul>
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

          <!-- Begin Page Content -->
          <div class="container-fluid">

            <!-- Content Row -->
            <div class="row" id="cardrow">

              <!-- Forbrug i M3 -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Forbrug i m<sup>3</sup></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ usageInM3 }} M<sup>3</sup></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-home fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Forbrug i DKK -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Forbrug i DKK</div>
                        <div v-bind="calculateUsageInDkk" class="h5 mb-0 font-weight-bold text-gray-800">
                          {{ this.usageInDkk }} DKK
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Betalt aconto -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Betalt aconto</div>
                        <div v-bind="calculateAconto" class="h5 mb-0 font-weight-bold text-gray-800">{{ aconto }} DKK
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Difference i DKK -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Difference i DKK</div>
                        <div v-bind="calculateDiff" class="h5 mb-0 font-weight-bold text-gray-800">{{ difference }}
                          DKK
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


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
                         aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                           aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" v-on:click="fillWithHot"><strong>Varmt vand</strong></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" v-on:click="fillWithCold"><strong>Koldt vand</strong></a>
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

<!--              &lt;!&ndash; TEST CHART &ndash;&gt;-->
<!--              <div class="col-xl-8 col-lg-7">-->
<!--                <div class="card shadow mb-4">-->
<!--                  &lt;!&ndash; Card Header - Dropdown &ndash;&gt;-->
<!--                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">-->
<!--                    <h6 class="m-0 font-weight-bold text-primary">Vandforbrug</h6>-->
<!--                    <div class="dropdown no-arrow">-->
<!--                      <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"-->
<!--                         aria-haspopup="true" aria-expanded="false">-->
<!--                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>-->
<!--                      </a>-->
<!--                      <div class="dropdown-menu dropdown-menu-right shadow animated&#45;&#45;fade-in"-->
<!--                           aria-labelledby="dropdownMenuLink">-->
<!--                        <a class="dropdown-item" v-on:click="fillWithHot"><strong>Varmt vand</strong></a>-->
<!--                        <div class="dropdown-divider"></div>-->
<!--                        <a class="dropdown-item" v-on:click="fillWithCold"><strong>Koldt vand</strong></a>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                  &lt;!&ndash; Card Body &ndash;&gt;-->
<!--                  <div class="card-body">-->
<!--                    <div class="chart-area">-->
<!--                      <bar-chart :width="300" :height="70" v-if="ready" :chart-data="datacollection"-->
<!--                                 :options="chartOptions"></bar-chart>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->

              <!-- Pie Chart -->
              <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                  <!-- Card Header - Dropdown -->
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                    <div class="dropdown no-arrow">
                      <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
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

                <!-- Color System -->
                <div class="row">
                  <div class="col-lg-6 mb-4">
                    <div class="card bg-primary text-white shadow">
                      <div class="card-body">
                        Primary
                        <div class="text-white-50 small">#4e73df</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-4">
                    <div class="card bg-success text-white shadow">
                      <div class="card-body">
                        Success
                        <div class="text-white-50 small">#1cc88a</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-4">
                    <div class="card bg-info text-white shadow">
                      <div class="card-body">
                        Info
                        <div class="text-white-50 small">#36b9cc</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-4">
                    <div class="card bg-warning text-white shadow">
                      <div class="card-body">
                        Warning
                        <div class="text-white-50 small">#f6c23e</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-4">
                    <div class="card bg-danger text-white shadow">
                      <div class="card-body">
                        Danger
                        <div class="text-white-50 small">#e74a3b</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-4">
                    <div class="card bg-secondary text-white shadow">
                      <div class="card-body">
                        Secondary
                        <div class="text-white-50 small">#858796</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-4">
                    <div class="card bg-light text-black shadow">
                      <div class="card-body">
                        Light
                        <div class="text-black-50 small">#f8f9fc</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-4">
                    <div class="card bg-dark text-white shadow">
                      <div class="card-body">
                        Dark
                        <div class="text-white-50 small">#5a5c69</div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <div class="col-lg-6 mb-4">

                <!-- Illustrations -->
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                  </div>
                  <div class="card-body">
                    <!-- <div class="text-center">
                      <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                           src="img/undraw_posting_photo.svg" alt="">
                    </div> -->
                    <p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow"
                                                                                          href="https://undraw.co/">unDraw</a>,
                      a constantly updated collection of beautiful svg images that you can use completely free and
                      without attribution!</p>
                    <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw
                      &rarr;</a>
                  </div>
                </div>

                <!-- Approach -->
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                  </div>
                  <div class="card-body">
                    <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce CSS bloat and
                      poor page performance. Custom CSS classes are used to create custom components and custom utility
                      classes.</p>
                    <p class="mb-0">Before working with this theme, you should become familiar with the Bootstrap
                      framework, especially the utility classes.</p>
                  </div>
                </div>

              </div>
            </div>

          </div>
          <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; Your Website 2020</span>
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
import '../assets/vendor/jquery/jquery.min'
// import '../assets/vendor/bootstrap/js/bootstrap.bundle.min.js';
import '../assets/vendor/jquery-easing/jquery.easing.min.js'
// import '../assets/js/sb-admin-2.min.js'
import '../assets/vendor/chart.js/Chart.min.js'
import '../assets/vendor/chart.js/Chart.min.js'
import LineChart from './LineChart'
import axios from 'axios'
// import '../assets/js/demo/chart-pie-demo.js';
export default {
  name: 'Dashboard',
  components: {
    LineChart,
    BarChart,
    BarChartInDkk
  },
  data () {
    return {
      ready: false,
      coldReady: false,
      datacollection: null,
      coldDatacollection: null,
      measurements: [],
      coldMeasurements: [],
      timeOfReader: [],
      coldTimeOfReader: [],
      reader: null,
      coldReader: null,
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
              display: false
            }
          }]
        },
        legend: {
          display: true
        },
        responsive: true,
        maintainAspectRatio: true
      },
      usageInDkk: null,
      usageInM3: null,
      aconto: null,
      difference: null,
      monthNumber: null,
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
    fillWithHot () {
      this.datacollection = {
        labels: this.timeOfReader, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [
          {
            label: 'Varmt vand',
            backgroundColor: '#f87979',
            data: this.measurements,
            fill: false,
            borderWidth: 1,
            borderColor: '#f87979',
            backgroundColor: '#f87979'
          }
        ]
      }
      this.ready = true
    },
    fillWithCold () {
      this.datacollection = {
        labels: this.coldTimeOfReader, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [
          {
            label: 'Koldt vand',
            backgroundColor: '#0a5493',
            data: this.coldMeasurements,
            fill: false,
            borderWidth: 1,
            borderColor: '#0a5493',
            backgroundColor: '#0a5493'
          }
        ]
      }
      this.coldReady = true
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
      this.measurements.shift(); // fjerner første element af array da data altid vil være 0
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
          .then(this.fillWithHot),
        axios
          .get('http://backend.test/api/data/currentYearUsage/total')
          .then(response => (this.usageInM3 = response.data)),
        axios
          .get('http://backend.test/api/data/currentYearUsage/total/monthNumber')
          .then(response => (this.monthNumber = response.data))
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
</style>
