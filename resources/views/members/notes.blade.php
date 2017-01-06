@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h3 class="pull-left">Notes for:
                <a class="link-muted" href="/members/{{$id}}">{{$last_names}}</a>
            </h3>
            <a data-toggle="modal" data-target="#noteModal" class="btn btn-primary pull-right">Add Note <i class="glyphicon glyphicon-plus"></i> </a>
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
                                            <i class="glyphicon glyphicon-triangle-top" v-if="sort_col == '{{$column['key']}}' && sort_dir == 'asc'"></i>
                                            <i class="glyphicon glyphicon-triangle-bottom" v-if="sort_col == '{{$column['key']}}' && sort_dir == 'desc'"></i>
                                        </span>
                                </th>
                            @else
                                <th class="col-sm-12 col-md-{{$column['col_size']}}">{{$column['display']}}</th>
                            @endif
                        @endforeach
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in rows">
                        <td>@{{ row.name }}</td>
                        <td>@{{ row.note }}</td>
                        <td>@{{ get_time(row.created_at) }}</td>
                        <td class="fit"><button v-on:click="remove(row.id)" class="btn btn-default btn-sm text-center" title="Remove Note">
                                <i class="glyphicon glyphicon-remove"></i>
                        </button></td>
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
    <div class="modal fade" role="dialog" id="noteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Add Note</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="noteText">Note:</label>
                            <p class="error-msg" v-if="note.error">@{{ note.error }}</p>
                            <input type="text" id="noteText" class="form-control" v-model="note.text">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9">
                            <button class="btn btn-primary pull-right" v-on:click="add_note()">Add Note</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/notes.js"></script>
@endsection