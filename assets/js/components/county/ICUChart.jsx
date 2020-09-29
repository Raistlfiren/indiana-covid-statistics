import React from 'react';
import Chart from "react-apexcharts";
import {HelpCircle} from "react-feather";

class ICUChart extends React.Component {
    render() {
        const stats = this.props.statistics

        let series = [
            stats.bedsIcuOccupiedBedsCovid,
            stats.bedOccupiedIcuNonCovid,
            stats.bedsAvailableIcuBedsTtotal
        ]

        let options = {
            ...this.props.chartOptions,
            title: {
                text: [stats.bedsIcuTotal, 'Total'],
                align: 'center',
                floating: true,
                offsetY: 90,
                style: {
                    fontSize: '20px'
                }
            },
            labels: ['Covid Use', 'Non-Covid Use', 'Available']
        }

        if (this.props.isLoading) {
            return <div>Loading</div>
        }

        return (
            <div id="icuBeds" className="col-sm-12 col-md-12 col-lg-6 grid-margin stretch-card">
                <div className="card">
                    <div className="card-body">
                        <div className="d-flex justify-content-between align-items-baseline">
                            <h6 className="card-title mb-0">ICU Beds</h6>
                            <div className="dropdown mb-2">
                                <button className="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <HelpCircle className="icon-lg text-muted pb-3px" />
                                </button>

                            </div>
                        </div>
                        <div className="row">
                            <div className="col-2">
                                <div className="card-inner-card mt-4 text-center">
                                    <p>
                                        <span className="h4 font-weight-bold" style={{color: "#f77eb9"}}>{this.props.statistics.bedsIcuOccupiedBedsCovid}</span>
                                        <br />
                                        <span className="black"> Covid Use</span>
                                    </p>
                                </div>
                                <div className="card-inner-card mt-3 text-center">
                                    <p>
                                        <span className="h4 font-weight-bold" style={{color: "#7ee5e5"}}>{this.props.statistics.bedOccupiedIcuNonCovid}</span>
                                        <br />
                                        <span className="black"> Non-Covid Use</span>
                                    </p>
                                </div>
                                <div className="card-inner-card mt-3 text-center">
                                    <p>
                                        <span className="h4 font-weight-bold" style={{color: "#4d8af0"}}>{this.props.statistics.bedsAvailableIcuBedsTtotal}</span>
                                        <br />
                                        <span className="black"> Available</span>
                                    </p>
                                </div>
                            </div>
                            <div className="col-10">
                                <Chart
                                    options={options}
                                    series={series}
                                    type="donut"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default ICUChart;