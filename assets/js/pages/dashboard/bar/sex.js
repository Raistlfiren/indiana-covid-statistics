import { stackedBarOptions } from '../shared/bar';
import ApexCharts from "apexcharts";

const options = {
    series: [{
        name: 'Tests',
        data: window.sexDetails.tests
    }, {
        name: 'Cases',
        data: window.sexDetails.positives
    }, {
        name: 'Deaths',
        data: window.sexDetails.deaths
    }],
    xaxis: {
        categories: window.sexDetails.labels
    },
}

const allOptions = Object.assign(stackedBarOptions, options);

if (window.sexDetails) {
    new ApexCharts(document.querySelector("#sexChart"), allOptions).render();
}