<template>
  <div>
    <h1>User:</h1>
    <div class="container">
      <div style="text-align: start">
        <p>Name: {{ info.firstName }} {{info.lastName}}</p>
        <p>Email: {{ info.email }}</p>
        <p>Phone: {{ info.phoneNumber }}</p>
      </div>
    </div>
    <div>
      <button style="width: 150px" type="button" class="btn-primary btn" @click="goBack()"> Back</button>
      <button @click="monthlySubmit()" type="button" class="btn-primary btn">Monthly Measurements</button>
      <button @click="consumptionSubmit()" type="button" class="btn-primary btn">Monthly Consumption</button>
    </div>
    <div style="display: flex; justify-content: center">
      <div v-if="monthly" class="displayGraph">
        <MonthlyMeasurements></MonthlyMeasurements>
      </div>
      <div v-if="consumption" class="displayGraph">
        <Consumption></Consumption>
      </div>
    </div>
  </div>
</template>

<script>
import FrontPage from './FrontPage'
import MonthlyMeasurements from './MonthlyMeasurements'
import Consumption from './Consumption'
import {mapState} from "vuex";

export default {
  name: 'SingleUser',
  components: {MonthlyMeasurements, FrontPage, Consumption},
  data () {
    return {
      monthly: false,
      consumption: false
    }
  },
  created () {
    this.$store.dispatch('retrieveUser')
  },
  computed: {
    ...mapState({
      info: 'user'
    })
  },
  methods: {
    goBack () {
      this.$router.push('/')
    },
    monthlySubmit () {
      if (this.monthly) {
        this.monthly = false
      } else {
        this.monthly = true
        this.consumption = false
      }
    },
    consumptionSubmit () {
      if (this.consumption) {
        this.consumption = false
      } else {
        this.consumption = true
        this.monthly = false
      }
    },
    hideGraph () {
      this.consumption = false
      this.monthly = false
    }
  }
}

</script>

<style scoped>
#chart {
  width: 1200px;
  height: 800px;
}

.container {
  display: flex;
  justify-content: space-evenly;
}

.rightArrow {
  display: flex;
  align-items: center;
}

.leftArrow {
  display: flex;
  align-items: center;
}
</style>
