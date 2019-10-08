import React, {Component} from 'react';
import {Button, Form, FormGroup, Input, Label} from "reactstrap";
import axios from "axios";

export default class Transfer extends Component {
    constructor(props) {
        super(props);

        const TokenType = localStorage.getItem('auth.token_type');
        const AccessToken = localStorage.getItem('auth.access_token');
        const MainWalletId = localStorage.getItem('user.main_wallet_id');

        const headers = {
            'Content-Type': 'application/json',
            'Authorization': TokenType + ' ' + AccessToken
        };

        this.state = {
            formSuccess: false,
            formError: false,
            currencies: {},
            recipients: {},
            authHeaders: headers,
            MainWalletId: MainWalletId,
            AccessToken: AccessToken,
        };

    }

    componentDidMount() {

        const {history} = this.props;
        if (!this.state.AccessToken) {
            history.push('/login')
        }

        axios.get('api/currencies')
            .then(res => {
                this.setState({currencies: res.data.currencies})
            });

        axios.get('api/recipients', {
            headers: this.state.authHeaders
        })
            .then(res => {
                this.setState({recipients: res.data.data})
            })
    }


    handleSubmit(event) {
        event.preventDefault();
        this.setState({'formSuccess': false})
        this.setState({'formError': false})

        const formData = new FormData(event.target);

        const wallet_to_id = formData.get("recipient");

        const data = JSON.stringify({
            "amount": formData.get("amount"),
            "currency": formData.get("currency")
        });

        const headers = {
            'Content-Type': 'application/json',
            'Authorization': this.state.TokenType + ' ' + this.state.AccessToken
        };

        axios.post(`api/wallet/${this.state.MainWalletId}/transfer/${wallet_to_id}`, data, {
            headers: this.state.authHeaders
        })
            .then(res => {
                localStorage.setItem('user.amount', res.data.amount);
                this.setState({'formSuccess': true})
            }).catch(error => {
            this.setState({'formError': error.response.data.error});
        })
    }

    render() {
        return (
            <div>
                <h1>Transfer</h1>
                {this.state.formSuccess && (
                    <div className="alert alert-success" role="alert">
                        Transfer was success!
                    </div>
                )}
                {this.state.formError && (
                    <div className="alert alert-danger" role="alert">
                        {this.state.formError}
                    </div>
                )}
                <Form onSubmit={this.handleSubmit.bind(this)}>
                    <FormGroup>
                        <Label>Amount</Label>
                        <Input type="number" name="amount" id="amount" required />
                    </FormGroup>
                    <FormGroup>
                        <Label>Currency</Label>
                        <Input type="select" name="currency" id="currency">
                            {Object.keys(this.state.currencies).map((key, index) => (
                                <option
                                    key={key}
                                    value={key}
                                >{this.state.currencies[key]}</option>
                            ))}
                        </Input>
                    </FormGroup>
                    <FormGroup>
                        <Label>Recipient</Label>
                        <Input type="select" name="recipient" id="recipient">
                            {Object.keys(this.state.recipients).map((key) => (
                                <option
                                    key={key}
                                    value={this.state.recipients[key].wallet_id}
                                >{this.state.recipients[key].name} ({this.state.recipients[key].client_email})</option>
                            ))}
                        </Input>
                    </FormGroup>
                    <Button>Send</Button>
                </Form>
            </div>
        );
    }
}
