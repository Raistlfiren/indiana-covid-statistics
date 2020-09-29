import React from 'react';
import BigCaseChart from "./BigCaseChart";
import WeeklyCaseSumChart from "./WeeklyCaseSumChart";
import BigDeathChart from "./BigDeathChart";
import BigTestChart from "./BigTestChart";

class BigCharts extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            chartOptions : {
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
                    enabled: false
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
                },
                annotations: {
                    xaxis: [
                        {
                            x: new Date('23 Mar 2020').getTime(),
                            borderColor: '#282f3a',
                            label: {
                                style: {
                                    color: '#282f3a',
                                },
                                text: 'Stage 1'
                            }
                        },
                        {
                            x: new Date('04 May 2020').getTime(),
                            borderColor: '#282f3a',
                            label: {
                                style: {
                                    color: '#282f3a',
                                },
                                text: 'Stage 2'
                            }
                        },
                        {
                            x: new Date('24 May 2020').getTime(),
                            borderColor: '#282f3a',
                            label: {
                                style: {
                                    color: '#282f3a',
                                },
                                text: 'Stage 3'
                            }
                        },
                        {
                            x: new Date('14 Jun 2020').getTime(),
                            borderColor: '#282f3a',
                            label: {
                                style: {
                                    color: '#282f3a',
                                },
                                text: 'Stage 4'
                            }
                        },
                        {
                            x: new Date('04 July 2020').getTime(),
                            borderColor: '#282f3a',
                            label: {
                                style: {
                                    color: '#282f3a',
                                },
                                text: 'Stage 5'
                            }
                        }
                    ]
                },
                legend: {
                    show: true,
                    position: "top",
                    horizontalAlign: 'left',
                    containerMargin: {
                        top: 30
                    }
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
                <BigCaseChart
                    chartOptions={this.state.chartOptions}
                    isLoading={this.props.isLoading}
                    dailyDates={this.props.dailyDates}
                    dailyCovidCount={this.props.dailyCovidCount}
                    dailyCovidCountSMA={this.props.dailyCovidCountSMA}
                    padArrayLeft={this.props.padArrayLeft}
                />
                <BigDeathChart
                    chartOptions={this.state.chartOptions}
                    isLoading={this.props.isLoading}
                    dailyDates={this.props.dailyDates}
                    dailyCovidDeaths={this.props.dailyCovidDeaths}
                    dailyCovidDeathsSMA={this.props.dailyCovidDeathsSMA}
                    padArrayLeft={this.props.padArrayLeft}
                />
                <BigTestChart
                    chartOptions={this.state.chartOptions}
                    isLoading={this.props.isLoading}
                    dailyDates={this.props.dailyDates}
                    dailyCovidTests={this.props.dailyCovidTests}
                    dailyCovidTestsSMA={this.props.dailyCovidTestsSMA}
                    padArrayLeft={this.props.padArrayLeft}
                />
            </div>
        );
    }
}

export default BigCharts;