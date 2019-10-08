import React, {Component} from 'react';
import {Button, Form, FormGroup, Label, Input} from 'reactstrap';
import {Link} from "react-router-dom";

import axios from 'axios'

class Login extends Component {

    constructor(props) {
        super(props);
        this.state = {formError: false};
    }

    handleSubmit(event) {
        const {history} = this.props;
        this.setState({'formError': false})
        event.preventDefault();
        const data = new FormData(event.target);

        axios.post('api/login', data)
            .then(res => {
                localStorage.setItem('auth.token_type', res.data.token_type);
                localStorage.setItem('auth.access_token', res.data.access_token);
                localStorage.setItem('user.name', res.data.name);
                localStorage.setItem('user.amount', res.data.amount);
                localStorage.setItem('user.main_wallet_id', res.data.main_wallet_id);
                localStorage.setItem('user.currency', res.data.currency);
                history.push('/dashboard')
            }).catch(error => {
            this.setState({'formError': true});
        });
    }

    render() {
        return (
            <div className="col-md-6 offset-md-3">
                <div className="row">
                    <div className="col-md-12">
                        {this.state.formError && (
                            <div className="alert alert-danger" role="alert">
                                Access Denied!
                            </div>
                        )}
                        <Form onSubmit={this.handleSubmit.bind(this)}>
                            <FormGroup>
                                <Label>Email</Label>
                                <Input type="email" name="email" id="email" required/>
                            </FormGroup>
                            <FormGroup>
                                <Label>Password</Label>
                                <Input type="password" name="password" id="password" required/>
                            </FormGroup>
                            <div className="text-center">
                                <Button color="primary" size="lg">Login</Button>
                            </div>
                        </Form>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12 mt-5">
                        <Link className="btn btn-secondary" to="/registration">Registration</Link>
                    </div>
                </div>
            </div>
        );
    }
}

export default Login;
