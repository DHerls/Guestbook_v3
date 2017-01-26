<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <!-- Styles -->
        <link href="{{url('/css/pdf.css')}}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <div class="content">
                <h1>Guests For {{$record->member->last_names()}}</h1>
                <hr/>
                <div class="containter">
                    <div class="row">
                        <p><strong>Check-In Time: </strong>{{$record->created_at}}</p>
                        <p><strong>Employee: </strong>{{$record->user->name}}</p>
                        <p><strong>Price: </strong>${{$record->price}}</p>
                        <p><strong>Payment Method: </strong>
                            @if($record->payment_method == 'account')
                                Charged to Account
                            @else
                                Paid Cash
                            @endif
                        </p>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-xs-6">
                            Adults:
                            <ul>
                                @foreach($record->guests()->whereType('adult')->get() as $adult)
                                    <li>{{$adult->first_name}} {{$adult->last_name}} ({{$adult->city}})
                                        @if($adult->pivot->free_pass)
                                            <strong>[Free Pass]</strong>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="col-xs-6">
                            Children:
                            <ul>
                                @foreach($record->guests()->whereType('child')->get() as $child)
                                    <li>{{$child->first_name}} {{$child->last_name}} ({{$child->city}})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                            <p>Member Signature</p>
                            <div class="sig">
                                <img src="{{storage_path()}}/app/signatures/{{$record->member_signature}}">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <p>Guest Signature</p>
                            <div class="sig">
                                <img src="{{storage_path()}}/app/signatures/{{$record->guest_signature}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
