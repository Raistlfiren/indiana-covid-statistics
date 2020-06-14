import { lineChartOptions } from '../shared/line';
import ApexCharts from "apexcharts";

const options = {
    series: [
        {
            name: 'Sum of Deaths',
            data: window.weeklyDeathSum.county
        }
    ],
    dataLabels: {
        enabled: true
    },
    yaxis: {
        title: {
            text: 'Deaths'
        }
    },
    xaxis: {
        type: 'string',
        categories: window.weeklyDeathSum.week_number,
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

if (window.weeklyDeathSum) {
    new ApexCharts(document.querySelector("#weeklyDeathChart"), allOptions).render();
}