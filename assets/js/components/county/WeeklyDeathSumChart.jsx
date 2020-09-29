import React from 'react';
import Chart from "react-apexcharts";
import {HelpCircle} from "react-feather";

class WeeklyDeathSumChart extends React.Component {
    render() {

        const weeks = this.props.weeks;

        let series = {
            series: [{
                name: 'Sum of Deaths',
                data: this.props.weeklyGroupingSum.map(elem => elem.covidDeaths)
            }]
        }

        let options = {
            ...this.props.chartOptions,
            yaxis: {
                title: {
                    text: 'Deaths'
                }
            },
            xaxis: {
                type: 'string',
                categories: this.props.weekNumbers,
                title: {
                    text: 'Week Number'
                }
            },
            tooltip: {
                x: {
                    formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
                        return weeks[dataPointIndex]
                    }
                }
            }
        }

        if (this.props.isLoading) {
            return <div>Loading</div>
        }

        return (
            <div className="row">
                <div id="weeklyCaseSum" className="col-12 col-xl-12 grid-margin stretch-card">
                    <div className="card overflow-hidden">
                        <div className="card-body">
                            <div className="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                                <h6 className="card-title mb-0">Weekly Death SUM</h6>
                                <button className="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <HelpCircle className="icon-lg text-muted pb-3px" />
                                </button>
                            </div>
                            <div className="row align-items-start mb-2">
                                <div className="col-md-12">
                                    <p className="text-muted tx-13 mb-3 mb-md-0">
                                        This is an overlook of the weekly death SUM over time.
                                    </p>
                                </div>
                            </div>
                            <div className="flot-wrapper">
                                <Chart
                                    series={series.series}
                                    options={options}
                                    height="350"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default WeeklyDeathSumChart;