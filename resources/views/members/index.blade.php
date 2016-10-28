@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container">
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