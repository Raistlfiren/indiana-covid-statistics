import { lineChartOptions } from '../shared/line';
import ApexCharts from "apexcharts";

const options = {
    series: [
        {
            name: 'Deaths',
            data: window.dailyDeaths.data
        },
        {
            name: '7 Day Moving Average',
            data: window.dailyDeaths.ma7
        },
        {
            name: '14 Day Moving Average',
            data: window.dailyDeaths.ma14
        }
    ],
    labels: window.dailyDeaths.dates,
}

const allOptions = Object.assign(lineChartOptions, options);

if (window.dailyDeaths) {
    new ApexCharts(document.querySelector("#bigDeathsChart"), allOptions).render();
}