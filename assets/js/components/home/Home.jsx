import React, { useState, useEffect } from 'react';
import { withRouter } from "react-router-dom";
import IndianaCountyMap from "./IndianaCountyMap";

class Home extends React.Component {
    render() {
        return (
            <div>
                <IndianaCountyMap />
            </div>
        );
    }
}

export default withRouter(Home);