import { lineChartOptions } from '../shared/line';
import ApexCharts from "apexcharts";

const options = {
    series: [
        {
            name: 'Tests',
            data: window.dailyTests.data
        },
        {
            name: '7 Day Moving Average',
            data: window.dailyTests.ma7
        },
        {
            name: '14 Day Moving Average',
            data: window.dailyTests.ma14
        }
    ],
    labels: window.dailyTests.dates,
}

const allOptions = Object.assign(lineChartOptions, options);

if (window.dailyTests) {
    new ApexCharts(document.querySelector("#bigTestsChart"), allOptions).render();
}