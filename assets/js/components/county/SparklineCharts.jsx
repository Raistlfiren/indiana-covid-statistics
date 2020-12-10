import React from 'react';
import NewCasesSparkline from "./NewCasesSparkline";
import axios from "axios";
import {TrendingUp, TrendingDown} from "react-feather";
import NewTestsSparkline from "./NewTestsSparkline";
import NewDeathsSparkline from "./NewDeathsSparkline";

class SparklineCharts extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            chartOptions: {
                chart: {
                    type: "line",
                    height: 60,
                    sparkline: {
                        enabled: true
                    }
                },
                stroke: {
                    width: 2,
                    curve: "smooth"
                },
                markers: {
                    size: 0
                },
                colors: ["#727cf5"],
                tooltip: {
                    fixed: {
                        enabled: !1
                    },
                    x: {
                        show: true
                    },
                    y: {
                        title: {
                            formatter: function(e) {
                                return "Case(s)"
                            }
                        }
                    },
                    marker: {
                        show: !1
                    }
                }
            }
        }
    }

    chartChangeIndicator(x, y)
    {
        let percentage = this.percentageChange(x, y)

        if (percentage == 0) {
            return (
                <p className="text-info">
                    <span>steady</span>
                    -
                </p>
            )
        } else if (percentage > 0) {
            return (
                <p className="text-danger">
                    <span>+{percentage}%</span>
                    <TrendingUp className="icon-sm mb-1" />
                </p>
            )
        }

        return (
            <p className="text-success">
                <span>{percentage}%</span>
                <TrendingDown className="icon-sm mb-1" />
            </p>
        )
    }

    currentActivityData(currentData, oldDate, term, total)
    {
        return (
            <div>
                <h3 className="mb-2">{currentData}</h3>
                {this.chartChangeIndicator(currentData, oldDate)}
                <p className="d-block">{oldDate} yesterday</p>
                <p>{new Intl.NumberFormat().format(total)} total</p>
            </div>
        )
    }

    getPreviousDay(data)
    {
        return data.slice(-2)[0]
    }

    getToday(data)
    {
        return data.slice(-1)[0]
    }

    render() {
        if (this.props.isLoading) {
            return <div>Loading</div>
        }

        return (
            <div className="row">
                <div className="col-12 col-xl-12 stretch-card">
                    <div className="row flex-grow">
                        <NewCasesSparkline
                            dailyDates={this.props.dailyDates}
                            dailyCovidCases={this.props.dailyCovidCases}
                            chartOptions={this.state.chartOptions}
                            today={this.getToday}
                            currentActivityData={this.currentActivityData}
                            previousDay={this.getPreviousDay}
                            county={this.props.county}
                            chartChangeIndicator={this.chartChangeIndicator}
                            percentageChange={this.props.percentageChange}
                        />
                        <NewTestsSparkline
                            dailyDates={this.props.dailyDates}
                            dailyCovidTests={this.props.dailyCovidTests}
                            chartOptions={this.state.chartOptions}
                            today={this.getToday}
                            currentActivityData={this.currentActivityData}
                            previousDay={this.getPreviousDay}
                            county={this.props.county}
                            chartChangeIndicator={this.chartChangeIndicator}
                            percentageChange={this.props.percentageChange}
                        />
                        <NewDeathsSparkline
                            dailyDates={this.props.dailyDates}
                            dailyCovidDeaths={this.props.dailyCovidDeaths}
                            chartOptions={this.state.chartOptions}
                            today={this.getToday}
                            currentActivityData={this.currentActivityData}
                            previousDay={this.getPreviousDay}
                            county={this.props.county}
                            chartChangeIndicator={this.chartChangeIndicator}
                            percentageChange={this.props.percentageChange}
                        />
                    </div>
                </div>
            </div>
        );
    }
}

export default SparklineCharts;