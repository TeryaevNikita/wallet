import React, {Component} from 'react';
import {Link} from "react-router-dom";

export default class Navbar extends Component {
    constructor(props) {
        super(props);

        const UserName = localStorage.getItem('user.name');
        const Amount = localStorage.getItem('user.amount');
        const UserCurrency = localStorage.getItem('user.currency');

        this.state = {
            UserName: UserName,
            Amount: Amount,
            UserCurrency: UserCurrency
        };
    }

    render() {
        return (
            <div className="row">
                <div className="col-md-12">
                    <nav className="navbar navbar-expand-lg navbar-light bg-light">
                        <div className="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul className="navbar-nav mr-auto">
                                <li className="nav-item">
                                    <Link className="nav-link" to="/login">Login</Link>
                                </li>
                                <li className="nav-item">
                                    <Link className="nav-link" to="/rate">Add Rate</Link>
                                </li>
                                <li className="nav-item">
                                    <Link className="nav-link" to="/statistics">Statistics</Link>
                                </li>
                                <li className="nav-item">
                                    <Link className="nav-link" to="/dashboard">Dashboard</Link>
                                </li>
                                <li className="nav-item">
                                    <Link className="nav-link" to="/withdraw">Withdraw</Link>
                                </li>
                                <li className="nav-item">
                                    <Link className="nav-link" to="/transfer">Transfer</Link>
                                </li>
                                <li className="nav-item">
                                    <Link className="nav-link" to="/logout">Logout</Link>
                                </li>
                            </ul>
                            {this.props.isAuth && (
                                <div>{this.state.UserName}: {this.state.Amount} {this.state.UserCurrency}</div>
                            )}
                        </div>
                    </nav>
                </div>
            </div>
        );
    }
}
