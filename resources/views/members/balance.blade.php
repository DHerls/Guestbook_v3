@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h3 class="pull-left">Balance Records for:
                <a class="link-muted" href="/members/{{$id}}">{{$last_names}}</a>
            </h3>
            <a data-toggle="modal" data-target="#balanceModal" class="btn btn-primary pull-right">Charge Account <i class="glyphicon glyphicon-plus"></i> </a>
        </div>
        <div class="row">
            @if(Auth::user()->isAdmin())
            <div class="col-sm-6 col-sm-offset-3 text-center">
                <datepicker v-model="date1" v-bind:end="date2"></datepicker>
                -
                <datepicker v-model="date2" v-bind:start="date1"></datepicker>
            </div>
            @endif
            <div class="pull-right">
                <h4>Current Balance: $@{{ current_balance }}</h4>
            </div>
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
                    <tr v-for="row in rows">
                        <td>@{{ row.name }}</td>
                        <td>$@{{ row.change_amount }}</td>
                        <td>@{{ row.reason }}</td>
                        <td>@{{ get_time(row.created_at) }}</td>
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
    <div class="modal fade" role="dialog" id="balanceModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Charge to Account</h3>
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
@endsection

@section('scripts')
    <script type="text/javascript" src="{{json_decode(\Storage::get('stats.json'),true)['balance']['js']}}"></script>
@endsection