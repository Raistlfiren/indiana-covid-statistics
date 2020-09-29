import React from 'react';
import BigCaseChart from "./BigCaseChart";
import WeeklyCaseSumChart from "./WeeklyCaseSumChart";
import BigDeathChart from "./BigDeathChart";
import BigTestChart from "./BigTestChart";
import SexChart from "./SexChart";
import AgeChart from "./AgeChart";
import RaceChart from "./RaceChart";
import EthnicityChart from "./EthnicityChart";

class BarCharts extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            chartOptions : {
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    },
                },
                colors: ["#727cf5", "#7ee5e5", "#f77eb9"],
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            }
        }
    }

    render() {
        if (this.props.isLoading) {
            return <div>Loading</div>
        }

        return (
            <div>
                <SexChart
                    countyName={this.props.countyName}
                    chartOptions={this.state.chartOptions}
                />
                <AgeChart
                    countyName={this.props.countyName}
                    chartOptions={this.state.chartOptions}
                />
                <RaceChart
                    countyName={this.props.countyName}
                    chartOptions={this.state.chartOptions}
                />
                <EthnicityChart
                    countyName={this.props.countyName}
                    chartOptions={this.state.chartOptions}
                />
            </div>
        );
    }
}

export default BarCharts;