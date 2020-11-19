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
              <i class="fas fa-tint fa-2x color-waterDroplet"></i>
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
              <i class="fas fa-dollar-sign fa-2x color-DollarSign"></i>
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
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Betalt acconto</div>
              <div v-bind="calculateAconto" class="h5 mb-0 font-weight-bold text-gray-800">{{ paidAconto }} DKK
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-piggy-bank fa-2x color-PiggyBank"></i>
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
              <i class="fas fa-dollar-sign fa-2x color-DollarSign"></i>
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

  data() {
    return {
      usageInM3: null,
      usageInDkk: null,
      paidAconto: null,
      acontoPrMonth: null,
      pricePrCubic: null,
      difference: null,
      monthNumber: null
    }
  },

  computed: {
    calculateUsageInDkk() {
      this.usageInDkk = (this.usageInM3 * this.pricePrCubic).toFixed(2)
    },
    calculateAconto() {
      this.paidAconto = (this.monthNumber * this.acontoPrMonth).toFixed(2)
    },
    calculateDiff() {
      this.difference = (this.paidAconto - this.usageInDkk).toFixed(2)
    }
  },

  created() {
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

    //Get aconto
    axios
      .get('http://backend.test/api/data/useraconto')
      .then(response => (this.acontoPrMonth = response.data))

    // Get price pr M3
    axios
    .get('http://backend.test/api/data/region')
    .then(response => (this.pricePrCubic = response.data))
  }
}
</script>

<style scoped>
.color-waterDroplet {
  color: #2483d1 !important;
}

.color-DollarSign {
  color: #039218bb !important;
}

.color-PiggyBank {
  color: #f8ba0f !important;
}
</style>
