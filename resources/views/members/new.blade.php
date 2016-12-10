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
            <div class="col-sm-12">
                <div class="panel panel-info">
                    <div class="panel-heading">Address</div>
                    <div class="panel-body">
                        <div v-for="field in address" v-bind:class="['col-md-' + field.width, field.error ? 'has-error' : '']">
                            <label for="field.key">@{{ field.title }}</label>
                            <input class="form-control" id="field.key" v-model="field.value" requried>
                            <p v-if="field.error" class="error-msg">@{{ field.error }}</p>
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