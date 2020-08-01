import React, {Component} from 'react';
import {Switch, Redirect, Link} from 'react-router-dom';

class Home extends Component {
    render() {
        return (
           <div>
               <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                   <Link className={"navbar-brand"} to={"/"}> Symfony React Project </Link>
                   <div className="collapse navbar-collapse" id="navbarText">
                       <ul className="navbar-nav mr-auto">
                           <li className="nav-item">
                               <Link className={"nav-link"} to={"/szczecin"}> Posts </Link>
                           </li>

                           <li className="nav-item">
                               <Link className={"nav-link"} to={"/about"}> Users </Link>
                           </li>
                       </ul>
                   </div>
               </nav>
               <Switch>
                   <Redirect exact from="/" to="/" />
               </Switch>
           </div>
        )
    }
}

export default Home;
