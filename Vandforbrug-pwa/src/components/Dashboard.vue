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
            <h2 class="text-center display-4 standard-heading">Din Oversigt</h2>
            <p class="text-center lead second-heading">Forbrug for indeværende regnskabsår</p>
            <!-- Cards row-->
            <Consumption-Overview></Consumption-Overview>
            <hr>
            
            <!-- Content Row -->
            <h2 class="text-center display-4 standard-heading">Få et overblik</h2>
            <p class="text-center lead second-heading">Brug dropdown menuerne til at indskrænke din søgning</p>
            <div class="row">
              <line-chart></line-chart>
            </div> 
            <hr>

            <!-- Content Row -->
            <h2 class="text-center display-4 standard-heading">Dyk ned i dit forbrug</h2>
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

              <!-- <hr> -->
              
              <div class="col-lg-6 mb-4">
                <daily-grid></daily-grid>
              </div>
            </div>

          </div>
          <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


  </div>
</template>

<script>
import BarChart from './chartJS/BarChart'
import BarChartInDkk from './VueCharts/BarChartInDkk'
import ConsumptionOverview from "@/components/ConsumptionOverview";
import '../assets/vendor/jquery/jquery.min'
import '../assets/vendor/jquery-easing/jquery.easing.min.js'
import '../assets/vendor/chart.js/Chart.min.js'
import '../assets/vendor/chart.js/Chart.min.js'
import LineChart from './VueCharts/LineChart'
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
  created () {
    this.$store.dispatch('retrieveUser')
  }
}

</script>

<style scoped>
@import '../assets/styles/sb-admin-2.min.css';
@import '../assets/styles/all.min.css';

#wrapper {
  margin-top: 10px;
  position: relative;
  bottom: 0px;
}

#cardrow {
  margin-top: 15px;
}

.standard-heading {
  font-family: 'Varela Round', sans-serif;
  position: relative;
  font-size: 32px;
}

.second-heading {
  font-size: 14px;
}

</style>
