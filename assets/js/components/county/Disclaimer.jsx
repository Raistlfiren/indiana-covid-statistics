import React from 'react';
import dayjs from 'dayjs';
import {AlertCircle} from "react-feather";

class Header extends React.Component {
    render() {
        return (
            <div className="alert alert-icon-primary d-flex" role="alert">
                <AlertCircle className="d-none d-sm-block" />
                <p>This data is obtained from <a
                    href="https://coronavirus.in.gov"><strong>https://coronavirus.in.gov</strong></a>,
                    and was updated on <strong>{dayjs(this.props.county.updatedAt).format('MMMM D, YYYY [at] h:mm A')} CST</strong>. <strong>All
                        data is provisional and reflects only what is reported to
                        the Indiana State Health Department.</strong> If you see an issue, then please report a problem
                    to <strong>webmaster [at] incovidstats [dot] net</strong>.</p>
            </div>

        );
    }
}

export default Header;