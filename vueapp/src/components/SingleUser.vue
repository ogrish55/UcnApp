<template>
  <div>
    <h1>User:</h1>
    <div class="container">
      <div class="leftArrow">
        <button @click="goPrevious()" type="button" class="btn btn-info"> Previous</button>
      </div>
      <div v-if="info" style="text-align: start">
        <p>Name: {{ info.firstName }}</p>
        <p>Email: {{ info.email }}</p>
        <p>Phone: {{ info.phoneNumber }}</p>
      </div>
      <div class="rightArrow">
        <button @click="goNext()" type="button" class="btn btn-info"> Next</button>
      </div>
    </div>
    <!--    <div class="container" id="chart" v-if="reader">-->
    <!--      <line-chart :chart-data="datacollection" :options="chartOptions"></line-chart>-->
    <!--    </div>-->
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
import axios from 'axios'
import FrontPage from './FrontPage'
import LineChart from './LineChart'
import MonthlyMeasurements from './MonthlyMeasurements'
import Consumption from './Consumption'

export default {
  name: 'SingleUser',
  components: {MonthlyMeasurements, LineChart, FrontPage, Consumption},
  data () {
    return {
      info: null,
      monthly: false,
      consumption: false,
      datacollection: null,
      measurements: [],
      timeOfReader: [],
      reader: null,
      chartOptions: {
        animation: {
          duration: 0
        },
        hover: {
          animationDuration: 0
        },
        responsiveAnimationDuration: 0
      }
    }
  },
  mounted () {
    this.apiCalls()
  },
  methods: {
    goBack () {
      this.$router.push('/')
    },
    fillData () {
      this.datacollection = {
        labels: this.timeOfReader, // ['13-05-2019', '14-05-2019', '15-05-2019', '16-05-2019', '17-05-2019'], // Time of read
        datasets: [
          {
            label: 'Hot water',
            backgroundColor: '#f87979',
            data: this.measurements
          }
        ]
      }
    },
    getDataFromReader () {
      this.reader.forEach(element => this.measurements.push(parseInt(element.value, 10)))
      this.reader.forEach(object => this.timeOfReader.push(object.measurement))
    },
    apiCalls () {
      axios
        .get('http://localhost:8000/api/users/' + this.$route.params.userId)
        .then(response => (this.info = response.data))
      axios
        .get('http://localhost:8000/api/measurement/' + this.$route.params.userId)
        .then(response => (this.reader = response.data[0]))
        .then(response => (this.getDataFromReader()))
        .then(this.fillData)
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
    goPrevious () {
      if (this.$route.params.userId > 1) {
        // this.$router.push({name: 'user', params: this.$route.params.userId--})
        this.$router.replace({name: 'user', params: this.$route.params.userId--})
        this.apiCalls()
        this.hideGraph()
      }
    },
    goNext () {
      this.$router.replace({name: 'user', params: this.$route.params.userId++})
      this.apiCalls()
      this.hideGraph()
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
