@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="spacer"></div>
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Change Password</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/change-pass') }}">
                            <div class="container-fluid">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('old_pass') ? ' has-error' : '' }}">
                                    <label for="old_pass" class="control-label">Old Password</label>

                                    <div class="">
                                        <input id="old_pass" type="password" class="form-control" name="old_pass" required>

                                        @if ($errors->has('old_pass'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('old_pass') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('new_pass') ? ' has-error' : '' }}">
                                    <label for="new_pass" class="control-label">New Password</label>

                                    <div class="">
                                        <input id="new_pass" type="password" class="form-control" name="new_pass" required>

                                        @if ($errors->has('new_pass'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('new_pass') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('new_pass_confirmation') ? ' has-error' : '' }}">
                                    <label for="new_pass_confirmation" class="control-label">Confirm Password</label>

                                    <div class="">
                                        <input id="new_pass_confirmation" type="password" class="form-control" name="new_pass_confirmation" required>

                                        @if ($errors->has('new_pass_confirmation'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('new_pass_confirmation') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary pull-right" style="clear: both;">
                                        Change
                                    </button>
                                </div>
                            </div>



                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
