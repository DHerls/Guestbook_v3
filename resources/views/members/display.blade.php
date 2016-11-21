@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row row-eq-height">

        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">Adults</div>
                <div class="panel-body">
                    <ul>
                        @foreach($member->adults as $adult)
                            <li>{{$adult->first_name}} {{$adult->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">Children</div>
                <div class="panel-body">
                    <ul>
                        @foreach($member->children as $child)
                            <li>{{$child->first_name}} {{$child->last_name}}</li>
                        @endforeach
                    </ul>
                </div>
             </div>
        </div>



        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">Email Addresses</div>
                <div class="panel-body">
                    <ul>
                        @foreach($member->emails as $email)
                            <li>
                                <a href="mailto:{{$email->address}}">{{$email->address}}</a>
                                {{($email->description) ? ("({$email->description})") : ("")}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-lg-3">
            <div class="panel panel-info">
                <div class="panel-heading">Phone Numbers</div>
                <div class="panel-body">
                    <ul>
                        @foreach($member->phones as $phone)
                            <li>{{$phone->fancyNumber()}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
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