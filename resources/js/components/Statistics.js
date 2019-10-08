import React, {Component} from 'react';
import {Button, Form, FormGroup, Input, Label, Table} from "reactstrap";
import {CSVLink, CSVDownload} from 'react-csv';
import axios from "axios";

export default class Statistics extends Component {
    constructor(props) {
        super(props);
        this.state = {
            formSuccess: false,
            formError: false,
            clients: {},
            stats: [],
        };

        this.tableHeaders = [
            {label: "Transaction ID", key: "transId"},
            {label: "Type", key: "type"},
            {label: "Amount", key: "amount"},
            {label: "Currency", key: "currency"},
            {label: "Date", key: "date"},
            {label: "Amount in USD", key: "usdamount"}
        ];

    }


    componentDidMount() {
        axios.get('api/clients')
            .then(res => {
                this.setState({clients: res.data.data})
            })
    }


    handleSubmit(event) {
        event.preventDefault();

        this.setState({'formError': false});

        const formData = new FormData(event.target);

        let data = {
            "client_email": formData.get("client")
        };

        if (formData.get('date_from')) {
            data.date_from = formData.get('date_from')
        }

        if (formData.get('date_to')) {
            data.date_to = formData.get('date_to')
        }


        axios.post(`api/statistic`, data)
            .then(res => {
                this.setState({stats: res.data.data})
            }).catch(error => {
            this.setState({stats: []})
            this.setState({'formError': error.response.data.error});
        })
    }

    render() {
        return (
            <div>
                <h1>Statistics</h1>
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

                <Form inline onSubmit={this.handleSubmit.bind(this)}>
                    <FormGroup>
                        <Label>Clients</Label>
                        <Input type="select" name="client" id="client">
                            {Object.keys(this.state.clients).map((key) => (
                                <option
                                    key={key}
                                    value={this.state.clients[key].client_email}
                                >{this.state.clients[key].name}</option>
                            ))}
                        </Input>
                    </FormGroup>
                    <FormGroup>
                        <Label>From</Label>
                        <Input
                            type="date"
                            name="date_from"
                            id="date_from"
                        />
                    </FormGroup>
                    <FormGroup>
                        <Label>To</Label>
                        <Input
                            type="date"
                            name="date_to"
                            id="date_to"
                        />
                    </FormGroup>
                    <Button>Submit</Button>
                    <div className="ml-5">
                        <CSVLink data={this.state.stats} headers={this.tableHeaders}>
                            Download CSV
                        </CSVLink>
                    </div>
                </Form>

                <Table>
                    <thead>
                    <tr>
                        {Object.keys(this.tableHeaders).map((key) => (
                            <th key={key}>{this.tableHeaders[key].label}</th>
                        ))}
                    </tr>
                    </thead>
                    <tbody>
                    {Object.keys(this.state.stats).map((key) => (
                        <tr key={key}>
                            <td>{this.state.stats[key].transId}</td>
                            <td>{this.state.stats[key].type}</td>
                            <td>{this.state.stats[key].amount}</td>
                            <td>{this.state.stats[key].currency}</td>
                            <td>{this.state.stats[key].date}</td>
                            <td>{this.state.stats[key].usdamount}</td>
                        </tr>
                    ))}
                    <tr>
                        <td></td>
                        <td></td>
                        <td>{this.state.stats.reduce((total, transaction) => total + parseFloat(transaction.amount), 0)}</td>
                        <td></td>
                        <td></td>
                        <td>{this.state.stats.reduce((totalUsd, transaction) => totalUsd + parseFloat(transaction.usdamount), 0.0)}</td>
                    </tr>
                    </tbody>
                </Table>
            </div>
        );
    }
}
