import React from 'react';
import Chart from "react-apexcharts";
import {HelpCircle} from "react-feather";
import sma from "sma";

class BigDeathChart extends React.Component {
    render() {

        let totalCount = this.props.dailyDates.length

        let series = {
            series: [
                {
                    name: 'Deaths',
                    data: this.props.dailyCovidDeaths
                },
                {
                    name: '7 Day Moving Average',
                    data: this.props.dailyCovidDeathsSMA
                },
                {
                    name: '14 Day Moving Average',
                    data: this.props.padArrayLeft(sma(this.props.dailyCovidDeaths, 14, this.toFixed), totalCount, 0),
                }
            ],
        }

        let options = {
            ...this.props.chartOptions,
            labels: this.props.dailyDates,
        }

        if (this.props.isLoading) {
            return <div>Loading</div>
        }

        return (
            <div className="row">
                <div id="bigCases" className="col-12 col-xl-12 grid-margin stretch-card">
                    <div className="card overflow-hidden">
                        <div className="card-body">
                            <div className="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                                <h6 className="card-title mb-0">Deaths</h6>
                                <button className="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <HelpCircle className="icon-lg text-muted pb-3px" />
                                </button>
                            </div>
                            <div className="row align-items-start mb-2">
                                <div className="col-md-12">
                                    <p className="text-muted tx-13 mb-3 mb-md-0">
                                        Deaths are the total positive cases recorded on a day to day basis from COVID-19.
                                        The 7-day moving average is the average of the past 7-days of deaths recorded
                                        with COVID-19.
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

export default BigDeathChart;