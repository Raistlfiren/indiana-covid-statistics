import { sparklineOptions } from '../shared/sparkline';
import ApexCharts from "apexcharts";

const options = {
    series: [{
        data: window.tests.data.reverse()
    }],
    xaxis: {
        categories: window.tests.dates.reverse()
    },
}

const allOptions = Object.assign(sparklineOptions, options);

if (window.tests) {
    new ApexCharts(document.querySelector("#testChart"), allOptions).render();
}