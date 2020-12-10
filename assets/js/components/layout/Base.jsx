import React from 'react';
import Navigation from "./Navigation";
import {Route, Switch} from "react-router-dom";
import Dashboard from "../county/Dashboard";
import Home from "../home/Home";
import dayjs from 'dayjs';

class Base extends React.Component {
    render() {
        return (
            <div className="main-wrapper">
                <div className="horizontal-menu">
                    <Navigation />
                </div>
                <div className="page-wrapper">
                    <div className="page-content">
                        <Switch>
                            <Route path="/county/:county" component={Dashboard} />
                            <Route path="/" component={Home} />
                        </Switch>
                    </div>
                    <footer className="footer">
                        <p className="text-muted text-center">Copyright © {dayjs().format('YYYY')}. All rights reserved</p>
                    </footer>
                </div>
            </div>
        );
    }
}

export default Base;