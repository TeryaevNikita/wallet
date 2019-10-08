import React, {Component} from 'react';
import {BrowserRouter as Router, Route, Switch, Link} from "react-router-dom";

import Registration from './Auth/Registration'
import Login from './Auth/Login'
import Logout from './Auth/Logout'
import Dashboard from './Dashboard'
import Rate from './Rate'
import Withdraw from './Withdraw'
import Statistics from './Statistics'
import Transfer from './Transfer'
import Navbar from './Navbar'


export default class App extends Component {

    constructor(props) {
        super(props);
        const AccessToken = localStorage.getItem('auth.access_token');

        this.state = {
            IsAuth: AccessToken !== null,
        };
    }

    render() {
        return (
            <Router>
                <div className="container">
                    <Navbar isAuth={this.state.IsAuth}/>

                    <div className="row align-items-center">
                        <div className="col-md-12">
                            <Switch>
                                <Route path="/login" component={Login}/>
                                <Route path="/registration" component={Registration}/>
                                <Route path="/rate" component={Rate}/>
                                <Route path="/statistics" component={Statistics}/>
                                <Route path="/dashboard" component={Dashboard}/>
                                <Route path="/withdraw" component={Withdraw}/>
                                <Route path="/transfer" component={Transfer}/>
                                <Route path="/logout" component={Logout}/>
                                <Route path="/" component={Login}/>
                            </Switch>
                        </div>
                    </div>
                </div>
            </Router>
        )
    };
}
