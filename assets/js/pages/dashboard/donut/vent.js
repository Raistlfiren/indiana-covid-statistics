import { donutOptions } from '../shared/donut';
import ApexCharts from "apexcharts";

const options = {
    title: {
        text: [window.dailyVents.all, 'Total'],
        align: 'center',
        floating: true,
        offsetY: 90,
        style: {
            fontSize: '20px'
        }
    },
    series: window.dailyVents.data,
    labels: window.dailyVents.labels
};

const allOptions = Object.assign(donutOptions, options);

if (window.dailyBeds) {
    new ApexCharts(document.querySelector("#ventsChart"), allOptions).render();
}