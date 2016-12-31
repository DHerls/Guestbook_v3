
<hr>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">Notes</div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>User</th>
                        <th>Note</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($notes as $note)
                        <tr>
                            <td>{{date("m-d-y",strtotime($note->created_at))}}</td>
                            <td>{{date("g:i a",strtotime($note->created_at))}}</td>
                            <td>{{$note->user->name}}</td>
                            <td>{{$note->note}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">Guest Records</div>
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
                            <td>{{$record->payment_method}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">Balance Updates</div>
                <div class="panel-body">
                    <h4>Current Balance: ${{$member->current_balance}}</h4>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($balanceRecords as $record)
                        <tr>
                            <td>{{date("m-d-y",strtotime($record->created_at))}}</td>
                            <td>{{date("g:i a",strtotime($record->created_at))}}</td>
                            <td>{{$record->user->name}}</td>
                            <td>${{$record->change_amount}}</td>
                            <td>{{$record->reason}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
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