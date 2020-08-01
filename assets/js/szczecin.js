import '../css/szczecin.scss';
import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from 'react-router-dom';
import Home from './components/Home';

console.log('Witaj Szczecinie :)');

ReactDOM.render(<Router><Home /></Router>, document.getElementById('root'));
