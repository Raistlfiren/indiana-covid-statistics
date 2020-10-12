import React from 'react';
import Chart from "react-apexcharts";
import {HelpCircle} from "react-feather";
import sma from "sma";
import axios from "axios";

class EthnicityChart extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            ethnicityDetails: [],
            isLoading: true,
        }
    }

    componentDidMount() {
        this.getethnicityDetails(this.props.countyName);
    }

    getethnicityDetails(countyName) {
        axios.get('http://localhost/api/county/' + countyName + '/ethnicity')
            .then(result => this.setState({
                ethnicityDetails: result.data,
                isLoading: false
            }))
            .catch(error => this.setState({
                error,
                isLoading: false
            }));
    }

    render() {
        if (this.state.isLoading) {
            return <div>Loading</div>
        }

        let series = {
            series: [{
                name: 'Cases',
                data: this.state.ethnicityDetails.map(elem => elem.covidCount)
            }, {
                name: 'Deaths',
                data: this.state.ethnicityDetails.map(elem => elem.covidDeaths)
            }]
        }

        let options = {
            ...this.props.chartOptions,
            xaxis: {
                categories: this.state.ethnicityDetails.map(elem => elem.ethnicity)
            },
        }

        return (
            <div className="row">
                <div id="bigCases" className="col-12 col-xl-12 grid-margin stretch-card">
                    <div className="card overflow-hidden">
                        <div className="card-body">
                            <div className="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                                <h6 className="card-title mb-0">Ethnicity</h6>
                                <button className="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <HelpCircle className="icon-lg text-muted pb-3px" />
                                </button>
                            </div>
                            <div className="row align-items-start mb-2">
                                <div className="col-md-12">
                                    <p className="text-muted tx-13 mb-3 mb-md-0">
                                        Cases are the total confirmed COVID-19 cases.
                                        Deaths are all COVID-19 related deaths.
                                    </p>
                                </div>
                            </div>
                            <div className="flot-wrapper">
                                <Chart
                                    type="bar"
                                    stacked="true"
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

export default EthnicityChart;