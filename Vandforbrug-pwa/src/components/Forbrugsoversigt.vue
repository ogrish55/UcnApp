<template>
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
              <i class="fas fa-tint fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Forbrug i DKK -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Forbrug i DKK</div>
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
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Betalt aconto</div>
              <div v-bind="calculateAconto" class="h5 mb-0 font-weight-bold text-gray-800">{{ aconto }} DKK
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-piggy-bank fa-2x text-gray-300"></i>
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
</template>

<script>
import axios from "axios";

export default {
  name: "Forbrugsoversigt",

  data () {
    return {
      usageInM3: null,
      usageInDkk: null,
      aconto: null,
      difference: null,
      monthNumber: null
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

  created () {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('access_token')
  },

  mounted() {
    // Get total usage in M3
    axios
      .get('http://backend.test/api/data/currentYearUsage/total')
      .then(response => (this.usageInM3 = response.data))

    // Get current month
    axios
      .get('http://backend.test/api/data/currentYearUsage/total/monthNumber')
      .then(response => (this.monthNumber = response.data))
  }
}
</script>

<style scoped>

</style>
