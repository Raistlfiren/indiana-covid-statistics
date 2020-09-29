import React from 'react';
import NewCasesSparkline from "./NewCasesSparkline";
import axios from "axios";
import {TrendingUp, TrendingDown} from "react-feather";
import NewTestsSparkline from "./NewTestsSparkline";
import NewDeathsSparkline from "./NewDeathsSparkline";
import ICUChart from "./ICUChart";
import VentChart from "./VentChart";
import WeeklyCaseSumChart from "./WeeklyCaseSumChart";
import WeeklyDeathSumChart from "./WeeklyDeathSumChart";
import WeeklyTestSumChart from "./WeeklyTestSumChart";

class WeeklySumCharts extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            chartOptions: {
                chart: {
                    type: "line",
                    height: 350
                },
                colors: ["#727cf5", "#f77eb9", "#7ee5e5"],
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                dataLabels: {
                    enabled: true
                },
                xaxis: {
                    type: 'datetime'
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return Math.floor(val)
                        }
                    }
                }
            }
        }
    }

    render() {
        if (this.props.isLoading) {
            return <div>Loading</div>
        }

        const weeks = this.props.weeklyGroupingSum.map(elem => elem.week)
        const weekNumbers = this.props.weeklyGroupingSum.map(elem => elem.id)

        return (
            <div>
                <WeeklyCaseSumChart
                    weeklyGroupingSum={this.props.weeklyGroupingSum}
                    weeks={weeks}
                    weekNumbers={weekNumbers}
                    chartOptions={this.state.chartOptions}
                    isLoading={this.props.isLoading}
                />
                <WeeklyDeathSumChart
                    weeklyGroupingSum={this.props.weeklyGroupingSum}
                    weeks={weeks}
                    weekNumbers={weekNumbers}
                    chartOptions={this.state.chartOptions}
                    isLoading={this.props.isLoading}
                />
                <WeeklyTestSumChart
                    weeklyGroupingSum={this.props.weeklyGroupingSum}
                    weeks={weeks}
                    weekNumbers={weekNumbers}
                    chartOptions={this.state.chartOptions}
                    isLoading={this.props.isLoading}
                />
            </div>
        );
    }
}

export default WeeklySumCharts;