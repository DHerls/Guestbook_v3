@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <form action="" autocomplete="off" class="form-horizontal" method="post" accept-charset="utf-8">
            <div class="input-group">
                <input id="member-search" role="search" type="text" class="form-control"/>
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
                        <th class="col-sm-12 col-md-4">Last Names</th>
                        <th class="col-sm-12 col-md-4">First Names</th>
                        <th class="col-sm-12 col-md-1 text-center">Members</th>
                        <th class="col-sm-12 col-md-1 text-center">Guests</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($adults as $adult)
                        <tr id={{$adult['id']}}>
                            <td><a href="/members/{{$adult['id']}}" class="btn btn-info" role="button">
                                    <span class="glyphicon glyphicon-list-alt"></span>
                                </a>
                            </td>
                            <td>{{$adult['first_name']}}</td>
                            <td>{{$adult['last_name']}}</td>
                            <td class="text-center memberCountDisplay">{{$adult['members']}}</td>
                            <td class="text-center">{{$adult['guests']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#memberTable').tablesorter( {sortList: [[1,0]]} );
    </script>
@endsection