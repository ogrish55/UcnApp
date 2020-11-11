<template> 
    <ag-grid-vue style="width: 75%; height: 100%;"
        class="ag-theme-alpine"
        :columnDefs="columns"
        :rowData="rowData">
    </ag-grid-vue>
</template>

<script>
import "ag-grid-community/dist/styles/ag-grid.css";
import "ag-grid-community/dist/styles/ag-theme-alpine.css";
import { AgGridVue } from 'ag-grid-vue';
import axios from 'axios'

export default {
    name: "DailyMeasurementsGrid",
    components: {
        AgGridVue
    },
    data () {
        return {
            columns: null,
            rowData: null,
            reader: null
        }
    },
    beforeMount(){
        this.columns = [
            {headerName: 'År', field: 'year', sortable: true, filter: true},
            {headerName: 'Måned', field: 'month', sortable: true, filter: true},
            {headerName: 'Dag', field: 'day', sortable: true, filter: true},
            {headerName: 'Måling i m3', field: 'value', sortable: true, filter: true}
        ];

        axios
        .get('http://backend.test/api/data/dailyMeasurements/all')
        .then(response => (this.rowData = response.data))

    }
}
</script>

<style>
    .ag-row-hover {
    /* putting in !important so it overrides the theme's styling as it hovers the row also */
    background-color: #dfdfff !important;
    }

    .ag-column-hover {
    background-color: #dfffdf;
    }
</style>
