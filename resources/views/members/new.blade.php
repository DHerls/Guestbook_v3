@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <multiple-edit v-bind:info="info.adults"></multiple-edit>
            </div>
            <div class="col-sm-12 col-md-6">
                <multiple-edit v-bind:info="info.children"></multiple-edit>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <multiple-edit v-bind:info="info.phones"></multiple-edit>
            </div>
            <div class="col-sm-12 col-md-6">
                <multiple-edit v-bind:info="info.emails"></multiple-edit>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <multiple-edit v-bind:info="address"></multiple-edit>
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
    <script type="text/javascript" src="{{json_decode(\Storage::get('stats.json'),true)['new']['js']}}"></script>
@endsection