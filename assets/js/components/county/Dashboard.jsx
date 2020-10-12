import React, { useState, useEffect } from 'react';
import Header from "./Header";
import Disclaimer from "./Disclaimer";
import Overcast from "./Overcast";
import axios from "axios";
import sma from "sma";
import SparklineCharts from "./SparklineCharts";
import dayjs from "dayjs";
import HospitalCharts from "./HospitalCharts";
import weekOfYear from 'dayjs/plugin/weekOfYear';
import WeeklySumCharts from "./WeeklySumCharts";
import en from 'dayjs/locale/en';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc';
import BigCharts from "./BigCharts";
import BarCharts from "./BarCharts";

dayjs.extend(utc)
dayjs.extend(timezone)
dayjs.extend(weekOfYear)

const DEFAULT_COUNTY = {
    id: null,
    name: null,
    district: null,
    covidCount: null,
    covidDeaths: null,
    covidTest: null,
    population: null,
    createdAt: null,
    updatedAt: null
}

class Dashboard extends React.Component {

    constructor(props) {
        super(props);

        this.handleChange = this.handleChange.bind(this);

        this.state = {
            countyName: this.props.match.params.county,
            county: DEFAULT_COUNTY,
            days: [],
            dailyDates: [],
            dailyCovidTests: [],
            dailyCovidCount: [],
            dailyCovidDeaths: [],
            weeklyGroupingSum: [],
            dailyCovidTestsSMA: [],
            dailyCovidCountSMA: [],
            dailyCovidDeathsSMA: [],
            selectedCountyName: null,
            isLoading: true,
            error: null
        };
    }

    componentDidMount() {
        this.getCounty(this.state.countyName)
        this.getDays(this.state.countyName)
        this.updatePageTitle(this.state.countyName)
    }

    getCounty(countyName) {
        axios.get('http://localhost/api/county/' + countyName)
            .then(result => this.setState({
                county: result.data
            }))
            .catch(error => this.setState({
                error
            }));
    }

    getDays(countyName) {
        axios.get('http://localhost/api/county/' + countyName + '/days')
            .then(result => {
                let covidCount = result.data.map(elem => (
                    elem.covidCount
                ));

                let covidDeaths = result.data.map(elem => (
                    elem.covidDeaths
                ));

                let covidTests = result.data.map(elem => (
                    elem.covidTest
                ));

                let dates = result.data.map(elem => (
                    dayjs(elem.date).format('YYYY-MM-DD')
                    // elem.date
                ));

                // let weeklySumOfCovidCases = [];
                // let weeklyDatesOfCovidCases = [];
                // let weeklySumOfCovidDeaths = [];
                // let weeklyDatesFfCovidDeaths = [];
                // result.data.forEach(function (item, index) {
                //     let date = dayjs(item.date);
                //     let yearWeek = date.format('YYYY') + '-' + date.week();
                //
                //     if (!weeklyDatesOfCovidCases[yearWeek]) {
                //         weeklyDatesOfCovidCases.push(yearWeek);
                //     }
                //
                //     if (!weeklySumOfCovidCases[yearWeek]) {
                //         weeklySumOfCovidCases.push({'week': yearWeek, 'sum': item.covidCount});
                //         // weeklySumOfCovidCases[yearWeek] = item.covidCount;
                //     } else {
                //         weeklySumOfCovidCases.push({'week': yearWeek, 'sum': item.covidCount});
                //         // weeklySumOfCovidCases[yearWeek] += item.covidCount;
                //     }
                //
                //     if (!weeklySumOfCovidDeaths[yearWeek]) {
                //         weeklySumOfCovidDeaths[yearWeek] = item.covidDeaths;
                //     } else {
                //         weeklySumOfCovidDeaths[yearWeek] += item.covidDeaths;
                //     }
                // });

                let weeklyGroupingSum = [];
                // dayjs.Ls.en.weekStart = 1;
                result.data.reduce((grouping, item) => {
                    let date = dayjs(item.date).utc(true);

                    // create a composed key: 'year-week'
                    let yearWeek = date.format('YYYY') + '-' + date.week();

                    // console.log(yearWeek, item.covidCount, date.format('YYYY-MM-DD'));
                    // grouping[yearWeek] = [...grouping[yearWeek] || [], item];

                    // add this key as a property to the result object
                    if (!grouping[yearWeek]) {
                        let startWeek = date.startOf('week')
                        let endWeek = date.endOf('week')
                        grouping[yearWeek] = {id: yearWeek, week: startWeek.format('MM/DD/YYYY') + '-' + endWeek.format('MM/DD/YYYY') , covidCount: 0, covidDeaths: 0, covidTests: 0};
                        weeklyGroupingSum.push(grouping[yearWeek])
                    }

                    // push the current date that belongs to the year-week calculated befor
                    // grouping[yearWeek].push(item);

                    grouping[yearWeek].covidCount += item.covidCount;
                    grouping[yearWeek].covidDeaths += item.covidDeaths;
                    grouping[yearWeek].covidTests += item.covidTest;
                    return grouping;

                }, {});

                // console.log(weeklyGroupingSum);
                let totalCount = dates.length

                this.setState({
                    days: result.data,
                    dailyCovidCount: covidCount,
                    dailyCovidDeaths: covidDeaths,
                    dailyCovidTests: covidTests,
                    dailyDates: dates,
                    weeklyGroupingSum: weeklyGroupingSum,
                    dailyCovidCountSMA: this.padArrayLeft(sma(covidCount, 7, this.toFixed), totalCount, 0),
                    dailyCovidTestsSMA: this.padArrayLeft(sma(covidTests, 7, this.toFixed), totalCount, 0),
                    dailyCovidDeathsSMA: this.padArrayLeft(sma(covidDeaths, 7, this.toFixed), totalCount, 0),
                    isLoading: false
                })
            })
            .catch(error => this.setState({
                error,
                isLoading: false
            }));
    }

