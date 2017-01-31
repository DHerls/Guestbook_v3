@extends('layouts.app')

@section('content')
<div class="container">
    <button class="btn btn-primary" v-on:click="show_confirm">Confirm</button>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{json_decode(\Storage::get('stats.json'),true)['test']['js']}}"></script>
@endsection