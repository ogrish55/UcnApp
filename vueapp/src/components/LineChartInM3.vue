<script>
import { Line } from "vue-chartjs";
// import LineChart from './LineChart'
import axios from "axios";
export default {
  extends: Line,
  name: "LineChart",
  data() {
    return {
      monthNames: [
        "Januar",
        "Februar",
        "Marts",
        "April",
        "Maj",
        "Juni",
        "Juli",
        "August",
        "September",
        "Oktober",
        "November",
        "December",
      ],
      chartData: null,
      chartOptions: {
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
              },
              gridLines: {
                display: true,
              },
            },
          ],
          xAxes: [
            {
              gridLines: {
                display: false,
              },
            },
          ],
        },
        legend: {
          display: true,
        },
        responsive: true,
        maintainAspectRatio: false,
      },
      hotReader: null,
      coldReader: null,
      hotMeasurements: [],
      coldMeasurements: [],
      hotTimeOfReader: [],
      coldTimeOfReader: [],
      averageHot: null,
      averageCold: null,
      averageHotArr: [],
      averageColdArr: [],
    };
  },
  mounted() {
    this.apiCalls();
  },
  methods: {
    fillWithHot() {
      this.chartData = {
        labels: this.hotTimeOfReader,
        datasets: [
          {
            // forbruget
            label: "Varmt vand",
            backgroundColor: "#f87979",
            data: this.hotMeasurements,
            fill: false,
            borderWidth: 4,
            borderColor: "#f87979",
            backgroundColor: "#f87979",
          },
          {
            // linje med gennemsnit
            label: "Gennemsnit",
            data: this.averageHotArr,
            type: "line",
            fill: false,
            borderColor: "#1CAB0B",
            borderWidth: 1,
            pointRadius: 0, // fjerner punkterne
          },
        ],
      };
      this.renderChart(this.chartData, this.options);
    },
    // fillWithCold() {
    //   this.chartData = {
    //     labels: this.coldTimeOfReader,
    //     datasets: [
    //       {
    //         label: "Koldt vand",
    //         backgroundColor: "#0a5493",
    //         data: this.coldMeasurements,
    //         fill: false,
    //         borderWidth: 1,
    //         borderColor: "#0a5493",
    //         backgroundColor: "#0a5493",
    //       },
    //       {
    //         // linje med gennemsnit
    //         label: "Gennemsnit",
    //         data: this.averageColdArr,
    //         type: "line",
    //         fill: false,
    //         borderColor: "#1CAB0B",
    //         borderWidth: 1,
    //         pointRadius: 0, // fjerner punkterne
    //       },
    //     ],
    //   }
    //   // this.renderChart(this.chartData, this.chartOptions)
    // },
    fillAverageLines() {
      let length = this.hotMeasurements.length;
      for (var i = 0; i < length; i++) {
        this.averageHotArr[i] = this.averageHot;
        // this.averageColdArr[i] = this.averageCold;
      }
    },
    // getColdDataFromReader() {
    //   let keys = Object.keys(this.coldReader);
    //   keys.forEach((key) => {
    //     this.coldMeasurements.push(this.coldReader[key].value);
    //     let dateString = this.coldReader[key].date.date;
    //     let dateTime = new Date(dateString); // konverter til date objekt
    //     let year = dateTime.getFullYear(); // "2019"
    //     year = year.toString().slice(-2); // "19"

    //     this.coldTimeOfReader.push(
    //       this.monthNames[dateTime.getMonth()] + " '" + year
    //     );
    //   });
    //   this.coldMeasurements.shift(); // fjerner første element af array da data altid vil være 0
    //   this.coldTimeOfReader.shift(); // fjerner tilsvarende label
    // },
    getHotDataFromReader() {
      let keys = Object.keys(this.hotReader);
      keys.forEach((key) => {
        this.hotMeasurements.push(this.hotReader[key].value);
        let dateString = this.hotReader[key].date.date;
        let dateTime = new Date(dateString);
        let year = dateTime.getFullYear(); // "2019"
        year = year.toString().slice(-2); // "19"

        this.hotTimeOfReader.push(
          this.monthNames[dateTime.getMonth()] + " '" + year
        );
      });
      this.hotMeasurements.shift(); // fjerner første element af array da data altid vil være 0
      this.hotTimeOfReader.shift(); // fjerner tilsvarende label
    },
    apiCalls() {
      //   axios
      //     .get("http://backend.test/api/data/consumption/cold/json")
      //     .then((response) => (this.coldReader = response.data[0]))
      //     .then((response) => this.getColdDataFromReader()),
      // .then(this.fillWithCold),
      axios
        .get("http://backend.test/api/data/consumption/hot/json")
        .then((response) => (this.hotReader = response.data[0]))
        .then((response) => this.getHotDataFromReader()),
        //   .then(this.fillWithHot),
        axios
          .get("http://backend.test/api/data/average/hot")
          .then((response) => (this.averageHot = response.data))
          .then(this.fillAverageLines)
          .then(this.fillWithHot);
      // axios
      //   .get("http://backend.test/api/data/average/hot")
      //   .then((response) => (this.averageHot = response.data)),
      // axios
      //   .get("http://backend.test/api/data/average/cold")
      //   .then((response) => (this.averageCold = response.data))
      //   .then(this.fillAverageLines)
      //   .then(this.fillWithHot)
      //   .then(this.fillWithCold)
    },
  },
};
</script>
}


<style scoped>
</style>
