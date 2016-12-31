@extends('layouts.app')

@section('content')
<div class="container-fluid" v-cloak>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">Adults</div>
                <ul class="list-group">
                    @foreach($member->adults as $adult)
                        <li class="list-group-item">{{$adult->first_name}} {{$adult->last_name}}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">Children</div>
                <ul class="list-group">
                    @foreach($member->children as $child)
                        <li class="list-group-item">{{$child->first_name}} {{$child->last_name}}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">Email Addresses</div>
                <ul class="list-group">
                    @foreach($member->emails as $email)
                        <li class="list-group-item">
                            <a href="mailto:{{$email->address}}">{{$email->address}}</a>
                            {{($email->description) ? ("({$email->description})") : ("")}}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">Phone Numbers</div>
                <ul class="list-group">
                    @foreach($member->phones as $phone)
                        <li class="list-group-item">{{$phone->fancyNumber()}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="panel panel-info">
                <div class="panel-heading">Address</div>
                <div class="panel-body text-center">
                    <p>{{$member->address_line_1}}</p>
                    <p>{{$member->address_line_2}}</p>
                    <p>{{$member->city}}, {{$member->state}} {{$member->zip}}</p>
                </div>
            </div>

        </div>
    </div>
</div>

    @include('members.records')
@endsection