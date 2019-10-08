import React, {Component} from 'react';

export default class Dashboard extends Component {
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
                    <h1>Dashboard</h1>
                </div>
        );
    }
}
