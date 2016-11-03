@extends('layouts.app')

@section('content')
<div id="member-search">
<div class="container">
    <div class="row">
        <form action="" autocomplete="off" class="form-horizontal" method="post" accept-charset="utf-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-search"></i>
                </span>
                <input id="member-search" role="search" type="search" class="form-control" @keyup="search" v-model="search_query" @keyup.esc="search_query = ''"/>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Search By<span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li v-on:click="search_col('last_name')"><a href="#">Last Name
                                <span><i class="glyphicon glyphicon-ok" v-if="search_column == 'last_name'"></i> </span>
                            </a>
                        </li>
                        <li v-on:click="search_col('first_name')"><a href="#">First Name
                                <span><i class="glyphicon glyphicon-ok" v-if="search_column == 'first_name'"></i> </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</div>
<div style="height: 25px; margin: 0; padding: 0;"></div>
<div class="container">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover" id="memberTable">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            @if($column['sortable'])
                                <th class="col-sm-12 col-md-{{$column['col_size']}} sortable" v-on:click="sort('{{$column['key']}}')">
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
</div>
<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Scroll to top" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>
@endsection