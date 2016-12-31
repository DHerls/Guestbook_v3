@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h3 class="pull-left">Current Guests for:
                <a class="link-muted" href="/members/{{$id}}">{{$last_names}}</a>
            </h3>
            <a href="{{Request::url()}}/new" class="btn btn-primary pull-right">Check in Guest <i class="glyphicon glyphicon-plus"></i> </a>
        </div>
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
                                            <i class="glyphicon glyphicon-triangle-top" v-if="sort_col == '{{$column['key']}}' && sort_dir == 'up'"></i>
                                            <i class="glyphicon glyphicon-triangle-bottom" v-if="sort_col == '{{$column['key']}}' && sort_dir == 'down'"></i>
                                        </span>
                                    </th>
                                @else
                                    <th class="col-sm-12 col-md-{{$column['col_size']}}">{{$column['display']}}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in sorted_rows">
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
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="/js/guests.js"></script>
@endsection