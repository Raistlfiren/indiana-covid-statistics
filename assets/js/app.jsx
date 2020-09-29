import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from 'react-router-dom';
import '../css/app.scss';
import Base from './components/layout/Base';

ReactDOM.render(<Router><Base /></Router>, document.getElementById('root'));