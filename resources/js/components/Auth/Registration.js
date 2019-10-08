import React, {Component} from 'react';
import {Button, Form, FormGroup, Label, Input} from 'reactstrap';
import axios from 'axios'

class Registration extends Component {
    constructor(props) {
        super(props);
        this.state = {currencies: {}};
    }

    componentDidMount() {
        axios.get('api/currencies')
            .then(res => {
                this.setState({currencies: res.data.currencies})
            })
    }

    handleSubmit(event) {
        const {history} = this.props;
        event.preventDefault();
        const data = new FormData(event.target);

        axios.post('api/register', data)
            .then(res => {
                history.push('/login')
            })
    }


    render() {
        return (
            <div className="col-md-6 offset-md-3">
                <div className="row">
                    <div className="col-md-12">
                        <Form onSubmit={this.handleSubmit.bind(this)}>
                            <FormGroup>
                                <Label>Name</Label>
                                <Input type="text" name="name" id="name"/>
                            </FormGroup>
                            <FormGroup>
                                <Label>Email</Label>
                                <Input type="email" name="email" id="email"/>
                            </FormGroup>
                            <FormGroup>
                                <Label>Password</Label>
                                <Input type="password" name="password" id="password"/>
                            </FormGroup>
                            <FormGroup>
                                <Label>Country</Label>
                                <Input type="text" name="country" id="country"/>
                            </FormGroup>
                            <FormGroup>
                                <Label>City</Label>
                                <Input type="text" name="city" id="city"/>
                            </FormGroup>
                            <FormGroup>
                                <Label>Currency</Label>
                                <Input type="select" name="wallet_cur" id="wallet_cur">
                                    {Object.keys(this.state.currencies).map((key, index) => (
                                        <option key={key} value={key}>{this.state.currencies[key]}</option>
                                    ))}
                                </Input>
                            </FormGroup>
                            <div className="text-center">
                                <Button color="primary" type="Submit">Registration</Button>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        );
    }
}

export default Registration;
