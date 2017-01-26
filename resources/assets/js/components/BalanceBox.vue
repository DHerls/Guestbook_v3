<!--suppress JSUnresolvedVariable -->
<template>
    <div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <a :href="here() + '/balance'" class="link-muted">Balance</a>
                <button type="button" class="btn btn-default pull-right" data-toggle="modal"
                        data-target="#balanceModal" title="Charge Account">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <h4>Current Balance: ${{current_balance}}</h4>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Reason</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="record in records">
                    <td>{{get_date(record.created_at)}}</td>
                    <td>{{get_time(record.created_at)}}</td>
                    <td>{{record.name}}</td>
                    <td>${{record.change_amount}}</td>
                    <td>{{record.reason}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="modal fade" role="dialog" id="balanceModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title">Charge to Account</h3>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="balanceAmount">Amount:</label>
                                <p class="error-msg" v-if="balance.amount_error">{{ balance.amount_error }}</p>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" id="balanceAmount" class="form-control" v-model="balance.amount">
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <label for="balanceReason">Reason:</label>
                                <p class="error-msg" v-if="balance.reason_error">@{{ balance.reason_error }}</p>
                                <input type="text" id="balanceReason" v-model="balance.reason" class="form-control">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-sm-offset-9">
                                <button class="btn btn-primary pull-right" v-on:click="charge()">Charge to Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .table td.fit,
    .table th.fit {
        white-space: nowrap;
        width: 1%;
    }
</style>
<script>
    import {validator} from "../validator";
    export default {
        data(){
            return {
                records: [],
                balance: {
                    amount: 0,
                    reason: "",
                    amount_error: "",
                    reason_error: ""
                }
            }
        },
        props: ['current_balance'],
        methods: {
            here: function () {
                return window.location.href;
            },
            get_time: function (datetime) {
                var options = {
                    hour: "2-digit", minute: "2-digit"
                };
                var date = new Date(datetime);
                date.setMinutes(date.getMinutes() + (360 - date.getTimezoneOffset()))
                return date.toLocaleTimeString('en-us',options);
            },
            get_date: function (datetime) {
                var options = {
                    month:'2-digit', day:'2-digit', year: 'numeric',
                };
                var date = new Date(datetime);
                date.setMinutes(date.getMinutes() + (360 - date.getTimezoneOffset()))
                return date.toLocaleTimeString('en-us',options);
            },
            charge: function () {
                var app = this;

                this.balance.amount_error = validator.validate(this.balance.amount,"required|numeric","Amount");
                this.balance.reason_error = validator.validate(this.balance.reason,"required|string|max:45","Reason");
                if (this.balance.amount_error || this.balance.reason_error) {
                    return;
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: window.location.href + "/balance",
                    dataType: 'json',
                    data: {amount: app.balance.amount, reason: app.balance.reason},
                    success: function(data){
                        app.current_balance = app.current_balance + app.balance.amount;
                        app.balance = {
                            amount: 0,
                            reason: "",
                            amount_error: "",
                            reason_error: ""
                        };
                        app.records.unshift(data);
                        if (app.records.length > 5){
                            app.records.pop();
                        }
                        $("#balanceModal").modal("hide");
                    },
                    error: function(data){
                        console.log(data);
                    }
                });

            },
        },
        created: function(){
            var app = this;
            $.get({
                url: window.location.href + "/balance/quick",
                success: function(data){
                    app.records = data;
                }
            });
        }
    }
</script>