import { donutOptions } from '../shared/donut';
import ApexCharts from "apexcharts";

const options = {
    title: {
        text: [window.dailyBeds.all, 'Total'],
        align: 'center',
        floating: true,
        offsetY: 90,
        style: {
            fontSize: '20px'
        }
    },
    series: window.dailyBeds.data,
    labels: window.dailyBeds.labels
};

const allOptions = Object.assign(donutOptions, options);

if (window.dailyBeds) {
    new ApexCharts(document.querySelector("#bedsChart"), allOptions).render();
}