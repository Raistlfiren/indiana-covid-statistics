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
    annotations: {
        xaxis: [
            {
                x: new Date('23 Mar 2020').getTime(),
                borderColor: '#282f3a',
                label: {
                    style: {
                        color: '#282f3a',
                    },
                    text: 'Stage 1'
                }
            },
            {
                x: new Date('04 May 2020').getTime(),
                borderColor: '#282f3a',
                label: {
                    style: {
                        color: '#282f3a',
                    },
                    text: 'Stage 2'
                }
            },
            {
                x: new Date('24 May 2020').getTime(),
                borderColor: '#282f3a',
                label: {
                    style: {
                        color: '#282f3a',
                    },
                    text: 'Stage 3'
                }
            },
            {
                x: new Date('14 Jun 2020').getTime(),
                borderColor: '#282f3a',
                label: {
                    style: {
                        color: '#282f3a',
                    },
                    text: 'Stage 4'
                }
            },
            {
                x: new Date('04 July 2020').getTime(),
                borderColor: '#282f3a',
                label: {
                    style: {
                        color: '#282f3a',
                    },
                    text: 'Stage 5'
                }
            }
        ]
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