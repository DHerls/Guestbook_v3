@extends('layouts.app')

@section('content')
<div id="member-search" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-on="http://www.w3.org/1999/xhtml">
<div class="container">
    <div class="row">
        <searchbar v-bind:search_columns="{'last_names': 'Last Name','first_names': 'First Name'}"
                   v-model="search_string" v-on:input="search(true)"></searchbar>
    </div>
</div>
    @if(Auth::user()->isAdmin())
        <div class="container" v-cloak>
            <br>
            <div class="row">
                <div class="text-center btn_group">
                    <a href="/users" class="btn btn-primary" role="button">Manage Users</span> </a>
                    <a href="/reports" class="btn btn-primary" role="button">Generate Reports</span> </a>
                    <a href="/members/new" class="btn btn-primary" role="button">Add Member <span class="glyphicon glyphicon-plus"></span> </a>
                </div>
            </div>
            <br>

        </div>
    @else
        <div style="height: 25px; margin: 0; padding: 0;"></div>
    @endif
<div class="container" v-cloak>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover" id="memberTable">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            @if($column['sortable'])
                                <th class="col-sm-12 col-md-{{$column['col_size']}} sortable" v-on:click="set_sort_col('{{$column['key']}}')">
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
                    <template v-for="data in rows">
                        <tr v-bind:id="'member-'+data.id">
                            <td><a v-bind:href="'/members/' + data.id" class="btn btn-info" role="button">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                </a>
                            </td>
                            <td >@{{data.last_names}}</td>
                            <td>@{{data.first_names}}</td>
                            <td>
                                $@{{ data.balance.toFixed(2) }}
                                <button type="button" class="btn btn-default btn-xs" data-toggle="modal"
                                        data-target="#balanceModal" v-on:click="set_member(data)" title="Charge to Account">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </button>
                            </td>
                            <td is="editfield" :dataobj="data" :key_col="'members'" :submit_url="'/members/' + data.id + '/records'" :submit_func="submit"></td>
                            <td>
                                <div class="btn-group">
                                    <a v-bind:href="'/members/' + data.id + '/guests'" class="btn btn-default" role="button">
                                        @{{guest_string(data.guests)}}
                                    </a>
                                    <a v-bind:href="'/members/' + data.id + '/guests/new'" class="btn btn-default" role="button" title="Quick Add">
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                </div>

                            </td>
                            <td>
                                @{{data.note}}
                                <button type="button" class="btn btn-default pull-right" data-toggle="modal"
                                        data-target="#noteModal" v-on:click="set_member(data)" title="Add Note">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </template>

                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 text-center">
                <paginator v-model="page" v-bind:max="max_pages"></paginator>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" id="balanceModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Charge: @{{ currentMember.last_name }}</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="balanceAmount">Amount:</label>
                            <p class="error-msg" v-if="balance.amount_error">@{{ balance.amount_error }}</p>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" id="balanceAmount" class="form-control" v-model="balance.amount">
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <label for="balanceReason">Reason:</label>
                            <p class="error-msg" v-if="balance.reason_error">@{{ balance.reason_error }}</p>
                            <input type="text" id="balanceReason" v-model="balance.reason" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9">
                            <button class="btn btn-primary pull-right" v-on:click="charge()">Charge to Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" id="noteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Add Note: @{{ currentMember.last_name }}</h3>
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
                            <button class="btn btn-primary pull-right" v-on:click="addNote()">Add Note</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Scroll to top" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>
@endsection

@section('scripts')
<script type="text/javascript" src="/js/index.js"></script>
@endsection