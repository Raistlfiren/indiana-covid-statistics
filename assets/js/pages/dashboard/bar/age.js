import { stackedBarOptions } from '../shared/bar';
import ApexCharts from "apexcharts";

const options = {
    series: [{
        name: 'Tests',
        data: window.ageDetails.tests
    }, {
        name: 'Cases',
        data: window.ageDetails.positives
    }, {
        name: 'Deaths',
        data: window.ageDetails.deaths
    }],
    xaxis: {
        categories: window.ageDetails.labels
    },
}

const allOptions = Object.assign(stackedBarOptions, options);

if (window.ageDetails) {
    new ApexCharts(document.querySelector("#ageChart"), allOptions).render();
}