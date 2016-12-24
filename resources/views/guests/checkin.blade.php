@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Check In Guests for: {{$last_names}}</h3>
    <br>
    <div class="row">
        <div class="col-xs-12 col-md-6" v-for="box in info">
            <div class="alert alert-danger" role="alert" v-if="box.visits.length > 0">
                The following have already visited 5 or more times:
                <ul>
                    <li v-for="guest in box.visits">@{{guest.first_name}} @{{ guest.last_name }} (@{{ guest.city }})</li>
                </ul>
            </div>
            <Multiple-Edit v-bind:info="box"></Multiple-Edit>
        </div>
    </div>
    <div class="row">
        <h2 class="text-center">Cost: $@{{cost}}</h2>
    </div>
    <div class="row">

        <div class="col-md-6">
            Member Signature:
            <canvas></canvas>
        </div>
        <div class="col-md-6">
            Guest Signature:
        </div>
    </div>
    <div class="row">
        <div class="pull-left">
            <vue-radio v-bind:values="paymentMethods" v-on:select="setPayment(arguments[0])" v-bind:default="'account'"></vue-radio>
        </div>
        <div class="pull-right">
            <a class="btn btn-default" href="/">Cancel</a>
            <button class="btn btn-success" v-on:click="submit" >Submit</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/checkin.js"></script>
@endsection