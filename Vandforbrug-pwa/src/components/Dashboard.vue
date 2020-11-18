<template>
  <div>
    <!-- Page Wrapper -->
    <div id="wrapper" v-if="!this.loading">
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column"> <!-- bg-gradient-primary-to-secondary */ -->

        <!-- Main Content -->
        <div id="content">

          <!-- Begin Page Content -->
          <div class="container-fluid">

            <!-- Cards row-->
            <Consumption-Overview></Consumption-Overview>

            <!-- Content Row -->
            <div class="row">
              <line-chart></line-chart>
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
    <div id="habibi" v-else style="display: flex; justify-content: center; margin-top: 450px">
      <div class="loader">

      </div>
    </div>
    <!-- End of Page Wrapper -->


  </div>
</template>

<script>
import BarChart from './BarChart'
import BarChartInDkk from './BarChartInDkk'
import ConsumptionOverview from "@/components/ConsumptionOverview";
import '../assets/vendor/jquery/jquery.min'
import '../assets/vendor/jquery-easing/jquery.easing.min.js'
import '../assets/vendor/chart.js/Chart.min.js'
import '../assets/vendor/chart.js/Chart.min.js'
import LineChart from './LineChart'
import axios from 'axios'
import DailyGrid from './DailyGrid'
export default {
  name: 'Dashboard',
  components: {
    LineChart,
    BarChart,
    BarChartInDkk,
    DailyGrid,
    ConsumptionOverview,
  },
  data () {
    return {
      loading: true
    }
  },

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
.sticky-footer {
  /* gradient baggrund */
  background: rgb(2, 0, 36);
  background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(73, 87, 255, 0.4150035014005602) 0%, rgba(255, 0, 0, 0.2553396358543417) 100%);
}

.loader {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 300px;
  height: 300px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
