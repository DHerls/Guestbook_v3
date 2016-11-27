@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <multiple-edit v-bind:title="'Adults'" v-bind:columns="columns.adults"></multiple-edit>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/new.js"></script>
@endsection