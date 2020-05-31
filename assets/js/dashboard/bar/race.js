import { stackedBarOptions } from '../shared/bar';
import ApexCharts from "apexcharts";

const options = {
    series: [{
        name: 'Cases',
        data: window.raceDetails.positives
    }, {
        name: 'Deaths',
        data: window.raceDetails.deaths
    }],
    xaxis: {
        categories: window.raceDetails.labels
    },
}

const allOptions = Object.assign(stackedBarOptions, options);

if (window.raceDetails) {
    new ApexCharts(document.querySelector("#raceChart"), allOptions).render();
}