import React, {Component} from 'react';
import {Button, Form, FormGroup, Input, Label} from "reactstrap";
import axios from "axios";

export default class Withdraw extends Component {
    constructor(props) {
        super(props);

        const TokenType = localStorage.getItem('auth.token_type');
        const AccessToken = localStorage.getItem('auth.access_token');
        const MainWalletId = localStorage.getItem('user.main_wallet_id');

        this.state = {
            formSuccess: false,
            formError: false,
            currencies: {},
            TokenType: TokenType,
            AccessToken: AccessToken,
            MainWalletId: MainWalletId,
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
            })
    }


    handleSubmit(event) {
        event.preventDefault();
        this.setState({'formSuccess': false})
        this.setState({'formError': false})

        const formData = new FormData(event.target);
        let data = JSON.stringify({
            "amount": formData.get("amount"),
            "currency": formData.get("currency")
        });

        const headers = {
            'Content-Type': 'application/json',
            'Authorization': this.state.TokenType + ' ' + this.state.AccessToken
        };

        axios.post(`api/wallet/${this.state.MainWalletId}/withdraw`, data, {
            headers: headers
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
                <h1>Withdraw</h1>
                {this.state.formSuccess && (
                    <div className="alert alert-success" role="alert">
                        Withdraw was success!
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
                        <Input type="number" name="amount" id="amount" required/>
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
                    <Button>Withrdaw</Button>
                </Form>
            </div>
        );
    }
}
