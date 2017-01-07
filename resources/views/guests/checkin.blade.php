@extends('layouts.app')

@section('content')
<div class="container" v-cloak>
    <div class="row">
        <h3 class="pull-left">Check In Guests for:
            <a class="link-muted" href="/members/{{$id}}">{{$last_names}}</a>
        </h3>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12 col-md-6" v-for="box in info">
            <Multiple-Edit v-bind:info="box"></Multiple-Edit>
        </div>
    </div>
    <div class="row">
        <h2 class="text-center">Cost: $@{{cost}}</h2>
    </div>
    <div class="row">

        <div class="col-md-6">
            <h4>Member Signature:
                <button class="btn btn-sm btn-default pull-right" onclick="clear_sig('member')">Clear</button>
            </h4>
            <div class="alert alert-danger" role="alert" v-if="mSigError">
                Member Signature is required
            </div>
            <canvas class="signature" id="memberSig" width="300" height="100"></canvas>
        </div>
        <div class="col-md-6">
            <h4>Guest Signature:
                <button class="btn btn-sm btn-default pull-right" onclick="clear_sig('guest')">Clear</button>
            </h4>
            <div class="alert alert-danger" role="alert" v-if="gSigError">
                Guest Signature is required
            </div>
            <canvas class="signature" id="guestSig" width="300" height="100"></canvas>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="pull-left">
            <vue-radio v-bind:values="paymentMethods" v-on:select="setPayment(arguments[0])" v-bind:default="'account'"></vue-radio>
        </div>
        <div class="pull-right">
            <a class="btn btn-default" href="/">Cancel</a>
            <button class="btn btn-success" v-on:click="submit()" >Submit</button>
        </div>
    </div>
</div>
    <div class="modal fade" role="dialog" id="overrideModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Check-In Error
                </div>
                <div class="modal-body">
                    <div class="containter">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-danger" role="alert">
                                    The following guests have already visited 5 or more times:
                                    <ul>
                                        <li v-for="guest in visits">@{{guest.first_name}} @{{ guest.last_name }} (@{{ guest.city }})</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if(!Auth::user()->isAdmin())
                                <div class="col-sm-5 col-sm-offset-7">
                                    <button type="button" v-on:click="hide_override()" class="btn btn-default">Cancel</button>
                                    <button type="button" v-on:click="request_override()" class="btn btn-danger">Override (Admin)</button>
                                </div>
                            @else
                                <div class="col-sm-4 col-sm-offset-8">
                                    <button type="button" v-on:click="hide_override()" class="btn btn-default">Cancel</button>
                                    <button type="button" v-on:click="override()" class="btn btn-danger">Override</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" role="dialog" id="adminModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Admin Required
            </div>
            <div class="modal-body">
                <div class="containter">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="error-msg" v-if="login_error">@{{ login_error }}</p>
                            <label for="username">Username</label>
                            <input type="text" id="username" class="form-control" v-model="username">
                            <label for="password">Password</label>
                            <input type="password" id="password" class="form-control" v-model="password">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-8">
                            <button type="button" v-on:click="hide_admin()" class="btn btn-default">Cancel</button>
                            <button type="button" v-on:click="override()" class="btn btn-danger">Override</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/checkin.js"></script>
@endsection