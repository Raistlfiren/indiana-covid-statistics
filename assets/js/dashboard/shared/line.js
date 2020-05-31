export const lineChartOptions = {
    chart: {
        type: "line",
        height: 350
    },
    colors: ["#727cf5", "#f77eb9", "#7ee5e5"],
    stroke: {
        width: 2,
        curve: 'smooth'
    },
    dataLabels: {
        enabled: false
    },
    xaxis: {
        type: 'datetime'
    },
    yaxis: {
        labels: {
            formatter: function(val) {
                return Math.floor(val)
            }
        }
    },
    legend: {
        show: true,
        position: "top",
        horizontalAlign: 'left',
        containerMargin: {
            top: 30
        }
    },
};