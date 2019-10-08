import React, {Component} from 'react';
import {Button, Form, FormGroup, Label, Input} from 'reactstrap';

import axios from 'axios'


export default class Rate extends Component {

    constructor(props) {
        super(props);
        this.state = {
            formSuccess: false,
            formError: false,
            currencies: {}
        };
    }

    componentDidMount() {
        axios.get('api/currencies')
            .then(res => {
                this.setState({currencies: res.data.currencies})
            })
    }


    handleSubmit(event) {
        this.setState({'formSuccess': false})
        this.setState({'formError': false})
        event.preventDefault();
        const data = new FormData(event.target);

        axios.post('api/rate', data)
            .then(res => {
                this.setState({'formSuccess': true})
            }).catch(error => {
            this.setState({'formError': error.response.data.message})
        });
    }

    render() {
        return (
            <div className="col-md-6 offset-md-3">
                <div className="row">
                    <div className="col-md-12">
                        <h1>Rates</h1>
                        {this.state.formSuccess && (
                            <div className="alert alert-success" role="alert">
                                Rate was added!
                            </div>
                        )}
                        {this.state.formError && (
                            <div className="alert alert-danger" role="alert">
                                {this.state.formError}
                            </div>
                        )}
                        <Form onSubmit={this.handleSubmit.bind(this)}>
                            <FormGroup>
                                <Label>Rate</Label>
                                <Input type="number" name="rate" id="rate" min="0" step="0.01" required />
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
                                <Label>Date</Label>
                                <Input
                                    type="date"
                                    name="date"
                                    id="date"
                                />
                            </FormGroup>
                            <Button>Add Rate</Button>
                        </Form>
                    </div>
                </div>
            </div>
        );
    }
}

