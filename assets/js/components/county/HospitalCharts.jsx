import React from 'react';
import NewCasesSparkline from "./NewCasesSparkline";
import axios from "axios";
import {TrendingUp, TrendingDown} from "react-feather";
import NewTestsSparkline from "./NewTestsSparkline";
import NewDeathsSparkline from "./NewDeathsSparkline";
import ICUChart from "./ICUChart";
import VentChart from "./VentChart";

const DEFAULT_STATISTICS = {
    id: null,
    bedsIcuOccupiedBedsCovid: null,
    bedOccupiedIcuNonCovid: null,
    bedsAvailableIcuBedsTtotal: null,
    bedsIcuTotal: null,
    ventsAllInUseCovid: null,
    ventsNonCovidPtsOnVents: null,
    ventsAllAvailableVentsNotInUse: null,
    ventsTotal: null,
}

class HospitalCharts extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            statistics: DEFAULT_STATISTICS,
            isLoading: true,
            chartOptions: {
                chart: {
                    height: 300,
                    type: "donut"
                },
                stroke: {
                    colors: ['rgba(0,0,0,0)']
                },
                colors: ["#f77eb9", "#7ee5e5","#4d8af0","#fbbc06"],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                },
                dataLabels: {
                    enabled: true
                },
                labels: ['Covid Use', 'Non-Covid Use', 'Available']
            }
        }
    }

    componentDidMount() {
        this.getHospitalStatistics(this.props.countyName);
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if (prevProps.countyName !== this.props.countyName) {
            this.getHospitalStatistics(this.props.countyName);
        }
    }

    getHospitalStatistics(countyName) {
        axios.get('http://localhost/api/county/' + countyName + '/hospital')
            .then(result => this.setState({
                statistics: result.data,
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

        return (
            <div className="row">
                <div className="col-12 col-xl-12 stretch-card">
                    <div className="row flex-grow">
                        <ICUChart
                            statistics={this.state.statistics}
                            chartOptions={this.state.chartOptions}
                        />
                        <VentChart
                            statistics={this.state.statistics}
                            chartOptions={this.state.chartOptions}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

export default HospitalCharts;