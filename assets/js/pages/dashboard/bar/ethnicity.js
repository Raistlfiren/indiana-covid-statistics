import { stackedBarOptions } from '../shared/bar';
import ApexCharts from "apexcharts";

const options = {
    series: [{
        name: 'Cases',
        data: window.ethnicityDetails.positives
    }, {
        name: 'Deaths',
        data: window.ethnicityDetails.deaths
    }],
    xaxis: {
        categories: window.ethnicityDetails.labels
    },
}

const allOptions = Object.assign(stackedBarOptions, options);

if (window.ethnicityDetails) {
    new ApexCharts(document.querySelector("#ethnicityChart"), allOptions).render();
}