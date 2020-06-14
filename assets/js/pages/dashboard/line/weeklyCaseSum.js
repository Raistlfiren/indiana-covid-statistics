import { lineChartOptions } from '../shared/line';
import ApexCharts from "apexcharts";

const options = {
    series: [
        {
            name: 'Sum of Cases',
            data: window.weeklyCaseSum.county
        }
    ],
    dataLabels: {
        enabled: true
    },
    yaxis: {
        title: {
            text: 'Cases'
        }
    },
    xaxis: {
        type: 'string',
        categories: window.weeklyCaseSum.week_number,
        title: {
            text: 'Week Number'
        }
    },
    tooltip: {
        x: {
            formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
                return window.weeklyCaseSum.week[dataPointIndex];
            }
        }
    }
}

const allOptions = Object.assign(lineChartOptions, options);

if (window.weeklyCaseSum) {
    new ApexCharts(document.querySelector("#weeklyCaseChart"), allOptions).render();
}