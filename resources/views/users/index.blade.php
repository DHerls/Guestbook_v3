@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h3 class="pull-left">Users</h3>
            <a href="{{url('/users/new')}}" class="btn btn-primary pull-right">Add User <i class="glyphicon glyphicon-plus"></i> </a>
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
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row, index in rows">
                        <td>@{{ row.name }}</td>
                        <td>@{{ row.username }}</td>
                        <td><button class="btn btn-default" data-toggle="modal" data-target="#passwordModal" v-on:click="user = row.id">Change Password</button></td>
                        <td><input type="checkbox" v-model="row.temp_pass" v-on:change="update_flags(row.id, index)" class="form-control"></td>
                        <td><input type="checkbox" v-model="row.admin" v-on:change="update_flags(row.id, index)" class="form-control"></td>
                        <td><input type="checkbox" v-model="row.disabled" v-on:change="update_flags(row.id, index)" class="form-control"></td>
                        <td><button class="btn btn-default" v-on:click="delete_user(row.id)"><i class="glyphicon glyphicon-trash"></i></button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 text-center">
                <paginator v-model="page" v-bind:max="maxPages"></paginator>
                <br>
                <br>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" id="passwordModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Set Password</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="password">Password:</label>
                            <p class="error-msg" v-if="password_error">@{{ password_error }}</p>
                            <input type="password" id="password" class="form-control" name="password" v-model="new_pass">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9">
                            <button class="btn btn-primary pull-right" v-on:click="set_password()">Set Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{json_decode(\Storage::get('stats.json'),true)['users']['js']}}"></script>
@endsection