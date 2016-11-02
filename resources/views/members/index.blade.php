@extends('layouts.app')

@section('content')
<div class="container" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="row">
        <form action="" autocomplete="off" class="form-horizontal" method="post" accept-charset="utf-8">
            <div class="input-group">
                <input id="member-search" role="search" type="text" class="form-control" @keyup="search" v-model="search_query"/>
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-search"></i>
                </span>
            </div>
        </form>
    </div>
</div>
<div class="spacer"></div>
<div class="container">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover" id="memberTable">
                <thead>
                    <tr>
                        <th class="col-sm-12 col-md-1">Info</th>
                        <th class="col-sm-12 col-md-4 sortable" v-on:click="sort('last_name')">Last Names
                            <span>
                                <i class="glyphicon glyphicon-triangle-top" v-if="sort_col == 'last_name' && sort_dir == 'up'"></i>
                                <i class="glyphicon glyphicon-triangle-bottom" v-if="sort_col == 'last_name' && sort_dir == 'down'"></i>
                            </span>
                        </th>
                        <th class="col-sm-12 col-md-4 sortable" v-on:click="sort('first_name')">First Names
                            <span>
                                <i class="glyphicon glyphicon-triangle-top" v-if="sort_col == 'first_name' && sort_dir == 'up'"></i>
                                <i class="glyphicon glyphicon-triangle-bottom" v-if="sort_col == 'first_name' && sort_dir == 'down'"></i>
                            </span>
                        </th>
                        <th class="col-sm-12 col-md-1 text-center sortable" v-on:click="sort('num_members')">Members
                            <span>
                                <i class="glyphicon glyphicon-triangle-top" v-if="sort_col == 'num_members' && sort_dir == 'up'"></i>
                                <i class="glyphicon glyphicon-triangle-bottom" v-if="sort_col == 'num_members' && sort_dir == 'down'"></i>
                            </span>
                        </th>
                        <th class="col-sm-12 col-md-1 text-center sortable" v-on:click="sort('num_guests')">Guests
                            <span>
                                <i class="glyphicon glyphicon-triangle-top" v-if="sort_col == 'num_guests' && sort_dir == 'up'"></i>
                                <i class="glyphicon glyphicon-triangle-bottom" v-if="sort_col == 'num_guests' && sort_dir == 'down'"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="member in sorted_members">
                        <tr v-bind:id="'member-'+member.id">
                            <td><a v-bind:href="'/members/' + member.id" class="btn btn-info" role="button">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                </a>
                            </td>
                            <td>@{{member.last_name}}</td>
                            <td>@{{member.first_name}}</td>
                            <td v-if="member.editing" v-bind:class="{ 'has-error': member.has_error }">
                                <input type="text" v-model="member.num_members"  class="text-center form-control" @blur="member.submit()" @keyup.enter="member.submit()" @keyup.esc="member.cancel()">
                            </td>
                            <td v-else class="text-center memberCountDisplay" @dblclick="member.edit()">@{{member.num_members}}</td>

                            <td class="text-center">@{{member.num_guests}}</td>
                        </tr>
                    </template>
   `
                </tbody>
            </table>
        </div>
    </div>
</div>
<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Scroll to top" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>
@endsection