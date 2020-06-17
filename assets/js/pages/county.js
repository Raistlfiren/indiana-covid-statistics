import dt from 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.css';
import $ from 'jquery';
import ApexCharts from "apexcharts";

$('#countyTable').DataTable({
    "paging": false,
    "dom": '<"toolbar">frtip',
    "rowCallback": function(row, data, index) {
        let percentage = parseInt(data[5]);
        if (percentage > 0) {
            $('th', row).addClass('table-danger');
            $('.percentage', row).addClass('text-danger');
        } else if (percentage < 0) {
            $('th', row).addClass('table-success');
            $('.percentage', row).addClass('text-success');
        }
        // else {
        //     $('th', row).addClass('table-warning');
        //     $('.percentage', row).addClass('text-warning');
        // }
    }
});

$("div.toolbar").html('<h2>County Overview</h2>');

const sparklineOptions = {
    chart: {
        type: "bar",
        // width: 300,
        height: 25,
        sparkline: {
            enabled: true
        }
    },
    dataLabels: {
        enabled: true,
        style: {
            fontSize: '12px',
            // colors: ['#ffffff']
            // colors: [function({ value, seriesIndex, w }) {
            //     console.log(value, seriesIndex, w);
            //     return '#ffffff';
            // }],
        },
        background: {
            enabled: true,
            foreColor: '#000',
            borderColor: '#000',
            opacity: 0.5
        },
        formatter: function(val, opt) {
            if (opt.dataPointIndex == 0 || opt.dataPointIndex == 13) {
                return val;
            }
        },
        // offsetX: function(val, opt) {
        //     if (opt.dataPointIndex == 0) {
        //         return -20;
        //     } else if (opt.dataPointIndex == 12) {
        //         return 20;
        //     }
        // },
    },
    // yaxis: {
    //     labels: {
    //         formatter: function(val) {
    //             return Math.floor(val)
    //         }
    //     }
    // },
    stroke: {
        width: 2,
        curve: "smooth"
    },
    colors: ["#727cf5"]
}

$(".chart").each(function(){
    let averages = $(this).data('averages');
    let dates = $(this).data('dates');
    let options = {
        series: [{
            name: 'Case(s)',
            data: averages.reverse()
        }],
        xaxis: {
            categories: dates.reverse()
        },
    };

    new ApexCharts($(this)[0], Object.assign(sparklineOptions, options)).render();
});
