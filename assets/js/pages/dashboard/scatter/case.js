import ApexCharts from "apexcharts";

const options = {
    chart: {
        height: 500,
        type: 'line'
    },
    colors: ["#f77eb9","#7ee5e5","#4d8af0","#7987a1"],
    // grid: {
    //     borderColor: "rgba(77, 138, 240, .1)"
    // },
    // stroke: {
    //     colors: ['rgba(0,0,0,0)']
    // },
    legend: {
        position: 'top',
        horizontalAlign: 'left'
    },
    xaxis: {
        type: 'datetime',
    },
    yaxis: {
        labels: {
            formatter: function(val) {
                return Math.floor(val)
            }
        }
    },
    fill: {
        type:'solid',
    },
    markers: {
        size: [6]
    },
    series: [
        {
            name: 'Min',
            type: 'scatter',
            data: window.aggregatedValidates.min
        },
        {
            name: 'Max',
            type: 'scatter',
            data: window.aggregatedValidates.max
        },
        {
            name: 'Average',
            type: 'scatter',
            data: window.aggregatedValidates.avg
        },
        {
            name: 'Current',
            type: 'line',
            data: window.aggregatedValidates.currents
        }
    ],
    labels: window.aggregatedValidates.date,
}

if (window.aggregatedValidates) {
    new ApexCharts(document.querySelector("#dateCountChart"), options).render();
}