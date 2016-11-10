@extends('layouts.app')

@section('meta')
    <meta name="data-url" content="{{Request::url()}}/data/members">
    <meta name="sort-col" content="last_name">
@endsection

@section('content')
<div id="member-search" xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-on="http://www.w3.org/1999/xhtml">
<div class="container">
    <div class="row">
        <searchbar v-bind:search_columns="{'last_name': 'Last Name','first_name': 'First Name'}"
            v-on:search="search(arguments[0],arguments[1])"
        ></searchbar>
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
                                <th class="col-sm-12 col-md-{{$column['col_size']}} sortable" v-on:click="set_sort_col('{{$column['key']}}')">
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
                    <template v-for="data in sorted_data">
                        <tr v-bind:id="'member-'+data.id">
                            <td><a v-bind:href="'/members/' + data.id" class="btn btn-info" role="button">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                </a>
                            </td>
                            <td >@{{data.last_name}}</td>
                            <td>@{{data.first_name}}</td>
                            <td is="editfield" :text="data.members" :key_col="'num_members'" :submit_url="'/members/' + data.id + '/records'" :submit_func="submit"></td>
                            <td><a v-bind:href="'/members/' + data.id + '/guests'" class="btn btn-default" role="button" style="width: 92px">
                                    @{{data.guest_string}}
                                </a>
                            </td>
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