@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div  class="row">
            <form id="delete" action="{{Request::url()}}/delete" method="post">
                {{ csrf_field() }}
                <button id="delete-button" class="btn btn-danger pull-right" onclick="return false;" v-on:click="delete_member">
                    Delete User
                </button>
            </form>
        </div>
    </div>
    <br>

    <div class="container-fluid">
        <div class="row row-eq-height">
            <div v-for="box in info" class="col-xs-12 col-sm-6 col-lg-3">
                <Multiple-Display v-bind:info="box"></Multiple-Display>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <Multiple-Display v-bind:info="address"></Multiple-Display>
            </div>
        </div>
    </div>

    @include('members.records')
@endsection

@section('scripts')
    <script type="text/javascript" src="{{json_decode(\Storage::get('stats.json'),true)['edit']['js']}}"></script>
@endsection