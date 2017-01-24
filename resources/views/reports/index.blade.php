@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">Guest Report</div>
                <div class="panel-body">
                    <div class="text-center">
                        <datepicker v-model="guest_start" v-bind:end="guest_end"></datepicker>
                        -
                        <datepicker v-model="guest_end" v-bind:start="guest_start"></datepicker>

                    </div>
                    <br>
                    <div class="text-center">
                        <p>After pressing, wait for download</p>
                        <button class="btn btn-success" v-on:click="get_guest">Generate Guest Report</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">Member Report</div>
                <div class="panel-body">
                    <div class="text-center">
                        <datepicker v-model="member_start" v-bind:end="member_end"></datepicker>
                        -
                        <datepicker v-model="member_end" v-bind:start="member_start"></datepicker>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/reports.js"></script>
@endsection