import React from 'react';
import {Home, Eye, BookOpen, Code, Menu} from "react-feather";

class Navigation extends React.Component {
    render() {
        return (
            <nav className="navbar top-navbar">
                <div className="container">
                    <div className="navbar-content">
                        <a href="/home" className="navbar-brand">
                            IN COVID<span>19</span>
                        </a>
                        <ul className="navbar-nav">
                            <li className="nav-item">
                                <a className="nav-link" href="/home">
                                    <Home className="link-icon" />&nbsp;
                                    <span className="menu-title">Home</span>
                                </a>
                            </li>
                            <li className="nav-item">
                                <a className="nav-link" href="/county">
                                    <Eye className="link-icon" />&nbsp;
                                    <span className="menu-title">Overview</span>
                                </a>
                            </li>
                            <li className="nav-item">
                                <a className="nav-link" href="/blog">
                                    <BookOpen className="link-icon" />&nbsp;
                                    <span className="menu-title">Blog</span>
                                </a>
                            </li>
                            <li className="nav-item">
                                <a className="nav-link" href="https://github.com/Raistlfiren/indiana-covid-statistics" target="_blank">
                                    <Code className="link-icon" />&nbsp;
                                    <span className="menu-title">Code</span>
                                </a>
                            </li>
                        </ul>
                        <button className="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                            <Menu />
                        </button>
                    </div>
                </div>
            </nav>
        );
    }
}

export default Navigation;