@extends('layouts.app')

@section('content')
<div class="content">
    <div class="row">
        <div class="container">
            <div class="table-responsive">
            	<table class="table table-hover">
            		<thead>
            			<tr>
            				<th>Last Names</th>
            				<th>First Names</th>
            				<th class="text-center">Members</th>
            				<th class="text-center">Guests</th>
            			</tr>
            		</thead>
            		<tbody>
                        @foreach($adults as $adult)
                            <tr id={{$adult['id']}}>
                                <td>{{$adult['first_name']}}</td>
                                <td>{{$adult['last_name']}}</td>
                                <td class="text-center">{{$adult['members']}}</td>
                                <td class="text-center">{{$adult['guests']}}</td>
                            </tr>
                        @endforeach
            		</tbody>
            	</table>
            </div>
        </div>
    </div>

</div>
@endsection