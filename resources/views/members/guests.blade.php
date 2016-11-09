@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <button class="btn btn-primary pull-right">Check in Guest <i class="glyphicon glyphicon-plus"></i> </button>
        </div>
        <div class="row">
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
                        <tr>
                            <edit-field v-bind:data_obj="data_obj"></edit-field>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection