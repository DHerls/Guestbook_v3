
<hr>

<div class="container-fluid" v-cloak>
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <note-box></note-box>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <a class="link-muted" href="{{Request::url()}}/guests">Guest Records</a>
                    <a class="btn btn-default pull-right" href="/members/{{$member->id}}/guests/new" title="Check in Guest">
                        <i class="glyphicon glyphicon-plus"></i>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>User</th>
                        <th># of Adults</th>
                        <th># of Children</th>
                        <th>Price</th>
                        <th>Payment Method</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($guestRecords as $record)
                        <tr>
                            <td>{{date("m-d-y",strtotime($record->created_at))}}</td>
                            <td>{{date("g:i a",strtotime($record->created_at))}}</td>
                            <td>{{$record->user->name}}</td>
                            <td>{{$record->adults}}</td>
                            <td>{{$record->children}}</td>
                            <td>${{$record->price}}</td>
                            @if($record->payment_method == "account")
                                <td>Charged to Account</td>
                            @elseif($record->payment_method == "cash")
                                <td>Paid Cash</td>
                            @elseif($record->payment_method == "pass")
                                <td>Free Pass</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <balance-box v-bind:current_balance="{{$member->current_balance}}"></balance-box>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">Member Records</div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>User</th>
                        <th># of Members</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($memberRecords as $record)
                        <tr>
                            <td>{{date("m-d-y",strtotime($record->created_at))}}</td>
                            <td>{{date("g:i a",strtotime($record->created_at))}}</td>
                            <td>{{$record->user->name}}</td>
                            <td>{{$record->num_members}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>