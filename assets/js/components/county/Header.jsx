import React from 'react';
import axios from "axios";

class Header extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            counties: [],
            isLoading: true,
            error: null
        };
    }

    componentDidMount() {
        axios.get('http://localhost/api/counties/')
            .then(result => this.setState({
                counties: result.data.counties,
                isLoading: false
            }))
            .catch(error => this.setState({
                error,
                isLoading: false
            }));
    }

    render() {
        const { counties, isLoading, error } = this.state;

        if (isLoading) {
            return <div>Loading</div>
        }

        return (
            <div className="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                <div>
                    <h4 className="mb-3 mb-md-0">YOU are viewing <span className="text-uppercase text-primary">{this.props.county.name}</span> County</h4>
                </div>
                <div className="d-flex align-items-center flex-wrap text-nowrap">
                    <span className="text-gray">Set your preferred county here: &nbsp;</span>
                    <select value={this.props.county.name} onChange={this.props.handleChange} id="countySelector"
                            className="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex">
                        <option>Select a County</option>
                        {counties.map((county, index) => (
                            <option key={index} value={county}>
                                {county}
                            </option>
                        ))}
                    </select>
                </div>
            </div>
        );
    }
}

export default Header;