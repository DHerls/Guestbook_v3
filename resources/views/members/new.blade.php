@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <multiple-edit v-bind:rows="rows.adults" v-bind:title="'Adults'" v-bind:columns="columns.adults"></multiple-edit>
                </div>
                <div class="col-sm-12 col-md-6">
                    <multiple-edit v-bind:rows="rows.children" v-bind:title="'Children'" v-bind:columns="columns.children"></multiple-edit>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <multiple-edit v-bind:rows="rows.phones" v-bind:title="'Phone Numbers'" v-bind:columns="columns.phones"></multiple-edit>
                </div>
                <div class="col-sm-12 col-md-6">
                    <multiple-edit v-bind:rows="rows.emails" v-bind:title="'Email Addresses'" v-bind:columns="columns.emails"></multiple-edit>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="panel panel-info">
                    <div class="panel-heading">Address</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="addr1">Address Line 1</label>
                                <input class="form-control" id="addr1" v-model="address1" requried>
                            </div>
                            <div class="col-md-6">
                                <label for="addr2">Address Line 2</label>
                                <input class="form-control" id="addr2" v-model="address2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="city">City</label>
                                <input class="form-control" id="city" v-model="city" requried>
                            </div>
                            <div class="col-md-4">
                                <label for="state">State</label>
                                <input class="form-control" id="state" v-model="state" requried>
                            </div>
                            <div class="col-md-4">
                                <label for="zip">Zip Code</label>
                                <input class="form-control" id="zip" v-model="zip" type="tel" requried>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="button-group pull-right">
                <a class="btn btn-default" href="/">Cancel</a>
                <button class="btn btn-primary" type="submit" v-on:click="submit">Submit</button>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/new.js"></script>
@endsection