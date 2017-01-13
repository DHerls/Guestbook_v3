@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h3 class="pull-left">Current Guests for:
                <a class="link-muted" href="/members/{{$id}}">{{$last_names}}</a>
            </h3>
            <a href="{{Request::url()}}/new" class="btn btn-primary pull-right">Check in Guest <i class="glyphicon glyphicon-plus"></i> </a>
        </div>
        @if(Auth::user()->isAdmin())
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 text-center">
                <datepicker v-model="date1" v-bind:end="date2"></datepicker>
                -
                <datepicker v-model="date2" v-bind:start="date1"></datepicker>
            </div>
        </div>
        @endif
        <br>
        <div class="row" v-cloak>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            @foreach($columns as $column)
                                @if($column['sortable'])
                                    <th class="col-sm-1 col-md-{{$column['col_size']}} sortable" v-on:click="sort('{{$column['key']}}')">
                                        {{$column['display']}}
                                        <span>
                                            <i class="glyphicon glyphicon-triangle-top" v-if="sort_col == '{{$column['key']}}' && sort_dir == 'asc'"></i>
                                            <i class="glyphicon glyphicon-triangle-bottom" v-if="sort_col == '{{$column['key']}}' && sort_dir == 'desc'"></i>
                                        </span>
                                    </th>
                                @else
                                    <th class="col-sm-12 col-md-{{$column['col_size']}}">{{$column['display']}}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in rows">
                            <td>@{{ row.user }}</td>
                            <td>
                                <ul>
                                    <li v-for="adult in row.adults">@{{adult.first_name}} @{{ adult.last_name }} (@{{ adult.city }})</li>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <li v-for="child in row.children">@{{child.first_name}} @{{ child.last_name }} (@{{ child.city }})</li>
                                </ul>
                            </td>
                            <td>$@{{ row.cost }}</td>
                            <td>@{{ row.payment }}</td>
                            <td>@{{ get_time(row.checkIn) }}</td>
                            <td><button class="btn btn-default" v-on:click="delete_row(row.id)"><i class="glyphicon glyphicon-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 text-center">
                <paginator v-model="page" v-bind:max="maxPages"></paginator>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="/js/guests.js"></script>
@endsection