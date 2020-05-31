export const sparklineOptions = {
    chart: {
        type: "line",
        height: 60,
        sparkline: {
            enabled: true
        }
    },
    yaxis: {
        labels: {
            formatter: function(val) {
                return Math.floor(val)
            }
        }
    },
    stroke: {
        width: 2,
        curve: "smooth"
    },
    markers: {
        size: 0
    },
    colors: ["#727cf5"],
    tooltip: {
        fixed: {
            enabled: !1
        },
        x: {
            show: true
        },
        y: {
            title: {
                formatter: function(e) {
                    return "Case(s)"
                }
            }
        },
        marker: {
            show: !1
        }
    }
}