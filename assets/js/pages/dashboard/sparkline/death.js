import { sparklineOptions } from '../shared/sparkline';
import ApexCharts from "apexcharts";

const options = {
    series: [{
        data: window.deaths.data.reverse()
    }],
    xaxis: {
        categories: window.deaths.dates.reverse()
    }
}

const allOptions = Object.assign(sparklineOptions, options);

if (window.deaths) {
    new ApexCharts(document.querySelector("#deathChart"), allOptions).render();
}