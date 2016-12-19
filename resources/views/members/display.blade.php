@extends('layouts.app')

@section('content')
@if(Auth::user()->isAdmin())
    <div class="container">
        <div class="row">
            <button class="btn btn-danger pull-right" type="submit">Delete User</button>
        </div>
    </div>
    <br>

@endif
<div class="container-fluid">
    <div class="row row-eq-height">
        <div v-for="box in info" class="col-xs-12 col-sm-6 col-lg-3">
            <Multiple-Display v-bind:info="box"></Multiple-Display>
        </div>
    </div>
</div>

<hr>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">Comments</div>
                <div class="panel-body">

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">Balance Updates</div>
                <div class="panel-body">

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">Member Records</div>
                <div class="panel-body">

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">Guest Records</div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
    <script type="text/javascript" src="/js/edit.js"></script>
@endsection