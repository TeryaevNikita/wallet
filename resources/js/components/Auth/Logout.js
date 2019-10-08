import React, {Component} from 'react';
import axios from 'axios'

export default class Logout extends Component {
    constructor(props) {
        super(props);
        const TokenType = localStorage.getItem('auth.token_type');
        const AccessToken = localStorage.getItem('auth.access_token');

        const headers = {
            'Content-Type': 'application/json',
            'Authorization': TokenType + ' ' + AccessToken
        };

        this.state = {
            authHeaders: headers,
        };
    }

    logout() {
        localStorage.removeItem('auth.token_type');
        localStorage.removeItem('auth.access_token');
        localStorage.removeItem('user.name');
        localStorage.removeItem('user.amount');
        localStorage.removeItem('user.main_wallet_id');
        localStorage.removeItem('user.currency');
    }

    componentDidMount() {
        const {history} = this.props;
        axios.get('api/logout', {
            headers: this.state.headers
        }).then(res => {
        }).catch(error => {
        }).finally(() => {
            this.logout();
            history.push('/login')
        })
    }

    render() {
        return (
            <div>
            </div>
        );
    }
}
