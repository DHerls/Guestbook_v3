@extends('layouts.app')

@section('content')
<div class="container">
    <div>
        <datepicker v-model="date1" v-bind:end="date2"></datepicker>
        <datepicker v-model="date2" v-bind:start="date1"></datepicker>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/vue.datepicker.min.js"></script>
    <link rel="stylesheet" href="/css/vue.datepicker.min.css">
    <script type="text/javascript" src="/js/test.js"></script>
@endsection