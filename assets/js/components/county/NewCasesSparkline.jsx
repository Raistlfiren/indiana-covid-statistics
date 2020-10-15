import React from 'react';
import Chart from "react-apexcharts";
import {HelpCircle} from "react-feather";

class NewCasesSparkline extends React.Component {
    render() {
        const series = {
            series: [{
                data: this.props.dailyCovidCases.slice(-7)
            }]
        }

        let options = {
            ...this.props.chartOptions,
            xaxis: {
                categories: this.props.dailyDates.slice(-7)
            }
        }

        if (this.props.isLoading) {
            return <div>Loading</div>
        }

        return (
            <div id="quickCases" className="col-sm-12 col-md-12 col-lg-4 grid-margin stretch-card">
                <div className="card">
                    <div className="card-body">
                        <div className="d-flex justify-content-between align-items-baseline">
                            <h6 className="card-title mb-0">New Cases</h6>
                            <div className="dropdown mb-2">
                                <button className="btn p-0" type="button" data-toggle="popover"
                                        data-content="Case data refers to a test that returns positive. <br /> <br /> <a class='btn btn-primary' role='button' href=#'>Read more</a>.">
                                    <HelpCircle className="icon-lg text-muted pb-3px" />
                                </button>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-6 col-md-12 col-xl-5">
                                {this.props.currentActivityData(this.props.today(this.props.dailyCovidCases), this.props.previousDay(this.props.dailyCovidCases), 'cases', this.props.county.covidCount)}
                            </div>
                            <div className="col-6 col-md-12 col-xl-7">
                                <h6 className="text-center">Past 7 Days</h6>
                                <div id="caseChart" className="mt-md-3 mt-xl-0">
                                    <Chart
                                        options={options}
                                        series={series.series}
                                    />
                                </div>
                                <p className="text-left">
                                    Past
                                    <span className="float-right">Now</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default NewCasesSparkline;