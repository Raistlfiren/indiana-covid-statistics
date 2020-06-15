import dt from 'datatables.net-bs4';
import 'datatables.net-bs4/css/dataTables.bootstrap4.css';
import $ from 'jquery';
import ApexCharts from "apexcharts";

$('#countyTable').DataTable({
    "paging": false,
    "rowCallback": function(row, data, index) {
        let percentage = parseInt(data[3]);
        if (percentage > 0) {
            $('th', row).addClass('table-danger');
            $('.percentage', row).addClass('text-danger');
        } else if (percentage < 0) {
            $('th', row).addClass('table-success');
            $('.percentage', row).addClass('text-success');
        } else {
            $('th', row).addClass('table-warning');
            $('.percentage', row).addClass('text-warning');
        }
    }
});

const sparklineOptions = {
    chart: {
        type: "bar",
        width: 400,
        height: 50,
        sparkline: {
            enabled: true
        }
    },
    dataLabels: {
        enabled: true,
        style: {
            colors: ['#000000']
        },
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
