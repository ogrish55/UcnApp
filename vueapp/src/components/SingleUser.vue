<template>
  <div>
    <h1>User:</h1>
    <div v-if="info">
      <p>Name: {{ info.firstName }}</p>
      <p>Email: {{ info.email }}</p>
      <p>Phone: {{ info.phoneNumber }}</p>
    </div>
    <!--    <div class="container" id="chart" v-if="reader">-->
    <!--      <line-chart :chart-data="datacollection" :options="chartOptions"></line-chart>-->
    <!--    </div>-->
    <div>
      <button style="width: 150px" type="button" class="btn-primary btn" @click="goBack()"> Back</button>
      <button @click="submit()" type="button" class="btn-primary btn">Monthly Measurements</button>
    </div>
    <div v-if="monthly">
      <MonthlyMeasurements></MonthlyMeasurements>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import HelloWorld from './HelloWorld'
import LineChart from './LineChart'
import MonthlyMeasurements from './MonthlyMeasurements'

export default {
  name: 'SingleUser',
  components: {MonthlyMeasurements, LineChart, HelloWorld},
  data () {
    return {
      info: null,
      monthly: false,
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
    submit () {
      if (this.monthly) {
        this.monthly = false
      } else {
        this.monthly = true
      }
    }
  }
}

</script>

<style scoped>
#chart {
  width: 1200px;
  height: 800px;
}
</style>
