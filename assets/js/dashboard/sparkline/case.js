import { sparklineOptions } from '../shared/sparkline';
import ApexCharts from "apexcharts";

const options = {
    series: [{
        data: window.cases.data.reverse()
    }],
    xaxis: {
        categories: window.cases.dates.reverse()
    }
}

const allOptions = Object.assign(sparklineOptions, options);

if (window.cases) {
    new ApexCharts(document.querySelector("#caseChart"), allOptions).render();
}