    toFixed(n) {
        return n.toFixed(0);
    }

    padArrayLeft(arr,len,fill) {
        return Array(len).fill(fill).concat(arr).slice( arr.length)
    }

    percentageChange(x, y)
    {
        if (y <= 0) {
            y = 1;
        }

        return (((x - y)/y) * 100).toFixed(0);
    }

    updatePageTitle(countyName) {
        document.title = countyName + ' County Dashboard'
    }

    handleChange(event) {
        this.getCounty(event.target.value);
        this.getDays(event.target.value);
        this.updatePageTitle(event.target.value);
    }

    render() {
        const { countyName, county, days, dailyDates, dailyCovidTests, dailyCovidCount, dailyCovidDeaths, weeklyGroupingSum, dailyCovidTestsSMA, dailyCovidCountSMA, dailyCovidDeathsSMA, isLoading, error } = this.state;

        if (isLoading) {
            return <div>Loading</div>
        }

        return (
            <div>
                <Header handleChange={this.handleChange}
                        county={county}
                />
                <Disclaimer county={county} />
                <Overcast county={county}
                            days={days}
                          dailyCovidCountSMA={dailyCovidCountSMA}
                          dailyCovidDeathsSMA={dailyCovidDeathsSMA}
                          percentageChange={this.percentageChange}
                          isLoading={isLoading}
                />
                <SparklineCharts dailyDates={dailyDates}
                                dailyCovidCases={dailyCovidCount}
                                 dailyCovidTests={dailyCovidTests}
                                 dailyCovidDeaths={dailyCovidDeaths}
                                 countyName={countyName}
                                 county={county}
                                 percentageChange={this.percentageChange}
                />
                <HospitalCharts
                    countyName={countyName}
                />
                <WeeklySumCharts
                    isLoading={isLoading}
                    weeklyGroupingSum={weeklyGroupingSum}
                />
                <BigCharts
                    isLoading={isLoading}
                    dailyDates={dailyDates}
                    dailyCovidCount={dailyCovidCount}
                    dailyCovidTests={dailyCovidTests}
                    dailyCovidDeaths={dailyCovidDeaths}
                    dailyCovidCountSMA={dailyCovidCountSMA}
                    dailyCovidTestsSMA={dailyCovidTestsSMA}
                    dailyCovidDeathsSMA={dailyCovidDeathsSMA}
                    padArrayLeft={this.padArrayLeft}
                />
                <BarCharts
                    countyName={countyName}
                />
            </div>
        );
    }
}

export default Dashboard;