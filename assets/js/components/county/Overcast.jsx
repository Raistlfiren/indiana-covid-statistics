import React from 'react';
import axios from "axios";
import {AlertCircle} from "react-feather";

class Header extends React.Component {
    textChange(x, y)
    {
        let percentage = this.props.percentageChange(x, y)

        if (percentage == 0) {
            return <span className="font-weight-bold text-info">staying the same</span>
        } else if (percentage > 0) {
            return <span className="font-weight-bold text-danger">increasing</span>
        } else {
            return <span className="font-weight-bold text-success">decreasing</span>
        }
    }

    getLastItemInArray(item)
    {
        return item.slice(-1)[0]
    }

    getSecondLastItemInArray(item)
    {
        return item.slice(-2)[0]
    }

    getHerdImmunityPercentage()
    {
        return ((this.props.county.covidCount/this.props.county.population)*100).toFixed(2)
    }

    render() {
        const dailyCovidCountSMA = this.props.dailyCovidCountSMA;
        const dailyCovidDeathsSMA = this.props.dailyCovidDeathsSMA;
        const county = this.props.county;

        if (this.props.isLoading) {
            return <div>Loading</div>
        }

        let mostRecentCasesSMA = this.getLastItemInArray(dailyCovidCountSMA)
        let mostRecentDeathsSMA = this.getLastItemInArray(dailyCovidDeathsSMA)

        return (
            <div className="alert alert-icon-dark d-flex" role="alert">
                <AlertCircle className="d-none d-sm-block" />
                <p>
                    Over the past 7 days { county.name } has had an average of <strong>{mostRecentCasesSMA}</strong> cases
                    and <strong>{mostRecentDeathsSMA}</strong> deaths per day. Based upon yesterday's data, { county.name } counties
                    cases are {this.textChange(mostRecentCasesSMA, this.getSecondLastItemInArray(dailyCovidCountSMA))} and
                    deaths are {this.textChange(mostRecentDeathsSMA, this.getSecondLastItemInArray(dailyCovidDeathsSMA))}.
                    The estimated population of {county.name} county is {county.population.toLocaleString()}. Our current "herd immunity" outlook is
                     <strong>{this.getHerdImmunityPercentage()}%</strong>.
                </p>
            </div>
        );
    }
}

export default Header;