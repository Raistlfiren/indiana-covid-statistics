import { lineChartOptions } from '../shared/line';
import ApexCharts from "apexcharts";

const options = {
    series: [
        {
            name: 'Cases',
            data: window.dailyCases.today
        },
        {
            name: '7 Day Moving Average',
            data: window.dailyCases.ma7
        },
        {
            name: '14 Day Moving Average',
            data: window.dailyCases.ma14
        }
    ],
    labels: window.dailyCases.dates,
}

const allOptions = Object.assign(lineChartOptions, options);

if (window.dailyCases) {
    new ApexCharts(document.querySelector("#bigCasesChart"), allOptions).render();
}